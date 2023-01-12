<?php

namespace App\Http\Controllers\Admin;
use App\Helpers\Helper;
use App\Hostnames;
use App\Http\Controllers\Controller;
use App\Models\Admin\EventsLog as EventsLogAdmin;
use App\Models\Admin\Subscription;
use App\Models\Phermacist;
use App\Models\Tenant\AccessLevel;
use App\Models\Tenant\Company;
use App\Models\Tenant\EventsLog;
use App\Models\Tenant\Facility;
use App\Models\Tenant\Location;
use App\Models\Tenant\Patient;
use App\Models\Tenant\Store;
use App\Rules\IsValidPassword;
use App\User;
use App\Websites;
use Carbon\Carbon;
use DB; 
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;


class Pharmacist extends Controller {

	protected function validator(array $data)
	{
		return Validator::make($data, [
			'first_name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			// 'password' => ['required', 'string', 'confirmed', 'min:6'],
			'password' => ['required', 'string', 'confirmed', new isValidPassword()],
			'term' => ['required'],
			'company_name' => ['required', 'string', 'max:255', 'unique:users'],
			'host_name' => ['required', 'string', 'max:190', 'unique:users'],
			'username' => ['required', 'string', 'min:6', 'max:20', 'unique:users'],
			'pin' => ['required', 'string', 'min:4', 'max:4'],

			// 'phone' => ['required', 'string', 'max:9', 'min:9'],
			
			'address' => ['required', 'string', 'max:255'],
			'subscription' => ['required', 'string', 'max:255'],
		]);
	}

	protected function update_pharmacy_validator(array $data)
	{
		return Validator::make($data, [
			'password' => ['required', 'string', 'confirmed', new isValidPassword()],
			
		]);
	}


	protected function update_technition_validator(array $data)
	{
		return Validator::make($data, [
			'password' => ['required', 'string', 'confirmed', new isValidPassword()],
			
		]);
	}
	
	protected function create(array $data) { 
		$host_name = $data['host_name'];
		$website = $this->tenantSetUp($host_name, $data);
		$subscrip = Subscription::getdatabycolumn_name('id', $data['subscription']);
		$insert_data = array(
			'name' => $data['first_name'] . ' ' . $data['last_name'],
			'initials_name' => strtoupper(substr($data['first_name'], 0, 1)) . '.' . strtoupper(substr($data['last_name'], 0, 1)) . '.',
			'first_name' => $data['first_name'],
			'last_name' => $data['last_name'],
			'email' => $data['email'],
			'password' => Hash::make($data['password']),
			'company_name' => $data['company_name'],
			'phone' => $data['phone'],
			'registration_no' => 'PHA00' . date('HisYdm'),
			'address' => Helper::mysql_escape($data['address']),
			'host_name' => $host_name,
			'website_id' => $website->id,
			'subscription' => $data['subscription'],
			'expired_date' => Carbon::now()->addDays($subscrip[0]->plan_validity),
		);

		User::create($insert_data);
		$insert_data['roll_type'] = 'admin';
		$insert_data['username'] = $data['email'];
		$company = Company::create($insert_data);

		return $host_name;

	}

	/*public function register(Request $request) {
		    $this->validator($request->all())->validate();
		    $user = $this->create($request->all());
		    return redirect('admin/all_pharmacies')->with('msg','<div class="alert alert-success""> New Pharmacy Created <strong> Successfully</strong>.</div>');

	*/
	
	public function register(Request $request) {

		$subscrip = Subscription::getdatabycolumn_name('id', $request->subscription);

		$request->uuid = $request->host_name;

		$this->validator($request->all())->validate();

		/*Create Tenant Setup*/
		$website = Websites::where('uuid', $request->host_name)->get();
		if (count($website)) {
			return redirect('admin/add_pharmacy')->with('msg', '<div class="alert alert-danger""> this host name is  already<strong>   exist .</strong>.</div>');
		}
		$host_name = $request->host_name;
		// $fqdn = "www.{$host_name}." . env('PROJECT_HOST', 'packpeak.com.au');

		//$fqdn = "{$host_name}." . env('PROJECT_HOST', 'packpeak.com.au');
		$fqdn = "{$host_name}." . env('PROJECT_HOST', 'localhost');
		
		$hostname = new Hostname;
		$hostname->fqdn = $fqdn;
		// $uuid = Str::limit(Str::slug($host_name, '_'), 15, '');
		$uuid = $host_name;
		$website = new Website;
		$website->uuid = $uuid;

		$website->hostnames()->save($hostname);
		$host = app(HostnameRepository::class)->create($hostname);

		
		// this is line that create database
		app(WebsiteRepository::class)->create($website);
	// dump("1");
		app(HostnameRepository::class)->attach($host, $website);
		$subscrip = Subscription::getdatabycolumn_name('id', $request->subscription);

		$permacist_result = Phermacist::create([
			'username' => $request->username,
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'email' => $request->email,
			'company_name' => $request->company_name,
			'host_name' => $request->host_name,
			'pin' => $request->pin,
			'password' => Hash::make($request->password),
			'subscription' => $request->subscription,
			'expired_date' => Carbon::now()->addDays(-1),
			// 'expired_date' => Carbon::now()->addDays($subscrip[0]->plan_validity),
			'website_id' => $website->id,
		]);
		
		if (count($subscrip)) {
			$update_data = [
				'no_of_admins' => $subscrip[0]->no_of_admins,
				'no_of_technicians' => $subscrip[0]->no_of_technicians,
				'default_cycle' => isset($subscrip[0]->default_cycle) ? $subscrip[0]->default_cycle : 4,
				'app_logout_time' => isset($subscrip[0]->app_logout_time) ? $subscrip[0]->app_logout_time : 10,
				'form1' => $subscrip[0]->form1,
				'form2' => $subscrip[0]->form2,
				'form3' => $subscrip[0]->form3,
				'form4' => $subscrip[0]->form4,
				'form5' => $subscrip[0]->form5,
				'form6' => $subscrip[0]->form6,
				'form7' => $subscrip[0]->form7,
				'form8' => $subscrip[0]->form8,
				'form9' => $subscrip[0]->form9,
				'form10' => $subscrip[0]->form10,
				'form11' => $subscrip[0]->form11,
				'form12' => $subscrip[0]->form12,
				'form13' => $subscrip[0]->form13,
				'form14' => $subscrip[0]->form14,
				'form15' => $subscrip[0]->form15,
				'form16' => $subscrip[0]->form16,
				'form17' => $subscrip[0]->form17,
				'form18' => $subscrip[0]->form18,
				'form19' => $subscrip[0]->form19,
				'form20' => $subscrip[0]->form20,
				'form21' => $subscrip[0]->form21,
				'website_id' => $website->id,
				'reminderdefaultdays' =>2,
			];
			AccessLevel::create($update_data);
		}

		$subscrip = Subscription::getdatabycolumn_name('id', $request->subscription);
		$insert_data = array(
			'name' => $request->first_name . ' ' . $request->last_name,
			'initials_name' => strtoupper(substr($request->first_name, 0, 1)) . '.' . strtoupper(substr($request->last_name, 0, 1)) . '.',
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
			'company_name' => $request->company_name,
			'phone' => $request->phone,
			'registration_no' => 'PHA00' . date('HisYdm'),
			'address' => Helper::mysql_escape($request->address),
			'latitude' => $request->latitude,
			'longitude' => $request->longitude,
			'host_name' => $host_name,
			'username' => $request->username,
			'pin' => $request->pin,
			'website_id' => $website->id,
			'subscription' => $request->subscription,
			'favourite'	=> $request->fvrt,
			// 'expired_date' => Carbon::now()->addDays($subscrip[0]->plan_validity),
			'expired_date' => Carbon::now()->addDays(-1),
			'Allowedsms' => $subscrip[0]->allowed_sms,
			'usedsms' => 0
		);
		
		User::create($insert_data);
		$insert_data['roll_type'] = 'admin';
		$company = Company::create($insert_data);



		$details['name'] = $request->first_name . ' ' . $request->last_name;
		$details['details'] = "Account Verification";
		$details['id'] = $request->email;
		$email = $request->email;
		\Mail::to($email)->send(new \App\Mail\VerificationEmail($details));


		EventsLogAdmin::create([
			'website_id' => $website->id,
			'action_by' => $request->session()->get('admin')->id,
			'action' => 1,
			'action_detail' => 'Pharmacy',
			'comment' => 'Create Pharmacy',
			'patient_id' => null,
			'ip_address' => $request->ip(),
			'type' => 'SuperAdmin',
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);

		return redirect('admin/all_pharmacies')->with('msg', '<div class="alert alert-success""> New Pharmacy Created <strong> Successfully</strong>.</div>');
	}

	private function tenantSetUp($host_name, $data) {
		$getHost = Hostnames::where('fqdn', $fqdn)->first();
		if (!empty($getHost) && $getHost->fqdn == "") {
			$website->hostnames()->save($hostname);
			$host = app(HostnameRepository::class)->create($hostname);
			app(WebsiteRepository::class)->create($website);
			app(HostnameRepository::class)->attach($host, $website);
		} else {
			return redirect('admin/add_pharmacy')->with('msg', '<div class="alert alert-danger""> this company name is   <strong> allready exist .</strong>.</div>');
		}
		//Create default folders
		// $folders = [
		//     'document_templates',
		//     'phermacist_documents',
		//     'phermacist_profile',
		// ];
		// foreach ($folders as $folder) {
		//     $path="storage/tenancy/tenants/{$uuid}/{$folder}";

		//     if(!is_dir($path)){
		//         File::makeDirectory("storage/tenancy/tenants/{$uuid}/{$folder}", 0777, true);
		//     }
		// }

		$subscrip = Subscription::getdatabycolumn_name('id', $data['subscription']);
		$permacist_result = Phermacist::create([
			'username' => Str::slug($data['first_name'] . $data['last_name']),
			'first_name' => $data['first_name'],
			'last_name' => $data['last_name'],
			'email' => $data['email'],
			'company_name' => $data['company_name'],
			'host_name' => $data['host_name'],
			'password' => Hash::make($data['password']),
			'subscription' => $data['subscription'],
			'expired_date' => Carbon::now()->addDays($subscrip[0]->plan_validity),
			'website_id' => $website->id,
		]);
		// echo $subscrip[0]->title;  die;
		if (count($subscrip)) {
			$update_data = [
				'no_of_admins' => $subscrip[0]->no_of_admins,
				'no_of_technicians' => $subscrip[0]->no_of_technicians,
				'default_cycle' => $subscrip[0]->default_cycle,
				'app_logout_time' => $subscrip[0]->app_logout_time,
				'form1' => $subscrip[0]->form1,
				'form2' => $subscrip[0]->form2,
				'form3' => $subscrip[0]->form3,
				'form4' => $subscrip[0]->form4,
				'form5' => $subscrip[0]->form5,
				'form6' => $subscrip[0]->form6,
				'form7' => $subscrip[0]->form7,
				'form8' => $subscrip[0]->form8,
				'form9' => $subscrip[0]->form9,
				'form10' => $subscrip[0]->form10,
				'form11' => $subscrip[0]->form11,
				'form12' => $subscrip[0]->form12,
				'form13' => $subscrip[0]->form13,
				'form14' => $subscrip[0]->form14,
				'form15' => $subscrip[0]->form15,
				'form16' => $subscrip[0]->form16,
				'form17' => $subscrip[0]->form17,
				'form18' => $subscrip[0]->form18,
				'form19' => $subscrip[0]->form19,
				'form20' => $subscrip[0]->form20,
				'form21' => $subscrip[0]->form21,
				'website_id' => $website->id,
			];
			AccessLevel::create($update_data);
		}

		// $create_date = date('Y-m-d H:i:s', strtotime($company->created_at));
		// $interval = new \DateInterval("P30D");
		// $until = new \DateTime($create_date);
		// $end_date = $until->add($interval);
		// $company->expiry_date = $end_date->format('Y-m-d H:i:s');
		// $company->save();

		// $employee = Company::create([
		//     'username'          => employee_username($data['first_name'], $data['last_name']),
		//     'first_name'        => $data['first_name'],
		//     'last_name'         => $data['last_name'],
		//     'email'             => $data['email'],
		//     'password'          => Hash::make($data['password']),
		//     'status'            => 'active',
		//     'login_created'     => 1,
		// ]);

		// $company->owner_id = $employee->id;
		// $company->save();

		// $defaultRole = config('roles.models.role')::where('level', '=', 0)->first();
		// $employee->attachRole($defaultRole);

		// $role = config('roles.models.role')::where(['is_admin_role' => 1, 'slug' => 'administrator'])->first();
		// $employee->attachRole($role);

		return $website;
	}

	/*Edit Pharmacy */
	public function edit_pharmacy(Request $request) {
		$data['all_subscription'] = Subscription::all();

		$this->get_connection($request->website_id);
		$data['pharmacy'] = Company::where('website_id', $request->website_id)->first();
		DB::disconnect('tenant');

		$resultuser = user::where('website_id', $request->website_id)->first();
		$favourite = $resultuser->favourite;
		if($favourite == null)
		{
			$favourite = 0;
		}
		$id = $resultuser->id;
		

		
		return view('admin.edit_pharmacy')->with($data)->with('favourite',$favourite)
		->with('id',$id);
	}

	public function update_pharmacy(Request $request)
	 {


		
		$validate_array = array(
			'first_name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
			// 'email'             => ['required', 'string', 'email', 'max:255', 'unique:users'],
			//'password'          => ['required', 'string', 'confirmed','min:6'],

			
			'term' => ['required'],
			'company_name' => ['required', 'string', 'max:255'],
			// 'host_name'         => ['required', 'string', 'max:190' , 'unique:users'],
			// 'phone' => ['required', 'string', 'max:9', 'min:9'],
			'address' => ['required', 'string', 'max:2553535'],
			'subscription'      => ['required', 'string', 'max:255'],
		);

		if($request->password != null)
		{
			$this->update_pharmacy_validator($request->all())->validate();	
		}
		
		$website = Websites::where('uuid', $request->company_name)->where('id','<>',$request->website_id)->get();
		if (count($website)) {
			return redirect('admin/edit_pharmacy/'.$request->website_id.'/'.$request->rowid)->with('msg', '<div class="alert alert-danger""> this host name is  already<strong>   exist .</strong>.</div>');
		}
		


		$update_data1 = array(
			'username' => $request->first_name . $request->last_name,
			'first_name' => $request->first_name,
		    'last_name' => $request->last_name,
			'company_name' => $request->company_name,
			'subscription' => $request->subscription,
		);



		$update_data2 = array(
			'name' => $request->first_name . ' ' . $request->last_name,
			'initials_name' => strtoupper(substr($request->first_name, 0, 1)) . '.' . strtoupper(substr($request->last_name, 0, 1)) . '.',
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'company_name' => $request->company_name,
			'phone' => $request->phone,
			'address' => $request->address,
			'subscription' => $request->subscription,
			'latitude' => $request->latitude,
			'longitude' => $request->longitude,
			'favourite'	=> $request->fvrt,
		);

		$company_update_data2 = array(
			'name' => $request->first_name . ' ' . $request->last_name,
			'initials_name' => strtoupper(substr($request->first_name, 0, 1)) . '.' . strtoupper(substr($request->last_name, 0, 1)) . '.',
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'company_name' => $request->company_name,
			'phone' => $request->phone,
			'address' => $request->address,
			'subscription' => $request->subscription,
			'latitude' => $request->latitude,
			'longitude' => $request->longitude,
		);


		if ($request->password != "") {
			$validate_array['password'] = ['required', 'string', 'confirmed', 'min:6'];
			$update_data1['password'] = Hash::make($request->password);
			$update_data2['password'] = Hash::make($request->password);
		}

		if ($request->pin != "") {

			$update_data1['pin'] = $request->pin;
			$update_data2['pin'] = $request->pin;
		}
		
		$request->validate($validate_array);
		User::where('website_id', $request->website_id)->update($update_data2);
		Phermacist::where('website_id', $request->website_id)->update($update_data1);
		
		
		// dd($update_data1);
		EventsLogAdmin::create([
			'website_id' => $request->website_id,
			'action_by' => $request->session()->get('admin')->id,
			'action' => 2,
			'action_detail' => 'Pharmacy Details',
			'comment' => 'Update Pharmacy Details',
			'patient_id' => null,
			'ip_address' => $request->ip(),
			'type' => 'SuperAdmin',
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		$this->get_connection($request->website_id);
		Company::where('website_id', $request->website_id)->where('id', 1)->update($company_update_data2);
		$subscrip = Subscription::getdatabycolumn_name('id', $request->subscription);
		if (count($subscrip)) {
			$update_data = [
				'no_of_admins' => $subscrip[0]->no_of_admins,
				'no_of_technicians' => $subscrip[0]->no_of_technicians,
				'default_cycle' => $subscrip[0]->default_cycle,
				'app_logout_time' => $subscrip[0]->app_logout_time,
				'form1' => $subscrip[0]->form1,
				'form2' => $subscrip[0]->form2,
				'form3' => $subscrip[0]->form3,
				'form4' => $subscrip[0]->form4,
				'form5' => $subscrip[0]->form5,
				'form6' => $subscrip[0]->form6,
				'form7' => $subscrip[0]->form7,
				'form8' => $subscrip[0]->form8,
				'form9' => $subscrip[0]->form9,
				'form10' => $subscrip[0]->form10,
				'form11' => $subscrip[0]->form11,
				'form12' => $subscrip[0]->form12,
				'form13' => $subscrip[0]->form13,
				'form14' => $subscrip[0]->form14,
				'form15' => $subscrip[0]->form15,
				'form16' => $subscrip[0]->form16,
				'form17' => $subscrip[0]->form17,
				'form18' => $subscrip[0]->form18,
				'form19' => $subscrip[0]->form19,
				'form20' => $subscrip[0]->form20,
				'form21' => $subscrip[0]->form21,
				'reminderdefaultdays' =>2,
			];
			AccessLevel::where('id', 1)->update($update_data);
		}
		DB::disconnect('tenant');
		return redirect('admin/all_pharmacies')->with('msg', '<div class="alert alert-success""> Pharmacy    <strong> Updated </strong> Successfully.</div>');

	}

	public function pharmacist() {
		
		$data['all_subscription'] = Subscription::all();
		return view('admin.pharmacy_signup')->with($data);
	}

	public function pharmacist_verification(String $id){
		 
		$id  = base64_decode($id);
		$update_status = \DB::table("phermacist")->where("email", $id)->update(['verification_status' => 1]);
		// echo json_encode($update_status);exit;
		
		 

		return redirect('/')->with('msg', '<div class="alert alert-success""> Account <strong>Verified</strong> Successfully.</div>');
	}

	public function add_pharmacist(Request $request) {
		
		$data['all_subscription'] = Subscription::all();		

		$request->uuid = $request->host_name;
		
		$this->validator($request->all())->validate();
		/*Create Tenant Setup*/
		$website = Websites::where('uuid', $request->host_name)->get();
		// if (count($website)) {
		// 	return redirect('admin/add_pharmacy')->with('msg', '<div class="alert alert-danger""> this host name is  allready<strong>   exist .</strong>.</div>');
		// }
		$host_name = $request->host_name;
		// $fqdn = "www.{$host_name}." . env('PROJECT_HOST', 'packpeak.com.au');
		// $fqdn = "{$host_name}." . env('PROJECT_HOST', 'packpeak.com.au');
		$fqdn = "{$host_name}." . env('PROJECT_HOST', 'localhost');
		$hostname = new Hostname;
		$hostname->fqdn = $fqdn;
		// $uuid = Str::limit(Str::slug($host_name, '_'), 15, '');
		$uuid = $host_name;
		$website = new Website;
		$website->uuid = $uuid;

		$website->hostnames()->save($hostname);
		$host = app(HostnameRepository::class)->create($hostname);
		app(WebsiteRepository::class)->create($website);
		app(HostnameRepository::class)->attach($host, $website);
		$subscrip = Subscription::getdatabycolumn_name('id', $request->subscription);
		$permacist_result = Phermacist::create([
			'username' => $request->username,
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'email' => $request->email,
			'company_name' => $request->company_name,
			'host_name' => $request->host_name,
			'pin' => $request->pin,
			'password' => Hash::make($request->password),
			'subscription' => $request->subscription,
			'expired_date' => Carbon::now()->addDays($subscrip[0]->plan_validity),
			'website_id' => $website->id,
		]);
		if (count($subscrip)) {
			$update_data = [
				'no_of_admins' => $subscrip[0]->no_of_admins,
				'no_of_technicians' => $subscrip[0]->no_of_technicians,
				'default_cycle' => isset($subscrip[0]->default_cycle) ? $subscrip[0]->default_cycle : 4,
				'app_logout_time' => isset($subscrip[0]->app_logout_time) ? $subscrip[0]->app_logout_time : 10,
				'form1' => $subscrip[0]->form1,
				'form2' => $subscrip[0]->form2,
				'form3' => $subscrip[0]->form3,
				'form4' => $subscrip[0]->form4,
				'form5' => $subscrip[0]->form5,
				'form6' => $subscrip[0]->form6,
				'form7' => $subscrip[0]->form7,
				'form8' => $subscrip[0]->form8,
				'form9' => $subscrip[0]->form9,
				'form10' => $subscrip[0]->form10,
				'form11' => $subscrip[0]->form11,
				'form12' => $subscrip[0]->form12,
				'form13' => $subscrip[0]->form13,
				'form14' => $subscrip[0]->form14,
				'form15' => $subscrip[0]->form15,
				'form16' => $subscrip[0]->form16,
				'form17' => $subscrip[0]->form17,
				'form18' => $subscrip[0]->form18,
				'form19' => $subscrip[0]->form19,
				'form20' => $subscrip[0]->form20,
				'form21' => $subscrip[0]->form21,
				'website_id' => $website->id,
			];
			AccessLevel::create($update_data);
		}

		$subscrip = Subscription::getdatabycolumn_name('id', $request->subscription);
		$insert_data = array(
			'name' => $request->first_name . ' ' . $request->last_name,
			'initials_name' => strtoupper(substr($request->first_name, 0, 1)) . '.' . strtoupper(substr($request->last_name, 0, 1)) . '.',
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
			'company_name' => $request->company_name,
			'phone' => $request->phone,
			'registration_no' => 'PHA00' . date('HisYdm'),
			'address' => Helper::mysql_escape($request->address),
			'latitude' => $request->latitude,
			'longitude' => $request->longitude,
			'host_name' => $host_name,
			'username' => $request->username,
			'pin' => $request->pin,
			'website_id' => $website->id,
			'subscription' => $request->subscription,
			'favourite'	=> $request->fvrt,
			'expired_date' => Carbon::now()->addDays($subscrip[0]->plan_validity),
		);
		User::create($insert_data);
		$insert_data['roll_type'] = 'admin';
		$company = Company::create($insert_data);
		EventsLogAdmin::create([
			'website_id' => $website->id,
			'action_by' => '9999999999',
			'action' => 1,
			'action_detail' => 'Pharmacy',
			'comment' => 'Create Pharmacy',
			'patient_id' => null,
			'ip_address' => $request->ip(),
			'type' => 'SuperAdmin',
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 0,
		]);

		$details['name'] = $request->first_name;
		$details['details'] = "Please verify your account.";
		$details['id'] = $request->email;
		if($request->subscription == 1){
			$plan_id = "P-5P33556222219705LNFW2RQQ";
		}elseif($request->subscription == 2){
			$plan_id = "P-8PP795878C742235GNF3WYYY";
		}elseif($request->subscription == 3)
		{
			$plan_id = "P-3LA40509PG404401SNF455GI";
		}
			
		\Mail::to($request->email)->send(new \App\Mail\VerificationEmail($details));
		return redirect("subscribe/paypal/$plan_id");
		//return redirect('/')->with('msg', '<div class="alert alert-success""> Verification link sent to your email address please click on link to verify your account <a  href="'.'/pharmacist_signup/resendVerificationEmail/'.$details['id'].'/'.$details['name'].'/'.$details['details'].'">resend verification link.</a>.</div>');
	}

	public function resend_verification()
	{

		$details['name'] = "Hasan Bilal";
		$details['details'] = "welcome to pack peak";
		$details['id'] = 'hasanbilal369@gmail.com';
		$email = 'hasanbilal369@gmail.com';
		\Mail::to($email)->send(new \App\Mail\VerificationEmail($details));

		return redirect('/')->with('msg', '<div class="alert alert-success""> Verification link sent to your email address please click on link to verify your account <a  href="'.'/pharmacist_signup/resendVerificationEmail/'.$details['id'].'/'.$details['name'].'/'.$details['details'].'">resend verification link.</a>.</div>');

	}


	// public function resend_verification($email,$name,$detail)
	// {

	// 	$details['name'] = $name;
	// 	$details['details'] = $detail;
	// 	$details['id'] = $email;
	// 	\Mail::to($email)->send(new \App\Mail\VerificationEmail($details));

	// 	return redirect('/')->with('msg', '<div class="alert alert-success""> Verification link sent to your email address please click on link to verify your account <a  href="'.'/pharmacist_signup/resendVerificationEmail/'.$details['id'].'/'.$details['name'].'/'.$details['details'].'">resend verification link.</a>.</div>');
	// }


	public  function  pharmacy_reports()
    {
		$data['all_pickups']=User::all();
		
        // $newarray=array();
        // foreach($all_pharmacy  as $row){
        //       $this->get_connection($row->website_id);
        //       $get_audit=Phermacist::all();
        //         foreach($get_audit as $col) {
        //             $col->pharmacy=$row->company_name;
        //             $newarray[]=$col;
        //         }
        //       DB::disconnect('tenant');
        // } 

		
        // $data['all_pickups']=Phermacist::all();
		
		return  view('admin.pharmacy_reports')->with($data); 
    }

	public  function generateRandomString($length = 20) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }	

	public function add_pharmacy(Request $request) {

		
		// dd(Helper::smsSendToMobile('+923244605828', "hi"));
		$data['all_subscription'] = Subscription::all();
		return view('admin.add_pharmacy')->with($data);
	}
	public function pharmacist_login(Request $request) {
		return view('admin.pharmacist-login');
	}
	public function all_pharmacies(Request $request) {
		
		$data['all_pharmacies'] = User::orderBy('favourite','DESC')->get();
		
		return view('admin.all_pharmacies')->with($data);
	}
	public function all_pharmacies_ajax(Request $request) {
		

		// $data['all_pharmacies'] = User::orderBy('favourite','DESC')->get();
		$data['all_pharmacies'] = User::orderBy('id','ASC')->get();
	
		return json_encode($data);

		/*return json_encode($data);*/

	}
	/* Details of  Pharmacy */
	public function pharmacy_deatils(Request $request) {
		//  print_r($request->website_id); die;
		$get_pharmacy = User::get_by_column('website_id', $request->website_id);
		if (count($get_pharmacy)) {
			$data['pharmacy_details'] = $get_pharmacy;
			$data['all_subscription'] = Subscription::all();
			$select_subscription = Subscription::getdatabycolumn_name('id', $get_pharmacy[0]->subscription);
			// echo $get_pharmacy[0]->host_name; die;
			config(['database.connections.tenant.database' => $get_pharmacy[0]->host_name]);
			DB::purge('tenant');
			DB::reconnect('tenant');
			DB::disconnect('tenant');
			$data['form'] = $select_subscription[0];
			$data['subscription'] = AccessLevel::all();
			return view("admin.pharmacy_details")->with($data);
		}
	}
	/*Extends validity */
	public function update_validity(Request $request) {
		$request->validate(['plan_validity' => 'required|numeric']);
		$current_date = \Carbon\Carbon::now()->format('Y-m-d');
		$start_date = \Carbon\Carbon::createFromFormat('Y-m-d', $current_date);
		$expiry_date = $start_date->addDays(($request->plan_validity) - 1)->format('Y-m-d');

		$request->website_id;
		$getUser = User::get_by_column('website_id', $request->website_id);

		if ($getUser[0]->expired_date < \Carbon\Carbon::now()->format('Y-m-d')) {

			Phermacist::update_where(array('host_name' => $getUser[0]->host_name), array('expired_date' => $expiry_date));
			User::where('website_id', $request->website_id)->update(array('expired_date' => $expiry_date));die("update");
			$this->get_connection($request->website_id);
			Company::where('website_id', $request->website_id)->update(array('expired_date' => $expiry_date));
			DB::disconnect('tenant');
		} else {

			$expiry_date = \Carbon\Carbon::createFromFormat('Y-m-d', $getUser[0]->expired_date)->addDays($request->plan_validity)->format('Y-m-d');

			Phermacist::update_where(array('host_name' => $getUser[0]->host_name), array('expired_date' => $expiry_date));
			User::where('website_id', $request->website_id)->update(array('expired_date' => $expiry_date));
			$this->get_connection($request->website_id);
			Company::where('website_id', $request->website_id)->update(array('expired_date' => $expiry_date));
			DB::disconnect('tenant');

		}
		EventsLogAdmin::create([
			'website_id' => $request->website_id,
			'action_by' => $request->session()->get('admin')->id,
			'action' => 2,
			'action_detail' => 'Pharmacy Validity',
			'comment' => 'Update Pharmacy Validity',
			'patient_id' => null,
			'ip_address' => $request->ip(),
			'type' => 'SuperAdmin',
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);

		return redirect('admin/all_pharmacies')->with('msg', '<div class="alert alert-success""> Subscription   <strong> plan validity </strong> extends.</div>');
	}

	/*get  tenant Connection */
	public function get_connection($website_id) {
		$get_user = User::get_by_column('website_id', $website_id);
		// echo $get_user[0]->host_name;   die("hi Pradeep");
		config(['database.connections.tenant.database' => $get_user[0]->host_name]);
		DB::purge('tenant');
		DB::reconnect('tenant');
		DB::disconnect('tenant');
	}

	public function technician() {
		
		
		$data['all_pharmacies'] = User::all();
		return view('admin.add_technician')->with($data);
	}
	/*save Technician */
	public function save_technician(Request $request) {
		
		$this->get_connection($request->company_name);
		$validate_array = $request->validate([
			'company_name' => ['required', 'string', 'max:255'],
			'first_name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'min:6', 'max:255', 'unique:tenant.companies'],
			'password' => ['required', 'string', 'confirmed', new isValidPassword()],
			'username' => ['required', 'string', 'min:6', 'max:20', 'unique:tenant.companies'],
			'pin' => ['required', 'string', 'min:4', 'max:4'],
			'role' => ['required', 'string'],
			'term' => ['required'],
			// 'phone' => ['required', 'string', 'min:9', 'max:9'],
			'address' => ['required', 'string', 'max:255'],
		], ["email.unique" => 'this email has been blocked. you can contact to administrator.']);

		//$request->validate($validate_array);

		$pharmacy = User::where('website_id', $request->company_name)->first();
		if ($pharmacy->subscription) {
			$subcription = $pharmacy->subscription;
		} else {
			$subcription = 1;
		}
		$insert_data = array(
			'name' => $request->first_name . ' ' . $request->last_name,
			'initials_name' => strtoupper(substr($request->first_name, 0, 1)) . '.' . strtoupper(substr($request->last_name, 0, 1)) . '.',
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
			'website_id' => $request->company_name,
			'phone' => $request->phone,
			'username' => $request->username,
			'pin' => $request->pin,
			'registration_no' => 'PHA00' . date('HisYdm'),
			'address' => $request->address,
			'roll_type' => $request->role,
			'subscription' => $subcription,
		);
		$getAccess = AccessLevel::find(1);
		$getAllAdmin = Company::where('roll_type', 'admin')->get();
		$getAllTechnician = Company::where('roll_type', 'technician')->get();
		if ($request->role == 'admin' && $getAccess->no_of_admins <= count($getAllAdmin)) {
			return redirect()->back()->withInput()->with('msg', '<div class="alert alert-danger""> your pharmacy can create only ' . $getAccess->no_of_admins . ' Admin </div>');
		}
		if ($request->role == 'technician' && $getAccess->no_of_technicians <= count($getAllTechnician)) {
			return redirect()->back()->withInput()->with('msg', '<div class="alert alert-danger""> your pharmacy can create only ' . $getAccess->no_of_technicians . '  Technician </div>');
		}

		$company = Company::create($insert_data);
		EventsLog::create([
			'website_id' => $request->company_name,
			'action_by' => '-' . $request->session()->get('admin')->id,
			'action' => 1,
			'action_detail' => ucfirst($request->role),
			'comment' => 'Create ' . ucfirst($request->role),
			'patient_id' => null,
			'ip_address' => $request->ip(),
			'type' => 'SuperAdmin',
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		DB::disconnect('tenant');
		return redirect('admin/technician')->with('msg', '<div class="alert alert-success""> ' . ucfirst($request->role) . '    <strong>' . $request->first_name . ' ' . $request->last_name . ' </strong>  Added Successfully.</div>');

	}

	/*Edit technicians*/
	public function edit_technician(Request $request) {
		$data['all_pharmacies'] = User::all();
		$this->get_connection($request->website_id);
		$data['technician'] = Company::get_by_where(array('id' => $request->row_id, 'deleted_at' => NULL));
		DB::disconnect('tenant');
		return view('admin.edit_technician')->with($data);
	}

	public function status_technician(Request $request) {

		$this->get_connection($request->website_id);
		if ($request->status == 0) 
		{

			Company::update_where(array('id' => $request->row_id, 'website_id' => $request->website_id), array('status' =>0));
		}
		elseif ($request->status == 1 )
		{	
			Company::update_where(array('id' => $request->row_id, 'website_id' => $request->website_id), array('status' =>1));
		}
		return redirect('admin/all_technician');
	}


	public function status_pharmacy(Request $request) {

		$this->get_connection($request->website_id);
		if ($request->status == 0) 
		{

			Company::update_where(array('id' => $request->row_id, 'website_id' => $request->website_id), array('status' =>0));
		}
		elseif ($request->status == 1 )
		{	
			Company::update_where(array('id' => $request->row_id, 'website_id' => $request->website_id), array('status' =>1));
		}
		$userresult = user::where('website_id','=',$request->website_id)->first();
		$userresult->status = $request->status;
		$userresult->save();
		$data['all_pickups']=User::all();		
		return  view('admin.pharmacy_reports')->with($data);
	}

	

	/*update Technician */
	public function update_technician(Request $request) {
	// dd("1");
	// return;
		$request->validate([
			'first_name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
			'term' => ['required'],
			'phone' => ['required', 'string', 'min:9', 'max:9'],
			'address' => ['required', 'string', 'max:255'],
		]);
		
		if($request->password != null)
		{
			$this->update_technition_validator($request->all())->validate();	
		}

		$update_data = array(
			'name' => $request->first_name . ' ' . $request->last_name,
			'initials_name' => strtoupper(substr($request->first_name, 0, 1)) . '.' . strtoupper(substr($request->last_name, 0, 1)) . '.',
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'phone' => $request->phone,
			'address' => $request->address,
			
			//below role update added by hasan bilal
			'roll_type' => $request->roll_type,
		);


		// $this->get_connection($request->company_name);
		// $getAccess = AccessLevel::find(1);
		// $getAllAdmin = Company::where('roll_type', 'admin')->get();
		// $getAllTechnician = Company::where('roll_type', 'technician')->get();
		// if ($request->roll_type == 'admin' && $getAccess->no_of_admins <= count($getAllAdmin)) {
		// 	return redirect()->back()->withInput()->with('msg', '<div class="alert alert-danger""> your pharmacy can create only ' . $getAccess->no_of_admins . ' Admin </div>');
		// }
		// if ($request->roll_type == 'technician' && $getAccess->no_of_technicians <= count($getAllTechnician)) {
		// 	return redirect()->back()->withInput()->with('msg', '<div class="alert alert-danger""> your pharmacy can create only ' . $getAccess->no_of_technicians . '  Technician </div>');
		// }

		if ($request->pin != "") {
			$update_data['pin'] = $request->pin;
		}
		if ($request->password && $request->password == $request->password_confirmation) {
			$update_data['password'] = Hash::make($request->password);
		}
		if ($request->password && $request->password != $request->password_confirmation) {
			return redirect()->back()->with('msg', '<div class="alert alert-danger""> Password  And Confirm Password Must be Same.</div>');
		}
		$this->get_connection($request->website_id);
		//  echo $request->row_id; die;
		$company = Company::update_where(array('id' => $request->row_id), $update_data);
		EventsLog::create([
			'website_id' => $request->website_id,
			'action_by' => '-' . $request->session()->get('admin')->id,
			'action' => 2,
			'action_detail' => 'Technician',
			'comment' => 'Update Technician',
			'patient_id' => null,
			'ip_address' => $request->ip(),
			'type' => 'SuperAdmin',
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		DB::disconnect('tenant');
		return redirect('admin/all_technician')->with('msg', '<div class="alert alert-success""> Technician  <strong>' . $request->first_name . ' ' . $request->last_name . ' </strong>  Updated Successfully.</div>');

	}

	/*All technician List */
	public function all_technician() {
		
		
		$all_pharmacy = User::all();
		$newarray = array();
		foreach ($all_pharmacy as $row) {
			$this->get_connection($row->website_id);
			//$all_result = Company::select_all_technician();
			$all_result = Company::withTrashed()->where('roll_type', 'technician')
			->where('deleted_by','=', NULL)
			->get();
			foreach ($all_result as $col) {
				$col->pharmacy = $row->company_name;
				$newarray[] = $col;
			}
			DB::disconnect('tenant');
		}
		$data['all_technician'] = $newarray;
		return view('admin.all_technician')->with($data);
	}

	/*Archived technician List */
	public function all_archived_technician() {


		$all_pharmacy = User::all();
		$newarray = array();
		foreach ($all_pharmacy as $row) {
			$this->get_connection($row->website_id);
			//$all_result = Company::select_all_technician();
			$all_result = Company::onlyTrashed()->where('roll_type', 'technician')
			->get();
			foreach ($all_result as $col) {
				$col->pharmacy = $row->company_name;
				$newarray[] = $col;
			}
			DB::disconnect('tenant');
		}
		$data['all_technician'] = $newarray;
		return view('admin.all_archived_technician')->with($data);
	}

	/*delete Technician */
	public function delete_technician(Request $request) {

		// return view('admin.all_techniciansss');
		$this->get_connection($request->website_id);
		Company::update_where(array('id' => $request->row_id, 'website_id' => $request->website_id), array('deleted_by' => '-' . $request->session()->get('admin')['id']));
	
	
		Company::delete_record($request->row_id);
		EventsLog::create([
			'website_id' => $request->website_id,
			'action_by' => '-' . $request->session()->get('admin')->id,
			'action' => 3,
			'action_detail' => 'Technician',
			'comment' => 'Delete Technician',
			'patient_id' => null,
			'ip_address' => $request->ip(),
			'type' => 'SuperAdmin',
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		DB::disconnect('tenant');
		echo '200';
	}

	public function get_parmacydata_by_website_id(Request $request) {
		if ($request->website_id) {
			$this->get_connection($request->website_id);
			$facility = Facility::get();
			$stores = Store::get();
			$patients = Patient::get();
			$company = Company::find($request->website_id);
			DB::disconnect('tenant');
			$facilitydata = '<option value="">-- Select Facility--</option>';
			if (count($facility)) {
				foreach ($facility as $key => $value) {
					$facilitydata .= '<option value="' . $value->name . '">' . $value->name . '</option>';
				}
			}
			$storedata = "";
			if (count($stores)) {
				foreach ($stores as $key => $value) {
					$storedata .= '<option value="' . $value->id . '">' . $value->name . '</option>';
				}
			}
			$patientdata = '<option value="">-- Select  Patient--</option>';
			if (count($patients)) {
				foreach ($patients as $key => $row) {
					$created_at = isset($row->latestPickups->created_at) ? $row->latestPickups->created_at : "";
					$no_of_weeks = isset($row->latestPickups->no_of_weeks) ? $row->latestPickups->no_of_weeks : "";
					$notes_from_patient = isset($row->latestPickups->notes_from_patient) ? $row->latestPickups->notes_from_patient : "";
					$location = isset($row->latestPickups->location) ? $row->latestPickups->location : "";
					$pick_up_by = isset($row->latestPickups->pick_up_by) ? $row->latestPickups->pick_up_by : "";
					$carer_name = isset($row->latestPickups->carer_name) ? $row->latestPickups->carer_name : "";

					$returnStore = isset($row->latestReturn->store) ? $row->latestReturn->store : '';
					$returnStoreOther = isset($row->latestReturn->other_store) ? $row->latestReturn->other_store : '';
					$AuditStore = isset($row->latestAudit->store) ? $row->latestAudit->store : '';
					$AuditStoreOther = isset($row->latestAudit->store_others_desc) ? $row->latestAudit->store_others_desc : '';
					$patientdata .= '<option value="' . $row->id . '" data-dob="' . $row->dob . '" data-lastPickupDate="' . $created_at . '"  data-lastPickupWeek="' . $no_of_weeks . '"
              data-lastNoteForPatient="' . $notes_from_patient . '"
              data-lastLocation="' . $location . '"
              data-last_pick_up_by="' . $pick_up_by . '"

              data-last_returnStore="' . $returnStore . '"
              data-last_returnStoreOther="' . $returnStoreOther . '"

              data-last_AuditStore="' . $AuditStore . '"
              data-last_AuditStoreOther="' . $AuditStoreOther . '"

              data-last_carer_name="' . $carer_name . '">' . $row->first_name . ' ' . $row->last_name . '(' . date("j/n", strtotime($row->dob)) . ')' . '</option>';

				}
			}
			return response()->json([
				'facility' => $facilitydata,
				'store' => $storedata,
				'patient' => $patientdata,
				'pin' => isset($company->pin) ? $company->pin : '',
				'status' => '0',
			]);
		} else {
			return response()->json([
				'facility' => aray(),
				'store' => aray(),
				'patient' => aray(),
				'pin' => '',
				'status' => '1',
			]);
		}
	}

}
