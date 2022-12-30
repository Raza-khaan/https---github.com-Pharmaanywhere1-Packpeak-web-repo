<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection; 

class EventsLog extends Model
{
    use UsesSystemConnection;
    protected $fillable = [
        'website_id',
        'action_by',
        'patient_id',
        'action',
        'action_detail',
        'comment',
        'ip_address','type','user_agent','authenticated_by',
        'status',
        'created_at',
        'updated_at'
    ];

    public function pharmacy(){
        return $this->hasOne('App\User','website_id','website_id');
    }
    
    public function users(){
        return $this->hasOne('App\Models\Admin\Admin','id','action_by');
    }

    

    // public function patients(){
    //     return $this->hasOne('App\Models\Tenant\Patient','id','patient_id');
    // }

    
}
