<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\User;
use App\smsprice;
use App\smspurchasedtransaction;
use Illuminate\Http\Request;
use DB; 
use App\Models\Tenant\PickUp as PickUp_Model;
use App\Models\Tenant\Pickups;
use App\Models\Tenant\PickupSlot as PickUp_Slot;
use App\Models\Tenant\AccessLevel;
use App\Models\Tenant\Patient as Patient_Model;
use App\Models\Admin\Location;
use App\Models\Tenant\Notification;
use App\Models\Tenant\EventsLog; 
use App\Models\Tenant\PatientLocation;
use Session;
use Carbon\Carbon;
use Validator;
use DateTime;
use Illuminate\Support\Facades\URL;
use PDF;
use Illuminate\Support\Facades\Storage;

class PickUp extends Near_Miss
{
    /* Start  of  Pickups */
    public  function  pickups(Request $request)
    {
        
        $data['all_pharmacies']=User::all();
        $data['created_at']=isset($request->day)?$request->day:'';
        //$data['patients']=Patient::get();
        $data['all_Location'] = Location::get();
       return  view('admin.pickups')->with($data);
    }
    public function pickups_reports_excel()
    {
       $all_pharmacy=User::all();
           $newarray=array();
           foreach($all_pharmacy  as $row){
                 $this->get_connection($row->website_id);
                 $get_audit=PickUp_Model::get_all();
                   foreach($get_audit as $col) {
                       $col->pharmacy=$row->company_name;
                       $newarray[]=$col;   
                   }
                 DB::disconnect('tenant');
           } 
           $data['all_pickups']=$newarray;
   
          
       // $get = DB::table('pick_ups')->get($data);
         // dd($data);
          $proData = "";
          if (count($data) >0) {
           $proData .= '<table border  style="height:100% width:100%">
           <tr>
           <th>Id</th>
           <th>Patient_id</th>
           <th>DOB</th>
           <th>Image</th>
           <th>weeks_last_picked_up</th>
           
           
           <th>pharmacist_sign</th>
           <th>patient_sign</th>
          
   
           </tr>';
       
           foreach ($data['all_pickups'] as $img)
           {      
            $proData .= '
               <tr>
               <td>'.$img->id.'</td>
               <td>'.$img->patient_id.'</td>
               <td>'.$img->dob.'</td>
               <td>'.$img->last_pick_up_date.'</td>
               <td>'.$img->weeks_last_picked_up.'</td>
             
               <td>
              
               <img src="'.$img->pharmacy_image.'"  height="20" width="50">
              
               </td>
               <td>
              
               <img src="'.$img->patient_image.'"  height="20" width="50">
              
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
   public function pickups_reports_pdf(Request $request)
   {       
         $all_pharmacy=User::all();
                    $newarray=array();
                    foreach($all_pharmacy  as $row){
                          $this->get_connection($row->website_id);
                          $get_audit=PickUp_Model::get_all();
                            foreach($get_audit as $col) {
                                $col->pharmacy=$row->company_name;
                                $newarray[]=$col;   
                            }
                          DB::disconnect('tenant');
                    } 
                      $data['newarray']=$newarray;
                      $pdf = PDF::loadView('pdf', $data);
                      return  $pdf->download(time().'.pdf');
  
       

    }
   
    public  function  new_pickup(){
        $data['all_pharmacies']=User::all();
        $data['created_at']=isset($request->day)?$request->day:'';
        //$data['patients']=Patient::get();
        $data['all_Location'] = Location::get();
        return  view('admin.newpickups')->with($data);
    }

    public  function  getPickupSlots(Request $request)
    {
        $date = $request->date;
        $company = $request->company_name;

        $this->get_connection($request->company_name); 
        
         
           $slots=PickUp_Slot::getAvailableSlots($date);
           if(count($slots)>0){
                $html = '';
                $html .="<label >Pickup Slot</label><br><select name='slot' class='form-control'>";

                foreach($slots as $slot){
                    $html .="<option value='".$slot->id."'>".$slot->start_time.' - '.$slot->end_time."</option>";
                }
                DB::disconnect('tenant');
                $html .= "</select>";
                echo $html;
            }
            else{
                echo "No Slot Available For $date";
            }
    }

    
   /*   Save Pickup */
   public function save_pickup(Request $request)
   {      



        $validate_array=array(
            'company_name'        => 'required|numeric|min:1',
            // 'patient_name'        => 'required|numeric|min:1',
            'dob'                 => 'date_format:d/m/Y|before:tomorrow',
            'no_of_weeks'         => 'required|numeric|min:1',
            'who_pickup'          => 'required|string|max:255',
            'patient_signature'   => 'required|string|max:999000',
           // 'pharmacist_signature'=> 'required|string|max:999000',
            // 'pickup_date'         => 'required|after_or_equal:date',
            // 'slot'                => 'required'
        ); 
      // pharmacy signature save in image colume 
       // $folderPath = public_path('uploding\\');
       $folderPath = public_path('signatures/pickups/pharmacy\\');
	    $image_parts = explode(";base64,", $request->pharmacist_signature);
	    $image_type_aux = explode("image/", $image_parts[0]);
	    $image_type = $image_type_aux[1];
	    $image_base64 = base64_decode($image_parts[1]);
        $file =  $folderPath. uniqid() . '.'.$image_type;
        file_put_contents($file, $image_base64);

        $folderPath2 = public_path('signatures/pickups/patient\\');
        $image_parts2 = explode(";base64,", $request->patient_signature);
	    $image_type_aux2 = explode("image/", $image_parts[0]);
	    $image_type2 = $image_type_aux2[1];
	    $image_base64_2 = base64_decode($image_parts2[1]);
        $file2 = $folderPath2. uniqid() . '.'.$image_type2;
        file_put_contents($file2, $image_base64_2);

        $date = Carbon::now();
        $insert_data=array(
            'patient_id'=>$request->patient_name,
            'dob'=> Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
            'no_of_weeks'=>$request->no_of_weeks,
            'location'=>$request->location?implode(',',$request->location):'',
            'pick_up_by'=>$request->who_pickup,
            'notes_from_patient'=>$request->note,
            'notes_for_patient'=>$request->notes_for_patient,
            'pharmacy_image'=> $file,
            'patient_image'=>$file2,
            'pharmacist_sign'=>$request->pharmacist_signature,
            'patient_sign'=>$request->patient_signature,
            'pickup_date'=>$date,
            // 'pickup_date'=>$request->pickup_date,
            // 'pickup_slot'=>$request->slot,
            'pickup_slot'=>0,
            'last_pick_up_date'=>$request->last_pick_up_date,
            'weeks_last_picked_up' => $request->weeks_last_picked_up
        );
        // 'pharmacist_sign'=>$request->pharmacist_signature,

        
        $location_data = array('locations'=>isset($request->location)?implode(',',$request->location):'');
        if(!empty($request->created_at))
        { 
            // $request->created_at=substr($request->created_at,0,10);
            // $created_at=\Carbon\Carbon::createFromFormat('Y-m-d',$request->created_at);
            // $insert_data['created_at']=$created_at;
        }
        
        if($request->who_pickup=='carer')
        {
            $insert_data['carer_name']=$request->carer_name; 
            $validate_array['carer_name']='required';
        }


        if(!empty($request->session()->get('admin')))
        {
            $insert_data['website_id']=$request->company_name; 
            $insert_data['created_by']='-'.$request->session()->get('admin')['id'];
            $validate_array['company_name']='required'; 
        }
       
        $validator = $request->validate($validate_array);
        
        // $image = time().rand(1,100).'.'.$request->pharmacist_signature;  
        // $request->pharmacist_signature->move(public_path('image'), $image);

        /* Pharmacy Signature   */
       
        /* Technician Singnature  */
      
        
        $this->get_connection($request->company_name);


        $save_res=PickUp_Model::insert_data($insert_data);
        // Patient location
        $location_data['patient_id']=$request->patient_name;
        $location_data['action_by' ] = $request->session()->get('admin')->id;
        PatientLocation::insert_data($location_data);
        // Patient Locations
        EventsLog::create([
            'website_id' => $request->company_name,
            'action_by' => '-'.$request->session()->get('admin')->id,
            'action' => 1,
            'action_detail' => 'PickUp',
            'comment' => 'Create PickUp',
            'patient_id' => $request->patient_name,
            'ip_address' => $request->ip(),
            'type' => 'SuperAdmin',
            'user_agent' => $request->userAgent(),
            'authenticated_by' => 'packnpeaks',
            'status' => 1
           ]);
            $datetime1 = new DateTime($request->pickup_date);
            $datetime2 = new DateTime(date('Y-m-d'));
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%a');
                if($days > 3){
                    $patient = Patient_Model::where('id', $request->patient_name)->first();
                    
                    $insert_notification = array(
                        'type' => 'pickup_creation',
                        'notification_msg' => "Pickup added of ".$request->pickup_date." for ".$patient->first_name." ".$patient->first_name,
                        'website_id' => $request->company_name,
                    );
    
                    $save_notification = Notification::insert_data($insert_notification);
                }
           
    
             
        DB::disconnect('tenant');
        return redirect()->back()->with(["msg"=>'<div class="alert alert-success"> <strong> Patient PickUps </strong> Added Successfully.</div>']);

   }

   /* End  of  Pickups */
   
   /* Start  of  pickups_reports */
    public  function  pickups_reports()
    {
        $all_pharmacy=User::all();
        $newarray=array();
        foreach($all_pharmacy  as $row){
              $this->get_connection($row->website_id);
              $get_audit=PickUp_Model::get_all();
                foreach($get_audit as $col) {
                    $col->pharmacy=$row->company_name;
                    $newarray[]=$col;
                }
              DB::disconnect('tenant');
        } 
        $data['all_pickups']=$newarray;
       return  view('admin.pickups_reports')->with($data); 
    }

    public  function  pickups_reports_last_month()
    {
        $all_pharmacy=User::all();
        $newarray=array();
        foreach($all_pharmacy  as $row){
              $this->get_connection($row->website_id);
              $get_audit=PickUp_Model::get_last_month_pickups();
                foreach($get_audit as $col) {
                    $col->pharmacy=$row->company_name;
                    $newarray[]=$col;
                }
              DB::disconnect('tenant');
        } 
        $data['all_pickups']=$newarray;
       return  view('admin.pickups_reports')->with($data); 
    }
   /* End of  pickups_reports */

   /* Start  of  archived_pickups_reports */
   public  function  archived_pickups_reports()
   {

       $all_pharmacy=User::all();
       $newarray=array();
       foreach($all_pharmacy  as $row){
             $this->get_connection($row->website_id);
             $get_audit=PickUp_Model::get_archived();
               foreach($get_audit as $col) {
                   $col->pharmacy=$row->company_name;
                   $newarray[]=$col;
               }
             DB::disconnect('tenant');
       } 
       $data['all_pickups']=$newarray;
      return  view('admin.archived_pickups_reports')->with($data); 
   }
  /* End of  archived_pickups_reports */

  public function email_pickup_report(Request $request){

        $email = $request->email;
        $start_date = $request->start_date  ;
        $end_date   = $request->end_date  ;
        
        $details['name'] = "PackPeak";
        $details['report_name'] = "PickUps Report";
        $details['date_range'] = "$start_date To $end_date";
        $details['url'] = "https://packpeak.co.au/pickupReport/$start_date/$end_date";

        \Mail::to($request->email)->send(new \App\Mail\AdminReportsEmail($details));
        return redirect('/admin/pickups_reports')->with(["msg"=>'<div class="alert alert-success"> <strong> Email Sent </strong> .</div>']);

  }

  public function pickup_notification_email()
//   public function pickup_notification_email(Request $request)
  {

    dump("1");
    return;
        $date = $request->date;
        $company = $request->company_name;

        $all_pharmacy=User::all();
        $newarray=array();
        $description = "The following pickups are coming.<br>";
        foreach($all_pharmacy  as $row){
             $this->get_connection($row->website_id);
             $pickups=PickUp_Model::get_three_days_prior_pickups();
              
               foreach($pickups as $col) {
                   $pickup_date = $col->pickup_date;
                   $first_name = $col->first_name;
                   $last_name = $col->last_name;

                    $description .='<span>Patient: '.$first_name.' '.$last_name.' Date: '.$pickup_date .'</span><br>';              
               }
             DB::disconnect('tenant');
        } 


           
        $email = 'info@yehtohoga.com';
        
        $details['name'] = "PackPeak";
        $details['details'] = $description;
         
        \Mail::to($email)->send(new \App\Mail\PickupNotification($details));
        return "E-Mail Sent";

  }
   public  function  getAllPickupForMonth(Request $request){
    $month=$request->month;
    $year=$request->year;
    $all_pharmacy=User::all();
        
        $allPickups=0;
        if(count($all_pharmacy)){
          
        foreach($all_pharmacy  as $row){
              
              $this->get_connection($row->website_id);
              $allPickups+=PickUp_Model::whereRaw('MONTH(`created_at`) = '.$month.' AND YEAR(`created_at`) = '.$year)->get()->count();
              DB::disconnect('tenant');
        }
       }
       return $allPickups;
   }

   /* pickups_calender */
    public  function  pickups_calender()
    {

        $all_pharmacy=User::all();
        $newarray=array();
        $allLastPickup=array();
        foreach($all_pharmacy  as $row){
              $this->get_connection($row->website_id);
              $get_result=PickUp_Model::get_all_pickup(); 
                foreach($get_result as $col) {
                    $col->pharmacy=$row->company_name;
                    $newarray[]=$col;
                }

                $getAccess=AccessLevel::first(); 
                if(!empty($getAccess)){
                    $default_cycle=$getAccess->default_cycle;
                }
                else{
                    $default_cycle=4;
                }
                $getAllPatient=PickUp_Model::distinct()->get(['patient_id']);
       
                
                foreach($getAllPatient as $key=>$value){
                    if($value->patient_id!=""){
                        $getLastPickup=PickUp_Model::where('patient_id',$value->patient_id)->orderBy('created_at', 'DESC')->first();
                        array_push($allLastPickup,$getLastPickup);
                    }
                }

              DB::disconnect('tenant');
        } 
        $data['pickups']=(object)$newarray;
        // $now = Carbon::now();
        // $allPickups=$this->getAllPickupForMonth($now->month,$now->year);
        // $data['allPickups']=$allPickups;
        // echo json_encode($allPickups);die; 
        /* next  */
        // print_r($allLastPickup); die;  
        // echo json_encode($allLastPickup); die;  
        $allNeaxtPickup=array();
        foreach($allLastPickup as $key=>$row){
            
            $this->get_connection($row->website_id);
            $no_of_weeks=$row->no_of_weeks?$row->no_of_weeks-1:0;
            for($i=0;$i < $no_of_weeks; $i++){
               $start_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$row->created_at);
               $nextdate=$start_date->addWeeks($default_cycle);
               $row->created_at=$nextdate;
               $row->updated_at=$nextdate;

               $nextpickup['id']=$i;
               $nextpickup['patient_id']=$row->patient_id;
               $nextpickup['dob']=$row->dob;
               $nextpickup['last_pick_up_date']=$row->created_at;
               $nextpickup['weeks_last_picked_up']=$row->weeks_last_picked_up;
               $nextpickup['no_of_weeks']=$row->no_of_weeks;
               $nextpickup['location']=$row->location;
               $nextpickup['pick_up_by']=$row->pick_up_by;
               $nextpickup['carer_name']=$row->carer_name;
               $nextpickup['notes_from_patient']=$row->notes_from_patient;
               $nextpickup['pharmacist_sign']=$row->pharmacist_sign;
               $nextpickup['patient_sign']=$row->patient_sign;
               $nextpickup['created_by']=$row->created_by;
               $nextpickup['deleted_by']=$row->deleted_by;
               $nextpickup['status']=$row->status;
               $nextpickup['deleted_at']=$row->deleted_at;
               $nextpickup['is_archive']=$row->is_archive;
               $nextpickup['website_id']=$row->website_id;
               $nextpickup['created_at']=$nextdate;
               $nextpickup['updated_at']=$nextdate;
               $nextpickup['patients']=$row->patients;
               array_push($allNeaxtPickup,$nextpickup); 
            }
            // echo $start_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$row->created_at);
            // echo $start_date->addWeeks($default_cycle);
            // echo json_encode($allNeaxtPickup);die; 
            DB::disconnect('tenant');
        }
        //echo json_encode($allNeaxtPickup);die;
        
        $data['nextPickupList']=(object)$allNeaxtPickup;

        return  view('admin.pickups_calender')->with($data); 
    }
   /* pickups_calender */

   /* Edit Pick up  */
   public  function  edit_pickup(Request  $request)
   {



        $data['all_pharmacies']=User::all();
        $data['all_Location'] = Location::get();
        $this->get_connection($request->website_id);
        $data['pickup']=PickUp_Model::get_by_where(array('id'=>$request->row_id,'deleted_at'=>NULL)); 
        $data['all_patients']=Patient_Model::get_by_where(array('deleted_at'=>NULL,'website_id'=>$request->website_id));
        $data['patient_location']=PatientLocation::where(array('patient_id'=>$data['pickup'][0]->patient_id))->get();
        DB::disconnect('tenant');
        
        return  view('admin.edit_pickup')->with($data);
   }
   public function update_pickup(Request  $request)
   {
            $validate_array=array(
                'patient_name'        => 'required|numeric|min:1',
                'dob'                 => 'date_format:d/m/Y|before:tomorrow',
                'no_of_weeks'         => 'required|numeric|min:1',
                'who_pickup'          => 'required|string|max:255'
            ); 

            $update_data=array(
                'patient_id'=>$request->patient_name,
                'dob'=>Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
                'no_of_weeks'=>$request->no_of_weeks,
                'location'=>$request->location?implode(',',$request->location):'',
                'pick_up_by'=>$request->who_pickup,
                'notes_from_patient'=>$request->note,
                'notes_for_patient'=>$request->notes_for_patient,
                'last_pick_up_date'=>$request->last_pick_up_date,
                'weeks_last_picked_up' => $request->weeks_last_picked_up
            );
            if($request->pharmacist_signature)
            { 
                $update_data['pharmacist_sign']=$request->pharmacist_signature; 
                 /* Pharmacy Signature   */
                $folderPath = public_path('upload/pharmacy/');
                $image_parts = explode(";base64,", $request->pharmacist_signature);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $file = $folderPath . uniqid() . '.'.$image_type;
                file_put_contents($file, $image_base64);
            }

            if($request->patient_signature)
            { 
                $update_data['patient_sign']=$request->patient_signature; 
                /* Technician Singnature  */
                $folderPath2 = public_path('upload/patient/');
                $image_parts2 = explode(";base64,", $request->patient_signature);
                $image_type_aux2 = explode("image/", $image_parts2[0]);
                $image_type2 = $image_type_aux2[1];
                $image_base64_2 = base64_decode($image_parts2[1]);
                $file2 = $folderPath2. uniqid() . '.'.$image_type2;
                file_put_contents($file2, $image_base64_2);
            }
            if($request->who_pickup=='carer')
            {
                $update_data['carer_name']=$request->carer_name; 
                $validate_array['carer_name']='required';
            }
            else
            {
                $update_data['carer_name']=null;
            }

            $validator = $request->validate($validate_array);
            $this->get_connection($request->website_id); 
            PickUp_Model::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),$update_data);
            //    Patient Location
                $location_data['locations']=$update_data['location'];
                $location_data['patient_id']=$request->patient_name;
                $location_data['action_by' ] = $request->session()->get('admin')->id;
                PatientLocation::insert_data($location_data);
        //    End
            EventsLog::create([
                'website_id' => $request->website_id,
                'action_by' => '-'.$request->session()->get('admin')->id,
                'action' => 2,
                'action_detail' => 'PickUp',
                'comment' => 'Update PickUp',
                'patient_id' => $request->patient_name,
                'ip_address' => $request->ip(),
                'type' => 'SuperAdmin',
                'user_agent' => $request->userAgent(),
                'authenticated_by' => 'packnpeaks',
                'status' => 1
            ]);
            DB::disconnect('tenant');
            return redirect('admin/pickups_reports')->with(["msg"=>'<div class="alert alert-success"> <strong>  PickUps </strong> Updated 
            Successfully.</div>']);
        

   } 
   /* 6_month_compliance */
    public  function six_month_compliance()
    {
        $all_pharmacy=User::all();
        $newarray=array();
        foreach($all_pharmacy  as $row){
              $this->get_connection($row->website_id);
              $get_result=PickUp_Model::get_six_month(); 
                foreach($get_result as $col) {
                    $col->pharmacy=$row->company_name;
                    $newarray[]=$col;
                }
              DB::disconnect('tenant');
        } 
        $data['all_pickups']=$newarray;
        return  view('admin.six_month_compliance')->with($data); 
    }  
    public  function archived_six_month_compliance()
    {
        $all_pharmacy=User::all();
        $newarray=array();
        foreach($all_pharmacy  as $row){
              $this->get_connection($row->website_id);
              $get_result=PickUp_Model::get_six_month_archived(); 
                foreach($get_result as $col) {
                    $col->pharmacy=$row->company_name;
                    $newarray[]=$col;
                }
              DB::disconnect('tenant');
        } 
        $data['all_pickups']=$newarray;
        return  view('admin.archived_six_month_compliance')->with($data); 
    }  
   /* 6_month_compliance */

    /* Delete Return  */
    public function  delete_pickup(Request $request)
    {

       $this->get_connection($request->website_id);
       $getdata=PickUp_Model::find($request->row_id);
       PickUp_Model::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id']));
       PickUp_Model::delete_record($request->row_id); 
       EventsLog::create([
        'website_id' => $request->website_id,
        'action_by' => '-'.$request->session()->get('admin')->id,
        'action' => 3,
        'action_detail' => 'PickUp',
        'comment' => 'Delete PickUp',
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
    public function  soft_delete_pickup(Request $request)
    {
       $this->get_connection($request->website_id);
       $getdata=PickUp_Model::find($request->row_id);

       if($request->archivetypeid == 1 )
        {
            PickUp_Model::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id'],'is_archive'=>'1'));
            //return "Achive";
        }
        else if($request->archivetypeid == 0 )
        {
            
            PickUp_Model::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id'],'deleted_at'=>NULL,'is_archive'=>'0'));
        }

        
    //    PickUp_Model::soft_delete_record($request->row_id); 
       
       EventsLog::create([
        'website_id' => $request->website_id,
        'action_by' => '-'.$request->session()->get('admin')->id,
        'action' => 3,
        'action_detail' => 'PickUp',
        'comment' => 'Soft Delete PickUp',
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
 
   /* all_compliance */
    public  function  all_compliance()
    {  
        $all_pharmacy=User::all();
        $newarray=array();
        foreach($all_pharmacy  as $row){
              $this->get_connection($row->website_id);
              $get_audit=PickUp_Model::get_all();
                foreach($get_audit as $col) {
                    $col->pharmacy=$row->company_name;
                    $newarray[]=$col;
                }
              DB::disconnect('tenant');
        } 
        $data['all_pickups']=$newarray;
        return  view('admin.all_compliance')->with($data); 
    }
   /* all_compliance */


   public  function  patients_picked_up_last_month()
    {
        $all_pharmacy=User::all();
        $newarray=array();
        foreach($all_pharmacy  as $row){
              $this->get_connection($row->website_id);
              $get_audit=PickUp_Model::get_last_month();
                foreach($get_audit as $col) {
                    $col->pharmacy=$row->company_name;
                    $newarray[]=$col;
                }
              DB::disconnect('tenant');
        } 
        $data['all_pickups']=$newarray;
        return  view('admin.patients_picked_up_last_month')->with($data);
    }

    /*GET  View  Details */
     public  function  pickup_info(Request $request)
     {
        $this->get_connection($request->website_id);
        $data['pickup']=PickUp_Model::get_by_where(array('website_id'=>$request->website_id,'id'=>$request->row_id));
        
        $data['mode']='pickup_info';
        DB::disconnect('tenant');
        
        return view('admin.ajax')->with($data);
     }

     public function customize_cycle_dates() 
     {
        $all_pharmacy = User::all();
        $smsdetails = smsprice::get();        
        $data['all_pharmacy'] = $all_pharmacy;
        return view('admin.customize_cycle_dates')->with($data)->with('smsdetails',$smsdetails);
    }

    public function getpatientnotes(Request $request)
	{
        
		return response()->json(['name' => 'Abigail', 'state' => 'CA']);
	}

	

   
    public function update_pharmacy_sms(Request $request )
    {
        smspurchasedtransaction::create([
            'noofsms' => $request->noofsmspurchase,
            'price' => $request->smsprice,
            'amount' => $request->totalamount,
            'websiteid' => $request->websiteid,
            'created_at' => Carbon::now(),
            'created_by' => 1,
            'transactionid' => '1',
            ]);

        return "200";
    }

    public function update_package_sms(Request $request )
    {
        $packageresult = smsprice::where('id', '=', $request->id)->first();
        $packageresult->name = $request->packagename;
        $packageresult->noofsms = $request->packagesms;
        $packageresult->price = $request->packageprice;
        $packageresult->save();

        return "200";
    }
    public function change_customize_cycle_dates(request $request)
    {

        $companyID = $request->companyID;
        $noOfWeeks = $request->noOfWeeks;
        $reminderdays =$request->reminderdays; 
        $all_pharmacy = User::all()->where('id',$companyID);
        
        foreach($all_pharmacy as $row)
        {
            $websiteID = $row->website_id;
            $this->get_connection($websiteID);

        //Update reminder days in access level database 
        $access= AccessLevel::first();
        if($reminderdays>0)
        {
        $access->reminderdefaultdays = $reminderdays;
        $access->default_cycle = $noOfWeeks;
        }
        $access->save();
        $insert_data = array(
        'type' => 'change_of_cycles',
        'notification_msg' => "Cycles changed to ".$noOfWeeks." no of weeks.",
        'website_id' => $websiteID,
        );
        $save_res = Notification::insert_data($insert_data);


            $pickups = PickUp_Model::all();
            // echo json_encode($patientID);exit;
            foreach ($pickups as $pickup) 
            {
                $pickupid = $pickup->id;
                $npickup = PickUp_Model::findOrFail($pickupid);
                $npickup->no_of_weeks = $noOfWeeks;
                $npickup->notes_for_patient = "Cycles changed to ".$noOfWeeks." no of weeks.";
                $npickup->save();
            }
            DB::disconnect('tenant');
        }
        echo '200';
    }
    
    public function to_pack_list()
    {
        return view('admin.packed_list');
    }
   
}
