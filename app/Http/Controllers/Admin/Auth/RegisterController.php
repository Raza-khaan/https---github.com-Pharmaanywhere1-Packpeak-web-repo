<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\Admin\Admin;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    use RegistersUsers;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
        $this->middleware('guest:admin');
    }

    

    public function createAdmin(Request $request) {
        $request->validate(array(
            'fullname' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:190', 'unique:admins'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ));
        $admin = Admin::create([
            'name' => $request['fullname'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        if($admin) {
            return redirect()->route('super-admin.dashboard')->with('status', 'You are Registred in as successfully!');
        } else {
            return redirect()->back()->with("msg", 'Something Went Wrong, Please try Again.');
        }
    }
}
