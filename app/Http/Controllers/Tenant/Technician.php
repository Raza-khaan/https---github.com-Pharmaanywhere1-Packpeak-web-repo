<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\AccessLevel;
use App\Models\Tenant\Company;
use App\Models\Tenant\EventsLog;
use App\Rules\IsValidPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use App\User;
use DB;

class Technician extends Controller {
	public function technician(Request $request) {

		
		
		$data['pin'] = "";
		$data['messageNote'] = "";
		
		if (!empty($request->session()->get('phrmacy'))) {
			$getPharmacyAdmin = Company::find($request->session()->get('phrmacy')->id);
			$data['pin'] = $getPharmacyAdmin->pin;

			$getAccess = AccessLevel::find(1);
			$getAllAdmin = Company::where('roll_type', 'admin')->where('status',1)->get();
			$getAllTechnician = Company::where('roll_type', 'technician')->where('status',1)->get();
			// if ($getAccess->no_of_admins <= count($getAllAdmin)) {
				$data['messageNote']  = ' Your pharmacy can create  ' . ((int)($getAccess->no_of_admins)-count($getAllAdmin)) . ' Admin & '.((int)($getAccess->no_of_technicians)-count($getAllTechnician)).' Technician';
			// }
			 

				 
		}
		return view('tenant.add_technician')->with($data);
	}

	public function verifyuser(Request $request)
	{
		DB::disconnect('tenant');
		$websiteid = $request->session()->get('phrmacy')->website_id;
		
		
		$all_pharmacy=User::all();
		$isalreadyexists = 0 ;
		
        foreach($all_pharmacy  as $row)
		{

			$this->get_connection($row->website_id);
            //   $allTechnicians = Company::where('email','=',$request->Email)->get();

			  $all_result = Company::where('email','=',$request->Email)->get();

                if($all_result)
			  {
				$isalreadyexists =1;
				return $isalreadyexists;
				break;
				DB::disconnect('tenant');	
			  }
			  else
			  {
				$isalreadyexists =0;
				DB::disconnect('tenant');
			  }
        }
       


		
	}
	public function add_technician(Request $request) {


		$validator = $request->validate(array(
			'first_name' => 'required|string|max:255',
			'last_name' => 'required|string|max:255',
			'email' => 'required|string|email|min:6|max:255|unique:tenant.companies',
			'password' => ['required', 'string', new isValidPassword()],
			'confirm_password' => 'required|string|same:password|min:4',
			'term' => 'required',
			'phone' => 'required|string|max:12|min:10',
			'username' => 'required|string|min:6|max:20|unique:tenant.companies',
			'pin' => 'required|numeric|min:4',
			'address' => 'required|string|max:255',
			'role' => 'required|string|max:255',
		), ["email.unique" => 'this email has been blocked. you can contact to administrator.']);


		$insert_data = array(
			'name' => $request->first_name . ' ' . $request->last_name,
			'initials_name' => strtoupper(substr($request->first_name, 0, 1)) . '.' . strtoupper(substr($request->last_name, 0, 1)) . '.',
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
			'phone' => $request->phone,
			'username' => $request->username,
			'pin' => $request->pin,
			'registration_no' => 'PHA00' . date('HisYdm'),
			'address' => $request->address,
			'roll_type' => $request->role,
		);
		if (!empty($request->session()->get('phrmacy'))) {
			$insert_data['subscription'] = $request->session()->get('phrmacy')->subscription;
			$insert_data['website_id'] = $request->session()->get('phrmacy')->website_id;
			$insert_data['created_by'] = '-' . $request->session()->get('phrmacy')->id;

		}

		$getAccess = AccessLevel::find(1);

		$getAllAdmin = Company::where('roll_type', 'admin')->where('status',1)->get();
		$getAllTechnician = Company::where('roll_type', 'technician')->where('status',1)->get();
		if ($request->role == 'admin' && $getAccess->no_of_admins <= count($getAllAdmin)) {
			return redirect()->back()->withInput()->with('msg', '<div class="alert alert-danger""> your pharmacy can create only ' . $getAccess->no_of_admins . ' Admin </div>');
		}
		if ($request->role == 'technician' && $getAccess->no_of_technicians <= count($getAllTechnician)) {
			return redirect()->back()->withInput()->with('msg', '<div class="alert alert-danger""> your pharmacy can create only ' . $getAccess->no_of_technicians . ' Technician </div>');
		}

		$result = Company::create($insert_data);
		EventsLog::create([
			'website_id' => $request->session()->get('phrmacy')->website_id,
			'action_by' => $request->session()->get('phrmacy')->id,
			'action' => 1,
			'action_detail' => ucfirst($request->role),
			'comment' => 'Create ' . ucfirst($request->role),
			'patient_id' => null,
			'ip_address' => $request->ip(),
			'type' => $request->session()->get('phrmacy')->roll_type,
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		if ($request->role == 'admin') {
			return redirect('all_admin')->with('msg', '<div class="alert alert-success""> ' . ucfirst($request->role) . ' <strong> ' . $request->first_name . ' ' . $request->last_name . '</strong>.  Added Successfully !</div>');
		} else {
			return redirect('all_technician')->with('msg', '<div class="alert alert-success""> ' . ucfirst($request->role) . ' <strong> ' . $request->first_name . ' ' . $request->last_name . '</strong>.  Added Successfully !</div>');
		}

	}

	public function all_technician() 
	{
		$data['all_technicians'] = Company::where('roll_type','=','technician')->get();
		return view('tenant.all_technician')->with($data);
	}

	public function all_admin() {
		$data['all_admins'] = Company::select_all_admin();
		return view('tenant.all_admins')->with($data);
	}

/*Delete techniaician */
	public function technicianDelete(Request $request, $tenantName, $id) {
		$delete = Company::find($id);
		if (!$delete) {
			return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Technician id <strong>' . $id . '</strong> doesnot match in our records.</div>']);
		}
		$delete->delete();
		EventsLog::create([
			'website_id' => $request->session()->get('phrmacy')->website_id,
			'action_by' => $request->session()->get('phrmacy')->id,
			'action' => 3,
			'action_detail' => 'Technician',
			'comment' => 'Suspended Technician',
			'patient_id' => null,
			'ip_address' => $request->ip(),
			'type' => $request->session()->get('phrmacy')->roll_type,
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		return redirect()->back()->with(["msg" => '<div class="alert alert-info">Technician Suspended Successfully.</div>']);
	}

/*Get all suspended techniaician */
	public function all_suspended_technician() {
		$data['all_technicians'] = Company::onlyTrashed()->get();
		return view('tenant.all_suspended_technician')->with($data);
	}

	/*Restore techniaician */
	public function technicianRestore(Request $request, $tenantName, $id) {
		
		$getAccess = AccessLevel::find(1);
		$getAllAdmin = Company::where('roll_type', 'admin')->where('status',1)->get();
		$getAllTechnician = Company::where('roll_type', 'technician')->where('status',1)->get();



		$technician = Company::onlyTrashed()->where('id', $id)->get();

		$Accessleveladminlimit =  $getAccess->no_of_admins;
		$Accessleveluserlimit =  $getAccess->no_of_technicians;

		$pharmacytotaladmins =  count($getAllAdmin);
		$pharmacytotalusers =  count($getAllTechnician);

		$rolltype =  $technician[0]->roll_type;
		
		
		if($rolltype == 'admin')
		{
			if ($Accessleveladminlimit == $pharmacytotaladmins)
			{

				return redirect()->back()->withInput()->with('msg', '<div class="alert alert-danger""> your pharmacy can restore only ' . $Accessleveladminlimit . ' Admin </div>');
			} 
		}
		else if($rolltype == 'technician')
		
		{
			if ($Accessleveluserlimit == $pharmacytotalusers)
			{

				return redirect()->back()->withInput()->with('msg', '<div class="alert alert-danger""> can not restore user! pharmacy already has the allowed number of users active in all users record page </div>');
			}
		}

		if (!$technician) {
			return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Technician id <strong>' . $id . '</strong> doesnot match in our records.</div>']);
		}
		Company::onlyTrashed()->where('id', $id)->restore();
		EventsLog::create([
			'website_id' => $request->session()->get('phrmacy')->website_id,
			'action_by' => $request->session()->get('phrmacy')->id,
			'action' => 3,
			'action_detail' => 'Technician',
			'comment' => 'Technician Restored',
			'patient_id' => null,
			'ip_address' => $request->ip(),
			'type' => $request->session()->get('phrmacy')->roll_type,
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		return redirect()->back()->with(["msg" => '<div class="alert alert-success">Technician Restored Successfully.</div>']);
	}

	/*Restore techniaician */
	public function technicianForceDelete(Request $request, $tenantName, $id) {
		$technician = Company::find($id);
		if (!$technician) {
			return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Technician id <strong>' . $id . '</strong> doesnot match in our records.</div>']);
		}
		$technician->forceDelete();
		EventsLog::create([
			'website_id' => $request->session()->get('phrmacy')->website_id,
			'action_by' => $request->session()->get('phrmacy')->id,
			'action' => 3,
			'action_detail' => 'Technician',
			'comment' => 'Technician deleted form system',
			'patient_id' => null,
			'ip_address' => $request->ip(),
			'type' => $request->session()->get('phrmacy')->roll_type,
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Technician Deleted Successfully.</div>']);
	}

	/* edit technician  */
	public function technicianEdit(Request $request, $tenantName, $id) {


		
		if($request->password != null)
		{
			
			
			$validator = $request->validate(array(
				'password' => ['required', 'string', new isValidPassword()]
					));
		
				}


		//return $request->all();
		$ob = Company::find($id);



		if (!$ob) {
			return redirect()->back()->with(["msg" => '<div class="alert alert-danger">Patient Notes id <strong>' . $id . '</strong> doesnot match in our records.</div>']);
		}

		if ($request->isMethod('post')) {
			//return $request->all();
			$validate_array = array(
				// 'first_name'        => 'required|string|max:255',
				// 'last_name'         => 'required|string|max:255',
				// 'term'              => 'required',
				// 'phone'             => 'required|string|max:10|min:10',
				// 'address'           => 'required|string|max:255',
				// 'role'         => 'required|string|max:255',

				'first_name' => ['required', 'string', 'max:255'],
				'last_name' => ['required', 'string', 'max:255'],
				'term' => ['required'],
				'phone' => ['required', 'string', 'max:12'],
				'address' => ['required', 'string', 'max:255'],

			);

			$update_data = array(
				'first_name' => $request->first_name,
				'last_name' => $request->last_name,
				'phone' => $request->phone,
				'address' => $request->address,
				'roll_type' => $request->role,
			);
			if ($request->password != "") {
				$validate_array['password'] = ['required', 'string', new isValidPassword()];
				$validate_array['confirm_password'] = 'required|string|same:password|min:4';
				$update_data['password'] = Hash::make($request->password);
			}
			if ($request->pin != "") {
				$update_data['pin'] = $request->pin;
			}

			$validator = $request->validate($validate_array);

			$ob->update($update_data);
			EventsLog::create([
				'website_id' => $request->session()->get('phrmacy')->website_id,
				'action_by' => $request->session()->get('phrmacy')->id,
				'action' => 2,
				'action_detail' => ucfirst($request->role),
				'comment' => 'Update ' . ucfirst($request->role),
				'patient_id' => null,
				'ip_address' => $request->ip(),
				'type' => $request->session()->get('phrmacy')->roll_type,
				'user_agent' => $request->userAgent(),
				'authenticated_by' => 'packnpeaks',
				'status' => 1,
			]);
			if ($request->role == 'admin') {
				return redirect('all_admin')->with(["msg" => '<div class="alert alert-success"><strong>' . ucfirst($request->role) . '</strong> Updated Successfully.</div>']);
			} else {
				return redirect('all_technician')->with(["msg" => '<div class="alert alert-success"><strong>' . ucfirst($request->role) . '</strong> Updated Successfully.</div>']);
			}

		}

		$data = array();
		$data['technician'] = $ob;
		// return $ob;
		return view('tenant.technicianEdit', $data);

	}

	/* update Technician Status Active or In-Active*/
	public function technicianStatus(Request $request) {
		$ob = Company::find($request->id);
		$update_data = array();
		if ($ob->status) {
			$update_data['status'] = '0';
		} else {
			$update_data['status'] = '1';
		}
		$ob->update($update_data);
		return redirect('all_technician')->with(["msg" => '<div class="alert alert-success"><strong> Records </strong> Updated Successfully.</div>']);
	}
}
