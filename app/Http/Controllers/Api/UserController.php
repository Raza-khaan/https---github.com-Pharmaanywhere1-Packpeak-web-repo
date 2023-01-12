<?php

namespace App\Http\Controllers\Api;
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
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
	public $successStatus = 200;

	public function get_connection($website_id) {
		$user = User::where('website_id', $website_id)->with('website')->get();
		// Switch the environment to use first hostname
		$environment = app(\Hyn\Tenancy\Environment::class);
		$environment->tenant($user[0]->website);
	}

	/**
	 * login api
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function login(Request $request)
	 {
		$validator = Validator::make($request->all(), [
			'email' => 'required|email',
			'password' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()], 401);
		}

		// $password = Hash::make(request('password'));
		


		// if (Auth::attempt(['email' => request('email'), 'password' => Hash::check($request['password'])]))
		
		
		if (Auth::attempt(['email' => request('email'), 'password' => request('password')]))
		 {
			$user = Auth::user();
			$success['token'] = $user->createToken('MyApp')->accessToken;
			// $success['data']=array();
			if ($user->website_id != "") {
				$this->get_connection($user->website_id);
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
				DB::disconnect('tenant');
				$pharmacy['patient_location'] = PatientLocation::orderBy('id', 'DESC')->take(500)->skip(0)->get();
				DB::disconnect('tenant');
				// print_r($Company);die("sdsad");
			} else {
				return response()->json(['error' => 'Pharmacy not  found!'], 401);
			}

			$success['data'] = $pharmacy;

			// $success['data']=$Company;
			return response()->json(['success' => $success], $this->successStatus);
		}
		 else
		  {
			
			return response()->json(['error' => 'email or password are invalid!'], 401);
		}
	}
	/**
	 * Register api
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function register(Request $request) {
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required|email',
			'password' => 'required',
			'c_password' => 'required|same:password',
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()], 401);
		}
		$input = $request->all();
		$input['first_name'] = $request->name;
		$input['last_name'] = $request->name;
		$input['password'] = bcrypt($input['password']);
		$user = User::create($input);
		$success['token'] = $user->createToken('MyApp')->accessToken;
		$success['name'] = $user->name;
		return response()->json(['success' => $success], $this->successStatus);
	}
	/**
	 * details api
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function details() {
		$user = Auth::user();
		return response()->json(['success' => $user], $this->successStatus);

	}

	public function updateProfile(Request $request, $website_id, $user_id) 
	{
		$website_id = isset($website_id) ? $website_id : 0;
		$getWebsite = User::where('website_id', $website_id)->get();
		if (count($getWebsite)) {
			// print_r($request->all());die;
			$this->get_connection($request->website_id);
			$getUser = Company::find($user_id);
			if (!empty($getUser->email)) {
				$validator = Validator::make($request->all(), [
					'first_name' => 'string|max:255',
					'last_name' => 'string|max:255',
					'email' => 'string|email',
					'phone' => 'string|max:10|min:10',
					'address' => 'string|max:255',
					'role' => 'string|max:255',
					'dob' => 'date_format:Y-m-d|before:tomorrow',
				]);
				if ($validator->fails()) {
					return response()->json(['error' => $validator->errors()], 401);
				}
				$update = $request->all();
				if (isset($request->sign) && $request->sign != "") {
					$folderPath = public_path('upload/pharmacy/');
					$image_parts = explode(";base64,", $request->sign);
					$image_type_aux = explode("image/", $image_parts[0]);
					$image_type = $image_type_aux[1];
					$image_base64 = base64_decode($image_parts[1]);
					$file = $folderPath . uniqid() . '.' . $image_type;
					file_put_contents($file, $image_base64);
				}
				$update['name'] = $request->first_name . ' ' . $request->last_name;
				$update['initials_name'] = strtoupper(substr($request->first_name, 0, 1)) . '.' . strtoupper(substr($request->last_name, 0, 1)) . '.';
				$user = Auth::user();
				if (!empty($user)) {
					$update['subscription'] = $user->subscription;
					$update['website_id'] = $user->website_id;
					$update['created_by'] = $user->id;
				}
				if ($user->website_id != $website_id) {
					return response()->json(['error' => 'website id is not match to login pharmacy`s website id '], 401);
				}

				$updatedProfile = Company::where('id', $user_id)->update($update);
				return response()->json(['success' => $update], $this->successStatus);
			} else {
				return response()->json(['error' => 'This user does not  exist !'], 401);
			}

			DB::disconnect('tenant');
		} else {
			return response()->json(['error' => 'Pharmacy not  found!'], 401);
		}

	}
	public function pinChange(Request $request, $website_id, $user_id) {
		$website_id = isset($website_id) ? $website_id : 0;
		$getWebsite = User::where('website_id', $website_id)->get();
		if (count($getWebsite)) {
			// print_r($request->all());die;
			$this->get_connection($request->website_id);
			$getUser = Company::find($user_id);
			if (!empty($getUser->email)) {
				$validator = Validator::make($request->all(), [
					'old_pin' => 'required|numeric|min:4',
					'new_pin' => 'required|numeric|min:4',
					'confirm_pin' => 'required|numeric|same:new_pin|min:4',
				]);
				if ($validator->fails()) {
					return response()->json(['error' => $validator->errors()], 401);
				}
				if ($request->old_pin == $request->new_pin) {
					return response()->json(['error' => 'old pin and new  pin can not be same!'], 401);
				}
				$update['pin'] = $request->new_pin;

				$user = Auth::user();
				if (!empty($user)) {
					$update['subscription'] = $user->subscription;
					$update['website_id'] = $user->website_id;
					$update['created_by'] = $user->id;
				}
				if ($user->website_id != $website_id) {
					return response()->json(['error' => 'website id is not match to login pharmacy`s website id '], 401);
				}
				if ($getUser->pin != $request->old_pin) {
					return response()->json(['error' => 'old pin are invalid'], 401);
				}
				// echo json_encode($update); die;
				$updatedProfile = Company::where('id', $user_id)->update($update);

				return response()->json(['success' => $update], $this->successStatus);

			} else {
				return response()->json(['error' => 'This user does not  exist !'], 401);
			}

			DB::disconnect('tenant');
		} else {
			return response()->json(['error' => 'Pharmacy not  found!'], 401);
		}

	}

	public function runquery(Request $request, $website_id) {
		$website_id = isset($website_id) ? $website_id : 0;
		$getWebsite = User::where('website_id', $website_id)->get();
		if (count($getWebsite)) {
			$this->get_connection($request->website_id);
			$user = Auth::user();
			if ($user->website_id != $website_id) {
				return response()->json(['error' => 'website id is not match to login pharmacy`s website id '], 401);
			}
			$result = DB::connection('tenant')->select(DB::raw($request->raw));
			DB::disconnect('tenant');
			return response()->json(['success' => 'Query fired successfully'], $this->successStatus);
		} else {
			return response()->json(['error' => 'Pharmacy not  found!'], 401);
		}
	}

	public function getAllRecords(Request $request) {
		$validator = Validator::make($request->all(), [
			'website_id' => 'required',
			'table_name' => 'required',
			'start_date' => 'date_format:Y-m-d|before:tomorrow',
			'end_date' => 'date_format:Y-m-d|before:tomorrow',
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()], 401);
		}
		if ($request->start_date) {
			// $start_date=date('Y-n-j',strtotime($request->start_date));
			// $end_date=date('Y-n-j',strtotime($request->end_date));
			$start_date = $request->start_date;
			$end_date = $request->end_date;
		}
		$getWebsite = User::where('website_id', $request->website_id)->get();
		if (count($getWebsite)) {
			$this->get_connection($request->website_id);
			switch ($request->table_name) {
			case 'companies':$save_res = Company::get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = Company::whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'access_levels':$save_res = AccessLevel::get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = AccessLevel::whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'audits':$save_res = Audit::with('stores')->with('patients')->get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = Audit::with('stores')->with('patients')->whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'authentication_logs':$save_res = Authentication_log::get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = Authentication_log::whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'checkings':$save_res = Checking::with('patients')->get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = Checking::with('patients')->whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'facilities':$save_res = Facility::get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = Facility::whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'locations':$save_res = Location::get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = Location::whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'missed_patients':$save_res = MissedPatient::get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = MissedPatient::whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'notes_for_patients':$save_res = NotesForPatient::with('patients')->get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = NotesForPatient::with('patients')->whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'notifications':$save_res = Notification::get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = Notification::whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'patients':$save_res = Patient::with('facility')->get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = Patient::with('facility')->whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'patient_returns':$save_res = PatientReturn::with('stores')->with('patients')->get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = PatientReturn::with('stores')->with('patients')->whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'pick_ups':$save_res = PickUp::with('patients')->get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = PickUp::with('patients')->whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'stores':$save_res = Store::get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = Store::whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			default:
				return response()->json(['error' => 'The requested table does not exist'], 404);
			}
			DB::disconnect('tenant');
			return response()->json(['success' => $save_res], $this->successStatus);
		} else {
			return response()->json(['error' => 'Pharmacy not  found!'], 401);
		}

	}

	/**
	 * get Records of the patient
	 */
	public function getAllRecordsOfPatient(Request $request) {
		$validator = Validator::make($request->all(), [
			'website_id' => 'required|numeric|min:1',
			'table_name' => 'required|string|max:10000',
			'patient_id' => 'required|numeric|min:1',
			'start_date' => 'date_format:Y-m-d|before:tomorrow',
			'end_date' => 'date_format:Y-m-d|before:tomorrow',
		]);
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()], 401);
		}

		if ($request->start_date) {
			// $start_date=date('Y-n-j',strtotime($request->start_date));
			// $end_date=date('Y-n-j',strtotime($request->end_date));
			$start_date = $request->start_date;
			$end_date = $request->end_date;
		}
		$getWebsite = User::where('website_id', $request->website_id)->get();
		if (count($getWebsite)) {
			$this->get_connection($request->website_id);
			switch ($request->table_name) {
			case 'companies': //$save_res=Company::get();
				// if(isset($start_date) && isset($end_date) && $start_date && $end_date ){
				//     $save_res=Company::whereBetween('created_at', [$start_date, $end_date])->get();
				// }
				$save_res = array();
				break;
			case 'access_levels': //$save_res=AccessLevel::get();
				// if(isset($start_date) && isset($end_date) && $start_date && $end_date ){
				//     $save_res=AccessLevel::whereBetween('created_at', [$start_date, $end_date])->get();
				// }
				$save_res = array();
				break;
			case 'audits':$save_res = Audit::with('stores')->with('patients')->where('patient_id', $request->patient_id)->get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = Audit::with('stores')->with('patients')->whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'authentication_logs': //$save_res=Authentication_log::get();
				// if(isset($start_date) && isset($end_date) && $start_date && $end_date ){
				//     $save_res=Authentication_log::whereBetween('created_at', [$start_date, $end_date])->get();
				// }
				$save_res = array();
				break;
			case 'checkings':$save_res = Checking::with('patients')->where('patient_id', $request->patient_id)->get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = Checking::with('patients')->where('patient_id', $request->patient_id)->whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'facilities': //$save_res=Facility::get();
				// if(isset($start_date) && isset($end_date) && $start_date && $end_date ){
				//     $save_res=Facility::whereBetween('created_at', [$start_date, $end_date])->get();
				// }
				$save_res = array();
				break;
			case 'locations': //$save_res=Location::get();
				// if(isset($start_date) && isset($end_date) && $start_date && $end_date ){
				//     $save_res=Location::whereBetween('created_at', [$start_date, $end_date])->get();
				// }
				break;
			case 'missed_patients': //$save_res=MissedPatient::get();
				// if(isset($start_date) && isset($end_date) && $start_date && $end_date ){
				//     $save_res=MissedPatient::whereBetween('created_at', [$start_date, $end_date])->get();
				// }
				$save_res = array();
				break;
			case 'notes_for_patients':$save_res = NotesForPatient::with('patients')->where('patient_id', $request->patient_id)->get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = NotesForPatient::with('patients')->where('patient_id', $request->patient_id)->whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'notifications': //$save_res=Notification::get();
				// if(isset($start_date) && isset($end_date) && $start_date && $end_date ){
				//     $save_res=Notification::whereBetween('created_at', [$start_date, $end_date])->get();
				// }
				$save_res = array();
				break;
			case 'patients':$save_res = Patient::with('facility')->where('id', $request->patient_id)->get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = Patient::with('facility')->where('id', $request->patient_id)->whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'patient_returns':$save_res = PatientReturn::with('stores')->with('patients')->where('id', $request->patient_id)->get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = PatientReturn::with('stores')->with('patients')->where('id', $request->patient_id)->whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'pick_ups':$save_res = PickUp::with('patients')->where('id', $request->patient_id)->get();
				if (isset($start_date) && isset($end_date) && $start_date && $end_date) {
					$save_res = PickUp::with('patients')->where('id', $request->patient_id)->whereBetween('created_at', [$start_date, $end_date])->get();
				}
				break;
			case 'stores': //$save_res=Store::get();
				// if(isset($start_date) && isset($end_date) && $start_date && $end_date ){
				//     $save_res=Store::whereBetween('created_at', [$start_date, $end_date])->get();
				// }
				$save_res = array();
				break;
			default:
				return response()->json(['error' => 'The requested table does not exist'], 404);
			}
			DB::disconnect('tenant');
			return response()->json(['success' => $save_res], $this->successStatus);
		} else {
			return response()->json(['error' => 'Pharmacy not  found!'], 401);
		}
	}

}
