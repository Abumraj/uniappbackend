<?php

namespace App\Http\Controllers\web;
use App\Program;
use App\School;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
class ProgramController extends Controller
{
   

    public function index(Request $request)
    {
        $paginate_count = 10;
        if($request->has('search')){
            $search = $request->input('search');
            $programs = Program::where('name', 'LIKE', '%' . $search . '%')
                           ->paginate($paginate_count);
        }
        else {
            $programs = Program::paginate($paginate_count);
        }
        
        return view('adminLte.program.index', compact('programs'));
    }


    public function getForm($program_id='', Request $request)
    {
        if($program_id) {
            $program = Program::find($program_id);
        }else{
            $program = $this->getColumnTable('programs');
        }
        return view('adminLte.program.form', compact('program'));
    }

    public function saveProgram(Request $request)
    {
        // echo '<pre>';print_r($_POST);exit;
        $program_id = $request->input('program_id');

        $validation_rules = [
            'name' => 'required|string|max:50',
            'code' => 'required|string|max:50'
    ];

        $validator = Validator::make($request->all(),$validation_rules);

        // Stop if validation fails
        if ($validator->fails()) {
            return $this->return_output('error', 'error', $validator, 'back', '422');
        }

        if ($program_id) {
            $program = Program::find($program_id);
            $success_message = 'Program updated successfully';
        } else {
            $program = new Program();
            $success_message = 'program added successfully';
        }

        $program->name = $request->input('name');
        $program->code = $request->input('code');
        
        $program->is_active = $request->input('is_active');
        $program->save();

        return $this->return_output('flash', 'success', $success_message, '/program', '200');
    }

    public function deleteProgram($program_id)
    {
        Program::destroy($program_id);
        return $this->return_output('flash', 'success', 'Category deleted successfully', '/program', '200');
    }
}
