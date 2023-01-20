<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\AccessLevel;
use App\Models\Tenant\Checking;
use App\Models\Tenant\Company;
use App\Models\Tenant\EventsLog;
use App\Models\Tenant\Notification;
use App\Models\Tenant\Patient;
use App\Models\Tenant\PatientReturn;
use App\Models\Admin\Location;
use App\Models\Tenant\PickUp;
use App\Models\Tenant\Pickups;
use App\Rules\IsValidPassword;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
use App\Models\Tenant\Checkings;
use App\Models\Tenant\Packed;
use App\Models\Tenant\NotesForPatient;
use App\Models\Tenant\PatientLocation;
use DB;
use DateTime;
use Illuminate\Http\Request;
use App\user;
use App\smspurchasedtransaction;

use Illuminate\Support\Facades\Hash;

class HomeController extends Controller {

	private function getCalendarData() {
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
			if ($value->patient_id != "")
			{
				$getLastPickup = Pickups::where('patient_id', $value->patient_id)->orderBy('created_at', 'DESC')->first();
				array_push($allLastPickup, $getLastPickup);
			}
		}

		$allNeaxtPickup = array();

		foreach ($allLastPickup as $key => $row) {

			$no_of_weeks = $row->no_of_weeks ? $row->no_of_weeks : 0;

			// echo date('Y-m-d H:i:s', strtotime('+' . $no_of_weeks . ' week', strtotime($row->created_at)));
			// echo "|||-" . $no_of_weeks . "||" . $row->created_at;die;

			for ($i = 0; $i < 1; $i++)
			 {
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
		// echo count($pickups);die;
		return $data;
	}

	public function textGuidelines() {
		return view('tenant.text_guidelines');
	}

	public function packboard(Request $request)
	{
		$tasks = packed::all();
		 $completed = $tasks->where('id');
		 $final = Carbon::now()->startOfMonth()->format('Y-m-d');
		// //dump($final);
         $currentdate = Carbon::now()->endOfMonth()->format('Y-m-d');
	    //  $set_date_range =  Carbon::now()->startOfMonth()->format('m/d/Y'). '-' .Carbon::now()->endOfMonth()->format('m/d/Y');
		//  dump($set_date_range);
		  $hold = $request->hold;
		//$testing = $request->startdate;
		//$testing  = explode(' - ' ,$testing);
		//dd($testing);
		$startdates = "1900-01-01"." 00:00:00";
		$date_end = "2050-01-01"." 23:59:59";
		if($request->startdate !=null){
		$startdate =$request->startdate;
		
		//$dats = $startdate[0]."00:00:00" .$startdate[1]."23:59:59";
		$startdate  = explode(' - ' ,$startdate);
	//	dd($startdate[0]);
	//	$date_end  = explode(' - ' ,$startdate);
		//date('Y-m-d',$time);
		$startdates = date('Y-m-d', strtotime($startdate[0])). " 00:00:00";
		$date_end = date('Y-m-d', strtotime($startdate[1])). " 23:59:59";
		// $dare = [$startdate[0] . " 00:00:00",$startdate[1] . " 23:59:59"];
     	//	$dare1 = $dare->format('Y-m-d');
		//dd($date_end);
		//dump($startdate);
		}

		//dd($date_end);
		// $enddate = "2050-01-01"." 23:59:59";
		// if($request->enddate !=null){
		// 	//dd($request->enddate);
		// $enddate = $request->enddate." 23:59:59";
		// }

		if ($request->form9 == '1') {
			$data = array();
			$data['locations'] = Location::get();
			$data['created_at'] = $request->day ? $request->day : "";
			$data['default_cycle'] = AccessLevel::get('default_cycle');
			$data['patients'] = Patient::where('is_archive','=',0)->get();
			//dd($data['patients']);
				if($hold == '1' )
				{
				$data['packed'] = Packed::select()
				//->whereBetween('created_at',[$startdates,$date_end])
				//->where('created_at' , '>=' ,$startdates." 00:00:00")
				
										//->where('created_at' , '<=' ,$date_end." 23:59:59")
										->where('hold', '=', $hold)
										->orderbydesc('id')
										->get();
				}
				elseif($hold == NULL)
				{
				$data['packed'] = Packed::select()->whereBetween('created_at',[$startdates,$date_end])
				//->where('created_at' , '>=' ,$startdate)
				//->whereBetween('created_at',[$startdate." 00:00:00",$date_end." 23:59:59"])
									//->where('created_at' , '<=' ,$date_end)
									->orderbydesc('id')
									->get();
				}
			
			if($hold == '1')
			{
				$data['checkings'] = Checkings::select()
				//->whereBetween('created_at',[$startdates,$date_end])
				//->where('created_at' , '>=' ,$startdate)
				//->whereBetween('created_at',[$startdate." 00:00:00",$date_end." 23:59:59"])
				//->where('created_at', '<=',$date_end)
				->where('hold', '=', $hold)
				->orderbydesc('id')
				->get();	
			
			}
			elseif($hold == NULL )
			{
				$data['checkings'] = Checkings::select()->whereBetween('created_at',[$startdates,$date_end])
				//->where('created_at' , '>=' ,$startdate)
				//->where('created_at', '<=',$date_end)
				//->whereBetween('created_at',[$startdate." 00:00:00",$date_end." 23:59:59"])
				->orderbydesc('id')
				//->where('hold', '=', $hold)
				->get();
			}

			if ($hold == '1') {
				$data['Pickups'] = Pickups::select()
				//->whereBetween('created_at',[$startdates,$date_end])
				//->whereBetween('created_at',[$startdate." 00:00:00",$date_end." 23:59:59"])
				//->where('created_at', '<=' ,$enddate)
				->where('hold', '=', $hold)
				->orderbydesc('id')
				->get();
			}
			elseif($hold == NULL)
			{
				$data['Pickups'] = Pickups::select()->whereBetween('created_at',[$startdates,$date_end])
				//->whereBetween('created_at',[$startdate." 00:00:00",$date_end." 23:59:59"])
				// ->where('created_at' , '>=' ,$startdate)
				// ->where('created_at', '<=' ,$enddate)
				->orderbydesc('id')
				//->where('hold', '=', $hold)
				->get();	
			}
			else{
                  dd('error');
			}


			$date = Carbon::now();
			
			// $all_pharmacy = User::all();
	
			$all_pharmacy = User::all();
	
			//dd($all_pharmacy);
			$newarray = array();
			
			// get all pharmacies
			foreach ($all_pharmacy as $row) 
			{
			$this->get_connection($row->website_id);
		
			$email_pharmacy = User::where('website_id','=',$row->website_id)->first();
	//dd($email_pharmacy);
			$pharmacyadminemail = $email_pharmacy->email;
	
			
			// get pharmacy cycle value
		//	$data['default_cycle'] = AccessLevel::get();
			$getaccesslevels = AccessLevel::get();
			//dd($getaccesslevels);
			$pharmacy_cyle = $getaccesslevels[0]->default_cycle; 
	
			$reminder_days = $getaccesslevels[0]->reminderdefaultdays; 	
			
			
			
			// get pharmacy all patient list 
			$getpatientlist = Patient::all();	
	
			$patientname = "";
			$patientdob = "";
			$lastpickupdate ="";
			$Note = "Webster(s) will be due in two days ";
	
			foreach ($getpatientlist as $patientlist) 
				{
					
					$patientname = $patientlist->first_name . ' '.   $patientlist->last_name;
					$patientdob = $patientlist->dob;
	            
					$getpatientlastpickup = Pickups::whereBetween('created_at',[$final,$currentdate])
					->orderbydesc('id')->take(1)->get();

			}}
			$packed_patient_count = Packed::select('patient_id')
			->groupBy('patient_id')->get();	
			$checking_patient_count = Checking::select('patient_id')
			->groupBy('patient_id')->get();	
			$pickup_patient_count = Pickups::select('patient_id')
			->groupBy('patient_id')->get();	
			// $packed_patient_count = DB::table('Packed')
            //      ->select('patient_id')
            //      ->groupBy('patient_id')
            //      ->get();
			//	 dd($packed_patient_count);	
				//  $packed_patient_count = Packed::orderBy('patient_id')
				//  ->groupBy('count')
				
				//  ->get();
			//return $data['patients'][0]->latestPickups;
			return view('tenant.packboard',compact('currentdate','final','getpatientlastpickup','completed','packed_patient_count','checking_patient_count','pickup_patient_count'))->with($data);
		} else {
			return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
		}
		  
	
	}
		public function gettopack()
		{


			// $getAdmin=Admin::where('email','admin@packpeak.com.au')->first();
			// $getAdmin=Admin::where('email','hasanbilal369@gmail.com')->first();
			// $details = $getAdmin;
			// // dump($details);
			
			// Mail::to('hasanbilal369@gmail.com')->send(new \App\Mail\PickupNotification($details));
			// dump("Send Email");
	
			// return;
	
			$date = Carbon::now();
			
			// $all_pharmacy = User::all();
	
			$all_pharmacy = User::all();
	
			
			$newarray = array();
			
			// get all pharmacies
			foreach ($all_pharmacy as $row) 
		{
			$this->get_connection($row->website_id);
		
			$email_pharmacy = User::where('website_id','=',$row->website_id)->first();
	
			$pharmacyadminemail = $email_pharmacy->email;
	
			
			// get pharmacy cycle value
			$getaccesslevels = AccessLevel::get();
			
			$pharmacy_cyle = $getaccesslevels[0]->default_cycle; 
	
			$reminder_days = $getaccesslevels[0]->reminderdefaultdays; 
			
			
			
			// get pharmacy all patient list 
			$getpatientlist = Patient::all();	
	
			$patientname = "";
			$patientdob = "";
			$lastpickupdate ="";
			$Note = "Webster(s) will be due in two days ";
	
			foreach ($getpatientlist as $patientlist) 
				{
					
					$patientname = $patientlist->first_name . ' '.   $patientlist->last_name;
					$patientdob = $patientlist->dob;
	
					$getpatientlastpickup = PickUp::where('patient_id','=',$patientlist->id)
					->orderbydesc('id')->take(1)->get();
					
					// dump($getpatientlastpickup);
					
					if(!$getpatientlastpickup ->isEmpty())
					{
						//Get latest pickup date from database 
						$last_pick_date = $getpatientlastpickup[0]->last_pick_up_date;
						
						// convert latest pickup date format as its varchar in databse 
						if($last_pick_date != null)
						{
	
							
							$last_pick_date =  substr($last_pick_date, 6, 4) .'/'.substr($last_pick_date, 3, 2) .'/'.substr($last_pick_date, 0, 2);
							
							//  $time = strtotime($last_pick_date);
							//  $newformat = date('Y-m-d',$time);	
							 
							//  $newdate =  $newformat->addDays();
							
							//  dump($last_pick_date);
							// dump($newdate);
							
	
						
						$weekdiffer =  $date->diffIndays($last_pick_date);
						
	
						$nextpickupdate = Carbon::parse($last_pick_date)->addDays($pharmacy_cyle * 7);
						
						dump($weekdiffer);
						dump(($pharmacy_cyle * 7) - $reminder_days);
						// if last pickup and current date difference is less then pharmacy cycle
							if ( $weekdiffer ==  ($pharmacy_cyle * 7) - $reminder_days )
							{
								
								// dump($pharmacyadminemail);
								
							
							// dump("patient name " . $patientname);
							// dump("patient dob " . $patientdob);
							// dump("last pickup date " . $last_pick_date);
							// dump("Number of Weeks They Picked Up" . ' ' .$getpatientlastpickup[0]->weeks_last_picked_up);
							// dump("Webster(s) will be due in two days ");
					
							$nextpickupdate = $nextpickupdate->format('Y-m-d');
	
							$duedate = "";
							$data = array('patientname'=>$patientname,'dob'=>$patientdob,'lastpickup'=>$last_pick_date,
							'noofweekspickup'=>$getpatientlastpickup[0]->weeks_last_picked_up,'duedays'=>$duedate,
							'nextpickupdate'=>$nextpickupdate,
							'message'=>$this);
	
	
							$patientdetail  = Patient::where('id','=',$patientlist->id)->first();
							
							
							// dump($patientdetail);
							// dump($data);
							// return;
	
	
							// $data=  DB::table('patients')
							// ->Join('pick_ups', 'patients.id', '=', 'pick_ups.patient_id')
							// ->select('pick_ups.first_name')
							// // ->orderBy('Mortgageheadid', 'DESC')->first();
							// ->first();
							// 	dump($data);
							//  return ; 		
	
							$details = $patientdetail;
	
							 // if($daysdiffer == 2)
								
									// $getAdmin=Admin::where('email','hasanbilal369@gmail.com')->first();
									// $details = $getAdmin;
									// dump($details);
									
									//Mail::to('hasanbilal369@gmail.com')->send(new \App\Mail\PickupNotification($details));
	
									// temporary comment  (2 august 2022)
									Mail::to('hasanbilal369@gmail.com')->send(new \App\Mail\PickupNotification($data));
									dump("Send Email");
							
									// if email send successfully then create transaction in database
									// pickupNotification::create([
									// 'patient_id' => $patientlist->id,
									// 'pickup_id' => 0,
									// 'website_id' => 0,
									// 'type' => 'email',
									// 'created_by' => 1,
									// 'patientname'=>$patientname
									// ]);
									// return;
							}
							else
								{
	
									// $getAdmin=Admin::where('email','hasanbilal369@gmail.com')->first();
									// $details = $getAdmin;
									// dump($details);
									
									// // Mail::to('hasanbilal369@gmail.com')->send(new \App\Mail\PickupNotification($details));
	
									// Mail::to('hasanbilal369@gmail.com')->send(new \App\Mail\PickupNotification($data));
									// dump("Send Email");
							
									// return;
									dump("Not Send Email");
								}
						}
	
						
					}
						
	
	
					
				}
	
			}
	
			return;
		}
	
	public function dashboard() 
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

		
		$allChecking = Checking::all()->count();
		$allPatients = Patient::all()->count();
		$allTechnicians = Company::select_all_technician()->count();
		$allPharmacists = Company::where('roll_type', 'admin')->where('id', '>', 1)->get()->count();
		$allPickups = PickUp::all()->count();
		

		$allReturns = PatientReturn::all()->count();

		
		$data = array('allPatients' => $allPatients, 'allChecking' => $allChecking, 
		'allTechnicians' => $allTechnicians, 'allPickups' => $allPickups, 'allReturns' => $allReturns,
		 'allPharmacists' => $allPharmacists);
		$cData = $this->getCalendarData();
		$result = array_merge($data, $cData);


		return view('tenant.index')->with($result)->with('Allowedsms',$Allowedsms)->with('usedsms',$usedsms);
	}

	public function notification_details(Request $request) {
		$data['notification'] = Notification::find($request->id);
		return view('tenant.notification_details')->with($data);
	}

	public function profile(Request $request) {
		if (isset($request->session()->get('phrmacy')->id)) {
			$data['accessLevel'] = AccessLevel::find(1);
			$data['patient_reports'] = Patient::where("status", 1)->get();
			$data['user_data'] = Company::find($request->session()->get('phrmacy')->id);
			return view('tenant.profile')->with($data);
		} else {
			return redirect('/')->with('msg', '<div class="alert alert-danger"> You are logged out <strong>!</strong></div>');
		}
	}

	/*Update Profile*/
	public function update_profile(Request $request) {
		if (isset($request->session()->get('phrmacy')->id)) {
			$validateArr = array(
				'first_name' => 'required|string|max:255',
				'last_name' => 'required|string|max:255',
				'phone' => 'required|string|max:12|min:10',
				'pin' => 'required|string|min:4|max:4',
			);
			$validator = $request->validate($validateArr);
			$pharmacyId = $request->session()->get('phrmacy')->id;
			$update = array('first_name' => $request->first_name, 'last_name' => $request->last_name, 'pin' => $request->pin, 'phone' => $request->phone);
			if ($request->dob != '') {
				$update['dob'] = ($request->dob != '') ? Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d') : null;
			}
			if ($request->sign != "") {
				/* Pharmacy Signature   */
				$folderPath = public_path('upload/pharmacy/');
				$image_parts = explode(";base64,", $request->sign);
				$image_type_aux = explode("image/", $image_parts[0]);
				$image_type = $image_type_aux[1];
				$image_base64 = base64_decode($image_parts[1]);
				$file = $folderPath . uniqid() . '.' . $image_type;
				file_put_contents($file, $image_base64);

				$update['sign'] = ($request->sign != '') ? $request->sign : null;
			}

			$update['name'] = $request->first_name . ' ' . $request->last_name;
			$update['initials_name'] = strtoupper(substr($request->first_name, 0, 1)) . '.' . strtoupper(substr($request->last_name, 0, 1)) . '.';
			$updateInformation = Company::where('id', $pharmacyId)->Update($update);
			EventsLog::create([
				'website_id' => $request->session()->get('phrmacy')->website_id,
				'action_by' => $request->session()->get('phrmacy')->id,
				'action' => 2,
				'action_detail' => 'Profile',
				'comment' => ' Change Profile',
				'patient_id' => null,
				'ip_address' => $request->ip(),
				'type' => $request->session()->get('phrmacy')->roll_type,
				'user_agent' => $request->userAgent(),
				'authenticated_by' => 'packnpeaks',
				'status' => 1,
			]);
			return redirect('profile')->with('msg', '<div class="alert alert-success"> Data Updated <strong>.</strong></div>');
		} else {
			return redirect('/')->with('msg', '<div class="alert alert-danger"> You are logged out <strong>!</strong></div>');
		}
	}

	/*Update Access*/
	public function update_access(Request $request) {
		if (isset($request->session()->get('phrmacy')->id)) {
			if ($request->session()->get('phrmacy')->roll_type == 'admin') {
				$update = array('app_logout_time' => $request->app_logout_time, 'default_cycle' => $request->default_cycle);
				$updateAppLogout = AccessLevel::where('id', 1)->update($update);
				EventsLog::create([
					'website_id' => $request->session()->get('phrmacy')->website_id,
					'action_by' => $request->session()->get('phrmacy')->id,
					'action' => 2,
					'action_detail' => 'Cycle And App Session Out Time',
					'comment' => ' Cycle And App Session Out Time',
					'patient_id' => null,
					'ip_address' => $request->ip(),
					'type' => $request->session()->get('phrmacy')->roll_type,
					'user_agent' => $request->userAgent(),
					'authenticated_by' => 'packnpeaks',
					'status' => 1,
				]);
				if ($updateAppLogout) {
					echo '200';
				} else {
					echo '401';
				}

			} else {
				echo 'You can not able update it.';
			}
		} else {
			echo 'First you can login again';
		}

	}

	/* Chnage Passowrd */
	public function update_password(Request $request) {
		if (isset($request->session()->get('phrmacy')->id)) {
			$validateArr = array(
				'old_password' => 'required|string|min:6',
				'new_password' => ['required', 'string', new isValidPassword()],
				'confirm_password' => 'same:new_password',
			);
			$validator = $request->validate($validateArr);
			$pharmacyId = $request->session()->get('phrmacy')->id;
			$company = Company::where("id", $pharmacyId)->get();
			if (Hash::check($request->new_password, $company[0]->password)) {
				return redirect()->back()->with(["msgp" => '<div class="alert alert-danger""><strong>New Password and Old Password </strong>  can not be same !!!</div>']);
			} else {
				if (Hash::check($request->old_password, $company[0]->password)) {
					$update = Company::where("id", $pharmacyId)->update(array('password' => Hash::make($request->new_password)));
					EventsLog::create([
						'website_id' => $request->session()->get('phrmacy')->website_id,
						'action_by' => $request->session()->get('phrmacy')->id,
						'action' => 2,
						'action_detail' => 'Password',
						'comment' => ' Change Password',
						'patient_id' => null,
						'ip_address' => $request->ip(),
						'type' => $request->session()->get('phrmacy')->roll_type,
						'user_agent' => $request->userAgent(),
						'authenticated_by' => 'packnpeaks',
						'status' => 1,
					]);
					return redirect()->back()->with(["msgp" => '<div class="alert alert-success""><strong>Password </strong> Updated Successfully !!</div>']);
				} else {
					return redirect()->back()->with(["msgp" => '<div class="alert alert-danger""><strong>Wrong </strong> Old Password does not match !!!</div>']);
				}
			}
		} else {
			return redirect('/')->with('msgp', '<div class="alert alert-danger"> You are logged out <strong>!</strong></div>');
		}
	}

	function updateSMSSetting(Request $request) {

		$update = Patient::where("id", $request->id)->update(array("sms_allowed" => $request->status));
		return $update;
	}

}
