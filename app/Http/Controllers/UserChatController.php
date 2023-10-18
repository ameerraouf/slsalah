<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Pusher\Pusher;

class UserChatController extends Controller
{
    public function index()
    {
        $messages = Chat::query()->where('sender_id', auth()->id())->orWhere('receiver_id', auth()->id())->get();
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
        return view('chat.index', compact('messages', 'adminPhoto', 'userPhoto'));
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
                'sender_id' => auth()->id(),
                'message' => $request->input('message'),
                'user_read_at' => now(),
                'receiver_id' => $admin->id,
            ]
        );


        $pusher->trigger('chat-channel', 'new-message', ['chat' => $chat]);
        return response()->json(['data' => $chat]);
    }
}
