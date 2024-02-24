<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\cart;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StalitepaymentController extends Controller
{


     public function transactionInit(Request $request)
     {     
      $user = User::find(auth()->user()->id);
            $email = $user->email;
            $amount = $request->amount;
            $koboAmount = $amount * 100;
            $result = array();
            $callback_url = "https://uniapp.ng";
            $result['callback_url'] = $callback_url;

            $url = "https://api.paystack.co/transaction/initialize";
    $fields = [
      'email' => "$email",
      'amount' => "$koboAmount",
      'callback_url' => $callback_url,
    ];
    $fields_string = http_build_query($fields);
    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Authorization: Bearer sk_test_7d00856e31b50448877e4cfe6964e111f3a77a12",
      "Cache-Control: no-cache",
    ));

    //So that curl_exec returns the contents of the cURL; rather than echoing it
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

    //execute post
    $resulting = json_decode(curl_exec($ch));
    $result['paystack'] = $resulting;
    return $result;
     }





     public function transactionVerify(Request $request)
     {
      $id = auth()->user()->id;
      $user = User::find($id);


       if($request->carryOver ){
            $cartCourses = Cart::select('course_id')->where('stalite_id', $id)->get();
            foreach($cartCourses as $cartCourse){
              $user->courses()->attach($cartCourse);
            }
            return response()->json('Registration successful');
            }

      $userCurrentPlanPrice = Subscription::find($user->subscription_id)->plan_price;
      $subscription = Subscription::find($request->subscriptionId);
//        $userFullAccessCourseCount = DB::table('course_user')
//        ->where('user_id', '=', $id)
//  ->where(['is_full_access', '=', 1])->count();

//  $totalCourseToBeGrantedFullAccess = $subscription->quantity - $userFullAccessCourseCount;
 $Amount = $subscription->plan_price - $userCurrentPlanPrice;
 $amountToPay = $Amount - $Amount * 0.01 * $subscription->percent_discount;


         $reference = $request->reference;
        //  $expire_at = Carbon::now()->addMonths(3);




        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.paystack.co/transaction/verify/$reference",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Authorization:  Bearer sk_test_7d00856e31b50448877e4cfe6964e111f3a77a12",
            "Cache-Control: no-cache",
          ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);
          // return $response;
        $err = curl_error($curl);
        curl_close($curl);


        if ($err) {
          return  "cURL Error #:" . $err;
        } elseif ($response["data"]["status"] == "success"){
          if(!$amountToPay*100 < $response["data"]['amount'] ){
            $cartCourses = $request->courseIds;
            log::debug($cartCourses);
            //return $cartCourses[0];
            foreach($cartCourses as $cartCourse){
              // $user->course()->detach();
              DB::table('course_user')
              ->where('user_id', '=', $id)
              ->where('course_id', '=', $cartCourse)
        ->update(['is_full_access' => 1]);
            }
            // for ($i=0; $i < $cartCourse.length-1; $i++) {
                
            // }
            // foreach($cartCourses as $cartCourse){

            // }
            $user->subscription_id = $request->subscriptionId;
            $user->save();
            }
            return response()->json('Your payment was successful');
        }

     }
}
