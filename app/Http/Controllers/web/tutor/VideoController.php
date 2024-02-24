<?php

namespace App\Http\Controllers\web\tutor;
use App\Video;
use App\Course;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Dawson\Youtube;


class VideoController extends Controller
{
    //



    public function index($chapter_id = '')
    {


                    $videos = DB::table('videos')
                    ->select( 'id', 'videos.name as title', 'videos.description as description', 'videos.imageurl as thumbnail' )
                    ->where('chapter_id', $chapter_id)->get();
// dd($videos);
        return view('adminLte.tutor.video.index', compact('videos', 'chapter_id'));
    }


    public function uploadVideo(Request $request)
    {
        dd($request->hasFile('video'));
        if ($request->hasFile('video')) {
            $video = Youtube::upload($request->file('video')->getPathName(), [
                'title'       => $request->input('title'),
                'description' => $request->input('description')
            ]);

          if($video){

              $databaseVideo = new Video();
              $databaseVideo->name = $request->input('title');
              $databaseVideo->chapter_id = $request->input('chapter_id');
              $databaseVideo->description = $request->input('description');
              $databaseVideo->imageurl = $video->getThumbnailUrl();
              $databaseVideo->videourl = $video->getVideoId();
              $databaseVideo->save();
         $success_message = 'video uploaded succesfully';

          } else{
         $success_message = 'An error occurred';

          }
            # code...
        }

    //     else{
    //         $databaseVideo = new Video();
    //         $databaseVideo->name = $request->input('title');
    //         $databaseVideo->chapter_id = $request->input('chapter_id');
    //         $databaseVideo->description = $request->input('description');
    //         $databaseVideo->save();
    //    $success_message = 'video uploaded succesfully';
    //     }

      $route = "tquestion";



        return $this->return_output('flash', 'success', $success_message, $route, '200');
    }

    public function updateVideo( $id = ''){

        $video = Video::find($id)->select('id', 'name', 'description')->get();

        return view('adminLte.tutor.video.form', compact('video'));


    }
    public function getForm($chapter_id ='')
    {
        //dd($chapter_id);
        return view('adminLte.tutor.video.form1', compact('chapter_id'));
    }
    public function deleteVideo( $id = ''){

        $video = Video::find($id);
        $videoId = $video->videourl;

        Youtube::delete($videoId);
        Video::destroy($id);
        return $this->return_output('flash', 'success', 'Video deleted successfully', '/chapter', '200');


    }


}
