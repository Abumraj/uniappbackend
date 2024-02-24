<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Http\Resources\SchoolResource;
use App\Http\Resources\UsertypeResource;
use App\Models\Program;
use App\Models\school;
use App\Models\level;
use App\Models\usertype;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function program()
    {
        $faculties = Program::all();

        return  $faculties;
    }


    public function school()
    {
          $school= school:: all();
          return SchoolResource::collection($school) ;
        // find($code)->schools;
        // foreach ($schoolses as $school) {
        //     return program::find($school->id)->schools;
        // }
    }

    public function channel()
    {
        $schoolChannel = Usertype::orderBy('id', 'desc')->get();
        return  UsertypeResource::collection($schoolChannel);
    }
    public function level()
    {
        $schoolChannel = Level::orderBy('id', 'desc')->get();
        return $schoolChannel;

    }

}
