<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Http\Resources\questionResource as QuestionResource;
use App\Http\Resources\OldQuestionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Question;


class QuestionController extends Controller
{


    // public function index()
    // {
    //     $questions = DB::table('questions')
    //                  ->join('answers', 'questions.id', '=', 'answers.question_id')
    //                  ->join('options', 'questions.id', '=', 'options.question_id')
    //                  ->select('questions.*', 'answers.answer', 'options.option2', 'options.option3', 'options.option4')
    //                  ->get();

    //                  return QuestionResource::collection($questions);

    // }








    public function courseQuestion()
    {

    $chaps = collect();
        $id = auth()->user()->id;
        $registeredCourses = User:: find($id)->courses;
        foreach ($registeredCourses as $registeredCourse) {
            $questionsByCourses = Question::where('course_id', $registeredCourse->id)->get();


           foreach($questionsByCourses as $questionsByCourse){


            $chaps->push($questionsByCourse);

            }
               }
            return QuestionResource::collection($chaps);

    }

     public function questions($id)
     {
        $questionsByCourse = DB::table('questions')->where('chapter_id', $id)
        ->join('answers', 'questions.id', '=', 'answers.question_id')
        ->join('options', 'questions.id', '=', 'options.question_id')
        ->select('questions.*', 'answers.answer', 'answers.solution', 'options.option2', 'options.option3', 'options.option4')->orderBy('questions.id')
        ->get();

        return OldQuestionResource::collection($questionsByCourse);

     }
         public function chapters($id)
         {
        $questionsByCourse = DB::table('chapters')->where('course_id', $id)->select('chapters.*')->get();

        return $questionsByCourse;

         }

}
