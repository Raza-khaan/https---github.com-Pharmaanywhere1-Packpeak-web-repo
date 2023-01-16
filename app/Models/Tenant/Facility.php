<?php


namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection; 
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use UsesTenantConnection;
    use SoftDeletes;
    protected $fillable = ['name','created_by'];

    public function patients(){
        return $this->hasMany('App\Models\Tenant\Patient','facilities_id','id');
    }

     /* delete record  */
   public  static  function  delete_record($id)
   {
     return Facility::find($id)->delete();
   }

}
