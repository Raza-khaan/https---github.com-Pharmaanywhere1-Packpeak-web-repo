<?php

namespace App\Http\Controllers\Admin\Auth\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
class All_api extends Controller
{
    public  function  list()
    {
       return User::all(); 
    }
}
