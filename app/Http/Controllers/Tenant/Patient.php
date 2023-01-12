<?php

namespace App\Http\Controllers\Tenant;

use ClickSend;
use App\Http\Controllers\Controller;
use App\Imports\PatientsImport;
use App\Models\Tenant\EventsLog;
use App\Models\Tenant\Facility;
use App\Models\Tenant\Location;
use App\Models\Tenant\Patient as Patient_Model;
use App\Models\Tenant\PatientLocation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use App\user;
use App\smspurchasedtransaction;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Smalot\PdfParser\Parser;
use GuzzleHttp\Client;

class Patient extends Controller
{
    protected $views = '';

    public function __construct()
    {

        $this->views = 'tenant';

        $host = explode('.', request()->getHttpHost());

        config(['database.connections.tenant.database' => $host[0]]);
        DB::purge('tenant');
        DB::reconnect('tenant');
        DB::disconnect('tenant');
    } 
    public function patientsDelete(Request $request, $tenantName, $id)
    {
        $patient = Patient_Model::find($id);
        if (!$patient) {
            return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Patient id <strong>' . $id . '</strong> doesnot match in our records.</div>']);
        }

        $patient_name = $patient->first_name . ' ' . $patient->last_name;
        $patient->delete();
        EventsLog::create([
            'website_id'       => $request->session()->get('phrmacy')->website_id,
            'action_by'        => $request->session()->get('phrmacy')->id,
            'action'           => 3,
            'action_detail'    => 'Patient',
            'comment'          => 'Delete Patient',
            'patient_id'       => $patient->id,
            'ip_address'       => $request->ip(),
            'type'             => $request->session()->get('phrmacy')->roll_type,
            'user_agent'       => $request->userAgent(),
            'authenticated_by' => 'packnpeaks',
            'status'           => 1,
        ]);
        return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Patient <strong>' . $patient_name . '</strong> deleted Successfully.</div>']);
    }



/* Soft Delete Return  */
public function  softarchive(Request $request)
{

   $getdata=Patient_Model::where('id','=',$request->id)->first();
   $getdata->is_archive = 1;
   $getdata->save();
   
   $name = $getdata->first_name .' '. $getdata->last_name;

	 return redirect()->back()->with(["msg" => '<div class="alert alert-success">Patient (<strong>' . $name . '</strong>) archived Successfully.</div>']);

}


public function add_exempted_patient(request $request) {
		
		
		
    $companyID = $request->company;
    $patientID = $request->patientID;

    $all_pharmacy = User::all()->where('website_id',$companyID);
    // $all_pharmacy = User::all()->where('company_name',$companyID);
    // echo json_encode($all_pharmacy);exit;
    foreach($all_pharmacy as $row)
    {
        $websiteID = $row->website_id;
     
        $this->get_connection($websiteID);
        $patient = Patient_Model::find($patientID);
        
        // echo json_encode($patientID);exit;
        if($request->isexemption == 0)
        {
            $patient->exempted = 0 ;
        }
        else if($request->isexemption == 1)
        {
            $patient->exempted = 1 ;
        }
        
        $patient->save();

         
         
        DB::disconnect('tenant');
    }
     
    if ($request->isexemption == 0)
    {
        echo '0';
    }
    else
    {
        echo '1';
    }

    
} 

public function exempted_patients(Request $req) {

	$pharmacyid = session()->get('phrmacy')->website_id;
    


    $all_pharmacy = User::where('website_id','=',$pharmacyid)->get();
   
  
    $newarray = array();
    $allPatientsArray = array();
    foreach ($all_pharmacy as $row) {
        
        $get_audit = Patient_Model::all()->where('exempted', '1');
        $all = Patient_Model::all()->where('exempted', '0');
        foreach ($get_audit as $col) {
            $Patientlocations = PatientLocation::where('patient_id', $col->id)->orderBy('created_at', 'desc')->first();
            $col->pharmacy = $row->company_name;
            $col->website_id = $row->website_id;
            if (!empty($Patientlocations)) {
                $col->locations = $Patientlocations->locations;
            }

            $newarray[] = $col;
        }

    
        foreach ($all as $col) {
            $Patientlocations = PatientLocation::where('patient_id', $col->id)->orderBy('created_at', 'desc')->first();
            $col->pharmacy = $row->company_name;
            if (!empty($Patientlocations)) {
                $col->locations = $Patientlocations->locations;
            }

            $allPatientsArray[] = $col;
        }
        
    }

   

    
    
    $data['new_patients'] = $newarray; //print_r($newarray[0]->facility); die;
    $data['all_patients'] = $allPatientsArray;


    // return view($this->views . '.new_patients_report', $data);
    return view($this->views. '.exempted_patients')->with($data);
}

public function  softunarchive(Request $request)
{

   $getdata=Patient_Model::where('id','=',$request->id)->first();
   $getdata->is_archive = 0;
   $getdata->save();
   
   $name = $getdata->first_name .' '. $getdata->last_name;

	 return redirect()->back()->with(["msg" => '<div class="alert alert-success">Patient (<strong>' . $name . '</strong>) unarchived Successfully.</div>']);

}

    public function patients(Request $request)
    {
        

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

        if($Allowedsms == $usedsms)
        {
            $Smssendlimit = 0;
        }
        else 
        {
            $Smssendlimit = 1;
        }

// // Configure HTTP basic authorization: BasicAuth
// $config = ClickSend\Configuration::getDefaultConfiguration()
// ->setUsername('amr_eid@msn.com')
// ->setPassword('5N^u#SLo2!w43SLk');



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


        if ($request->form6 == '1') {
            $host               = explode('.', request()->getHttpHost());
            $data               = array();
            $data['locations']  = Location::get();
            $data['facilities'] = Facility::get();
            return view($this->views . '.patients', $data)->with('Allowedsms',$Allowedsms)->with('usedsms',$usedsms)
            ->with('Smssendlimit',$Smssendlimit);
        } else {
            return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
        }

    }

    public function patientsEdit(Request $request, $tenantName, $id)
    {


        if ($request->form7 == '1') {
            $patient = Patient_Model::find($id);
            if (!$patient) {
                return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Patient id <strong>' . $id . '</strong> doesnot match in our records.</div>']);
            }

            if ($request->isMethod('post')) {

                $validateArr = array(
                    'first_name'    => 'required|string|max:255',
                    'last_name'     => 'required|string|max:255',
                    'dob'           => 'required|date_format:d/m/Y|before:tomorrow',
                    'phone_number'  => 'required|min:10|max:10',
                    'facilities_id' => 'required|string|max:255',
                    'mobile_no'     => 'min:10|max:10',
                );

                $insert_data = array(
                    'first_name'                  => $request->first_name,
                    'last_name'                   => $request->last_name,
                    'dob'                         => Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
                    'address'                     => $request->address,
                    'phone_number'                => $request->phone_number,
                    'facilities_id'               => $request->facilities_id,
                    'location'                    => isset($request->location) ? implode(',', $request->location) : '',
                    'text_when_picked_up_deliver' => 0,
                );

                if ($request->has('up_delivered')) {
                    $insert_data['text_when_picked_up_deliver'] = 1;
                    if ($request->has('up_delivered')) {
                        $insert_data['text_when_picked_up_deliver'] = 1;
                        $mobileNo                                   = isset($request->mobile_no) ? $request->mobile_no : '';
                        $phone_number                               = isset($request->phone_number) ? $request->phone_number : '';
                        $insert_data['mobile_no']                   = $mobileNo ? $mobileNo : $phone_number;
                    }

                } else {
                    $insert_data['mobile_no'] = "";
                }

                if (!empty($request->get('facilities_id'))) {
                    $facility = Facility::where('name', $request->get('facilities_id'))->first();
                    if (empty($facility)) {
                        $createNewFacility = Facility::create([
                            'name'       => $request->get('facilities_id'),
                            'created_by' => $request->session()->get('phrmacy')->id,
                            'status'     => '1',
                        ]);
                        $facilityId = $createNewFacility->id;
                    } else {
                        $facilityId = $facility->id;
                    }

                    $insert_data['facilities_id'] = $facilityId;

                }

                $custommessage = array(
                    'phone_number.required' => 'The Mobile Number field is required.',
                );
                $validator = $request->validate($validateArr, $custommessage);
                $patient->update($insert_data);
                //    Patient Location
                    $location_data['locations']=$insert_data['location'];
                    $location_data['patient_id']=$id;
                    $location_data['action_by' ] = $request->session()->get('phrmacy')->id;
                    PatientLocation::insert_data($location_data);
            //    End

                EventsLog::create([
                    'website_id'       => $request->session()->get('phrmacy')->website_id,
                    'action_by'        => $request->session()->get('phrmacy')->id,
                    'action'           => 2,
                    'action_detail'    => 'Patient',
                    'comment'          => 'Update Patient',
                    'patient_id'       => $id,
                    'ip_address'       => $request->ip(),
                    'type'             => $request->session()->get('phrmacy')->roll_type,
                    'user_agent'       => $request->userAgent(),
                    'authenticated_by' => 'packnpeaks',
                    'status'           => 1,
                ]);
                return redirect("new_patients_report")->with(["msg" => '<div class="alert alert-success">Patient <strong>  Updated </strong>  Successfully.</div>']);

            }

            $data                     = array();
            $data['locations']        = Location::get();
            $data['facilities']       = Facility::get();
            $data['patient']          = $patient;
            $data['patient_location'] = PatientLocation::where('patient_id', $id)->get();
            //return $data;
            return view($this->views . '.patientsEdit', $data);
        } else {
            return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
        }
    }

    public function save_patient(Request $request)
    {
        $companyid = 0;
		if(Cookie::get('companyid'))
		{
		$cookie_data = stripslashes(Cookie::get('companyid'));
		$companyid = json_decode($cookie_data, true);
		}


        // return $request->all();die;
        // dd($request->has('mobile_no'));
        if (session()->has('phrmacy') && session()->get('phrmacy')->id > 0) {
            $validateArr = array(
                'first_name'    => 'required|string|max:255',
                'last_name'     => 'required|string|max:255',
                'dob'           => 'required|date_format:d/m/Y|before:tomorrow',
                'phone_number'  => 'required|min:10|max:10',
                'facilities_id' => 'required|string|max:255',
                'mobile_no'     => 'min:10|max:10',
            );


            $gettext = 0;
            if($request->up_delivered != null)
            {
                $gettext = 1;
            }
            $insert_data = array(
                'first_name'                  => $request->first_name,
                'last_name'                   => $request->last_name,
                'dob'                         => Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
                'address'                     => $request->address,
                'phone_number'                => $request->phone_number,
                'location'                    => $request->location ? implode(',', $request->location) : '',
                'text_when_picked_up_deliver' => $gettext,
            );

            if ($request->has('up_delivered')) {
                $insert_data['text_when_picked_up_deliver'] = 1;
                $mobileNo                                   = isset($request->mobile_no) ? $request->mobile_no : '';
                $phone_number                               = isset($request->phone_number) ? $request->phone_number : '';
                $insert_data['mobile_no']                   = $mobileNo ? $mobileNo : $phone_number;
            }



            if (!empty($request->get('facilities_id'))) {
                $facility = Facility::where('name', $request->get('facilities_id'))->first();
                if (empty($facility)) {
                    $createNewFacility = Facility::create([
                        'name'       => $request->get('facilities_id'),
                        'created_by' => $request->session()->get('phrmacy')->id,
                        'status'     => '1',
                    ]);
                    $facilityId = $createNewFacility->id;
                } else {
                    $facilityId = $facility->id;
                }

                $insert_data['facilities_id'] = $facilityId;

            }

            if (!empty($request->session()->get('phrmacy'))) {
                $insert_data['website_id'] = $request->session()->get('phrmacy')->website_id;
                $insert_data['created_by'] = $request->session()->get('phrmacy')->id;

            }

            // $validator = $request->validate($validateArr);
            $custommessage = array(
                'phone_number.required' => 'The Mobile Number field is required.',
            );
            $validator = \Validator::make($request->all(), $validateArr, $custommessage);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }

            
            $getPatient = Patient_Model::where('first_name', $request->first_name)
				->where('last_name', $request->last_name)
				->where('dob', Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'))
				->get();

                


                

            if(count($getPatient))
            {
                // $patientid =  patient::where('first_name', $request->first_name)
				// ->where('last_name', $request->last_name)
				// ->where('dob', Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'))
				// ->get();

                
                Patient_Model::update_where(array('id' => $getPatient[0]->id, 'website_id' => $companyid)
                , $insert_data);
             
                //    Patient Location
                $location_data['locations']  = $insert_data['location'];
                $location_data['patient_id'] = $request->id;
                $location_data['action_by']  = $request->session()->get('phrmacy')->id;
                PatientLocation::insert_data($location_data);
            }
            else
            {
            
                $save_res = Patient_Model::create($insert_data);
            //    Patient Location
            $location_data['locations']  = $insert_data['location'];
            $location_data['patient_id'] = $request->id;
            $location_data['action_by']  = $request->session()->get('phrmacy')->id;
            
            PatientLocation::insert_data($location_data);
            }
            


            // verify sms limit 
            $companyid = 0;
            if(Cookie::get('companyid'))
            {
            $cookie_data = stripslashes(Cookie::get('companyid'));
            $companyid = json_decode($cookie_data, true);
            }
            $purchasedsms = smspurchasedtransaction::where('websiteid','=',$companyid)->sum('noofsms');;
            $userresult = user::where('website_id','=',$companyid)->first();
            // Sum subscription sms and purchased sms
            $Allowedsms = $userresult->Allowedsms;
            $Allowedsms =  $purchasedsms +  $Allowedsms;
            // get used sms count
            $usedsms = $userresult->usedsms;
            $Smssendlimit = 0 ;
            if($Allowedsms == $usedsms)
            {
                $Smssendlimit = 0;
            }
            else 
            {
                $Smssendlimit = 1;
            }

        
        
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

        if($Allowedsms == $usedsms)
        {
            $Smssendlimit = 0;
        }
        else 
        {
            $Smssendlimit = 1;
            
// Configure HTTP basic authorization: BasicAuth
$config = ClickSend\Configuration::getDefaultConfiguration()
->setUsername('amr_eid@msn.com')
->setPassword('5N^u#SLo2!w43SLk');

$apiInstance = new ClickSend\Api\SMSApi(new \GuzzleHttp\Client(),$config);
$msg = new \ClickSend\Model\SmsMessage();
$msg->setBody("Hi welcome to packpeak"); 
$msg->setTo('+61'.$request->phone_number);
// $msg->setTo("+923234774241");
$msg->setSource("+61422222222");


// \ClickSend\Model\SmsMessageCollection | SmsMessageCollection model
$sms_messages = new \ClickSend\Model\SmsMessageCollection(); 
$sms_messages->setMessages([$msg]);

try {
$result = $apiInstance->smsSendPost($sms_messages);
print_r($result);
} catch (Exception $e) {
echo 'Exception when calling SMSApi->smsSendPost: ', $e->getMessage(), PHP_EOL;
}


           
        }



        // SMS Sending transaction
        // pickupNotification::create([
        //     		'patient_id' => $save_res->id,
        //     		'pickup_id' => 0,
        //     		'website_id' => $request->session()->get('phrmacy')->website_id,
        //     		'type' => 'sms',
        //     		'created_by' => $request->session()->get('phrmacy')->id,
        //     		'patientname'=>$request->first_name . ' ' .$request->last_name
        //     		]);

        // Email sending Transaction
        EventsLog::create([
                'website_id'       => $request->session()->get('phrmacy')->website_id,
                'action_by'        => $request->session()->get('phrmacy')->id,
                'action'           => 1,
                'action_detail'    => 'Patient',
                'comment'          => 'Create Patient',
                'patient_id'       => $request->id,
                'ip_address'       => $request->ip(),
                'type'             => $request->session()->get('phrmacy')->roll_type,
                'user_agent'       => $request->userAgent(),
                'authenticated_by' => 'packnpeaks',
                'status'           => 1,
            ]);
            return response()->json(['success' => 1, 'errors' => ""]);
            // return redirect()->back()->with(["msg"=>'<div class="alert alert-success">Patient <strong>'.$request->first_name.' '.$request->last_name.'</strong> Added Successfully.</div>']);
        } else {
            return response()->json(['success' => 0, 'errors' => array('login' => 'Login Error !')]);
            //return redirect($this->views.'-login')->with(["msg"=>'<div class="alert alert-danger><strong>Wrong </strong> First you can do login !!!</div>']);
        }
    }

    public function new_patients_report(Request $request)
    {
        if ($request->form7 == '1') {
            $data['patient_reports'] = Patient_Model::where('is_archive','=',0)->get();
           
            return view($this->views . '.new_patients_report', $data);
        } else {
            return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
        }
    }

    public function archived_patients_report(Request $request)
    {
        if ($request->form7 == '1') {
            $data['patient_reports'] = Patient_Model::where('is_archive','=',1)->get();
            return view($this->views . '.archived_patients_report', $data);
        } else {
            return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
        }
    }

    public function patients_notification()
    {
        if ($request->form7 == '1') {
            $data['patient_reports'] = Patient_Model::get();
            return view($this->views . '.patients_notification', $data);
        } else {
            return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
        }
    }
    public function notification(Request $request, $tenantName, $id)
    {
        $patient = Patient_Model::find($id);
        if (!$patient) {
            return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Patient id <strong>' . $id . '</strong> doesnot match in our records.</div>']);
        }

        $patient_name = $patient->first_name . ' ' . $patient->last_name;
        if ($patient->notification == '1') {
            $update = array('notification' => null);
        } else {
            $update = array('notification' => 1);
        }
        $patient->update($update);
        EventsLog::create([
            'website_id'       => $request->session()->get('phrmacy')->website_id,
            'action_by'        => $request->session()->get('phrmacy')->id,
            'action'           => ($patient->notification == '1') ? 6 : 7,
            'action_detail'    => 'Patient Notification',
            'comment'          => ' Patient Notification',
            'patient_id'       => $patient->id,
            'ip_address'       => $request->ip(),
            'type'             => $request->session()->get('phrmacy')->roll_type,
            'user_agent'       => $request->userAgent(),
            'authenticated_by' => 'packnpeaks',
            'status'           => 1,
        ]);
        return redirect()->back()->with(["msg" => '<div class="alert alert-success">Patient <strong>' . $patient_name . '</strong> Notification update .</div>']);
    }

    public function import_patients(Request $request)
    {
          
     
        $validateArr = array(
            'patient_file' => 'required',
        );
        $validator = $request->validate($validateArr);
        $files     = $request->file('patient_file')->store('import');
        $file      = $request->file('patient_file');
        if($request->file('patient_file')->extension() == 'pdf'){
            // echo "file is pdf";exit;
            $fileName = $file->getClientOriginalName();

            $client = new Client();
            //$res = $client->post('https://ocr.yehtohoga.com/api/docfile/DataExtraction?authKey=10005-P10225-10000', 
            $res = $client->post('https://api.pharmaanywhere.com.au/api/docfile/DataExtraction?authKey=10005-P10225-10000', 
                [
                    'multipart' => [
                        [
                            'name'     => 'FileContents',
                            'contents' => file_get_contents($file),
                            //'contents' =>'',
                            'filename' => $fileName
                        ],
                        [
                            'name'     => 'FileInfo',
                            'contents' => json_encode($file)
                            //'contents' =>''
                            ]

                    ],
                ]);

                
            
            // echo $res->getStatusCode(); // 200
            $content = json_decode($res->getBody(), true);
            // echo "<pre>";

            $patientss = $content[0]['PatientData'];

            
         
            // print_r($content[0]['PatientData']);
            if(isset($content[0]['PatientData'])){
                        $import = new PatientsImport;
                 //start pdf import
                    foreach($patientss as $pat){
                        
                        // dd($pat);
                        if (session()->has('phrmacy') && session()->get('phrmacy')->id > 0) {
                             if(!isset($pat['location'])){
                                $pat['location'] = '';
                             }

                            $insert_data = array(
                                'first_name'                  => $pat['FirstName'],
                                'last_name'                   => $pat['LastName'],
                                'dob'                         => Carbon::createFromFormat('d/m/Y', $pat['DOB'])->format('Y-m-d'),
                                'address'                     => $pat['Address'],
                                'phone_number'                => $pat['PhoneNumber'],
                                'location'                    => $pat['location'] ? implode(',', $pat['location']) : '',
                                'text_when_picked_up_deliver' => 0,
                            );

                            if ($request->has('up_delivered')) {
                                $insert_data['text_when_picked_up_deliver'] = 1;
                                $mobileNo                                   = isset($pat['MobileNo']) ? $pat['MobileNo'] : '';
                                $phone_number                               = isset($pat['PhoneNumber']) ? $pat['PhoneNumber'] : '';
                                $insert_data['mobile_no']                   = $mobileNo ? $mobileNo : $phone_number;
                            }

                            if (!empty($pat['Facility'])) 
                            // if (!empty($request->get('Facility'))) 
                            {
                                $facility = Facility::where('name', $pat['Facility'])->first();
                                // $facility = Facility::where('name', $request->get('Facility'))->first();

                                
                                if (empty($facility)) {
                                    $createNewFacility = Facility::create([
                                        'name'       => $pat['Facility'],
                                        // 'name'       => $request->get('Facility'),
                                        'created_by' => $request->session()->get('phrmacy')->id,
                                        'status'     => '1',
                                    ]);
                                    $facilityId = $createNewFacility->id;
                                } else {
                                    $facilityId = $facility->id;
                                }

                                $insert_data['facilities_id'] = $facilityId;

                            }

                            if (!empty($request->session()->get('phrmacy'))) {
                                $insert_data['website_id'] = $request->session()->get('phrmacy')->website_id;
                                $insert_data['created_by'] = $request->session()->get('phrmacy')->id;

                            }

                            // $validator = $request->validate($validateArr);
                            $custommessage = array(
                                'phone_number.required' => 'The Mobile Number field is required.',
                            );
                                
                            $save_res = Patient_Model::create($insert_data);
                            //    Patient Location
                            $location_data['locations']  = $insert_data['location'];
                            $location_data['patient_id'] = $save_res->id;
                            $location_data['action_by']  = $request->session()->get('phrmacy')->id;
                            PatientLocation::insert_data($location_data);
                            //    End
                            EventsLog::create([
                                'website_id'       => $request->session()->get('phrmacy')->website_id,
                                'action_by'        => $request->session()->get('phrmacy')->id,
                                'action'           => 1,
                                'action_detail'    => 'Patient',
                                'comment'          => 'Create Patient',
                                'patient_id'       => $save_res->id,
                                'ip_address'       => $request->ip(),
                                'type'             => $request->session()->get('phrmacy')->roll_type,
                                'user_agent'       => $request->userAgent(),
                                'authenticated_by' => 'packnpeaks',
                                'status'           => 1,
                            ]);
                             
                    }
                }
                return redirect()->back()->with(["msg" => '<div class="alert alert-success"> <strong>Patient </strong> Added Successfully.</div>']);
                //pdf import end
            }
            exit;
        }
        // $import=new PatientsImport;
        // Excel::import(new PatientsImport,$files);

        // (new PatientsImport)->import($files);
        $import = new PatientsImport;
        $import->import($files);
        // $import->import(request()->file('patient_file'));
        //Excel::import(new PatientsImport,request()->file('patient_file'));
        EventsLog::create([
            'website_id'       => $request->session()->get('phrmacy')->website_id,
            'action_by'        => $request->session()->get('phrmacy')->id,
            'action'           => 1,
            'action_detail'    => 'Patient Import',
            'comment'          => ' Patient Import',
            'patient_id'       => null,
            'ip_address'       => $request->ip(),
            'type'             => $request->session()->get('phrmacy')->roll_type,
            'user_agent'       => $request->userAgent(),
            'authenticated_by' => 'packnpeaks',
            'status'           => 1,
        ]);
        return redirect()->back()->with(["msg" => '<div class="alert alert-success"> <strong>Patient </strong> Added Successfully.</div>']);

    }

    /*  check Duplicate patient */
    public function checkduplicatePatient(Request $request)
    {
        // print_r($request->all()); die;
        if ($request->first_name && $request->last_name && $request->dob) {
            $getPatient = Patient_Model::where('first_name', $request->first_name)
                ->where('last_name', $request->last_name)
                ->where('dob', Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'))
                ->get();
            if (count($getPatient)) {
                echo '1'; //  records  exit
            } else {
                echo '0'; //  records not  exit
            }
        } else {
            echo '401';
        }
    }

}
