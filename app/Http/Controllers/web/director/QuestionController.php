<?php

namespace App\Http\Controllers\web\director;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\QuestionsImport;
use App\Imports\TestQuestionsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Question;
use App\Models\Notification;
use App\Models\TestQuestion;
use App\Models\Test;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\questionResource as QuestionResource;
use App\Http\Resources\TestQuestionResource;


class QuestionController extends Controller
{



//  public function __construct()
//     {
//         $this->middleware(['role:Director-of-Undergraduate-Studies|Director-of-PostUtme|Director-of-Remedial-Studies']);
//     }




    public function index($chapter_id ='')
    {
       $question = Question::where('chapter_id', $chapter_id)->get();


       return QuestionResource::collection($question);
    }
    public function testIndex($test_id ='')
    {
       $question = Test::find($test_id)->testQuestions;


       return TestQuestionResource::collection($question);
    }


    public function saveQuestion(Request $request)
    {
        $question_id = $request->id;
        $validation_rules = [
             'courseId' => 'required|integer|max:5000000000',
            'chapterId' => 'required|integer|max:5000000000',
            'question' => 'string|max:100000000',
            'imageUrl' => 'string|max:100000000',
            'answer' => 'string|max:100000000',
            'solution' => 'string|max:100000000',
            'option2' => 'string|max:100000000',
            'option3' => 'string|max:100000000',
            'option4' => 'string|max:100000000',
    ];

        // $validator = Validator::make($request->all(),$validation_rules);

        // if ($validator->fails()) {
        //     return response()->json("An error occured");
        // }

        if ($question_id != null) {
            $question = Question::find($question_id);
        $success_message = 'Question updated successfully';
    } else {
        $question = new Question();
     $success_message = 'Question added successfully';
    }

    $question->course_id = $request->courseId;
    $question->chapter_id = $request->chapterId;
        $question->question = $request->question;
        $question->option2 = $request->option2;
        $question->option3 = $request->option3;
        $question->option4 = $request->option4;
        $question->answer = $request->answer;
        $question->solution = $request->solution;
        $question->imageUrl = $request->imageUrl;
        $question->is_published = $request->isPublished;
        $question->save();



        return response()->json($success_message);
    }
    public function saveTestQuestion(Request $request)
    {
        $question_id = $request->id;
    //     $validation_rules = [
    //         'testId' => 'string|integer|max:5000000000',
    //         'question' => 'string|max:100000000',
    //         'imageUrl' => 'string|max:100000000',
    //         'answer' => 'string|max:100000000',
    //         'option2' => 'string|max:100000000',
    //         'option3' => 'string|max:100000000',
    //         'option4' => 'string|max:100000000',
    // ];

        // $validator = Validator::make($request->all(),$validation_rules);

        // if ($validator->fails()) {
        //     return response()->json("An error occured");
        // }

        if ($question_id !=  null) {
            $question = TestQuestion::find($question_id);
        $success_message = 'Test question updated successfully';
    } else {
        $question = new TestQuestion();

     $success_message = 'Test question added successfully';
    //  return $request->isPublished;
    }

        $question->test_id = $request->testId;
        $question->question = $request->question;
        $question->option2 = $request->option2;
        $question->option3 = $request->option3;
        $question->option4 = $request->option4;
        $question->answer = $request->answer;
        $question->imageUrl = $request->imageUrl;
        $question->is_published = $request->isPublished;
        $question->save();



        return response()->json($success_message);
    }

    public function isQuestionPublished(Request $request)
    {
         $chapter = Question::find($request->questionId);
         if ($chapter->is_published == 0) {
          $chapter->is_published = 1;
          $success_message = 'Published';
         }else {
          $chapter->is_published = 0;
          $success_message = 'UnPublished';
         }
         $chapter->save();
         return response()->json($success_message);

    }
    public function isTestQuestionPublished(Request $request)
    {
         $chapter = TestQuestion::find($request->questionId);
         if ($chapter->is_published == 0) {
          $chapter->is_published = 1;
          $success_message = 'Published';
         }else {
          $chapter->is_published = 0;
          $success_message = 'UnPublished';
         }
         $chapter->save();
         return response()->json($success_message);

    }
    public function deleteQuestion($questionId)
    {
        Question::destroy($questionId);
        return response()->json("Question deleted successfully");
    }
    public function deleteTestQuestion($testId)
    {
        TestQuestion::destroy($testId);
        return response()->json("Test question deleted successfully");
    }




    public function uploadQuestion(Request $request)
    {
     $course = Course::find($request->course_id);
       $questions = Excel::import(new QuestionsImport, $request->file('questions'), null, \Maatwebsite\Excel\Excel::XLSX);
        if ($questions) {
            $success_message = 'Questions uploaded successfully';
            Notification::pushNotification($course->coursecode,$course->coursecode, "More Questions added. Kindly refresh to see new changes.");
        }else {
            $success_message = 'An error occurred';
        }
            return response()->json($success_message);

    }
    public function uploadTestQuestion(Request $request)
    {
       $questions = Excel::import(new TestQuestionsImport, $request->file('questions'), null, \Maatwebsite\Excel\Excel::XLSX);
        if ($questions) {
            $success_message = 'Questions uploaded successfully';
        }else {
            $success_message = 'An error occurred';
        }
            return response()->json($success_message);

    }




     public function uploadCourseImage($course_id = "", Request $request)
    {
        // $course_id = $request->input('course_id');

        $course = Course::find($course_id);

        // dd($course);
          $departments = Department::all();

        return view('director.course.courseImage', compact('departments', 'course', 'course_id'));

    }
     public function saveCourseImage(Request $request)
    {
        $courseImage = $request->file('course_image');
        $course_id = $request->input('course_id');

        $course = Course::find($course_id);
        $course->addMediaFromRequest('course_image')->toMediaCollection('courseImage');
        $newCourseImage = $course->getFirstMediaUrl('courseImage');
        //$newCourseImage = $newCourseImage[0]->getPath();
        $course->imageUrl = $newCourseImage;
        $course->save();
        //dd($courseImage);
        return $this->return_output('flash', 'success', 'Course Image uploaded successfully', '/question', '200');

    }

    public function uploadChapterImage($chapter_id = "", Request $request)
    {
       // $chapter_id = $request->input('course_id');

        $chapter = Course::find($chapter_id);


        return view('director.chapter.courseImage', compact('chapter'));

    }
     public function saveChapterImage(Request $request)
    {
        $courseImage = $request->file('chapterImage');
        $course_id = $request->input('chapter_id');

        $chapter = Chapter::find($course_id);
        $chapter->addMedia($courseImage)->toMediaCollection('chapterImage');
        $newCourseImage = $chapter->getFirstMediaUrl('chapterImage');
        //$newCourseImage = $newCourseImage[0]->getPath();
        $chapter->imageUrl = $newCourseImage;
        $chapter->save();

        return $this->return_output('flash', 'success', 'Chapter image uploaded successfully', '/question', '200');

    }
     public function uploadVideoThumbNail($chapter_id = '', Request $request)
    {
        $courseImage = $request->file('videoThumbnail');
        // $course_id = $request->input('chapter_id');

        $newCourseImage = Chapter::find($chapter_id);
        $chapters = $newCourseImage->getMedia('chapterVideo');

        return view('director.video.index', compact('chapters'));

    }

     public function uploadChapterVideo(Request $request)
    {
        $courseImage = $request->file('chapterVideo');
        $course_id = $request->input('chapter_id');
        $name = $request->input('name');
        $description = $request->input('description');

        $newCourseImage = Chapter::find($course_id);
        $newCourseImage->addMedia($courseImage)
        ->withCustomProperties([
        'name' => $name,
        'description' => $description,
        'status' => 'waiting'
        ])
        ->toMediaCollection('chapterVideo');

        return $this->return_output('flash', 'success', 'Question deleted successfully', '/chapter', '200');

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
        return view('director.question.form', compact('question'));
    }
    public function getForm1($course_id ='', $chapter_id ='')
    {
        //dd($chapter_id);
        return view('director.question.form1', compact('course_id', 'chapter_id'));
    }


    public function getCourseImage($course_id='', Request $request)
    {
        if($course_id) {
            $courseImage = DB::table('questions')
            ->leftJoin('answers', 'answers.question_id', '=', 'questions.id')
            ->leftJoin('options', 'options.question_id', '=', 'questions.id')
            ->where('questions.course_id', $course_id)
            ->select('questions.*', 'answers.answer as answer', 'answers.solution as solution', 'options.option2 as option2' , 'options.option3 as option3', 'options.option4 as option4')->get();
        }else{
            $question = $this->getColumnTable('questions');
        }
       // dd($question);
        return view('director.question.form', compact('question'));
    }

}
