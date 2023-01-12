<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection; 
use Illuminate\Database\Eloquent\SoftDeletes;
use DB; 

class pickupNotification extends Model
{
    use UsesTenantConnection; 
    use SoftDeletes;

    protected $table = 'pickupnotification';
    
    

    protected $fillable = [
        'patient_id','patientname','pickup_id','website_id','type','created_by','created_at',
    ];


   


}
