<?php

namespace App\Http\Controllers\web\director;
use App\Models\Course;
use App\Models\Department;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CourseResource;
use App\Http\Resources\LecturerResource;
use App\Http\Resources\CourseLecturerResource;
use App\Http\Resources\DrcourseResource;
use App\Http\Resources\NotificationResource;
use App\Models\school;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


// define( 'API_ACCESS_KEY', Notification::API_ACCESS_KEY);

class CourseController extends Controller
{

    public function index(Request $request)

    {
   $currentUser = Auth::user()->id;
     $courses = User::find($currentUser)->courses;


//         $courses =DB::table('courses')
//    ->select('courses.*', 'course_user.role as tutor_role')
//    ->leftJoin('course_user', 'course_user.course_id', '=', 'courses.id')

//    ->where('course_user.user_id', $currentUser)->get();
     return CourseResource::collection($courses);


    }



    public function saveCourse(Request $request)
    {

        $course_id = $request->courseId;
        // return $course_id;
        // $validation_rules = [
            // 'title' => 'required|string|max:50',
            // 'code' => 'required|string|max:50',
            // 'semester' => 'required|integer|max:5',
            // 'unit' => 'required|integer|max:10',
            // 'level' => 'required|integer|max:1000',
            // 'status' => 'required|string|max:5',
            // 'type' => 'required|string|max:50',
        //     'chatLink' => 'required|string|max:1000'
        // ];

        // $validator = Validator::make($request->all(),$validation_rules);

        // if ($validator->fails()) {
        //     return "Please input appropriate credentials";
        // }

        // if ($course_id) {
            $course = Course::find($course_id);
            $success_message = 'Course updated successfully';
        // } else {
        //     $course = new Course();
        //     $success_message = 'course added successfully';
        // }
        // $course->name = $request->title;
        // $course->code = $request->code;
        // $course->type = $request->type;
        // $course->level = $request->level;
        // $course->semester = $request->semester;
        $course->chatLink = $request->chatLink;
        // $course->unit = $request->unit;
        // $course->status = $request->status;
        $course->save();
        return response()->json($success_message);
    }

    public function deleteCourse($course_id)
    {
        Course::destroy($course_id);
        return response()->json('Course deleted successfully');
    }



    public function courseManagement(Request $request)
    {

        $course_id = $request->course_id;
        $course = Course::find($course_id);


// if ($request->input('department_id')) {
//      $tutors = $request->input('department_id');

//    $counter = count($tutors);

//       for ($x = 0; $x <= ($counter-1) ; $x++) {
//         $isApproved = $course->departments()->wherePivot('department_id', $tutors[$x])->exist();
//   if ($isApproved) {
//     $course->departments()->detach(
//         [$tutors[$x]]
//     );
//   }else {
//     # code...
//     $course->departments()->attach(
//         [$tutors[$x]]
//     );
//   }
//  }

// } else {

    $lead_tutor = $request->lead_tutor;
    $tutor = $request->tutor;
    if (isset($lead_tutor)) {
        $isApproved = DB::table('course_user')->where('user_id', $lead_tutor)->where('course_id', $course_id)->count();
        // return $isApproved;
        if ($isApproved > 0) {
      $course->users()->detach(
          $lead_tutor
          );
          $message = "Lecturer UnAssigned Successfully";

    }else {
      $course->users()->attach(
          [ $lead_tutor => ['role' => 'lead-tutor']]
          );
          $message = "Lecturer Assigned Successfully";

    }
   }
   if (isset($tutor)) {
    $isApproved = DB::table('course_user')->where('user_id', $tutor)->where('course_id', $course_id)->count();
    if ($isApproved >0) {
      $course->users()->detach(
          $tutor
          );
          $message = "Lecturer UnAssigned Successfully";

    }else {
      $course->users()->attach(
          [ $tutor => ['role' => 'tutor']]
          );
          $message = "Lecturer Assigned Successfully";

    }
   }




//    $counter = count($tutors);

//       for ($x = 0; $x <= ($counter-1) ; $x++) {
//         $isApproved = $course->users()->wherePivot('user_id', $lead_tutor)->exist();
//         if ($isApproved) {
//             $course->users()->detach(
//                 $tutors[$x]
//                 );
//                 $message = "Lecturer Unassigned Successfully";

//           }else {
//             $course->users()->attach(
//                 [ $tutors[$x] => ['role' => 'tutor']]

//                 );
//                 $message = "Lecturer Assigned Successfully";
//           }



return response()->json($message);

    }



    public function departmentalCourses(){
        // $id = auth()->user()->id;
        // $departmentId = User::find($id)->department->id;
        //return $departmentId;
        $courses = Course::all();
        return DrcourseResource::collection($courses);

    }
    public function departmentalLecturers(){
        $id = auth()->user()->id;
        $departmentId = User::find($id)->department->id;
        //return $departmentId;
        $lecturers = User::where('department_id',  $departmentId)->where('status', 'lecturer')->get();
        // return $lecturers;
        return CourseLecturerResource::collection($lecturers);
    }
    public function courseLecturers($course_id){
        $lecturers = Course::find($course_id)->users()->where('status', 'lecturer')->get();
        return CourseLecturerResource::collection($lecturers);
    }


    public function leadChapter($course_id = '', $tutor_role = '', Request $request)
    {

        $id = auth()->user()->id;
                 if($tutor_role == 'lead-tutor'){
                     $chapters = Course::find($course_id)->chapters;
                   }else{
                    $chapters = DB::table('chapters')
                    ->select('chapters.*' )
                    ->where('chapters.course_id', $course_id)
                    ->where('chapters.user_id', $id)->get();
                   }
        return view('director.leadcourse.chapter', compact('chapters', 'tutor_role'));
    }

    public function getForm($course_id='', Request $request)
    {

        if($course_id) {
            $course = Course::find($course_id);

        }else{
            $course = $this->getColumnTable('courses');
        }
        return view('director.course.form', compact('course'));
    }
    public function getForm1($course_id='', Request $request)
    {
        $lead_tutors = DB::table('course_user')
        ->select('*')
        ->where('course_id', '=', $course_id)
        ->where('role', '=', 'lead-tutor')
        ->get();

        $sub_tutors = DB::table('course_user')
        ->select('*')
        ->where('course_id', '=', $course_id)
        ->where('role', '=', 'tutor')
        ->get();
          $course_id = $course_id;
        $users = User::role('Director-of-Undergraduate-Studies', 'Tutor')->get();
        return view('director.course.form1', compact('lead_tutors', 'sub_tutors', 'users', 'course_id'));
    }


    public function myNotification()
    {
         $id = Auth::user()->id;
         $notification = Notification::where("user_id", $id)->get();
         return  NotificationResourc::collection($notification);
    }



     public function sendNotification(Request $request)
    {
        $school = school::where('name', 'Undergraduate')->get();
         $user = Auth::user();
         $notification = new Notification();
         $message = 'Notification sent successfully';
         $notification->title = $request->title;
         $notification->user_id = $user->id;
         $notification->body = $request->body;
         $notification->sent_time = Carbon::now();
         if (($user->hasRole('SU PRESIDENT') || $user->hasRole('SU PRO')) && $school->sugurl !=null) {
            $notification->coursecode = $school->sugurl .'/'.      $request->link;
         } else {
            $notification->coursecode = 'uniapp blog baseurl' .'/'.      $request->link;
         }

         $notification->save();
       Notification::pushNotification($request->title,$request->body, $request->link);
         return  response()->json($message);
    }

    public function pushNotification($title, $body, $link=null)
    {
       	#API access key from Google API's Console

		$notificationContent['title']				=	trim($title);
		$notificationContent['body']				=	trim($body);

		$notificationContent['sound'] 				=   "default";
		$notificationContent['content_available'] 	=  true;
		$notificationContent['priority'] 			=   "high";

		$dataContent	=	$notificationContent;
		//$dataContent=[];
		if(isset($link)){
			$dataContent = array_merge($dataContent,["data" => "$link"]);

		}
		$data= [];
		// $data['registration_ids'] 			= $deviceTokens;
		$data['notification']  				= $notificationContent;

		$data['data']  			= $dataContent;
		//echo '<pre>'; print_r($data);

		/*
		if(!$msg['silent']) {
			$fields['notification'] 	= 	$msg;
		}
		*/
		//exit;

		$headers			=	array(
									'Authorization: key=' . API_ACCESS_KEY,
									'Content-Type: application/json'
								);

		#Send Reponse To FireBase Server
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $data ) );
		$result	=	curl_exec($ch );
		curl_close( $ch );

		#Echo Result Of FireBase Server
		//echo $result;

		$jsonResult	=	json_decode($result ,true);
	//	echo '<pre>'; print_r($jsonResult); exit;
		// return (int)@$jsonResult['success'];
    }

}
