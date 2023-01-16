<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection; 

class Authentication_log extends Model
{
    use UsesTenantConnection; 
    protected $fillable = [
        'authenticatable_type ',
        'authenticatable_id',
        'ip_address',
        'uid',
        'type',
        'user_agent',
        'authenticated_by',
        'login_at',
        'logout_at',
        'created_at',
        'website_id'
    ];

    public function pharmacy(){
        return $this->belongsTo('App\Models\Tenant\Company','uid','id');
    }


}
