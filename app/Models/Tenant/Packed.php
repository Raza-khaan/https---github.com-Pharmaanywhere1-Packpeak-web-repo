<?php

namespace App\Models\Tenant;
use Hyn\Tenancy\Traits\UsesTenantConnection; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Packed extends Model
{
    use UsesTenantConnection; 
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'Packed';
   // protected $table_two = 'Packeds';

    protected $fillable = ['patient_id','no_of_weeks','dd','location','pharmacist_signature','note_from_patient',
    'created_by','deleted_by','status','created_at','updated_at','deleted_at','website_id'  
    ];
  
    
     /* save data   */
     public static function insert_data($insert_data){
        return Packed::create($insert_data);
      }

    public static  function  get_all()
    {
        return Packed::where('Packed.deleted_at', NULL)
        ->select('Packed.*','patients.first_name','patients.last_name')
        ->join('patients', 'Packed.patient_id', '=', 'patients.id')
        ->where('patients.deleted_at', NULL)
        ->where('Packed.is_archive', 0)
        ->orderBy('Packed.id', 'DESC')
        ->skip(0)->take(50)
        ->get();
    }

    public static  function  get_archived()
    {
        return Packed::withTrashed()
        ->select('Packed.*','patients.first_name','patients.last_name')
        ->join('patients', 'Packed.patient_id', '=', 'patients.id')
        ->where('Packed.is_archive', 1)
        ->orderBy('Packed.id', 'DESC')
        ->skip(0)->take(50)
        ->get();
    }


    /* get  By column  */
    public static  function  get_by_where($condition)
    {
        return  Packed::where($condition)->get(); 
    }

    /* update data  */
    public static  function update_where($condition,$update_data)
    {
      return Packed::where($condition)->update($update_data);
    }

   /* delete record  */
   public  static  function  delete_record($id)
   {
     return Packed::find($id)->delete();
   }

   /* soft delete record  */
   public  static  function  soft_delete_record($id)
   {
     return Packed::find($id)->delete();
   }

   public function patients(){
    return $this->belongsTo('App\Models\Tenant\Patient','patient_id','id');
}

}
