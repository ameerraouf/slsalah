<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class UserNotificationController extends BaseController
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->get();

        return view('user_notification', compact('notifications'));
    }
    public function readNotification($notification)
    {
        Notification::query()->find($notification)->update(['read_at' => now()]);
    }
}
