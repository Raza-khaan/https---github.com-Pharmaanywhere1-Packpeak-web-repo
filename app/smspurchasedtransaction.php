<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection; 
use Illuminate\Database\Eloquent\SoftDeletes;
use  DB; 

class smspurchasedtransaction extends Model
{
    use UsesSystemConnection; 
    use SoftDeletes;

    protected $table = 'tblextrasms';
    
    protected $fillable = [
        'noofsms','price','amount','created_by','created_at','transactionid','websiteid',
        'packageid'
    ];




   


}
