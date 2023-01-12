<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection; 
use Illuminate\Database\Eloquent\SoftDeletes;
use  DB; 

class smssettings extends Model
{
    use UsesSystemConnection; 
    use SoftDeletes;

    protected $table = 'tblsmssettings';
    
    protected $fillable = ['name','pass','created_by','created_at'];
}
