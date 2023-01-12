<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant\PatientLocation;
use App\Models\Tenant\PickUp;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class PickUpController extends Controller {
	public $successStatus = 200;
	public function get_connection($website_id) {
		$get_user = User::get_by_column('website_id', $website_id);
		config(['database.connections.tenant.database' => $get_user[0]->host_name]);
		DB::purge('tenant');
		DB::reconnect('tenant');
	}

	/**
	 * create Pickup
	 */
	public function createPickup(Request $request) {
		$getWebsite = User::where('website_id', $request->website_id)->get();
		if (count($getWebsite)) {
			$this->get_connection($request->website_id);
			$validator = Validator::make($request->all(), [
				'website_id' => 'required|numeric|min:1',
				'patient_name' => 'required|numeric|min:1',
				'dob' => 'date_format:Y-m-d|before:tomorrow',
				'no_of_weeks' => 'required|numeric|min:1',
				'who_pickup' => 'required|string|max:255',
				'patient_signature' => 'required|string|max:9000',
				'pharmacist_signature' => 'required|string|max:9000',
			]);
			if ($validator->fails()) {
				return response()->json(['error' => $validator->errors()], 401);
			}
			$insert_data = array(
				'patient_id' => $request->patient_name,
				'dob' => $request->dob,
				'no_of_weeks' => $request->no_of_weeks,
				'location' => isset($request->location) ? implode(',', $request->location) : '',
				'pick_up_by' => $request->who_pickup,
				'notes_from_patient' => $request->note,
				'notes_for_patient' => $request->notes_for_patient,
				'pharmacist_sign' => $request->pharmacist_signature,
				'patient_sign' => $request->patient_signature,
				'last_pick_up_date' => $request->last_pick_up_date,
				'weeks_last_picked_up' => $request->weeks_last_picked_up,
			);
			if ($request->who_pickup == 'carer') {
				$insert_data['carer_name'] = $request->carer_name;
			}
			$user = Auth::user();
			$insert_data['website_id'] = $request->website_id;
			$insert_data['created_by'] = $request->created_by;
			if ($user->website_id != $request->website_id) {
				return response()->json(['error' => 'website id is not match to login pharmacy`s website id '], 401);
			}

			/* Pharmacy Signature   */
			/* $folderPath = public_path('upload/pharmacy/');
				            $image_parts = explode(";base64,", $request->pharmacist_signature);
				            $image_type_aux = explode("image/", $image_parts[0]);
				            $image_type = $image_type_aux[1];
				            $image_base64 = base64_decode($image_parts[1]);
				            $file = $folderPath . uniqid() . '.'.$image_type;
			*/
			/* Technician Singnature  */
			/* $folderPath2 = public_path('upload/patient/');
				            $image_parts2 = explode(";base64,", $request->patient_signature);
				            $image_type_aux2 = explode("image/", $image_parts[0]);
				            $image_type2 = $image_type_aux2[1];
				            $image_base64_2 = base64_decode($image_parts2[1]);
				            $file2 = $folderPath2. uniqid() . '.'.$image_type2;
			*/

			$createdPickup = PickUp::insert_data($insert_data);

			//    Patient Location
			$location_data['locations'] = $insert_data['location'];
			$location_data['patient_id'] = $insert_data['patient_id'];
			$location_data['action_by'] = '';
			PatientLocation::insert_data($location_data);
			//    End
			// echo json_encode($request->all());
			// echo json_encode($insert_data);
			DB::disconnect('tenant');
			return response()->json(['success' => $createdPickup], $this->successStatus);
		} else {
			return response()->json(['error' => 'Pharmacy not  found!'], 401);
		}
	}
}
