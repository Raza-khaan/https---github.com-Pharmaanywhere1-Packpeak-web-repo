<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\User; 
use Illuminate\Http\Request;
use App\Models\Tenant\Audit as Audit_Model;
use App\Models\Admin\Facility;
use App\Models\Tenant\Patient as Patient_Model;
use App\Models\Admin\Store;
use App\Models\Tenant\Store as TenantStore;
use App\Models\Tenant\EventsLog; 

// use App\Models\Tenant\EventsLog; 
use DB,Config;

class Audit extends Near_Miss
{
    public function  audits()
    {
        $data['all_pharmacies']=User::all();
        $data['all_facilities']=Store::all();
        return view('admin.audits')->with($data);
    }
   /* Save Audit   */
    public  function  save_audit(Request $request)
   {
        $validate_array=array(
            'company_name'          => 'required|numeric|min:1',
            'patient_name'      => 'required|min:1',
            'no_of_weeks'         => 'required|numeric|min:1',
            'store'               => 'required|numeric|min:1',
            // 'staff_initials'      => 'required|string|max:255'
        ); 

        $patientIDS = ($request->patient_name);
        
        foreach ($patientIDS as $ipatient_name ) {

            $request->patient_name = $ipatient_name;
            $insert_data=array(
                'patient_id'=>$request->patient_name,
                'no_of_weeks'=>$request->no_of_weeks,
                'store'=>$request->store,
                'staff_initials'=>$request->staff_initials
            );
            if(isset($request->store) && $request->store=='5')
            {
                $insert_data['store_others_desc']=$request->other_store; 
                $validate_array['other_store']='required'; 
            }
            $insert_data['website_id']='1'; 
            if(!empty($request->session()->get('admin')))
            {
                $insert_data['website_id']=$request->company_name; 
                $insert_data['created_by']='-'.$request->session()->get('admin')['id'];
                $validate_array['company_name']='required'; 
            }
            $validator = $request->validate($validate_array);
            $this->get_connection($request->company_name); 
            $save_res=Audit_Model::insert_data($insert_data);
            EventsLog::create([
                'website_id' => $request->company_name,
                'action_by' => '-'.$request->session()->get('admin')->id,
                'action' => 1,
                'action_detail' => 'Audit',
                'comment' => 'Create Audit',
                'patient_id' => $request->patient_name,
                'ip_address' => $request->ip(),
                'type' => 'SuperAdmin',
                'user_agent' => $request->userAgent(),
                'authenticated_by' => 'packnpeaks',
                'status' => 1
            ]);
        }
        
        DB::disconnect('tenant');
        return redirect()->back()->with(["msg"=>'<div class="alert alert-success"> <strong> Patient Audit </strong> Added 
        Successfully.</div>']); 
   }
   /* All Audits  */
    public  function  all_audits()
    {
        $all_pharmacy=User::all();
        $newarray=array();
        foreach($all_pharmacy  as $row){
              $this->get_connection($row->website_id);
              $get_audit=Audit_Model::get_all();
                foreach($get_audit as $col) {
                    $col->pharmacy=$row->company_name;
                    $newarray[]=$col;
                }
              DB::disconnect('tenant');
        } 
        $data['all_audit']=$newarray;  
        return  view('admin.all_audits')->with($data); 
    }

    public  function  archived_all_audits()
    {
        $all_pharmacy=User::all();
        $newarray=array();
        foreach($all_pharmacy  as $row){
              $this->get_connection($row->website_id);
              $get_audit=Audit_Model::get_archived();
                foreach($get_audit as $col) {
                    $col->pharmacy=$row->company_name;
                    $newarray[]=$col;
                }
              DB::disconnect('tenant');
        } 
        $data['all_audit']=$newarray;  
        return  view('admin.archived_all_audits')->with($data); 
    }


    /* edit Audit  */
    public  function  edit_audit(Request $request)
    {
        $data['all_pharmacies']=User::all();
        $this->get_connection($request->website_id);
        $data['all_facilities']=TenantStore::all();
        $data['audit']=Audit_Model::get_by_where(array('id'=>$request->row_id,'deleted_at'=>NULL)); 
        $data['all_patients']=Patient_Model::get_by_where(array('deleted_at'=>NULL,'website_id'=>$request->website_id));
        DB::disconnect('tenant');
        return  view('admin.edit_audit')->with($data); 
    }

    /* update data */
    public function  update_audit(Request $request)
    {
        $validate_array=array(
            'patient_name'        => 'required|numeric|min:1',
            'no_of_weeks'         => 'required|numeric|min:1',
            'store'               => 'required|numeric|min:1',
            // 'staff_initials'      => 'required|string|max:255'
        ); 
        $update_data=array(
            'patient_id'=>$request->patient_name,
            'no_of_weeks'=>$request->no_of_weeks,
            'store'=>$request->store,
            'staff_initials'=>$request->staff_initials
        );
        if(isset($request->store) && $request->store=='5')
        {
            $update_data['store_others_desc']=$request->other_store; 
            $validate_array['other_store']='required'; 
        }

        $validator = $request->validate($validate_array); 
        $this->get_connection($request->website_id); 
        Audit_Model::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),$update_data);
        EventsLog::create([
            'website_id' => $request->website_id,
            'action_by' => '-'.$request->session()->get('admin')->id,
            'action' => 2,
            'action_detail' => 'Audit',
            'comment' => 'Update Audit',
            'patient_id' => $request->patient_name,
            'ip_address' => $request->ip(),
            'type' => 'SuperAdmin',
            'user_agent' => $request->userAgent(),
            'authenticated_by' => 'packnpeaks',
            'status' => 1
        ]);
        DB::disconnect('tenant');
        
        return redirect('admin/all_audits')->with(["msg"=>'<div class="alert alert-success"> <strong>  Audit </strong> Updated 
        Successfully.</div>']); 
    }

      /* Delete Return  */
    public function  delete_audit(Request $request)
    {
       $this->get_connection($request->website_id);
       $getdata=Audit_Model::find($request->row_id);
       Audit_Model::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id']));
       Audit_Model::delete_record($request->row_id); 
       EventsLog::create([
            'website_id' => $request->website_id,
            'action_by' => '-'.$request->session()->get('admin')->id,
            'action' => 3,
            'action_detail' => 'Audit',
            'comment' => 'Delete Audit',
            'patient_id' => $getdata->patient_id,
            'ip_address' => $request->ip(),
            'type' => 'SuperAdmin',
            'user_agent' => $request->userAgent(),
            'authenticated_by' => 'packnpeaks',
            'status' => 1
        ]);
       DB::disconnect('tenant');
       
       echo '200'; 
    }

      /* Soft Delete Return  */
      public function  soft_delete_audit(Request $request)
      {
         $this->get_connection($request->website_id);
         $getdata=Audit_Model::find($request->row_id);
       
         if($request->archivetypeid == 1 )
         {
            Audit_Model::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id'],'is_archive'=>'1'));
        }
        else
        {
            Audit_Model::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id'],'is_archive'=>'0'));
        }
         
       
       
         //  Audit_Model::soft_delete_record($request->row_id); 
         EventsLog::create([
              'website_id' => $request->website_id,
              'action_by' => '-'.$request->session()->get('admin')->id,
              'action' => 3,
              'action_detail' => 'Audit',
              'comment' => 'Soft Delete Audit',
              'patient_id' => $getdata->patient_id,
              'ip_address' => $request->ip(),
              'type' => 'SuperAdmin',
              'user_agent' => $request->userAgent(),
              'authenticated_by' => 'packnpeaks',
              'status' => 1
          ]);
         DB::disconnect('tenant');
         
         echo '200'; 
      }


      public function  remove_soft_delete_audit(Request $request)
      {

        
         $this->get_connection($request->website_id);
         $getdata=Audit_Model::find($request->row_id);
      
         if($request->archivetypeid == 1 )
         {
            Audit_Model::update_unarchive_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id'],'is_archive'=>'1'));
         }
            else if($request->archivetypeid == 0)
         {
            Audit_Model::update_unarchive_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id'],'is_archive'=>'0'));
         }

      
      
         //  Audit_Model::soft_delete_record($request->row_id); 
         EventsLog::create([
              'website_id' => $request->website_id,
              'action_by' => '-'.$request->session()->get('admin')->id,
              'action' => 3,
              'action_detail' => 'Audit',
              'comment' => 'Soft Delete Audit',
              'patient_id' => $getdata->patient_id,
              'ip_address' => $request->ip(),
              'type' => 'SuperAdmin',
              'user_agent' => $request->userAgent(),
              'authenticated_by' => 'packnpeaks',
              'status' => 1
          ]);
         DB::disconnect('tenant');
         
         echo '200'; 
      }

      


    /*Multiple Delete */
    public function multiple_delete_audit(Request $request)
    {
        $ids = explode(",",$request->row_id);
        $website_Ids=explode(",",$request->website_id);
        for($i=0;$i < count($ids);$i++)
        {
               $this->get_connection($website_Ids[$i]);
               $getdata=Audit_Model::find($ids[$i]);
               Audit_Model::update_where(array('id'=>$ids[$i],'website_id'=>$website_Ids[$i]),array('deleted_by'=>'-'.$request->session()->get('admin')['id']));
               Audit_Model::delete_record($ids[$i]); 
               EventsLog::create([
                        'website_id' => $request->website_id,
                        'action_by' => '-'.$request->session()->get('admin')->id,
                        'action' => 3,
                        'action_detail' => 'Audit',
                        'comment' => 'Delete Audit',
                        'patient_id' => $getdata->patient_id,
                        'ip_address' => $request->ip(),
                        'type' => 'SuperAdmin',
                        'user_agent' => $request->userAgent(),
                        'authenticated_by' => 'packnpeaks',
                        'status' => 1
                    ]);
               DB::disconnect('tenant');

        }  
        return response()->json(['status'=>true,'message'=>"Patient Audits  deleted successfully."]);
    }

    /*GET  Patient  Details */
     public  function  audit_info(Request $request)
     {
        $this->get_connection($request->website_id); 
        $data['audit']=Audit_Model::get_by_where(array('website_id'=>$request->website_id,'id'=>$request->row_id));
        $data['mode']='audit_info';
        DB::disconnect('tenant'); 
        return view('admin.ajax')->with($data);
     }

     public function email_audit_report(Request $request)
     {
        $email = $request->email;
        $start_date = $request->start_date  ;
        $end_date   = $request->end_date  ;
        
        $details['name'] = "PackPeak";
        $details['report_name'] = "Audits Report";
        $details['date_range'] = "$start_date To $end_date";
        $details['url'] = "https://packpeak.co.au/auditReport/$start_date/$end_date";

        \Mail::to($request->email)->send(new \App\Mail\AdminReportsEmail($details));
        return redirect()->back()->with(["msg"=>'<div class="alert alert-success"> <strong> Email Sent </strong> .</div>']);
     }

}
