<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use SoftDeletes;
    protected $fillable = ['name'];

    

     /* delete record  */
   public  static  function  delete_record($id)
   {
     return Store::find($id)->delete();
   }
}
