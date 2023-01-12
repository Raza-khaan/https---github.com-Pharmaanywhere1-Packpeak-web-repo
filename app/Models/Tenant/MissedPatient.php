<?php

namespace App\Models\Tenant;
 
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class MissedPatient extends Model
{
    use UsesTenantConnection;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [ 
        'missed_tablet',
        'extra_tablet',
        'wrong_tablet',
        'wrong_day',
        'other',
        'person_involved',
        'initials',
        'created_by','deleted_by','status','updated_at','deleted_at','website_id'
    ];


     /* save data   */
     public static function insert_data($insert_data){
        return MissedPatient::create($insert_data);
      }


    public static  function  get_all()
    {
      
        return MissedPatient::where('missed_patients.deleted_at', NULL)
        ->where('missed_patients.is_archive', 0)
        ->orderBy('missed_patients.id', 'DESC')
        ->skip(0)->take(50)
        ->get();
    }

    public static  function  get_archived()
    {
      
        return MissedPatient::withTrashed()
        ->where('missed_patients.is_archive', 1)
        ->orderBy('missed_patients.id', 'DESC')
        ->skip(0)->take(50)
        ->get();
    }
    /* get  By column  */
    public static  function  get_by_where($condition)
    {
        return  MissedPatient::where($condition)->get(); 
    }

    /* update data  */
    public static  function update_where($condition,$update_data)
    {
        return MissedPatient::where($condition)->update($update_data);
    }

    public  static  function  get_last_month()
   {
        return MissedPatient::where('missed_patients.deleted_at', NULL)
        ->whereMonth('missed_patients.created_at', '=', Carbon::now()->subMonth()->month)
        ->where('missed_patients.is_archive', 0)
        ->orderBy('missed_patients.id', 'DESC')
        ->skip(0)->take(50)
        ->get();
   }

     /* delete record  */
   public  static  function  delete_record($id)
   {
     return MissedPatient::find($id)->delete();
   }

   public  static  function  soft_delete_record($id)
   {
     return MissedPatient::find($id)->delete();
   }


   public  static  function  delete_archived_record($id)
   {

        return MissedPatient::onlyTrashed()->find($id)->forceDelete();
   }
}
