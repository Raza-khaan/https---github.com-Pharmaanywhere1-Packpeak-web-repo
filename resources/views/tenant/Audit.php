<?php

namespace App\Http\Controllers\Tenant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Audit as Audit_Model;
use App\Models\Tenant\Facility;
use App\Models\Tenant\Store;
use App\Models\Tenant\Patient;
use App\Models\Tenant\EventsLog;
use DB;

class Audit extends Controller
{
    protected $views='';
    public function __construct(){
        $this->views='tenant';
        $host=explode('.',request()->getHttpHost());
        config(['database.connections.tenant.database' => $host[0]]);
        DB::purge('tenant');
        DB::reconnect('tenant');
        DB::disconnect('tenant'); 
    }

    public function  audits(Request $request)
    {
        if($request->form17=='1'){
        $data['facilities']=Store::get();
        $data['patients']=Patient::get();
        return view($this->views.'.audits',$data);
        }
        else{
            return redirect('dashboard')->with(["msg"=>'<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
        }
    }


   

    public  function  all_audits(Request $request)
    { 
        if($request->form18=='1'){
        $data=[];
        $data['audits']=Audit_Model::where('is_archive','=',0)->get();
        return  view($this->views.'.all_audits',$data); 
        }
        else{
            return redirect('dashboard')->with(["msg"=>'<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
        }
    }


    public function archive_all_audits(Request $request) {
		
		if ($request->form10 == '1') {
			$data = array();
			$data['audits'] = Audit_Model::where('is_archive','=',1)->get();
			//return $data['checkings'];
			return view($this->views . '.archive_all_audits')->with($data);
		} else {
			return redirect('dashboard')->with(["msg" => '<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
		}
	}


/* Soft Delete Return  */
public function  softarchive(Request $request)
{

	$getdata=Audit_Model::where('id','=',$request->id)->first();
	$getdata->is_archive = 1;
	$getdata->save();
	
	$patient_name = Patient::where('id','=',$getdata->patient_id)->first();
	$name = $patient_name->first_name .' '. $patient_name->last_name;
 
	return redirect()->back()->with(["msg" => '<div class="alert alert-success">Patient Audit (<strong>' . $name . '</strong>) archived Successfully.</div>']);

 }


public function  softunarchive(Request $request)
{

   $getdata=Audit_Model::where('id','=',$request->id)->first();
   $getdata->is_archive = 0;
   $getdata->save();
   
   $patient_name = Patient::where('id','=',$getdata->patient_id)->first();
   $name = $patient_name->first_name .' '. $patient_name->last_name;

	 return redirect()->back()->with(["msg" => '<div class="alert alert-success">Patient Audit(<strong>' . $name . '</strong>) unarchived Successfully.</div>']);


}

    /*save the return data */
    public  function save_audits(Request $request)
    {    
        $validate_array=array(
            'patient_id'          => 'required|numeric|min:1',
            'no_of_weeks'         => 'required|numeric|min:1',
            'store'               => 'required|numeric|min:1',
            // 'staff_initials'      => 'required|string|max:255'
        ); 
        $insert_data=array(
            'patient_id'=>$request->patient_id,
            'no_of_weeks'=>$request->no_of_weeks,
            'store'=>$request->store,
            'staff_initials'=>$request->staff_initials,
        ); 
        if(!empty($request->session()->get('phrmacy')))
        {
            $insert_data['website_id']=$request->session()->get('phrmacy')->website_id; 
            $insert_data['created_by']='-'.$request->session()->get('phrmacy')->id;
        }
        $validator = $request->validate($validate_array);
        if(isset($request->store_others_desc)){
            $insert_data['store_others_desc']=$request->store_others_desc;
        }
        EventsLog::create([
            'website_id' => $request->session()->get('phrmacy')->website_id,
            'action_by' => $request->session()->get('phrmacy')->id,
            'action' => 1,
            'action_detail' => 'Audit',
            'comment' => 'Create Audit',
            'patient_id' => $request->patient_id,
            'ip_address' => $request->ip(),
            'type' => $request->session()->get('phrmacy')->roll_type,
            'user_agent' => $request->userAgent(),
            'authenticated_by' => 'packnpeaks',
            'status' => 1
          ]);
        $save_res=Audit_Model::insert_data($insert_data);
        return redirect()->back()->with(["msg"=>'<div class="alert alert-success">Patient <strong>Audits </strong> Added Successfully.</div>']);
    }

    
    /* delete Return  */
    public  function auditsDelete(Request $request,$tenantName,$id)
    {
        $delete=Audit_Model::find($id);
        if(!$delete){
            return redirect()->back()->with(["msg"=>'<div class="alert alert-danger">Patient id <strong>'.$id.'</strong> doesnot match in our records.</div>']);
        }

        $patient_name=$delete->patients->first_name.' '.$delete->patients->last_name;
        $delete->delete();
        EventsLog::create([
            'website_id' => $request->session()->get('phrmacy')->website_id,
            'action_by' => $request->session()->get('phrmacy')->id,
            'action' => 3,
            'action_detail' => 'Audit',
            'comment' => 'Delete Audit',
            'patient_id' => $delete->patient_id,
            'ip_address' => $request->ip(),
            'type' => $request->session()->get('phrmacy')->roll_type,
            'user_agent' => $request->userAgent(),
            'authenticated_by' => 'packnpeaks',
            'status' => 1
        ]);
        return redirect()->back()->with(["msg"=>'<div class="alert alert-danger">Audit of (<strong>'.$patient_name.'</strong>) deleted Successfully.</div>']);
    }

    /* edit Return  */
    public  function  auditsEdit(Request $request,$tenantName,$id)
    {       
        if($request->form18=='1'){
        $ob=Audit_Model::find($id);
        if(!$ob){
            return redirect()->back()->with(["msg"=>'<div class="alert alert-danger">Audits id <strong>'.$id.'</strong> doesnot match in our records.</div>']);
        }

        if ($request->isMethod('post')) {

            $validate_array=array(
                'patient_id'          => 'required|numeric|min:1',
                'no_of_weeks'         => 'required|numeric|min:1',
                'store'               => 'required|numeric|min:1',
                // 'staff_initials'      => 'required|string|max:255'
            ); 
    
            $insert_data=array(
                'patient_id'=>$request->patient_id,
                'no_of_weeks'=>$request->no_of_weeks,
                'store'=>$request->store,
                'staff_initials'=>$request->staff_initials,
            ); 
            if($request->store=='5'){
                $insert_data['store_others_desc']=$request->store_others_desc;
            }
            else{
                $insert_data['store_others_desc']='';
            }
            $validator = $request->validate($validate_array); 
            $ob->update($insert_data);
            EventsLog::create([
                'website_id' => $request->session()->get('phrmacy')->website_id,
                'action_by' => $request->session()->get('phrmacy')->id,
                'action' => 2,
                'action_detail' => 'Audit',
                'comment' => 'Update Audit',
                'patient_id' => $request->patient_id,
                'ip_address' => $request->ip(),
                'type' => $request->session()->get('phrmacy')->roll_type,
                'user_agent' => $request->userAgent(),
                'authenticated_by' => 'packnpeaks',
                'status' => 1
              ]);
            return redirect('all_audits')->with(["msg"=>'<div class="alert alert-success">Audit is <strong>Updated </strong> Successfully.</div>']);
        }

        $data=array();
        $data['facilities']=Store::get();
        $data['patients']=Patient::get();
        $data['audits']=$ob;
        // return $ob;
        return  view($this->views.'.auditsEdit',$data); 

        }
        else{
            return redirect('dashboard')->with(["msg"=>'<div class="alert alert-danger"> you don`t have  <strong>Access</strong> to this page .</div>']);
        }

    }
    
}
