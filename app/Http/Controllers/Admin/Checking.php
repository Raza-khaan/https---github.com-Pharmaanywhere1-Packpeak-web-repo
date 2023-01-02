<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\User; 
use App\Models\Tenant\Checking as Checking_Model;
use App\Models\Tenant\Packed as Packed_Model;
use App\Models\Tenant\Patient as Patient_Model;

use App\Models\Tenant\NotesForPatient;
use App\Models\Admin\Location;
use App\Models\Tenant\EventsLog;
use App\Models\Tenant\PatientLocation; 
use DB;
use Illuminate\Http\Request;
use PDF;

class Checking extends Near_Miss
{
    public  function  checkings(Request $request)
    {
       $data['all_pharmacies']=User::all();
       $data['all_Location'] = Location::get();
       return  view('admin.checkings')->with($data); 
    }

    public function packed()
    {

    $data['all_pharmacies']=User::all();
    $data['all_Location'] = Location::get();
    return  view('admin.packed')->with($data); 
    }

    /* save Checking here  */
    public  function  save_checking(Request $request)
    {       
        // dd($request);
        $validate_array=array(
            'company_name'        => 'required|numeric|min:1',
            'patient_name'      => 'required|min:1',
            'no_of_weeks'       => 'required|numeric|min:1',
            'pharmacist_signature'   => 'required|string|max:999000',
        ); 

        $folderPath = public_path('signatures/checkings/pharmacy\\');

        $image_parts = explode(";base64,", $request->pharmacist_signature);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . uniqid() . '.'.$image_type;
        file_put_contents($file, $image_base64);

        $patientIDS = ($request->patient_name);
        foreach ($patientIDS as $ipatient_name ) {
            $request->patient_name = $ipatient_name;
            $insert_data=array(
                'patient_id'=>$request->patient_name,
                'no_of_weeks'=>$request->no_of_weeks,
                'location'=>$request->location?implode(',',$request->location):'',
                'pharmacist_signature'=>$file,
                'note_from_patient'=>$request->note
            );
             // dd($request->patient_name);


        
                if($request->dd){ $insert_data['dd']=1; }else{ $insert_data['dd']=0; }
                $insert_data['website_id']='1'; 
                if(!empty($request->session()->get('admin')))
                {
                    $insert_data['website_id']=$request->company_name; 
                    $insert_data['created_by']='-'.$request->session()->get('admin')['id'];
                    $validate_array['company_name']='required'; 
                }

                $validator = $request->validate($validate_array);
                
                /* Pharmacy Signature   */
               

                $this->get_connection($request->company_name); 
                $save_res=Checking_Model::insert_data($insert_data);
                //    Patient Location
                $location_data['locations']=$insert_data['location'];
                $location_data['patient_id']=$request->patient_name;
                $location_data['action_by' ] = $request->session()->get('admin')->id;
                PatientLocation::insert_data($location_data);
//    End
                if(isset($request->note) && $request->note!=""){
                    $getPatient=Patient_Model::find($request->patient_name);
                    $getPatient->dob;
                    $insert=array(
                        'patient_id' => $request->patient_name,
                        'dob' => $getPatient->dob,
                        'notes_for_patients' => $request->note,
                        'notes_as_text' => 0
                );
                if(!empty($request->session()->get('admin')))
                {
                    $insert['website_id']=$request->company_name; 
                    $insert['created_by']=$request->session()->get('admin')['id'];
                }

                $insertedData=NotesForPatient::create($insert); 
                EventsLog::create([
                    'website_id' => $request->company_name,
                    'action_by' => '-'.$request->session()->get('admin')->id,
                    'action' => 1,
                    'action_detail' => 'Note For Patient',
                    'comment' => 'Create Note For Patient',
                    'patient_id' => $request->patient_name,
                    'ip_address' => $request->ip(),
                    'type' => 'SuperAdmin',
                    'user_agent' => $request->userAgent(),
                    'authenticated_by' => 'packnpeaks',
                    'status' => 1
                   ]);
                }
                EventsLog::create([
                    'website_id' => $request->company_name,
                    'action_by' => '-'.$request->session()->get('admin')->id,
                    'action' => 1,
                    'action_detail' => 'Checking',
                    'comment' => 'Create Checking',
                    'patient_id' => $request->patient_name,
                    'ip_address' => $request->ip(),
                    'type' => 'SuperAdmin',
                    'user_agent' => $request->userAgent(),
                    'authenticated_by' => 'packnpeaks',
                    'status' => 1
               ]);


        }
               
        
        
        DB::disconnect('tenant');
        return redirect()->back()->with(["msg"=>'<div class="alert alert-success"> <strong> Patient Checking </strong> Added Successfully.</div>']);
        
        
    }


    public  function  save_packed(Request $request)
    {       
        // dd($request);
        $validate_array=array(
            'company_name'        => 'required|numeric|min:1',
            'patient_name'      => 'required|min:1',
            'no_of_weeks'       => 'required|numeric|min:1',
            'pharmacist_signature'   => 'required|string|max:999000',
        ); 
        $folderPath = public_path('signatures/packed/pharmacy\\');

        $image_parts = explode(";base64,", $request->pharmacist_signature);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . uniqid() . '.'.$image_type;
        file_put_contents($file, $image_base64);
    
        $patientIDS = ($request->patient_name);
        foreach ($patientIDS as $ipatient_name ) {
            $request->patient_name = $ipatient_name;
            $insert_data=array(
                'patient_id'=>$request->patient_name,
                'no_of_weeks'=>$request->no_of_weeks,
                'location'=>$request->location?implode(',',$request->location):'',
                'pharmacist_signature'=>$file,
                'note_from_patient'=>$request->note
            );
             // dd($request->patient_name);


        
                if($request->dd){ $insert_data['dd']=1; }else{ $insert_data['dd']=0; }
                $insert_data['website_id']='1'; 
                if(!empty($request->session()->get('admin')))
                {
                    $insert_data['website_id']=$request->company_name; 
                    $insert_data['created_by']='-'.$request->session()->get('admin')['id'];
                    $validate_array['company_name']='required'; 
                }

                $validator = $request->validate($validate_array);
                
                /* Pharmacy Signature   */
              

                $this->get_connection($request->company_name); 
                $save_res=Packed_Model::insert_data($insert_data);
                //    Patient Location
                $location_data['locations']=$insert_data['location'];
                $location_data['patient_id']=$request->patient_name;
                $location_data['action_by' ] = $request->session()->get('admin')->id;
                PatientLocation::insert_data($location_data);
//    End
                if(isset($request->note) && $request->note!=""){
                    $getPatient=Patient_Model::find($request->patient_name);
                    $getPatient->dob;
                    $insert=array(
                        'patient_id' => $request->patient_name,
                        'dob' => $getPatient->dob,
                        'notes_for_patients' => $request->note,
                        'notes_as_text' => 0
                );
                if(!empty($request->session()->get('admin')))
                {
                    $insert['website_id']=$request->company_name; 
                    $insert['created_by']=$request->session()->get('admin')['id'];
                }

                $insertedData=NotesForPatient::create($insert); 
                EventsLog::create([
                    'website_id' => $request->company_name,
                    'action_by' => '-'.$request->session()->get('admin')->id,
                    'action' => 1,
                    'action_detail' => 'Note For Patient',
                    'comment' => 'Create Note For Patient',
                    'patient_id' => $request->patient_name,
                    'ip_address' => $request->ip(),
                    'type' => 'SuperAdmin',
                    'user_agent' => $request->userAgent(),
                    'authenticated_by' => 'packnpeaks',
                    'status' => 1
                   ]);
                }
                EventsLog::create([
                    'website_id' => $request->company_name,
                    'action_by' => '-'.$request->session()->get('admin')->id,
                    'action' => 1,
                    'action_detail' => 'Checking',
                    'comment' => 'Create Checking',
                    'patient_id' => $request->patient_name,
                    'ip_address' => $request->ip(),
                    'type' => 'SuperAdmin',
                    'user_agent' => $request->userAgent(),
                    'authenticated_by' => 'packnpeaks',
                    'status' => 1
               ]);


        }
               
        
        
        DB::disconnect('tenant');
        return redirect()->back()->with(["msg"=>'<div class="alert alert-success"> <strong> Patient Packed </strong> Added Successfully.</div>']);
        
        
    }
    public function export_excel_packed()
    {
        $all_pharmacy=User::all();
        $newarray=array();
        foreach($all_pharmacy  as $row){ 
              $this->get_connection($row->website_id);
              $get_audit=Packed_Model::get_all();
           
                foreach($get_audit as $col) {
                    $col->pharmacy=$row->company_name;
                    $newarray[]=$col;
                }
              DB::disconnect('tenant');
        } 
        $data['all_packed']=$newarray;
      //  dd('raza');
        $proData = "";
        if (count($data) >0) {
         $proData .= '<table  border height="50" width="60">
         <tr  >
     
         <th>Patient_id</th>
         <th>DOB</th>
         <th>Image</th>
         <th>weeks_last_picked_up</th>
         
         
         <th>pharmacist_sign</th>
         <th>patient_sign</th>
        
    
         </tr>';
     
         foreach ($data['all_packed'] as $img)
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
    
 public function export_pdf_packed()
 {
    $all_pharmacy=User::all();
        $newarray=array();
        foreach($all_pharmacy  as $row){ 
              $this->get_connection($row->website_id);
              $get_audit=Packed_Model::get_all();
           
                foreach($get_audit as $col) {
                    $col->pharmacy=$row->company_name;
                    $newarray[]=$col;
                }
              DB::disconnect('tenant');
        } 
        $data['newarray']=$newarray;
        $pdf = PDF::loadView('checkingpdf', $data);
        return  $pdf->download(time().'.pdf');
 }
 
   
    public function email_checking_report(Request $request)
    {
        $email = $request->email;
        $start_date = $request->start_date  ;
        $end_date   = $request->end_date  ;
        
        $details['name'] = "PackPeak";
        $details['report_name'] = "Checkings Report";
        $details['date_range'] = "$start_date To $end_date";
        $details['url'] = "https://packpeak.co.au/checkinReport/$start_date/$end_date";

        \Mail::to($request->email)->send(new \App\Mail\AdminReportsEmail($details));
        return redirect()->back()->with(["msg"=>'<div class="alert alert-success"> <strong> Email Sent </strong> .</div>']);
    }
    /* checking Reports  */
    public function checkings_report()
    {
        $all_pharmacy=User::all();
        $newarray=array();
        foreach($all_pharmacy  as $row){ 
              $this->get_connection($row->website_id);
              $get_audit=Checking_Model::get_all();
                foreach($get_audit as $col) {
                    $col->pharmacy=$row->company_name;
                    $newarray[]=$col;
                }
              DB::disconnect('tenant');
        } 
        $data['all_checkings']=$newarray;  
        return  view('admin.checking_report')->with($data);
    }
 public function checking_export_excel()
 {
    $all_pharmacy=User::all();
        $newarray=array();
        foreach($all_pharmacy  as $row){ 
              $this->get_connection($row->website_id);
              $get_audit=Checking_Model::get_all();
                foreach($get_audit as $col) {
                    $col->pharmacy=$row->company_name;
                    $newarray[]=$col;
                }
              DB::disconnect('tenant');
        } 
        $data['all_checkings']=$newarray;  

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
 
     foreach ($data['all_checkings'] as $img)
     {      
      $proData .= '
         <tr>
         <td>'.$img->id.'</td>
         <td>'.$img->patient_id.'</td>
         <td>'.$img->no_of_weeks.'</td>
         <td>'.$img->note_from_patient.'</td>
       
       
         <td>
        
         <img src="'.$img->pharmacist_signature.'"  height="20" width="20">
        
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
 public function export_checking_pdf()
 {
     $all_pharmacy=User::all();
     $newarray=array();
     foreach($all_pharmacy  as $row){ 
           $this->get_connection($row->website_id);
           $get_audit=Checking_Model::get_all();
             foreach($get_audit as $col) {
                 $col->pharmacy=$row->company_name;
                 $newarray[]=$col;
             }
           DB::disconnect('tenant');
     } 
   //  $data['all_checkings']=$newarray;  
     $data['newarray']=$newarray;
     $pdf = PDF::loadView('checkingpdf', $data);
     return  $pdf->download(time().'.pdf');
 }
    public function packed_report()
    {
        //dd( 'raza'); 
        $all_pharmacy=User::all();
        $newarray=array();
        foreach($all_pharmacy  as $row){ 
              $this->get_connection($row->website_id);
              $get_audit=Packed_Model::get_all();
           
                foreach($get_audit as $col) {
                    $col->pharmacy=$row->company_name;
                    $newarray[]=$col;
                }
              DB::disconnect('tenant');
        } 
        $data['all_packed']=$newarray; 
      // dd( $data['all_packed']);
        return  view('admin.packed_report')->with($data);
    }
    /* archived checking Reports  */
    public function archived_checkings_report()
    {
        $all_pharmacy=User::all();
        $newarray=array();
        foreach($all_pharmacy  as $row){
              $this->get_connection($row->website_id);
              $get_audit=Checking_Model::get_archived();
                foreach($get_audit as $col) {
                    $col->pharmacy=$row->company_name;
                    $newarray[]=$col;
                }
              DB::disconnect('tenant');
        } 
        $data['all_checkings']=$newarray;  
        return  view('admin.archived_checking_report')->with($data);
    }


    public function archived_packed_report()
    {
        $all_pharmacy=User::all();
        $newarray=array();
        foreach($all_pharmacy  as $row){
              $this->get_connection($row->website_id);
              $get_audit=Packed_Model::get_archived();
                foreach($get_audit as $col) {
                    $col->pharmacy=$row->company_name;
                    $newarray[]=$col;
                }
              DB::disconnect('tenant');
        } 
        $data['all_packed']=$newarray;  
        return  view('admin.archived_packed_report')->with($data);
    }


/* Edit Checking  */
    public  function  edit_checking(Request $request)
    {
        $data['all_pharmacies']=User::all();
        $data['all_Location'] = Location::get();
        $this->get_connection($request->website_id);
        $data['get_checking']=Checking_Model::get_by_where(array('id'=>$request->row_id,'deleted_at'=>NULL)); 
        $data['all_patients']=Patient_Model::get_by_where(array('deleted_at'=>NULL,'website_id'=>$request->website_id));
        $data['patient_location']=PatientLocation::where(array('patient_id'=>$data['get_checking'][0]->patient_id))->get();
        DB::disconnect('tenant');
        
        return  view('admin.edit_checking')->with($data);
    }

    public  function  edit_packed(Request $request)
    {
        $data['all_pharmacies']=User::all();
        $data['all_Location'] = Location::get();
        $this->get_connection($request->website_id);
        $data['get_checking']=Packed_Model::get_by_where(array('id'=>$request->row_id,'deleted_at'=>NULL)); 
        $data['all_patients']=Patient_Model::get_by_where(array('deleted_at'=>NULL,'website_id'=>$request->website_id));
        $data['patient_location']=PatientLocation::where(array('patient_id'=>$data['get_checking'][0]->patient_id))->get();
        DB::disconnect('tenant');
        
        return  view('admin.edit_packed')->with($data);
    }

    /* Update Checking  */
    public  function  update_checking(Request $request)
    {

      //dump($request);

        $validate_array=array(
            'patient_name'      => 'required|min:1',
            'no_of_weeks'       => 'required|numeric|min:1'
        ); 

        $patientIDS = ($request->patient_name);
        foreach ($patientIDS as $ipatient_name ) {
            $request->patient_name = $ipatient_name;
            // 
            $update_data=array(
                'patient_id'=>$request->patient_name,
                'no_of_weeks'=>$request->no_of_weeks,
                'location'=>$request->location?implode(',',$request->location):'',
                'pharmacist_signature'=>$request->pharmacist_signature,
                'note_from_patient'=>$request->note
            );
            
            if($request->dd){ $update_data['dd']=1; }else{ $update_data['dd']=0; }
        
            if($request->pharmacist_signature){ 
                $update_data['pharmacist_signature']=$request->pharmacist_signature; 
                /* Pharmacy Signature   */
                $folderPath = public_path('upload\pharmacy\\');
                $image_parts = explode(";base64,", $request->pharmacist_signature);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $file = $folderPath . uniqid() . '.'.$image_type;
                file_put_contents($file, $image_base64);
            }
        
            $validator = $request->validate($validate_array);
        
            
            $this->get_connection($request->website_id); 
            Checking_Model::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),$update_data);
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
                'action_detail' => 'Checking',
                'comment' => 'Update Checking',
                'patient_id' => $request->patient_name,
                'ip_address' => $request->ip(),
                'type' => 'SuperAdmin',
                'user_agent' => $request->userAgent(),
                'authenticated_by' => 'packnpeaks',
                'status' => 1
            ]);
        }
        DB::disconnect('tenant');
        return redirect('admin/checkings_report')->with(["msg"=>'<div class="alert alert-success"> <strong>  Checking </strong> Updated 
        Successfully.</div>']);
    }


    public  function  update_packed(Request $request)
    {

      //dump($request);

        $validate_array=array(
            'patient_name'      => 'required|min:1',
            'no_of_weeks'       => 'required|numeric|min:1'
        ); 

        $patientIDS = ($request->patient_name);
        foreach ($patientIDS as $ipatient_name ) {
            $request->patient_name = $ipatient_name;
            // 
            $update_data=array(
                'patient_id'=>$request->patient_name,
                'no_of_weeks'=>$request->no_of_weeks,
                'location'=>$request->location?implode(',',$request->location):'',
                'pharmacist_signature'=>$request->pharmacist_signature,
                'note_from_patient'=>$request->note
            );
            
            if($request->dd){ $update_data['dd']=1; }else{ $update_data['dd']=0; }
        
            if($request->pharmacist_signature){ 
                $update_data['pharmacist_signature']=$request->pharmacist_signature; 
                /* Pharmacy Signature   */
                $folderPath = public_path('upload\pharmacy\\');
                $image_parts = explode(";base64,", $request->pharmacist_signature);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $file = $folderPath . uniqid() . '.'.$image_type;
                file_put_contents($file, $image_base64);
            }
        
            $validator = $request->validate($validate_array);
        
            
            $this->get_connection($request->website_id); 
            Packed_Model::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),$update_data);
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
                'action_detail' => 'Checking',
                'comment' => 'Update Checking',
                'patient_id' => $request->patient_name,
                'ip_address' => $request->ip(),
                'type' => 'SuperAdmin',
                'user_agent' => $request->userAgent(),
                'authenticated_by' => 'packnpeaks',
                'status' => 1
            ]);
        }
        DB::disconnect('tenant');
        return redirect('admin/packed_report')->with(["msg"=>'<div class="alert alert-success"> <strong>  Packed </strong> Updated 
        Successfully.</div>']);
    }

     /* Delete Return  */
     public function  delete_checking(Request $request)
     {
        $this->get_connection($request->website_id);
        $getdata=Checking_Model::find($request->row_id);
        Checking_Model::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id']));
        Checking_Model::delete_record($request->row_id); 
        EventsLog::create([
            'website_id' => $request->website_id,
            'action_by' => '-'.$request->session()->get('admin')->id,
            'action' => 3,
            'action_detail' => 'Checking',
            'comment' => 'Delete Checking',
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


     public function  delete_packed(Request $request)
     {
        $this->get_connection($request->website_id);
        $getdata=Checking_Model::find($request->row_id);
        Packed_Model::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id']));
        Packed_Model::delete_record($request->row_id); 
        EventsLog::create([
            'website_id' => $request->website_id,
            'action_by' => '-'.$request->session()->get('admin')->id,
            'action' => 3,
            'action_detail' => 'Checking',
            'comment' => 'Delete Checking',
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
     public function  soft_delete_checking(Request $request)
     {
        $this->get_connection($request->website_id);
        $getdata=Checking_Model::find($request->row_id);
        if($request->archivetypeid == 1 )
        {
            Checking_Model::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id'],'is_archive'=>'1'));
        }
        else
        {
            Checking_Model::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id'],'is_archive'=>'0'));
        }

        
        
        // Checking_Model::delete_record($request->row_id); 
        EventsLog::create([
            'website_id' => $request->website_id,
            'action_by' => '-'.$request->session()->get('admin')->id,
            'action' => 3,
            'action_detail' => 'Checking',
            'comment' => 'Soft Delete Checking',
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
      public function  soft_delete_packed(Request $request)
      {
         $this->get_connection($request->website_id);
         $getdata=Packed_Model::find($request->row_id);
         if($request->archivetypeid == 1 )
         {
            Packed_Model::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id'],'is_archive'=>'1'));
         }
         else
         {
            Packed_Model::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id'],'is_archive'=>'0'));
         }
 
         
         
         // Checking_Model::delete_record($request->row_id); 
         EventsLog::create([
             'website_id' => $request->website_id,
             'action_by' => '-'.$request->session()->get('admin')->id,
             'action' => 3,
             'action_detail' => 'Checking',
             'comment' => 'Soft Delete Checking',
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
     /* Multi  ple  delete  */
     public function multiple_delete_checking(Request $request)
    {
        $ids = explode(",",$request->row_id);
        $website_Ids=explode(",",$request->website_id);
        for($i=0;$i < count($ids);$i++)
        {
               $this->get_connection($website_Ids[$i]);
               $getdata=Checking_Model::find($ids[$i]);
               Checking_Model::update_where(array('id'=>$ids[$i],'website_id'=>$website_Ids[$i]),array('deleted_by'=>'-'.$request->session()->get('admin')['id']));
               Checking_Model::delete_record($ids[$i]); 
               EventsLog::create([
                'website_id' => $website_Ids[$i],
                'action_by' => '-'.$request->session()->get('admin')->id,
                'action' => 3,
                'action_detail' => 'Checking',
                'comment' => 'Delete Checking',
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
     public  function  checking_info(Request $request)
     {
        $this->get_connection($request->website_id); 
        $data['checking']=Checking_Model::get_by_where(array('website_id'=>$request->website_id,'id'=>$request->row_id));
        $data['mode']='checking_info';
        DB::disconnect('tenant');
        return view('admin.ajax')->with($data);
     }

       
  
}
