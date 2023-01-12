<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection; 
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkings extends Model
{
    use UsesTenantConnection;
    use SoftDeletes;
    protected $table = 'checkings';

    protected $fillable = ['patient_id','dd','date_of_birth','no_of_weeks','location','note_from_patient','pharmacist_signature','created_by','deleted_by','status','website_id'];

    public function patients(){
        return $this->belongsTo('App\Models\Tenant\Patient','patient_id','id');
    }

    
    

}
