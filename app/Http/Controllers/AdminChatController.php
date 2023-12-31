<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Carbon\Carbon;
use http\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;

class AdminChatController extends SuperAdminController
{
    public function index()
    {
        $adminPhoto = User::query()->where('super_admin', 1)->first()->photo;
        if($adminPhoto) {
            $adminPhoto = url('uploads/'. $adminPhoto);
        }else{
            $adminPhoto = url('/'. env('DEFAULT_PHOTO')??"");
        }


        $photo = auth()->user()->photo;

        if($photo){
            $userPhoto = url('uploads/' . $photo);
        }else{
            $userPhoto = url('/'. env('DEFAULT_PHOTO')??"");
        }


        $chats = Chat::query()
            ->latest()
            ->where('receiver_id', auth()->id())

            ->get()
            ->unique('chat_id');


        return view('super-admin.chat.index', compact('chats', 'userPhoto', 'adminPhoto'));
    }

    public function getChat(Request $request)
    {

        $messages = Chat::query()->where('chat_id', $request->input('chatId'))->get();

        foreach ($messages as $message){
            $message->update(['admin_read_at' => now()]);
        }

        $chatClosed= false;

        if($messages->first()){
            if($messages->first()->is_open == 0 ){
                $chatClosed = true;
            }
        }

        $adminPhoto = User::query()->where('super_admin', 1)->first()->photo;
        if($adminPhoto) {
            $adminPhoto = url('uploads/'. $adminPhoto);
        }else{
            $adminPhoto = url('/'. env('DEFAULT_PHOTO')??"");
        }

        $user = User::query()->find($messages->first()->sender_id);

        if($user->photo){
            $userPhoto = url('uploads/' . $user->photo);
        }else{
            $userPhoto =url('/'. env('DEFAULT_PHOTO')??"");
        }

        $messagesCount = Chat::query()
            ->where('receiver_id' , auth()->id())
            ->where('admin_read_at', null)
            ->count();

        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            config('broadcasting.connections.pusher.options')
        );


        $pusher->trigger('count-chat', 'count-chat', ['count' => $messagesCount]);

        return response()->json(['data' => $messages, 'userPhoto' => $userPhoto,'chatClosed' => $chatClosed,  'adminPhoto' => $adminPhoto]);
    }

    public function send(Request $request)
    {
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            config('broadcasting.connections.pusher.options')
        );

        $admin = User::query()->where('super_admin' , 1)->first();

        if($request->has('file') && $request->input('file') != "undefined") {
            $file = $request->file('file');

            $filePath = $file->store("media", "uploads");
        }
        $audioPath ='';

        if($request->has('audio') && $request->input('audio') !="undefined" ) {
            $file = $request->file('audio');
            $audioPath = $file->store("media", "uploads");
        }


        $row = Chat::query()->where('chat_id', $request->input('user_id'))->first();
        $chat = Chat::query()->where('receiver_id', $row->sender_id)->where('is_open', 1)->first();


        $message = Chat::query()->create([
            'chat_id' =>$request->input('user_id'),
            'message' => $request->input('message')??null,
            'admin_read_at' => now(),
            'file' => $filePath??null,
            'sender_id' => $admin->id,
            'receiver_id' => $row->sender_id,
            'is_open' => 1,
            'user_read_at' => null,
            'audio' => $audioPath??null
        ]);

        $this->updateCountOfMessages($row->sender_id, $admin->id, $request->input('user_id'));

        $pusher->trigger('chat-channel', 'new-message', ['message' => $message]);
        return response()->json(['data' => $message]);
    }
    public function updateCountOfMessages($userId, $adminId, $chatId)
    {
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            config('broadcasting.connections.pusher.options')
        );

        $messagesCount = Chat::query()
            ->where('receiver_id' , $userId)
            ->where('user_read_at', null)
            ->count();

        $adminMessages = Chat::query()
            ->where('chat_id', $chatId)
            ->where('admin_read_at', null)
            ->get();


        foreach ($adminMessages as $adminMessage){
            $adminMessage->update(['admin_read_at' => now()]);
        }

        $adminMessages = Chat::query()->where('sender_id', $adminId)
            ->where('admin_read_at',null)
            ->count();

        $pusher->trigger('user-count-chat', 'user-count-chat', ['count' => $messagesCount]);
        $pusher->trigger('count-chat', 'count-chat', ['count' => $adminMessages]);

    }

    public function disableChat(Request $request)
    {
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            config('broadcasting.connections.pusher.options')
        );

        $chats = Chat::query()->where('chat_id', $request->chatId)->update(['is_open' => 0]);
        $pusher->trigger('reload-page', 'reload-page', ['message' => 'disabled']);
        return response()->json(['data' => []], 200);
    }
}
