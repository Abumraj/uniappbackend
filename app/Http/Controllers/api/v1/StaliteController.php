<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\http\Requests\storeStaliteRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Story;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\CourseResource;
use App\Http\Resources\UserResource;
use App\Models\Semester;
use App\Models\Subscription;
use App\Models\Usertype;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StaliteController extends Controller
{
    public function register(Request $request)
    {
    $email = $request['email'];
    $userCount = User::where('email', $email)->count();
    if($userCount > 0){

        return response()->json('User with this email already exist');

    } else{
        $plan = Subscription::where('plan_price', 0)->where('type', Subscription::UNDERGRADUATE_PLAN )->first();
         $stalite = User::create([
             'name' =>$request['name'],
             'phone' =>$request['phone'],
             'email' =>$request['email'],
             'is_admitted' => 1,
             'usertype_id' =>$request['usertypeId'],
             'subscription_id' =>$plan->id,
             'password' =>Hash::make($request['password']),
         ]);
         $stalite->assignRole('Stalite');
         return response()->json('You have successfully registered');
         }
        }

// Qujuan client secret key for stripe payment.
// const stripe = require('stripe')('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')

        public function login(Request $request)
        {
          $newAuth = collect();

          $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $email = $request['email'];
    $user = User::where('email', $email)->first();
    $userRole = User::find($user->id)->getRoleNames();
    $userType = Usertype::find($user->usertype_id);
                try {
                    if (! $user || ! Hash::check($request->password, $user->password)) {
                        throw ValidationException::withMessages([
                            'email' => ['The provided credentials are incorrect.'],
                        ]);
                    }
                   $token  = $user->createToken($email)->plainTextToken;
                   $newAuth->put("access_token", $token);
                   $newAuth->put("role", $userRole);
                   $newAuth->put("type", $userType->type);
                   $newAuth->put("isAdmitted", $user->is_admitted);
                   $newAuth->put("isLoginFirst", $user->is_login_first);
                    if ($user->is_login_first == 1) {
                       User::where('id',$user->id)->update(['is_login_first'=> 0]);
                    }

                       return $newAuth;
                     } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                         if ($e->getCode() === 401) {
                             return  response()->json(['info' => 'Your credentials are incorrect. please try again']);
                         }
                         return response()->json(['info' =>'Something went wrong on the server']);
                     }
            }

    public function logout()
    {
    $id = auth()->user()->id;
          $stalite = User::find($id);
         $stalite->tokens->delete();


            return response()->json('Logout successfully', 200);

    }



    public function registeredCourse()
    {
        $user = auth()->user();
        $registeredCourses = User:: find($user->id)->courses()->select('id','title', 'code', 'semester', 'status', 'level_id', 'unit','chatLink','is_published' )->get();


        return    CourseResource::collection($registeredCourses);
        // foreach ($registeredCourses as $registeredCourse) {

        //        }
    }
    // public function registeredCourseVideo()
    // {
    //    $id = auth()->user()->id;
    //    $live = Live::where('status', 'buffering')->where('type', 'general')->get();
    //           return AnswerResource::collection($live);

    // }


    public function staliteSetup(Request $request)
{
             $userId = FacadesAuth::id();
             $user = User::find($userId);
             $user->update([
         'department_id'=>  $request['departmentId'],
         'level_id' =>   $request['levelId'],
         'is_admitted' => 2,
             ]);

      $departmentCourses = DB::table('courses')
      ->join('course_department', 'courses.id', '=', 'course_department.course_id')
      ->where('course_department.department_id', $request['departmentId'] )
      ->where('courses.semester', Semester::IS_ACTIVE)->select('courses.id')->get();
      $course_ids = array();
      foreach ($departmentCourses as $course) {
          $course_ids[] = $course->id;
      }

      $user->courses()->attach($course_ids);

    return 'Set up successful';
}


public function myUpdates()

{
    $id = auth()->user()->id;

    $stories = Story::where('user_id',$id)->withCount('storylikes as likes')->withCount('storydislikes as dislikes')->withCount('storyviews as views')->get();
    // foreach($stories as $story){
    //  $storylikes  = Storylike::where('story_id', $story->id)->count();
    // //  $totalStoryLikes =
    //     return $story->with($storylikes);
    // }
return $stories;

}



public function myDetails()
    {
        return new UserResource( User::find(FacadesAuth::user()->id));

    }
}
