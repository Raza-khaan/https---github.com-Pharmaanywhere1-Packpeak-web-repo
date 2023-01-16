<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\User;
use Validator,DB;
use App\Models\Tenant\EventsLog; 
class EventsLogController extends Controller
{
    public $successStatus = 200;
    public  function  get_connection($website_id)
    {
        $get_user=User::get_by_column('website_id',$website_id);
        config(['database.connections.tenant.database' => $get_user[0]->host_name]);
         DB::purge('tenant');
         DB::reconnect('tenant');
    }
     public  function  saveEventsLogs(Request $request){
        $getWebsite=User::where('website_id',$request->website_id)->get();
        if(count($getWebsite)){
            $this->get_connection($request->website_id); 
            $validator = Validator::make($request->all(), [
                'website_id'         => 'required|numeric|min:1',
                'action_by'          => 'required|numeric|min:1',
                'action'             => 'required|numeric|min:1|max:7',
                'action_detail'      => 'required|numeric|min:1',
                'ip_address'         => 'required|ip',
                'type'               => 'required|string|max:255',   //  admin .  superadmin,  technician 
                'created_at'         => 'required|date_format:Y-m-d H:i:s'
            ]);
            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
            }

            $eventLogs=EventsLog::create([
                'website_id' => $request->website_id,
                'action_by' => $request->action_by,
                'action' => $request->action,
                'action_detail' => $request->action_detail,
                'comment' => $request->comment?$request->comment:null,
                'patient_id' => $request->patient_id?$request->patient_id:null,
                'ip_address' => $request->ip_address,
                'type' => $request->type,
                'user_agent' => $request->userAgent(),
                'authenticated_by' => 'packnpeaks',
                'status' => 1
              ]);

            
            DB::disconnect('tenant');
            return response()->json(['success' => $eventLogs], $this-> successStatus); 
        }
        else
        {
            return response()->json(['error'=>'Pharmacy not  found!'], 401); 
        }

     }
}
