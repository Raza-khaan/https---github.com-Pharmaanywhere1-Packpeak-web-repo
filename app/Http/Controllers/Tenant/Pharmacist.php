<?php

namespace App\Http\Controllers\Tenant;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

use App\User;
use App\Models\Tenant\Company;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Hyn\Tenancy\Models\Website;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use App\Models\Phermacist;
use Illuminate\Support\Facades\Redirect;
use DB;
use  App\Models\Admin\Subscription;
use  App\Models\Tenant\AccessLevel;
use  App\Models\Tenant\Location;
use  App\Models\Tenant\Facility;
use App\Websites;

class Pharmacist extends Controller
{
  protected $views='';
  public function __construct(){
       $this->views='tenant';
  }
  protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name'        => ['required', 'string', 'max:255'],
            'last_name'         => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'          => ['required', 'string', 'confirmed','min:6'],
            // 'confirm_password'  => ['required', 'string', 'min:6'],
            'term'              => ['required'],
            'company_name'      => ['required', 'string', 'max:255'],
            'host_name'         => ['required', 'string', 'max:190'],
            'phone'             => ['required', 'string', 'max:12'],
            'address'           => ['required', 'string', 'max:255'],
            'subscription'      => ['required', 'string', 'max:255'],
        ]);
    }
    protected function create(array $data)
    {   
        $host_name = $data['host_name'];  
       
        $website = $this->tenantSetUp($host_name, $data); 
        $insert_data=array(
                'name'          => $data['first_name'].' '.$data['last_name'],
                'initials_name' => strtoupper(substr($data['first_name'],0,1)).'.'.strtoupper(substr($data['last_name'],0,1)).'.',
                'first_name'    => $data['first_name'],
                'last_name'     => $data['last_name'],
                'email'         => $data['email'],
                'password'      => Hash::make($data['password']),
                'company_name'  => $data['company_name'],
                'phone'         => '04'.$data['phone'],
               'registration_no'=> 'PHA00'.date('HisYdm'),
                'address'       => $data['address'],
                'host_name'     => $host_name,
                'website_id'    => $website->id,
                'subscription'  => $data['subscription']
        );

        User::create($insert_data); 
        $insert_data['roll_type']='ADMIN';
       
        $company=Company::create($insert_data);
       
        return  $host_name;


    }

    public function register(Request $request) {
      
      

      $this->validator($request->all())->validate();
      $host_name = $this->create($request->all()); 
      $redirect= 'http://'.$host_name.'.'.env('PROJECT_HOST').(env('PROJECT_HOST')=='localhost'?'/Pack-Peak/public':''); 
      return redirect($redirect);
    
    }

   private function tenantSetUp($host_name, $data) {
        // $fqdn = "www.{$host_name}." . env('PROJECT_HOST', 'packpeak.com.au');
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



        //Create default folders
        $folders = [
            'document_templates',
            'phermacist_documents',
            'phermacist_profile',
        ];
        foreach ($folders as $folder) {
            $path="storage/tenancy/tenants/{$uuid}/{$folder}"; 
            if(!is_dir($path)){
                File::makeDirectory("storage/tenancy/tenants/{$uuid}/{$folder}", 0777, true);
            }
        }



        $permacist_result = Phermacist::create([
            'username'         => Str::slug($data['first_name'].$data['last_name']),
            'first_name'       =>$data['first_name'],     
            'last_name'        =>$data['last_name'],
            'email'            =>$data['email'],
            'company_name'     =>$data['company_name'],
            'host_name'        =>$data['host_name'],
            'password'         =>Hash::make($data['password']),
            'subscription'     => $data['subscription'],
        ]); 

        $subscrip=Subscription::getdatabycolumn_name('id',$data['subscription']); 
        // echo $subscrip[0]->title;  die; 
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
        ]; 
        }
        AccessLevel::create($update_data);

        // $create_date = date('Y-m-d H:i:s', strtotime($company->created_at));
        // $interval = new \DateInterval("P30D");
        // $until = new \DateTime($create_date);
        // $end_date = $until->add($interval);
        // $company->expiry_date = $end_date->format('Y-m-d H:i:s');
        // $company->save();

        // $employee = Company::create([
        //     'username'          => employee_username($data['first_name'], $data['last_name']),
        //     'first_name'        => $data['first_name'],
        //     'last_name'         => $data['last_name'],
        //     'email'             => $data['email'],
        //     'password'          => Hash::make($data['password']),
        //     'status'            => 'active',
        //     'login_created'     => 1,
        // ]);

        // $company->owner_id = $employee->id;
        // $company->save();

        // $defaultRole = config('roles.models.role')::where('level', '=', 0)->first();
        // $employee->attachRole($defaultRole);

        // $role = config('roles.models.role')::where(['is_admin_role' => 1, 'slug' => $this->views.'istrator'])->first();
        // $employee->attachRole($role);

        return $website;
}


    public  function  pharmacist()
    {
      $data['all_subscription']=Subscription::all();
      return view($this->views.'.pharmacy_signup')->with($data); 
    }

    public  function  def_pharmacy(Request $request){
      $TENANCY_DEFAULT_HOSTNAME=Websites::where('uuid',env('TENANCY_DEFAULT_HOSTNAME'))->count();
     
      if($TENANCY_DEFAULT_HOSTNAME>0){
        $redirect= 'http://'.env('TENANCY_DEFAULT_HOSTNAME').'.'.env('PROJECT_HOST').env('PROJECT_HOST')=='localhost'?'/Pack-Peak/public':'';
        dd($redirect);
        return redirect($redirect);
      }

      $data['all_subscription']=Subscription::all();

      return view($this->views.'.def_pharmacy')->with($data);
    }

    public  function  add_pharmacy(Request $request){
      $TENANCY_DEFAULT_HOSTNAME=Websites::where('uuid',env('TENANCY_DEFAULT_HOSTNAME'))->count();
     
      if($TENANCY_DEFAULT_HOSTNAME>0){
        $redirect= 'http://'.env('TENANCY_DEFAULT_HOSTNAME').'.'.env('PROJECT_HOST');
        //return redirect($redirect);
      }

      $data['all_subscription']=Subscription::all();
      return view($this->views.'.add_pharmacy')->with($data);
    }

    public  function pharmacist_login(Request $request)
    {  
      return view($this->views.'.pharmacist-login'); 
    }
    
    public  function  all_pharmacies(Request $request){


      // $data['all_pharmacies']=User::all(); 
      $all_pharmacy=User::all();


      return view('all_pharmacies',['pharmacylist'=>$all_pharmacy]);
    }
}
