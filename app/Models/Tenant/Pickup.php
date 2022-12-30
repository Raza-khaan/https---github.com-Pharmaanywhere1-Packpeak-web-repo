<?php

namespace App\Models\Tenant;
use Hyn\Tenancy\Traits\UsesTenantConnection; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class PickUp extends Model
{
    use UsesTenantConnection; 
    use SoftDeletes;
    protected $table = 'pick_ups';

    protected $fillable = ['patient_id','dob','last_pick_up_date','weeks_last_picked_up','no_of_weeks','location','pick_up_by',
    'carer_name','notes_for_patient','notes_from_patient','pharmacist_sign','patient_sign','pickup_date','pickup_slot','created_at',
    'created_by','deleted_by','status','pharmacy_image','patient_image','image','updated_at','deleted_at','website_id'
    //,'reminderdefaultdays'
  ];
    
       
    public function patients()
    {
      return $this->belongsTo('App\Models\Tenant\Patient','patient_id','id');
    }

     /* save data   */
     public static function insert_data($insert_data){
        return PickUp::create($insert_data);
    }

    public static  function  get_all_pickup()
    {
        return PickUp::where('pick_ups.deleted_at', NULL)
        ->select('pick_ups.*','patients.first_name','patients.last_name','facilities.name as facility')
        ->join('patients', 'pick_ups.patient_id', '=', 'patients.id')
        ->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
        ->where('facilities.deleted_at', NULL)
        ->where('patients.deleted_at', NULL)
        ->where('pick_ups.is_archive', 0)
        ->orderBy('pick_ups.id', 'DESC')
        ->get();
    }

    public static  function  get_all()
    {
        return PickUp::where('pick_ups.deleted_at', NULL)
        ->select('pick_ups.*','patients.first_name','patients.last_name','facilities.name as facility')
        ->join('patients', 'pick_ups.patient_id', '=', 'patients.id')
        ->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
        ->where('facilities.deleted_at', NULL)
        ->where('patients.deleted_at', NULL)
        ->where('pick_ups.is_archive', 0)
        ->orderBy('pick_ups.id', 'DESC')
        ->skip(0)->take(50)
        ->get();
    }

    public static  function  get_three_days_prior_pickups()
    {
        return PickUp::where('pick_ups.deleted_at', NULL)
        ->select('pick_ups.*','patients.first_name','patients.last_name','facilities.name as facility')
        ->join('patients', 'pick_ups.patient_id', '=', 'patients.id')
        ->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
        ->where('facilities.deleted_at', NULL)
        ->where('patients.deleted_at', NULL)
        ->where('pick_ups.is_archive', 0)
        ->where('pick_ups.pickup_date','>','DATE_ADD(CURDATE(), INTERVAL 3 DAY)')
        ->orderBy('pick_ups.id', 'DESC')
        ->skip(0)->take(50)
        ->get();
    }

    public static  function  get_last_month_pickups()
    {
        $start_date = date('Y-m-d', strtotime('first day of last month')).' 00:00:01';
        $end_date   = date('Y-m-d', strtotime('last day of last month')).' 23:59:59';

        return PickUp::where('pick_ups.deleted_at', NULL)
        ->select('pick_ups.*','patients.first_name','patients.last_name','facilities.name as facility')
        ->join('patients', 'pick_ups.patient_id', '=', 'patients.id')
        ->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
        ->where('facilities.deleted_at', NULL)
        ->where('patients.deleted_at', NULL)
        ->whereBetween('pick_ups.created_at', [$start_date,$end_date])
        ->where('pick_ups.is_archive', 0)
        ->orderBy('pick_ups.id', 'DESC')
        ->skip(0)->take(50)
        ->get();
    }

    public static  function  get_archived()
    {
      return PickUp::withTrashed()
        ->select('pick_ups.*','patients.first_name','patients.last_name','facilities.name as facility')
        ->join('patients', 'pick_ups.patient_id', '=', 'patients.id')
        ->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
        ->where('pick_ups.is_archive', 1)
        ->orderBy('pick_ups.id', 'DESC')
        ->skip(0)->take(50)
        ->get();  
    }

    /* get  By column  */
    public static  function  get_by_where($condition)
    {
        return  PickUp::where($condition)->get(); 
    }

    /* update data  */
    public static  function update_where($condition,$update_data)
    {
    
      return PickUp::where($condition)->update($update_data);
    }


    // get six month record 
   public  static function get_six_month()
   {   
      return PickUp::where('pick_ups.deleted_at', NULL)
      ->select('pick_ups.*','patients.first_name','patients.last_name','facilities.name as facility')
      ->join('patients', 'pick_ups.patient_id', '=', 'patients.id')
      ->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
      ->where('facilities.deleted_at', NULL)
      ->where('patients.deleted_at', NULL)
      ->where("pick_ups.created_at",">", Carbon::now()->subMonths(6))
      ->where('pick_ups.is_archive', 0)
      ->orderBy('pick_ups.id', 'DESC')
      // ->skip(0)->take(50)
      ->get();
   }

   public  static function get_six_month_archived()
   {   
      return PickUp::withTrashed()
      ->select('pick_ups.*','patients.first_name','patients.last_name','facilities.name as facility')
      ->join('patients', 'pick_ups.patient_id', '=', 'patients.id')
      ->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
      ->where("pick_ups.created_at",">", Carbon::now()->subMonths(6))
      ->where('pick_ups.is_archive', 1)
      ->orderBy('pick_ups.id', 'DESC')
      // ->skip(0)->take(50)
      ->get();
   }

   public  static  function  get_last_month()
   {
        return PickUp::where('pick_ups.deleted_at', NULL)
        ->select('pick_ups.*','patients.first_name','patients.last_name','facilities.name as facility')
        ->join('patients', 'pick_ups.patient_id', '=', 'patients.id')
        ->join('facilities', 'patients.facilities_id', '=', 'facilities.id')
        ->where('facilities.deleted_at', NULL)
        ->where('patients.deleted_at', NULL)
        ->whereMonth('pick_ups.created_at', '=', Carbon::now()->subMonth()->month)
        ->where('pick_ups.is_archive', 0)
        ->orderBy('pick_ups.id', 'DESC')
        // ->skip(0)->take(50)
        ->get();
   }

    /* delete record  */
    public  static  function  delete_record($id)
    {
      
      return PickUp::find($id)->delete();
    
    }
    /* soft delete record  */
    public  static  function  soft_delete_record($id)
    {
      return PickUp::find($id)->delete();
    }

  }