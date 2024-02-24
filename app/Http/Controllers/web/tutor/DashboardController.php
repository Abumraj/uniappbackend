<?php

namespace App\Http\Controllers\web\tutor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use \Spatie\Permission\Models\Role;
use App\User;
use App\Course;
use Illuminate\Support\Facades\DB;

use Hash;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware(['role:Tutor']);
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()


    {
        // $metrics = array();
        // $metrics['allcourses'] = Course::all()->count();
   $currentUser = Auth::user()->id;
   //dd($currentUser);
   //$metrics['tutorCourses'] = $course->course->count();
   $metrics['tutorCourseNumber'] = DB::table('courses')
                    ->select('courses.*')
                    ->join('course_user', 'course_user.course_id', '=', 'courses.id')
                    ->where('course_user.user_id',$currentUser)->count();
   $counter = 0;
   $courses= DB::table('courses')
                    ->select('courses.id')
                    ->join('course_user', 'course_user.course_id', '=', 'courses.id')
                    ->where('course_user.user_id',$currentUser)->get();
                    // dd($courses);
                    foreach($courses as $course){
                    $numb = DB::table('users')
                        ->select('users.id')
                        ->join('course_user', 'course_user.user_id', '=', 'users.id')
                        ->where('course_user.course_id',$course->id)->count();
                       $counter += $numb;
                    }
                    $metrics['studentCount'] = $counter;
//dd($metrics['studentCount']);
   $tutorCourses =DB::table('courses')
   ->select('courses.*', 'course_user.role as tutor_role')
   ->leftJoin('course_user', 'course_user.course_id', '=', 'courses.id')
   
   ->where('course_user.user_id', $currentUser)->get();
  


        return view('adminLte.tutor.dashboard', compact('metrics', 'tutorCourses'));

    }

    public function studentlist()
    {

        $studentLists = User::role('Remedial', 'Stalite', 'Aspirant')->select('name','email', 'phone', 'type')->get();
        return view('adminLte.studentlist', compact('studentLists'));
    }

    public function stafflist()
    {

        $studentLists = User::with('Director-of-Undergraduate-Studies', 'Director-of-PostUtme', 'Director-of-Remedial-Studies')->select('name','email', 'phone', 'type')->get();
        return view('adminLte.staff.index', compact('studentLists'));
    }

    public function getStaffForm($user_id='', Request $request)
    {
        $roles = Role::all();
        if($user_id) {
            $user = User::find($user_id)->with('roles');
            // $roles = $user->getRoleNames();
        }else{
            $user = $this->getColumnTable('users');
        }
        return view('adminLte.staff.form', compact('user', 'roles'));
    }
public function saveStaff(Request $request)
    {
        // echo '<pre>';print_r($_POST);exit;
        $program_id = $request->input('user_id');

        $validation_rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required'
        ];

        $validator = Validator::make($request->all(),$validation_rules);

        // Stop if validation fails
        if ($validator->fails()) {
            return $this->return_output('error', 'error', $validator, 'back', '422');
        }

        if ($program_id) {
            $program = User::find($program_id);
            $success_message = 'User updated successfully';
        } else {
            $program = new User();
            $success_message = 'User added successfully';
        }

        $program->name = $request->input('name');
        $program->email = $request->input('email');
        $program->type = $request->input('type');
        $role = $request->input('role');
        $program->is_active = $request->input('is_active');
        $password = bcrypt($request->input('password'));
        $program->password = $password;
        $program = User::where('email', $program->email)->assignRole($role);
        $program->save();

        return $this->return_output('flash', 'success', $success_message, '/stafflist', '200');
    }

    public function studentprofile()
    {
        return view('adminLte.studentprofile');
    }




    public function profile()
    {
        return view('adminLte.profile');
    }
}
