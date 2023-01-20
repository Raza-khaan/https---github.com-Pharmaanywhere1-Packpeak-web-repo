<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Hyn\Tenancy\Models\Website;
use App\Models\Tenant\Patient;
use App\Websites;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Models\Hostname;
use Illuminate\Support\Str;
use App\User;
use App\Models\Tenant\Company;
use DB;
use Carbon\Carbon;
use  App\Models\Admin\Subscription;
use  App\Models\Tenant\AccessLevel;
use  App\Models\Tenant\Location;
use  App\Models\Tenant\Facility;
use App\Models\Phermacist;
use Mail;
class Pharmacy extends UserController
{
    public $successStatus = 200;
    
    public function patientrecord()
	{
     
		 return Patient::get();
	
	}
    public  function  get_connection($website_id)
    {
        $get_user=User::get_by_column('website_id',$website_id);
        config(['database.connections.tenant.database' => $get_user[0]->host_name]);
         DB::purge('tenant');
         DB::reconnect('tenant');
          
    }

	protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name'        => ['required', 'string', 'max:255'],
            'last_name'         => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'          => ['required', 'string', 'confirmed','min:6'],
            'term'              => ['required'],
            'company_name'      => ['required', 'string', 'max:255' , 'unique:users'],
            'host_name'         => ['required', 'string', 'max:190' , 'unique:users'],
            'phone'             => ['required', 'string', 'max:10' ,'min:10'],
            'address'           => ['required', 'string', 'max:255'],
            'subscription'      => ['required', 'string', 'max:255'],
        ]);
    }
/*Create New Pharmacy */
    public function create_pharmacy(Request $request) { 

    $request->uuid=$request->host_name;
     $validator = Validator::make($request->all(), [
            'first_name'        => ['required', 'string', 'max:255'],
            'last_name'         => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'          => ['required', 'string', 'confirmed','min:6'],
            'term'              => ['required'],
            'company_name'      => ['required', 'string', 'max:255' , 'unique:users'],
            'host_name'         => ['required', 'string', 'max:190' , 'unique:users'],
            'phone'             => ['required', 'string', 'max:10' ,'min:10'],
            'address'           => ['required', 'string', 'max:255'],
            'subscription'      => ['required', 'string', 'max:255'],
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
    	
      /*Create Tenant Setup*/
        $website=Websites::where('uuid',$request->host_name)->get();
        if(count($website)){
            return response()->json(['error'=>'This host name is  allready exist'], 401);
        }
        $host_name=$request->host_name;
        // $fqdn = "www.{$host_name}." . env('PROJECT_HOST', 'packpeak.com.au');
        // $fqdn = "{$host_name}." . env('PROJECT_HOST', 'packpeak.com.au');
        $fqdn = "{$host_name}." . env('PROJECT_HOST', 'localhost');
        $hostname = new Hostname;
        $hostname->fqdn = $fqdn;
        $uuid = Str::limit(Str::slug($host_name, '_'), 15, '');
        $website = new Website;
        $website->uuid = $uuid;
        
        $website->hostnames()->save($hostname); 
        $host = app(HostnameRepository::class)->create($hostname);
        app(WebsiteRepository::class)->create($website);
        app(HostnameRepository::class)->attach($host, $website);
        $subscrip=Subscription::getdatabycolumn_name('id',$request->subscription); 
        $permacist_result = Phermacist::create([
            'username'         => Str::slug($request->first_name.$request->last_name),
            'first_name'       =>$request->first_name,     
            'last_name'        =>$request->last_name,
            'email'            =>$request->email,
            'company_name'     =>$request->company_name,
            'host_name'        =>$request->host_name,
            'password'         =>Hash::make($request->password),
            'subscription'     => $request->subscription,
            'expired_date'     => Carbon::now()->addDays($subscrip[0]->plan_validity),
            'website_id'       => $website->id
        ]);
        if(count($subscrip))
        {
            $update_data=[
            'no_of_admins'=>$subscrip[0]->no_of_admins,
            'no_of_technicians'=>$subscrip[0]->no_of_technicians,
            'form1' => $subscrip[0]->form1,
            'form2' => $subscrip[0]->form2,
            'form3' => $subscrip[0]->form3,
            'form4' => $subscrip[0]->form4,
            'form5' => $subscrip[0]->form5,
            'form6' => $subscrip[0]->form6,
            'form7' => $subscrip[0]->form7,
            'form8' => $subscrip[0]->form8,
            'form9' => $subscrip[0]->form9,
            'form10'=> $subscrip[0]->form10,
            'form11'=> $subscrip[0]->form11,
            'form12'=> $subscrip[0]->form12,
            'form13'=> $subscrip[0]->form13,
            'form14'=> $subscrip[0]->form14,
            'form15'=> $subscrip[0]->form15,
            'form16'=> $subscrip[0]->form16,
            'form17'=> $subscrip[0]->form17,
            'form18'=> $subscrip[0]->form18,
            'form19'=> $subscrip[0]->form19,
            'form20'=> $subscrip[0]->form20,
            'form21'=> $subscrip[0]->form21,
            'website_id'=>$website->id
            ]; 
            AccessLevel::create($update_data);
        }

        $subscrip=Subscription::getdatabycolumn_name('id',$request->subscription); 
        $insert_data=array(
                'name'          => $request->first_name.' '.$request->last_name,
                'initials_name' => strtoupper(substr($request->first_name,0,1)).'.'.strtoupper(substr($request->last_name,0,1)).'.',
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'company_name'  => $request->company_name,
                'phone'         => $request->phone,
               'registration_no'=> 'PHA00'.date('HisYdm'),
                'address'       => $request->address,
                'host_name'     => $host_name,
                'website_id'    => $website->id,
                'subscription'  => $request->subscription,
                'expired_date'  => Carbon::now()->addDays($subscrip[0]->plan_validity)
        );
        User::create($insert_data); 
        $insert_data['roll_type']='admin';
        $insert_data['username'] =$request->email;
        $company=Company::create($insert_data);
        return response()->json(['success'=>$insert_data,'message'=>'New Pharmacy Created Successfully.'], $this-> successStatus); 
    
    }

   public  function  updateAppLogoutTime(Request $request,$website_id){
     $getPharmacy=User::where('website_id',$website_id)->get();
     if(count($getPharmacy)){
         $validator = Validator::make($request->all(), [ 
            'app_logout_time' => 'required'
         ]);
         if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
         }
         $update=$request->all();
         $update['website_id']=$website_id;
         $this->get_connection($request->website_id); 
         $updateAccess=AccessLevel::where('id',1)->update($update);
         DB::disconnect('tenant');
         $getAccess=AccessLevel::find(1);
         return response()->json($getAccess, $this-> successStatus); 
     }
     else{
          return response()->json(['error'=>'Pharmacy not found'], 401);
     }
   }

   public function packpeakapp()
   {
    
    return response()->json(["Success"=>"API URL Works"], 200);
   }

   /*  get  App session logout time*/
     public function  getAppLogoutTime(Request $request,$website_id){
        // public function  getAppLogoutTime(Request $request,$website_id){

            // return response()->json(["Success"=>"API URL Works Fine"], 200);

        $getPharmacy=User::where('website_id',$website_id)->get();
         if(count($getPharmacy)){
             
             $this->get_connection($request->website_id);
             $getAccess=AccessLevel::find(1);
             DB::disconnect('tenant');
             return response()->json($getAccess, $this-> successStatus); 
         }
         else{
              return response()->json(["error"=>"Pharmacy not found"], 401);
         }

     }
     
     
    public  function sendResetLinkEmail(Request $request){
        $validator = Validator::make($request->all(), [ 
            'email'     => 'required|email|max:255',
         ]);
         if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
         }
         $getAdmin=User::where('email',$request->email)->first(); 
         if(!empty($getAdmin)){
             $details = $getAdmin;
             Mail::to($getAdmin->email)->send(new \App\Mail\PharmacyMail($details));
             return response()->json(['success'=>'We have  e-mailed  your password reset link!.'], $this-> successStatus);
         }
         else{
             return response()->json(["error"=>"We can`t find a user with that e-mail address."], 401);
         }
    }

   



}
