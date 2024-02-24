<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $metrics = array();
        // $metrics['allcourses'] = \DB::table('courses')->count();
        // $metrics['stalitecourses'] = \DB::table('courses')
        //                         ->where('type', 'stalite')->count();
        // $metrics['remedialcourses'] = \DB::table('courses')
        //                         ->where('type', 'stalite')->count();
        // $metrics['aspirantcourses'] = \DB::table('courses')
        //                         ->where('type', 'aspirant')->count();
        // $metrics['students'] = \DB::table('users')
        //                         ->where('roles.name', 'Stalite')
        //                         ->where('roles.name', 'Remedial')
        //                         ->where('roles.name', 'Aspirant')
        //                         ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
        //                         ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
        //                         ->count();
        // $metrics['remedials'] = \DB::table('users')
        //                         ->where('roles.name', 'Remedial')
        //                         ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
        //                         ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
        //                         ->count();
        // $metrics['stalites'] = \DB::table('users')
        //                         ->where('roles.name', 'Stalite')
        //                         ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
        //                         ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
        //                         ->count();
        // $metrics['aspirants'] = \DB::table('users')
        //                         ->where('roles.name', 'Aspirant')
        //                         ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
        //                         ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
        //                         ->count();
        // $metrics['directors'] = \DB::table('users')
        //                         ->where('roles.name', 'Director-of-Undergraduate-Studies')
        //                         ->where('roles.name', 'Director-of-PostUtme')
        //                         ->where('roles.name', 'Director-of-Remedial-Studies')
        //                         ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
        //                         ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
        //                         ->count();
        // $metrics['students'] = \DB::table('users')
        //                         ->where('roles.name', 'Stalite')
        //                         ->where('roles.name', 'Remedial')
        //                         ->where('roles.name', 'Aspirant')
        //                         ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
        //                         ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
        //                         ->count();
        // $metrics['instructors'] = \DB::table('users')
        //                         ->where('roles.name', 'instructor')
        //                         ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
        //                         ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
        //                         ->count();
        return view('adminLte.dashboard');
    // }
        // return view('home');
    }

    public function studentlist()
    {
        return view('adminLte.studentlist');
    }
}
