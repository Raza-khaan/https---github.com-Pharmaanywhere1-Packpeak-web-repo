<?php

namespace App\Http\Controllers\Admin;
use App\Helpers\Helper;
use App\Hostnames;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Models\Admin\Subscription;
use  App\Models\Tenant\AccessLevel; 
use  App\Models\Admin\EventsLog as EventsLogAdmin; 
use App\Models\Admin\FormTable;
use App\Models\Tenant\Company;
use App\User;
use App\Websites;
use Carbon\Carbon;
use Auth; 
use DB; 
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;

class Subscriptions extends Near_Miss
{
    public  function subscriptions()
    {
        // $user = Auth::user();
        
        
        $data['subcriptions']=Subscription::all(); 
        return view('admin.subscriptions')->with($data); 
    }

    /* edit form:on/off  for  Subcription */
    public function add_form($form,$id)
    {
        $data['form']=array('name'=>$form,'id'=>$id);
        $data['subscription']=Subscription::getdatabycolumn_name('id',$id);  
        return view('admin.add_from')->with($data);  
    }

    /* Update form On OR OFF  */
    public  function  update_form(Request $request)
    {
        
        Subscription::update_data('id',$request->row_id,$request->status,$request->form);
        
        $all_pharmacy_list = User::where('subscription','=',$request->row_id)->get();
        
        foreach ($all_pharmacy_list as $row) 
        {
        $this->get_connection($row->website_id); 
        
        $level=AccessLevel::update_plan_data(array('website_id'=>$row->website_id),array($request->form=>$request->status)); 
        DB::disconnect('tenant');
        }
        return response()->json(array('success' => true,'message'=> 'Form On/Off'));
    }
    public function  edit_subscription(Request $request)
    {
        $data['subscription']=Subscription::getdatabycolumn_name('id',$request->row_id);
        $data['subcriptions']=Subscription::all(); 
        return view('admin.subscriptions')->with($data); 
    }

    
    /* Upadte Subscription  */
    public  function  update_subscription(Request $request)
    {    

        Subscription::update_record(array('id'=>$request->row_id),array('title'=>$request->title,'plan_validity'=>$request->plan_validity,'allowed_sms'=>$request->allowed_sms,
        'amount'=>$request->amount));
        
        $all_pharmacy_list = User::where('subscription','=',$request->row_id)->get();
        
        foreach ($all_pharmacy_list as $row) 
        {
        $this->get_connection($row->website_id); 
        
        $level=AccessLevel::update_plan_data(array('website_id'=>$row->website_id),array($request->form=>$request->status)); 
        DB::disconnect('tenant');
        }
        return  redirect('admin/subscriptions')->with('msg','<div class="alert alert-success""> Subscription <strong> '.$request->title.'</strong> is updated.</div>'); 
    }

     /*update Tenant database @form On Off And detail  of  pharmacy*/
    public  function update_form_of_tenant(Request $request)
    {
        // config(['database.connections.tenant.database' => $request->host_name]);
        // DB::purge('tenant');
        // DB::reconnect('tenant');
        
        $formTableget=FormTable::where('form_name',$request->form)->first(); 
        $this->get_connection($request->website_id); 
        $level=AccessLevel::update_data(array('id'=>$request->row_id,'website_id'=>$request->website_id),array($request->form=>$request->status)); 
        DB::disconnect('tenant');
        if($request->status){ $action=6; }else{ $action=7; }
        EventsLogAdmin::create([
            'website_id' => $request->website_id,
            'action_by' => $request->session()->get('admin')->id,
            'action' => $action,
            'action_detail' => $formTableget->form_description,
            'comment' => 'Update Pharmacy Subscription Form',
            'patient_id' => null,
            'ip_address' => $request->ip(),
            'type' => 'SuperAdmin',
            'user_agent' => $request->userAgent(),
            'authenticated_by' => 'packnpeaks',
            'status' => 1
          ]);
        return response()->json(array('success' => true,'message'=> 'Form On/Off'));
    }


    /* update the  tenant Admin And Technician  */
    public function  update_form_tenant_admin_technician(Request $request)
    {
        
        $this->get_connection($request->website_id); 
        $level=AccessLevel::update_data(array('id'=>$request->row_id,'website_id'=>$request->website_id),array($request->form=>$request->status)); 
        DB::disconnect('tenant');
        switch ($request->form) {
            case 'no_of_technicians':
                 $description='Technician Limit';
              break;
            case 'app_logout_time':
                $description='App Logout Time';
              break;
            case 'no_of_admins':
                $description='Admin Limit';
              break;
            case 'default_cycle':
                $description='Pickup Cycle';
            break;
            default:
            $description='Pharmacy Subscription';
              
          }
        EventsLogAdmin::create([
            'website_id' => $request->website_id,
            'action_by' => $request->session()->get('admin')->id,
            'action' => 2,
            'action_detail' => $description,
            'comment' => 'Update Pharmacy Subscription Form',
            'patient_id' => null,
            'ip_address' => $request->ip(),
            'type' => 'SuperAdmin',
            'user_agent' => $request->userAgent(),
            'authenticated_by' => 'packnpeaks',
            'status' => 1
          ]);
        return response()->json(array('success' => true,'message'=> 'Form On/Off'));
    }
       
    
    




}
