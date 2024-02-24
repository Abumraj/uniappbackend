<?php

namespace App\Http\Controllers\web\tutor;
use App\Chapter;
use App\Course;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ChapterController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['role:Tutor']);
    }

    // public function index()
    // {
    //     // $paginate_count = 100;


    //     return view('director.chapter.index', compact('courses'));
    // }
    public function index($course_id = '', $tutor_role = '', Request $request)
    {
        $id = Auth::user()->id;
                 if($tutor_role == 'lead-tutor'){
                     $chapters = Course::find($course_id)->chapters;
                   }else{
                    $chapters = DB::table('chapters')
                    ->select('chapters.*' )
                    ->where('chapters.course_id', $course_id)
                    ->where('chapters.user_id', $id)->orderBy('imageUrl')->get();
                   }
        return view('adminLte.tutor.chapter.index', compact('chapters', 'tutor_role'));
    }


}
