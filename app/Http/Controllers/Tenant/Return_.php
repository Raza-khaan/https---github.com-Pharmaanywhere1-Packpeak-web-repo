<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\EventsLog;
use App\Models\Tenant\Patient;
use App\Models\Tenant\PatientReturn;
use App\Models\Tenant\Store;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class Return_ extends Controller {
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

	public function returns(Request $request) {

		

		if ($request->form15 == '1') {
			$data['facilities'] = Store::get();
			$data['patients'] = Patient::get();
			return view($this->views . '.returns')->with($data);
		} else {
			return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
		}
	}

	public function all_returns(Request $request) {
		if ($request->form15 == '1') {
			$data = [];
			$data['returns'] = PatientReturn::where('is_archive','=',0)->get();
			return view($this->views . '.all_returns')->with($data);
		} else {
			return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
		}
	}

	public function archive_all_returns(Request $request) {
		
			$data['returns'] = PatientReturn::where('is_archive','=',1)->get();
		
			return view($this->views . '.archive_all_returns')->with($data);
		
	}


	/* Soft Delete Return  */
public function  softarchive(Request $request)
{


	$getdata=PatientReturn::where('id','=',$request->id)->first();
	$getdata->is_archive = 1;
	$getdata->save();
	


	$patient_name = Patient::where('id','=',$getdata->patient_id)->first();
	$name = $patient_name->first_name .' '. $patient_name->last_name;
 
	return redirect()->back()->with(["msg" => '<div class="alert alert-success">Patient return (<strong>' . $name . '</strong>) archived Successfully.</div>']);

 }


public function  softunarchive(Request $request)
{
   $getdata=PatientReturn::where('id','=',$request->id)->first();
   $getdata->is_archive = 0;
   $getdata->save();


   $patient_name = Patient::where('id','=',$getdata->patient_id)->first();
   $name = $patient_name->first_name .' '. $patient_name->last_name;
   return redirect()->back()->with(["msg" => '<div class="alert alert-success">Patient return (<strong>' . $name . '</strong>) unarchived Successfully.</div>']);
}

	/*save the return data */
	public function save_return(Request $request) {

		$validate_array = array(
			'patient_id' => 'required|numeric|min:1',
			'select_days_weeks' => 'required|string|max:255',
			'store' => 'required|numeric|min:1',
			'dob' => 'date_format:d/m/Y|before:tomorrow',
			'initials' => 'required|max:255',
			'no_of_returned_day_weeks' => 'required|min:0',
		);
		if ($request->store == 'other') {
			$validate_array['other_store'] = 'required';

		}
		$validator = $request->validate($validate_array);
		// return $validator;

		$insert_data = array(
			'patient_id' => $request->patient_id,
			'dob' => Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
			'day_weeks' => $request->select_days_weeks,
			'returned_in_days_weeks' => $request->no_of_returned_day_weeks,
			'store' => $request->store,
			'staff_initials' => $request->initials,
		);

		if (!empty($request->session()->get('phrmacy'))) {
			$insert_data['website_id'] = $request->session()->get('phrmacy')->website_id;
			$insert_data['created_by'] = '-' . $request->session()->get('phrmacy')->id;

		}

		$facility = Store::find($request->store);

		if ($facility->name == 'other') {

			$insert_data['other_store'] = $request->other_store;

		}
		// return $insert_data;
		//    print_r($insert_data);  die;
		$save_res = PatientReturn::insert_data($insert_data);


		$storeresult = Store::where('name','=',$request->other_store)->first();
		
		if($storeresult)
		{
	
		}
		else
		{
			if($request->other_store <>'')
			{
				$newstore = new Store;
				$newstore->name =  $request->other_store;
				$newstore->save();
			}
			
		}

		EventsLog::create([
			'website_id' => $request->session()->get('phrmacy')->website_id,
			'action_by' => $request->session()->get('phrmacy')->id,
			'action' => 1,
			'action_detail' => 'Return',
			'comment' => 'Create Return',
			'patient_id' => $request->patient_id,
			'ip_address' => $request->ip(),
			'type' => $request->session()->get('phrmacy')->roll_type,
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		return redirect()->back()->with(["msg" => '<div class="alert alert-success">Patient <strong>Return </strong> Added Succes.</div>']);
	}

	/* delete Return  */
	public function returnDelete(Request $request, $tenantName, $id) {
		$delete = PatientReturn::find($id);
		if (!$delete) {
			return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Patient id <strong>' . $id . '</strong> doesnot match in our records.</div>']);
		}

		$patient_name = $delete->patients->first_name . ' ' . $delete->patients->last_name;
		$delete->delete();
		EventsLog::create([
			'website_id' => $request->session()->get('phrmacy')->website_id,
			'action_by' => $request->session()->get('phrmacy')->id,
			'action' => 3,
			'action_detail' => 'Return',
			'comment' => 'Delete Return',
			'patient_id' => $delete->patient_id,
			'ip_address' => $request->ip(),
			'type' => $request->session()->get('phrmacy')->roll_type,
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Return of this patient (<strong>' . $patient_name . '</strong>) deleted Successfully.</div>']);
	}

	/* edit Return  */
	public function returnEdit(Request $request, $tenantName, $id) {
		if ($request->form15 == '1') {
			$ob = PatientReturn::find($id);
			if (!$ob) {
				return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Return id <strong>' . $id . '</strong> doesnot match in our records.</div>']);
			}

			if ($request->isMethod('post')) {

				$validate_array = array(
					'patient_id' => 'required|numeric|min:1',
					'select_days_weeks' => 'required|string|max:255',
					'store' => 'required|numeric|min:1',
					'dob' => 'date_format:d/m/Y|before:tomorrow',
					// 'initials'          => 'string|max:255',
					'no_of_returned_day_weeks' => 'required|min:0',
				);

				$insert_data = array(
					'patient_id' => $request->patient_id,
					'dob' => Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
					'day_weeks' => $request->select_days_weeks,
					'returned_in_days_weeks' => $request->no_of_returned_day_weeks,
					'store' => $request->store,
					'staff_initials' => $request->initials,
				);

				if (!empty($request->session()->get('phrmacy'))) {
					// return $request->session()->get('phrmacy')->website_id;
					$insert_data['website_id'] = $request->session()->get('phrmacy')->website_id;
					$insert_data['created_by'] = '-' . $request->session()->get('phrmacy')->id;
					//$validate_array['website_id']='required';
				}

				$validator = $request->validate($validate_array);

				if (isset($request->other_store)) {

					$insert_data['other_store'] = $request->other_store;
				}
				//return $insert_data;
				$ob->update($insert_data);


				$storeresult = Store::where('name','=',$request->other_store)->first();
		
				if($storeresult)
				{
			
				}
				else
				{
					if($request->other_store <>'')
					{
						$newstore = new Store;
						$newstore->name =  $request->other_store;
						$newstore->save();
					}
					
				}

				EventsLog::create([
					'website_id' => $request->session()->get('phrmacy')->website_id,
					'action_by' => $request->session()->get('phrmacy')->id,
					'action' => 2,
					'action_detail' => 'Return',
					'comment' => 'Update Return',
					'patient_id' => $request->patient_id,
					'ip_address' => $request->ip(),
					'type' => $request->session()->get('phrmacy')->roll_type,
					'user_agent' => $request->userAgent(),
					'authenticated_by' => 'packnpeaks',
					'status' => 1,
				]);
				return redirect('all_returns')->with(["msg" => '<div class="alert alert-success">Return is <strong>Updated </strong> Successfully.</div>']);
			}

			$data = array();
			$data['facilities'] = Store::get();
			$data['patients'] = Patient::get();
			$data['returns'] = $ob;
			// return $ob;
			return view($this->views . '.returnEdit', $data);
		} else {
			return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
		}

	}

}