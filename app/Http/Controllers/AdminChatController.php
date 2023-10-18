<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;

class AdminChatController extends Controller
{
    public function index()
    {
        $chats = Chat::whereIn('id', function ($query) {
            $query->select(DB::raw('MAX(id)'))
                ->from('chats')
                ->where('receiver_id', 1)
                ->groupBy('sender_id');
        })
            ->get();
        ;
        return view('super-admin.chat.index', compact('chats'));
    }

    public function getChat(Request $request)
    {
        $chats = Chat::where('sender_id', $request->input('chatId'))->get();
        $adminPhoto = User::query()->where('super_admin', 1)->first()->photo;
        if($adminPhoto) {
            $adminPhoto = url('uploads/'. $adminPhoto);
        }else{
            $adminPhoto = env('DEFAULT_PHOTO')??"";
        }

        $photo = auth()->user()->photo;

        if($photo){
            $userPhoto = url('uploads/' . $photo);
        }else{
            $userPhoto = env('DEFAULT_PHOTO')??"";
        }

        return response()->json(['data' => $chats, 'userPhoto' => $userPhoto, 'adminPhoto' => $adminPhoto]);
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
        $chat = Chat::query()->create(
            [
                'sender_id' => $admin->id,
                'message' => $request->input('message'),
                'user_read_at' => now(),
                'receiver_id' => 71,
            ]
        );
        $chat['created_at'] = Carbon::parse($chat->created_at)->format('Y-m-d h:m:s');
        $pusher->trigger('chat-channel', 'new-message', ['chat' => $chat]);
    }
}
