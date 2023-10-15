<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index()
    {
        return view('super-admin.admin_notification');
    }
}
