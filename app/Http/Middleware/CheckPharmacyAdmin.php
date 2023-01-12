<?php

namespace App\Http\Middleware;

use Closure;

class CheckPharmacyAdmin
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

        if(!session()->has('phrmacy') || session()->get('phrmacy')->roll_type!='admin')
        {
            return redirect('admin-login')->with('msg','<div class="alert alert-danger""> You are <strong>logged Out</strong>!</div>'); 
        }
        return $next($request);
    }
}
