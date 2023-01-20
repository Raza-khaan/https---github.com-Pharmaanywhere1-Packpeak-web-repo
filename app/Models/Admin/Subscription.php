<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection; 
use DB; 

class Subscription extends Model
{
    use UsesSystemConnection;
    
    
    protected $fillable = [
        'title','no_of_admins','no_of_technicians','default_cycle','allowed_sms','app_logout_time',
        'form1','form2','form3','form4','form5','form6','form7','form8','form9','form10','form11','form12',
        'form13','form14','form15','form16','form17','form18','form19','form20','form21','plan_validity','status'
    ];

    /* Update form On / OFF  */
    public static function update_data($column,$row_id,$status,$form){
       Subscription::where($column,$row_id)->update(array($form=>$status));
    }

    /* select data by column name  */
    public  static  function getdatabycolumn_name($column,$row_id)
    {
        return Subscription::where($column,$row_id)->get();
    }
    
    public static function update_record($condition,$update_data)
    {
        return Subscription::where($condition)->update($update_data);
    }

    public function menus(){
        return $this->hasMany('App\Models\Tenant\Menu');
    }

    public function submenus(){
        return $this->hasMany('App\Models\Tenant\SubMenu');
    }


}
