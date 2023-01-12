<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection; 
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use UsesTenantConnection;
    use SoftDeletes;
    protected $fillable = ['name'];

    

     /* delete record  */
   public  static  function  delete_record($id)
   {
     return Store::find($id)->delete();
   }
}
