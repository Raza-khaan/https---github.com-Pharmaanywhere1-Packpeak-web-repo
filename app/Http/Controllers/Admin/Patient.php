<?php

namespace App\Http\Controllers\Admin;
use GuzzleHttp\Client;
use ClickSend;
use App\Models\Admin\Facility;
use App\Models\Admin\Location;
use App\Models\Tenant\Company;
use App\Models\Tenant\EventsLog;
use App\Models\Tenant\Patient as Patient_Model;
use App\Models\Tenant\PatientLocation;
use App\Models\Tenant\Facility as TenantFacilityModel;
use App\Models\Tenant\Store;
use App\Models\Tenant\Pickup;
use App\Models\Tenant\Checking;
use App\Models\Tenant\Audit;
use App\Models\Tenant\AccessLevel;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Response;
use DateTime;

class Patient extends Near_Miss {

	public  function  overall_view_report(Request $req)
    {
		$weeks = 0;
		$company_id = 0;
		$date = Carbon::now();
		$datefrom;
		$dateto;

		if($req->weeks != null)
			{
				$weeks = $req->weeks; 
			}
		if($req->company_id != null)
			{
				$company_id = $req->company_id; 
			}
		if($req->datefrom !=null)
			{
				$datefrom = $req->datefrom;
			}
		else
			{
				$datefrom = $date->format('Y-m-d');
			}
		if($req->dateto !=null)
			{
				$dateto = $req->dateto;
			}
		else
			{
				$dateto = $date->format('Y-m-d');
			}
			
			// get all pharmacy list
			$all_pharmacy_list = User::all();

			if($company_id>0)
			{
			$all_pharmacy = User::all()->where('website_id','=',$company_id);
			}
			else
			{
			$all_pharmacy = User::all();
			}
			
			$data['all_pharmacy'] = $all_pharmacy_list;
			$newarray = array();
			foreach ($all_pharmacy as $row) {
			$this->get_connection($row->website_id);

		// $get_audit = Patient_Model::all();
		if($req->excludeweeks>0)
		{

			$Includedays = $req->excludeweeks * 7;
			

			$includepickupdate = Carbon::parse($date->format('Y-m-d'))->addDays(-$Includedays);
			
			$get_audit = PickUp::where('pick_ups.deleted_at', NULL)
			->select('pick_ups.id','pick_ups.created_at','patients.id','patients.first_name','patients.last_name')
			->join('patients', 'pick_ups.patient_id', '=', 'patients.id')
			->where('patients.deleted_at', NULL)
			//->where('pick_ups.created_at', '<', $nextpickupdate)
			->whereBetween('pick_ups.created_at', [$includepickupdate, $date->format('Y-m-d')])
			->groupBy('patients.id')
			->get();
		}
		else
		{
			

			$get_audit = PickUp::where('pick_ups.deleted_at', NULL)
			->select('pick_ups.id','pick_ups.created_at','patients.id','patients.first_name','patients.last_name')
			->join('patients', 'pick_ups.patient_id', '=', 'patients.id')
			->where('patients.deleted_at', NULL)
			// ->whereBetween('pick_ups.created_at', [$datefrom, $dateto])
			->groupBy('patients.id')
			->get();
		}

			foreach ($get_audit as $col) {
				$Patientlocations = PatientLocation::where('patient_id', $col->id)->orderBy('created_at', 'desc')->first();
				$col->pharmacy = $row->company_name;
				if (!empty($Patientlocations)) {
					$col->locations = $Patientlocations->locations;
				}
				// last pickup detail patient
				if($req->excludeweeks>0)
				{
					$Patientlastpickup = Pickup::where('patient_id', $col->id)
					->where('created_at',"<", $nextpickupdate)->orderBy('created_at', 'desc')->first();					
				}
				else
				{
					$Patientlastpickup = Pickup::where('patient_id', $col->id)
					->whereBetween('created_at', [$req->datefrom, $req->dateto])->orderBy('created_at', 'desc')->first();					
				}
	
				if (!empty($Patientlastpickup))
				 {
					$col->lastpickupdate = $Patientlastpickup->created_at;
					$col->noofpickuppacks = $Patientlastpickup->no_of_weeks;
				}
	
				// last checked detail patient
				if($req->excludeweeks>0)
				{
					$Patientlastchecked = Checking::where('patient_id', $col->id)
					->where('created_at',"<", $nextpickupdate)->orderBy('created_at', 'desc')->first();
				}
				else
				{
					$Patientlastchecked = Checking::where('patient_id', $col->id)
					->whereBetween('created_at', [$req->datefrom, $req->dateto])->orderBy('created_at', 'desc')->first();
				}
				
				if (!empty($Patientlastchecked))
				 {
					$col->lastcheckeddate = $Patientlastchecked->created_at;
					$col->noofcheckedpacks = $Patientlastchecked->no_of_weeks;
				}
	
				//last audit detail patient
				if($req->excludeweeks>0)
				{
					$Patientlastaudit = Audit::where('patient_id', $col->id)
					->where('created_at',"<", $nextpickupdate)->orderBy('created_at', 'desc')->first();			
				}
				else
				{
					$Patientlastaudit = Audit::where('patient_id', $col->id)
					->whereBetween('created_at', [$req->datefrom, $req->dateto])->orderBy('created_at', 'desc')->first();		
				}
				
				if (!empty($Patientlastaudit))
				 {
					$col->lastauditdate = $Patientlastaudit->created_at;
					$col->noofauditpacks = $Patientlastaudit->no_of_weeks;
				}
				$newarray[] = $col;
			}
			DB::disconnect('tenant');
		}
		
		$data['new_patients'] = $newarray;
		
        return view('admin.overall_view_report',['weeks'=>$weeks,
		'datefrom'=>$datefrom,'dateto'=>$dateto,'company_id'=>$company_id])->with($data); 

    }

	public function overall_filter_report(Request $req)
	{
		$weeks = 0;
		$company_id = 0;
		$nextpickupdate;
		// Get weeks filter value and exclude patients with weeks filter pickup
		if($req->excludeweeks != null)
		{
			$weeks = $req->excludeweeks; 
			$date = Carbon::now();
			$nextpickupdate = Carbon::parse($date)->addDays(-$req->excludeweeks * 7);
			$nextpickupdate = $nextpickupdate->format('Y-m-d');	
		}
		// Get Company/Pharmacy filter value
		if($req->company_id != null)
		{
			$company_id = $req->company_id; 
		}
		// get all pharmacy list
		$all_pharmacy_list = User::all();
		if($company_id>0)
		{
			$all_pharmacy = User::all()->where('website_id','=',$company_id);
		}
		else
		{
			$all_pharmacy = User::all();
		}
		
		$data['all_pharmacy'] = $all_pharmacy_list;
		$newarray = array();
		foreach ($all_pharmacy as $row) {
		$this->get_connection($row->website_id);

		// $get_audit = Patient_Model::all();
		if($req->excludeweeks>0)
		{

			$Includedays = $req->excludeweeks * 7;
				

				$includepickupdate = Carbon::parse($date->format('Y-m-d'))->addDays(-$Includedays);
				
				$get_audit = PickUp::where('pick_ups.deleted_at', NULL)
				->select('pick_ups.id','pick_ups.created_at','patients.id','patients.first_name','patients.last_name')
				->join('patients', 'pick_ups.patient_id', '=', 'patients.id')
				->where('patients.deleted_at', NULL)
				//->where('pick_ups.created_at', '<', $nextpickupdate)
				->whereBetween('pick_ups.created_at', [$includepickupdate, $date->format('Y-m-d')])
				->groupBy('patients.id')
				->get();
		}
		else
		{
			$get_audit = PickUp::where('pick_ups.deleted_at', NULL)
			->select('pick_ups.id','pick_ups.created_at','patients.id','patients.first_name','patients.last_name',
			'patients.facilities_id','facilities.name')
			->join('patients', 'pick_ups.patient_id', '=', 'patients.id')
			->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
			->where('patients.deleted_at', NULL)
			->whereBetween('pick_ups.created_at', [$req->datefrom, $req->dateto])
			->groupBy('patients.id')
			->get();
		}



		foreach ($get_audit as $col)
		{
			$Patientlocations = PatientLocation::where('patient_id', $col->id)->orderBy('created_at', 'desc')->first();
			$col->pharmacy = $row->company_name;
			if (!empty($Patientlocations)) {
				$col->locations = $Patientlocations->locations;
			}

			// last pickup detail patient
			$Patientlastpickup = Pickup::where('patient_id', $col->id)->orderBy('created_at', 'desc')->first();	
			if (!empty($Patientlastpickup))
			{
				$col->lastpickupdate = $Patientlastpickup->created_at;
				$col->noofpickuppacks = $Patientlastpickup->no_of_weeks;
			}

			// last checked detail patient
			$Patientlastchecked = Checking::where('patient_id', $col->id)->orderBy('created_at', 'desc')->first();
			
			if (!empty($Patientlastchecked))
			{
				$col->lastcheckeddate = $Patientlastchecked->created_at;
				$col->noofcheckedpacks = $Patientlastchecked->no_of_weeks;
			}

			//last audit detail patient
			$Patientlastaudit = Audit::where('patient_id', $col->id)->orderBy('created_at', 'desc')->first();	
			
			if (!empty($Patientlastaudit))
			{
				$col->lastauditdate = $Patientlastaudit->created_at;
				$col->noofauditpacks = $Patientlastaudit->no_of_weeks;
			}

			$newarray[] = $col;
		}
		DB::disconnect('tenant');
	}

		$data['new_patients'] = $newarray;

		return view('admin.overall_view_report',['weeks'=>$req->excludeweeks,
		'datefrom'=>$req->datefrom,'dateto'=>$req->dateto,'company_id'=>$company_id])->with($data); 
	}


	public  function  patient_report(Request $req)
    {
		$weeks = 0;
		$date = Carbon::now();
		$datefrom;
		$dateto;
		$company_id = 0;
		$nextpickupdate;

		// Get weeks filter value and exclude patients with weeks filter pickup
		if($req->excludeweeks != null)
		{
			$weeks = $req->excludeweeks; 
			$date = Carbon::now();
			$nextpickupdate = Carbon::parse($date)->addDays(-$req->excludeweeks * 7);
			$nextpickupdate = $nextpickupdate->format('Y-m-d');	
		}
		if($req->datefrom !=null)
			{
				$datefrom = $req->datefrom;
			}
		else
			{
				$datefrom = $date->format('Y-m-d');
			}
		
		if($req->dateto !=null)
			{
				$dateto = $req->dateto;
			}
		else
			{
				$dateto = $date->format('Y-m-d');
			}

			// Get Company/Pharmacy filter value
		if($req->company_id != null)
		{
			$company_id = $req->company_id; 
		}
		// get all pharmacy list
		$all_pharmacy_list = User::all();
		if($company_id>0)
		{
			$all_pharmacy = User::all()->where('website_id','=',$company_id);
		}
		else
		{
			$all_pharmacy = User::all();
		}
		
		$data['all_pharmacy'] = $all_pharmacy_list;
			$newarray = array();
			foreach ($all_pharmacy as $row) {
			$this->get_connection($row->website_id);
			// $get_audit = Patient_Model::all();

			// $get_audit = Patient_Model::all();
			if($req->excludeweeks>0)
			{
				$Includedays = $req->excludeweeks * 7;
$includepickupdate = Carbon::parse($date->format('Y-m-d'))->addDays(-$Includedays);
				$get_audit = PickUp::where('pick_ups.deleted_at', NULL)
				->select('pick_ups.id','pick_ups.created_at','patients.id','patients.first_name','patients.last_name',
				'patients.facilities_id','facilities.name','patients.dob')
				->leftjoin('patients', 'pick_ups.patient_id', '=', 'patients.id')
				->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
				->where('patients.deleted_at', NULL)
				// ->where('pick_ups.created_at', '<', $nextpickupdate)
				->whereBetween('pick_ups.created_at', [$includepickupdate, $date->format('Y-m-d')])
				->groupBy('patients.id')
				->get();
			}
			else
			{
				$get_audit = PickUp::where('pick_ups.deleted_at', NULL)
				->select('pick_ups.id','pick_ups.created_at','patients.id','patients.first_name','patients.last_name',
				'patients.facilities_id','facilities.name','patients.dob')
				->leftjoin('patients', 'pick_ups.patient_id', '=', 'patients.id')
				->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
				->where('patients.deleted_at', NULL)
				->whereBetween('pick_ups.created_at', [$req->datefrom, $req->dateto])
				->groupBy('patients.id')
				->get();
			}


			foreach ($get_audit as $col) {
				$Patientlocations = PatientLocation::where('patient_id', $col->id)->orderBy('created_at', 'desc')->first();
				$col->pharmacy = $row->company_name;
				if (!empty($Patientlocations)) {
					$col->locations = $Patientlocations->locations;
				}
				// last pickup detail patient
				if($req->excludeweeks>0)
				{
					$Patientlastpickup = Pickup::where('patient_id', $col->id)
					->where('created_at',"<", $nextpickupdate)->orderBy('created_at', 'desc')->first();					
				}
				else
				{
					$Patientlastpickup = Pickup::where('patient_id', $col->id)
					->whereBetween('created_at', [$req->datefrom, $req->dateto])->orderBy('created_at', 'desc')->first();					
				}
	
				if (!empty($Patientlastpickup))
				 {
					$col->lastpickupdate = $Patientlastpickup->created_at;
					$col->noofpickuppacks = $Patientlastpickup->no_of_weeks;
				}
	
				// last checked detail patient
				if($req->excludeweeks>0)
				{
					$Patientlastchecked = Checking::where('patient_id', $col->id)
					->where('created_at',"<", $nextpickupdate)->orderBy('created_at', 'desc')->first();
				}
				else
				{
					$Patientlastchecked = Checking::where('patient_id', $col->id)
					->whereBetween('created_at', [$req->datefrom, $req->dateto])->orderBy('created_at', 'desc')->first();
				}
				
				if (!empty($Patientlastchecked))
				 {
					$col->lastcheckeddate = $Patientlastchecked->created_at;
					$col->noofcheckedpacks = $Patientlastchecked->no_of_weeks;
				}
	
				//last audit detail patient
				if($req->excludeweeks>0)
				{
					$Patientlastaudit = Audit::where('patient_id', $col->id)
					->where('created_at',"<", $nextpickupdate)->orderBy('created_at', 'desc')->first();			
				}
				else
				{
					$Patientlastaudit = Audit::where('patient_id', $col->id)
					->whereBetween('created_at', [$req->datefrom, $req->dateto])->orderBy('created_at', 'desc')->first();		
				}
				
				if (!empty($Patientlastaudit))
				 {
					$col->lastauditdate = $Patientlastaudit->created_at;
					$col->noofauditpacks = $Patientlastaudit->no_of_weeks;
				}
				$newarray[] = $col;
			}
			DB::disconnect('tenant');
		}
		
		
		$data['new_patients'] = $newarray;
	
        return view('admin.patient_report',['weeks'=>$weeks,
		'datefrom'=>$datefrom,'dateto'=>$dateto,'company_id'=>$company_id])->with($data); 

    }

	public function patient_filter_report(Request $req)
	{
		$weeks = 0;
		$date = Carbon::now();
		$datefrom;
		$dateto;
		$company_id = 0;
		$nextpickupdate;


		// Get weeks filter value and exclude patients with weeks filter pickup
		if($req->excludeweeks != null)
		{
			$weeks = $req->excludeweeks; 
			$date = Carbon::now();
			$nextpickupdate = Carbon::parse($date)->addDays(-$req->excludeweeks * 7);
			$nextpickupdate = $nextpickupdate->format('Y-m-d');	
		}
		
		
		
		// Get Company/Pharmacy filter value
		if($req->company_id != null)
		{
			$company_id = $req->company_id; 
		}
		// get all pharmacy list
		$all_pharmacy_list = User::all();
		if($company_id>0)
		{
			$all_pharmacy = User::all()->where('website_id','=',$company_id);
		}
		else
		{
			$all_pharmacy = User::all();
		}
		
		$data['all_pharmacy'] = $all_pharmacy_list;


		$newarray = array();
		foreach ($all_pharmacy as $row) {
		$this->get_connection($row->website_id);
		
		// $get_audit = Patient_Model::all();
		// $get_audit = Patient_Model::all();
		if($req->excludeweeks>0)
		{

		$Includedays = $req->excludeweeks * 7;
		$includepickupdate = Carbon::parse($date->format('Y-m-d'))->addDays(-$Includedays);

			$get_audit = PickUp::where('pick_ups.deleted_at', NULL)
			->select('pick_ups.id','pick_ups.created_at','patients.id','patients.first_name','patients.last_name',
			'patients.facilities_id','facilities.name','patients.dob')
			->join('patients', 'pick_ups.patient_id', '=', 'patients.id')
			->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
			->where('patients.deleted_at', NULL)
			// ->where('pick_ups.created_at', '<', $nextpickupdate)
			->whereBetween('pick_ups.created_at', [$includepickupdate, $date->format('Y-m-d')])
			->groupBy('patients.id')
			->get();
		}
		else
		{
			$get_audit = PickUp::where('pick_ups.deleted_at', NULL)
			->select('pick_ups.id','pick_ups.created_at','patients.id','patients.first_name','patients.last_name',
			'patients.facilities_id','facilities.name','patients.dob')
			->join('patients', 'pick_ups.patient_id', '=', 'patients.id')
			->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
			->where('patients.deleted_at', NULL)
			->whereBetween('pick_ups.created_at', [$req->datefrom, $req->dateto])
			->groupBy('patients.id')
			->get();
		}


		foreach ($get_audit as $col) 
		{
			$Patientlocations = PatientLocation::where('patient_id', $col->id)->orderBy('created_at', 'desc')->first();
			$col->pharmacy = $row->company_name;
			
			if (!empty($Patientlocations)) {
				$col->locations = $Patientlocations->locations;
				
			}
			// last pickup detail patient
			if($req->excludeweeks>0)
			{
				$Patientlastpickup = Pickup::where('patient_id', $col->id)
				->where('created_at',"<", $nextpickupdate)->orderBy('created_at', 'desc')->first();					
			}
			else
			{
				$Patientlastpickup = Pickup::where('patient_id', $col->id)
				->whereBetween('created_at', [$req->datefrom, $req->dateto])->orderBy('created_at', 'desc')->first();					
			}

			if (!empty($Patientlastpickup))
			 {
				$col->lastpickupdate = $Patientlastpickup->created_at;
				$col->noofpickuppacks = $Patientlastpickup->no_of_weeks;
			}

			// last checked detail patient
			if($req->excludeweeks>0)
			{
				$Patientlastchecked = Checking::where('patient_id', $col->id)
				->where('created_at',"<", $nextpickupdate)->orderBy('created_at', 'desc')->first();
			}
			else
			{
				$Patientlastchecked = Checking::where('patient_id', $col->id)
				->whereBetween('created_at', [$req->datefrom, $req->dateto])
				->orderBy('created_at', 'desc')->first();
			}
			
			if (!empty($Patientlastchecked))
			 {
				$col->lastcheckeddate = $Patientlastchecked->created_at;
				$col->noofcheckedpacks = $Patientlastchecked->no_of_weeks;
			}
			//last audit detail patient
			if($req->excludeweeks>0)
			{
				$Patientlastaudit = Audit::where('patient_id', $col->id)
				->where('created_at',"<", $nextpickupdate)->orderBy('created_at', 'desc')->first();			
			}
			else
			{
				$Patientlastaudit = Audit::where('patient_id', $col->id)
				->whereBetween('created_at', [$req->datefrom, $req->dateto])->orderBy('created_at', 'desc')->first();		
			}
			if (!empty($Patientlastaudit))
			 {
				$col->lastauditdate = $Patientlastaudit->created_at;
				$col->noofauditpacks = $Patientlastaudit->no_of_weeks;
			}
			$newarray[] = $col;
		}
		DB::disconnect('tenant');
	}

		$data['new_patients'] = $newarray;

		
		return view('admin.patient_report',['weeks'=>$req->excludeweeks,
		'datefrom'=>$req->datefrom,'dateto'=>$req->dateto,'company_id'=>$company_id])->with($data); 
	}

	public  function  due_patient_report(Request $req)
    {
		$weeks = 0;
		$date = Carbon::now(); 
		$datefrom;
		$dateto;
		$nextweekdate=$date->format('Y-m-d');
		$company_id = 0;
		$Isconditionalformatting = 0;
		$nextpickupdate;

		// Get weeks filter value and exclude patients with weeks filter pickup
		if($req->excludeweeks != null)
		{
			$weeks = $req->excludeweeks; 
			$date = Carbon::now();
			$nextpickupdate = Carbon::parse($date)->addDays(-$req->excludeweeks * 7);
			$nextpickupdate = $nextpickupdate->format('Y-m-d');	
		}
		if($req->datefrom !=null)
			{
				$datefrom = $req->datefrom;
			}
		else
			{
				$datefrom = $date->format('Y-m-d');
			}
		
		if($req->dateto !=null)
			{
				$dateto = $req->dateto;
			}
		else
			{
				$dateto = $date->format('Y-m-d');
			}

			// Get Company/Pharmacy filter value
		if($req->company_id != null)
		{
			$company_id = $req->company_id; 
		}
		// get all pharmacy list
		$all_pharmacy_list = User::all();
		if($company_id>0)
		{
			$all_pharmacy = User::all()->where('website_id','=',$company_id);
		}
		else
		{
			$all_pharmacy = User::all();
		}
		
		$data['all_pharmacy'] = $all_pharmacy_list;
			$newarray = array();
			foreach ($all_pharmacy as $row) {
			$this->get_connection($row->website_id);
			// $get_audit = Patient_Model::all();

			// $get_audit = Patient_Model::all();

			$default_cycle = 0 ;
			$default_cycle = AccessLevel::pluck('default_cycle')->first();

			
			if($req->excludeweeks>0)
			{
				
$Includedays = $req->excludeweeks * 7;
$includepickupdate = Carbon::parse($date->format('Y-m-d'))->addDays(-$Includedays);


				$get_audit = Patient_Model::where('patients.deleted_at', NULL)
				->select('pick_ups.id','pick_ups.created_at','patients.id','patients.first_name','patients.last_name',
				'patients.facilities_id','facilities.name','patients.dob')
				->leftjoin('pick_ups', 'pick_ups.patient_id', '=', 'patients.id')
				->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
				->where('patients.deleted_at', NULL)
				// ->where('pick_ups.created_at', '<', $nextpickupdate)

				->whereBetween('pick_ups.created_at', [$includepickupdate, $date->format('Y-m-d')])
				->groupBy('patients.id')
				->get();
			}
			else
			{
				$get_audit = Patient_Model::where('patients.deleted_at', NULL)
				->select('pick_ups.id','pick_ups.created_at','patients.id','patients.first_name','patients.last_name',
				'patients.facilities_id','facilities.name','patients.dob')
				->leftjoin('pick_ups', 'pick_ups.patient_id', '=', 'patients.id')
				->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
				->where('patients.deleted_at', NULL)
				->whereBetween('pick_ups.created_at', [$req->datefrom, $req->dateto])
				->groupBy('patients.id')
				->get();
			}

			foreach ($get_audit as $col) {
				$Patientlocations = PatientLocation::where('patient_id', $col->id)->orderBy('created_at', 'desc')->first();
				$col->pharmacy = $row->company_name;
				if (!empty($Patientlocations)) {
					$col->locations = $Patientlocations->locations;
				}
				// last pickup detail patient
				if($req->excludeweeks>0)
				{
					$Patientlastpickup = Pickup::where('patient_id', $col->id)
					->where('created_at',"<", $nextpickupdate)->orderBy('created_at', 'desc')->first();					
				}
				else
				{
					$Patientlastpickup = Pickup::where('patient_id', $col->id)
					->whereBetween('created_at', [$req->datefrom, $req->dateto])->orderBy('created_at', 'desc')->first();					
				}
	
				if (!empty($Patientlastpickup))
				 {
					$col->lastpickupdate = $Patientlastpickup->created_at;
					$col->noofpickuppacks = $Patientlastpickup->no_of_weeks;
					
					//Next due pickup date
					$nextdays = $default_cycle *  7 ;
					$nextduepickupdate = Carbon::parse($col->lastpickupdate)->addDays($nextdays);
					$nextduepickupdate = $nextduepickupdate->format('Y-m-d');	

					
					if($nextduepickupdate>$date )
					{
						$Isconditionalformatting=1;
					}
				}
	
				
				$newarray[] = $col;
			}
			DB::disconnect('tenant');
		}
		
		
		$data['new_patients'] = $newarray;
	
        return view('admin.due_patient_report',['weeks'=>$weeks,
		'datefrom'=>$datefrom,'dateto'=>$dateto,'company_id'=>$company_id,
		'Isconditionalformatting'=>$Isconditionalformatting,
		'nextweekdate'=>$nextweekdate
		])->with($data); 
		

    }


	public function due_patient_filter_report(Request $req)
	{
		$weeks = 0;
		$date = Carbon::now();
		$datefrom;
		$dateto;
		$company_id = 0;
		$excludeweeksdate;
		$Isconditionalformatting = 0;

		$nextduepickupdate;
		$nextweekdate = "";	

		$weeksneeded;
		$nextweekdate = $req->dateweeks;		
		
		// Get weeks filter value and exclude patients with weeks filter pickup
		if($req->excludeweeks != null)
		{
			$weeks = $req->excludeweeks; 
			$date = Carbon::now();
			$excludeweeksdate = Carbon::parse($date)->addDays(-$req->excludeweeks * 7);
			$excludeweeksdate = $excludeweeksdate->format('Y-m-d');	
		}

		if($req->datefrom !=null)
			{
				$datefrom = $req->datefrom;
			}
		else
			{
				$datefrom = $date->format('Y-m-d');
			}
		
		if($req->dateto !=null)
			{
				$dateto = $req->dateto;
			}
		else
			{
				$dateto = $date->format('Y-m-d');
			}

			// Get Company/Pharmacy filter value
		if($req->company_id != null)
		{
			$company_id = $req->company_id; 
		}
		// get all pharmacy list
		$all_pharmacy_list = User::all();
		if($company_id>0)
		{
			$all_pharmacy = User::all()->where('website_id','=',$company_id);
		}
		else
		{
			$all_pharmacy = User::all();
		}

	
		
		
		$data['all_pharmacy'] = $all_pharmacy_list;
			$newarray = array();
			foreach ($all_pharmacy as $row) {
			$this->get_connection($row->website_id);
			// $get_audit = Patient_Model::all();

			// $get_audit = Patient_Model::all();

			$default_cycle = 0 ;
			$default_cycle = AccessLevel::pluck('default_cycle')->first();

			
			
			
			if($req->excludeweeks>0)
			{

				$Includedays = $req->excludeweeks * 7;
$includepickupdate = Carbon::parse($date->format('Y-m-d'))->addDays(-$Includedays);




				$get_audit = Patient_Model::where('patients.deleted_at', NULL)
				->select('pick_ups.id','pick_ups.created_at','patients.id','patients.first_name','patients.last_name',
				'patients.facilities_id','facilities.name','patients.dob')
				->leftjoin('pick_ups', 'pick_ups.patient_id', '=', 'patients.id')
				->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
				->where('patients.deleted_at', NULL)
				// ->where('pick_ups.created_at', '<',$excludeweeksdate)
				->whereBetween('pick_ups.created_at', [$includepickupdate, $date->format('Y-m-d')])
				->groupBy('patients.id')
				->get();
			}
			else
			{
				$get_audit = Patient_Model::where('patients.deleted_at', NULL)
				->select('pick_ups.id','pick_ups.created_at','patients.id','patients.first_name','patients.last_name',
				'patients.facilities_id','facilities.name','patients.dob')
				->leftjoin('pick_ups', 'pick_ups.patient_id', '=', 'patients.id')
				->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
				->where('patients.deleted_at', NULL)
				->whereBetween('pick_ups.created_at', [$req->datefrom, $req->dateto])
				->groupBy('patients.id')
				->get();
			}

			

			foreach ($get_audit as $col) 
			{
				$Patientlocations = PatientLocation::where('patient_id', $col->id)->orderBy('created_at', 'desc')->first();
				$col->pharmacy = $row->company_name;
				if (!empty($Patientlocations)) {
					$col->locations = $Patientlocations->locations;
				}
				// last pickup detail patient
				if($req->excludeweeks>0)
				{
					$Patientlastpickup = Pickup::where('patient_id', $col->id)
					//->where('created_at',"<", $nextpickupdate)
					->orderBy('created_at', 'desc')->first();					
				}
				else
				{
					$Patientlastpickup = Pickup::where('patient_id', $col->id)
					//->whereBetween('created_at', [$req->datefrom, $req->dateto])
					->orderBy('created_at', 'desc')->first();					
				}
	
				if (!empty($Patientlastpickup))
				 {
					$col->lastpickupdate = $Patientlastpickup->created_at;
					$col->noofpickuppacks = $Patientlastpickup->no_of_weeks;
					
					//Next due pickup date
					$nextdays = $default_cycle *  7 ;
					$nextduepickupdate = Carbon::parse($col->lastpickupdate)->addDays($nextdays);
					$nextduepickupdate = $nextduepickupdate->format('Y-m-d');	

					$col->nextduepickupdate = $nextduepickupdate;
					if($nextduepickupdate>$date)
					{
						$Isconditionalformatting=1;
					}
					$col->Isconditionalformatting =$Isconditionalformatting;
				}

				
				$col->medicalenough =$nextweekdate;
	
				$formatted_dt1=Carbon::parse($nextweekdate);

				$formatted_dt2=Carbon::parse($nextduepickupdate);
				
				$date_diff=$formatted_dt1->diffInDays($formatted_dt2);

				
				$col->weeksneeded = round($date_diff/7);
				$newarray[] = $col;
			}
			DB::disconnect('tenant');
		}

		$data['new_patients'] = $newarray;		
		return view('admin.due_patient_report',['weeks'=>$req->excludeweeks,
		'datefrom'=>$req->datefrom,'dateto'=>$req->dateto,'company_id'=>$company_id,
		'nextweekdate'=>$nextweekdate,
		'Isconditionalformatting'=>$Isconditionalformatting])->with($data); 
	}


	public function compliance_index_report(Request $req)
	{

		$weeks = 0;
		$date = Carbon::now();
		$datefrom;
		$dateto;
		$company_id = 0;
		$nextpickupdate;
		$Isconditionalformatting = 0;
		$backpickupdate;
		$isNeedreview=0;
		$Totalnumberofweekspickedup = 0;
		$ComplianceIndex = 0;


		// get 26 weeks back  date from current date(182 days)
		$backpickupdate = Carbon::parse($date)->addDays(-182);
		$backpickupdate = $backpickupdate->format('Y-m-d');
		
		
		// for needed review  get 10 weeks back  date from current date(70 days)
		$reviewupdate = Carbon::parse($date)->addDays(-70);
		$reviewupdate = $reviewupdate->format('Y-m-d');
		
		// Get weeks filter value and exclude patients with weeks filter pickup
		if($req->excludeweeks != null)
		{
			$weeks = $req->excludeweeks; 
			$nextpickupdate = Carbon::parse($date)->addDays(-$req->excludeweeks * 7);
			$nextpickupdate = $nextpickupdate->format('Y-m-d');	
		}

		//set date from and date to filter values
		if($req->datefrom !=null)
			{
				$datefrom = $req->datefrom;
			}
		else
			{
				$datefrom = $date->format('Y-m-d');
			}
		
		if($req->dateto !=null)
			{
				$dateto = $req->dateto;
			}
		else
			{
				$dateto = $date->format('Y-m-d');
			}

		// Get Company/Pharmacy filter value from dropdown
		if($req->company_id != null)
		{
			$company_id = $req->company_id; 
		}

		// get all pharmacy list
		$all_pharmacy_list = User::all();
		if($company_id>0)
		{
			$all_pharmacy = User::all()->where('website_id','=',$company_id);
		}
		else
		{
			$all_pharmacy = User::all();
		}

		
		//format date which is 182 days back (26 weeks back date)
		$formatted_dt1=Carbon::parse($backpickupdate);
			
		$data['all_pharmacy'] = $all_pharmacy_list;
		$newarray = array();
		
		// loop through all pharmacies to get pickup
		foreach ($all_pharmacy as $row)
			{
			$this->get_connection($row->website_id);
			$default_cycle = 0 ;
			$default_cycle = AccessLevel::pluck('default_cycle')->first();
				
			if($req->excludeweeks>0)
			{
				$get_audit = Patient_Model::where('patients.deleted_at', NULL)
				->select('pick_ups.id','pick_ups.created_at','patients.id','patients.first_name','patients.last_name',
				'patients.facilities_id','facilities.name','patients.dob')
				->leftjoin('pick_ups', 'pick_ups.patient_id', '=', 'patients.id')
				->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
				->where('patients.deleted_at', NULL)
				// ->where('pick_ups.created_at', '<', $nextpickupdate)
				->groupBy('patients.id')
				->get();
			}
			else
			{
				
				
			$get_audit = PickUp::where('pick_ups.deleted_at', NULL)
			->select('pick_ups.id','pick_ups.created_at','patients.id','patients.first_name','patients.last_name',
			'patients.facilities_id','facilities.name','patients.dob')
			->join('patients', 'pick_ups.patient_id', '=', 'patients.id')
			->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
			->where('patients.deleted_at', NULL)
			
			//->where('patients.id','=', 7)
			// ->whereBetween('pick_ups.created_at', [$req->datefrom, $req->dateto])
			->groupBy('patients.id')
			->get();
			}

			foreach ($get_audit as $col) 
			{
				$Patientlocations = PatientLocation::where('patient_id', $col->id)->orderBy('created_at', 'desc')->first();
				$col->pharmacy = $row->company_name;

				if (!empty($Patientlocations)) 
				{
					$col->locations = $Patientlocations->locations;
				}

				// last pickup detail patient
				// last pickup detail patient
				if($req->excludeweeks>0)
				{
					$Patientlastpickup = Pickup::where('patient_id', $col->id)
					//->where('created_at',"<", $backpickupdate)
					->orderBy('created_at', 'desc')->first();					
				}
				else
				{
					$Patientlastpickup = Pickup::where('patient_id', $col->id)
					// ->whereBetween('created_at', [$req->datefrom, $req->dateto]
					//->where('created_at',"<", $backpickupdate)
					->orderBy('created_at', 'desc')->first();					
				}					
	
				if (!empty($Patientlastpickup))
				 {
					$col->lastpickupdate = $Patientlastpickup->created_at;
					$col->noofpickuppacks = $Patientlastpickup->no_of_weeks;
					
					// Condition No#2 for Next due pickup date
					$nextdays = 	$col->noofpickuppacks *  7 ;
					$nextduepickupdate = Carbon::parse($col->lastpickupdate)->addDays($nextdays);
					$nextduepickupdate = $nextduepickupdate->format('Y-m-d');	

					if($nextduepickupdate > $date )
					{
						$Isconditionalformatting=1;
						$col->nextduepickupdate = $nextduepickupdate;
						
					}
					else
					{
						
						
						$col->nextduepickupdate = $date;
					}

							
					
					//Condition No#3 3- 
					//if the Corrected "Due pickup date" is longer than 10 weeks ago i.e.
					// 70 days ---> Report as "Need Review?"
					$interval = 0;
					$interval_dt1=Carbon::parse($col->nextduepickupdate);
					$interval_dt2=Carbon::parse($Patientlastpickup->created_at);
					$interval=$interval_dt1->diffInDays($interval_dt2);
	
					
					if($interval ==70)
					{
						$isNeedreview = "Need Review?";
					}
					
					//4- (Corrected Due Date  -- 1 st pick up date) / 7 => # Weeks (X),
					//round up the resulted figure. e.g. 6.7 to 7
					$firstpickupdate = $col->lastpickupdate;
					$correctedduepickupdate = $nextduepickupdate;

					//*$Totalnumberofweekspickedup =  $Patientlastpickup->created_at->diff(Carbon::parse($col->lastpickupdate)->addDays($nextdays));
					$Totalnumberofdayspickedup = 0;
					$weekspickup_dt1 = Carbon::parse($nextduepickupdate);
					$weekspickup_dt2 = Carbon::parse($col->nextduepickupdate);
					$Totalnumberofdayspickedup = $weekspickup_dt1->diffInDays($weekspickup_dt2);
					
					
					$Totalnumberofweekspickedup = round($Totalnumberofdayspickedup/7);
					
					
					$ComplianceIndex =0;
					
					if($Totalnumberofweekspickedup>0)
					{
						$noofpaks = $col->noofpickuppacks + 1;
						$ComplianceIndex = ($noofpaks /$Totalnumberofweekspickedup) * 100;
					}
					

						
					if($ComplianceIndex>100)
					{
						$ComplianceIndex = 100;
					}
					$col->ComplianceIndex = round($ComplianceIndex);
 					$col->isNeedreview=$isNeedreview;
				}
				
				$newarray[] = $col;
			}
			DB::disconnect('tenant');
		}

		$data['new_patients'] = $newarray;

		
		return view('admin.compliance_index_report',['weeks'=>$req->excludeweeks,
		'datefrom'=>$req->datefrom,'dateto'=>$req->dateto,'company_id'=>$company_id])->with($data); 
	}



	public function compliance_index_filter_report(Request $req)
	{
		
		$weeks = 0;
		$date = Carbon::now();
		$datefrom;
		$dateto;
		$company_id = 0;
		$nextpickupdate;
		$Isconditionalformatting = 0;


		$nextpickupdate;
		$backpickupdate;

		$isNeedreview = 0;
		$Totalnumberofweekspickedup = 0;
		$ComplianceIndex = 0;

		// get 26 weeks back  date from current date(182 days)
		$backpickupdate = Carbon::parse($date)->addDays(-182);
		$backpickupdate = $backpickupdate->format('Y-m-d');
		
		
		// Get weeks filter value and exclude patients with weeks filter pickup
		if($req->excludeweeks != null)
		{
			$weeks = $req->excludeweeks; 
			$nextpickupdate = Carbon::parse($date)->addDays(-$req->excludeweeks * 7);
			$nextpickupdate = $nextpickupdate->format('Y-m-d');	
		}

		if($req->datefrom !=null)
			{
				$datefrom = $req->datefrom;
			}
		else
			{
				$datefrom = $date->format('Y-m-d');
			}
		
		if($req->dateto !=null)
			{
				$dateto = $req->dateto;
			}
		else
			{
				$dateto = $date->format('Y-m-d');
			}

			// Get Company/Pharmacy filter value
		if($req->company_id != null)
		{
			$company_id = $req->company_id; 
		}
		// get all pharmacy list
		$all_pharmacy_list = User::all();
		if($company_id>0)
		{
			$all_pharmacy = User::all()->where('website_id','=',$company_id);
		}
		else
		{
			$all_pharmacy = User::all();
		}
			
		$data['all_pharmacy'] = $all_pharmacy_list;
			$newarray = array();
			foreach ($all_pharmacy as $row) {
			$this->get_connection($row->website_id);
			// $get_audit = Patient_Model::all();

			// $get_audit = Patient_Model::all();

			$default_cycle = 0 ;
			$default_cycle = AccessLevel::pluck('default_cycle')->first();

			if($req->excludeweeks>0)
			{
				$get_audit = Patient_Model::where('patients.deleted_at', NULL)
				->select('pick_ups.id','pick_ups.created_at','patients.id','patients.first_name','patients.last_name',
				'patients.facilities_id','facilities.name','patients.dob')
				->leftjoin('pick_ups', 'pick_ups.patient_id', '=', 'patients.id')
				->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
				->where('patients.deleted_at', NULL)
				// ->where('pick_ups.created_at', '<', $nextpickupdate)
				->groupBy('patients.id')
				->get();
			}
			else
			{
				
				
			$get_audit = PickUp::where('pick_ups.deleted_at', NULL)
			->select('pick_ups.id','pick_ups.created_at','patients.id','patients.first_name','patients.last_name',
			'patients.facilities_id','facilities.name','patients.dob')
			->join('patients', 'pick_ups.patient_id', '=', 'patients.id')
			->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
			->where('patients.deleted_at', NULL)
			
			//->where('patients.id','=', 7)
			// ->whereBetween('pick_ups.created_at', [$req->datefrom, $req->dateto])
			->groupBy('patients.id')
			->get();
			}

			foreach ($get_audit as $col) 
			{
				$Patientlocations = PatientLocation::where('patient_id', $col->id)->orderBy('created_at', 'desc')->first();
				$col->pharmacy = $row->company_name;

				if (!empty($Patientlocations)) 
				{
					$col->locations = $Patientlocations->locations;
				}

				// last pickup detail patient
				if($req->excludeweeks>0)
				{
					$Patientlastpickup = Pickup::where('patient_id', $col->id)
					//->where('created_at',"<", $backpickupdate)
					->orderBy('created_at', 'desc')->first();					
				}
				else
				{
					$Patientlastpickup = Pickup::where('patient_id', $col->id)
					// ->whereBetween('created_at', [$req->datefrom, $req->dateto]
					//->where('created_at',"<", $backpickupdate)
					->orderBy('created_at', 'desc')->first();					
				}
	
				if (!empty($Patientlastpickup))
				 {
					$col->lastpickupdate = $Patientlastpickup->created_at;
					$col->noofpickuppacks = $Patientlastpickup->no_of_weeks;
					
					// Condition No#2 for Next due pickup date
					$nextdays = 	$col->noofpickuppacks *  7 ;
					$nextduepickupdate = Carbon::parse($col->lastpickupdate)->addDays($nextdays);
					$nextduepickupdate = $nextduepickupdate->format('Y-m-d');	

					if($nextduepickupdate > $date )
					{
						$Isconditionalformatting=1;
						$col->nextduepickupdate = $nextduepickupdate;
					}
					else
					{
						$col->nextduepickupdate = $date;
					}

					
					//Condition No#3 3- 
					//if the Corrected "Due pickup date" is longer than 10 weeks ago i.e.
					// 70 days ---> Report as "Need Review?"
					
					//*$interval = $col->nextduepickupdate->diff($date);
					
					$interval = 0;
					$interval_dt1=Carbon::parse($col->nextduepickupdate);
					$interval_dt2=Carbon::parse($Patientlastpickup->created_at);
					$interval=$interval_dt1->diffInDays($interval_dt2);


					if($interval==70)
					{
						$isNeedreview = 1;
					}
					

					//4- (Corrected Due Date  -- 1 st pick up date) / 7 => # Weeks (X),
					//round up the resulted figure. e.g. 6.7 to 7
					$firstpickupdate = $col->lastpickupdate;
					$correctedduepickupdate = $nextduepickupdate;

					//*$Totalnumberofweekspickedup =  $Patientlastpickup->created_at->diff(Carbon::parse($col->lastpickupdate)->addDays($nextdays));
					$Totalnumberofdayspickedup = 0;
					$weekspickup_dt1 = Carbon::parse($nextduepickupdate);
					$weekspickup_dt2 = Carbon::parse($col->nextduepickupdate);
					$Totalnumberofdayspickedup = $weekspickup_dt1->diffInDays($weekspickup_dt2);
					
					
					$Totalnumberofweekspickedup = round($Totalnumberofdayspickedup/7);
					
					
					$ComplianceIndex =0;
					
					if($Totalnumberofweekspickedup>0)
					{
						$noofpaks = $col->noofpickuppacks + 1;
						$ComplianceIndex = ($noofpaks /$Totalnumberofweekspickedup) * 100;
					}
					
					
						
						
					if($ComplianceIndex>100)
					{
						$ComplianceIndex = 100;
					}
					
					$col->ComplianceIndex = round($ComplianceIndex);
 					$col->isNeedreview=$isNeedreview;

 				}
	
				
				$newarray[] = $col;
			}
			DB::disconnect('tenant');
		}

		$data['new_patients'] = $newarray;

		return view('admin.compliance_index_report',['weeks'=>$req->excludeweeks,
		'datefrom'=>$req->datefrom,'dateto'=>$req->dateto,'company_id'=>$company_id,
		'Isconditionalformatting'=>$Isconditionalformatting])->with($data); 
	}
	public function patients() 
	{
		$data['all_pharmacies'] = User::all();
		$data['all_facilities'] = Facility::all();
		$data['all_Location'] = Location::get();
		return view('admin.patients')->with($data);
	}

	public function autocomplete(Request $request)
    {
    // $data['all_pharmacies'] = User::all();

		$data = User::select("name","company_name","website_id")
		->where("company_name","LIKE","%{$request->input('prefix')}%")
		->get();
   
        return response()->json($data);
    }

	public function bulk_import() {
		return view('admin.bulk_import');
	} 
	public function save_patient(Request $request) 
	{

		


		$validate_array = array(
			'company_name' => 'required|numeric|min:1',
			'first_name' => 'required|string|max:255',
			'last_name' => 'required|string|max:255',
			'dob' => 'required|date_format:d/m/Y|before:tomorrow',
			/*'phone_number' => 'required|min:10|max:10',
			*/'facility' => 'required|string|max:255',
			/*'mobile_no' => 'min:8|max:10',*/
		);
		$insert_data = array(
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'dob' => Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
			'address' => $request->address,
			'phone_number' => $request->phone_number,
			'facilities_id' => $request->facility,
			'location' => isset($request->location) ? implode(',', $request->location) : '',
		);

		$location_data = array('locations' => isset($request->location) ? implode(',', $request->location) : '');

		if (isset($request->up_delivered) && $request->up_delivered == 'on') {
			$insert_data['text_when_picked_up_deliver'] = 1;
		} else {
			$insert_data['text_when_picked_up_deliver'] = isset($request->mobile_no) ? '1' : null;
		}

		if (isset($request->same_as_above) && $request->same_as_above == 'on')
		 {
			$insert_data['mobile_no'] = $request->mobile_no;
		} else {
			$insert_data['mobile_no'] = $request->phone_number;
		}

		$insert_data['website_id'] = '1';
		if (!empty($request->session()->get('admin')))
		 {
			$insert_data['website_id'] = $request->company_name;
			$insert_data['created_by'] = '-' . $request->session()->get('admin')['id'];
			$get_user = User::get_by_column('website_id', $request->company_name);
			$validate_array['company_name'] = 'required';
			//config(['database.connections.tenant.database' => 'packweb']);

			config(['database.connections.tenant.database' => $get_user[0]->host_name]);
			DB::purge('tenant');
			DB::reconnect('tenant');

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
		// $validator = $request->validate($validate_array);
		$custommessage = array(
			'phone_number.required' => 'The Mobile Number field is required.',
		);
		$validator = \Validator::make($request->all(), $validate_array, $custommessage);
		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()->all()]);
		}
		
		$save_res = Patient_Model::create($insert_data);

		
		//    Patient Location
		$location_data['patient_id'] = $save_res->id;
		$location_data['action_by'] = $request->session()->get('admin')->id;
		
		PatientLocation::insert_data($location_data);
		//    End
		EventsLog::create([
			'website_id' => $request->company_name,
			'action_by' => '-' . $request->session()->get('admin')->id,
			'action' => 1,
			'action_detail' => 'Patient',
			'comment' => 'Create Patient',
			'patient_id' => $save_res->id,
			'ip_address' => $request->ip(),
			'type' => 'SuperAdmin',
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);

		DB::disconnect('tenant');

		// Send sms 
			// Configure HTTP basic authorization: BasicAuth
			$config = ClickSend\Configuration::getDefaultConfiguration()
			->setUsername('amr_eid@msn.com')
			->setPassword('5N^u#SLo2!w43SLk');

			$apiInstance = new ClickSend\Api\SMSApi(new \GuzzleHttp\Client(),$config);
			$msg = new \ClickSend\Model\SmsMessage();
			$msg->setBody("Hi Welcome to packpeak"); 

			if($request->same_as_above <>"")
			{
				$msg->setTo('+91'.$request->mobile_no);
			}
			else
			{
				$msg->setTo('+91'.$request->phone_number);
			}
			// $msg->setTo("+923234774241");
			$msg->setSource("+61422222222");


			// \ClickSend\Model\SmsMessageCollection | SmsMessageCollection model
			$sms_messages = new \ClickSend\Model\SmsMessageCollection(); 
			$sms_messages->setMessages([$msg]);

			try
			{
			$result = $apiInstance->smsSendPost($sms_messages);
			print_r($result);
			} catch (Exception $e) {
			echo 'Exception when calling SMSApi->smsSendPost: ', $e->getMessage(), PHP_EOL;
			}
		

		return response()->json(['success' => 1, 'errors' => ""]);

		// $save_res=Patient_Model::insert_data($insert_data);
		// return redirect()->back()->with(["msg"=>'<div class="alert alert-success">Patient <strong>'.$request->first_name.' '.$request->last_name.'</strong> Added Successfully.</div>']);

	}

	 

	public function get_connection($website_id) {
		$get_user = User::get_by_column('website_id', $website_id);
		config(['database.connections.tenant.database' => $get_user[0]->host_name]);
		DB::purge('tenant');
		DB::reconnect('tenant');
		DB::disconnect('tenant');
	}

	public function get_patients_by_website_id(Request $request) {
		$this->get_connection($request->website_id);
		
		// $data['all_patients'] = Patient_Model::all();
		// $data['all_patients'] = Patient_Model::all();

		$data['all_patients'] = Patient_Model::get_by_where(array('is_archive' => 0));

		$data['mode'] = 'all_patients';
		
		return view('admin.ajax')->with($data);
	}

	/* get  DOB of Patients  */
	public function get_patient_dob(Request $request) {
		$this->get_connection($request->website_id);
		$patients = Patient_Model::where('id', $request->patient_id)->select('dob')->first();
		// return  $patients->dob;
		return Response::json(array('success' => true, 'dob' => $patients->dob), 200);
	}
	
	public function new_patients_report() {

		$all_pharmacy = User::all();
	
		
		$newarray = array();
		foreach ($all_pharmacy as $row) {
			$this->get_connection($row->website_id);
			
			$get_audit = Patient_Model::all();
			
			foreach ($get_audit as $col) {
				$Patientlocations = PatientLocation::where('patient_id', $col->id)->orderBy('created_at', 'desc')->first();
				$col->pharmacy = $row->company_name;
				if (!empty($Patientlocations)) {
					$col->locations = $Patientlocations->locations;
				}

				$newarray[] = $col;
			}
			DB::disconnect('tenant');
		}
		
		
		$data['new_patients'] = $newarray; //print_r($newarray[0]->facility); die;
		return view('admin.new_patients_report')->with($data);
	}

	public function archived_new_patients_report() {
		$all_pharmacy = User::all();
		$newarray = array();
		foreach ($all_pharmacy as $row) {
			$this->get_connection($row->website_id);
			$get_audit = Patient_Model::get_archived();
			
			
			
			foreach ($get_audit as $col) {
				$Patientlocations = PatientLocation::where('patient_id', $col->id)->orderBy('created_at', 'desc')->first();
				$col->pharmacy = $row->company_name;
				if (!empty($Patientlocations)) {
					$col->locations = $Patientlocations->locations;
				}

				$newarray[] = $col;
			}
	
			DB::disconnect('tenant');
		}

		
		$data['new_patients'] = $newarray; //print_r($newarray[0]->facility); die;
		

		return view('admin.archived_new_patients_report')->with($data);
	}

	public function email_new_patients_report( Request $request) {
		
		// dd($request);
		$email = /*$request->email;*/'naeemahsan08@gmail.com';
		$start_date = $request->start_date  ;
		$end_date 	= $request->end_date  ;
		
		$details['name'] = "PackPeak";
		$details['report_name'] = "Patients Report";
		$details['date_range'] = "$start_date To $end_date";
		$details['url'] = "https://packpeak.co.au/pickupReport/$start_date/$end_date";
		 // dd($details);
		\Mail::to($request->email)->send(new \App\Mail\AdminReportsEmail($details));
		return redirect('admin/new_patients_report')->with(["msg" => '<div class="alert alert-success"> <strong>  Email Sent. </strong></div>']);
		 
		 
	}
	/* Edit Patient  */
	public function edit_patient(Request $request) {
		$data['all_pharmacies'] = User::all();

		$data['all_Location'] = Location::get();
		$this->get_connection($request->website_id);
		$data['all_facilities'] = TenantFacilityModel::all();
		$data['patient'] = Patient_Model::get_by_where(array('id' => $request->row_id, 'deleted_at' => NULL));
		$data['patient_location'] = PatientLocation::where(array('patient_id' => $request->row_id))->get();
		DB::disconnect('tenant');
		return view('admin.edit_patient')->with($data);
	}

	public function update_patient(Request $request) {
		$iseditform =0;

	if($request-> ieseditform !=null)
	{
		$iseditform = $request-> ieseditform;
	}


		$duplicate = "no";
		if ($request->company_name && $request->first_name && $request->last_name && $request->dob) {

			$this->get_connection($request->company_name);
			$getPatient = Patient_Model::where('first_name', $request->first_name)
				->where('last_name', $request->last_name)
				->where('dob', Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'))
				->get();
			DB::disconnect('tenant');

			if (count($getPatient)) {
				$request->row_id = $getPatient[0]->id;
				$request->website_id = $getPatient[0]->website_id;
				$duplicate = "yes";
			} else {
			}
		} else {
			echo '401';
		}
		//duplicate overwrite end
		// dd($request->row_id);
		$this->get_connection($request->website_id); //  validation for Unique  Field

		
		$validate_array = array(
			'first_name' => 'required|string|max:255',
			'last_name' => 'required|string|max:255',
			'dob' => 'required|date_format:d/m/Y|before:tomorrow',
			// 'phone_number' => 'required|min:9|max:9',
			'facility' => 'required|string|max:255',

		);
		$update_data = array(
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'dob' => Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
			'address' => $request->address,
			'phone_number' => $request->phone_number,

			'location' => isset($request->location) ? implode(',', $request->location) : '',
		);

		if (isset($request->up_delivered) && $request->up_delivered == 'on') {
			$update_data['text_when_picked_up_deliver'] = 1;
		} else {
			$update_data['text_when_picked_up_deliver'] = isset($request->mobile_no) ? '1' : null;
		}

		if (isset($request->same_as_above) && $request->same_as_above == 'on') {
			$update_data['mobile_no'] = $request->mobile_no;
		} else {
			$update_data['mobile_no'] = $request->phone_number;
		}
		$custommessage = array(
			'phone_number.required' => 'The Mobile Number field is required.',
		);
		$validator = $request->validate($validate_array, $custommessage);

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
			$update_data['facilities_id'] = $facilityId;

		}
		Patient_Model::update_where(array('id' => $request->row_id, 'website_id' => $request->website_id), $update_data);
		// Paitent Locations
		$location_data['locations'] = $update_data['location'];
		$location_data['patient_id'] = $request->row_id;
		$location_data['action_by'] = $request->session()->get('admin')->id;
		PatientLocation::insert_data($location_data);
		// End
		EventsLog::create([
			'website_id' => $request->website_id,
			'action_by' => '-' . $request->session()->get('admin')->id,
			'action' => 2,
			'action_detail' => 'Patient',
			'comment' => 'Update Patient',
			'patient_id' => $request->row_id,
			'ip_address' => $request->ip(),
			'type' => 'SuperAdmin',
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		DB::disconnect('tenant');


		// Send sms 
			// Configure HTTP basic authorization: BasicAuth
			$config = ClickSend\Configuration::getDefaultConfiguration()
			->setUsername('amr_eid@msn.com')
			->setPassword('5N^u#SLo2!w43SLk');

			$apiInstance = new ClickSend\Api\SMSApi(new \GuzzleHttp\Client(),$config);
			$msg = new \ClickSend\Model\SmsMessage();
			$msg->setBody("Hi Welcome to packpeak"); 

			if($request->same_as_above <>"")
			{
				$msg->setTo('+61'.$request->mobile_no);
			}
			else
			{
				$msg->setTo('+61'.$request->phone_number);
			}

			//$msg->setTo("+923234774241");
			$msg->setSource("+61422222222");


			// \ClickSend\Model\SmsMessageCollection | SmsMessageCollection model
			$sms_messages = new \ClickSend\Model\SmsMessageCollection(); 
			$sms_messages->setMessages([$msg]);

			try
			{
			$result = $apiInstance->smsSendPost($sms_messages);
			// print_r($result);
			} catch (Exception $e) {
			echo 'Exception when calling SMSApi->smsSendPost: ', $e->getMessage(), PHP_EOL;
			return response()->json(['success' => 0, 'errors' => "Verify mobile number"]);
			}
			
		if($duplicate == "yes"){


			if($iseditform==1)
				{
					return redirect('admin/new_patients_report')->with(["msg" => '<div class="alert alert-success"> <strong>  Patient Report </strong> Updated Successfully.</div>']);			
				}
				else
				{
					return response()->json(['success' => 1, 'errors' => ""]);
				}
		}
		 return redirect('admin/new_patients_report')->with(["msg" => '<div class="alert alert-success"> <strong>  Patient Report </strong> Updated Successfully.</div>']);
	}

	/* Delete Return  */
	public function delete_patient(Request $request) {
		$this->get_connection($request->website_id);
		Patient_Model::update_where(array('id' => $request->row_id, 'website_id' => $request->website_id), array('deleted_by' => '-' . $request->session()->get('admin')['id']));
		Patient_Model::delete_record($request->row_id);
		EventsLog::create([
			'website_id' => $request->website_id,
			'action_by' => '-' . $request->session()->get('admin')->id,
			'action' => 3,
			'action_detail' => 'Patient',
			'comment' => 'Delete Patient',
			'patient_id' => $request->row_id,
			'ip_address' => $request->ip(),
			'type' => 'SuperAdmin',
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		DB::disconnect('tenant');
		echo '200';
	}

	public function soft_delete_patient(Request $request) {

		
		$this->get_connection($request->website_id);

		
		if($request->archivetypeid == 1 )
        {
			Patient_Model::update_where(array('id' => $request->row_id, 'website_id' => $request->website_id), array('deleted_by' => '-' . $request->session()->get('admin')['id'],'is_archive'=>'1'));
        }
        else if($request->archivetypeid == 0 )
        {
            
            Patient_Model::update_where(array('id' => $request->row_id, 'website_id' => $request->website_id), array('deleted_by' => '-' . $request->session()->get('admin')['id'],'is_archive'=>'0'));
        }
		
		
		// Patient_Model::soft_delete_record($request->row_id);
		
		EventsLog::create([
			'website_id' => $request->website_id,
			'action_by' => '-' . $request->session()->get('admin')->id,
			'action' => 3,
			'action_detail' => 'Patient',
			'comment' => 'Soft Delete Patient',
			'patient_id' => $request->row_id,
			'ip_address' => $request->ip(),
			'type' => 'SuperAdmin',
			'user_agent' => $request->userAgent(),
			'authenticated_by' => 'packnpeaks',
			'status' => 1,
		]);
		DB::disconnect('tenant');
		echo '200';
	}

	/*GET  Patient  Details */
	public function patient_info(Request $request) {
		//print_r($request->all());
		$this->get_connection($request->website_id);
		$data['patients'] = Patient_Model::get_by_where(array('website_id' => $request->website_id, 'id' => $request->row_id));
		$data['mode'] = 'patients_info';
		DB::disconnect('tenant');
		//print_r($data['patients']);die;
		return view('admin.ajax')->with($data);
	}

	/*  check Duplicate patient */
	public function checkduplicatePatient(Request $request) {
		//   print_r($request->all()); die;
		if ($request->company_name && $request->first_name && $request->last_name && $request->dob)
		// if ($request->company_name && $request->first_name && $request->last_name && $request->dob)
		 {

			$this->get_connection($request->company_name);
			$getPatient = Patient_Model::where('first_name', $request->first_name)
				->where('last_name', $request->last_name)
				->where('dob', Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'))
				->get();
			DB::disconnect('tenant');

			if (count($getPatient)) {
				echo '1'; //  records  exit
			} else {
				echo '0'; //  records not  exit
			}
		}
		 else {
			echo '401';
		}
	}

	public function get_parmacydata_by_website_id(Request $request) {
		if ($request->website_id) {
			$this->get_connection($request->website_id);
			$facility = TenantFacilityModel::get();
			$stores = Store::get();
			$patients = Patient_Model::get();
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


	public function exempted_patients() {

		
		
		$all_pharmacy = User::where('website_id','=',1)->get();
		// $all_pharmacy = User::all();
		
		$newarray = array();
		$allPatientsArray = array();
		foreach ($all_pharmacy as $row) {
			$this->get_connection($row->website_id);
			$get_audit = Patient_Model::all()->where('exempted', '1');
			$all = Patient_Model::all()->where('exempted', '0');
			foreach ($get_audit as $col) {
				$Patientlocations = PatientLocation::where('patient_id', $col->id)->orderBy('created_at', 'desc')->first();
				$col->pharmacy = $row->company_name;
				$col->website_id = $row->website_id;
				if (!empty($Patientlocations)) {
					$col->locations = $Patientlocations->locations;
				}

				$newarray[] = $col;
			}

		
			foreach ($all as $col) {
				$Patientlocations = PatientLocation::where('patient_id', $col->id)->orderBy('created_at', 'desc')->first();
				$col->pharmacy = $row->company_name;
				if (!empty($Patientlocations)) {
					$col->locations = $Patientlocations->locations;
				}

				$allPatientsArray[] = $col;
			}
			DB::disconnect('tenant');
		}

		
		
		$data['new_patients'] = $newarray; //print_r($newarray[0]->facility); die;
		$data['all_patients'] = $allPatientsArray;

		
		return view('admin.exempted_patients')->with($data);
	}

	public function add_exempted_patient(request $request) {
		
		
		
		$companyID = $request->company;
		$patientID = $request->patientID;

		$all_pharmacy = User::all()->where('website_id',$companyID);
		// $all_pharmacy = User::all()->where('company_name',$companyID);
		// echo json_encode($all_pharmacy);exit;
		foreach($all_pharmacy as $row)
		{
			$websiteID = $row->website_id;
		 
			$this->get_connection($websiteID);
			$patient = Patient_Model::find($patientID);
			
			// echo json_encode($patientID);exit;
			if($request->isexemption == 0)
			{
				$patient->exempted = 0 ;
			}
			else if($request->isexemption == 1)
			{
				$patient->exempted = 1 ;
			}
			
			$patient->save();

			 
			 
			DB::disconnect('tenant');
		}
		 
		if ($request->isexemption == 0)
		{
			echo '0';
		}
		else
		{
			echo '1';
		}
	
		
	} 

	

}
