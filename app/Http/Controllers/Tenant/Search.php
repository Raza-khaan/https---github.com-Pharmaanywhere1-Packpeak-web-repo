<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use App\User;
use App\Models\Tenant\Pickup; 
use App\Models\Tenant\Checking;  
use App\Models\Tenant\PatientReturn; 
use App\Models\Tenant\Audit; 
use App\Models\Tenant\Patient; 
use App\Models\Tenant\Company; 
class Search extends Controller
{
    public function  index()
    {
        $pharmacyid = session()->get('phrmacy')->website_id;
       

        $company = User::where('website_id','=',$pharmacyid)->first();
       
       if($company->subscription != 1){
        return  redirect('/dashboard')->with(["msg"=>'<div class="alert alert-danger"> <strong> Change to premium plans to search your custom reports </strong> .</div>']);
       }else{
            $data['patients']=Patient::get();
            return  view('tenant.search')->with($data); 
       }
    }

    /*Search  patient*/
    public  function  search_patient(Request  $request)
    {
        
        $validator = $request->validate([
         'patient_id'=>'required',
         'dob'=>'required'
        ]); 
        
        $data['patient']=Patient::find($request->patient_id);
        $data['allPickup']=Pickup::get_by_where(array('patient_id' => $request->patient_id));
        $data['allChecking']=Checking::get_by_where(array('patient_id' => $request->patient_id));
        $data['allPatientReturn']=PatientReturn::get_by_where(array('patient_id' => $request->patient_id));
        $data['allAudit']=Audit::get_by_where(array('patient_id' => $request->patient_id));
        
        return view('tenant.search_result')->with($data);
    }

/*Searched patient information PDF */
    public function  create_patient_details_pdf(Request  $request)
    {
      
        if($request->row_id)
        {
                $data['patient']=Patient::find($request->row_id);
                $data['allPickup']=PickUp::get_by_where(array('patient_id' => $request->row_id));
                $data['allChecking']=Checking::get_by_where(array('patient_id' => $request->row_id));
                $data['allPatientReturn']=PatientReturn::get_by_where(array('patient_id' => $request->row_id));
                $data['allAudit']=Audit::get_by_where(array('patient_id' => $request->row_id));
               if(!empty($data['patient']))
               {
                    //$data = ['title' => 'Welcome to ItSolutionStuff.com'];
                    $pdf = PDF::loadView('tenant.search_result_pdf', $data);
                    return $pdf->download('Patient_Details.pdf');
                    // return  view('admin.search_result_pdf', $data);
               }
               else
               {
                  return  redirect('search')->with(["msg"=>'<div class="alert alert-danger"> <strong> Patient Records </strong> Not Found.</div>']);
               }
               
        }
        else
        {
            return  redirect('search')->with(["msg"=>'<div class="alert alert-danger"> <strong> Patient Records </strong> Not Found.</div>']);
        }
    }

    
}
