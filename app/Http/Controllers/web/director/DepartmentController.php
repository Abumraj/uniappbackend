<?php

namespace App\Http\Controllers\web\director;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Department;
use App\Models\Faculty;
// use DB;
use Illuminate\Support\Facades\DB ;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $paginate_count = 10;
        if($request->has('search')){
            $search = $request->input('search');
         $departments = DB::table('departments')
                         ->where('name', 'LIKE', '%' . $search . '%')
                        ->select('departments.*', 'faculties.name as faculty_name')
                        ->leftJoin('faculties', 'faculties.id', '=', 'departments.faculty_id')
                        ->paginate($paginate_count);
        }
        else {
            $departments = DB::table('departments')
           ->select('departments.*', 'faculties.name as faculty_name')
           ->leftJoin('faculties', 'faculties.id', '=', 'departments.faculty_id')
           ->paginate($paginate_count);
        }

        return view('director.department.index', compact('departments'));
    }


    public function getForm($department_id='', Request $request)
    {


        $faculties = Faculty::all();

        if($department_id) {
            $department = Department::find($department_id);
        }else{
            $department = $this->getColumnTable('departments');
        }
        return view('director.department.form', compact('department', 'faculties'));
    }

    public function saveDepartment(Request $request)
    {
        // echo '<pre>';print_r($_POST);exit;
        $department_id = $request->input('department_id');

        $validation_rules = [
            'name' => 'required|string|max:50',

    ];

        $validator = Validator::make($request->all(),$validation_rules);

        // Stop if validation fails
        if ($validator->fails()) {
            return $this->return_output('error', 'error', $validator, 'back', '422');
        }

        if ($department_id) {
            $department = Department::find($department_id);
            $success_message = 'Department updated successfully';
        } else {
            $department = new Department();
            $success_message = 'department added successfully';
        }

        $department->name = $request->input('name');
        $department->faculty_id = $request->input('faculty_id');
        $department->save();

        return $this->return_output('flash', 'success', $success_message, '/director/department', '200');
    }

    public function deleteDepartment($department_id)
    {
        Department::destroy($department_id);
        return $this->return_output('flash', 'success', 'Department deleted successfully', '/director/department', '200');
    }



}

