<?php

namespace App\Imports;
use App\Models\Tenant\Facility;
// use Maatwebsite\Excel\Concerns\SkipsOnError;
use App\Models\Tenant\Patient as Patient_Model;
use App\Models\Tenant\PatientLocation;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Session;

class PatientsImport implements ToCollection, WithHeadingRow, WithValidation {
	use Importable, SkipsErrors;
	/**
	 * @param array $row
	 *
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */
	public function model(array $row) {

		if (!empty($row['facilities_id'])) {
			$facility = Facility::where('name', $row['facilities_id'])->first();
			if (empty($facility)) {
				$createNewFacility = Facility::create([
					'name' => $row['facilities_id'],
					'created_by' => Session::get('phrmacy')->id,
					'status' => '1',
				]);
				$facilityId = $createNewFacility->id;
			} else {
				$facilityId = $facility->id;
			}
		}

		$locations = explode(',', $row['location']);
		$selectedLocation = [];
		if (in_array('shelf', $locations)) {
			array_push($selectedLocation, 1);
		}
		if (in_array('fridge', $locations)) {
			array_push($selectedLocation, 2);
		}
		if (in_array('safe', $locations)) {
			array_push($selectedLocation, 3);
		}

		$resp = new Patient_Model([
			'first_name' => $row['first_name'],
			'last_name' => $row['last_name'],
			'dob' => $this->getAttribute($row['dob']),
			'address' => $row['address'],
			'phone_number' => $row['phone_number'],
			'facilities_id' => $facilityId,
			'location' => implode(",", $selectedLocation),
			'text_when_picked_up_deliver' => 0,
			'website_id' => Session::get('phrmacy')->website_id,
			'created_by' => Session::get('phrmacy')->id,
		]);
		print_r($resp);
		//    Patient Location
		$location_data['locations'] = implode(",", $selectedLocation);
		$location_data['patient_id'] = $resp->id;
		$location_data['action_by'] = Session::get('phrmacy')->id;
		PatientLocation::insert_data($location_data);
		//    End
		die;
		return $resp;
	}

	public function collection(Collection $rows) {
		foreach ($rows as $row) {
			if (!empty($row['facilities_id'])) {
				$facility = Facility::where('name', $row['facilities_id'])->first();
				if (empty($facility)) {
					$createNewFacility = Facility::create([
						'name' => $row['facilities_id'],
						'created_by' => Session::get('phrmacy')->id,
						'status' => '1',
					]);
					$facilityId = $createNewFacility->id;
				} else {
					$facilityId = $facility->id;
				}
			}

			$locations = explode(',', $row['location']);
			$selectedLocation = [];
			if (in_array('shelf', $locations)) {
				array_push($selectedLocation, 1);
			}
			if (in_array('fridge', $locations)) {
				array_push($selectedLocation, 2);
			}
			if (in_array('safe', $locations)) {
				array_push($selectedLocation, 3);
			}

			$id = Patient_Model::insertGetId([
				'first_name' => $row['first_name'],
				'last_name' => $row['last_name'],
				'dob' => $this->getAttribute($row['dob']),
				'address' => $row['address'],
				'phone_number' => $row['phone_number'],
				'facilities_id' => $facilityId,
				'location' => implode(",", $selectedLocation),
				'text_when_picked_up_deliver' => 0,
				'website_id' => Session::get('phrmacy')->website_id,
				'created_by' => Session::get('phrmacy')->id,
			]);

			//    Patient Location
			$location_data['locations'] = implode(",", $selectedLocation);
			$location_data['patient_id'] = $id;
			$location_data['action_by'] = Session::get('phrmacy')->id;
			PatientLocation::insert_data($location_data);
			//    End

		}
	}

	public function getAttribute($value) {
		$date = Carbon::parse($value);
		return $date->format('Y-m-d');
	}

	// public function onError(Throwable $error){

	// }

	public function rules(): array
	{
		return [
			'*.first_name' => 'required|string|max:255',
			'*.last_name' => 'required|string|max:255',
			'*.dob' => 'required|date_format:d-m-Y|before:tomorrow',
			'*.phone_number' => 'required|min:10|max:10',
			'*.facilities_id' => 'required|string|min:1',
			'*.mobile_no' => 'min:10|max:10',
		];
	}
}
