<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Auth; 
use Illuminate\Http\Request;
use App\User;
use App\Models\Admin\Admin;
use App\Models\Tenant\Audit; 
use App\Models\Tenant\Checking; 
use App\Models\Tenant\MissedPatient; 
use App\Models\Tenant\NotesForPatient; 
use App\Models\Tenant\Patient; 
use App\Models\Tenant\PatientReturn; 
use App\Models\Tenant\PickUp; 
use App\Models\Tenant\Company; 
use App\Models\Phermacist;
use Illuminate\Support\Facades\Hash;
use DB;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {   
       
    	$all_pharmacy=User::all();
        $newarray=array(); $allPatients=0;$allPharmacyCount=0;$allPharmacy=0;$allTechnicians=0;$allPickups=0;$allPharmacist=0;
        if(count($all_pharmacy)){
          $allPharmacyCount+=User::all()->count();
          $allPharmacist+=Phermacist::all()->count();
        foreach($all_pharmacy  as $row){
              $this->get_connection($row->website_id);
              $allPatients+=Patient::all()->count();
              $allTechnicians+=Company::select_all_technician()->count();
              $allPickups+=PickUp::all()->count();
              DB::disconnect('tenant');
        }
       }

        $data=array('allPatients'=>$allPatients,'allPharmacist'=>$allPharmacist,'allPharmacyCount'=>$allPharmacyCount,'all_pharmacies'=>$all_pharmacy,'allTechnicians'=>$allTechnicians,'allPickups'=>$allPickups); 
      
       
        return view('admin.index',['pharmacylist'=>$all_pharmacy])->with($data);
    }


    public function profile()
    {

        $value = session()->get('admin');
        $details = Admin::where('email','=',$value->email)->first();
       
        return view('admin.profile')->with('email',$details->email)
        ->with('name',$details->name)->with('code',$details->code)->with('number',$details->number);
        
    }

    public function update_profile(Request $request )
    {
        $admindetails =   Admin::where('email','=',$request->email)->first();
        $admindetails->name =   $request->name;
        $admindetails->code =   $request->code;
        $admindetails->number =   $request->phone;
        $admindetails->save();
        return "200";
    }

    public function changepassword()
    {

        $value = session()->get('admin');
        $details = Admin::where('email','=',$value->email)->first();
       
        return view('admin.changepassword')->with('email',$details->email)
        ->with('name',$details->name)->with('code',$details->code)->with('number',$details->number);
        
    }


    public function update_password(Request $req)
    {

        
        $details = Admin::where('email','=',$req->email)->first();
         
 
            $new = Hash::make($req->currentpassword);
        
            
         if(Hash::check($req->currentpassword,$details->password))
         {
            $currentpassword = Hash::make($req->newpassword);
            $details->password = $currentpassword;
            $details->save();
            return 1;
        
         }
        else
        {
            return 0;
        }

    
        
    }


     public  function  get_connection($website_id)
    {
        $get_user=User::get_by_column('website_id',$website_id);
        config(['database.connections.tenant.database' => $get_user[0]->host_name]);
         DB::purge('tenant');
         DB::reconnect('tenant');
         DB::disconnect('tenant'); 
    }


     /**
     * Reset  Password 
    */

    public  function  reset(Request $request){ 
        $rowId=base64_decode($request->row_id);
        $getUser=User::find($rowId);
        if(!empty($getUser)){
            if($getUser->website_id!=""){
                $this->get_connection($getUser->website_id);
                $getAdmin=Company::find(1);
                if(!empty($getAdmin)){
                       $getUser->row_id=$request->row_id;
                       $data['getDetails']=$getUser;
                    //    print_r($data['getDetails']);  die; 
                       return view('admin.auth.tenant.passwords.reset')->with($data); 
                }
                
                DB::disconnect('tenant'); 
            }
            
        
        }
        

        
    }

    /**
     * Update Admin Password 
    */
    public function  updatePassword(Request $request){ 
        $rowId=base64_decode($request->row_id);
        $validate_array=array(
            'password'     => 'required|string|min:6|max:255',
            'password_confirmation'     => 'required|string|min:6|max:255|same:password',
         ); 
         $validator = $request->validate($validate_array);
         $getUser=User::find($rowId);
         if(!empty($getUser)){
            if($getUser->website_id!=""){
                $this->get_connection($getUser->website_id);
                $getAdmin=Company::find(1);
                if(!empty($getAdmin)){
                    $updateCompany=Company::where('id',$getAdmin->id)->update(array('password'=>Hash::make($request->password))); 
                    $getPhermacist=Phermacist::where('email',$getAdmin->email)->first(); 
                    if(!empty($getPhermacist)){
                        $UpdatePhermacist=Phermacist::where('id',$getPhermacist->id)->update(array('password'=>Hash::make($request->password)));
                    }
                    if(!empty($getUser)){
                        $updateUser=User::where('id',$getUser->id)->update(array('password'=>Hash::make($request->password)));
                    }
                    return redirect('admin-login')->with(["msg"=>'<div class="alert alert-success">Pharmacy Password Changed Successfully.. </div>']);
                }
                
                DB::disconnect('tenant'); 
            }
            else{
                return redirect()->back()->with(["msg"=>'<div class="alert alert-danger">Pharmacy not  found !</div>']);
            }
         }
         else{
            return redirect()->back()->with(["msg"=>'<div class="alert alert-danger">user not  found ! </div>']);
         }
       
    }


}
