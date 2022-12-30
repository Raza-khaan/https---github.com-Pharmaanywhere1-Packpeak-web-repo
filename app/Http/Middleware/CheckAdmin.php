<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
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
        if(!session()->has('admin'))
        {
            return redirect('admin-login')->with('msg','<div class="alert alert-danger""> You are <strong>logged Out</strong>!</div>'); 
        }
        $request->merge(['role_type' =>'admin']);
        return $next($request);
    }
}
