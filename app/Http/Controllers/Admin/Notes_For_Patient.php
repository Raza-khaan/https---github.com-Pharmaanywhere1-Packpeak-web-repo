<?php

namespace App\Http\Controllers\Admin;

use GuzzleHttp\Client;
use ClickSend;
use App\smssettings;
use App\Http\Controllers\Controller;
use App\User; 
use Illuminate\Http\Request;
use App\Models\Tenant\NotesForPatient;
use App\Models\Tenant\Patient as Patient_Model;
use App\Models\Tenant\EventsLog; 
use App\Exports\Returnexport;
use Maatwebsite\Excel\Facades\Excel;
use DB; 
use Carbon\Carbon;
use App\Helpers\Helper;

class Notes_For_Patient extends Near_Miss
{
    public  function  notes_for_patients()
    {
        

        $data['all_pharmacies']=User::all();
        return view('admin.notes_for_patients')->with($data); 
    }
   /* save the note for  Patients   */
   public  function save_note_for_patient(Request $request)
   {    
        $validate_array=array(
            'company_name'        => 'required|numeric|min:1',
            'patient_name'      => 'required|numeric|min:1',
            'dob'               => 'date_format:d/m/Y|before:tomorrow',
            'note_for_patient'=> 'required|string|max:10000',
            'notes_as_text'     => 'max:1|min:0' 
        ); 
        
        $insert_data=array(
            'patient_id'=>$request->patient_name,
            'dob'=>Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
            'notes_for_patients'=>$request->note_for_patient
        ); 
        if($request->send_note)
        {
        $insert_data['notes_as_text']=1; 
        }
        $insert_data['website_id']='1'; 
        if(!empty($request->session()->get('admin')))
        {
            $insert_data['website_id']=$request->company_name; 
            $insert_data['created_by']='-'.$request->session()->get('admin')['id'];
            $validate_array['company_name']='required'; 
        }
        $validator = $request->validate($validate_array);
        // print_r($insert_data); die; 
        $this->get_connection($request->company_name); 
        $save_res=NotesForPatient::insert_data($insert_data);


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
        DB::disconnect('tenant');







        if(isset($insert_data['notes_as_text']) && $insert_data['notes_as_text']==1 && preg_match('/^[6789]\d{9}$/',$save_res->patients->phone_number)) 
        {   //  AUS No  REG// ^\({0,1}((0|\+61)(2|4|3|7|8)){0,1}\){0,1}( |-){0,1}[0-9]{2}( |-){0,1}[0-9]{2}( |-){0,1}[0-9]{1}( |-){0,1}[0-9]{3}$ 
          $result=Helper::smsSendToMobile('+91'.$save_res->patients->phone_number,$insert_data['notes_for_patients']);
        }
        // else 
        // {
        //   return redirect()->back()->with(["msg"=>'<div class="alert alert-danger"> Patient Phone Number<strong>  Invalid ! </strong> .</div>']);
        // }


// Configure HTTP basic authorization: BasicAuth
$config = ClickSend\Configuration::getDefaultConfiguration()
->setUsername('amr_eid@msn.com')
->setPassword('5N^u#SLo2!w43SLk');

$apiInstance = new ClickSend\Api\SMSApi(new \GuzzleHttp\Client(),$config);
$msg = new \ClickSend\Model\SmsMessage();
$msg->setBody($request->note_for_patient); 
$msg->setTo('+91'.$save_res->patients->phone_number);
 //$msg->setTo("+923234774241");
$msg->setSource("+61422222222");


// \ClickSend\Model\SmsMessageCollection | SmsMessageCollection model
$sms_messages = new \ClickSend\Model\SmsMessageCollection(); 
$sms_messages->setMessages([$msg]);

try {
$result = $apiInstance->smsSendPost($sms_messages);
print_r($result);
} catch (Exception $e) {
echo 'Exception when calling SMSApi->smsSendPost: ', $e->getMessage(), PHP_EOL;
}






        return redirect()->back()->with(["msg"=>'<div class="alert alert-success"> <strong> Patient Notes </strong> Added Successfully.</div>']);
   }

    public  function  notes_for_patients_report()
    {
        $all_pharmacy=User::all();
        
        $newarray=array();
        foreach($all_pharmacy  as $row){
              $this->get_connection($row->website_id);
              $get_audit=NotesForPatient::get_all();
                foreach($get_audit as $col) {
                    $col->pharmacy=$row->company_name;
                    $newarray[]=$col;
                }
              DB::disconnect('tenant');
        } 
        
        $data['all_not_for_patient']=$newarray; 
        
        
        return view('admin.notes_for_patients_report')->with($data); 
    }

    public  function  archived_notes_for_patients_report()
    {
        $all_pharmacy=User::all();
        $newarray=array();
        foreach($all_pharmacy  as $row){
              $this->get_connection($row->website_id);
              $get_audit=NotesForPatient::get_archived();
                foreach($get_audit as $col) {
                    $col->pharmacy=$row->company_name;
                    $newarray[]=$col;
                }
              DB::disconnect('tenant');
        } 
        $data['all_not_for_patient']=$newarray; 
        return view('admin.archived_notes_for_patients_report')->with($data); 
    }

    /* Edit Note for Patient  */
   /*  public  function edit_note_for_patient(Request $request)
    {
        $data['all_pharmacies']=User::all();
        $this->get_connection($request->website_id);
        $data['note_for_patient']=NotesForPatient::get_by_where(array('id'=>$request->row_id,'deleted_at'=>NULL)); 
        $data['all_patients']=Patient_Model::get_by_where(array('deleted_at'=>NULL,'website_id'=>$request->website_id));
        DB::disconnect('tenant');
        if(count($data['note_for_patient']))
        {
            return view('admin.edit_notes_for_patients_report')->with($data); 
        }
    } */

    /* update note For Patient */
  /*   public function  update_note_for_patient(Request $request)
    {
        $validate_array=array(
            'patient_name'=>'required',
            'dob'=>'required',
            'note_for_patient'=>'required'
        ); 
        $update_data=array(
            'patient_id'=>$request->patient_name,
            'dob'=>$request->dob,
            'notes_for_patients'=>$request->note_for_patient
        ); 
        if($request->send_note){ $update_data['notes_as_text']=1;  }else{ $update_data['notes_as_text']=0; }
        $validator = $request->validate($validate_array);
        $this->get_connection($request->website_id);
        NotesForPatient::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),$update_data);
        DB::disconnect('tenant');
        return redirect('admin/notes_for_patients_report')->with(["msg"=>'<div class="alert alert-success"> <strong> Patient Notes </strong> Updated Successfully.</div>']);
    } */


    // public function  delete_note_for_patient(Request $request)
    // {
    //    $this->get_connection($request->website_id);
    //    NotesForPatient::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id']));
    //    NotesForPatient::delete_record($request->row_id); 
    //    DB::disconnect('tenant');
    //    echo '200'; 
    // }

    /* Edit Note for Patient  */
    public  function edit_note_for_patient(Request $request)
    {
        $data['all_pharmacies']=User::all();
        $this->get_connection($request->website_id);
        $data['note_for_patient']=NotesForPatient::get_by_where(array('id'=>$request->row_id,'deleted_at'=>NULL)); 
        $data['all_patients']=Patient_Model::get_by_where(array('deleted_at'=>NULL,'website_id'=>$request->website_id));
        DB::disconnect('tenant');
        if(count($data['note_for_patient']))
        {
            return view('admin.edit_notes_for_patients_report')->with($data); 
        }
    }

    /* update note For Patient */
    public function  update_note_for_patient(Request $request)
    {
        $validate_array=array(
            'patient_name'      => 'required|numeric|min:1',
            'dob'               => 'date_format:d/m/Y|before:tomorrow',
            'note_for_patient'  => 'required|string|max:10000',
            'notes_as_text'     => 'max:1|min:0' 
        ); 
        $update_data=array(
            'patient_id'=>$request->patient_name,
            'dob'=>Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
            'notes_for_patients'=>$request->note_for_patient
        ); 
        if($request->send_note){ $update_data['notes_as_text']=1;  }else{ $update_data['notes_as_text']=0; }
        $validator = $request->validate($validate_array);
        $this->get_connection($request->website_id);
        NotesForPatient::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),$update_data);
        EventsLog::create([
            'website_id' => $request->website_id,
            'action_by' => '-'.$request->session()->get('admin')->id,
            'action' => 2,
            'action_detail' => 'Note For Patient',
            'comment' => 'Update Note For Patient',
            'patient_id' => $request->patient_name,
            'ip_address' => $request->ip(),
            'type' => 'SuperAdmin',
            'user_agent' => $request->userAgent(),
            'authenticated_by' => 'packnpeaks',
            'status' => 1
           ]);
        $save_res=Patient_Model::where('id',$request->patient_name)->first();
        DB::disconnect('tenant');

        if( isset($update_data['notes_as_text']) && $update_data['notes_as_text']==1 && preg_match('/^[6789]\d{9}$/',$save_res->phone_number)) 
        {    
           //  AUS No  REG// ^\({0,1}((0|\+61)(2|4|3|7|8)){0,1}\){0,1}( |-){0,1}[0-9]{2}( |-){0,1}[0-9]{2}( |-){0,1}[0-9]{1}( |-){0,1}[0-9]{3}$ 
           $result=Helper::smsSendToMobile('+91'.$save_res->phone_number,$update_data['notes_for_patients']);
        }
        // else 
        // {
        //   return redirect()->back()->with(["msg"=>'<div class="alert alert-danger"> Patient Notes Updated and  Phone Number<strong>  Invalid ! </strong> .</div>']);
        // }
        return redirect('admin/notes_for_patients_report')->with(["msg"=>'<div class="alert alert-success"> <strong> Patient Notes </strong> Updated Successfully.</div>']);
    }


    public function  delete_note_for_patient(Request $request)
    {
       $this->get_connection($request->website_id);
       $getdata=NotesForPatient::find($request->row_id);
       NotesForPatient::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id']));
       NotesForPatient::delete_record($request->row_id); 
       EventsLog::create([
        'website_id' => $request->website_id,
        'action_by' => '-'.$request->session()->get('admin')->id,
        'action' => 3,
        'action_detail' => 'Note For Patient',
        'comment' => 'Delete Note For Patient',
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
    
    public function  soft_delete_note_for_patient(Request $request)
    {

        
       
       $this->get_connection($request->website_id);
       
       $getdata=NotesForPatient::find($request->row_id);
     
       if($request->archivetypeid == 1 )
       {
        NotesForPatient::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id'],'is_archive'=>'1'));
       }
       else if($request->archivetypeid == 0 )
       {
        NotesForPatient::update_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id'],'is_archive'=>'0'));
       }
       
       
       
    //    NotesForPatient::soft_delete_record($request->row_id); 
       
       EventsLog::create([
        'website_id' => $request->website_id,
        'action_by' => '-'.$request->session()->get('admin')->id,
        'action' => 3,
        'action_detail' => 'Note For Patient',
        'comment' => 'Soft Delete Note For Patient',
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


    public function  soft_unarchive_note_for_patient(Request $request)
    {

        
       
       $this->get_connection($request->website_id);
       
       $getdata=NotesForPatient::find($request->row_id);
       NotesForPatient::update_unarchive_where(array('id'=>$request->row_id,'website_id'=>$request->website_id),array('deleted_by'=>'-'.$request->session()->get('admin')['id'],'is_archive'=>'0'));
    //    NotesForPatient::soft_delete_record($request->row_id); 
       
       EventsLog::create([
        'website_id' => $request->website_id,
        'action_by' => '-'.$request->session()->get('admin')->id,
        'action' => 3,
        'action_detail' => 'Note For Patient',
        'comment' => 'Soft Delete Note For Patient',
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
    

    public function multiple_delete_note_for_patient(Request $request)
    {
        $ids = explode(",",$request->row_id);
        $website_Ids=explode(",",$request->website_id);
        for($i=0;$i < count($ids);$i++)
        {
            $this->get_connection($website_Ids[$i]);
            $getdata=NotesForPatient::find($ids[$i]);
            NotesForPatient::update_where(array('id'=>$ids[$i],'website_id'=>$website_Ids[$i]),array('deleted_by'=>'-'.$request->session()->get('admin')['id']));
            NotesForPatient::delete_record($ids[$i]); 
            EventsLog::create([
                'website_id' => $website_Ids[$i],
                'action_by' => '-'.$request->session()->get('admin')->id,
                'action' => 3,
                'action_detail' => 'Note For Patient',
                'comment' => 'Delete Note For Patient',
                'patient_id' => $getdata->patient_id,
                'ip_address' => $request->ip(),
                'type' => 'SuperAdmin',
                'user_agent' => $request->userAgent(),
                'authenticated_by' => 'packnpeaks',
                'status' => 1
               ]);
            DB::disconnect('tenant');

        }  
        return response()->json(['status'=>true,'message'=>"Patient note  deleted successfully."]);
    }

    /* Multiple Export  */
    public  function multiple_pdf_export_note_for_patient(Request $request)
    {
        $ids = explode(",",$request->row_id);
        $website_Ids=explode(",",$request->website_id);
        return Excel::download(new Returnexport($ids,$website_Ids),'Financial_layout_'.date("Yisdm").'.xlsx');
       
    }
    // public  function ght(Request $request)
    // {
    //     $ids = explode(",",$request->row_id);
    //     $website_Ids=explode(",",$request->website_id);
    //     return Excel::download(new Returnexport($ids,$website_Ids),'Financial_layout_'.date("Yisdm").'.xlsx');
    //    // $m=(new Returnexport($ids,$website_Ids))->download('invoices.xlsx');
    //     // echo $m; die; 
    //     // return response()->download(storage_path($m));
    //     //header('Content-Type: text/csv');
    //     // echo file_get_contents('invoice.xlsx');
    // }



/* public  function multiple_pdf_export_note_for_patient(Request $request)
    {
       
        $ids = explode(",",$request->row_id);
        $website_Ids=explode(",",$request->website_id);

         $newarray=[];
        for($i=0;$i < count($ids);$i++)
        {
            $this->get_connection($website_Ids[$i]);
            NotesForPatient::update_where(array('id'=>$ids[$i],'website_id'=>$website_Ids[$i]),array('deleted_by'=>'-'.$request->session()->get('admin')['id']));
            NotesForPatient::delete_record($ids[$i]); 
            $get_data=NotesForPatient::get_by_where(array('id'=>$ids[$i],'website_id'=>$website_Ids[$i])); 
            // $newarray['pharmacy']=$row->company_name;
            $newarray[]=$get_data[0];
            DB::disconnect('tenant');
            
        }    

        $alias = [
            'product_list' => Returnexport::class
        ];
         return Excel::download(new Returnexport,'Financial_layout_'.date("Yisdm").'.xlsx');
         return Excel::download(new Returnexport,'Note_For_Patient_'.date("Yisdm").'.csv');
    } 
  */






public function  sms_tracking_report()
  {
  
  $all_pharmacy=User::all();
  $newarray=array();
  $data=[];
  $sms_trackings=[];
  $patients_names=[];
  
  foreach($all_pharmacy as $row){
  
  $this->get_connection($row->website_id);
  $all_patients=NotesForPatient::where(['notes_as_text'=>1])->get();

  $sms_trackings[$row->company_name]=[];
  $patients_names[$row->company_name]=Patient_Model::get()->keyBy('id');
  
  foreach($all_patients as $all_patient){
  
  if(array_key_exists($all_patient->patient_id,$sms_trackings[$row->company_name])){
  $dateArr=explode('-',$all_patient->created_at);
  $sms_trackings[$row->company_name][$all_patient->patient_id][(int)$dateArr[1]]['total']+=1;
  
  }else{
  
  $sms_trackings[$row->company_name][$all_patient->patient_id]=$this->monthArr();
  
  //echo '<pre>';print_r($sms_trackings);
  }
  }
  DB::disconnect('tenant');
  
  }
  
  //return $patients_names;
  $data['patients_names']=$patients_names;
  $data['sms_trackings']=$sms_trackings;
  return view('admin.sms_tracking_report',$data);
  }


  protected function monthArr(){
    //echo '<br/>'.$patient_id;
    $momnthArr[1]['total']=0;
    $momnthArr[2]['total']=0;
    $momnthArr[3]['total']=0;
    $momnthArr[4]['total']=0;
    $momnthArr[5]['total']=0;
    $momnthArr[6]['total']=0;
    $momnthArr[7]['total']=0;
    $momnthArr[8]['total']=0;
    $momnthArr[9]['total']=0;
    $momnthArr[10]['total']=0;
    $momnthArr[11]['total']=0;
    $momnthArr[12]['total']=0;
    
    return $momnthArr;
    
    }


    /*GET    Details */
     public  function  noteForPatients_info(Request $request)
     {
        $this->get_connection($request->website_id); 
        $data['noteForPatients']=NotesForPatient::get_by_where(array('website_id'=>$request->website_id,'id'=>$request->row_id));
        $data['mode']='noteForPatients_info';
        DB::disconnect('tenant');
        return view('admin.ajax')->with($data);
     }

     public function email_note_for_patient_report(Request $request)
     {
         $email = $request->email;
         $start_date = $request->start_date  ;
         $end_date   = $request->end_date  ;
        
         $details['name'] = "PackPeak";
         $details['report_name'] = "Note For Patient Report";
         $details['date_range'] = "$start_date To $end_date";
         $details['url'] = "https://packpeak.co.au/noteforpatientReport/$start_date/$end_date";

         \Mail::to($request->email)->send(new \App\Mail\AdminReportsEmail($details));
         return redirect()->back()->with(["msg"=>'<div class="alert alert-success"> <strong> Email Sent </strong> .</div>']);
     }
}
