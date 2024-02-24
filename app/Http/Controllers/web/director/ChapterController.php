<?php

namespace App\Http\Controllers\web\director;
use App\Models\Chapter;
use App\Models\Test;
use App\Models\TestResult;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\chapterResource as ChapterResource;
use App\Http\Resources\LecturerResource as LecturerResource;
use App\Http\Resources\TestResource;
use App\Http\Resources\TestApprovalResource;
use App\Http\Resources\TestResultResource;
use App\Http\Resources\TestResultCollection;
use App\Imports\TestQuestionsImport;
use Maatwebsite\Excel\Facades\Excel;


class ChapterController extends Controller
{

//  public function __construct()
//     {
//         $this->middleware(['role:Director-of-Undergraduate-Studies|Director-of-PostUtme|Director-of-Remedial-Studies']);
//     }

    public function index($course_id = '', $tutor_role = '', Request $request)
    {
        $id = Auth::user()->id;
        if($tutor_role == 'lead-tutor'){
            $chapters = Course::find($course_id)->chapters;
          }else{
           $chapters = DB::table('chapters')
           ->select('chapters.*' )
           ->where('chapters.course_id', $course_id)
           ->where('chapters.user_id', $id)->get();
          }
          return ChapterResource::collection($chapters);
        }


        public function tests($course_id)
        {
            //  return $course_id;
            $chapters = Course::find($course_id)->tests;
            //  return $chapters;

              return TestResource::collection($chapters);
            }




      public function isChapterPublished(Request $request)
      {
           $chapter = Chapter::find($request->chapterId);
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
      public function isTestChapterPublished(Request $request)
      {
           $chapter = Test::find($request->testId);
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
      public function isTestStarted(Request $request)
      {
           $chapter = Test::find($request->testId);
           if ($chapter->is_started == 0) {
            $chapter->is_started = 1;
            $success_message = 'Published';
           }else {
            $chapter->is_started = 0;
            $success_message = 'UnPublished';
           }
           $chapter->save();
           return response()->json($success_message);

      }
    public function saveChapter(Request $request)
    {
        $chapter_id = $request->chapterId;
        // $chapter_id = $request->courseId;

        $validation_rules = [
            'chapterName' => 'required|string|max:100',
            'chapterDescrip' => 'required|string|max:5000',
            'quesNum' => 'required|integer|max:200',
            'quesTime' => 'required|integer|max:200'
    ];

        $validator = Validator::make($request->all(),$validation_rules);
        if ($validator->fails()) {
            return response()->json("error");
        }

        if ($chapter_id != null) {
            $chapter = Chapter::find($chapter_id);
            $success_message = 'Chapter updated successfully';

        } else {
            $chapter = new chapter();
            $success_message = 'Chapter added successfully';
        }
        //   return $success_message;
        $chapter->course_id =$request->courseId;
        $chapter->user_id = $request->userId;
        $chapter->name = $request->chapterName;
        $chapter->description = $request->chapterDescrip;
        $chapter->quesnum = $request->quesNum;
        $chapter->questime = $request->quesTime;
        $chapter->orderId = $request->quesTime;
        $chapter->is_Published = $request->isPublished;
        $chapter->save();

        return response()->json($success_message);
    }
    public function saveTest(Request $request)
    {

        $id = Auth::user()->id;

    //     $validation_rules = [
    //         'name' => 'required|string|max:100',
    //         'description' => 'required|string|max:5000',
    //         'mark' => 'required|integer|max:2000',
    //         'quesnum' => 'required|integer|max:200',
    //         'questime' => 'required|integer|max:200'
    // ];

    //     $validator = Validator::make($request->all(),$validation_rules);
        // if ($validator->fails()) {
        //     return response()->json("An error occured");
        // }
        if ($request->chapterDescrip == "Make-Up") {

        $idForMake = Test::where('name', $request->chapterName)->count();
        if ($idForMake > 0 ) {
            $test = Test::where('name', $request->chapterName)->get();
            $endtime = Carbon::createFromFormat('Y-m-d H:i:s',$test[0]->endTime);
            $now = Carbon::now();
            $hasTestEnded = $endtime->lessThan($now);
            if ($hasTestEnded ==false) {
         return 'Test not created';
         # code...
            }
            $idForMakeUp = $test[0]->id;
                // $success_message = 'Test updated successfully';
        }else{
         return 'Test not created';
        }

        }
        if ($request->testId != null) {
            $chapter = Test::find($request->testId);
            $success_message = 'Test updated successfully';

        } else {
            $chapter = new Test();
            $success_message = 'Test added successfully';
        }
        $chapter->course_id =$request->courseId;
        $chapter->user_id =$id;
        $chapter->name = $request->chapterName;
        $chapter->description = $request->chapterDescrip;
        $chapter->quesNum = $request->quesNum;
        $chapter->mark = $request->mark;
        $chapter->quesTime = $request->quesTime;
        $chapter->startTime = $request->startTime;
        $chapter->endTime = $request->endTime;
        $chapter->is_published = $request->isPublished;
        $chapter->save();

        if ($request->testId == null && $request->chapterDescrip != "Make-Up") {
            $testId= Test::where('name', $request->chapterName)->select('id')->get();
            $courseIds = array();
            $students = Course::find($request->courseId)->users;
        foreach($students as $student){
        array_push($courseIds,$student->id);
        }
        $test = Test::find($testId[0]->id);
        $test->users()->attach($courseIds, ['is_approved' => $request->isApproved]);
        }else if($request->testId == null && $request->chapterDescrip == "Make-Up"){
            $testId= Test::where('name', $request->chapterName)->where('description', "Make-Up" )->select('id')->get();
        $chapter = DB::table('test_user')->where('test_id', $idForMakeUp)->where('is_approved', 0)->select('user_id')->get();
            $studentIds = array();
            foreach($chapter as $student){
                array_push($studentIds,$student->user_id);
                }
                $test = Test::find($testId[0]->id);
                $test->users()->attach($studentIds);
            $success_message = 'Make-up test created successfully';

        }


        return response()->json($success_message);
    }



    public function registeredTestStudents($testId)
    {
        $courseImage = TestResult::where('test_id', $testId)->paginate(50);
        return
        // $courseImage;
       new TestResultCollection($courseImage);
        // return

      // $courses =  User::find($testId)->courses;
    //    return   TestApprovalResource::collection($students);
    }
    public function myDetails()
    {
        return new LecturerResource( User::find(Auth::user()->id));

    }
    public function registeredTestStudent($testId)
    {

      $students = Test::find($testId)->users;

      // $courses =  User::find($testId)->courses;
       return   TestApprovalResource::collection($students);
    }

      public function testResults($test_id)
      {
        $courseImage = TestResult::where('test_id', $test_id)->paginate(2);
               return        new TestResultCollection($courseImage);

      }

        public function deletechapter($chapter_id)
        {
            // return $chapter_id;
            Chapter::destroy($chapter_id);
            return response()->json('Chapter deleted successfully');
        }
        public function deleteTest($test_id)
        {
            $userCollectId = array();
            $test = Test::find($test_id);
            $userIds = $test->users;
            foreach($userIds as $userId){
                array_push($userCollectId, $userId->id);
            }
            $test->users()->detach($userCollectId);
            Test::destroy($test_id);
            return response()->json('Test deleted successfully');
        }





    public function testApproval(Request $request)
    {
        $test_id = $request->testId;
        $user_id = $request->userId;

        $chapter = DB::table('test_user')->where('user_id', $user_id)->where('test_id', $test_id)->get();

        if ($chapter[0]->is_approved == 0) {
         DB::table('test_user')->where('user_id', $user_id)->where('test_id', $test_id)->update(['is_approved'=> 1]);
            $success_message = 'Published';
        }else if ($chapter[0]->is_approved == 1) {
            DB::table('test_user')->where('user_id', $user_id)->where('test_id', $test_id)->update(['is_approved'=> 0]);
            $success_message = 'UnPublished';
        } if ($chapter[0]->is_approved == 2){
            $success_message = 'impossible';
        }

     return response()->json($success_message);


    }
    public function chapterManagement(Request $request)
    {
        $chapter_ids = $request->chapter_id;
        $tutor = $request->tutor;
        $user = User::find($tutor);
        foreach($chapter_ids as $id){
        $isApproved = $user->chapters()->wherePivot('chapter_id', $id)->exist();
        if ($isApproved) {
        $user->chapters->detach($chapter_ids);
   $message = 'Lecturer Unassigned successfully';
    }else {
        $user->chapters->attach($chapter_ids);
        $message = "Lecturer assigned successfully";
    }
        }

        return response()->json($message);
    }




    public function getForm($course_id='', $chapter_id = '', Request $request)
    {
        $tutors = Course::find($course_id)->users()->get();

         $course_id =  $course_id;
        if($chapter_id) {
            $chapter = Chapter::find($chapter_id);

        }else{
            $chapter = $this->getColumnTable('chapters');
        }
       // dd($chapter);
        return view('director.chapter.form', compact('chapter','tutors', 'course_id'));
    }
    public function getForm1($course_id='', Request $request)
    {
        $tutors = Course::find($course_id);
             foreach($tutors->users as $user){
               return $user;
           }

            $chapters = Chapter::find($course_id)->get();

        return view('director.chapter.form1', compact('tutors', 'chapters'));
    }
}
