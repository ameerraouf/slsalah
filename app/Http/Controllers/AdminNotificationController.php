<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class AdminNotificationController extends SuperAdminController
{
    public function index()
    {
        $notifications = $this->user->notifications()->latest()->get();
        return view('super-admin.admin_notification', compact('notifications'));
    }

    public function readNotification($notification)
    {
        Notification::query()->find($notification)->update(['read_at' => now()]);

    }
}
