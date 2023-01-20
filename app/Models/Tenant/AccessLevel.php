<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Model;
// use Hyn\Tenancy\Traits\UsesSystemConnection; 
use Hyn\Tenancy\Traits\UsesTenantConnection; 

class AccessLevel extends Model
{
    // use UsesSystemConnection;
    use UsesTenantConnection; 
    
    protected $fillable = [
        'no_of_admins','no_of_technicians','default_cycle','app_logout_time',
        'form1','form2','form3','form4','form5','form6','form7','form8','form9','form10','form11','form12',
        'form13','form14','form15','form16','form17','form18','form19','form20','form21','website_id',
        'reminderdefaultdays'
    ];

     /* Update form On / OFF  */
    public static function update_data($condition,$update_data)
    {
      return AccessLevel::where($condition)->update($update_data);
    }

    public static function update_plan_data($condition,$update_data)
    {
      return AccessLevel::where($condition)->update($update_data);
    }
}
