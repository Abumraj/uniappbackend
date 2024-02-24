<?php

namespace App\Http\Controllers\Auth;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/welcome';
    public function authenticated(Request $request, $user)
    {


        if($user->hasRole('Super-Admin')){
            return redirect('/dashboard');
        }
        if($user->hasAnyRole(['Director-of-Undergraduate-Studies', 'Director-of-PostUtme','Director-of-Remedial-Studies']))
        {
            return redirect('/director/dashboard');
        }
        if($user->hasRole('Tutor')){
            return redirect('/tutor/dashboard');
        }
        return redirect('/login');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
