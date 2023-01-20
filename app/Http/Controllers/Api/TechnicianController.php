<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant\AccessLevel;
use App\Models\Tenant\Company;
use Illuminate\Support\Facades\Auth; 
use App\User;
use Validator,DB;
class TechnicianController extends Controller
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
     * create  Technician
    */
    public  function createTechnician(Request $request){
        //  print_r($request->all());
        $getWebsite=User::where('website_id',$request->website_id)->get();
        if(count($getWebsite)){
            $this->get_connection($request->website_id); 
            $validator = Validator::make($request->all(), [
                'website_id'        => 'required|numeric|min:1',
                'first_name'        => 'required|string|max:255',
                'last_name'         => 'required|string|max:255',
                'email'             => 'required|string|email|min:6|max:255|unique:tenant.companies',
                'password'          => 'required|string|min:4',
                'confirm_password'  => 'required|string|same:password|min:4',
                'term'              => 'required',
                'phone'             => 'required|string|max:12|min:10',
                'username'          => 'required|string|min:6|max:20|unique:tenant.companies',
                'pin'               => 'required|numeric|min:4',
                'address'           => 'required|string|max:255',
                'role'              => 'required|string|max:255'
            ]);

            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
            }
            $insert_data=array(
                'name'          => $request->first_name.' '.$request->last_name,
                'initials_name' => strtoupper(substr($request->first_name,0,1)).'.'.strtoupper(substr($request->last_name,0,1)).'.',
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'phone'         => $request->phone,
                'pin'           => $request->pin,
                'username'      => $request->username,
                'registration_no'=>'PHA00'.date('HisYdm'),
                'address'       => $request->address,
                'roll_type'     => $request->role
            );
            $user = Auth::user();
            if(!empty($user))
            {
                $insert_data['subscription']=$user->subscription; 
                $insert_data['website_id']=$user->website_id; 
                $insert_data['created_by']=$user->id;
            }
            if($user->website_id!=$request->website_id){
                return response()->json(['error'=>'website id is not match to login pharmacy`s website id '], 401); 
            }
            $getAccess=AccessLevel::find(1); 
            $getAllAdmin=Company::where('roll_type','admin')->get();
            $getAllTechnician=Company::where('roll_type','technician')->get();
            if($request->role=='admin' && $getAccess->no_of_admins <= count($getAllAdmin)){ 
                return response()->json(['error'=>'your pharmacy can create only '.$getAccess->no_of_admins.' Admin '], 401);
            }
            elseif($request->role=='technician' && $getAccess->no_of_technicians <= count($getAllTechnician) ){ 
                return response()->json(['error'=>'your pharmacy can create only '.$getAccess->no_of_technicians.' Technician '], 401);
            }
            // print_r($insert_data);
            $createdTechnician=Company::create($insert_data);

            DB::disconnect('tenant');
            return response()->json(['success' => $createdTechnician], $this-> successStatus); 
        }
        else
        {
            return response()->json(['error'=>'Pharmacy not  found!'], 401); 
        }
         
    }
}
