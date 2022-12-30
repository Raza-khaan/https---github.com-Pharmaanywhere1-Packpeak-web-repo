<?php

namespace App\Http\Controllers\Admin\Auth;

use Auth;
use Password;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Admin;
use Illuminate\Support\Facades\Hash;
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
    
    public function showLinkRequestForm(Request $request)
    {
       return view('admin.auth.passwords.email'); 
    }

    public  function sendResetLinkEmail(Request $request){

        $validate_array=array(
            'email'     => 'required|email|max:255',
         ); 
         $validator = $request->validate($validate_array);

        $getAdmin=Admin::where('email',$request->email)->first(); 

        // echo json_encode($getAdmin); die; 
        if(!empty($getAdmin))
        {
            $details = $getAdmin;
            //   base64_encode
            // echo "asda";exit;
            //echo $details;
            
            Mail::to($getAdmin->email)->send(new \App\Mail\AdminMail($details));
            return redirect()->back()->with(["msg"=>'<div class="alert alert-success">Link send to  e-mail address.</div>']);
            
            
            // return redirect()->back()->with(["msg"=>'<div class="alert alert-success">We have <strong> e-mailed </strong> your password reset link!.</div>']);
            echo "sent";
            if (Mail::failures()) 
            {
                // return response showing failed emails
                echo "failed";
                dd("Email failed");
            }
        }
        else
        {
            return redirect()->back()->with(["msg"=>'<div class="alert alert-danger">We can`t find a user with that e-mail address.</div>']);
        }
       
    }

    /**
     * Reset  Password 
    */

    public  function  reset(Request $request){
        $rowId=base64_decode($request->row_id);
        $getAdmin=Admin::find($rowId);
        if(!empty($getAdmin)){
               $getAdmin->row_id=$request->row_id;
               $data['getDetails']=$getAdmin;
               return view('admin.auth.passwords.reset')->with($data); 
        }
        else{
            return redirect('admin/passwords/reset')->with(["msg"=>'<div class="alert alert-danger">first send a  reset  password link </div>']);
        }
    }


    function changepassword(Request $request)
    {

        // dump($request->email);
        // return;
        $getAdmin=Admin::where('email',$request->email)->first();
        // dump($getAdmin);
        if(!empty($getAdmin))
         {

            $email =$request->email;
            $data = array('Email'=>$request->email);        
      
            $headers = "MIME-Version: 1.0" . "\r\n"; 
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            
            Mail::send(['html'=>'forgetmail'], $data, function($message) use ($email)
            {
                
               $message->to($email, 'Reset Password')
               ->subject('Reset Password');
               $message->from('info@dieseltech.studio','CashMyMortgage');           
            },$headers);


            return view('admin.auth.passwords.changepassword',['email'=>$request->email]); 
         }
        else
        {
            return redirect('admin/passwords/reset')->with(["msg"=>'<div class="alert alert-danger">
            Email not registered 
            </div>']);
        }
        // return view('admin.auth.passwords.changepassword',['email'=>$request->email]); 
    }
    
    function updatepasswords(Request $request)
    {
        $getAdmin=Admin::where('email',$request->email)->first();
        $getAdmin->password =$req->password;
        $getAdmin->name ='PackPeakss';
        
        
        $getAdmin->save();
        return view('admin.login');
        // return view('admin.auth.passwords.changepassword',['email'=>$request->email]); 
    }




    

    /**
     * Update Admin Password 
    */
    public function  updatePassword(Request $request){
        $rowId=base64_decode($request->row_id);
        $getAdmin=Admin::find($rowId);
        if(!empty($getAdmin)){
            $validate_array=array(
                'password'     => 'required|string|min:6|max:255',
                'password_confirmation'     => 'required|string|min:6|max:255|same:password',
             ); 
             $validator = $request->validate($validate_array);
            //  Hash::make($request['password'])
            $getAdmin=Admin::where('id',$rowId)->update(array('password'=>Hash::make($request->password))); 
            return redirect('admin-login')->with(["msg"=>'<div class="alert alert-success">Password Changed Successfully.. </div>']);
        }
        else{
            return redirect('admin/passwords/reset')->with(["msg"=>'<div class="alert alert-danger">first send a  reset  password link </div>']);
        }
    }

}
