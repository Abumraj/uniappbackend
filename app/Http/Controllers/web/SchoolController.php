<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\School;
use App\Program;
use DB;
class SchoolController extends Controller
{
    public function index(Request $request)
    {
        $paginate_count = 10;
        if($request->has('search')){
            $search = $request->input('search');
         $schools = DB::table('schools')
                         ->where('name', 'LIKE', '%' . $search . '%')
                        ->select('schools.*', 'programs.name as program_name')
                        ->leftJoin('programs', 'programs.id', '=', 'schools.program_id')
                        ->paginate($paginate_count);
        }
        else {
            $schools = DB::table('schools')
           ->select('schools.*', 'programs.name as program_name')
           ->leftJoin('programs', 'programs.id', '=', 'schools.program_id')
           ->paginate($paginate_count);
        }
        
        return view('adminLte.school.index', compact('schools'));
    }


    public function getForm($school_id='', Request $request)
    {
        $programs = Program::where('is_active', 'true')->get();
        if($school_id) {
            $school = School::find($school_id);
        }else{
            $school = $this->getColumnTable('schools');
        }
        return view('adminLte.school.form', compact('school', 'programs'));
    }

    public function saveSchool(Request $request)
    {
        // echo '<pre>';print_r($_POST);exit;
        $school_id = $request->input('school_id');

        $validation_rules = [
            'name' => 'required|string|max:50',
            'code' => 'required|string|max:50',
            'channel' => 'required|string|max:100'
    ];

        $validator = Validator::make($request->all(),$validation_rules);

        // Stop if validation fails
        if ($validator->fails()) {
            return $this->return_output('error', 'error', $validator, 'back', '422');
        }

        if ($school_id) {
            $school = School::find($school_id);
            $success_message = 'School updated successfully';
        } else {
            $school = new School();
            $success_message = 'school added successfully';
        }

        $school->name = $request->input('name');
        $school->program_id = $request->input('program_id');
        $school->code = $request->input('code');
        $school->channel = $request->input('channel');
        $school->is_active = $request->input('is_active');
        $school->save();

        return $this->return_output('flash', 'success', $success_message, '/school', '200');
    }

    public function deleteSchool($school_id)
    {
        School::destroy($school_id);
        return $this->return_output('flash', 'success', 'School deleted successfully', '/school', '200');
    }
}
