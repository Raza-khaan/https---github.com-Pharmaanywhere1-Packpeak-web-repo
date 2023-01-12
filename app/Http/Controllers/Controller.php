<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request; 
use App\User;
use Hyn\Tenancy\Models\Website;
use DB; 

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $image_ext = array("jpeg", "png", "jpg", "JPEG", "PNG", "JPG");
    public function __construct() {
       /* $website = app(\Hyn\Tenancy\Environment::class)->tenant();
        if ($website) {
            view()->share('website_id', $website->uuid);
            view()->share('upload_path', "storage/tenancy/tenants/{$website->uuid}/");
            view()->share('swiss_country', 'CHE');
            // $languages = Language::all();
            // view()->share('header_languages', $languages);
            
            $user = User::where('website_id', $website->id)->first();
            if ($user) {
                view()->share('host_name', $user->host_name);
            }

            $days_left = 0;
            $company = Company::first();
            if ($company) {
                $image_url= $company->logo ? asset('storage/'.$company->logo) : asset('media/logos/logo.png');
                view()->share('image_url', $image_url);
                
                view()->share('company_name', $company->name);

                view()->share('is_sub_company', $company->enable_sub_companies);
                
                $expiry_date = date('Y-m-d', strtotime($company->expiry_date));
                $to = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
                $from = \Carbon\Carbon::createFromFormat('Y-m-d', $expiry_date);
                $days_left = $to->diffInDays($from);
                
                if(strtotime($company->expiry_date) < strtotime(date('Y-m-d H:i:s'))) {
                    $days_left = 0;
                }
            
                if(!session()->has('timezone')) {
                    session()->put('timezone', $company->timezone ?? config('app.timezone'));
                }
            }

            view()->share('days_left', $days_left);

            view()->share('upload_path', "storage/tenancy/tenants/{$website->uuid}/");
            
            if(!session()->has('admin-roles')) {
                $adminRoles = Role::where('is_admin_role', 1)->pluck('id')->toArray();
                session()->put('admin-roles', $adminRoles);
            }
        }*/
    }


    /* START OF Forward to  Parmacy Subdomain   */
    public function pharmacy_login(Request $request) { 
        $request->validate([
            'host_name' => 'required',
        ]);
       

        $host_name = request('host_name');
        
        $user = User::where('host_name', $host_name)->first();
        
        if ($user && $user->website_id) {
        //    dd($user->website_id);
            // $fqdn = "www.{$host_name}." . env('PROJECT_HOST', 'localhost');
            $fqdn = "{$host_name}." . env('PROJECT_HOST', 'localhost'); 
            // $fqdn = $host_name;
            $host = Website::find($user->website_id)->hostnames()->first();
            //  dd($host);
            if ($host) {
                // dd($host);
                if ($host->force_https) {

                    // return redirect()->away("https://{$host->fqdn}/");
                    return redirect()->away("https://{$host->fqdn}/Pack-Peak/public/");
                } else {
                    // return redirect()->away("http://{$host->fqdn}/");
                    return redirect()->away("http://{$host->fqdn}/Pack-Peak/public/");
                }
            }
        }
        return redirect()->back()->with('msg', '<div class="alert alert-danger"><strong>Inavlid </strong>  host name! !!!</div>');
    }
    /* END of  Forward to  Parmacy Subdomain   */


 public  function  get_connection($website_id)
    {
        // dump($website_id);
        $get_user=User::get_by_column('website_id',$website_id);
        config(['database.connections.tenant.database' => $get_user[0]->host_name]);
         DB::purge('tenant');
         DB::reconnect('tenant');
         DB::disconnect('tenant'); 
    }


}
