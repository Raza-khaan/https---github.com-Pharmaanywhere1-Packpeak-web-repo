<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Audit;
use Illuminate\Support\Facades\Auth; 
use App\User;
use Validator,DB;
class AuditController extends Controller
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
     * Create Patient`s Audit
    */

    public function createPatientAudit(Request $request){
        $getWebsite=User::where('website_id',$request->website_id)->get();
        if(count($getWebsite)){
            $this->get_connection($request->website_id); 
            $validator = Validator::make($request->all(), [
                'website_id'          => 'required|numeric|min:1',
                'patient_name'        => 'required|numeric|min:1',
                'no_of_weeks'         => 'required|numeric|min:1',
                'store'               => 'required|numeric|min:1',
                // 'staff_initials'      => 'required|string|max:255'

            ]);
            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
            }
            $insert_data=array(
                'patient_id'=>$request->patient_name,
                'no_of_weeks'=>$request->no_of_weeks,
                'store'=>$request->store,
                'staff_initials'=>$request->staff_initials
            );
            if(isset($request->other_store))
            {
                $insert_data['store_others_desc']=$request->other_store; 
            }
            $user = Auth::user();
            $insert_data['website_id']=$request->website_id;
            $insert_data['created_by']=$request->created_by;
            if($user->website_id!=$request->website_id){
                return response()->json(['error'=>'website id is not match to login pharmacy`s website id '], 401); 
            }
            $createdPatientAudit=Audit::create($insert_data);
            // echo json_encode($insert_data);
            // die;
            DB::disconnect('tenant');
            return response()->json(['success' => $createdPatientAudit], $this-> successStatus); 
        }
        else
        {
            return response()->json(['error'=>'Pharmacy not  found!'], 401); 
        }
    }
}
