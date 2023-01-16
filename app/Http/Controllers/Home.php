<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cookie;
use App\Helpers\Helper;
use App\Models\Admin\Location;
use App\Models\Admin\Store;
use App\Models\Tenant\AccessLevel;
use App\Models\Tenant\Audit;
use App\Models\Tenant\Checking;
use App\Models\Tenant\MissedPatient;
use App\Models\Tenant\NotesForPatient;
use App\Models\Tenant\Notification;
use App\Models\Tenant\Patient;
use App\Models\Tenant\PatientReturn;
use App\Models\Tenant\PickUp;
use App\Models\Tenant\Patient as Patient_Model;
use App\Models\Admin\Admin;

use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mail;
use PDF;

class Home extends Controller {

	protected $before_day = 3;
	protected $on_day = 3;

	public function index(Request $request) {
		//return  view('front.index');
		return view('welcome');
	}
	public function term_condition(Request $request) {
		//return  view('front.index');
		return view('terms_condition');
	}
	public function privacy_policy(Request $request) {
		//return  view('front.index');
		return view('privacy_policy');
	}

	public function get_connection($website_id) {
		$get_user = User::get_by_column('website_id', $website_id);
		config(['database.connections.tenant.database' => $get_user[0]->host_name]);
		DB::purge('tenant');
		DB::reconnect('tenant');
		DB::disconnect('tenant');
	}

	public function before_expiry_send_notification() {
		$all_pharmacy = User::all();
		foreach ($all_pharmacy as $value) {
			if ($value->expired_date != NULL) {
				// get  Diff  between Two  date //  Carbon::now()->format('Y-m-d')
				$current_date = Carbon::now()->format('Y-m-d');
				$start_date = \Carbon\Carbon::createFromFormat('Y-m-d', $current_date);
				$end_date = \Carbon\Carbon::createFromFormat('Y-m-d', $value->expired_date);
				$different_days = $start_date->diffInDays($end_date);
				if ($different_days > $this->before_day) {

					$insert_data = array(
						'type' => 'before_plan_expiry',
						'notification_msg' => 'after 3 days your subscription plan will be expire',
						'website_id' => $value->website_id,
					);
					$this->get_connection($value->website_id);
					$save_res = Notification::insert_data($insert_data);
					DB::disconnect('tenant');
					echo 'created notification || ';
				} else {
					echo 'not create ||';
				}

			} else {
				echo 'Expiry  no Define ||';
			}
		}
	}

	/*on Expiry  date notification */

	public function on_expiry_send_notification() {
		//Carbon::now()->subDays(1); // yesterday
		//Carbon::now()->addDays(1) //  Twomarrow

		$all_pharmacy = User::all();
		foreach ($all_pharmacy as $value) {
			if ($value->expired_date != NULL) {
				// get  Diff  between Two  date //  Carbon::now()->format('Y-m-d')
				$current_date = Carbon::now()->format('Y-m-d');
				$start_date = \Carbon\Carbon::createFromFormat('Y-m-d', $current_date);
				$end_date = \Carbon\Carbon::createFromFormat('Y-m-d', $value->expired_date);
				$different_days = $start_date->diffInDays($end_date);
				//echo $end_date->addDays(2)->format('d-m-Y');  die;

				//echo $different_days;  die;
				if ($different_days == '0') {
					$insert_data = array(
						'type' => 'on_plan_expiry',
						'notification_msg' => 'your plan has been expire today. your free trail expire on ' . $end_date->addDays(2)->format('d-m-Y'),
						'website_id' => $value->website_id,
					);
					$this->get_connection($value->website_id);
					$save_res = Notification::insert_data($insert_data);
					DB::disconnect('tenant');

					echo 'created notification || ';
				} else {
					echo 'not create ||';
				}

			} else {
				echo 'Expiry  no Define ||';
			}
		}

	}

	/*on trail Expiry  notification */

	public function on_trail_expiry_notification() {
		$all_pharmacy = User::all();
		foreach ($all_pharmacy as $value) {
			if ($value->expired_date != NULL) {
				// get  Diff  between Two  date //  Carbon::now()->format('Y-m-d')
				$current_date = Carbon::now()->format('Y-m-d');
				$start_date = \Carbon\Carbon::createFromFormat('Y-m-d', $current_date);
				$end_date = \Carbon\Carbon::createFromFormat('Y-m-d', $value->expired_date);
				$different_days = $start_date->diffInDays($end_date);
				//echo $end_date->addDays(2)->format('d-m-Y');  die;
				if ($end_date->addDays(2)->format('d-m-Y') == $start_date->format('d-m-Y')) {
					$insert_data = array(
						'type' => 'on_trail_expiry',
						'notification_msg' => 'your free trail plan has been expire today.',
						'website_id' => $value->website_id,
					);
					$this->get_connection($value->website_id);
					$save_res = Notification::insert_data($insert_data);
					DB::disconnect('tenant');

					echo 'created notification || ';
				} else {
					echo 'not create ||';
				}

			} else {
				echo 'Expiry  no Define ||';
			}
		}
	}
	/*End of Expiry of Trail Time */

	/*Create archive */
	public function create_archive() {
		$all_pharmacy = User::all();
		foreach ($all_pharmacy as $value) {

			$current_date = Carbon::now()->subDays(29)->format('Y-m-d');
			$this->get_connection($value->website_id);
			Audit::where('created_at', '<', $current_date)->update(array('is_archive' => 1));
			Checking::where('created_at', '<', $current_date)->update(array('is_archive' => 1));
			MissedPatient::where('created_at', '<', $current_date)->update(array('is_archive' => 1));
			NotesForPatient::where('created_at', '<', $current_date)->update(array('is_archive' => 1));
			Patient::where('created_at', '<', $current_date)->update(array('is_archive' => 1));
			PatientReturn::where('created_at', '<', $current_date)->update(array('is_archive' => 1));
			PickUp::where('created_at', '<', $current_date)->update(array('is_archive' => 1));
			DB::disconnect('tenant');

		}

		echo 'Archive Created';
	}

	public function notifyPharmacy() {
		$all_pharmacy = User::all();
		foreach ($all_pharmacy as $value) {

			$current_date = Carbon::now()->subDays(29)->format('Y-m-d');
			$this->get_connection($value->website_id);
			$getAccess = AccessLevel::first();
			$pickups = PickUp::whereRaw("DATEDIFF(DATE_ADD(created_at, INTERVAL no_of_weeks WEEK), NOW())= 3")->selectRaw('patient_id,no_of_weeks,max(created_at)')->groupBy('patient_id')->get();

			foreach ($pickups as $pickup) {
				if ($getAccess->default_cycle !== $pickup->no_of_weeks) {
					$patient = Patient::where('id', $pickup->patient_id)->first();
					if ($patient->sms_allowed) {
						$msg = "Hi, " . $patient->first_name . " " . $patient->last_name . " pickup has scheduled 2 days later @" . date('Y-m-d', strtotime('+2 days'));
						Helper::smsSendToMobile('+923244605828', $msg);
						echo "send sms to phrmacy for patient" . $patient->id . "/" . $value->website_id . "<br />";
					} else {
						echo "not send sms to phrmacy for patient" . $patient->id . "/" . $value->phone . "<br />";
					}
				}

			}
			DB::disconnect('tenant');
		}

	}

	// function to send notification to patients for pickup medicine
	public function getpickupnotifications ()
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

	public function pickupCustomDateRange(Request $request) {



		$dateS = new Carbon($request->start_date);
		$dateE = new Carbon($request->end_date);
		$all_pharmacy = User::all();	




		foreach ($all_pharmacy as $row) {
			$newarray = array();
			
			$get_result = PickUp::select("pick_ups.*", "patients.first_name", "patients.last_name")
				->join("patients", "pick_ups.patient_id", "=", "patients.id")
				->whereBetween('pick_ups.created_at', [$dateS->format('Y-m-d') . " 00:00:00", $dateE->format('Y-m-d') . " 23:59:59"])
				->get();

				


			foreach ($get_result as $col) {
				$myArray = explode(',', $col->location);
				$locations = Location::whereIn('id', $myArray)->get();
				$loc = array();
				foreach ($locations as $location) {
					$loc[] = $location->name;
				}

				$col->locations = implode(",", $loc);

				$newarray[] = $col;
			}
			$data['newarray'] = $newarray;
			$fileName = $row->website_id . '-' . time() . ".pdf";
			$pdf = PDF::loadView('pdf', $data);
			$pdf->setPaper('a4', 'landscape'); //save('/public/upload/pdf/'.$fileName);
			Storage::disk('public')->put('/pdf/' . $fileName, $pdf->output());
			$details = $row;
			$details['url'] = Storage::url($fileName);
			$details['report_name'] = "Pickup Report";
			$details['date_range'] = $dateS->format('Y-m-d') . " to " . $dateE->format('Y-m-d');
			Mail::to($row->email)->send(new \App\Mail\AttachFile($details));
			// echo "</br>". url($details['url'])."</br>";

			DB::disconnect('tenant');
		}

	}

	public function checkinsCustomDateRange(Request $request) {
		$dateS = new Carbon($request->start_date);
		$dateE = new Carbon($request->end_date);
		$all_pharmacy = User::all();

		foreach ($all_pharmacy as $row) {
			$newarray = array();
			$this->get_connection($row->website_id);
			$get_result = Checking::select("checkings.*", "patients.first_name", "patients.last_name", "patients.dob")
				->join("patients", "checkings.patient_id", "=", "patients.id")
				->whereBetween('checkings.created_at', [$dateS->format('Y-m-d') . " 00:00:00", $dateE->format('Y-m-d') . " 23:59:59"])
				->get();
			foreach ($get_result as $col) {
				$myArray = explode(',', $col->location);
				$locations = Location::whereIn('id', $myArray)->get();
				$loc = array();
				foreach ($locations as $location) {
					$loc[] = $location->name;
				}

				$col->locations = implode(",", $loc);
				$newarray[] = $col;
			}
			$data['newarray'] = $newarray;
			$fileName = $row->website_id . '-checkins-' . time() . ".pdf";
			$pdf = PDF::loadView('checkinpdf', $data);
			$pdf->setPaper('a4', 'landscape'); //save('/public/upload/pdf/'.$fileName);
			Storage::disk('public')->put('/pdf/' . $fileName, $pdf->output());
			$details = $row;
			$details['url'] = Storage::url($fileName);
			$details['report_name'] = "Checkins Report";
			$details['date_range'] = $dateS->format('Y-m-d') . " to " . $dateE->format('Y-m-d');
			Mail::to($row->email)->send(new \App\Mail\AttachFile($details));
			// echo "</br>". url($details['url'])."</br>";

			DB::disconnect('tenant');
		}

	}

	public function nearmissCustomDateRange(Request $request) {
		$dateS = new Carbon($request->start_date);
		$dateE = new Carbon($request->end_date);
		$all_pharmacy = User::all();

		foreach ($all_pharmacy as $row) {
			$newarray = array();
			$this->get_connection($row->website_id);
			$get_result = MissedPatient::whereBetween('created_at', [$dateS->format('Y-m-d') . " 00:00:00", $dateE->format('Y-m-d') . " 23:59:59"])->get();
			// foreach($get_result as $col) {
			// 	$newarray[]=$col;
			// }
			$data['newarray'] = $get_result;
			$fileName = $row->website_id . '-nearmiss-' . time() . ".pdf";
			$pdf = PDF::loadView('nearmisspdf', $data);
			$pdf->setPaper('a4', 'landscape'); //save('/public/upload/pdf/'.$fileName);
			Storage::disk('public')->put('/pdf/' . $fileName, $pdf->output());
			$details = $row;
			$details['url'] = Storage::url($fileName);
			$details['report_name'] = "Nearmiss Report";
			$details['date_range'] = $dateS->format('Y-m-d') . " to " . $dateE->format('Y-m-d');
			Mail::to($row->email)->send(new \App\Mail\AttachFile($details));
			// echo "</br>". url($details['url'])."</br>";

			DB::disconnect('tenant');
		}

	}

	public function returnCustomDateRange(Request $request) {
		$dateS = new Carbon($request->start_date);
		$dateE = new Carbon($request->end_date);
		$all_pharmacy = User::all();

		foreach ($all_pharmacy as $row) {
			$newarray = array();
			$this->get_connection($row->website_id);
			$get_result = PatientReturn::select("patient_returns.*", "patients.first_name", "patients.last_name")
				->join("patients", "patient_returns.patient_id", "=", "patients.id")
				->whereBetween('patient_returns.created_at', [$dateS->format('Y-m-d') . " 00:00:00", $dateE->format('Y-m-d') . " 23:59:59"])
				->get();
			foreach ($get_result as $col) {
				$myArray = explode(',', $col->store);
				$stors = Store::whereIn('id', $myArray)->get();
				$loc = array();
				foreach ($stors as $store) {
					$loc[] = $store->name;
				}

				$col->store = implode(",", $loc);
				$newarray[] = $col;
			}
			$data['newarray'] = $newarray;
			$fileName = $row->website_id . '-returns-' . time() . ".pdf";
			$pdf = PDF::loadView('returnpdf', $data);
			$pdf->setPaper('a4', 'landscape'); //save('/public/upload/pdf/'.$fileName);
			Storage::disk('public')->put('/pdf/' . $fileName, $pdf->output());
			$details = $row;
			$details['url'] = Storage::url($fileName);
			$details['report_name'] = "Returns Report";
			$details['date_range'] = $dateS->format('Y-m-d') . " to " . $dateE->format('Y-m-d');
			Mail::to($row->email)->send(new \App\Mail\AttachFile($details));
			// echo "</br>". url($details['url'])."</br>";

			DB::disconnect('tenant');
		}

	}
	public function auditCustomDateRange(Request $request) {
		$dateS = new Carbon($request->start_date);
		$dateE = new Carbon($request->end_date);
		$all_pharmacy = User::all();

		foreach ($all_pharmacy as $row) {
			$newarray = array();
			$this->get_connection($row->website_id);
			$get_result = PatientReturn::select("patient_returns.*", "patients.first_name", "patients.last_name")
				->join("patients", "patient_returns.patient_id", "=", "patients.id")
				->whereBetween('patient_returns.created_at', [$dateS->format('Y-m-d') . " 00:00:00", $dateE->format('Y-m-d') . " 23:59:59"])
				->get();
			foreach ($get_result as $col) {
				$myArray = explode(',', $col->store);
				$stors = Store::whereIn('id', $myArray)->get();
				$loc = array();
				foreach ($stors as $store) {
					$loc[] = $store->name;
				}

				$col->store = implode(",", $loc);
				$newarray[] = $col;
			}
			$data['newarray'] = $newarray;
			$fileName = $row->website_id . '-audit-' . time() . ".pdf";
			$pdf = PDF::loadView('auditpdf', $data);
			$pdf->setPaper('a4', 'landscape'); //save('/public/upload/pdf/'.$fileName);
			Storage::disk('public')->put('/pdf/' . $fileName, $pdf->output());
			$details = $row;
			$details['url'] = Storage::url($fileName);
			$details['Audit_name'] = "Checkins Report";
			$details['date_range'] = $dateS->format('Y-m-d') . " to " . $dateE->format('Y-m-d');
			Mail::to($row->email)->send(new \App\Mail\AttachFile($details));
			// echo "</br>". url($details['url'])."</br>";

			DB::disconnect('tenant');
		}

	}
	public function noteforpatientCustomDateRange(Request $request) {
		$dateS = new Carbon($request->start_date);
		$dateE = new Carbon($request->end_date);
		$all_pharmacy = User::all();

		foreach ($all_pharmacy as $row) {
			$newarray = array();
			$this->get_connection($row->website_id);
			$get_result = NotesForPatient::select("notes_for_patients.*", "patients.first_name", "patients.last_name")
				->join("patients", "notes_for_patients.patient_id", "=", "patients.id")
				->whereBetween('notes_for_patients.created_at', [$dateS->format('Y-m-d') . " 00:00:00", $dateE->format('Y-m-d') . " 23:59:59"])
				->get();
			// foreach($get_result as $col) {
			// 	$newarray[]=$col;
			// }
			$data['newarray'] = $get_result;
			$fileName = $row->website_id . '-notes_for_patients-' . time() . ".pdf";
			$pdf = PDF::loadView('noteforpatientpdf', $data);
			$pdf->setPaper('a4', 'landscape'); //save('/public/upload/pdf/'.$fileName);
			Storage::disk('public')->put('/pdf/' . $fileName, $pdf->output());
			$details = $row;
			$details['url'] = Storage::url($fileName);
			$details['report_name'] = "NotesForPatient Report";
			$details['date_range'] = $dateS->format('Y-m-d') . " to " . $dateE->format('Y-m-d');
			Mail::to($row->email)->send(new \App\Mail\AttachFile($details));
			// echo "</br>". url($details['url'])."</br>";

			DB::disconnect('tenant');
		}

	}

}

// UPDATE patients SET is_archive = CASE WHEN created_at < '2020-09-01' THEN '1' ELSE 0 END