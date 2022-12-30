<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection; 
use Illuminate\Database\Eloquent\SoftDeletes;


class PatientLocation extends Model
{
    //
    use UsesTenantConnection;
    protected $table = 'patient_locations';
    protected $fillable = ['action_by','patient_id','locations'];

    public function patient()
    {
        return $this->belongsTo('App\Models\Tenant\Patient','id','patient_id');
    }

      /* update data  */
      public static  function update_where($condition,$update_data)
      {
        return PatientLocation::where($condition)->update($update_data);
      }

      /* save data   */
    public static function insert_data($insert_data){
        
         PatientLocation::updateOrCreate(['patient_id'=>$insert_data['patient_id']],['locations'=>$insert_data['locations']]);
    }

    public static function update_data($update_data){
        
         PatientLocation::updateOrCreate(['patient_id'=>$update_data['patient_id']],['locations'=>$update_data['locations']]);
    }
}
