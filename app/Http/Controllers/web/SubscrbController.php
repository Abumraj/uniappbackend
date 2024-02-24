<?php

namespace App\Http\Controllers\web;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\AspirantSubscription as Aspirant_subscription;

class SubscrbController extends Controller
{
    public function index(Request $request)
    {
        $paginate_count = 10;
        if($request->has('search')){
            $search = $request->input('search');
            $subscriptionplans = Aspirant_subscription::where('name', 'LIKE', '%' . $search . '%')
                           ->paginate($paginate_count);
        }
        else {
            $subscriptionplans = Aspirant_subscription::paginate($paginate_count);
        }
        
        return view('adminLte.subscriptionplan.index', compact('subscriptionplans'));
    }


    public function getForm($subscriptionplan_id='', Request $request)
    {
        if($subscriptionplan_id) {
            $subscriptionplan = Aspirant_subscription::find($subscriptionplan_id);
        }else{
            $subscriptionplan = $this->getColumnTable('aspirant_subscriptions');
        }
        return view('adminLte.subscriptionplan.form', compact('subscriptionplan'));
    }

    public function saveSubscriptionplan(Request $request)
    {
        // echo '<pre>';print_r($_POST);exit;
        $aspirant_subscription_id = $request->input('subscriptionplan_id');

        $validation_rules = [
            'planName' => 'required|string|max:50',
            'code' => 'required|string|max:50',
            'planPrice' => 'required|integer|max:200000',
            'type' => 'required|string|max:50',
            'description1' => 'required|string|max:150',
            'description2' => 'required|string|max:150',
            'description3' => 'required|string|max:150',
            'description4' => 'required|string|max:150',
            'description5' => 'required|string|max:150'
          
    ];

        $validator = Validator::make($request->all(),$validation_rules);

        // Stop if validation fails
        if ($validator->fails()) {
            return $this->return_output('error', 'error', $validator, 'back', '422');
        }

        if ($aspirant_subscription_id) {
            $subscriptionplan = Aspirant_subscription::find($aspirant_subscription_id);
            $success_message = 'Aspirant_subscription updated successfully';
        } else {
            $subscriptionplan = new Aspirant_subscription();
            $success_message = 'aspirant_subscription added successfully';
        }

        $subscriptionplan->planName = $request->input('planName');
        $subscriptionplan->planPrice = $request->input('planPrice');
        $subscriptionplan->code = $request->input('code');
        $subscriptionplan->type = $request->input('type');
        $subscriptionplan->description1 = $request->input('description1');
        $subscriptionplan->description2 = $request->input('description2');
        $subscriptionplan->description3 = $request->input('description3');
        $subscriptionplan->description4 = $request->input('description4');
        $subscriptionplan->description5 = $request->input('description5');
        $subscriptionplan->save();

        return $this->return_output('flash', 'success', $success_message, '/subscriptionplan', '200');
    }

    public function deleteSubscriptionplan($subscriptionplan_id)
    {
        Aspirant_subscription::destroy($subscriptionplan_id);
        return $this->return_output('flash', 'success', 'Category deleted successfully', '/aspirant_subscription', '200');
    }
}
