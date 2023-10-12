<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos = Video::all();
        return view('video.index', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('video.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'url' => 'required| starts_with:https://www.youtube.com/',
            'description' => 'required',
            'time' => 'required',
            'isActive' => 'required',
        ], [
            'url.starts_with' => 'يجب ان يكون الحقل رابط من موقع اليوتيوب '
        ]);
        Video::create([
            'name' => $request->name,
            'url' => $request->url,
            'description' => $request->description,
            'time' => $request->time,
            'isActive' => ($request->isActive == 1 ? 1 :0 ),
        ]);
        return redirect()->route('video.index')->with('msg', 'تم اضافة الفيديو');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $video = Video::findorFail($id);
        return view('video.edit', compact('video'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'url' => 'required| starts_with:https://www.youtube.com/',
            'description' => 'required',
            'time' => 'required',
            'isActive' => 'required',
        ], [
            'url.starts_with' => 'يجب ان يكون الحقل رابط من موقع اليوتيوب '
        ]);
        $video = Video::findorFail($id);
        $video->name = $request->name;
        $video->url = $request->url;
        $video->description = $request->description;
        $video->time = $request->time;
        $video->isActive = $request->isActive;
        $video->save();
        return redirect()->route('video.index')->with('msg', 'تم تعديل الفيديو');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $video = Video::findorFail($id);
        $video->delete();
        return redirect()->back()->with('msg', 'تم حذف الفيديو');
    }
}
