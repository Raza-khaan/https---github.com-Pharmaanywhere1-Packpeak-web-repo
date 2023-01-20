<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use  DB;

class All_Admin extends Model
{
    public static  function  save_data($table,$insert_data)
    {
       return DB::table($table)->insert($insert_data);
    }
}
