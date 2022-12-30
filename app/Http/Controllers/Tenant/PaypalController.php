<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use URL;
use Session;
use Redirect;
use Illuminate\Support\Carbon;
use Input;
// Used to process plans
use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\ShippingAddress;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use App\User;
use App\paypaltransaction;
use App\smspurchasedtransaction;

use App\Models\Tenant\Company;
use  App\Models\Admin\Subscription;
use DB;

class PaypalController extends Controller
{
    private $_api_context;
    private $apiContext;
    private $mode;
    private $client_id;
    private $secret;

    private $plan_id;
    
    public function __construct()
    {
            
        $paypal_configuration = \Config::get('paypal');

        if(env('PAYPAL_MODE','sandbox') == 'sandbox'){
            $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_configuration['sandbox_client_id'], $paypal_configuration['sandbox_secret']));
        }else{
            $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_configuration['live_client_id'], $paypal_configuration['live_secret']));
        }
        
        $this->_api_context->setConfig($paypal_configuration['settings']);


//        use to send sms paypal transaction
  //      Detect if we are running in live mode or sandbox
        //   if(config('paypal.settings.mode') == 'live'){
        //     $this->client_id = config('paypal.live_client_id');
        //     $this->secret = config('paypal.live_secret');
        // } else {
        //     $this->client_id = config('paypal.sandbox_client_id');
        //     $this->secret = config('paypal.sandbox_secret');
        //     $this->plan_id = env('PAYPAL_SANDBOX_Basic_PLAN_ID', '');

        // }
        
        // // Set the Paypal API Context/Credentials
        // $this->apiContext = new ApiContext(new OAuthTokenCredential($this->client_id, $this->secret));
        // $this->apiContext->setConfig(config('paypal.settings'));
    }



    public function create_plan()
    {

        // Create a new billing plan
        $plan = new Plan();
        $plan->setName('Premium Subscription Plan')
          ->setDescription('Premium Subscription')
          ->setType('infinite');

        // Set billing plan definitions
        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Premium Payments')
          ->setType('REGULAR')
          ->setFrequency('Month')
          ->setFrequencyInterval('1')
          ->setCycles('0')
          ->setAmount(new Currency(array('value' => 35, 'currency' => 'USD')));
          
        // Set merchant preferences
        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl('http://chsdndhj.packpeak.com.au/subscribe/paypal/return')
          ->setCancelUrl('http://chsdndhj.packpeak.com.au/subscriptions')
          ->setAutoBillAmount('yes')
          ->setInitialFailAmountAction('CONTINUE')
          ->setMaxFailAttempts('0');

        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);

        //create the plan
        try {
            $createdPlan = $plan->create($this->apiContext);
            
            try {
                $patch = new Patch();
                $value = new PayPalModel('{"state":"ACTIVE"}');
                $patch->setOp('replace')
                  ->setPath('/')
                  ->setValue($value);
                $patchRequest = new PatchRequest();
                $patchRequest->addPatch($patch);
                $createdPlan->update($patchRequest, $this->apiContext);
                $plan = Plan::get($createdPlan->getId(), $this->apiContext);

                // Output plan id
                echo 'Plan ID:' . $plan->getId();
            } catch (PayPal\Exception\PayPalConnectionException $ex) {
                echo $ex->getCode();
                echo $ex->getData();
                die($ex);
            } catch (Exception $ex) {
                die($ex);
            }
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }

    }

    public function update_plan(){

    try {
        $patch = new Patch();
    
        $value = new PayPalModel('{
               "state":"ACTIVE"
             }');
    
        $patch->setOp('replace')
            ->setPath('/')
            ->setValue($value);
        $patchRequest = new PatchRequest();
        $patchRequest->addPatch($patch);
    
        $createdPlan->update($patchRequest, $apiContext);
    
        $plan = Plan::get($createdPlan->getId(), $apiContext);
    } catch (Exception $ex) {
        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        ResultPrinter::printError("Updated the Plan to Active State", "Plan", null, $patchRequest, $ex);
        exit(1);
    }
    
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
     ResultPrinter::printResult("Updated the Plan to Active State", "Plan", $plan->getId(), $patchRequest, $plan);
    
    return $plan;

}

    public function paypalRedirect($id){
        // Create new agreement
        $plan_id = $id;
        $agreement = new Agreement();
        $agreement->setName('Packpeak Monthly Subscription Agreement')
          ->setDescription('Packpeak Monthly Subscription Agreement')
          ->setStartDate(\Carbon\Carbon::now()->addMinutes(5)->toIso8601String());

        // Set plan id
        $plan = new Plan();
        $plan->setId($plan_id);
        $agreement->setPlan($plan);

        // Add payer type
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $agreement->setPayer($payer);

        try {
          // Create agreement
          $agreement = $agreement->create($this->apiContext);

          // Extract approval URL to redirect user
          $approvalUrl = $agreement->getApprovalLink();

          return redirect($approvalUrl);
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
          echo $ex->getCode();
          echo $ex->getData();
          die($ex);
        } catch (Exception $ex) {
          die($ex);
        }

    }

    public function paypalReturn(Request $request){

        $token = $request->token;
        $agreement = new \PayPal\Api\Agreement();

        try {
            // Execute agreement
            $result = $agreement->execute($token, $this->apiContext);
            echo "Basic Result";
            echo "<pre>";
            print_r($result);
            // $user = Auth::user();
            // $user->role = 'subscriber';
            // $user->paypal = 1;
            // if(isset($result->id)){
            //     $user->paypal_agreement_id = $result->id;
            // }
            // $user->save();

            echo 'New Subscriber Created and Billed';

        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            echo 'You have either cancelled the request or your session has expired';
        }
    }


    public function paywithpaypal(Request $request)
    {
      
         $maindatabase = user::where('website_id','=',$request->website_id)->get();

      $subscriptiondetails = Subscription::where('id','=',$request->subscription_id)->get();

      
        return view('paywithpaypal')->with('website_id',$request->website_id)
        ->with('Host_Name',$maindatabase[0]->host_name)
        ->with('package',$subscriptiondetails[0]->title)->with('Amount',$subscriptiondetails[0]->amount)
        ->with('row_id',$request->row_id)->with('subscription_id',$request->subscription_id)
        ->with('Admin',$subscriptiondetails[0]->no_of_admins)->with('users',$subscriptiondetails[0]->no_of_technicians)
        ->with('SMS',$subscriptiondetails[0]->allowed_sms);
    }



    public function postPaymentWithpaypal(Request $request)
    {
   
        
        $subsctriptionid = 0;
        $websiteid = 0;
        $rowid = 0;
        $packageid = 0;
        $transactiontype = "";

        $price = 0;
        $noofsms = 0;

        if($request->subscription_id != null)
        {
            $subsctriptionid = $request->subscription_id;
        }

        if($request->txtwebsiteid != null)
        {
            $websiteid = $request->txtwebsiteid;
        }

        if($request->row_id != null)
        {
            $rowid = $request->row_id;
        }

        if($request->txtpackageid != null)
        {
            $packageid = $request->txtpackageid;
        }


        if($request->txttotalsms != null)
        {
            $noofsms = $request->txttotalsms;
        }

        if($request->txttotalprice != null)
        {
            $amount = $request->txttotalprice;
        }

      $payer = new Payer();
      $payer->setPaymentMethod('paypal');
      $item_1 = new Item();
      $item_1->setName('Product 1')
          ->setCurrency('AUD')
          ->setQuantity(1)
          ->setPrice($request->get('amount'));
      $item_list = new ItemList();
      $item_list->setItems(array($item_1));
      $amount = new Amount();
      $amount->setCurrency('AUD')
          ->setTotal($request->get('amount'));
      $transaction = new Transaction();
      $transaction->setAmount($amount)
          ->setItemList($item_list)
          ->setDescription('Enter Your transaction description');

      $redirect_urls = new RedirectUrls();
      $redirect_urls->setReturnUrl(URL::route('status'))
          ->setCancelUrl(URL::route('status'));

      $payment = new Payment();
      $payment->setIntent('Sale')
          ->setPayer($payer)
          ->setRedirectUrls($redirect_urls)
          ->setTransactions(array($transaction));            
      try {
          $payment->create($this->_api_context);
         
      } catch (\PayPal\Exception\PPConnectionException $ex)
       {
          if (\Config::get('app.debug')) {
              \Session::put('error','Connection timeout');
              return Redirect::route('paywithpaypal');                
          } else {
              \Session::put('error','Some error occur, sorry for inconvenient');
              return Redirect::route('paywithpaypal');                
          }
      }

      foreach($payment->getLinks() as $link) {
          if($link->getRel() == 'approval_url') {
              $redirect_url = $link->getHref();
              break;
          }
      }
      
      
      Session::put('paypal_payment_id', $payment->getId());
      Session::put('subscriptionid', $subsctriptionid);
      Session::put('websiteid', $websiteid);
      Session::put('rowid', $rowid);
      Session::put('packageid', $packageid);
      Session::put('transactiontype', $request->transactiontype);

      Session::put('noofsms', $noofsms);
      Session::put('price', $amount);
      
      if(isset($redirect_url)) {            
          return Redirect::away($redirect_url);
      }
    
      \Session::put('error','Unknown error occurred');
      // dump($redirect_url);
      // return;
    //  return Redirect::route("/");

    return Redirect::route('paywithpaypal');

      // return view('paywithpaypal')->with('website_id',$request->website_id)
      // ->with('Host_Name',$maindatabase[0]->host_name)
      // ->with('package',$subscriptiondetails[0]->title)->with('Amount',$subscriptiondetails[0]->amount)
      // ->with('row_id',$request->row_id)->with('subscription_id',$request->subscription_id);
      
    }

    public function getPaymentStatus(Request $request)
    {        
      
        $payment_id = Session::get('paypal_payment_id');
        $subscriptionid = Session::get('subscriptionid');
        $websiteid = Session::get('websiteid');
        $rowid = Session::get('rowid');

        $packageid = Session::get('packageid');
        $transactiontype = Session::get('transactiontype');

        $noofsms = Session::get('noofsms');
        $price = Session::get('price');

        Session::forget('paypal_payment_id');
        if (empty($request->input('PayerID')) || empty($request->input('token'))) {
            \Session::put('error','Payment failed');
            return Redirect::route('paywithpaypal');
        }
        $payment = Payment::get($payment_id, $this->_api_context);        
        $execution = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));        
        $result = $payment->execute($execution, $this->_api_context);
        
        if ($result->getState() == 'approved') 
        {         
            \Session::put('success','Payment success !!');
            $subscriptiondetails = Subscription::where('id','=',$subscriptionid)->get();

            if($transactiontype == 'Subscription' )
           {
                // Update expire date in user main database table
                $userresult = user::where('website_id','=',$websiteid)->first();
                $userresult->expired_date =  Carbon::now()->addDays($subscriptiondetails[0]->plan_validity);
                $userresult->save();

                // update expiry date in tenancy pharmacy      
                $this->get_connection($websiteid);
                Company::update_where(array('id' => $rowid, 'website_id' => $websiteid), array('expired_date' =>Carbon::now()->addDays($subscriptiondetails[0]->plan_validity)));      
                DB::disconnect('tenant');

                paypaltransaction::create([
                'subscriptionid' => $subscriptionid,
                'subscription_name' => $subscriptiondetails[0]->title,
                // 'amount' => $request->session()->get('phrmacy')->website_id,
                'amount' => $subscriptiondetails[0]->amount,
                'transactionid' => 'test',
                'transactiondate' => Carbon::now(),
                'paymentstatus' => 'paid',
                'companyid' => $websiteid,
                'userid' => $rowid,
                'created_at' => Carbon::now(),
                'expirydate' => Carbon::now()->addDays($subscriptiondetails[0]->plan_validity),
                'created_by' => 1,
                ]);
                return redirect("/");
            }
            else if($transactiontype == 'SMS')
            {
                smspurchasedtransaction::create([
                    'noofsms' => $noofsms,
                    'price' => $price,
                    'amount' => $price,
                    'websiteid' => $websiteid,
                    'created_at' => Carbon::now(),
                    'created_by' => 1,
                    'transactionid' => '1',
                    'packageid' => $packageid,
                    ]);


                return redirect("Sms_settings");
            }
           
      
              

         
        }

        \Session::put('error','Payment failed !!');
        return Redirect::route('paywithpaypal')->with('website_id',$request->website_id)
      ->with('row_id',$request->row_id)->with('subscription_id',$request->subscription_id);
    }


   
}