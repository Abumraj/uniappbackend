<?php
namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Http\Resources\courseResource;
use App\Http\Resources\TestResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\optionResource as OptionResource;
use App\Http\Resources\TestQuestionResource;
use App\Http\Resources\chapterResource as ChapterResource;
use App\Models\Course;
use App\Models\Live;
use App\Models\Test;
use App\Models\TestResult;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\ResetPasswordForMobile;


class CourseController extends Controller
{
    // public function _construct()
    // {
    //     $this->middleware('auth:aspirant');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::all();

            return $courses;
            
            
            courseResource::collection($courses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    public function tests($course_id)
    {
        //  return $course_id;
        $chapters = Course::find($course_id)->tests->where('is_published', 1);
        //  return $chapters;

          return TestResource::collection($chapters);
        }

        public function testApproval($test_id)
    {
        $user_id = Auth::user()->id;


        $chapter = DB::table('test_user')->where('user_id', $user_id)->where('test_id', $test_id)->first();

        if ($chapter->is_approved == 0) {
            $success_message = 'not approved';
        }else if ($chapter->is_approved == 1) {
            $success_message = 'approved';
        }else{
            $success_message = 'done';
        }

     return response()->json($success_message);


    }

    public function testResult(Request $request)
    {
        $user_id = Auth::user()->id;
        $test_id = $request["chapterId"];
        $result = $request["result"];
        $chapter = DB::table('test_results')->where('user_id', $user_id)->where('test_id', $test_id)->count();
      if ($chapter > 0) {
        return 'Multiple Submission not allowed';
      }
      $courseImage = new TestResult();
      $courseImage->test_id = $test_id;
      $courseImage->score = $result;
      $courseImage->user_id = $user_id;
      $courseImage->save();
      DB::table('test_user')->where('user_id', $user_id)->where('test_id', $test_id)->update(['is_approved'=> 2]);


      return 'Submitted';

    }

    public function testIndex($test_id ='')
    {
       $question = Test::find($test_id)->testQuestions->where('is_published', 0);


       return TestQuestionResource::collection($question);
    }


    public function startTest($testId)
    {
        $chapter = Test::find($testId);

        if ($chapter->is_started == 0) {
           return "false";
           }else {
         return "true";
           }


    }
    /**
     * Display the specified resource.
     *
     * @param  \App\course  $course
     * @return \Illuminate\Http\Response
     */
    public function chapters()
    {
    $chaps = collect();
    $id = auth()->user()->id;
        $registeredCourses = User:: find($id)->courses;
        foreach ($registeredCourses as $registeredCourse) {
        $chapters = Course:: find($registeredCourse->id)->chapters;


           foreach($chapters as $chapter){
           $chaps->push($chapter);

           }


    }
    return ChapterResource::collection($chaps);
}
    public function courseVideoChapter($course_id)
    {   $chap = collect();
         $id = auth()->user()->id;
        $registeredCoursesLives = User::find($id)->courses;
        // dd($registeredCoursesLive);
        foreach($registeredCoursesLives as $vet ){

            $registeredCoursesLive = Live::where('course_id', $vet->id)->get();
        }
        // $courseVideoChapters = Chapter:: find($course_id)->get();
        //dd($courseVideoChapters);
              return OptionResource::collection($registeredCoursesLive);

    }


     /*password reset*/
     public function sentForgetCode($email)
     {
         $user = User::where('email', $email)->first();
         if ($user != null) {

             $id = rand(10, 100000);
             /*send here email and insert the code in */
             try {
                 $user->notify(new ResetPasswordForMobile($id));
                 $user->fcode = $id;
                 $user->save(); //save the code
                 $success_message = "An Activation code is already sent to your email, Please check your email";
             } catch (\Exception $exception) {
                 $success_message = 'There are Some Problem. Try again!';
             }
         } else {
             $success_message = 'Email Not Found';
         }
         return $success_message;
     }


     public function matchForgetCode($email, $code)
     {
         $user = User::where('email', $email)->where('fcode', $code)->first();
         if ($user != null) {
             $success_message = "Type your new password";
         } else {
             $success_message = 'Invalid Code';
         }
         return $success_message;
     }


     public function saveForgetCodePassword(Request $request)
     {
         $user = User::where('email', $request->email)
             ->where('fcode', $request->code)->first();
         if ($user != null) {
             $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
             $user->fcode = null;
             $user->save();
             $success_message = "Password Is Updated";
         } else {
             $success_message = 'Invalid Attempt. Try again!';
         }
         return $success_message;
     }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(course $course)
    {
        //
    }
}
