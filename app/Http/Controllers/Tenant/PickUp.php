<?php

namespace App\Http\Controllers\Tenant;
use GuzzleHttp\Client;
use ClickSend;
use App\Http\Controllers\Controller;
use App\Models\Tenant\AccessLevel;
use App\Models\Tenant\EventsLog;
use App\Models\Tenant\pickupNotification;
use App\Models\Tenant\Facility;
use App\Models\Tenant\Location;
use App\Models\Tenant\Patient;
use Illuminate\Support\Facades\Cookie;
use App\Models\Tenant\PatientLocation;
use App\Models\Tenant\Pickups;
use App\Models\Admin\Admin;
use  App\Models\Admin\Subscription;
use Carbon\Carbon;
use App\user;
use App\smspurchasedtransaction;
use App\smsprice;
use App\Demo;
use App\paypaltransaction;
use DB;
use Mail;
use Illuminate\Http\Request;
use Session;
use PDF;

class PickUp extends Controller {
	protected $views = '';
	public function __construct() {
		$this->views = 'tenant';
		// dd(session()->all());

		$host = explode('.', request()->getHttpHost());
		//dd($host[0]);
		config(['database.connections.tenant.database' => $host[0]]);
		DB::purge('tenant');
		DB::reconnect('tenant');
		DB::disconnect('tenant');
	}
	/* Start  of  Pickups */
	public function pickups(Request $request) {

		
		if ($request->form1 == '1') {
			$data = array();
			$data['created_at'] = $request->day ? $request->day : "";
			$data['patients'] = Patient::where('is_archive','=',0)->get();
			$data['default_cycle'] = AccessLevel::get('default_cycle');
			//return $data['patients'][0]->latestPickups;
			$data['locations'] = Location::get();
			return view($this->views . '.pickups', $data);
		} else {
			return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
		}
	}

	

	public function Sms_settings() {
        
		$companyid = 0;
		if(Cookie::get('companyid'))
		{
		$cookie_data = stripslashes(Cookie::get('companyid'));
		$companyid = json_decode($cookie_data, true);
		}
        $purchasedsms = smspurchasedtransaction::where('websiteid','=',$companyid)->sum('noofsms');
        $userresult = user::where('website_id','=',$companyid)->first();
		
		$accesslevel =  AccessLevel::where('website_id','=',$companyid)->first();

		$Subscriptiondetails =  Subscription::where('id','=',$userresult->subscription)->first();
		
		$paypaltransactiondetail = paypaltransaction::where('companyid','=',$companyid)->orderByDesc('id')->first();
		if($paypaltransactiondetail != null)
		{
			$subscriptiondate =  $paypaltransactiondetail->transactiondate;
		}
		else
		{
			$subscriptiondate =  "";
		}		

        $Allowedsms = $userresult->Allowedsms;
        $Allowedsms =  $purchasedsms +  $Allowedsms;
        $usedsms = $userresult->usedsms;
		$websiteid = session()->get('phrmacy')->website_id;
        $all_pharmacy = User::all();
        $smsdetails = smsprice::get();
        $data['all_pharmacy'] = $all_pharmacy;
        
		$Availablesms =    $Allowedsms - $usedsms;
		
		return view($this->views . '.Sms_settings')->with($data)->with('smsprice',$smsdetails)
		->with('websiteid',$websiteid)->with('Allowedsms',$Allowedsms)->with('usedsms',$usedsms)
		->with('Availablesms',$Availablesms)
		->with('Packagename',$Subscriptiondetails->title)->with('Allowedadmin',$accesslevel->no_of_admins)
		->with('Alloweduser',$accesslevel->no_of_technicians)
		->with('defaultcycle',$accesslevel->default_cycle)->with('smsallowed',$Subscriptiondetails->allowed_sms)
		->with('subscriptiondate',$subscriptiondate);
    }



/* Soft Delete Return  */
public function  soft_delete_pickup(Request $request)
{
	//$this->get_connection($request->website_id);
   $getdata=Pickups::where('id','=',$request->id)->first();
   $getdata->is_archive = 1;
   $getdata->save();
   
   $patient_name = Patient::where('id','=',$getdata->patient_id)->first();
   $name = $patient_name->first_name .' '. $patient_name->last_name;

	 return redirect()->back()->with(["msg" => '<div class="alert alert-success">Pickups of this patient (<strong>' . $name . '</strong>) archived Successfully.</div>']);

}

public function  softunarchive(Request $request)
{
	//$this->get_connection($request->website_id);
   $getdata=Pickups::where('id','=',$request->id)->first();
   $getdata->is_archive = 0;
   $getdata->save();
   
   $patient_name = Patient::where('id','=',$getdata->patient_id)->first();
   $name = $patient_name->first_name .' '. $patient_name->last_name;

	 return redirect()->back()->with(["msg" => '<div class="alert alert-success">Pickups of this patient (<strong>' . $name . '</strong>) unarchived Successfully.</div>']);

}



	public function pickupsEdit(Request $request, $tenantName, $id) {

		$pickups = Pickups::find($id);
		if (!$pickups) {
			return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Pickups id <strong>' . $id . '</strong> doesnot match in our records.</div>']);
		}

		if ($request->isMethod('post')) {
			//return $request->all();
			$validateArr = array(
				'patient_id' => 'required|numeric|min:1',
				'dob' => 'date_format:d/m/Y|before:tomorrow',
				'no_of_weeks' => 'required|numeric|min:1',
				'pick_up_by' => 'required|string|max:255',
			);

			$insert_data = array(
				'patient_id' => $request->patient_id,
				'dob' => Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
				'no_of_weeks' => $request->no_of_weeks,
				'notes_from_patient' => $request->notes_from_patient,
				'pick_up_by' => $request->pick_up_by,
				'location' => isset($request->location) ? implode(',', $request->location) : '',
				'patient_sign' => $request->patient_sign,
				'pharmacist_sign' => $request->pharmacist_sign,
				'pickup_date'=>'2021-10-01',
	            'pickup_slot'=>'1',
			);

			if ($request->has('pick_up_by') && $request->get('pick_up_by') == 'carer') {
				$validateArr['carer_name'] = 'required';
				$insert_data['carer_name'] = $request->get('carer_name');
			}

			$validator = $request->validate($validateArr);
			$pickups->update($insert_data);
			EventsLog::create([
				'website_id' => $request->session()->get('phrmacy')->website_id,
				'action_by' => $request->session()->get('phrmacy')->id,
				'action' => 2,
				'action_detail' => 'PickUp',
				'comment' => 'Update PickUp',
				'patient_id' => $request->patient_id,
				'ip_address' => $request->ip(),
				'type' => $request->session()->get('phrmacy')->roll_type,
				'user_agent' => $request->userAgent(),
				'authenticated_by' => 'packnpeaks',
				'status' => 1,
			]);
			return redirect('pickups_reports')->with(["msg" => '<div class="alert alert-success">Pickups Report <strong> Updated </strong> .</div>']);

		}

		$data = array();
		$data['patients'] = Patient::get();
		$data['locations'] = Location::get();
		$data['facilities'] = Facility::get();
		$data['pickups'] = $pickups;
		//return $data;
		return view($this->views . '.pickupsEdit', $data);

	}

	public function pickupsShow(Request $request, $tenantName, $id) {
		$pickups = Pickups::find($id);
		if (!$pickups) {
			return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Pickups id <strong>' . $id . '</strong> does not match in our records.</div>']);
		}
		$data = array();
		$data['patients'] = Patient::get();
		$data['locations'] = Location::get();
		$data['facilities'] = Facility::get();
		$data['pickups'] = $pickups;
		//return $data;
		return view($this->views . '.pickupsShow', $data);
	}

	public function pickupsDelete(Request $request, $tenantName, $id) {
		$pickups = Pickups::find($id);
		if (!$pickups) {
			return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Patient id <strong>' . $id . '</strong> doesnot match in our records.</div>']);
		}

		$patient_name = $pickups->patients->first_name . ' ' . $pickups->patients->last_name;
		$pickups->delete();
		EventsLog::create([
			'website_id' => $request->session()->get('phrmacy')->website_id,
			'action_by' => $request->session()->get('phrmacy')->id,
			'action' => 3,
			'action_detail' => 'PickUp',
			'comment' => 'Delete PickUp',
			'patient_id' => $pickups->patient_id,
			'ip_address' => $request->ip(),
			'type' => $request->session()->get('phrmacy')->roll_type,
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Pickups of this patient (<strong>' . $patient_name . '</strong>) deleted Successfully.</div>']);
	}

	public function add_pickups(Request $request)
	{
		
		$data = array('PatientName'=>$request->patientname,'dob'=>$request->dob,'lastpickup'=>$request->last_pick_up_date,
	'noofweekspickup'=>$request->numberofweekspicup);

		if($request->weeks_last_picked_up==null)
		{
			$request->weeks_last_picked_up = $request->no_of_weeks;
		}
		
		// print_r($request->all());die;
		$validateArr = array(
			'patient_id' => 'required|numeric|min:1',
			'dob' => 'date_format:d/m/Y|before:tomorrow',
			'no_of_weeks' => 'required|numeric|min:1',
			'pick_up_by' => 'required|string|max:255',
			'patient_sign' => 'required|string|max:9000',
			'pharmacist_sign' => 'required|string|max:9000',
		);
		$insert_data = array(
			'patient_id' => $request->patient_id,
			'dob' => Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
			'no_of_weeks' => $request->no_of_weeks,
			'notes_from_patient' => $request->notes_from_patient,
			'pick_up_by' => $request->pick_up_by,
			'location' => isset($request->location) ? implode(',', $request->location) : '',
			'patient_sign' => $request->patient_sign,
			'pharmacist_sign' => $request->pharmacist_sign,
			'last_pick_up_date' => $request->last_pick_up_date,
			'weeks_last_picked_up' => $request->weeks_last_picked_up,
		);

		if (!empty($request->created_at)) {
			$request->created_at = substr($request->created_at, 0, 10);
			$created_at = \Carbon\Carbon::createFromFormat('Y-m-d', $request->created_at);
			$insert_data['created_at'] = $created_at;
		}

		if ($request->has('pick_up_by') && $request->get('pick_up_by') == 'carer') {
			$validateArr['carer_name'] = 'required';
			$insert_data['carer_name'] = $request->get('carer_name');
		}

		if (!empty($request->session()->get('phrmacy'))) {
			$insert_data['website_id'] = $request->session()->get('phrmacy')->website_id;
			$insert_data['created_by'] = '-' . $request->session()->get('phrmacy')->id;

		}
		$validator = $request->validate($validateArr);

		$save_res = Pickups::create($insert_data);

		
		
		//    Patient Location
		$location_data['locations'] = $insert_data['location'];
		$location_data['patient_id'] = $request->patient_id;
		$location_data['action_by'] = $request->session()->get('phrmacy')->id;
		PatientLocation::insert_data($location_data);
//    End




		

	// Below code to send sms on mobile     

	// Configure HTTP basic authorization: BasicAuth
// $config = ClickSend\Configuration::getDefaultConfiguration()
// ->setUsername('amr_eid@msn.com')
// ->setPassword('5N^u#SLo2!w43SLk');

// $apiInstance = new ClickSend\Api\SMSApi(new GuzzleHttp\Client(),$config);
// $msg = new \ClickSend\Model\SmsMessage();
// $msg->setBody("test body"); 
// $msg->setTo("+923234774241");
// $msg->setSource("+61422222222");

// // \ClickSend\Model\SmsMessageCollection | SmsMessageCollection model
// $sms_messages = new \ClickSend\Model\SmsMessageCollection(); 
// $sms_messages->setMessages([$msg]);

// try {
// $result = $apiInstance->smsSendPost($sms_messages);


// // below code to create pickup notifications 
// // if sms send successfully and response as 200 then save transaction in database
// if($result == 200)
// {

		// Update used sms 
		// $userresult = user::where('website_id','=',$companyid)->first();
		// $userresult->usedsms = $usedsms + 1;
		// $userresult->save();

		// 	pickupNotification::create([
		// 		'patient_id' => $request->patient_id,
		// 		'pickup_id' => $save_res->id,
		// 		'website_id' => $request->session()->get('phrmacy')->website_id,
		// 		'type' => 'sms',
		// 		'created_by' => $request->session()->get('phrmacy')->id,
		// 		'patientname'=>$request->patientname
		// 		]);
		// }


// }
//  catch (Exception $e)
//  {
// echo 'Exception when calling SMSApi->smsSendPost: ', $e->getMessage(), PHP_EOL;
// }


		// below code to send email to admin and client for pickups

		$data = array('patientname'=>$request->patientname,'dob'=>$request->dob,'lastpickup'=>$request->last_pick_up_date,
	'noofweekspickup'=>$request->numberofweekspicup,'duedays'=>0,
	'nextpickupdate'=>" ");

		$getAdmin=Admin::where('email','hasanbilal369@gmail.com')->first(); 
        // echo json_encode($getAdmin); die; 
        if(!empty($getAdmin))
        {
            $details = $getAdmin;
            // Mail::to($getAdmin->email)->send(new \App\Mail\Newpickupnotification($data));
			Mail::to($getAdmin->email)->send(new \App\Mail\PickupNotification($data));
        
            if (Mail::failures()) {
                // return response showing failed emails
                echo "failed";
                dd("Email failed");
            }
			else
			{
				// if email send successfully then create transaction in database
				pickupNotification::create([
					'patient_id' => $request->patient_id,
					'pickup_id' => $save_res->id,
					'website_id' => $request->session()->get('phrmacy')->website_id,
					'type' => 'email',
					'created_by' => $request->session()->get('phrmacy')->id,
					'patientname'=>$request->patientname
					]);
			}
        }



		


		EventsLog::create([
			'website_id' => $request->session()->get('phrmacy')->website_id,
			'action_by' => $request->session()->get('phrmacy')->id,
			'action' => 1,
			'action_detail' => 'PickUp',
			'comment' => 'Create PickUp',
			'patient_id' => $request->patient_id,
			'ip_address' => $request->ip(),
			'type' => $request->session()->get('phrmacy')->roll_type,
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		$patient = Patient::find($request->patient_id);

		return redirect()->back()->with(["msg" => '<div class="alert alert-success">Your <strong> Pick Ups</strong> Added Successfully.</div>']);
	}
	/* End  of  Pickups */
	/* Start  of  pickups_reports */
	public function pickups_reports(Request $request) {
		if ($request->form2 == '1') {
			$patient = Patient::orderBy('first_name', 'asc')->get();
			$pickups = Pickups::where('is_archive','=',0)->get();

			$data['pickups'] = $pickups;
			$data['patients'] = $patient;
			return view($this->views . '.pickups_reports', $data);
		} else {
			return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
		}
	}


	public function pickups_reports_calender(Request $request) {
			

			$pickups = Pickups::where('is_archive','=',0)->where('id','=',$request->id)->get();
			$patient = Patient::where('id','=',$pickups[0]->patient_id)->get();
			$data['pickups'] = $pickups;
			$data['patients'] = $patient;
			return view($this->views . '.pickups_reports', $data);
		
	}


	public function pickups_archived_reports(Request $request) {
		
			$patient = Patient::orderBy('first_name', 'asc')->get();
			$pickups = Pickups::where('is_archive','=',1)->get();

			$data['pickups'] = $pickups;
			$data['patients'] = $patient;
			return view($this->views . '.pickups_archived_reports', $data);
		
	}

	public function update_pharmacy_tenant(Request $request)
    {
	
    	    
		$accesslevel = AccessLevel::first();
		$accesslevel->app_logout_time = $request->applogout;
		$accesslevel->default_cycle = $request->defaultcycle;
		$accesslevel->save();
		return "200";
    }


	
	public function pickups_notifications(Request $request) {
		
		$companyid = 0;
		if(Cookie::get('companyid'))
		{
		$cookie_data = stripslashes(Cookie::get('companyid'));
		$companyid = json_decode($cookie_data, true);
		}
		$accesslevel =  AccessLevel::where('website_id','=',$companyid)->first();


		$datapickup = pickupNotification::get();
		
		if ($request->form2 == '1') {
			
			// $patient = Patient::orderBy('first_name', 'asc')->get();
			// $pickups = Pickups::get();

			$data['pickups'] = $datapickup;
			
			// $data['patients'] = $patient;
			// $data['datapickup'] = $datapickup;
			// return view($this->views . '.pickups_notifications', $data);

			return view($this->views . '.pickups_notifications', $data)
			->with('applogout',$accesslevel->app_logout_time)
			->with('defaultcycle',$accesslevel->default_cycle);;
		} else {
			return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
		}
	}

	
	/* End of  pickups_reports */

	/* Start  of  last_month_pickups_reports */
	public function patients_picked_up_last_month() {
		$prevMonthDate = date("Y-m-d 00:00:00", strtotime("first day of previous month"));
		$lastMonthDate = date("Y-m-d 24:00:00", strtotime("last day of previous month"));

		$patient = Patient::whereHas('pickups', function ($query) {
			$prevMonthDate = date("Y-m-d 00:00:00", strtotime("first day of previous month"));
			$lastMonthDate = date("Y-m-d 24:00:00", strtotime("last day of previous month"));
			$query->where([['created_at', '>=', $prevMonthDate], ['created_at', '<=', $lastMonthDate]]);})->orderBy('first_name', 'asc')->get();
		$data['patients'] = $patient;

		$pickups = Pickups::where([['created_at', '>=', $prevMonthDate], ['created_at', '<=', $lastMonthDate]])->orderBy('created_at', 'desc')->get();
		$data['pickups'] = $pickups;
		//return $pickups;
		return view($this->views . '.pickups_reports_last_month', $data);
	}
	/* End of  last_month_pickups_reports */

	/* get All Pickup  for this Specific month */
	public function getAllPickupForMonth(Request $request) {
		$month = $request->month;
		$year = $request->year;
		$allPickups = 0;
		$allPickups = Pickups::whereRaw('MONTH(`created_at`) = ' . $month . ' AND YEAR(`created_at`) = ' . $year)->distinct()->get(['patient_id'])->count();
		$xMonthOld = Pickups::whereRaw('created_at >= now()-interval 4 month')->distinct()->get(['patient_id'])->count();
		$x = ($allPickups > 0) ? $allPickups : '';
		$y = (($xMonthOld - $allPickups) > 0) ? ($xMonthOld - $allPickups) : '';
		$z = ($allPickups > 0 && ($xMonthOld - $allPickups) > 0) ? "/" : '';
		return $x . $z . $y;
	}

	/* pickups_calender */
	public function pickups_calender(Request $request) {
		if ($request->form3 == '1') {
			$pickups = Pickups::get();
			$data['pickups'] = $pickups;
			// $now = Carbon::now();
			// $allPickups=$this->getAllPickupForMonth($now->month,$now->year);
			// $data['allPickups']=$allPickups;

			// if(session()->has('phrmacy') && session()->get('phrmacy')->id>0)
			// echo session()->get('phrmacy')->website_id;
			$getAccess = AccessLevel::first();
			if (!empty($getAccess)) {
				$default_cycle = $getAccess->default_cycle;
			} else {
				$default_cycle = 4;
			}

			$getAllPatient = Pickups::distinct()->get(['patient_id']);

			$allLastPickup = array();
			foreach ($getAllPatient as $key => $value) {
				if ($value->patient_id != "") {
					$getLastPickup = Pickups::where('patient_id', $value->patient_id)->orderBy('created_at', 'DESC')->first();
					array_push($allLastPickup, $getLastPickup);
				}
			}

			$allNeaxtPickup = array();

			foreach ($allLastPickup as $key => $row) {

				$no_of_weeks = $row->no_of_weeks ? $row->no_of_weeks : 0;

				// echo date('Y-m-d H:i:s', strtotime('+' . $no_of_weeks . ' week', strtotime($row->created_at)));
				// echo "|||-" . $no_of_weeks . "||" . $row->created_at;die;

				for ($i = 0; $i < 1; $i++) {
					$nextpickup_1 = date('Y-m-d H:i:s', strtotime('+' . $no_of_weeks . ' week', strtotime($row->created_at)));
					$start_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at);
					$nextdate = $start_date->addWeeks($default_cycle);
					$row->created_at = $nextpickup_1;
					$row->updated_at = $nextpickup_1;

					$nextpickup['id'] = $i + 1;
					$nextpickup['patient_id'] = $row->patient_id;
					$nextpickup['dob'] = $row->dob;
					$nextpickup['last_pick_up_date'] = $row->created_at;
					$nextpickup['weeks_last_picked_up'] = $row->weeks_last_picked_up;
					$nextpickup['no_of_weeks'] = $row->no_of_weeks;
					$nextpickup['location'] = $row->location;
					$nextpickup['pick_up_by'] = $row->pick_up_by;
					$nextpickup['carer_name'] = $row->carer_name;
					$nextpickup['notes_from_patient'] = $row->notes_from_patient;
					$nextpickup['pharmacist_sign'] = $row->pharmacist_sign;
					$nextpickup['patient_sign'] = $row->patient_sign;
					$nextpickup['created_by'] = $row->created_by;
					$nextpickup['deleted_by'] = $row->deleted_by;
					$nextpickup['status'] = $row->status;
					$nextpickup['deleted_at'] = $row->deleted_at;
					$nextpickup['is_archive'] = $row->is_archive;
					$nextpickup['website_id'] = $row->website_id;
					$nextpickup['created_at'] = $nextpickup_1;
					$nextpickup['updated_at'] = $nextpickup_1;
					$nextpickup['patients'] = $row->patients;
					array_push($allNeaxtPickup, $nextpickup);
				}
				// echo $start_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$row->created_at);
				// echo $start_date->addWeeks($default_cycle);
				// echo json_encode($allNeaxtPickup);die;
			}
			$data['nextPickupList'] = (object) $allNeaxtPickup;
			$data['uniquePickupCount'] = $cnt = count(array_unique(array_column($allNeaxtPickup, 'patient_id')));
			return view($this->views . '.pickups_calender', $data);
		} else {
			return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
		}
	}
	/* pickups_calender */

	/* 6_month_compliance */
	public function six_month_compliance(Request $request) {
		if ($request->form4 == '1') {
			$lastSixMonthDate = date("Y-m-d 00:00:00", strtotime("- 6 months"));
			$patient = Patient::whereHas('pickups', function ($query) {

				$lastSixMonthDate = date("Y-m-d 00:00:00", strtotime("- 6 months"));

				$query->where([['created_at', '>=', $lastSixMonthDate]]);})->orderBy('first_name', 'asc')->get();

			$pickups = Pickups::where([['created_at', '>=', $lastSixMonthDate]])->orderBy('created_at', 'desc')->get();
			$data['pickups'] = $pickups;
			$data['patients'] = $patient;
			return view($this->views . '.six_month_compliance', $data);
		} else {
			return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
		}
	}
	/* 6_month_compliance */

	/* all_compliance */
	public function all_compliance(Request $request) {
		if ($request->form5 == '1') {
			$patient = Patient::whereHas('pickups', function ($query) {

				$lastSixMonthDate = date("Y-m-d 00:00:00", strtotime("- 6 months"));

				$query->where([['created_at', '>=', $lastSixMonthDate]]);})->orderBy('first_name', 'asc')->get();
			$data['patients'] = $patient;
			return view($this->views . '.all_compliance', $data);
		} else {
			return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
		}
	}
	/* all_compliance */

	public function export_excel_pickup_phar()
	{
		$newarray = Pickups::get();
		$proData = "";
		if (count($newarray) >0) {
		 $proData .= '<table border  style="height:100% width:100%">
		 <tr>
		 <th>Id</th>
		 <th>Patient_id</th>
		 <th>DOB</th>
		 <th>Image</th>
		 <th>weeks_last_picked_up</th>
		 <th>pharmacist_sign</th>
		 <th>patient_sign</th>
		
 
		 </tr>';
	 
		 foreach ($newarray as $img)
		 {      
		  $proData .= '
			 <tr>
			 <td>'.$img->id.'</td>
			 <td>'.$img->patient_id.'</td>
			 <td>'.$img->dob.'</td>
			 <td>'.$img->last_pick_up_date.'</td>
			 <td>'.$img->weeks_last_picked_up.'</td>
		   
			 <td>
			
			 <img src="'.$img->pharmacy_image.'"  height="20" width="50">
			
			 </td>
			 <td>
			
			 <img src="'.$img->patient_image.'"  height="20" width="50">
			
			 </td>
			 
			 <td> 
			
				
			
			
			 </tr>';
		   
			 
	 
		 }
		 $proData .= '</table>';
		}
		header('Content-Type: application/xls');
		header('Content-Disposition: attachment; filename='.time().'.xls');
		echo $proData;
	 
	}
	public function export_pdf_pickup_phar()
	{
	
        $data = Patient::get();
		$newarray = Pickups::get();
	//	dd($newarray);
    $pdf =  PDF::loadView('pdf', compact('newarray'));
//	return view('checkingpdf',compact('newarray'));
        return  $pdf->download(time().'.pdf');

	}

}
