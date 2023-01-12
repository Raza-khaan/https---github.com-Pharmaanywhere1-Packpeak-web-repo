<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Patient;
use App\Models\Tenant\PatientLocation;
use App\User;
use App\Models\Tenant\Facility as  TenantFacilityModel;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class PatientController extends Controller {
	public $successStatus = 200;
	public function get_connection($website_id) {
		$get_user = User::get_by_column('website_id', $website_id);
		config(['database.connections.tenant.database' => $get_user[0]->host_name]);
		DB::purge('tenant');
		DB::reconnect('tenant');
	}

	/**
	 * Create Patient
	 */
	public function createPatient(Request $request) {
		$getWebsite = User::where('website_id', $request->website_id)->get();
		if (count($getWebsite)) {
			$this->get_connection($request->website_id);
			$validator = Validator::make($request->all(), [
				'website_id' => 'required|numeric|min:1',
				'first_name' => 'required|string|max:255',
				'last_name' => 'required|string|max:255',
				'dob' => 'required|date_format:Y-m-d|before:tomorrow',
				'phone_number' => 'required|unique:tenant.patients|min:10|max:10',
				'facility' => 'required|string|max:255',
				'mobile_no' => 'min:10|max:10',
			]);
			if ($validator->fails()) {
				return response()->json(['error' => $validator->errors()], 401);
			}
			// if($request->dob){
			//     $request->dob=date('Y-n-j',strtotime($request->dob));
			// }
			$insert_data = array(
				'first_name' => $request->first_name,
				'last_name' => $request->last_name,
				'dob' => $request->dob,
				'address' => $request->address,
				'phone_number' => $request->phone_number,
				'facilities_id' => $request->facility,
				'location' => isset($request->location) ? implode(',', $request->location) : '',
			);
			if (isset($request->up_delivered) && $request->up_delivered == 'on') {
				$insert_data['text_when_picked_up_deliver'] = 1;
			} else {
				$insert_data['text_when_picked_up_deliver'] = 0;
			}
			if (isset($request->same_as_above) && $request->same_as_above == 'on') {
				$insert_data['mobile_no'] = $request->mobile_no;
			} else {
				$insert_data['mobile_no'] = $request->phone_number;
			}
			$user = Auth::user();
			$insert_data['website_id'] = $request->website_id;
			$insert_data['created_by'] = $request->created_by;
			if ($user->website_id != $request->website_id) {
				return response()->json(['error' => 'website id is not match to login pharmacy`s website id '], 401);
			}
			if (isset($request->facility)) {
				$getPharmacFacility = TenantFacilityModel::where('name', $request->facility)->first();
				if (empty($getPharmacFacility)) {
					$createNewFacility = TenantFacilityModel::create([
						'name' => $request->facility,
						'created_by' => '-1',
						'status' => '1',
					]);
					$facilityId = $createNewFacility->id;
				} else {
					$facilityId = $getPharmacFacility->id;
				}
				$insert_data['facilities_id'] = $facilityId;

			}
			$createdPatient = Patient::create($insert_data);

			//    Patient Location
			$location_data['locations'] = $insert_data['location'];
			$location_data['patient_id'] = $createdPatient->id;
			$location_data['action_by'] = '';
			PatientLocation::insert_data($location_data);
			//    End
			// print_r($insert_data); die;
			DB::disconnect('tenant');
			return response()->json(['success' => $createdPatient], $this->successStatus);
		} else {
			return response()->json(['error' => 'Pharmacy not  found!'], 401);
		}
	}

}
