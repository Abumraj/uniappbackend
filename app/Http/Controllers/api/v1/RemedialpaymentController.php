<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class RemedialpaymentController extends Controller
{

public function transactionInit(Request $request)
    {
        $id = Auth::user()->id;
     $user = User::find($id);
     $reference = $request->reference;
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
            "Authorization: Bearer sk_test_7d00856e31b50448877e4cfe6964e111f3a77a12",
           "Cache-Control: no-cache",
          ),
        ));

         $response = array();
        $result =curl_exec($curl);
        $response = json_decode($result, true);
       $customer_id = $response['data']['customer']['id'];
         if($response['data']['status']=='success')

       {
        $cartCourses = Course::select('id')->where('type', 'remedial')->where('is_active', 'true')->get();
            foreach($cartCourses as $cartCourse){
              $user->courses()->attach($cartCourse);

            }

       }
      $err = curl_error($curl);
       curl_close($curl);

          $curl = curl_init();

        curl_setopt_array($curl, array(
         CURLOPT_URL => "https://api.paystack.co/subscription/?customer=$customer_id",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer sk_test_7d00856e31b50448877e4cfe6964e111f3a77a12",
           "Cache-Control: no-cache",
          ),
        ));

 $resume =curl_exec($curl);

        $response = json_decode($resume, true);

        $str = $response['data'][0]['most_recent_invoice']['period_end'];
        $date = strtotime($str);

       $err = curl_error($curl);
       curl_close($curl);
       $checkSubscription = Subscription::find($id);
       if($checkSubscription){
       $subHouse = $checkSubscription;
       } else{
        $subHouse = new Subscription();
       }
      $subHouse->user_id = $id;
      $subHouse->name = $response['data'][0]['plan']['name'];
      $subHouse->paystack_code = $response['data'][0]['subscription_code'];
      $subHouse->quantity = $response['data'][0]['quantity'];
      $subHouse->paystack_plan = $response['data'][0]['plan']['plan_code'];
      $subHouse->paystack_email_token = $response['data'][0]['email_token'];
      $subHouse->ends_at = date('Y-m-d H:i:s', $date);
      $subHouse->save();
      $user->trial_ends_at = date('Y-m-d H:i:s', $date);
      $user->save();



   echo 'subscription successful';
    }

    public function transactionVerify(Request $request)
     {
      $id = Auth::user()->id;
      $user = User::find($id);
      $subscription = Subscription::where('user_id',$id)->first();

      if($subscription > 0){
       $checkSubscription = Subscription::find($subscription->id);

      }
      else{
       return "You are not currently subscribed to any plan";
      }
      $subscription_code = $checkSubscription->paystack_code;
      $subscription_email_code = $checkSubscription->paystack_email_code;

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.paystack.co/subscription/?subscription_code=$subscription_code",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer sk_test_7d00856e31b50448877e4cfe6964e111f3a77a12",
            "Cache-Control: no-cache",
          ),
        ));

        $response = json_decode(curl_exec($curl), true);


        if ( $response['data'][0]['status'] == 'complete' || $response['data'][0]['status'] == 'cancelled' )
        {

        $checkSubscription->delete();
        $user->courses()->detach();

        return "Subscription Expired";
        } elseif( $response['data'][0]['quantity'] == 'active'){
          $url = "https://api.paystack.co/subscription/disable";
        $fields = [
      'code' => $subscription_code,
      'token' => $subscription_email_code ,
    ];
          $fields_string = http_build_query($fields);
          curl_setopt($curl,CURLOPT_URL, $url);
          curl_setopt($curl,CURLOPT_POST, true);
          curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);
          curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer sk_test_7d00856e31b50448877e4cfe6964e111f3a77a12",
            "Cache-Control: no-cache",
          ),
        );
        $response = json_decode(curl_exec($curl), true);
        $err = curl_error($curl);
        curl_close($curl);
        return 'Subscription Disabled Successfully';

        } else{

         $url = "https://api.paystack.co/subscription/enable";
        $fields = [
      'code' => $subscription_code,
      'token' => $subscription_email_code ,
    ];
          $fields_string = http_build_query($fields);
          curl_setopt($curl,CURLOPT_URL, $url);
          curl_setopt($curl,CURLOPT_POST, true);
          curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);
           curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer sk_test_7d00856e31b50448877e4cfe6964e111f3a77a12",
            "Cache-Control: no-cache",
          ),
        );
        $response = json_decode(curl_exec($curl), true);

        $err = curl_error($curl);
        curl_close($curl);

        return 'Subscription Enabled Successfully';



        }










        $err = curl_error($curl);
        curl_close($curl);

       return $response;
       }
  
}
