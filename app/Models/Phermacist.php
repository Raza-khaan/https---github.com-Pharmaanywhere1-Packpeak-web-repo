<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection; 

class Phermacist extends Model
{
    use UsesSystemConnection;
    
    protected $table='phermacist';
    protected $fillable = [ 'username','sign','dob','pin','first_name', 'last_name', 
    'email','company_name','host_name','password','subscription','expired_date','website_id'];

    /* update data  */
    public static  function update_where($condition,$update_data)
    {
      return Phermacist::where($condition)->update($update_data);
    }
    
    
}


