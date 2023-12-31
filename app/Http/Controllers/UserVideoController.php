<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class UserVideoController extends BaseController
{
    public function index()
    {
        $videos = Video::query()->where('isActive', 1)->latest()->get();

        return view('video.user_video', compact('videos'));
    }
    public function show(Video $video)
    {
        return  view('video.show', compact('video'));
    }
}
