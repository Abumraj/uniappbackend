<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\AccountDetail;
class AccountDetailController extends Controller
{
    public function index()
    {
        $id  = Auth()->user()->id;
        $programs = DB::table('account_details')->where('user_id', $id)->count();
        // dd($programs);
        if($programs > 0) {
            $accounts = DB::table('account_details')->where('user_id', $id)->select('*')->get();
           $account = $accounts[0];
        }else{
            $account = $this->getColumnTable('account_details');
        }
        return view('adminLte.tutor.account', compact('account'));
    }

    public function saveAccountDetail(Request $request)
    {
        $userId =Auth()->user()->id;

        $account = AccountDetail::find($userId);

        if ($account) {
            $account->accountName = $request->input('name');
            $account->accountNumber = $request->input('number');
            $account->bankName = $request->input('bank');

            $account->save();
                } else {
            $account = new AccountDetail();

            $account->user_id = $userId;
            $account->accountName = $request->input('name');
            $account->accountNumber = $request->input('number');
            $account->bankName = $request->input('bank');

            $account->save();
        }

        return $this->return_output('flash', 'success', 'Category deleted successfully', '/tutor/dashboard', '200');

    }
}
