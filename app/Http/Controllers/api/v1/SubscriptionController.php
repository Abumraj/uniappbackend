<?php

namespace App\Http\Controllers\api\v1;

use App\Semester;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function remedialSubscription()
    {
        $subscription = Subscription::where('type', Subscription::REMEDIAL_PLAN)->get();

            return  SubscriptionResource::collection($subscription);
    }
    public function aspirantSubscription()
    {
        $subscription = Subscription::where('type', Subscription::ASPIRANT_PLAN)->get();

            return SubscriptionResource::collection($subscription);
    }
    public function staliteSubscription()
    {

        $subscription = Subscription::where('type', Subscription::UNDERGRADUATE_PLAN)->get();
        // $departmentCourses = DB::table('courses')
        // ->join('course_department', 'courses.id', '=', 'course_department.course_id')
        // ->where('course_department.department_id', 1 )
        // ->where('courses.semester', Semester::IS_ACTIVE)->select('courses.id')->get();


            return      SubscriptionResource::collection($subscription);
    }
    public function jupebSubscription()
    {
        $subscription = Subscription::where('type', Subscription::JUPEB_PLAN)->get();

            return SubscriptionResource::collection($subscription);
    }
    public function ijmbSubscription()
    {
        $subscription = Subscription::where('type', Subscription::IJMB_PLAN)->get();

            return SubscriptionResource::collection($subscription);
    }
    public function sandwichSubscription()
    {
        $subscription = Subscription::where('type', Subscription::SANDWICH_PLAN)->get();

            return SubscriptionResource::collection($subscription);
    }
}
