<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use http\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class UserChatController extends BaseController
{
    public $userPhoto;
    public $adminPhoto;
    public function __construct()
    {
        $this->adminPhoto = User::query()->where('super_admin', 1)->first()->photo;
        if($this->adminPhoto) {
            $this->adminPhoto = url('uploads/'. $this->adminPhoto);
        }else{
            $this->adminPhoto = url('/'. env('DEFAULT_PHOTO')??"");
        }

        if(isset( $this->user->photo)){
            $this->userPhoto = url('uploads/' .  $this->user->photo);
        }else{

            $this->userPhoto = url('/'. env('DEFAULT_PHOTO')??"");
        }
    }

    public function index()
    {
        $chats = Chat::query()
            ->where('is_open', false)
            ->where('sender_id', auth()->id())
            ->get()
            ->unique('chat_id');

        $chatClosed = false;

        $currentChat = Chat::query()
            ->where('sender_id', auth()->id())
            ->where('is_open', 1)
            ->latest()
            ->first();

        if ($currentChat) {
            if($currentChat->is_open == 1){
                $messages = Chat::query()
                    ->where('chat_id', $currentChat->chat_id)
                    ->get();

                if($messages->first()->is_open == 0){
                    $chatClosed = true;
                }
            }
        } else {
            $messages = [];
        }

        $adminPhoto = $this->adminPhoto;
        $userPhoto = $this->userPhoto;

        return view('chat.index', compact('chats','currentChat', 'chatClosed' , 'messages', 'adminPhoto', 'userPhoto'));
    }


    public function send(Request $request)
    {
        $date = Carbon::now();
        $date->setTimezone('Africa/Cairo');

        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            config('broadcasting.connections.pusher.options')
        );

        $admin = User::query()->where('super_admin' , 1)->first();

        $filePath = '';
        if($request->has('file') && $request->input('file') != "undefined") {
            $file = $request->file('file');

            $filePath = $file->store("media", "uploads");
        }

        $oldChat = Chat::query()->where('is_open', 1)
            ->where('sender_id', auth()->id())
            ->latest()
            ->first();

        if($oldChat){

            if($oldChat->is_open == 1){
                $chat = Chat::query()->create([
                    'is_open' => 1,
                    'chat_id' => $oldChat->chat_id,
                    'message' => $request->input('message'),
                    'user_read_at' => now(),
                    'file' => $filePath??null,
                    'sender_id' => auth()->id(),
                    'receiver_id' => $admin->id,
                ]);

                $userPhoto = $this->userPhoto;
                $pusher->trigger('chat-channel', 'new-message', ['chat' => $chat, 'userPhoto' => $userPhoto]);

                $messagesCount = Chat::query()
                    ->where('receiver_id' ,1)

                    ->where('admin_read_at', null)->count();

                $pusher->trigger('count-chat', 'count-chat', ['count' => $messagesCount]);

                return response()->json(['data' => $chat, 'userPhoto' => $userPhoto]);
            }
            return  response()->json(['data' => 'تم انهاء الشات من قبل الادمن']);

        }else{
            $chat = Chat::query()->create([
                'is_open' => 1,
                'message' => $request->input('message'),
                'user_read_at' => now(),
                'file' => $filePath??null,
                'sender_id' => auth()->id(),
                'receiver_id' => $admin->id,
            ]);
            $chat->chat_id = $chat->id;
            $chat->save();
            $userPhoto = $this->userPhoto;
            $pusher->trigger('reload-page-admin', 'reload-page-admin', ['message' => 'disabled']);
            $pusher->trigger('chat-channel', 'new-message', ['chat' => $chat, 'userPhoto' => $userPhoto]);

            $messagesCount = Chat::query()
                ->where('receiver_id' ,1)
                ->where('admin_read_at', null)
                ->count();


            $pusher->trigger('count-chat', 'count-chat', ['count' => $messagesCount]);

            return response()->json(['data' => $chat, 'userPhoto' => $userPhoto]);
        }
    }
}
