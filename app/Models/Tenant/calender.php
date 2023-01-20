<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection; 
class calender extends Model
{
    use UsesTenantConnection; 
    protected $table = 'calender';
    protected $fillable = ['event_date','event_name'];
}
