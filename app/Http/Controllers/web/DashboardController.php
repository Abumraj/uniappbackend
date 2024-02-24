<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use \Spatie\Permission\Models\Role;
use App\User;
use App\Course;
use DB;
use Hash;

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
        // $metrics = array();
        $metrics['allcourses'] = Course::all()->count();
        
        $metrics['students'] = User::role('Remedial', 'Stalite', 'Aspirant')                      
                                ->count();
        $metrics['remedials'] = User::role('Remedial')                      
                                ->count();
        $metrics['stalites'] = User::role('Stalite')                      
                                 ->count();
        $metrics['aspirants'] = User::role('Aspirant')                      
                                ->count();
        $metrics['staffs'] = User::role('Director-of-Undergraduate-Studies', 'Director-of-PostUtme', 'Director-of-Remedial-Studies', 'Lead-Tutor', 'Tutor')                      
                                ->count();                      
        $metrics['lead tutors'] = User::role('Lead-Tutor')                      
                                ->count();                      
        $metrics['tutors'] = User::role('Tutor')                      
                                ->count();                      
        $metrics['directors'] = User::role('Director-of-Undergraduate-Studies', 'Director-of-PostUtme', 'Director-of-Remedial-Studies')                      
        ->count();
       
        return view('adminLte.dashboard', compact('metrics'));
   
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