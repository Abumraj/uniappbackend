<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Audio;
use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Resources\videoResource;

class VideoController extends Controller
{

    public function courseVideo($id)
    {
        $videos = Video::where('chapter_id',$id)->get();
        foreach ($videos as $video) {
              return videoResource::collection($videos);
            }
    }
    public function courseAudio($id)
    {
        $videos = Audio::where('chapter_id',$id)->get();
        foreach ($videos as $video) {
              return videoResource::collection($videos);
            }
    }




}
