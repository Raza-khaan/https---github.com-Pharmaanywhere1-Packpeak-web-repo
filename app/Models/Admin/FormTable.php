<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class FormTable extends Model
{
    
    protected $fillable = ['form_name','form_description'];

    // public function patients(){
    //     return $this->belongsTo('App\Models\Tenant\Patient','facilities_id','id');
    // }
    


}
