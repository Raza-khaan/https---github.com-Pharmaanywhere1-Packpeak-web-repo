<?php
namespace App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection; 
use Illuminate\Database\Eloquent\SoftDeletes;

class NotesForPatient extends Model
{
    use UsesTenantConnection; 
    use SoftDeletes;
    protected $fillable = ['patient_id','dob','notes_for_patients','notes_as_text',
    'created_by','deleted_by','status','updated_at','deleted_at','website_id'
    ];

  protected $dates = ['deleted_at'];

     /* save data   */
    public static function insert_data($insert_data){
      return NotesForPatient::create($insert_data);
    }

    /* get  data  All  of Admin  */
    public static  function  get_all()
    {
       
       return NotesForPatient::where('notes_for_patients.deleted_at', NULL)
      ->select('notes_for_patients.*','patients.first_name','patients.last_name')
      ->join('patients', 'notes_for_patients.patient_id', '=', 'patients.id')
      ->where('patients.deleted_at', NULL)
      ->where('notes_for_patients.is_archive', 0)
      ->orderBy('notes_for_patients.id', 'DESC')
      ->skip(0)->take(50)
      ->get();
      
    }

    public static  function  get_archived()
    {
       
       return NotesForPatient::withTrashed()
      ->select('notes_for_patients.*','patients.first_name','patients.last_name')
      ->join('patients', 'notes_for_patients.patient_id', '=', 'patients.id')
      ->where('notes_for_patients.is_archive', 1)
      ->orderBy('notes_for_patients.id', 'DESC')
      ->skip(0)->take(50)
      ->get();
      
    }

    /* get  By column  */
    public static  function  get_by_where($condition)
    {
        return  NotesForPatient::where($condition)->get(); 
    }

    /* update data  */
    public static  function update_where($condition,$update_data)
    {
      return NotesForPatient::where($condition)->update($update_data);
    }

    public static  function update_unarchive_where($condition,$update_data)
    {
      return NotesForPatient::where($condition)->update($update_data);
    }


    /* delete record  */
    public  static  function  delete_record($id)
    {
      return NotesForPatient::find($id)->delete();
    }

    public function patients(){
      return $this->belongsTo('App\Models\Tenant\Patient','patient_id','id');
  }

  public  static  function  soft_delete_record($id)
  {
    return NotesForPatient::find($id)->delete();
  }


 



    
}
