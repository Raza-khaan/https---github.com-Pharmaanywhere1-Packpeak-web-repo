<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hyn\Tenancy\Traits\UsesTenantConnection; 
class PatientReturn extends Model
{
    use UsesTenantConnection; 
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'patient_id','dob',
        'day_weeks','returned_in_days_weeks','store','other_store','staff_initials',
        'created_by','deleted_by','status','updated_at','deleted_at','website_id'
    ];

     /* save data   */
    public static function insert_data($insert_data){
      return PatientReturn::create($insert_data);
    }
    

     public static  function  get_all()
    {
        return PatientReturn::where('patient_returns.deleted_at', NULL)
        ->select('patient_returns.*','patients.first_name','patients.last_name','stores.name as store')
        ->join('stores', 'patient_returns.store', '=', 'stores.id')
        ->join('patients', 'patient_returns.patient_id', '=', 'patients.id')
        ->where('stores.deleted_at', NULL)
        ->where('patients.deleted_at', NULL)
        ->where('patient_returns.is_archive', 0)
        ->orderBy('patient_returns.id', 'DESC')
        ->skip(0)->take(50)
        ->get();
    }

     public static  function  get_archived()
    {
        return PatientReturn::withTrashed()
        ->select('patient_returns.*','patients.first_name','patients.last_name','stores.name as store')
        ->join('stores', 'patient_returns.store', '=', 'stores.id')
        ->join('patients', 'patient_returns.patient_id', '=', 'patients.id')
        ->where('patient_returns.is_archive', 1)
        ->orderBy('patient_returns.id', 'DESC')
        ->skip(0)->take(50)
        ->get();
    }


    /* get  By column  */
    public static  function  get_by_where($condition)
    {
        return  PatientReturn::where($condition)->get(); 
    }

    /* update data  */
    public static  function update_where($condition,$update_data)
    {
      return PatientReturn::where($condition)->update($update_data);
    }

    /* delete record  */
    public  static  function  delete_record($id)
    {
      return PatientReturn::find($id)->delete();
    }

    public  static  function  soft_delete_record($id)
    {
      return PatientReturn::find($id)->delete();
    }

    public function patients(){
      return $this->belongsTo('App\Models\Tenant\Patient','patient_id','id');
    }

    // public function facility(){
    //   return $this->belongsTo('App\Models\Tenant\Facility','store','id');
    // }
    public function stores(){
      return $this->belongsTo('App\Models\Tenant\Store','store','id');
    }

}
