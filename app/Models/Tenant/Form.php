<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection; 

class Form extends Model
{
    use UsesTenantConnection;
    protected $fillable = ['form_no','form_name','status','created_by'];


}
