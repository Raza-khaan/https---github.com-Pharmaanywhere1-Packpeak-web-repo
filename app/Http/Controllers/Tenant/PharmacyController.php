<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Authentication_log; 
use App\Models\Tenant\EventsLog;
use App\Models\Tenant\Company;
use Illuminate\Support\Carbon;
use Session;
use Illuminate\Support\Str;

class PharmacyController extends Controller
{
    public function __construct() {
        parent::__construct();
        // $this->middleware('auth:pharmacy');
    }

    public function logout(request $request) {  

         $company=$request->session()->get('phrmacy');
        
        if ($company) {   
            $company_name = $company->company_name; 
            $company_logo = $company->logo ? asset('storage/'.$company->logo) : asset('media/logos/logo.png');  
            $ip = request()->ip();
            $userAgent = request()->userAgent();
            $provider='packnpeaks';
            $insert_data=array(
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'logout_at' => Carbon::now(),
            'authenticated_by' => $provider,
            'uid' =>$company->id,
            'type' =>$company->roll_type,
            'website_id' =>$company->website_id
            );

            Authentication_log::insert($insert_data);
            EventsLog::create([
                'website_id' => $company->website_id,
                'action_by' => $company->id,
                'action' => 5,
                'action_detail' => 'logout',
                'comment' => 'logout',
                'ip_address' => request()->ip(),
                'type' => $company->roll_type,
                'user_agent' => request()->userAgent(),
                'authenticated_by' => 'packnpeaks',
                'status' => 1
              ]);

            Session::forget('phrmacy');
            return redirect('/')->with('msg','<div class="alert alert-danger"> You are logged out <strong>successfully!</strong></div>'); 
            // return view('tenant.auth.login',compact('account', 'company_name', 'company_logo'))->with('msg','<div class="alert alert-danger"> You are logged out <strong>successfully!</strong>');
        } 
       
    }

   
}
