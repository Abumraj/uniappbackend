<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\http\Requests\storeUserRequest;
use App\Models\user;
use Illuminate\Http\Request;
use GuzzleHttp;
use Illuminate\Support\Facades\Hash as FacadesHash;
use phpseclib\Crypt\Hash;

class userController extends Controller
{

public function show($id)
 {

 }

    public function register(storeUserRequest $request)
    {
         $user = User::create([
             'name' =>$request['name'],
             'phone' =>$request['phone'],
             'email' =>$request['email'],
             'password' =>FacadesHash::make($request['password']),
         ]);
             return $user;
            }


            public function login(Request $request)
         {
                  try {
                    $http = new GuzzleHttp\Client;

                    $response = $http->post('http://127.0.0.1:8000/public/oauth/token', [
                        'form_params' => [
                            'grant_type' => 'password',
                            'client_id' => '1',
                            'client_secret' => 'tCfyM7QS6YFdV3fJAcIrIotMQDRqXmEJD0JW1QZq',
                            'username' => $request->email,
                            'password' => $request->password,
                            'scope' => 'stalite',
                        ],
                    ]);

                    return $response->getBody();
                  } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                      if ($e->getCode === 401) {
                          return response()->json('Your credentials are incorrect. please try again', $e->getCode());
                      }
                      return response()->json('Something went wrong on the server', $e->getCode());

                  }
         }

    public function logout()
    {
        return auth()->user()->tokens->each(function($token){
            $token->delete();
        });
        return response()->json('Logout successfully', 200);

    }



    public function registeredCourse()
    {
        $id = auth()->user()->id;
        $registeredCourses = User:: find($id)->courses->all();
        foreach ($registeredCourses as $registeredCourse) {
              return $registeredCourse;
               }
    }
}
