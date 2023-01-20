<?php

namespace App\Models\Tenant;
use Hyn\Tenancy\Traits\UsesTenantConnection; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Checking extends Model
{
    use UsesTenantConnection; 
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['patient_id','date_of_birth','no_of_weeks','dd','location','pharmacist_signature','note_from_patient',
    'created_by','deleted_by','status','updated_at','deleted_at','website_id'  
    ];
  
    
     /* save data   */
     public static function insert_data($insert_data){
        return Checking::create($insert_data);
      }

    public static  function  get_all()
    {
        return Checking::where('checkings.deleted_at', NULL)
        ->select('checkings.*','patients.first_name','patients.last_name')
        ->join('patients', 'checkings.patient_id', '=', 'patients.id')
        ->where('patients.deleted_at', NULL)
        ->where('checkings.is_archive', 0)
        ->orderBy('checkings.id', 'DESC')
        ->skip(0)->take(50)
        ->get();
    }

    public static  function  get_archived()
    {
        return Checking::withTrashed()
        ->select('checkings.*','patients.first_name','patients.last_name')
        ->join('patients', 'checkings.patient_id', '=', 'patients.id')
        ->where('checkings.is_archive', 1)
        ->orderBy('checkings.id', 'DESC')
        ->skip(0)->take(50)
        ->get();
    }


    /* get  By column  */
    public static  function  get_by_where($condition)
    {
        return  Checking::where($condition)->get(); 
    }

    /* update data  */
    public static  function update_where($condition,$update_data)
    {
      return Checking::where($condition)->update($update_data);
    }

   /* delete record  */
   public  static  function  delete_record($id)
   {
     return Checking::find($id)->delete();
   }

   /* soft delete record  */
   public  static  function  soft_delete_record($id)
   {
     return Checking::find($id)->delete();
   }

   public function patients(){
    return $this->belongsTo('App\Models\Tenant\Patient','patient_id','id');
}


}
