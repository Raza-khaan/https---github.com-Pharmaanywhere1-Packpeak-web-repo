<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection; 
use Illuminate\Database\Eloquent\SoftDeletes;
use  DB; 

class paypaltransaction extends Model
{
    use UsesSystemConnection; 
    use SoftDeletes;

    protected $table = 'tbltransaction';
    
    protected $fillable = [
        'subscriptionid','subscription_name','amount','transactionid','transactiondate','paymentstatus','companyid',
        'expirydate','userid','created_by','created_at',
    ];




   


}
