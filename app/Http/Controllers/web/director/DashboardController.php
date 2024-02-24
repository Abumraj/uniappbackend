<?php

namespace App\Http\Controllers\web\director;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Course;
use App\Models\Department;
use DB;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Todo Count number of courses based on logged in director's department
        $departmentId = Auth::user()->department->id;
        // $depart = User::find($id)->department;
        // return $id;
        // $departmentId = $depart->id;
             $metrics = array();
        if(Auth::user()->hasRole('Director')){
            $metrics['lecturers'] = User::role('Director')
            ->count();
            $metrics['Students'] = User::withoutRole('Director')
            ->count();
            $metrics['My Courses'] = User::find(Auth::user()->id)->courses()
            ->count();
            $metrics['courses'] = Course::count();
        } elseif(Auth::user()->hasRole('Director-of-PostUtme')){
            $metrics['students'] = User::role('Aspirant')
            ->count();
            $metrics['courses'] = FacadesDB::table('courses')->where('type', 'aspirant')->count();
        } elseif(Auth::user()->hasRole('Director-of-Remedial-Studies')){
            //Director-of-Remedial-Studies
            $metrics['courses'] = Course::where('type', 'remedial')->count();
            $metrics['students'] = User::role('Remedial')
                                ->count();
        }

        $metrics['tutors'] = User::find(Auth::user()->id)->getRoleNames()
                                ;


        return $metrics;

    }
    // array('user' => function($query) {
    //     $query->select('id','username');
    // })
    public function studentlist()
    {

        if(Auth::user()->hasRole('Director-of-Undergraduate-Studies')){
            $studentLists = User::role('Stalite')->select('name','email')->get();

        } elseif(Auth::user()->hasRole('Director-of-PostUtme')){
            $studentLists = User::role('Aspirant')->select('name','email')->get();

        } elseif(Auth::user()->hasRole('Director-of-Remedial-Studies')){
            //Director-of-Remedial-Studies
            $studentLists = User::role('Remedial', 'Stalite', 'Aspirant')->select('name','email')->get();

        }


        return view('director.studentlist', compact('studentLists'));
    }











}
