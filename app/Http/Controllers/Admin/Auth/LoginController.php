<?php

namespace App\Http\Controllers\Admin\Auth;

use ClickSend;
use GuzzleHttp\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Auth;
use Srmklive\PayPal\Services\ExpressCheckout;


use App\Models\Admin\Admin;
use App\Models\Admin\EventsLog; 
use DB; 
use App\User;
use App\Admins;
use Illuminate\Support\Facades\Hash;
use Notification;
use App\Notifications\SignupVerification;
use Session;
use App\Providers\RouteServiceProvider;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating admin users for the application and
    | redirecting them to your admin dashboard.
    |
    */
    
    /**
     * This trait has all the login throttling functionality.
     */
    use ThrottlesLogins;
    
    /**
    * Max login attempts allowed.
    */
    public $maxAttempts = 3;

    /**
    * Number of minutes to lock the login.
    */
    public $decayMinutes = 10;

    /**
     * Only guests for "admin" guard are allowed except
     * for logout.
     * 
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest:admin')->except('logout');
    }

    protected function guard() {
        return Auth::guard('admin');
    }
    
    /**
     * Username used in ThrottlesLogins trait
     * 
     * @return string
     */
    public function username(){
        return 'email';
    }
    
    /**
     * Show the login form.
     * 
     * @return \Illuminate\Http\Response
     */

/**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        dd('Your payment is canceled. You can create cancel page here.');
    }
  
    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function success(Request $request)
    {

        $provider = new ExpressCheckout;
        $response = $provider->getExpressCheckoutDetails($request->token);
  
       
        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            dd('Your payment was successfully. You can create success page here.');
        }
  
        dd('Something is wrong.');
    }

    public function showLoginForm()
    {   


        
        // $data = [];
        // $data['items'] = [
        //     [
        //         'name' => 'ItSolutionStuff.com',
        //         'price' => 100,
        //         'desc'  => 'Description for ItSolutionStuff.com',
        //         'qty' => 1
        //     ]
        // ];
  
        // $data['invoice_id'] = 1;
        // $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        // $data['return_url'] = route('payment.success');
        // $data['cancel_url'] = route('payment.cancel');
        // $data['total'] = 100;
  
        // $provider = new ExpressCheckout;
  
        // $response = $provider->setExpressCheckout($data);
        // return redirect($response['paypal_link']);

        
        
// // Configure HTTP basic authorization: BasicAuth
// $config = ClickSend\Configuration::getDefaultConfiguration()
// ->setUsername('amr_eid@msn.com')
// ->setPassword('B#!H9GYomwYBuuiw');



// $apiInstance = new ClickSend\Api\SMSApi(new \GuzzleHttp\Client(),$config);
// $msg = new \ClickSend\Model\SmsMessage();
// $msg->setBody("test body"); 
// $msg->setTo("+923234774241");
// $msg->setSource("+61422222222");


// // \ClickSend\Model\SmsMessageCollection | SmsMessageCollection model
// $sms_messages = new \ClickSend\Model\SmsMessageCollection(); 
// $sms_messages->setMessages([$msg]);

// try {
// $result = $apiInstance->smsSendPost($sms_messages);
// print_r($result);
// } catch (Exception $e) {
// echo 'Exception when calling SMSApi->smsSendPost: ', $e->getMessage(), PHP_EOL;
// }

// return;


        $super_admin = Admin::where('status', 1)->count();
        // return view('super-admin.auth.login');
        return view('admin.login');
    }
    
    /**
     * Validate the form data.
     * 
     * @param \Illuminate\Http\Request $request
     * @return 
     */
    private function validator(Request $request)
    {
        //validation rules.
        $rules = [
            'email'    => 'required|email|exists:admins|min:5|max:191',
            'password' => 'required|string|min:4|max:255',
        ];

        //custom validation error messages.
        $messages = [
            'email.exists' => __('These credentials do not match our records.'),
        ];

        //validate the request.
        $request->validate($rules,$messages);
    }

    /* public function login(Request $request) {
        $this->validator($request);
        
        //check if the user has too many login attempts.
        if ($this->hasTooManyLoginAttempts($request)){
            //Fire the lockout event.
            $this->fireLockoutEvent($request);

            //redirect the user back after lockout.
            return $this->sendLockoutResponse($request);
        }
        
        if (Auth::guard('admin')->attempt($request->only('email','password'), $request->filled('remember'))) {
            //Authenticated
            return redirect()->route('super-admin.dashboard')->with('status', __('Login failed, please try again!'));
        }
        
        //keep track of login attempts from the user.
        $this->incrementLoginAttempts($request);
        
        return redirect()->back()->with('error', __('Login failed, please try again!'));
    } */

    public  function sign_in(Request $request){  

        
        $validatedData = $request->validate(array('email' => 'required','password' => 'required'));
        $user_details = Admin::get_super_admin($request);
        // print_r($user_details);
        // die("Hi Pradeep");

        

        // $request->session()->put('admin',$user_details); 
        // EventsLog::create([
        //    'website_id' => null,
        //    'action_by' => $request->session()->get('admin')->id,
        //    'action' => 4,
        //    'action_detail' => 'SuperAdmin',
        //    'comment' => 'Login SuperAdmin',
        //    'patient_id' => null,
        //    'ip_address' => $request->ip(),
        //    'type' => 'SuperAdmin',
        //    'user_agent' => $request->userAgent(),
        //    'authenticated_by' => 'packnpeaks',
        //    'status' => 1
        //   ]);


          //return redirect('admin/dashboard')->with(["login_success"=>'<div class="alert alert-success""><strong>Success </strong> Login Successfully  !!! </div>']);
          
        if($user_details != NULL)
        {
            if(Hash::check($request->password, $user_details->password))
             { 

        //         dump($request->password);
        //         dump("1");
        // return;
                     $request->session()->put('admin',$user_details); 
                     EventsLog::create([
                        'website_id' => null,
                        'action_by' => $request->session()->get('admin')->id,
                        'action' => 4,
                        'action_detail' => 'SuperAdmin',
                        'comment' => 'Login SuperAdmin',
                        'patient_id' => null,
                        'ip_address' => $request->ip(),
                        'type' => 'SuperAdmin',
                        'user_agent' => $request->userAgent(),
                        'authenticated_by' => 'packnpeaks',
                        'status' => 1
                       ]);

                     // $request->session()->get('admin'); 
                    // session(['users_roll_type' =>'admin','user_id'=>$user_details->id,'email'=>$user_details->email,'name'=>$user_details->name]); 
                    // Auth::login($user_details); 
                    return redirect('admin/dashboard')->with(["login_success"=>'<div class="alert alert-success""><strong>Success </strong> Login Successfully  !!! </div>']);
             }
            else
            {    
                    return redirect('admin-login')->with(["msg"=>'<div class="alert alert-danger""><strong>Wrong </strong> Password does not match !!!</div>']);  
            }		
        }
        else
        { 
          
            return redirect('admin-login')->with(["msg"=>'<div class="alert alert-danger""><strong>Wrong </strong> Email does not  match with this credential !!! </div>']);
        } 
    }

    
    /**
     * Logout the admin.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request) { 
          EventsLog::create([
            'website_id' => null,
            'action_by' => $request->session()->get('admin')->id,
            'action' => 5,
            'action_detail' => 'SuperAdmin',
            'comment' => 'Logout SuperAdmin',
            'patient_id' => null,
            'ip_address' => $request->ip(),
            'type' => 'SuperAdmin',
            'user_agent' => $request->userAgent(),
            'authenticated_by' => 'packnpeaks',
            'status' => 1
           ]);
        Session::forget('admin');
        // Auth::logout($user);
        return redirect('admin-login')->with('msg','<div class="alert alert-danger""> You are <strong>logged Out</strong>!</div>');

    }
}
