<?php

namespace App\Http\Controllers;

use App\Models\Subscribe;
use App\Models\Workspace;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    //


    public function index(){
        $workspaces = Workspace::with(['user', 'plan'])->get();

        return view('super-admin.subscription.list', compact('workspaces'));
    }

    public function showAll()
    {
        $workspaces = Subscribe::query()->latest()->get();

        return view('super-admin.subscription.index', compact('workspaces'));
    }

}
