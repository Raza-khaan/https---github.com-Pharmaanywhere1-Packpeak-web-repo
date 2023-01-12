<?php

namespace App\Models\Tenant;
use Carbon\Carbon;
use Hash;
use Hyn\Tenancy\Traits\UsesTenantConnection; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Authenticatable 
{
    use UsesTenantConnection;

    protected $guard = 'pharmacy';
    use SoftDeletes;
    
    public $table = 'companies';

    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'email_verified_at',
    ];

    protected $fillable = [
        'name',
        'initials_name',
        'first_name',
        'last_name',
        'email',
        'username',
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
        'company_name',
        'sign',
        'dob',
        'phone',
        'pin',
        'registration_no',
        'address',
        'latitude',
        'longitude',
        'host_name',
        'roll_type',
        'website_id',
        'email_verified_at',
        'subscription',
        'roll_type',
        'expired_date',
        'created_by',
        'deleted_by',
        'status'
    ];


    public  static  function  select_all_technician()
    {
       return  Company::where('roll_type','technician')->get(); 
    }
    public  static  function  select_all_admin()
    {
       return  Company::where('roll_type','admin')->where('id','>',1)->get(); 
    }



    /* Update form On / OFF  */
    public static function update_data($column,$row_id,$status,$form){
      return Company::where($column,$row_id)->update(array($form=>$status));
    }

    /* update data  */
    public static  function update_where($condition,$update_data)
    {
        return Company::where($condition)->update($update_data);
    }

    /* select data by column name  */
    public  static  function getdatabycolumn_name($column,$row_id)
    {
        return Company::where($column,$row_id)->get();
    }
  /* get  By column  */
    public static  function  get_by_where($condition)
    {
        return  Company::where($condition)->get(); 
    }
    /* delete record  */
   public  static  function  delete_record($id)
   {
     return Company::find($id)->delete();
   }

    
}
