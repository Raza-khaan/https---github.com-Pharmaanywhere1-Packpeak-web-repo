<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use App\Models\Tenant\Authentication_log; 
use App\Models\Tenant\EventsLog;
use App\Models\Tenant\Company;
use App\paypaltransaction;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Auth;
use Notification;
use  App\Models\Admin\Subscription;
use App\Notifications\SignupVerification;
use Session;
use App\Providers\RouteServiceProvider;

use Illuminate\Support\Carbon;
use Yadahan\AuthenticationLog\AuthenticationLog;
use Yadahan\AuthenticationLog\Notifications\NewDevice;
use App\Models\Tenant\Employee;
use Illuminate\Support\Str;
// use Srmklive\PayPal\Services\ExpressCheckout;
use DB;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:pharmacy')->except('logout');
    }
    protected function guard() {
      // return Auth::guard('pharmacy');
    }
  
    public function showLoginForm(Request $request,$account)
    {    
      
     
      //  if($request->session()->has('phrmacy')){
      //    return redirect('dashboard');
      //  }
      //  else{
      //   $company = Company::first();
      //   // dd(\DB::connection());
      //   $company_name = $company->company_name; 
      //   $company_logo = $company->logo ? asset('storage/'.$company->logo) : asset('media/logos/logo.png'); 
      //   // $password_settings = \App\Models\Tenant\PasswordConfiguration::first();
      //   // $auth_settings = \App\Models\Tenant\AuthenticationSetting::first();
      //   return view('tenant.auth.login', compact('account', 'company_name', 'company_logo'));
      //  }
    
      $company = Company::first();
    
      $company_logo = $company->logo ? asset('storage/'.$company->logo) : asset('media/logos/logo.png'); 
     
        $company_name = $company->company_name; 
       
       return view('tenant.auth.login', compact('account', 'company_name', 'company_logo'));
    }

    public function paypal(Request $request)
    {

      // dump($request->website_id);
      // dump($request->row_id);
      // dump($request->subscription_id);

      

      $subscriptiondetails = Subscription::where('id','=',$request->subscription_id)->get();
     

      // Update expire date in user main database table
      $userresult = user::where('website_id','=',$request->website_id)->first();
      $userresult->expired_date =  Carbon::now()->addDays($subscriptiondetails[0]->plan_validity);
      $userresult->save();

      // update expiry date in tenancy pharmacy      
      $this->get_connection($request->website_id);
      Company::update_where(array('id' => $request->row_id, 'website_id' => $request->website_id), array('expired_date' =>Carbon::now()->addDays($subscriptiondetails[0]->plan_validity)));      
      DB::disconnect('tenant');

      paypaltransaction::create([
        'subscriptionid' => $request->subscription_id,
        'subscription_name' => $subscriptiondetails[0]->title,
        // 'amount' => $request->session()->get('phrmacy')->website_id,
        'amount' => $subscriptiondetails[0]->amount,
        'transactionid' => 'test',
        'transactiondate' => Carbon::now(),
        'paymentstatus' => 'paid',
        'companyid' => $request->website_id,
        'userid' => $request->row_id,
        'created_at' => Carbon::now(),
        'expirydate' => Carbon::now()->addDays($subscriptiondetails[0]->plan_validity),
        'created_by' => 1,
        ]);
      

        return redirect("/");

      // $data = [];
      //   $data['items'] = [
      //       [
      //           'name' => 'ItSolutionStuff.com',
      //           'price' => 100,
      //           'desc'  => 'Description for ItSolutionStuff.com',
      //           'qty' => 1
      //       ]
      //   ];
  
      //   $data['invoice_id'] = 1;
      //   $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
      //   $data['return_url'] = route('success');
      //   $data['cancel_url'] = route('cancel');
      //   $data['total'] = 100;
  
      //   $provider = new ExpressCheckout;
  
      //   $response = $provider->setExpressCheckout($data);
  
      //   $response = $provider->setExpressCheckout($data, true);
  
      //   return redirect($response['paypal_link']);
      
      // dump("payment successfull");
      // return redirect("/");
      
    }

    public function isverification(Request $request)
    {
      $users = DB::table('phermacist')->where('email','=',$request->email)->get();
      return $users[0]->verification_status;
    }

    public function sendverificationemail(Request $request)
    {

     
      $users = DB::table('phermacist')->where('email','=',$request->email)->get();
      $details['name'] = $users[0]->first_name . ' ' . $users[0]->last_name;
      $details['details'] = "Account Verification";
      $details['id'] = $users[0]->email;
      $email = $users[0]->email;
      \Mail::to($email)->send(new \App\Mail\VerificationEmail($details));
      return redirect("/dashboard");
    }

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
        $response = $provider->getExpressCheckoutDetails($request->token);
  
        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            dd('Your payment was successfully. You can create success page here.');
        }
  
        dd('Something is wrong.');
    }

    public function pharmacylogin(Request $request) {

          $request->validate(array(
              'email' => ['required', 'string', 'email', 'max:190'],
              //'password' => ['required', 'string'],
          ));  
          
          $company = Company::getdatabycolumn_name("email",$request->email);

           if($company->isEmpty())
           {
            $company = user::where('email','=',$request->email)->get();
           } 
          $time = strtotime(Carbon::now());
        $newformat = date('Y-m-d',$time);
        
     
          if (count($company) ) {
            if(Hash::check($request->password, $company[0]->password))
            { 


 
              $maindatabase = user::where('website_id','=',$company[0]->website_id)->get();
              

              $companyid = json_encode($company[0]->website_id);
              $minutes = 3600;
              Cookie::queue(Cookie::make('companyid', $companyid, $minutes));

             
              

              $request->session()->put('phrmacy',$company[0]);
              if($company[0]->status=='1'){
              
                $this->createLoginLog($request,$company[0]);
                EventsLog::create([
                  'website_id' => $company[0]->website_id,
                  'action_by' => $company[0]->id,
                  'action' => 4,
                  'action_detail' => 'login',
                  'comment' => 'login',
                  'ip_address' => $request->ip(),
                  'type' => $company[0]->roll_type,
                  'user_agent' => $request->userAgent(),
                  'authenticated_by' => 'packnpeaks',
                  'status' => 1
                ]);
                
                return redirect('dashboard');
                dd('aa');
                return;
                // return redirect('admin-login')->with('account', $account);
            }
            else{
               return redirect()->back()->with(["msg"=>'<div class="alert alert-danger""><strong>Wrong </strong> You are blocked . Please contact to  admin . !!!</div>']);
            }
      
      //         if($maindatabase[0]->status == 0)
      //         {
                
      //           return redirect()->back()->with(["msg"=>'<div class="alert alert-danger""><strong>Wrong </strong>
      //           Your pharmacy is not active
      //             . Please contact to admin . !!!
      //              </div>']);
      //         }

      //         if($maindatabase[0]->expired_date < $newformat )
      //         // if($company[0]->expired_date < $newformat )
      //        {
              
      //           if ($request->email <> $maindatabase[0]->email )
      //           {
                 
      //             return redirect()->back()->with(["msg"=>'<div class="alert alert-danger""><strong>Wrong </strong>Your Subscription  has been expired
      //             . Please contact to admin . !!!
      //             <a style="display:none" href="paywithpaypal/'. $company[0]->website_id .'/'. $company[0]->id .'/'. $company[0]->subscription.'" class="btn btn-primary text-center mt-3" style="width:100%">Continue</a>

                  
      //              </div>']);
      //           // <a style="display:none" href="paypal/'. $company[0]->website_id .'/'. $company[0]->id .'/'. $company[0]->subscription.'" class="btn btn-primary text-center mt-3" style="width:100%">Continue</a>
      //             }
      //           else if ($request->email == $maindatabase[0]->email )
      //           {
      //             return redirect()->back()->with(["msg"=>'<div class="alert alert-danger""><strong>Wrong </strong>Your Subscription  has been expired
      //             . Please renew your subscription to continue . !!! 
      //             <a href="paywithpaypal/'. $company[0]->website_id .'/'. $company[0]->id .'/'. $company[0]->subscription.'" class="btn btn-primary text-center mt-3" style="width:100%">Continue</a> </div>']);
      //           } 

      //         }

      //         Session::put('pharmacyemail', $request->email);
              
      //     if($company[0]->status=='1'){
                
      //             $this->createLoginLog($request,$company[0]);
      //             EventsLog::create([
      //               'website_id' => $company[0]->website_id,
      //               'action_by' => $company[0]->id,
      //               'action' => 4,
      //               'action_detail' => 'login',
      //               'comment' => 'login',
      //               'ip_address' => $request->ip(),
      //               'type' => $company[0]->roll_type,
      //               'user_agent' => $request->userAgent(),
      //               'authenticated_by' => 'packnpeaks',
      //               'status' => 1
      //             ]);
      //             return redirect('dashboard')->with('account', $account);
      //             // return redirect('admin-login')->with('account', $account);
      //         }
      //         else{
      //            return redirect()->back()->with(["msg"=>'<div class="alert alert-danger""><strong>Wrong </strong> You are blocked . Please contact to  admin . !!!</div>']);
      //         }
              
      //       }
      //       else
      //       {
      //         return redirect()->back()->with(["msg"=>'<div class="alert alert-danger""><strong>Wrong </strong> Password does not match !!!</div>']);
      //       }
      //     } else {     
      //         return redirect()->back()->with("msg",'<div class="alert alert-danger""><strong>Wrong </strong> Email does not  match with this credential !!! </div>');
      //     }
      // }
            }}}

      private function createLoginLog($request, $user, $provider = 'packnpeaks') {
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        // // $known = $user->authentications()->whereIpAddress($ip)->whereUserAgent($userAgent)->first();
        $insert_data=array(
          'ip_address' => $ip,
          'user_agent' => $userAgent,
          'login_at' => Carbon::now(),
          'authenticated_by' => $provider,
          'uid' =>$user->id,
          'type' =>$user->roll_type,
          'website_id' =>$user->website_id
        );

         return Authentication_log::insert($insert_data);

    }

	public function sign_in(Request $request){


	      $validatedData = $request->validate(array('email' => 'required','password' => 'required'));
        $user_details = User::get_user_details($request); 
       
		if($user_details != NULL){ 
      
		     if(Hash::check($request->password, $user_details->password)){ 
           
           if($user_details->email_verified_at != NULL){  
                    
                    session(['users_roll_type' =>$user_details->roll_id,'user_id'=>$user_details->id,'email'=>$user_details->email,'name'=>$user_details->name,'join_date'=>$user_details->join_date,'image'=>$user_details->image]); 
                    Auth::login($user_details);
                   if($user_details->roll_id > 0 ){
                    return redirect('/dashboard')->with(["login_success"=>'<div class="alert alert-success""><strong>Success </strong> Login Successfully  !!! </div>']);
                   }
				  } 
				 else{   
				   return redirect('admin-login')->with(["msg"=>'<div class="alert alert-danger""><strong>Note </strong> Your email is not verified at , verification code send in your registered mail ,please verify your mail   !!! </div>']);
				  }  
	
				}
		     else{ 
				  return redirect('admin-login')->with(["msg"=>'<div class="alert alert-danger""><strong>Wrong </strong> Password does not match !!!</div>']);  
				}		
		 }
		else{ 
		  return redirect('admin-login')->with(["msg"=>'<div class="alert alert-danger""><strong>Wrong </strong> Email does not  match with this credential !!! </div>']);
		} 
	}
	
}
