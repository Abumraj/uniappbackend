<?php

namespace App\Http\Controllers\web;
use App\Course;
use App\Department;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
class CourseController extends Controller
{


    public function index(Request $request)
    {
        $paginate_count = 100;
        if($request->has('search')){
            $search = $request->input('search');
            $courses = Course::where('name', 'LIKE', '%' . $search . '%')
                           ->paginate($paginate_count);
        }
        else {
            $courses = Course::paginate($paginate_count);
        }
        return view('adminLte.course.index', compact('courses'));
    }


    public function getForm($course_id='', Request $request)
    {

        if($course_id) {
            $course = Course::find($course_id);

        }else{
            $course = $this->getColumnTable('courses');
        }
        return view('adminLte.course.form', compact('course'));
    }
    public function getForm1($course_id='', Request $request)
    {
        $lead_tutors = DB::table('course_user')
        ->select('*')
        ->where('course_id', '=', $course_id)
        ->where('role', '=', 'lead-tutor')
        ->get();

        // if ($lcourse){

        //     $lead_tutors = $lcourse;
        // }
        //dd($lead_tutors);
        $sub_tutors = DB::table('course_user')
        ->select('*')
        ->where('course_id', '=', $course_id)
        ->where('role', '=', 'tutor')
        ->get();
          $course_id = $course_id;
        $users = User::role('Director-of-Undergraduate-Studies', 'Director-of-PostUtme', 'Director-of-Remedial-Studies')->get();
        return view('adminLte.course.form1', compact('lead_tutors', 'sub_tutors', 'users', 'course_id'));
    }

    public function saveCourse(Request $request)
    {
        // echo '<pre>';print_r($_POST);exit;
        $course_id = $request->input('course_id');

        $validation_rules = [
            'name' => 'required|string|max:50',
            'code' => 'required|string|max:50'
    ];

        $validator = Validator::make($request->all(),$validation_rules);

        // Stop if validation fails
        if ($validator->fails()) {
            return $this->return_output('error', 'error', $validator, 'back', '422');
        }

        if ($course_id) {
            $course = Course::find($course_id);
            $success_message = 'Course updated successfully';
        } else {
            $course = new Course();
            $success_message = 'course added successfully';
        }

        $course->name = $request->input('name');
        $course->code = $request->input('code');
        $course->description = $request->input('description');
        $course->price = $request->input('price');
        $course->imageUrl = $request->input('image');
        $course->type = $request->input('type');
        $course->level = $request->input('level');
        $course->semester = $request->input('semester');
        $course->unit = $request->input('unit');
        $course->save();

        return $this->return_output('flash', 'success', $success_message, '/course', '200');
    }



    public function courseManagement(Request $request)
    {
        $course_id = $request->input('course_id');
        $course = Course::find($course_id);
        // dd($request->input('departmenbt_id'));

if ($request->input('department_id')) {
    $course->departments()->syncWithoutDetaching(
        [$request->input("department_id")]
    );

} else {

    $lead_tutor = $request->input('lead_tutor');
    $tutors = $request->input('tutor');
    $course->users()->attach(
        [ $lead_tutor => ['role' => 'lead-tutor']]
        );
        $course->users()->syncWithoutDetaching(
        [ $tutors => ['role' => 'tutor']]
        );
        dd($tutors);
       
}


        return $this->return_output('flash', 'success', 'Category deleted successfully', '/director/course', '200');
    }

    public function courseDepartment(Request $request){
        $course_id = $request->input('course_id');
        $courses =Course::find($course_id)->departments->get();

        return view('adminLte.course.form1', compact('courses','course_id'));
    }
    public function addCourseToDepartment(Request $request){
        $course_id = $request->input('course_id');
        $departments= Department::all();
        // $courses =Course::find($course_id)->departments->get();

        return view('adminLte.course.form1', compact('departments','course_id'));
    }


    public function deleteCourse($course_id)
    {
        Course::destroy($course_id);
        return $this->return_output('flash', 'success', 'Category deleted successfully', '/course', '200');
    }
}




