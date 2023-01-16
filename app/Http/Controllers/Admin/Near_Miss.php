<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\User; 
use Illuminate\Http\Request;
use App\Models\Tenant\MissedPatient; 
use App\Models\Tenant\EventsLog; 
use DB; 
class Near_Miss extends Controller
{

   public  function  get_connection($website_id)
    {
        $get_user=User::get_by_column('website_id',$website_id);
        config(['database.connections.tenant.database' => $get_user[0]->host_name]);
         DB::purge('tenant');
         DB::reconnect('tenant');
          
    }
     public  function  near_miss(Request $request)
     {
        $data['all_pharmacies']=User::all();
        return view('admin.near_miss')->with($data); 
     }

     /* save Near misss  data  */
     public  function save_near_miss(Request $request)
     {  
          $validate_array=array(
            'company_name'     => 'required|numeric|min:1',
            'person_involved'  => 'required|string|max:255',
            'initials'         => 'required|string|max:255'
         );
         if(isset($request->other_checkbox)){
         	$validate_array =array_merge($validate_array,['other'=>'required|string|max:255']);
         } 
         
         $insert_data=array(
               'person_involved'=>$request->person_involved,
               'other'=>isset($request->other)?$request->other:'',
               'initials'=>$request->initials
         ); 
         // print_r($request->all()); die; 
         if(isset($request->missed_tablet))
         {
            $insert_data['missed_tablet']=1; 
         }
         if(isset($request->extra_tablet))
         {
            $insert_data['extra_tablet']=1;
         }
         if(isset($request->wrong_tablet))
         {
            $insert_data['wrong_tablet']=1;
         }
         if(isset($request->wrong_day))
         {
            $insert_data['wrong_day']=1;
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
         $save_res=MissedPatient::insert_data($insert_data);
         EventsLog::create([
            'website_id' => $request->company_name,
            'action_by' => '-'.$request->session()->get('admin')->id,
            'action' => 1,
            'action_detail' => 'Near Miss',
            'comment' => 'Create Near Miss',
            'patient_id' => null,
            'ip_address' => $request->ip(),
            'type' => 'SuperAdmin',
            'user_agent' => $request->userAgent(),
            'authenticated_by' => 'packnpeaks',
            'status' => 1
           ]);
         DB::disconnect('tenant');
         return redirect()->back()->with(["msg"=>'<div class="alert alert-success">Patient <strong>Missed </strong> Added Successfully.</div>']);
     }
  /* All Near Miss   */
     public  function  all_near_miss()
     {
         $all_pharmacy=User::all();
         $newarray=array(); $allMissedTablet=0; $allExtraTablet=0; $allWrongTablet=0;$allWrongDay=0;
         foreach($all_pharmacy  as $row){
               $this->get_connection($row->website_id);
               $get_audit=MissedPatient::get_all();

               $missedTablet=MissedPatient::where('missed_tablet',1)->get();
               $extraTablet=MissedPatient::where('extra_tablet',1)->get();
               $wrongTablet=MissedPatient::where('wrong_tablet',1)->get();
               $wrongDay=MissedPatient::where('wrong_day',1)->get();

               $allMissedTablet+=$missedTablet->count();
               $allExtraTablet+=$extraTablet->count();
               $allWrongTablet+=$wrongTablet->count();
               $allWrongDay+=$wrongDay->count();

               foreach($get_audit as $col) {
                     $col->pharmacy=$row->company_name;
                     $newarray[]=$col;
               }

               DB::disconnect('tenant'); 
         } 

          
         $data['all_missed_patients']=$newarray; 

         $data['allMissedTablet']=$allMissedTablet;
         $data['allExtraTablet']=$allExtraTablet;
         $data['allWrongTablet']=$allWrongTablet;
         $data['allWrongDay']=$allWrongDay;
         return view('admin.all_near_miss')->with($data);
     }

     public  function  archived_all_near_miss()
     {
         $all_pharmacy=User::all();
         $newarray=array(); $allMissedTablet=0; $allExtraTablet=0; $allWrongTablet=0;$allWrongDay=0;
         foreach($all_pharmacy  as $row){
               $this->get_connection($row->website_id);
               $get_audit=MissedPatient::get_archived();

               $missedTablet=MissedPatient::where('missed_tablet',1)->get();
               $extraTablet=MissedPatient::where('extra_tablet',1)->get();
               $wrongTablet=MissedPatient::where('wrong_tablet',1)->get();
               $wrongDay=MissedPatient::where('wrong_day',1)->get();

               $allMissedTablet+=$missedTablet->count();
               $allExtraTablet+=$extraTablet->count();
               $allWrongTablet+=$wrongTablet->count();
               $allWrongDay+=$wrongDay->count();

               foreach($get_audit as $col) {
                     $col->pharmacy=$row->company_name;
                     $newarray[]=$col;
               }

               DB::disconnect('tenant'); 
         } 

          
         $data['all_missed_patients']=$newarray; 

         $data['allMissedTablet']=$allMissedTablet;
         $data['allExtraTablet']=$allExtraTablet;
         $data['allWrongTablet']=$allWrongTablet;
         $data['allWrongDay']=$allWrongDay;
         return view('admin.archived_all_near_miss')->with($data);
     }

     /* Edit Return  */
     public  function edit_near_miss(Request $request)
     {
         $data['all_pharmacies']=User::all();
         $this->get_connection($request->website_id);
         $data['near_miss']=MissedPatient::get_by_where(array('id'=>$request->row_id,'deleted_at'=>NULL)); 
         DB::disconnect('tenant');
         return view('admin.edit_near_miss')->with($data);
     }
     /* UPdate Near Miss */
     public function  update_near_miss(Request $request)
     {
         $validate_array=array(
            'person_involved'  => 'required|string|max:255',
            'initials'         => 'required|string|max:255'
         ); 
         if(isset($request->other_checkbox)){
         	$validate_array =array_merge($validate_array,['other'=>'required|string|max:255']);
         } 
         
         $update_data=array(
               'person_involved'=>$request->person_involved,
               'other'=>isset($request->other)?$request->other:'',
               'initials'=>$request->initials,
               'missed_tablet'=>$request->missed_tablet?1:0,
               'extra_tablet'=>$request->extra_tablet?1:0,
               'wrong_tablet'=>$request->wrong_tablet?1:0,
               'wrong_day'=>$request->wrong_day?1:0
         ); 
        $validator = $request->validate($validate_array); 
        $this->get_connection($request->website_id); 
        MissedPatient::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),$update_data);
        EventsLog::create([
         'website_id' => $request->website_id,
         'action_by' => '-'.$request->session()->get('admin')->id,
         'action' => 2,
         'action_detail' => 'Near Miss',
         'comment' => 'Update Near Miss',
         'patient_id' => null,
         'ip_address' => $request->ip(),
         'type' => 'SuperAdmin',
         'user_agent' => $request->userAgent(),
         'authenticated_by' => 'packnpeaks',
         'status' => 1
        ]);

        DB::disconnect('tenant');
        return redirect('admin/all_near_miss')->with(["msg"=>'<div class="alert alert-success"> <strong>  Near Miss </strong> Updated 
        Successfully.</div>']); 
         
     }

      /* Delete Return  */
      public function  delete_near_miss(Request $request)
      {
         $this->get_connection($request->website_id);
         MissedPatient::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id']));
         MissedPatient::delete_record($request->row_id); 
         EventsLog::create([
            'website_id' => $request->website_id,
            'action_by' => '-'.$request->session()->get('admin')->id,
            'action' => 3,
            'action_detail' => 'Near Miss',
            'comment' => 'Delete Near Miss',
            'patient_id' => null,
            'ip_address' => $request->ip(),
            'type' => 'SuperAdmin',
            'user_agent' => $request->userAgent(),
            'authenticated_by' => 'packnpeaks',
            'status' => 1
           ]);
         DB::disconnect('tenant');
         echo '200'; 
      }


      /* Delete Return  */
      public function  soft_delete_near_miss(Request $request)
      {
         $this->get_connection($request->website_id);
        
         if($request->archivetypeid == 1 )
         {
            MissedPatient::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id'],'is_archive'=>'1'));
         }
         else
         {
            MissedPatient::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id'],'is_archive'=>'0'));
         }
         
        
        
         // MissedPatient::soft_delete_record($request->row_id); 
         EventsLog::create([
            'website_id' => $request->website_id,
            'action_by' => '-'.$request->session()->get('admin')->id,
            'action' => 3,
            'action_detail' => 'Near Miss',
            'comment' => 'Soft Delete Near Miss',
            'patient_id' => null,
            'ip_address' => $request->ip(),
            'type' => 'SuperAdmin',
            'user_agent' => $request->userAgent(),
            'authenticated_by' => 'packnpeaks',
            'status' => 1
           ]);
         DB::disconnect('tenant');
         echo '200'; 
      }

     public function  delete_archived_near_miss(Request $request)
     {
      
         $this->get_connection($request->website_id);
         MissedPatient::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id']));
         MissedPatient::delete_archived_record($request->row_id); 
         EventsLog::create([
            'website_id' => $request->website_id,
            'action_by' => '-'.$request->session()->get('admin')->id,
            'action' => 3,
            'action_detail' => 'Near Miss',
            'comment' => 'Delete Archived Near Miss',
            'patient_id' => null,
            'ip_address' => $request->ip(),
            'type' => 'SuperAdmin',
            'user_agent' => $request->userAgent(),
            'authenticated_by' => 'packnpeaks',
            'status' => 1
           ]);
         DB::disconnect('tenant');
         echo '200'; 
      }

     /* last Month near Miss  */
     public  function  nm_last_month()
     {
         $all_pharmacy=User::all();
         $newarray=array();
         foreach($all_pharmacy  as $row){
               $this->get_connection($row->website_id);
               $get_audit=MissedPatient::get_last_month();
               foreach($get_audit as $col) {
                     $col->pharmacy=$row->company_name;
                     $newarray[]=$col;
               }
               DB::disconnect('tenant');
         } 
         $data['last_month_missed_patients']=$newarray;
        return  view('admin.nm_last_month')->with($data);  
     }

   public function multiple_delete_near_miss(Request $request)
   {
      $ids = explode(",",$request->row_id);
      $website_Ids=explode(",",$request->website_id);
      for($i=0;$i < count($ids);$i++)
      {
               $this->get_connection($website_Ids[$i]);
               MissedPatient::update_where(array('id'=>$ids[$i],'website_id'=>$website_Ids[$i]),array('deleted_by'=>'-'.$request->session()->get('admin')['id']));
               MissedPatient::delete_record($ids[$i]); 
               EventsLog::create([
                  'website_id' => $website_Ids[$i],
                  'action_by' => '-'.$request->session()->get('admin')->id,
                  'action' => 3,
                  'action_detail' => 'Near Miss',
                  'comment' => 'Delete Near Miss',
                  'patient_id' => null,
                  'ip_address' => $request->ip(),
                  'type' => 'SuperAdmin',
                  'user_agent' => $request->userAgent(),
                  'authenticated_by' => 'packnpeaks',
                  'status' => 1
                 ]);
               DB::disconnect('tenant');

      }  
      return response()->json(['status'=>true,'message'=>"Patient miss  deleted successfully."]);
   }

   
     public function nm_monthly()
     {
         $all_pharmacy=User::all();
         $all_pharmacy=User::all();
         $newarray=array();
         foreach($all_pharmacy  as $row){
               $this->get_connection($row->website_id);
               $get_audit=MissedPatient::get_all();
               foreach($get_audit as $col) {
                     $col->pharmacy=$row->company_name;
                     $newarray[]=$col;
               }
               DB::disconnect('tenant');
         } 
         $data['all_missed_patients']=$newarray;  
        return  view('admin.nm_monthly')->with($data); 
     }  

     /*GET  Patient   Near Miss Details */
     public  function  near_miss_info(Request $request)
     {
        //print_r($request->all());
        $this->get_connection($request->website_id); 
        $data['near_miss']=MissedPatient::get_by_where(array('website_id'=>$request->website_id,'id'=>$request->row_id));
        $data['mode']='near_miss_info';
        DB::disconnect('tenant');
        //print_r($data['patients']);die; 
        return view('admin.ajax')->with($data);
     }

     public function email_nearmiss_report(Request $request)
     {
         $email = $request->email;
         $start_date = $request->start_date  ;
         $end_date   = $request->end_date  ;
        
         $details['name'] = "PackPeak";
         $details['report_name'] = "Near Miss Report";
         $details['date_range'] = "$start_date To $end_date";
         $details['url'] = "https://packpeak.co.au/nearmissReport/$start_date/$end_date";

         \Mail::to($request->email)->send(new \App\Mail\AdminReportsEmail($details));
         return redirect()->back()->with(["msg"=>'<div class="alert alert-success"> <strong> Email Sent </strong> .</div>']);
     }


}
