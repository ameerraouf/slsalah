<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class UserNotificationController extends Controller
{
    public function index()
    {
        $notifications = [];
        return view('user_notification', compact('notifications'));
    }
    public function readNotification($notification)
    {
        Notification::query()->find($notification)->update(['read_at' => now()]);
    }
}
