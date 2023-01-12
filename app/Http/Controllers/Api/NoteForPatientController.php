<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\NotesForPatient;
use Illuminate\Support\Facades\Auth; 
use App\User;
use Validator,DB;
use App\Helpers\Helper;
class NoteForPatientController extends Controller
{
    public $successStatus = 200;
    public  function  get_connection($website_id)
    {
        $get_user=User::get_by_column('website_id',$website_id);
        config(['database.connections.tenant.database' => $get_user[0]->host_name]);
         DB::purge('tenant');
         DB::reconnect('tenant');
    }

    /**
     * Create Note For Patient 
    */

    public  function createNoteForPatient(Request $request){
        $getWebsite=User::where('website_id',$request->website_id)->get();
        if(count($getWebsite)){
            $this->get_connection($request->website_id); 
            $validator = Validator::make($request->all(), [
                'website_id'        => 'required|numeric|min:1',
                'patient_name'      => 'required|numeric|min:1',
                'dob'               => 'date_format:Y-m-d|before:tomorrow',
                'notes_for_patients'=> 'required|string|max:10000',
                'notes_as_text'     => 'max:1|min:0'
            ]);
            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
            }
            $insert_data=array(
                'patient_id'=>$request->patient_name,
                'dob'=>$request->dob,
                'notes_for_patients'=>$request->notes_for_patients
            );
            $insert_data['notes_as_text']=(isset($request->notes_as_text) &&$request->notes_as_text ==true) ?1:0; 
            $user = Auth::user();
            $insert_data['website_id']=$request->website_id;
            $insert_data['created_by']=$request->created_by;
            if($user->website_id!=$request->website_id){
                return response()->json(['error'=>'website id is not match to login pharmacy`s website id '], 401); 
            }
            
            
            $createdNoteForPatient=NotesForPatient::create($insert_data);
            if($insert_data['notes_as_text']==1 && preg_match('/^\d{s10}$/',$createdNoteForPatient->patients->phone_number)) 
            {
             Helper::smsSendToMobile('+91'.$createdNoteForPatient->patients->phone_number,$request->notes_for_patients);
            }
            // else 
            // {
            //     return response()->json(['error'=>'Patient Phone Number Invalid!'], 401);
            // }
            // echo json_encode($insert_data);die;
            DB::disconnect('tenant');
            return response()->json(['success' => $createdNoteForPatient], $this-> successStatus); 
        }
        else
        {
            return response()->json(['error'=>'Pharmacy not  found!'], 401); 
        }
    }
}
