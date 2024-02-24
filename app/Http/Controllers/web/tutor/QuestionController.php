<?php

namespace App\Http\Controllers\web\tutor;

use App\Chapter;
use App\Course;
use App\Answer;
use App\Option;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\QuestionImport;
use App\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
class QuestionController extends Controller
{

public function __construct()
    {
        $this->middleware(['role:Tutor|Director-of-Undergraduate-Studies|Director-of-PostUtme|Director-of-Remedial-Studies']);
    }





    public function index($chapter_id ='', $course_id ='')
    {
        $paginate_count = 250;
        $arr = explode(',', $chapter_id);
         $chapter_id = implode(',',array_slice($arr, 0, 1));
         $course_id = implode(',',array_slice($arr, 1, 1));
        // dd($chapter_id, $course_id);


       $questions =     DB::table('questions')
       ->leftJoin('answers', 'answers.question_id', '=', 'questions.id')
       ->leftJoin('options', 'options.question_id', '=', 'questions.id')
       ->where('questions.chapter_id', $chapter_id)

       ->select('questions.*', 'answers.answer as answer', 'answers.solution as solution', 'options.option2 as option2' , 'options.option3 as option3', 'options.option4 as option4')
       ->orderBy('questions.id')->paginate($paginate_count);


        return view('adminLte.tutor.question.index', compact('questions','course_id', 'chapter_id'));
    }

    public function getForm($question_id='', Request $request)
    {
        if($question_id) {
            $question = DB::table('questions')
            ->leftJoin('answers', 'answers.question_id', '=', 'questions.id')
            ->leftJoin('options', 'options.question_id', '=', 'questions.id')
            ->where('questions.id', $question_id)
            ->select('questions.*', 'answers.answer as answer', 'answers.solution as solution', 'options.option2 as option2' , 'options.option3 as option3', 'options.option4 as option4')->get();
        }else{
            $question = $this->getColumnTable('questions');
        }
       // dd($question);
        return view('adminLte.tutor.question.form', compact('question'));
    }
    public function getForm1($course_id ='', $chapter_id ='')
    {
        //dd($chapter_id);
        return view('adminLte.tutor.question.form1', compact('course_id', 'chapter_id'));
    }

    public function saveQuestion(Request $request)
    {
        // echo '<pre>';print_r($_POST);exit;
        $question_id = $request->input('question_id');
        //$url = $request->input('url');
        $question_id = intval($question_id);
//dd($question_id);
        $validation_rules = [
            //'name' => 'required|string|max:50',
    ];

        $validator = Validator::make($request->all(),$validation_rules);

        // Stop if validation fails
        if ($validator->fails()) {
            return $this->return_output('error', 'error', $validator, 'back', '422');
        }

        if ($question_id) {
            $question = Question::find($question_id);
        $question->question = $request->input('question');
        $question->course_id = $request->input('course_id');
        $question->chapter_id = $request->input('chapter_id');
        $question->save();
        $success_message = 'Question updated successfully';
    } else {
        $question = new Question();
        $question->question = $request->input('question');
        $question->course_id = $request->input('course_id');
        //dd($request->input('chapter_id'));
        $question->chapter_id = $request->input('chapter_id');
            $question->save();
            $questions = Question::where('question', $question->question)->get();
            $question_id = $questions[0]->id;
            $success_message = 'question added successfully';
        }

        $option = Option::where('question_id', $question_id)->get();
        //dd($option->toArray());
        if ($option->count()) {
            # code...
            $option = Option::find($option[0]->id);
        } else{
            $option = new Option();
            $option->question_id = $question_id;
        }
        $answer = Answer::where('question_id', $question_id)->get();
        if ($answer->count()) {
            # code...
            $answer = Answer::find($answer[0]->id);
        } else{
            $answer = new Answer();
            $answer->question_id = $question_id;
        }
        //dd($answer->id, $option->id);
        $option->option2 = $request->input('option2');
        $option->option3 = $request->input('option3');
        $option->option4 = $request->input('option4');
        $answer->answer = $request->input('answer');
        $answer->solution = $request->input('solution');
        $option->save();
        $answer->save();
        $chapter_id = $request->input('chapter_id');
        $course_id = $request->input('course_id');
        
        if(Auth::user()->hasRole('Director-of-Undergraduate-Studies|Director-of-PostUtme|Director-of-Remedial-Studies')){
        
          $route = 'question/'.$course_id. ',' .$chapter_id;
        }else{
        $route = 'tquestion/'.$chapter_id. ',' .$course_id;
      
        }

        return $this->return_output('flash', 'success', $success_message, $route, '200');
    }


    public function deleteQuestion($question_id)
    {
        $question = Question::find($question_id);
        $chapter_id = $question->chapter_id;
        $course_id = $question->course_id;
         if(Auth::user()->hasRole('Director-of-Undergraduate-Studies|Director-of-PostUtme|Director-of-Remedial-Studies')){
        
          $route = 'question/'.$course_id. ',' .$chapter_id;
        }else{
        $route = 'tquestion/'.$chapter_id. ',' .$course_id;
      
        }
        Question::destroy($question_id);

        return $this->return_output('flash', 'success', 'Question deleted successfully', $route, '200');
    }
}
