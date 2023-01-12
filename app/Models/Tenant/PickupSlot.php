<?php

namespace App\Models\Tenant;
use Hyn\Tenancy\Traits\UsesTenantConnection; 
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class PickupSlot extends Model
{
    use UsesTenantConnection; 

    protected $table = 'pickup_slots';

    public static  function  getAvailableSlots(String $date)
    {
        
        return PickupSlot::whereRaw("id NOT IN (SELECT pickup_slot FROM pick_ups WHERE pickup_date = '".$date."')")->get()
        ;
    }
}
