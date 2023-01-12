<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant\AccessLevel;
class CheckPharmacy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if(!session()->has('phrmacy'))
        {
            return redirect('admin-login')->with('msg','<div class="alert alert-danger""> You are <strong>logged Out</strong>!</div>'); 
        }
        $getAccess=AccessLevel::first(); 
        $request->merge([
            'role_type' =>session()->get('phrmacy')->roll_type,
            'form1' =>  $getAccess->form1,
            'form2' =>  $getAccess->form2,
            'form3' =>  $getAccess->form3,
            'form4' =>  $getAccess->form4,
            'form5' =>  $getAccess->form5,
            'form6' =>  $getAccess->form6,
            'form7' =>  $getAccess->form7,
            'form8' =>  $getAccess->form8,
            'form9' =>  $getAccess->form9,
            'form10' =>  $getAccess->form10,
            'form11' =>  $getAccess->form11,
            'form12' =>  $getAccess->form12,
            'form13' =>  $getAccess->form13,
            'form14' =>  $getAccess->form14,
            'form15' =>  $getAccess->form15,
            'form16' =>  $getAccess->form16,
            'form17' =>  $getAccess->form17,
            'form18' =>  $getAccess->form18,
            'form19' =>  $getAccess->form19,
            'form20' =>  $getAccess->form20,
            'form21' =>  $getAccess->form21
            ]);
        return $next($request);
    }
}
