<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuSubscription extends Model
{
    use SoftDeletes;

    protected $fillable = ['menu_id','subscription_id'];


}
