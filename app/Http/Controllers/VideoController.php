<?php

namespace App\Http\Controllers;

use App\Jobs\ConvertVideoJob;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('videos.uploader');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $userId = auth()->user()->id;
        $request->validate(['title' => 'required',
            'image' => 'image|required',
            'video' => 'required']);
        $videoFile = $request->file('video');
        $imageFile = $request->file('image');
        $imageName = $imageFile->hashName();
        $videoName = $videoFile->hashName();
        $image = ImageManager::usingDriver(Driver::class)
            ->decode($request->image)->resize(320, 180);

        Storage::put($imageName, $image->encodeUsingFormat(Format::JPEG));
        $request->video->storeAs('/', $videoName, 'public');

        $video = Video::create([
            'disk' => 'public',
            'video_path' => $videoName,
            'image_path' => $imageName,
            'title' => $request->title,
            'user_id' => $userId,

        ]);
        ConvertVideoJob::dispatch($video);

        return back()->with('success', __('Your Video Converted Successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
