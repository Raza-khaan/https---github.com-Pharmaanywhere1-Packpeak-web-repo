<?php

namespace App\Models\Tenant;
use Hyn\Tenancy\Traits\UsesTenantConnection; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Audit extends Model
{
    use UsesTenantConnection; 
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ['patient_id','no_of_weeks','store','store_others_desc','staff_initials','created_by','deleted_by','status','created_at','updated_at','deleted_at','website_id'  
    ];
    
    public static function insert_data($insert_data){
        return Audit::create($insert_data);
    }

    public static  function  get_all()
    {
        return Audit::where('audits.deleted_at', NULL)
        ->select('audits.*','patients.first_name','patients.last_name','stores.name as store')
        ->join('stores', 'audits.store', '=', 'stores.id')
        ->join('patients', 'audits.patient_id', '=', 'patients.id')
        ->where('stores.deleted_at', NULL)
        ->where('patients.deleted_at', NULL)
        ->where('audits.is_archive', 0)
        ->orderBy('audits.id', 'DESC')
        ->skip(0)->take(50)
        ->get();
    }

    public static  function  get_archived()
    {
        return Audit::withTrashed()
        ->select('audits.*','patients.first_name','patients.last_name','stores.name as store')
        ->join('stores', 'audits.store', '=', 'stores.id')
        ->join('patients', 'audits.patient_id', '=', 'patients.id')
        ->where('audits.is_archive', 1)
        ->orderBy('audits.id', 'DESC')
        ->skip(0)->take(50)
        ->get();
      
    }
    /* get  By column  */
    public static  function  get_by_where($condition)
    {
        return  Audit::where($condition)->get(); 
    }

    /* update data  */
    public static  function update_where($condition,$update_data)
    {
      return Audit::where($condition)->update($update_data);
    }

    public static  function update_unarchive_where($condition,$update_data)
    {
     
      return Audit::where($condition)->update($update_data);
    }

    /* delete record  */
    public  static  function  delete_record($id)
    {
      return Audit::find($id)->delete();
    }


    public  static  function  soft_delete_record($id)
    {
      return Audit::find($id)->delete();
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
