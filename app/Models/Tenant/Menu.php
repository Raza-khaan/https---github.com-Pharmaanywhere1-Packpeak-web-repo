<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','url','param','icon','submenu','sequence'];

    public function subscriptions(){
        return $this->belongsToMany('App\Models\Admin\Subscription');
    }

    public function submenus(){
        return $this->hasMany('App\Models\Tenant\SubMenu');
    }

}
