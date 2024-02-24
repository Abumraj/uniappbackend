<?php
namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Resources\departmentResource;
use App\Http\Resources\AspirantResource;
use App\Models\Semester;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;


class DepartmentController extends Controller
{
    public function show($id)
    {

        $departments = Department::where('faculty_id', $id)->get();
              return  departmentResource::collection($departments);

    }
    public function remedialDepartment()
    {
        // $plan = Subscription::where('plan_price', 0)->where('type', Subscription::REMEDIAL_PLAN )->first();

        $departments = Department::where('type', Department::REMEDIAL_PLAN)->get();
              return 

              departmentResource::collection($departments);

    }

    public function jupebDepartment()
    {

        $departments = Department::where('type', Department::JUPEB_PLAN)->get();
              return  departmentResource::collection($departments);

    }
    public function ijmbDepartment()
    {

        $departments = Department::where('type', Department::IJMB_PLAN)->get();
              return  departmentResource::collection($departments);

    }
    public function sandwichDepartment()
    {

        $departments = Department::where('type', Department::SANDWICH_PLAN)->get();
              return  departmentResource::collection($departments);

    }
       //  get all department course in a semester.
    public function departmentalCourse($level = null)
    {
       $semester = Semester::where('is_active', Semester::IS_ACTIVE)->get();
        $currentUser = Auth::user()->id;
     $user = User::find($currentUser);
     if ($level = null) {
        $level = $user->level_id;
     }
     $idd = $user->department_id;
        $departmental = Department::find($idd)->courses()->
        where('semester', $semester)
       -> where('level', $level)
        ->orderBy('id')->get();
              return AspirantResource::collection($departmental);

    }


}
