<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection; 
use Illuminate\Database\Eloquent\SoftDeletes;
use  DB; 

class demo extends Model
{
    use UsesSystemConnection; 
    use SoftDeletes;

    protected $table = 'tbldemo';
}
