<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\MissedPatient; 
use App\Models\Tenant\Patient;
use Illuminate\Support\Facades\Auth; 
use App\User;
use Validator,DB;
class NearMissController extends Controller
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
     * Create Near Miss 
    */
    public function createNearMiss(Request $request){
        $getWebsite=User::where('website_id',$request->website_id)->get();
        if(count($getWebsite)){
            $this->get_connection($request->website_id); 
            $validator = Validator::make($request->all(), [
                'website_id'        => 'required|numeric|min:1',
                'person_involved'        => 'required|string|max:255',
                'initials'         => 'required|string|max:255'
            ]);
            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
            }

            $insert_data=array(
                'person_involved'=>$request->person_involved,
                'other'=>isset($request->other)?$request->other:'',
                'initials'=>$request->initials
            ); 

            if(isset($request->missed_tablet) && $request->missed_tablet=='1' )
            {
                $insert_data['missed_tablet']=1; 
            }
            else{
             $insert_data['missed_tablet']=0; 
            }
            if(isset($request->extra_tablet) && $request->extra_tablet=='1' )
            {
                $insert_data['extra_tablet']=1;
            }
            else{
             $insert_data['extra_tablet']=0; 
            }
            if(isset($request->wrong_tablet) && $request->wrong_tablet=='1' )
            {
                $insert_data['wrong_tablet']=1;
            }
            else{
             $insert_data['wrong_tablet']=0; 
            }
            if(isset($request->wrong_day) && $request->wrong_day=='1' )
            {
                $insert_data['wrong_day']=1;
            }
            else{
             $insert_data['wrong_day']=0; 
            }
            $user = Auth::user();
            $insert_data['website_id']=$request->website_id;
            $insert_data['created_by']=$request->created_by;
            if($user->website_id!=$request->website_id){
                return response()->json(['error'=>'website id is not match to login pharmacy`s website id '], 401); 
            }
            $createdNearMiss=MissedPatient::create($insert_data);
            // echo json_encode($insert_data);die;
            DB::disconnect('tenant');
            return response()->json(['success' => $createdNearMiss], $this-> successStatus); 
        }
        else
        {
            return response()->json(['error'=>'Pharmacy not  found!'], 401); 
        }
    }
}
