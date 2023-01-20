<?php

namespace App\Http\Controllers\Api;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Tenant\AccessLevel;
use App\Models\Tenant\Audit;
use App\Models\Tenant\Authentication_log;
use App\Models\Tenant\Checking;
use App\Models\Tenant\Company;
use App\Models\Tenant\EventsLog;
use App\Models\Tenant\Facility;
use App\Models\Tenant\Form;
use App\Models\Tenant\Location;
use App\Models\Tenant\MissedPatient;
use App\Models\Tenant\NotesForPatient;
use App\Models\Tenant\Notification;
use App\Models\Tenant\Patient;
use App\Models\Tenant\PatientLocation;
use App\Models\Tenant\PatientReturn;
use App\Models\Tenant\PickUp;
use App\Models\Tenant\Store;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;

class PostController extends Controller {
	public $successStatus = 200;

	public function sendNotification(Request $request) {

		$regx = "/^04[0-9]{8}$/";
		if (isset($request)) {
			$validator = Validator::make($request->all(), [
				'message' => 'required|string|max:255',
				'msg_to' => 'required|regex:/^04[0-9]{8}$/',
			]);
			if ($validator->fails()) {
				return response()->json(['error' => $validator->errors()], 401);
			} else {
				try {
					$to = $request->msg_to;
					$message = $request->message;
					$result = Helper::smsSendToMobile('+918896123344', $message);
					return response()->json(['success' => $result], $this->successStatus);
				} catch (\Exception $e) {
					return response()->json(['error' => "Internal Server Error"], 500);
				}
			}
		}

	}

	public function get_connection($website_id) {
		$user = User::where('website_id', $website_id)->with('website')->get();
		// Switch the environment to use first hostname
		$environment = app(\Hyn\Tenancy\Environment::class);
		$environment->tenant($user[0]->website);
	}

	public function getNewChanges(Request $request) {

		$website_id = isset($request->website_id) ? $request->website_id : 0;
		$getWebsite = User::where('website_id', $website_id)->get();
		if (count($getWebsite)) {
			$this->get_connection($request->website_id);

			$validator = Validator::make($request->all(), [
				'last_sync' => 'required|date_format:Y-m-d H:i:s|before:tomorrow',
			]);
			if ($validator->fails()) {
				return response()->json(['error' => $validator->errors()], 401);
			}
			$last_sync = $request->last_sync;
			$mytime = Carbon::now();
			$current = $mytime->toDateTimeString();

			$getChangeData = EventsLog::whereBetween('created_at', [$last_sync, $current])->get();
			//echo json_encode($getChangeData);die;
			if (count($getChangeData)) {
				$pharmacy = $this->getAllTableRecords();
				$success['data'] = $pharmacy;
				return response()->json(['success' => $success], $this->successStatus);
			}

			DB::disconnect('tenant');
			return response()->json(['success' => "no chnage found !"], $this->successStatus);
		} else {
			return response()->json(['error' => 'Pharmacy not  found!'], 401);
		}

	}

	public function getAllTableRecords() {
		$pharmacy['companies'] = Company::orderBy('id', 'DESC')->take(500)->skip(0)->get();
		$pharmacy['access_levels'] = AccessLevel::orderBy('id', 'DESC')->take(500)->skip(0)->get();
		$pharmacy['audits'] = Audit::with('stores')->with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
		$pharmacy['authentication_logs'] = Authentication_log::orderBy('id', 'DESC')->take(500)->skip(0)->get();
		$pharmacy['events_logs'] = EventsLog::orderBy('id', 'DESC')->take(500)->skip(0)->get();
		$pharmacy['checkings'] = Checking::with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
		$pharmacy['stores'] = Store::orderBy('id', 'DESC')->take(500)->skip(0)->get();
		$pharmacy['facilities'] = Facility::orderBy('id', 'DESC')->take(500)->skip(0)->get();
		$pharmacy['locations'] = Location::orderBy('id', 'DESC')->take(500)->skip(0)->get();
		$pharmacy['missed_patients'] = MissedPatient::orderBy('id', 'DESC')->take(500)->skip(0)->get();
		$pharmacy['notes_for_patients'] = NotesForPatient::with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
		$pharmacy['notifications'] = Notification::orderBy('id', 'DESC')->take(500)->skip(0)->get();
		$pharmacy['patients'] = Patient::with('facility')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
		$pharmacy['patient_returns'] = PatientReturn::with('stores')->with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
		$pharmacy['pick_ups'] = PickUp::with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
		$pharmacy['forms'] = Form::orderBy('id', 'DESC')->take(500)->skip(0)->get();
		$pharmacy['patient_locations'] = PatientLocation::orderBy('id', 'DESC')->take(500)->skip(0)->get();
		return $pharmacy;
	}

	public function saveAllRecords(Request $request) {
		$website_id = isset($request->website_id) ? $request->website_id : 0;
		$getWebsite = User::where('website_id', $website_id)->get();
		if (count($getWebsite)) {
			$this->get_connection($request->website_id);
			//  echo count($request->data); die;
			$oldPatientIdsAray = array();
			$newPatientIdsAray = array();
			$combinearray = array();

			if (isset($request->patients) && count($request->patients)) {
				$validator = Validator::make($request->all(), [
					'patients.*.first_name' => 'string|max:255',
					'patients.*.last_name' => 'string|max:255',
					'patients.*.dob' => 'date_format:Y-m-d|before:tomorrow',
					//'patients.*.phone_number'      => 'unique:tenant.patients|min:10|max:10',
					//'patients.*.facilities_id'     => 'numeric|min:1',
					'patients.*.mobile_no' => 'min:10|max:10',
				]);
				if ($validator->fails()) {
					return response()->json(['error' => $validator->errors()], 401);
				}
			}

			if (isset($request->audits) && count($request->audits)) {
				$validatorAudit = Validator::make($request->all(), [
					'audits.*.patient_id' => 'required|numeric|min:1',
					'audits.*.no_of_weeks' => 'required|numeric|min:1',
					'audits.*.store' => 'required|numeric|min:1',
					'audits.*.staff_initials' => 'string|min:1||max:255',
					'audits.*.created_by' => 'numeric|min:1',
					'audits.*.status' => 'numeric|min:1',
					// 'audits.*.created_at' => 'date_format:Y-m-d H:i:s',
					'audits.*.website_id' => 'required|numeric|min:1',
				]);
				if ($validatorAudit->fails()) {
					return response()->json(['error' => $validatorAudit->errors()], 401);
				}
			}

			if (isset($request->checkings) && count($request->checkings)) {
				$validatorChecking = Validator::make($request->all(), [
					'checkings.*.patient_id' => 'required|numeric|min:1',
					'checkings.*.no_of_weeks' => 'required|numeric|min:1',
					'checkings.*.dd' => 'numeric|min:0|max:1',
					'checkings.*.location' => 'string|max:5000',
					'checkings.*.pharmacist_signature' => 'required|string|max:99999',
					'checkings.*.note_from_patient' => 'max:99999',
					'checkings.*.created_by' => 'numeric|min:1',
					'checkings.*.status' => 'numeric|min:1',
					'checkings.*.created_at' => 'date_format:Y-m-d H:i:s',
					'checkings.*.website_id' => 'required|numeric|min:1',
				]);
				if ($validatorChecking->fails()) {
					return response()->json(['error' => $validatorChecking->errors()], 401);
				}
			}

			if (isset($request->patients) && count($request->patients)) {
				foreach ($request->patients as $patients) {
					if ($patients['website_id'] == $website_id) {
						$oldPatientId = $patients['id'];
						$patients['id'] = null;
						if (isset($patients['facilities_name'])) {
							$getPharmacFacility = Facility::where('name', $patients['facilities_name'])->first();
							if (empty($getPharmacFacility)) {
								$createNewFacility = Facility::create([
									'name' => $patients['facilities_name'],
									'created_by' => '-1',
									'status' => '1',
								]);
								$facilityId = $createNewFacility->id;
							} else {
								$facilityId = $getPharmacFacility->id;
							}
							$patients['facilities_id'] = $facilityId;

						}
						$patients['location'] = $patients['locationID'];
						$createdPatient = Patient::create($patients);
						$newPatientId = $createdPatient->id;
						array_push($oldPatientIdsAray, $oldPatientId);
						array_push($newPatientIdsAray, $newPatientId);
					}
				}
				$combin = array_combine($oldPatientIdsAray, $newPatientIdsAray);
				array_push($combinearray, $combin);

			}

			if (isset($request->audits) && count($request->audits)) {
				foreach ($request->audits as $audit) {
					if ($audit['website_id'] == $website_id) {
						$audit['id'] = null;
						$patient_id = in_array($audit['patient_id'], $oldPatientIdsAray) ? $combinearray[0][$audit['patient_id']] : $audit['patient_id'];
						$audit['patient_id'] = $patient_id;
						Audit::insert($audit);
					}
				}
			}

			if (isset($request->checkings) && count($request->checkings)) {
				foreach ($request->checkings as $checkings) {
					if ($checkings['website_id'] == $website_id) {
						$checkings['id'] = null;
						$patient_id = in_array($checkings['patient_id'], $oldPatientIdsAray) ? $combinearray[0][$checkings['patient_id']] : $checkings['patient_id'];
						$checkings['patient_id'] = $patient_id;
						Checking::insert($checkings);
					}
				}

			}

			if (isset($request->missed_patients) && count($request->missed_patients)) {
				foreach ($request->missed_patients as $missed_patients) {
					if ($missed_patients['website_id'] == $website_id) {
						$missed_patients['id'] = null;
						MissedPatient::insert($missed_patients);
					}
				}

			}
			if (isset($request->notes_for_patients) && count($request->notes_for_patients)) {

				foreach ($request->notes_for_patients as $notes_for_patients) {
					if ($notes_for_patients['website_id'] == $website_id) {
						$notes_for_patients['id'] = null;
						$patient_id = in_array($notes_for_patients['patient_id'], $oldPatientIdsAray) ? $combinearray[0][$notes_for_patients['patient_id']] : $notes_for_patients['patient_id'];
						$notes_for_patients['patient_id'] = $patient_id;
						NotesForPatient::insert($notes_for_patients);
					}
				}

			}

			if (isset($request->patient_returns) && count($request->patient_returns)) {

				foreach ($request->patient_returns as $patient_returns) {
					if ($patient_returns['website_id'] == $website_id) {
						$patient_returns['id'] = null;
						$patient_id = in_array($patient_returns['patient_id'], $oldPatientIdsAray) ? $combinearray[0][$patient_returns['patient_id']] : $patient_returns['patient_id'];
						$patient_returns['patient_id'] = $patient_id;
						PatientReturn::insert($patient_returns);
					}
				}

			}
			if (isset($request->pick_ups) && count($request->pick_ups)) {

				foreach ($request->pick_ups as $pick_ups) {
					if ($pick_ups['website_id'] == $website_id) {
						$pick_ups['id'] = null;
						$patient_id = in_array($pick_ups['patient_id'], $oldPatientIdsAray) ? $combinearray[0][$pick_ups['patient_id']] : $pick_ups['patient_id'];
						$pick_ups['patient_id'] = $patient_id;
						PickUp::insert($pick_ups);
					}
				}

			}

			if (isset($request->patient_locations) && count($request->patient_locations)) {

				foreach ($request->patient_locations as $patient_location) {
					if ($patient_location['website_id'] == $website_id) {
						$patient_location['id'] = null;
						$patient_id = in_array($patient_location['patient_id'], $oldPatientIdsAray) ? $combinearray[0][$patient_location['patient_id']] : $patient_location['patient_id'];
						$patient_location['patient_id'] = $patient_id;
						PatientLocation::insert_data($patient_location);
					}
				}

			}

			$pharmacy['companies'] = Company::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['access_levels'] = AccessLevel::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['audits'] = Audit::with('stores')->with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['authentication_logs'] = Authentication_log::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['events_logs'] = EventsLog::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['checkings'] = Checking::with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['stores'] = Store::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['facilities'] = Facility::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['locations'] = Location::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['missed_patients'] = MissedPatient::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['notes_for_patients'] = NotesForPatient::with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['notifications'] = Notification::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['patients'] = Patient::with('facility')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['patient_returns'] = PatientReturn::with('stores')->with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['pick_ups'] = PickUp::with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['forms'] = Form::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['patient_locations'] = PatientLocation::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$success['data'] = $pharmacy;

			DB::disconnect('tenant');
			return response()->json(['success' => $success], $this->successStatus);
		} else {
			return response()->json(['error' => 'Pharmacy not  found!'], 401);
		}
	}

	public function saveAllRecords_new(Request $request) {
		$website_id = isset($request->website_id) ? $request->website_id : 0;
		$getWebsite = User::where('website_id', $website_id)->get();
		if (count($getWebsite)) {
			$this->get_connection($request->website_id);

			if (isset($request->patients) && count($request->patients)) {

				foreach ($request->patients as $patients) {
					if ($patients['website_id'] == $website_id) {
						$patients['id'] = null;
						Patient::insert($patients);
					}
				}

			}
			if (isset($request->audits) && count($request->audits)) {

				foreach ($request->audits as $audit) {
					if ($audit['website_id'] == $website_id) {
						$audit['id'] = null;
						Audit::insert($audit);
					}
				}

			}

			if (isset($request->checkings) && count($request->checkings)) {

				foreach ($request->checkings as $checkings) {
					if ($checkings['website_id'] == $website_id) {
						$checkings['id'] = null;
						Checking::insert($checkings);
					}
				}

			}

			if (isset($request->missed_patients) && count($request->missed_patients)) {
				foreach ($request->missed_patients as $missed_patients) {
					if ($missed_patients['website_id'] == $website_id) {
						$missed_patients['id'] = null;
						MissedPatient::insert($missed_patients);
					}
				}

			}
			if (isset($request->notes_for_patients) && count($request->notes_for_patients)) {

				foreach ($request->notes_for_patients as $notes_for_patients) {
					if ($notes_for_patients['website_id'] == $website_id) {
						$notes_for_patients['id'] = null;
						NotesForPatient::insert($notes_for_patients);
					}
				}

			}

			if (isset($request->patient_returns) && count($request->patient_returns)) {

				foreach ($request->patient_returns as $patient_returns) {
					if ($patient_returns['website_id'] == $website_id) {
						$patient_returns['id'] = null;
						PatientReturn::insert($patient_returns);
					}
				}

			}
			if (isset($request->pick_ups) && count($request->pick_ups)) {

				foreach ($request->pick_ups as $pick_ups) {
					if ($pick_ups['website_id'] == $website_id) {
						$pick_ups['id'] = null;
						PickUp::insert($pick_ups);
					}
				}

			}

			$pharmacy['companies'] = Company::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['access_levels'] = AccessLevel::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['audits'] = Audit::with('stores')->with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['authentication_logs'] = Authentication_log::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['events_logs'] = EventsLog::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['checkings'] = Checking::with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['stores'] = Store::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['facilities'] = Facility::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['locations'] = Location::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['missed_patients'] = MissedPatient::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['notes_for_patients'] = NotesForPatient::with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['notifications'] = Notification::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['patients'] = Patient::with('facility')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['patient_returns'] = PatientReturn::with('stores')->with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['pick_ups'] = PickUp::with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['forms'] = Form::orderBy('id', 'DESC')->take(500)->skip(0)->get();

			$success['data'] = $pharmacy;

			DB::disconnect('tenant');
			return response()->json(['success' => $success], $this->successStatus);
		} else {
			return response()->json(['error' => 'Pharmacy not  found!'], 401);
		}
	}

	public function saveAllRecords_old(Request $request) {
		$website_id = isset($request->website_id) ? $request->website_id : 0;
		$getWebsite = User::where('website_id', $website_id)->get();
		if (count($getWebsite)) {
			$this->get_connection($request->website_id);
			foreach ($request->data as $key => $row) {

				if ($key == 'patients') {
					if (count($row)) {
						foreach ($row as $patients) {
							if ($patients['website_id'] == $website_id) {
								$patients['id'] = null;
								Patient::insert($patients);
							}
						}
					}
				}
				if ($key == 'audits') {
					if (count($row)) {
						foreach ($row as $audit) {
							if ($audit['website_id'] == $website_id) {
								$audit['id'] = null;
								Audit::insert($audit);
							}
						}
					}
				}
				if ($key == 'checkings') {
					if (count($row)) {
						foreach ($row as $checkings) {
							if ($checkings['website_id'] == $website_id) {
								$checkings['id'] = null;
								Checking::insert($checkings);
							}
						}
					}
				}
				if ($key == 'missed_patients') {
					if (count($row)) {
						foreach ($row as $missed_patients) {
							if ($missed_patients['website_id'] == $website_id) {
								$missed_patients['id'] = null;
								MissedPatient::insert($missed_patients);
							}
						}
					}
				}
				if ($key == 'notes_for_patients') {
					if (count($row)) {
						foreach ($row as $notes_for_patients) {
							if ($notes_for_patients['website_id'] == $website_id) {
								$notes_for_patients['id'] = null;
								NotesForPatient::insert($notes_for_patients);
							}
						}
					}
				}

				if ($key == 'patient_returns') {
					if (count($row)) {
						foreach ($row as $patient_returns) {
							if ($patient_returns['website_id'] == $website_id) {
								$patient_returns['id'] = null;
								PatientReturn::insert($patient_returns);
							}
						}
					}
				}
				if ($key == 'pick_ups') {
					if (count($row)) {
						foreach ($row as $pick_ups) {
							if ($pick_ups['website_id'] == $website_id) {
								$pick_ups['id'] = null;
								PickUp::insert($pick_ups);
							}
						}
					}
				}

			}
			$pharmacy['companies'] = Company::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['access_levels'] = AccessLevel::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['audits'] = Audit::with('stores')->with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['authentication_logs'] = Authentication_log::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['events_logs'] = EventsLog::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['checkings'] = Checking::with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['stores'] = Store::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['facilities'] = Facility::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['locations'] = Location::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['missed_patients'] = MissedPatient::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['notes_for_patients'] = NotesForPatient::with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['notifications'] = Notification::orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['patients'] = Patient::with('facility')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['patient_returns'] = PatientReturn::with('stores')->with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['pick_ups'] = PickUp::with('patients')->orderBy('id', 'DESC')->take(500)->skip(0)->get();
			$pharmacy['forms'] = Form::orderBy('id', 'DESC')->take(500)->skip(0)->get();

			$success['data'] = $pharmacy;

			DB::disconnect('tenant');
			return response()->json(['success' => $success], $this->successStatus);
		} else {
			return response()->json(['error' => 'Pharmacy not  found!'], 401);
		}
	}
	
}
