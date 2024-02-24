<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\facultyResource as FacultyResource;
use App\Http\Resources\departmentResource;

class FacultyController extends Controller
{
    //get all faculties
    public function index()
    {
        $faculties = Faculty::all();

        return  FacultyResource::collection($faculties);
    }



//  get all departments in a particular faculty
    public function department($id)
    {
        $departments = Department::find($id);
        foreach ($departments as $department) {
              return DepartmentResource::collection($departments);
               }
    }
}
