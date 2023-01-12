<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use App\User;
use App\Models\Tenant\PickUp; 
use App\Models\Tenant\Checking;  
use App\Models\Tenant\PatientReturn; 
use App\Models\Tenant\Audit; 
use App\Models\Tenant\Patient; 
class Search extends Controller
{
    public function  index()
    {
    	$data['all_pharmacies']=User::all();
       return  view('admin.search')->with($data); 
    }

    /*Search  patient*/
    public  function  search_patient(Request  $request)
    {

        $this->get_connection($request->company_name); 
        $data['patient']=Patient::find($request->patient_name);
        $data['allPickup']=PickUp::get_by_where(array('patient_id' => $request->patient_name,'website_id'=>$request->company_name ));
        $data['allChecking']=Checking::get_by_where(array('patient_id' => $request->patient_name,'website_id'=>$request->company_name ));
        $data['allPatientReturn']=PatientReturn::get_by_where(array('patient_id' => $request->patient_name,'website_id'=>$request->company_name ));
        $data['allAudit']=Audit::get_by_where(array('patient_id' => $request->patient_name,'website_id'=>$request->company_name ));
        DB::disconnect('tenant');
        return view('admin.search_result')->with($data);
    }

/*Searched patient information PDF */
    public function  create_patient_details_pdf(Request  $request)
    {
      
        if($request->website_id && $request->row_id)
        {
                $this->get_connection($request->website_id); 
                $data['patient']=Patient::find($request->row_id);
                $data['allPickup']=PickUp::get_by_where(array('patient_id' => $request->row_id,'website_id'=>$request->website_id));
                $data['allChecking']=Checking::get_by_where(array('patient_id' => $request->row_id,'website_id'=>$request->website_id ));
                $data['allPatientReturn']=PatientReturn::get_by_where(array('patient_id' => $request->row_id,'website_id'=>$request->website_id ));
                $data['allAudit']=Audit::get_by_where(array('patient_id' => $request->row_id,'website_id'=>$request->website_id ));
                DB::disconnect('tenant');
               if(!empty($data['patient']))
               {
                    //$data = ['title' => 'Welcome to ItSolutionStuff.com'];
                    $pdf = PDF::loadView('admin.search_result_pdf', $data);
                    return $pdf->download('Patient_Details.pdf');
                    // return  view('admin.search_result_pdf', $data);
               }
               else
               {
                  return  redirect('admin/search')->with(["msg"=>'<div class="alert alert-danger"> <strong> Patient Records </strong> Not Found.</div>']);
               }
               
        }
        else
        {
            return  redirect('admin/search')->with(["msg"=>'<div class="alert alert-danger"> <strong> Patient Records </strong> Not Found.</div>']);
        }
    }

    /*Searched patient information excel */
    public function  create_patient_details_excel(Request  $request)
    {
        
    }
}
