<?php


namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection; 
use Illuminate\Database\Eloquent\SoftDeletes;

class Pickups extends Model
{
    use UsesTenantConnection;
    use SoftDeletes;
    protected $table = 'pick_ups';

    protected $fillable = ['patient_id','dob','last_pick_up_date','weeks_last_picked_up','no_of_weeks','location','pick_up_by'
    ,'notes_from_patient','notes_for_patient','pharmacist_sign','patient_sign','carer_name','image','created_at','website_id'];

    public function patients(){
        return $this->belongsTo('App\Models\Tenant\Patient','patient_id','id');
    }


}
