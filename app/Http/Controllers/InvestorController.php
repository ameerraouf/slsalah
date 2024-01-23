<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestorController extends Controller
{
    public function index()
    {
        return view('investor.index');
    }
    public function news()
    {
        return view('investor.news.index');
    }
    public function profile()
    {
        $user = Auth::guard('investor')->user();
        $available_languages = User::$available_languages;



        return view("investor.profile.profile" ,compact('user','available_languages'));
    }

}
