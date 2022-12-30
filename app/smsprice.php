<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection; 


class smsprice extends Model
{
    use UsesSystemConnection;
    protected $table = 'tblsmsprice';
    protected $fillable = ['name','noofsms','price','created_by','created_at'];   
}
