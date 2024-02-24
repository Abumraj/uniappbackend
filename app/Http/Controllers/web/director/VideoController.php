<?php

namespace App\Http\Controllers\web\director;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\videoResource;
use App\Imports\AudiosImport;
use App\Imports\VideosImport;
use App\Models\Audio;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class VideoController extends Controller
{

//  public function __construct()
//     {
//         $this->middleware(['role:Director']);
//     }

    public function index($chapter_id ='')
    {
       $question = Video::where('chapter_id', $chapter_id)->get();


       return videoResource::collection($question);
    }
    public function audioIndex($chapter_id ='')
    {
       $question = Audio::where('chapter_id', $chapter_id)->get();


       return videoResource::collection($question);
    }



    public function uploadVideo(Request $request)
    {
        $course_id = Chapter::find($request->chapter_id)->id;
     $course = Course::find($course_id);
       $questions = Excel::import(new VideosImport, $request->file('videos'), null, \Maatwebsite\Excel\Excel::XLSX);
        if ($questions) {
            $success_message = 'Videos uploaded successfully';
            Notification::pushNotification($course->coursecode,$course->coursecode, "More Videos added. Kindly refresh to see new changes.");
        }else {
            $success_message = 'An error occurred';
        }
            return response()->json($success_message);

    }
    public function uploadAudio(Request $request)
    {
        $course_id = Chapter::find($request->chapter_id)->id;
        $course = Course::find($course_id);

       $questions = Excel::import(new AudiosImport, $request->file('audios'), null, \Maatwebsite\Excel\Excel::XLSX);
        if ($questions) {
            $success_message = 'Audios uploaded successfully';
            Notification::pushNotification($course->coursecode,$course->coursecode, "More Audios added. Kindly refresh to see new changes.");
        }else {
            $success_message = 'An error occurred';
        }
            return response()->json($success_message);

    }


    public function deleteVideo($questionId)
    {
        Video::destroy($questionId);
        return response()->json("Video deleted successfully");
    }
    public function deleteAudio($questionId)
    {
        Audio::destroy($questionId);
        return response()->json("Video deleted successfully");
    }





    //    public function index($chapter_id = '')
    // {
    //                 $videos = DB::table('videos')
    //                 ->select( 'id', 'videos.name as title', 'videos.description as description', 'videos.videourl as thumbnail' )
    //                 ->where('chapter_id', $chapter_id)->get();
    //     return view('director.video.index', compact('videos', 'chapter_id'));
    // }


    public function getForm($course_id='', $chapter_id = '', Request $request)
    {

        // $courses = Course::all();
     // dd($chapter_id);
        $tutors = Course::find($course_id)->users()->get();
        //dd($tutors);

         $course_id =  $course_id;
        if($chapter_id) {
            $chapter = Chapter::find($chapter_id);

        }else{
            $chapter = $this->getColumnTable('chapters');
        }
       // dd($chapter);
        return view('director.chapter.form', compact('chapter','tutors', 'course_id'));
    }

    public function saveVideo(Request $request)
    {
        $question_id = $request->id;

        $validation_rules = [
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:5000',
            'quesnum' => 'required|integer|max:200',
            'questime' => 'required|integer|max:200'
    ];

        $validator = Validator::make($request->all(),$validation_rules);

        // Stop if validation fails
        if ($question_id != null) {
            $video = Video::find($question_id);
        $success_message = 'Video updated successfully';
    } else {
        $video = new Video();
     $success_message = 'Video added successfully';
    }

        $chapter_id = $request->chapter_id;
        $video->chapter_id = $chapter_id;
        $video->name = $request->name;
        $video->description = $request->description;
        $video->url = $request->url;
        $video->save();

        return response()->json($success_message);
    }
    public function saveAudio(Request $request)
    {
        $question_id = $request->id;

        $validation_rules = [
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:5000',
            'quesnum' => 'required|integer|max:200',
            'questime' => 'required|integer|max:200'
    ];

        $validator = Validator::make($request->all(),$validation_rules);

        // Stop if validation fails
        if ($question_id != null) {
            $video = Audio::find($question_id);
        $success_message = 'Audio updated successfully';
    } else {
        $video = new Audio();
     $success_message = 'Audio added successfully';
    }

        $chapter_id = $request->chapter_id;
        $video->chapter_id = $chapter_id;
        $video->name = $request->name;
        $video->description = $request->description;
        $video->url = $request->url;
        $video->save();

        return response()->json($success_message);
    }




    // public function deleteVideo($chapter_id)
    // {
    //     Chapter::destroy($chapter_id);
    //     return $this->return_output('flash', 'success', 'Category deleted successfully', '/chapter', '200');
    // }
}
