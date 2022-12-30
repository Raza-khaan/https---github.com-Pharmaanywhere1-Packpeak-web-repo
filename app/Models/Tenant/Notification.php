<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection; 
use Illuminate\Database\Eloquent\SoftDeletes;
use  DB; 

class Notification extends Model
{
    use UsesTenantConnection; 
    use SoftDeletes;
    
    

    protected $fillable = [
        'type','notification_msg','created_by','deleted_by','status','updated_at','deleted_at','website_id'
    ];


     /* save data   */
    public static function insert_data($insert_data){

      return Notification::create($insert_data);
    }


}
