<?php

namespace App\Http\Controllers\web;
use App\Faculty;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FacultyController extends Controller
{
    public function index(Request $request)
    {
        $paginate_count = 10;
        if($request->has('search')){
            $search = $request->input('search');
            $faculties = Faculty::where('name', 'LIKE', '%' . $search . '%')
                           ->paginate($paginate_count);
        }
        else {
            $faculties = Faculty::paginate($paginate_count);
        }
        
        return view('adminLte.faculty.index', compact('faculties'));
    }


    public function getForm($faculty_id='', Request $request)
    {
        if($faculty_id) {
            $faculty = Faculty::find($faculty_id);
        }else{
            $faculty = $this->getColumnTable('faculties');
        }
        return view('adminLte.faculty.form', compact('faculty'));
    }

    public function saveFaculty(Request $request)
    {
        // echo '<pre>';print_r($_POST);exit;
        $faculty_id = $request->input('faculty_id');

        $validation_rules = [
            'name' => 'required|string|max:50',
    ];

        $validator = Validator::make($request->all(),$validation_rules);

        // Stop if validation fails
        if ($validator->fails()) {
            return $this->return_output('error', 'error', $validator, 'back', '422');
        }

        if ($faculty_id) {
            $faculty = Faculty::find($faculty_id);
            $success_message = 'Faculty updated successfully';
        } else {
            $faculty = new Faculty();
            $success_message = 'faculty added successfully';
        }

        $faculty->name = $request->input('name');
        
        $faculty->save();

        return $this->return_output('flash', 'success', $success_message, '/faculty', '200');
    }

    public function deleteFaculty($faculty_id)
    {
        Faculty::destroy($faculty_id);
        return $this->return_output('flash', 'success', 'Category deleted successfully', '/faculty', '200');
    }
}
