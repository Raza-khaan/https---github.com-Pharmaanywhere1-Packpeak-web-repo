<?php


namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
   
    use SoftDeletes;
    protected $fillable = ['name'];

    public function patients(){
        return $this->hasMany('App\Models\Tenant\Patient','facilities_id','id');
    }
}
