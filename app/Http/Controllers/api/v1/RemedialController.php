<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\http\Requests\storeRemedialRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Live;
use Hash;
use GuzzleHttp;
use App\Http\Resources\RemedialResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\UserResource;
use App\Models\Story;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash as FacadesHash;

class RemedialController extends Controller
{

    public function register(Request $request)
    {
    $email = $request['email'];
    $userCount = User::where('email', $email)->count();

    if($userCount > 0){

    return response()->json('User with this email already exist');

    } else{
        $plan = Subscription::where('plan_price', 0)->where('type', Subscription::REMEDIAL_PLAN)->first();
        $departmentCourses = DB::table('courses')
        ->join('course_department', 'courses.id', '=', 'course_department.course_id')
        ->where('course_department.department_id', $request['departmentId'] )
       ->select('courses.id')->get();
        $course_ids = array();
        foreach ($departmentCourses as $course) {
            $course_ids[] = $course->id;
        }
           $stalite = User::create([
               'name' =>$request['name'],
               'phone' =>$request['phone'],
               'email' =>$request['email'],
               'department_id' =>$request['departmentId'],
               'usertype_id' =>$request['usertypeId'],
               'subscription_id' =>$plan->id,
               'password' =>FacadesHash::make($request['password']),
           ]);
           $stalite->courses()->attach($course_ids);
           $stalite->assignRole('Remedial');
           return response()->json('You have successfully registered');
           }
        }

        public function login(Request $request)
        {
          $newAuth = collect();
        $email = $request['email'];
    $user = User::where('email', $email)->get();
    $userCount = User::find($user[0]->id)->getRoleNames();
    $userType = User::find($user[0]->id);

            try {
                $http = new GuzzleHttp\Client;

                $response = $http->post('http://localhost/uniapp/public/oauth/token', [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => '1',
                        'client_secret' => 'IEgncDHt3gUBm9Rz3KkILiGobPytqdFDNFDkXFpr',
                        'username' => $request->email,
                        'password' => $request->password,
                        'scope' => '*',
                    ],
                ]);

                   $newAuth->push(json_decode((string) $response->getBody(), true));
                    if ($userType->is_login_first == 1) {
                        $userType->update(['is_login_first', 0]);
                    }
                   $newAuth->put("role", $userCount);
                   $newAuth->put("type", $userType->type);
                   $newAuth->put("isLoginFirst", $userType->is_login_first);

                       return $newAuth;
                       print($response->getBody());
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
         auth()->user()->tokens->each(function($token){
            $token->revoke();
            $token->delete();

        });

        $stalite->is_active = "false";
        $stalite->save();

            return response()->json('Logout successfully', 200);

    }



 public function registeredCourse()
    {
        $id = auth()->user()->id;
        $registeredCourses = User:: find($id)->courses()->select('id','name', 'code', 'imageUrl', 'introUrl', 'courseChatLink', 'courseMaterialLink', 'description',)->get();


        //$registeredCourses = Course::where()->select('id','name', 'code', 'description',)->get();
        foreach ($registeredCourses as $registeredCourse) {
        //   return [
        //     'courseId' =>$registeredCourse->id,
        //     'courseName' =>$registeredCourse->name,
        //     'coursecode' =>$registeredCourse->code,
        //     'courseDescrip' =>$registeredCourse->description,
        //     'expire_at' =>$registeredCourse->expire_at,
        //   ];
                   return CourseResource::collection($registeredCourses);
               }
    }
    public function myUpdates()

    {
        $id = auth()->user()->id;

        $stories = Story   ::where('user_id',$id)->withCount('storylikes as likes')->withCount('storydislikes as dislikes')->withCount('storyviews as views')->get();
        // foreach($stories as $story){
        //  $storylikes  = Storylike::where('story_id', $story->id)->count();
        // //  $totalStoryLikes =
        //     return $story->with($storylikes);
        // }
    return $stories;

    }



    public function myDetails()
        {
            return new UserResource(User::find(Auth::user()->id));
        }
}
