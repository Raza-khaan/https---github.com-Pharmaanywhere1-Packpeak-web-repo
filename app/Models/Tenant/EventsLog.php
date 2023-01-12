<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection; 

class EventsLog extends Model
{
    use UsesTenantConnection;
    protected $fillable = [
        'website_id',
        'action_by',
        'patient_id',
        'action',
        'action_detail',
        'comment',
        'ip_address',
        'type',
        'user_agent',
        'authenticated_by',
        'status',
        'created_at',
        'updated_at'
    ];

    public function pharmacy(){
        return $this->hasOne('App\Models\Tenant\Company','website_id','wesite_id');
    }

    public function users(){
        return $this->hasOne('App\Models\Tenant\Company','id','action_by');
    }
    
    public function patients(){
        return $this->hasOne('App\Models\Tenant\Patient','id','patient_id');
    }


    // public function subscriptions(){
    //     return $this->belongsToMany('App\Models\Admin\Subscription');
    // }

    // public function submenus(){
    //     return $this->hasMany('App\Models\Tenant\SubMenu');
    // }
}
