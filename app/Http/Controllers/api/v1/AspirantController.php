<?php
namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\http\Requests\storeAspirantRequest;
use Illuminate\Http\Request;
use GuzzleHttp;
use App\Http\Resources\CourseResource;
use Illuminate\Support\Facades\Hash as FacadesHash;
use App\Http\Resources\UserResource;
use App\Models\Course;
use App\Models\User;
use App\Models\Story;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class AspirantController extends Controller
{
    public function register(Request $request)
    {
    $email = $request['email'];
    $userCount = User::where('email', $email)->count();

    if($userCount > 0){

    return response()->json('User with this email already exist');
    } else{
        $plan = Subscription::where('plan_price', 0)->where('type', Subscription::ASPIRANT_PLAN)->first();
        $aspirantCourses = Course::where('type', Course::ASPIRANT_PLAN)->get();


         $aspirant = User::create([
             'name' =>$request['name'],
             'phone' =>$request['phone'],
             'email' =>$request['email'],
             'subscription_id' =>$plan->id,
             'usertype_id' =>$request['usertypeId'],
             'password' =>FacadesHash::make($request['password']),
         ]);
         $course_ids = array();
         foreach ($aspirantCourses as $course) {
             $course_ids[] = $course->id;
            }
            $aspirant->courses()->attach($course_ids);
         $aspirant->assignRole('Aspirant');
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
        // if($userCount[0]->is_active == "true"){
        // return response()->json(['info' =>'Multiple session not allowed.']);

        // }


                // $metric = \DB::table('course_user')->insert(['course_id' => 1, 'user_id' => 4, 'role' => 'student']);
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
    //  Log::debug(new UserResource( User::find(Auth::user()->id)));
            return new UserResource( User::find(Auth::user()->id));

        }
}
