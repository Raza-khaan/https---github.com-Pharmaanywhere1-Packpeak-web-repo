<?php

namespace App\Http\Controllers\Tenant\Auth;

use Auth;
use Password;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Admin;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant\Company;
use App\Models\Phermacist;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Mail;
class ForgotPasswordController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset emails and
      | includes a trait which assists in sending these notifications from
      | your application to your users. Feel free to explore this trait.
      |
     */

    use SendsPasswordResetEmails;

    /**
     * Only guests for "admin" guard are allowed except
     * for logout.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin');
    }
    
    /**
     * password broker for admin guard.
     * 
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker(){
        return Password::broker('admins');
    }

    /**
     * Get the guard to be used during authentication
     * after password reset.
     * 
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    
    

    public function showLinkRequestForm(Request $request,$account)
    {  
        $company = Company::first();
        $company_name = $company->company_name; 
        $company_logo = $company->logo ? asset('storage/'.$company->logo) : asset('media/logos/logo.png'); 
       return view('tenant.auth.passwords.email',compact('account', 'company_name', 'company_logo')); 
    }

    public  function sendResetLinkEmail(Request $request){

        $validate_array=array(
            'email'     => 'required|email|max:255',
         ); 
         $validator = $request->validate($validate_array);

        $getAdmin=Company::where('email',$request->email)->first(); 
        // echo json_encode($getAdmin); die; 
        if(!empty($getAdmin)){
            $details = $getAdmin;
            //   base64_encode
            Mail::to($getAdmin->email)->send(new \App\Mail\PharmacyMail($details));
            return redirect()->back()->with(["msg"=>'<div class="alert alert-success">We have <strong> e-mailed </strong> your password reset link!.</div>']);
        }
        else{
            return redirect()->back()->with(["msg"=>'<div class="alert alert-danger">We can`t find a user with that e-mail address.</div>']);
        }
       
    }

    /**
     * Reset  Password 
    */

    public  function  reset(Request $request,$account){ 
        $rowId=base64_decode($request->row_id);
        $getAdmin=Company::find($rowId);
        if(!empty($getAdmin)){
               $getAdmin->row_id=$request->row_id;
              
               $getAdmin->account=$account;
               $data['getDetails']=$getAdmin;
               
               return view('tenant.auth.passwords.reset')->with($data); 
        }
        else{
            return redirect('passwords/reset')->with(["msg"=>'<div class="alert alert-danger">first send a  reset  password link </div>']);
        }
    }

    /**
     * Update Admin Password 
    */
    public function  updatePassword(Request $request){
        $rowId=base64_decode($request->row_id);
        $getAdmin=Company::find($rowId);
        if(!empty($getAdmin)){
            $validate_array=array(
                'password'     => 'required|string|min:6|max:255',
                'password_confirmation'     => 'required|string|min:6|max:255|same:password',
             ); 
             $validator = $request->validate($validate_array);
            //  Hash::make($request['password'])
            $updateCompany=Company::where('id',$rowId)->update(array('password'=>Hash::make($request->password))); 
            $getPhermacist=Phermacist::where('email',$getAdmin->email)->first(); 
            if(!empty($getPhermacist)){
                $UpdatePhermacist=Phermacist::where('id',$getPhermacist->id)->update(array('password'=>Hash::make($request->password)));
            }
            $getUser=User::where('email',$getAdmin->email)->first(); 
            if(!empty($getUser)){
                $updateUser=Phermacist::where('id',$getUser->id)->update(array('password'=>Hash::make($request->password)));
            }
            return redirect('admin-login')->with(["msg"=>'<div class="alert alert-success">Password Changed Successfully.. </div>']);
        }
        else{
            return redirect('passwords/reset')->with(["msg"=>'<div class="alert alert-danger">first send a  reset  password link </div>']);
        }
    }

}
