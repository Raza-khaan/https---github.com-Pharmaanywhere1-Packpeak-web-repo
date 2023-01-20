<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Checkings;
use App\Models\Tenant\Packed;
use App\Models\Tenant\Pickups;
use App\Models\Tenant\EventsLog;
use App\Models\Tenant\Location;
use App\Models\Tenant\calender;
use App\Models\Tenant\NotesForPatient;
use App\Models\Tenant\Patient;
use App\Models\Tenant\PatientLocation;
use DB;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;
use Alert;

class Checking extends Controller {
	protected $views = '';
	public function __construct() {
		$this->views = 'tenant';
		$host = explode('.', request()->getHttpHost());
		//dd($host[0]);
		config(['database.connections.tenant.database' => $host[0]]);
		DB::purge('tenant');
		DB::reconnect('tenant');
		DB::disconnect('tenant');
	}

	public function checkings(Request $request) {
		if ($request->form9 == '1') {
			$data = array();
			$data['locations'] = Location::get();

			$data['patients'] = Patient::where('is_archive','=',0)->get();
			//return $data['patients'][0]->latestPickups;
			return view($this->views . '.checkings')->with($data);
		} else {
			return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
		}
	}

	public function packed(Request $request) {
		if ($request->form9 == '1') {
			$data = array();
			$data['locations'] = Location::get();

			$data['patients'] = Patient::where('is_archive','=',0)->get();
			//return $data['patients'][0]->latestPickups;
			return view($this->views . '.packed')->with($data);
		} else {
			return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
		}
	}

	/* save Checking here  */
	public function save_checking(Request $request) {
		$validate_array = array(
			'patient_id' => 'required|numeric|min:1',
			'no_of_weeks' => 'required|numeric|min:1',
			'pharmacist_signature' => 'required|string|max:99000',
		);
		//print_r($request->location); die;
		
		$insert_data = array(
			'patient_id' => $request->patient_id,
			'no_of_weeks' => $request->no_of_weeks,
			'location' => isset($request->location) ? implode(',', $request->location) : '',
			'pharmacist_signature' => $request->pharmacist_signature,
			'note_from_patient' => $request->note,
		);

		if ($request->dd) {$insert_data['dd'] = 1;} else { $insert_data['dd'] = 0;}
		if (!empty($request->session()->get('phrmacy'))) {
			$insert_data['website_id'] = $request->session()->get('phrmacy')->website_id;
			$insert_data['created_by'] = '-' . $request->session()->get('phrmacy')->id;
		}
		$validator = $request->validate($validate_array);
		EventsLog::create([
			'website_id' => $request->session()->get('phrmacy')->website_id,
			'action_by' => $request->session()->get('phrmacy')->id,
			'action' => 1,
			'action_detail' => 'Checking',
			'comment' => 'Create Checking',
			'patient_id' => $request->patient_id,
			'ip_address' => $request->ip(),
			'type' => $request->session()->get('phrmacy')->roll_type,
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		Checkings::create($insert_data);
		//    Patient Location
		$location_data['locations'] = $insert_data['location'];
		$location_data['patient_id'] = $insert_data['patient_id'];
		$location_data['action_by'] = $request->session()->get('phrmacy')->id;
		PatientLocation::insert_data($location_data);
		//    End
		if (isset($request->note) && $request->note != "") {
			$getPatient = Patient::find($request->patient_id);
			$getPatient->dob;
			$insert = array(
				'patient_id' => $request->patient_id,
				'dob' => $getPatient->dob,
				'notes_for_patients' => $request->note,
				'notes_as_text' => 0,
			);
			if (!empty($request->session()->get('admin'))) {
				$insert['website_id'] = $request->session()->get('phrmacy')->website_id;
				$insert['created_by'] = $request->session()->get('phrmacy')->id;
			}

			$insertedData = NotesForPatient::create($insert);
			EventsLog::create([
				'website_id' => $request->session()->get('phrmacy')->website_id,
				'action_by' => $request->session()->get('phrmacy')->id,
				'action' => 1,
				'action_detail' => 'Note For Patient',
				'comment' => 'Create Note For Patient',
				'patient_id' => $request->patient_id,
				'ip_address' => $request->ip(),
				'type' => $request->session()->get('phrmacy')->roll_type,
				'user_agent' => $request->userAgent(),
				'authenticated_by' => 'packnpeaks',
				'status' => 1,
			]);
		}

		// Checkings::create($insert_data);
		return redirect()->back()->with(["msg" => '<div class="alert alert-success"> <strong> Patient Checking </strong> Added Successfully.</div>']);
	}
	public function save_packed_fields(Request $request)
	{
		
       if($request->type == 1)
	   {
		$validate_array = array(
		
			// 'no_of_weeks' => 'required|numeric|min:1',
			
		);
		if ($request->id == Null)
		{
			$multi_id = $request->text;		
			$multi_id = rtrim($multi_id, ",");
			$str =explode("," , $multi_id);
			//dd($str);
			
			//dd($split_str);
			$str = ($request->patient_id);
			
			 foreach ($str as $ipatient_name ) {
				//dd($ipatient_name);
			$request->patient_id = $ipatient_name;
			$insert_data = array(
				'patient_id' => $request->patient_id,
				'no_of_weeks' => $request->no_of_weeks,
				'location' => " ",
				'pharmacist_signature' => " ",
				'note_from_patient' => $request->address,
				'dd'=>1
			);
			if (!empty($request->session()->get('phrmacy'))) {
				$insert_data['website_id'] = $request->session()->get('phrmacy')->website_id;
				$insert_data['created_by'] = '-' . $request->session()->get('phrmacy')->id;
			}
			
		//	dd($insert_data);
		//$from = $request->daterange;
		//$to = $request->daterange;
	//	$start = Carbon::parse($request->daterange);
      //  $end = Carbon::parse($request->daterange);
			Packed::create($insert_data);
			
			
		
		
	}
	return redirect()->back()->with(["msg" => '<div class="alert alert-success"> <strong> Packed </strong> Added Successfully.</div>']);
}
		else
		{
			$ob = Packed::find($request->id);
			$patientIDS = ($request->patient_id);
			foreach ($patientIDS as $ipatient_name ) {
			$request->patient_id = $ipatient_name;
			$update_data = array(
				'patient_id' => $request->patient_id,
				'no_of_weeks' => $request->no_of_weeks,
				'location' =>" ",
				'pharmacist_signature' => " ",
				'note_from_patient' => $request->address,
				'dd' => 1,
			);
			if ($request->dd) {$insert_data['dd'] = 1;} else { $insert_data['dd'] = 0;}
			$validator = $request->validate($validate_array);
			$ob->update($update_data);
			$data = array();
			$data['patients'] = Patient::get();
			$data['locations'] = Location::get();
			$data['packed'] = $ob;
			return redirect()->back()->with(["msg" => '<div class="alert alert-success"> <strong> Packed </strong> Updated Successfully.</div>']);
		
		}
	}}
	elseif($request->type == 2)
	{
		$validate_array = array(
		
			// 'no_of_weeks' => 'required|numeric|min:1',
			
		);
		if ($request->id == Null)
		{
			$multi_id = $request->text;
			$multi_id = rtrim($multi_id, ",");
			// dump($multi_id);
			// return;		
			$str =explode("," , $multi_id);
			//dd($str);	
			$str = ($request->patient_id);
			 foreach ($str as $ipatient_name ) {
			//	dd($ipatient_name);
			$request->patient_id = $ipatient_name;
			//dump($request->patient_id);
		   $insert_data = array(
			'patient_id' =>  $request->patient_id,
			'no_of_weeks' => $request->no_of_weeks,
			'location' => " ",
			'pharmacist_signature' => " ",
			'note_from_patient' => $request->address,
			'dd'=>1
		);
		if (!empty($request->session()->get('phrmacy'))) {
			$insert_data['website_id'] = $request->session()->get('phrmacy')->website_id;
			$insert_data['created_by'] = '-' . $request->session()->get('phrmacy')->id;
		}
	Checkings::create($insert_data);
		
}
return redirect()->back()->with(["msg" => '<div class="alert alert-success"> <strong>  Checking </strong> Added Successfully.</div>']);
			 
			}

	else
	{
		$ob = Checkings::find($request->id);
		$patientIDS = ($request->patient_id);
			foreach ($patientIDS as $ipatient_name ) {
			$request->patient_id = $ipatient_name;
		$update_data = array(
			'patient_id' => $request->patient_id,
			'no_of_weeks' => $request->no_of_weeks,
			'location' =>" ",
			'pharmacist_signature' => " ",
			'note_from_patient' => $request->address,
			'dd' => 1,
		);
		if ($request->dd) {$insert_data['dd'] = 1;} else { $insert_data['dd'] = 0;}
		$validator = $request->validate($validate_array);
		$ob->update($update_data);
		$data = array();
		$data['patients'] = Patient::get();
		$data['locations'] = Location::get();
		$data['Checkings'] = $ob;
		return redirect()->back()->with(["msg" => '<div class="alert alert-success"> <strong>  Checking </strong> Updated Successfully.</div>']);
	
	}
	}
}
elseif($request->type == 3)
{
	$validate_array = array(
		
		 'no_of_weeks' => 'required|numeric|min:1',
		
	);
	if ($request->id == Null)
		{
			//dd('add');
			$multi_id = $request->text;
			$multi_id = rtrim($multi_id, ",");
			// dump($multi_id);
			// return;		

			$str =explode("," , $multi_id);
			$str = ($request->patient_id);
			foreach ($str as $ipatient_name ) {
			$request->patient_id = $ipatient_name;
			$insert_data = array(
				'patient_id' => $request->patient_id,
				'no_of_weeks' => $request->no_of_weeks,
				'location' => " ",
				'pick_up_by'=>$request->who_pickup,
				'pharmacist_signature' => " ",
				'notes_from_patient' => $request->address,
				
			);
			if (!empty($request->session()->get('phrmacy'))) {
				$insert_data['website_id'] = $request->session()->get('phrmacy')->website_id;
				$insert_data['created_by'] = '-' . $request->session()->get('phrmacy')->id;
			}
			
		//	dd($insert_data);
		Pickups::create($insert_data);
			
	}
	return redirect()->back()->with(["msg" => '<div class="alert alert-success"> <strong> Pickups </strong> Added Successfully.</div>']);
	}
		else
		{
            $ob = Pickups::find($request->id);
			$patientIDS = ($request->patient_id);
			foreach ($patientIDS as $ipatient_name ) {
			$request->patient_id = $ipatient_name;
		$insert_data = array(
			'patient_id' => $request->patient_id,
			'no_of_weeks' => $request->no_of_weeks,
			'location' =>" ",
			'pharmacist_signature' => " ",
			'notes_from_patient' => $request->address,
			'dd' => 1,
		);
		if ($request->dd) {$insert_data['dd'] = 1;} else { $insert_data['dd'] = 0;}
		$validator = $request->validate($validate_array);
		$ob->update($insert_data);
		$data = array();
		$data['patients'] = Patient::get();
		$data['locations'] = Location::get();
		$data['Pickups'] = $ob;
		return redirect()->back()->with(["msg" => '<div class="alert alert-success"> <strong> Pickups </strong> Updated Successfully.</div>']);
	
		}

}}
	
	else{
		echo "error";
	}
		
		
		

	}	
	public function packed_Delete(Request $request, $tenantName, $id) {
		$delete = Packed::find($id);
		if (!$delete)
		 {
			return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Packed <strong>' . $id . '</strong> doesnot match in our records.</div>']);
		}

		$patient_name = $delete->patients->first_name . ' ' . $delete->patients->last_name;
		$delete->delete();
		
		return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Packed of this patient (<strong>' . $patient_name . '</strong>) deleted Successfully.</div>']);
	}
	public function packed_unhold_button(Request $request, $tenantName,$id)
	{
	// dd('a');
	 $user = Packed::find($id);
	   $user->hold='0';
	   $patient_name = $user->patients->first_name . ' ' . $user->patients->last_name;
	   $user->save();
	   
	  // return 'success';
	   return redirect()->back()->with(["msg" => '<div class="alert alert-success">Packed of this patient (<strong>' . $patient_name . '</strong>) unHold Successfully.</div>']);
	}
 
   public function packed_hold_button(Request $request, $tenantName,$id)
   {
	
	$user = Packed::find($id);
      $user->hold='1';
	//  $patient_name = $user->patients->first_name . ' ' . $user->patients->last_name;
      $user->save();
	  
	 // return 'success';
      return redirect()->back()->with(["msg" => '<div class="alert alert-warning">Packed of this patient (<strong>' . $patient_name . '</strong>) Hold Successfully.</div>']);
   }
   public function checking_hold_button(Request $request, $tenantName,$id)
   {
	  $user = Checkings::find($id);
      $user->hold='1';
	  $patient_name = $user->patients->first_name . ' ' . $user->patients->last_name;
      $user->save();
      return redirect()->back()->with(["msg" => '<div class="alert alert-warning">Checking of this patient (<strong>' . $patient_name . '</strong>) Hold Successfully.</div>']);
   }
   public function checking_unhold_button(Request $request, $tenantName,$id)
   {
	  $user = Checkings::find($id);
      $user->hold='0';
	  $patient_name = $user->patients->first_name . ' ' . $user->patients->last_name;
      $user->save();
      return redirect()->back()->with(["msg" => '<div class="alert alert-success">Checking of this patient (<strong>' . $patient_name . '</strong>) unHold Successfully.</div>']);
   }
   public function pickup_hold_button(Request $request, $tenantName,$id)
   {
	$user = Pickups::find($id);
      $user->hold='1';
	  $patient_name = $user->patients->first_name . ' ' . $user->patients->last_name;
      $user->save();
      return redirect()->back()->with(["msg" => '<div class="alert alert-warning">Pickup of this patient (<strong>' . $patient_name . '</strong>) Hold Successfully.</div>']);
   }
 public function pickup_unhold_button(Request $request, $tenantName,$id)
 {
	$user = Pickups::find($id);
	$user->hold='0';
	$patient_name = $user->patients->first_name . ' ' . $user->patients->last_name;
	$user->save();
	return redirect()->back()->with(["msg" => '<div class="alert alert-success">Pickup of this patient (<strong>' . $patient_name . '</strong>) unHold Successfully.</div>']);

 }
	public function pickup_board_Delete(Request $request, $tenantName, $id)
	{
		$delete = Pickups::find($id);
		if (!$delete)
		{
			return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Patient id <strong>' . $id . '</strong> doesnot match in our records.</div>']);
		}

		$patient_name = $delete->patients->first_name . ' ' . $delete->patients->last_name;
		$delete->delete();
		
		return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Pickup of this patient (<strong>' . $patient_name . '</strong>) deleted Successfully.</div>']);
	}
	public function checking_board_Delete(Request $request, $tenantName, $id) {
		$delete = Checkings::find($id);
		if (!$delete)
		{
			return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Patient id <strong>' . $id . '</strong> doesnot match in our records.</div>']);
		}

		$patient_name = $delete->patients->first_name . ' ' . $delete->patients->last_name;
		$delete->delete();
		
		return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Checking of this patient (<strong>' . $patient_name . '</strong>) deleted Successfully.</div>']);
	}
	public function time_limt_packed(Request $request)
	{ 
		
		//$data['patients'] = Patient::get();
		//$chk = new Checkings();
//return $chk;
		//new Checkings();
		//dd($chk);
		$data_two = Checkings::select()->latest('created_at')->first();
	//	dd($data_two);
	
		$result = $data_two->created_at->format('Y-m-d H:i:s');
		$result = Carbon::parse($result);

		$date = Carbon::now()->format('Y-m-d H:i:s');
		$date = Carbon::parse($date);
	
		
		$totalDuration = $date->diffInHours($result);
		//dd($totalDuration);
		// if ($totalDuration) {
		
		// 	$chk = $data_two;
			
		// 	return response()->json([$chk]);
		// }
		//dd($totalDuration);
		if($totalDuration < 12 )
		{	
			$chk = new Checkings();		
			$chk = $data_two;
			$chk->verifyid=1;
			return response()->json([$chk]);
			//dd($chk);
			
			//dd($chk);
		}
		else{
			$chk = new Checkings();	
			$chk->verifyid=-1;
			return response()->json([$chk]);
		}
		//dd($chk);
		return response()->json([$chk]);
		//dd($chk);
	
		//dd($chk);
		//$dublicate = "Dublication Entry Found";
		//return response()->json([$chk]);
		//return $totalDuration;
	}
	public function time_limt_checking(Request $request)
	{ 
	//	$chk = new Pickups();
		$data_two = Pickups::select()->latest('created_at')->first();
		$result = $data_two->created_at->format('Y-m-d H:i:s');
		$result = Carbon::parse($result);
		 //dd($result);
		//return $data_two->format('Y-m-d H:i');
		$date = Carbon::now()->format('Y-m-d H:i:s');
		$date = Carbon::parse($date);
		//dd($date);
		
		$totalDuration = $date->diffInHours($result);
		if($totalDuration < 1 )
		{	
			$chk = new Pickups();		
			$chk = $data_two;
			$chk->verifyid=1;
			return response()->json([$chk]);
			//dd($chk);
			
			//dd($chk);
		}
		
		else{
			$chk = new Pickups();	
			$chk->verifyid=-1;
			return response()->json([$chk]);
		}
		//dd($chk);
		return response()->json([$chk]);
		//Alert::error('Error Title', 'Error Message');
	//	dd($totalDuration);
	// if($totalDuration < 12)
	// {			
	// 	$chk = $data_two;
	// 	//dd($chk);
	// }
//	$dublicate = "Dublication Entry Found";
	//return response()->json([$totalDuration]);
		//return $totalDuration;//->with('error', 'The error message here!');//->with("result" , '<div class="alert alert-danger">Checking of this patient deleted Successfully.</div>');
	}
	/* save Checking here  */
	public function save_pack(Request $request) {
		$validate_array = array(
			'patient_id' => 'required|numeric|min:1',
			'no_of_weeks' => 'required|numeric|min:1',
			'pharmacist_signature' => 'required|string|max:99000',
		);
		//print_r($request->location); die;
		$insert_data = array(
			'patient_id' => $request->patient_id,
			'no_of_weeks' => $request->no_of_weeks,
			'location' => isset($request->location) ? implode(',', $request->location) : '',
			'pharmacist_signature' => $request->pharmacist_signature,
			'note_from_patient' => $request->note,
		);

		if ($request->dd) {$insert_data['dd'] = 1;} else { $insert_data['dd'] = 0;}
		if (!empty($request->session()->get('phrmacy'))) {
			$insert_data['website_id'] = $request->session()->get('phrmacy')->website_id;
			$insert_data['created_by'] = '-' . $request->session()->get('phrmacy')->id;
		}
		$validator = $request->validate($validate_array);
		EventsLog::create([
			'website_id' => $request->session()->get('phrmacy')->website_id,
			'action_by' => $request->session()->get('phrmacy')->id,
			'action' => 1,
			'action_detail' => 'packed',
			'comment' => 'Create pack',
			'patient_id' => $request->patient_id,
			'ip_address' => $request->ip(),
			'type' => $request->session()->get('phrmacy')->roll_type,
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		Packed::create($insert_data);
		//    Patient Location
		$location_data['locations'] = $insert_data['location'];
		$location_data['patient_id'] = $insert_data['patient_id'];
		$location_data['action_by'] = $request->session()->get('phrmacy')->id;
		PatientLocation::insert_data($location_data);
		//    End
		if (isset($request->note) && $request->note != "") {
			$getPatient = Patient::find($request->patient_id);
			$getPatient->dob;
			$insert = array(
				'patient_id' => $request->patient_id,
				'dob' => $getPatient->dob,
				'notes_for_patients' => $request->note,
				'notes_as_text' => 0,
			);
			if (!empty($request->session()->get('admin'))) {
				$insert['website_id'] = $request->session()->get('phrmacy')->website_id;
				$insert['created_by'] = $request->session()->get('phrmacy')->id;
			}

			$insertedData = NotesForPatient::create($insert);
			EventsLog::create([
				'website_id' => $request->session()->get('phrmacy')->website_id,
				'action_by' => $request->session()->get('phrmacy')->id,
				'action' => 1,
				'action_detail' => 'Note For Patient',
				'comment' => 'Create Note For Patient',
				'patient_id' => $request->patient_id,
				'ip_address' => $request->ip(),
				'type' => $request->session()->get('phrmacy')->roll_type,
				'user_agent' => $request->userAgent(),
				'authenticated_by' => 'packnpeaks',
				'status' => 1,
			]);
		}

		// Checkings::create($insert_data);
		return redirect()->back()->with(["msg" => '<div class="alert alert-success"> <strong> Patient Pack </strong> Added Successfully.</div>']);
	}
	/* checking Reports  */
	public function checkings_report(Request $request) {
		
		if ($request->form10 == '1') {
			$data = array();
			$data['checkings'] = Checkings::where('is_archive','=',0)->get();
			//return $data['checkings'];
			return view($this->views . '.checking_report')->with($data);
		} else {
			return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
		}
	}

		/* checking Reports  */
		public function packed_report(Request $request) {
		
			if ($request->form10 == '1') {
				$data = array();
				$data['checkings'] = Packed::where('is_archive','=',0)->get();
				//return $data['checkings'];
				return view($this->views . '.packed_report')->with($data);
			} else {
				return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
			}
		}

	public function archive_checkings_report(Request $request) {
		
		if ($request->form10 == '1') {
			$data = array();
			$data['checkings'] = Checkings::where('is_archive','=',1)->get();
			//return $data['checkings'];
			return view($this->views . '.archive_checkings_report')->with($data);
		} else {
			return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
		}
	}

	public function archive_packed_report(Request $request) {
		
		if ($request->form10 == '1') {
			$data = array();
			$data['checkings'] = Packed::where('is_archive','=',1)->get();
			//return $data['checkings'];
			return view($this->views . '.archive_packed_report')->with($data);
		} else {
			return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
		}
	}


/* Soft Delete Return  */
public function  softarchive(Request $request)
{

	$getdata=Checkings::where('id','=',$request->id)->first();
	$getdata->is_archive = 1;
	$getdata->save();
	
	$patient_name = Patient::where('id','=',$getdata->patient_id)->first();
	$name = $patient_name->first_name .' '. $patient_name->last_name;
 
	return redirect()->back()->with(["msg" => '<div class="alert alert-success">Patient checking (<strong>' . $name . '</strong>) archived Successfully.</div>']);

 }


 /* Soft Delete Return  */
public function  softpacakarchive(Request $request)
{

	$getdata=Packed::where('id','=',$request->id)->first();
	$getdata->is_archive = 1;
	$getdata->save();
	
	$patient_name = Patient::where('id','=',$getdata->patient_id)->first();
	$name = $patient_name->first_name .' '. $patient_name->last_name;
 
	return redirect()->back()->with(["msg" => '<div class="alert alert-success">Patient Packed (<strong>' . $name . '</strong>) archived Successfully.</div>']);

 }


public function  softunarchive(Request $request)
{

   $getdata=Checkings::where('id','=',$request->id)->first();
   $getdata->is_archive = 0;
   $getdata->save();
   
   $patient_name = Patient::where('id','=',$getdata->patient_id)->first();
   $name = $patient_name->first_name .' '. $patient_name->last_name;

	 return redirect()->back()->with(["msg" => '<div class="alert alert-success">Patient (<strong>' . $name . '</strong>) unarchived Successfully.</div>']);

}



public function  softpackunarchive(Request $request)
{

   $getdata=Packed::where('id','=',$request->id)->first();
   $getdata->is_archive = 0;
   $getdata->save();
   
   $patient_name = Patient::where('id','=',$getdata->patient_id)->first();
   $name = $patient_name->first_name .' '. $patient_name->last_name;

	 return redirect()->back()->with(["msg" => '<div class="alert alert-success">Patient Packed (<strong>' . $name . '</strong>) unarchived Successfully.</div>']);

}
public function gettopack()
{
dd('raza');
        
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


	public function checkingsDelete(Request $request, $tenantName, $id) {
		$delete = Checkings::find($id);
		if (!$delete) {
			return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Patient id <strong>' . $id . '</strong> doesnot match in our records.</div>']);
		}

		$patient_name = $delete->patients->first_name . ' ' . $delete->patients->last_name;
		$delete->delete();
		EventsLog::create([
			'website_id' => $request->session()->get('phrmacy')->website_id,
			'action_by' => $request->session()->get('phrmacy')->id,
			'action' => 3,
			'action_detail' => 'Checking',
			'comment' => 'Delete Checking',
			'patient_id' => $delete->patient_id,
			'ip_address' => $request->ip(),
			'type' => $request->session()->get('phrmacy')->roll_type,
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Checkings of this patient (<strong>' . $patient_name . '</strong>) deleted Successfully.</div>']);
	}


	
	public function packedDelete(Request $request, $tenantName, $id) {
		$delete = Packed::find($id);
		if (!$delete) {
			return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Patient id <strong>' . $id . '</strong> doesnot match in our records.</div>']);
		}

		$patient_name = $delete->patients->first_name . ' ' . $delete->patients->last_name;
		$delete->delete();
		EventsLog::create([
			'website_id' => $request->session()->get('phrmacy')->website_id,
			'action_by' => $request->session()->get('phrmacy')->id,
			'action' => 3,
			'action_detail' => 'Packed',
			'comment' => 'Delete Packed',
			'patient_id' => $delete->patient_id,
			'ip_address' => $request->ip(),
			'type' => $request->session()->get('phrmacy')->roll_type,
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Packed of this patient (<strong>' . $patient_name . '</strong>) deleted Successfully.</div>']);
	}

	public function packEdit(Request $request, $tenantName, $id) {

		if ($request->form10 == '1') {
			$ob = Packed::find($id);
			if (!$ob) {
				return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Packed id <strong>' . $id . '</strong> doesnot match in our records.</div>']);
			}

			if ($request->isMethod('post')) {

				$validate_array = array(
					'patient_id' => 'required|numeric|min:1',
					'no_of_weeks' => 'required|numeric|min:1',
				);
				$insert_data = array(
					'patient_id' => $request->patient_id,
					'no_of_weeks' => $request->no_of_weeks,
					'location' => $request->location ? implode(',', $request->location) : '',
					'pharmacist_signature' => $request->pharmacist_signature,
					'note_from_patient' => $request->note,
					'dd' => $request->dd,
				);
				if ($request->dd) {$insert_data['dd'] = 1;} else { $insert_data['dd'] = 0;}
				$validator = $request->validate($validate_array);
				$ob->update($insert_data);
				EventsLog::create([
					'website_id' => $request->session()->get('phrmacy')->website_id,
					'action_by' => $request->session()->get('phrmacy')->id,
					'action' => 2,
					'action_detail' => 'Packed',
					'comment' => 'Update Pack',
					'patient_id' => $request->patient_id,
					'ip_address' => $request->ip(),
					'type' => $request->session()->get('phrmacy')->roll_type,
					'user_agent' => $request->userAgent(),
					'authenticated_by' => 'packnpeaks',
					'status' => 1,
				]);
				return redirect('packed_report')->with(["msg" => '<div class="alert alert-success"> <strong> Patient Packed </strong> Updated Successfully.</div>']);
			}

			$data = array();
			$data['patients'] = Patient::get();
			$data['locations'] = Location::get();
			$data['checkings'] = $ob;
			return view($this->views . '.packEdit', $data);
		} else {
			return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
		}

	}


	public function checkingsEdit(Request $request, $tenantName, $id) {

		if ($request->form10 == '1') {
			$ob = Checkings::find($id);
			if (!$ob) {
				return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Checkings id <strong>' . $id . '</strong> doesnot match in our records.</div>']);
			}

			if ($request->isMethod('post')) {

				$validate_array = array(
					'patient_id' => 'required|numeric|min:1',
					'no_of_weeks' => 'required|numeric|min:1',
				);
				$insert_data = array(
					'patient_id' => $request->patient_id,
					'no_of_weeks' => $request->no_of_weeks,
					'location' => $request->location ? implode(',', $request->location) : '',
					'pharmacist_signature' => $request->pharmacist_signature,
					'note_from_patient' => $request->note,
					'dd' => $request->dd,
				);
				if ($request->dd) {$insert_data['dd'] = 1;} else { $insert_data['dd'] = 0;}
				$validator = $request->validate($validate_array);
				$ob->update($insert_data);
				EventsLog::create([
					'website_id' => $request->session()->get('phrmacy')->website_id,
					'action_by' => $request->session()->get('phrmacy')->id,
					'action' => 2,
					'action_detail' => 'Checking',
					'comment' => 'Update Checking',
					'patient_id' => $request->patient_id,
					'ip_address' => $request->ip(),
					'type' => $request->session()->get('phrmacy')->roll_type,
					'user_agent' => $request->userAgent(),
					'authenticated_by' => 'packnpeaks',
					'status' => 1,
				]);
				return redirect('checkings_report')->with(["msg" => '<div class="alert alert-success"> <strong> Patient Checking </strong> Updated Successfully.</div>']);
			}

			$data = array();
			$data['patients'] = Patient::get();
			$data['locations'] = Location::get();
			$data['checkings'] = $ob;
			return view($this->views . '.checkingsEdit', $data);
		} else {
			return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
		}

	}

	public function checkings_board()
	{
		
			if ($request->form9 == '1') {
				$datas = array();
				$datas['locations'] = Location::get();
	
				$datas['patients'] = Patient::get();
				$datas['checkings'] = Checkings::get();
				//return $data['patients'][0]->latestPickups;
				return view('tenant.packboard')->with($datas);
			} else {
				return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
			}
		
	}
	public function save_checkings_board(Request $request)
	{
	// 	$validate_array = array(
		
	// 		'no_of_weeks' => 'required|numeric|min:1',
			
	// 	);
	// 	$insert_data = array(
	// 		'patient_id' =>  $request->patient_id,
	// 		'no_of_weeks' => $request->no_of_weeks,
	// 		'location' => " ",
	// 		'pharmacist_signature' => " ",
	// 		'note_from_patient' => $request->address,
	// 		'dd'=>1
	// 	);
	// 	if (!empty($request->session()->get('phrmacy'))) {
	// 		$insert_data['website_id'] = $request->session()->get('phrmacy')->website_id;
	// 		$insert_data['created_by'] = '-' . $request->session()->get('phrmacy')->id;
	// 	}
		
	// //	dd($insert_data);
	// Checkings::create($insert_data);
		
	// 	return redirect()->back()->with(["msg" => '<div class="alert alert-success"> <strong> Patient Checking </strong> Added Successfully.</div>']);
	
	}
	public function export_excel_phar()
	{
		$newarray = Packed::get();
		$proData = "";
        if (count($newarray) >0) {
         $proData .= '<table  border height="50" width="60">
         <tr  >
     
         <th>Patient_id</th>
         <th>No of weeks</th>
		 <th>Notes from patient</th>
         <th>pharmacist signature</th>
        
         
         
         
        
    
         </tr>';
     
         foreach ($newarray as $img)
         {      
          $proData .= '
             <tr >
           
             <td>'.$img->patient_id.'</td>
             <td>'.$img->no_of_weeks.'</td>
             <td>'.$img->note_from_patient.'</td>
           
           
             <td>
            
             <img src="'.$img->pharmacist_signature.'"  height="30" width="20" >
            
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
	public function export_pdf_phar()
	{
	
		$data['patients'] = Patient::get();
		$newarray = Packed::get();
	//	dd($newarray);
    $pdf =  PDF::loadView('checkingpdf',compact('newarray','data'));
	//return view('checkingpdf',compact('newarray'));
        return  $pdf->download(time().'.pdf');

	}
	public function export_excel_checking_phar()
	{
		$newarray = checkings::get();
		$proData = "";
        if (count($newarray) >0) {
         $proData .= '<table  border height="50" width="60">
         <tr  >
     
         <th>Patient_id</th>
         <th>No of weeks</th>
		 <th>Notes from patient</th>
         <th>pharmacist signature</th>
        
         
         
         
        
    
         </tr>';
     
         foreach ($newarray as $img)
         {      
          $proData .= '
             <tr >
           
             <td>'.$img->patient_id.'</td>
             <td>'.$img->no_of_weeks.'</td>
             <td>'.$img->note_from_patient.'</td>
           
           
             <td>
            
             <img src="'.$img->pharmacist_signature.'"  height="30" width="20" >
            
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
	public function export_pdf_checking_phar()
	{
//	dd('rsza');
        $data['patients'] = Patient::get();
		$newarray = checkings::get();
	//	dd($newarray);
    $pdf =  PDF::loadView('checkingpdf',compact('newarray','data'));
	//return view('checkingpdf',compact('newarray'));
        return  $pdf->download(time().'.pdf');

	}
 public function full_calender()
 {
	//dd('ok');
	return view('tenant.full_calender');
 }
 public function calender_events(Request $req)
 {
    //  $calender = new calender;
	//  $calender->event_date=$req->getdate;
	//  $calender->event_name=$req->name;
	//  $calender->save();
	if($req->id == NULL){
	 $insert_data = array(
		 'event_date' =>$req->event_date,
		 'event_name' => $req->event_name
		// 'event_date' => $req->input('getdate'), //$request->title
		// 'event_name' => $req->input('name')
		
	);
	
	
//	dd($insert_data);
calender::create($insert_data);
return response()->json(
	[
	  'success' => true,
	  'message' => 'Data inserted successfully'
	]);
	//dd($req->getdate);
	// dd('ok');
}
else
{
	
		$ob = calender::find($req->id);
// 		$input     = "2020-01-07T11:55:34:438 GMT+0600"                   // "2020-01-07T11:55:34:438 GMT+0600"
// $timestamp = substr($input,0,19);                                 // "2020-01-07T11:55:34"
// $mysql     = date_format(date_create($timestamp),'Y-m-d H:i:s');
// 		$input =$req->event_date ;
// $date = DateTime::createFromFormat('Y-m-d\Th:i:s:u \G\M\TO', $input);
//echo $date->format('Y-m-d h:i:s');
	//	$result = $req->event_date->format('Y-m-d H:i:s');
	//	$result_one = Carbon::parse($result);
		$update_data = array(			
		    	'event_date' => $req->event_date,
				'event_name' => $req->event_name

		);



		$ob->update($update_data);

		return response()->json(
		[
		'success' => true,
		'message' => 'Data inserted successfully'
		]);

	
	
}
 }
 public function calender_events_fetch()
	{  //$user = User::find($id);
		$calender_events = calender::all();
		//dd($calender_events);
		return response()->json([$calender_events]);
	}
	public function calender_events_edit()
	{  //$user = User::find($id);
		$calender = calender::all();
		//dd($calender_events);
		return response()->json($calender);
	}
	public function calender_event_delete(Request $req)	
	{
		
		
		$ob = calender::find($req->id);
	
	//	dd($insert_data);
	$ob->delete();
	// calender::update($update_data);
	return response()->json(
	   [
		 'success' => true,
		 'message' => 'Data inserted successfully'
	   ]);
		
		//$user = User::find($id);
		// $calender = calender::all();
		// //dd($calender_events);
		// return response()->json($calender);
		// $user = User::find($id);
        // $user->delete();
        // return redirect('/show_member')->with('status',"Data deleted successfully");
	}

	
}
