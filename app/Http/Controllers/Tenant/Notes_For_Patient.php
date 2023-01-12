<?php

namespace App\Http\Controllers\Tenant;

use GuzzleHttp\Client;
use ClickSend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Patient;
use App\Models\Tenant\NotesForPatient;
use App\Models\Tenant\EventsLog; 
use DB;
use Illuminate\Support\Facades\Cookie;
use App\user;
use App\smspurchasedtransaction;
use App\Helpers\Helper;
use Carbon\Carbon;


class Notes_For_Patient extends Controller
{
    protected $views='';
    public function __construct(){
        $this->views='tenant';
        $host=explode('.',request()->getHttpHost());
        config(['database.connections.tenant.database' => $host[0]]);
        DB::purge('tenant');
        DB::reconnect('tenant');
        DB::disconnect('tenant'); 
    }
    
    public  function  notes_for_patients(Request $request)
    {

        if($request->form19=='1'){

            $companyid = 0;
            if(Cookie::get('companyid'))
            {
            $cookie_data = stripslashes(Cookie::get('companyid'));
            $companyid = json_decode($cookie_data, true);
            }
    
            $purchasedsms = smspurchasedtransaction::where('websiteid','=',$companyid)->sum('noofsms');;
            
    
            $userresult = user::where('website_id','=',$companyid)->first();
    
            $Allowedsms = $userresult->Allowedsms;
            $Allowedsms =  $purchasedsms +  $Allowedsms;
    
            $usedsms = $userresult->usedsms;
    
            $Smssendlimit = 0 ;
    
            if($Allowedsms <= $usedsms)
            {
                $Smssendlimit = 0;
            }
            else 
            {
                $Smssendlimit = 1;
            }

           


        $data['patients']=Patient::get();
        return view($this->views.'.notes_for_patients',$data)->with('Allowedsms',$Allowedsms)->with('usedsms',$usedsms)
            ->with('Smssendlimit',$Smssendlimit); 
        }
        else{
            return redirect('dashboard')->with(["msg"=>'<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
        }
    }


    public  function  notes_for_patients_report(Request $request)
    {
        if($request->form20=='1'){
        $data=[];
        $data['notes_for_patients']=NotesForPatient::where('is_archive','=',0)->orderBy('id','DESC')->get();  
        return view($this->views.'.notes_for_patients_report',$data); 
        }
        else{
            return redirect('dashboard')->with(["msg"=>'<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
        }
    }


    public function archive_notes_for_patients_report(Request $request) {
        
            $data=[];
            $data['notes_for_patients']=NotesForPatient::where('is_archive','=',1)->orderBy('id','DESC')->get();  
            return view($this->views.'.archive_notes_for_patients_report',$data); 
        
	}


/* Soft Delete Return  */
public function  softarchive(Request $request)
{

	$getdata=NotesForPatient::where('id','=',$request->id)->first();
	$getdata->is_archive = 1;
	$getdata->save();
	
	$patient_name = Patient::where('id','=',$getdata->patient_id)->first();
	$name = $patient_name->first_name .' '. $patient_name->last_name;
 
	return redirect()->back()->with(["msg" => '<div class="alert alert-success">Patient Notes (<strong>' . $name . '</strong>) archived Successfully.</div>']);

 }


public function  softunarchive(Request $request)
{

   $getdata=NotesForPatient::where('id','=',$request->id)->first();
   $getdata->is_archive = 0;
   $getdata->save();
   
   $patient_name = Patient::where('id','=',$getdata->patient_id)->first();
   $name = $patient_name->first_name .' '. $patient_name->last_name;

	 return redirect()->back()->with(["msg" => '<div class="alert alert-success">Patient Notes(<strong>' . $name . '</strong>) unarchived Successfully.</div>']);


}

    public function sms_tracking_report()
    {
        $data=[];
        $all_patients=NotesForPatient::where(['notes_as_text'=>1])->get(); 
        //return $all_patients->all();
        //return  $data['notes_for_patients'];
        $sms_trackings=[];
        foreach($all_patients as $all_patient){
             
             if(array_key_exists($all_patient->patient_id,$sms_trackings)){
                $dateArr=explode('-',$all_patient->created_at);
                $sms_trackings[$all_patient->patient_id][(int)$dateArr[1]]['total']+=1;

             }else{
                
                $sms_trackings[$all_patient->patient_id]=$this->monthArr();
                //echo '<pre>';print_r($sms_trackings);
             }
        }
        //return $sms_trackings;
        $data['notes_for_patients']=NotesForPatient::groupBy('patient_id')->where(['notes_as_text'=>1])->get(); 
        $data['sms_trackings']=$sms_trackings; 
        return view($this->views.'.sms_tracking_report',$data); 
    }

    public  function save_notes_for_patients(Request $request)
    {    

        $patientdetail = Patient::where('id','=',$request->patient_id)->first();
        
         $validate_array=array(
            'patient_id'      => 'required|numeric|min:1',
            'dob'               => 'date_format:d/m/Y|before:tomorrow',
            'notes_for_patients'=> 'required|string|max:10000',
            'notes_as_text'     => 'max:1|min:0'
         ); 
         $insert_data=array(
             'patient_id'=>$request->patient_id,
             'dob'=>Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
             'notes_for_patients'=>$request->notes_for_patients
         ); 

        $insert_data['notes_as_text']=$request->has('notes_as_text')?1:0; 
        if(!empty($request->session()->get('phrmacy')))
        {
            $insert_data['website_id']=$request->session()->get('phrmacy')->website_id; 
            $insert_data['created_by']='-'.$request->session()->get('phrmacy')->id;
        }

        if(!empty($request->session()->get('phrmacy')))
        {
            $insert_data['website_id']=$request->session()->get('phrmacy')->website_id; 
            $insert_data['created_by']='-'.$request->session()->get('phrmacy')->id;
        }
        $validator = $request->validate($validate_array);
        $save_res=NotesForPatient::insert_data($insert_data);

        


        if($request->notes_as_text == 1)
        {

            // Configure HTTP basic authorization: BasicAuth
            $config = ClickSend\Configuration::getDefaultConfiguration()
            ->setUsername('amr_eid@msn.com')
            ->setPassword('B#!H9GYomwYBuuiw');

            $apiInstance = new ClickSend\Api\SMSApi(new \GuzzleHttp\Client(),$config);
            $msg = new \ClickSend\Model\SmsMessage();
            $msg->setBody($request->notes_for_patients); 
            //$msg->setTo('+61'.$patientdetail->phone_number);
            $msg->setTo("+923234774241");
            $msg->setSource("+61422222222");

                 // \ClickSend\Model\SmsMessageCollection | SmsMessageCollection model
        $sms_messages = new \ClickSend\Model\SmsMessageCollection(); 
        $sms_messages->setMessages([$msg]);

        try {
        $result = $apiInstance->smsSendPost($sms_messages);
        } catch (Exception $e) {
        echo 'Exception when calling SMSApi->smsSendPost: ', $e->getMessage(), PHP_EOL;
        }
        }

       

        EventsLog::create([
            'website_id' => $request->session()->get('phrmacy')->website_id,
            'action_by' => $request->session()->get('phrmacy')->id,
            'action' => 1,
            'action_detail' => 'Note For Patient',
            'comment' => 'Create Note For Patient',
            'patient_id' => $request->patient_id,
            'ip_address' => $request->ip(),
            'type' => $request->session()->get('phrmacy')->roll_type,
            'user_agent' => $request->userAgent(),
            'authenticated_by' => 'packnpeaks',
            'status' => 1
          ]);
        // if($insert_data['notes_as_text']==1 && preg_match('/^\d{s10}$/',$save_res->patients->phone_number)) 
        if($insert_data['notes_as_text']=='1' && preg_match('/^[6789]\d{9}$/',$save_res->patients->phone_number)) 
        {   //  AUS No  REG// ^\({0,1}((0|\+61)(2|4|3|7|8)){0,1}\){0,1}( |-){0,1}[0-9]{2}( |-){0,1}[0-9]{2}( |-){0,1}[0-9]{1}( |-){0,1}[0-9]{3}$ 
          $result=Helper::smsSendToMobile('+91'.$save_res->patients->phone_number,$insert_data['notes_for_patients']);
        }
        /*else 
        {
          return redirect()->back()->with(["msg"=>'<div class="alert alert-danger"> Patient Phone Number<strong>  Invalid ! </strong> .</div>']);
        }*/     
        return redirect()->back()->with(["msg"=>'<div class="alert alert-success"> <strong> Patient Notes </strong> Added Successfully.</div>']);
    }

    public  function notes_for_patientsDelete(Request $request,$tenantName,$id)
    {
        $delete=NotesForPatient::find($id);
        if(!$delete){
            return redirect()->back()->with(["msg"=>'<div class="alert alert-danger">Patient Notes id <strong>'.$id.'</strong> doesnot match in our records.</div>']);
        }

        $patient_name=$delete->patients->first_name.' '.$delete->patients->last_name;
        $delete->delete();
        EventsLog::create([
            'website_id' => $request->session()->get('phrmacy')->website_id,
            'action_by' => $request->session()->get('phrmacy')->id,
            'action' => 3,
            'action_detail' => 'Note For Patient',
            'comment' => 'Delete Note For Patient',
            'patient_id' => $delete->patient_id,
            'ip_address' => $request->ip(),
            'type' => $request->session()->get('phrmacy')->roll_type,
            'user_agent' => $request->userAgent(),
            'authenticated_by' => 'packnpeaks',
            'status' => 1
         ]);
        return redirect()->back()->with(["msg"=>'<div class="alert alert-danger">Notes of this patient (<strong>'.$patient_name.'</strong>) deleted Successfully.</div>']);
    }

    /* edit Return  */
    public  function  notes_for_patientsEdit(Request $request,$tenantName,$id)
    {       
        if($request->form20=='1'){
        $ob=NotesForPatient::find($id);
        if(!$ob){
           return redirect()->back()->with(["msg"=>'<div class="alert alert-danger">Patient Notes id <strong>'.$id.'</strong> doesnot match in our records.</div>']);
        }

        if ($request->isMethod('post')) {
            //return $request->all();
            $validate_array=array(
                'patient_id'      => 'required|numeric|min:1',
                'dob'               => 'date_format:d/m/Y|before:tomorrow',
                'notes_for_patients'=> 'required|string|max:10000',
                'notes_as_text'     => 'max:1|min:0'
            ); 
            
            $insert_data=array(
                'patient_id'=>$request->patient_id,
                'dob'=> Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
                'notes_for_patients'=>$request->notes_for_patients
            ); 
   
           $insert_data['notes_as_text']=$request->has('notes_as_text')?1:0; 
   
           
           $validator = $request->validate($validate_array);
            //return $insert_data;
            $ob->update($insert_data);


            if($request->notes_as_text == 1)
            {
    
                // Configure HTTP basic authorization: BasicAuth
                $config = ClickSend\Configuration::getDefaultConfiguration()
                ->setUsername('amr_eid@msn.com')
                ->setPassword('B#!H9GYomwYBuuiw');
    
                $apiInstance = new ClickSend\Api\SMSApi(new \GuzzleHttp\Client(),$config);
                $msg = new \ClickSend\Model\SmsMessage();
                $msg->setBody($request->notes_for_patients); 
                $msg->setTo('+92'.$request->phonenumber);
                //$msg->setTo("+923234774241");
                $msg->setSource("+61422222222");
    
            // \ClickSend\Model\SmsMessageCollection | SmsMessageCollection model
            $sms_messages = new \ClickSend\Model\SmsMessageCollection(); 
            $sms_messages->setMessages([$msg]);
    
            try 
                {
                $result = $apiInstance->smsSendPost($sms_messages);
                }
                catch (Exception $e)
                {
                    echo 'Exception when calling SMSApi->smsSendPost: ', $e->getMessage(), PHP_EOL;
                }
            }

            EventsLog::create([
                'website_id' => $request->session()->get('phrmacy')->website_id,
                'action_by' => $request->session()->get('phrmacy')->id,
                'action' => 2,
                'action_detail' => 'Note For Patient',
                'comment' => 'Update Note For Patient',
                'patient_id' => $request->patient_id,
                'ip_address' => $request->ip(),
                'type' => $request->session()->get('phrmacy')->roll_type,
                'user_agent' => $request->userAgent(),
                'authenticated_by' => 'packnpeaks',
                'status' => 1
              ]);
            if($insert_data['notes_as_text']==1 && preg_match('/^[6789]\d{9}$/',$ob->patients->phone_number) ){
                $sendSms=Helper::smsSendToMobile('+91'.$ob->patients->phone_number,$insert_data['notes_for_patients']);
            }
            // else 
            // {
            //   return redirect()->back()->with(["msg"=>'<div class="alert alert-danger"> Patient Notes Updated and  Phone Number<strong>  Invalid ! </strong> .</div>']);
            // }


       
       

          return redirect('notes_for_patients_report')->with(["msg"=>'<div class="alert alert-success">Note for Patient  <strong> Updated </strong> Successfully.</div>']);


        }

        $data=array();
        $data['patients']=Patient::get();
        $data['notes_for_patients']=$ob;
       // return $ob;
        return  view($this->views.'.notes_for_patientsEdit',$data); 

        }
        else{
            return redirect('dashboard')->with(["msg"=>'<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
        }

    }

    protected function monthArr(){
        //echo '<br/>'.$patient_id;
        $momnthArr[1]['total']=0;
        $momnthArr[2]['total']=0;
        $momnthArr[3]['total']=0;
        $momnthArr[4]['total']=0;
        $momnthArr[5]['total']=0;
        $momnthArr[6]['total']=0;
        $momnthArr[7]['total']=0;
        $momnthArr[8]['total']=0;
        $momnthArr[9]['total']=0;
        $momnthArr[10]['total']=0;
        $momnthArr[11]['total']=0;
        $momnthArr[12]['total']=0;

        return $momnthArr;
        
    }


}
