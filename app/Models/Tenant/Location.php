<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection; 
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use UsesTenantConnection;
    use SoftDeletes;
    
    protected $fillable = ['name','created_by'];

    // public function patients(){
    //     return $this->hasMany('App\Models\patient','location_id','id');
    // }

}
