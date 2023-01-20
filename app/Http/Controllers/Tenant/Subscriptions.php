<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Models\Admin\Subscription;
use Auth; 
class Subscriptions extends Controller
{
    protected $views='';
    public function __construct(){
         $this->views='tenant';
    }
    public  function subscriptions()
    {
        // $user = Auth::user();
        // print_r($user);  die; 
        $data['subcriptions']=Subscription::all(); 
        return view($this->views.'.subscriptions')->with($data); 
    }

    /* edit form:on/off  for  Subcription */
    public function add_form($form,$id)
    {
        $data['form']=array('name'=>$form,'id'=>$id);
        $data['subscription']=Subscription::getdatabycolumn_name('id',$id);  
        return view($this->views.'.add_from')->with($data); 
    }

    /* Update form On OR OFF  */
    public  function  update_form(Request $request)
    {
        
        Subscription::update_data('id',$request->row_id,$request->status,$request->form);
        return response()->json(array('success' => true,'message'=> 'Form On/Off'));
    }
    public function  edit_subscription(Request $request)
    {
        $data['subscription']=Subscription::getdatabycolumn_name('id',$request->row_id);
        $data['subcriptions']=Subscription::all(); 
        return view($this->views.'.subscriptions')->with($data); 
    }

    /* Upadte Subscription  */
    public  function  update_subscription(Request $request)
    {
        Subscription::update_data('id',$request->row_id,$request->title,'title');
        return  redirect($this->views.'/subscriptions')->with('msg','<div class="alert alert-success""> Subscription <strong> '.$request->title.'</strong> is updated.</div>'); 
    }




}
