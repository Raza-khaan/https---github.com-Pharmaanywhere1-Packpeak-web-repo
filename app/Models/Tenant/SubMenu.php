<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubMenu extends Model
{
    use SoftDeletes;

    protected $fillable = ['menu_id','name','url','param','icon','submenu','sequence'];

    public function subscriptions(){
        return $this->belongsToMany('App\Models\Admin\Subscription');
    }

    public function menus(){
        return $this->belongsToMany('App\Models\Tenant\Menu');
    }
}
