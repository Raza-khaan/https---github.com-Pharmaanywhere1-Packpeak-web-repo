<?php

namespace App\Models\Admin;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
// use App\Notifications\AdminResetPasswordNotification as Notification;

class Admin extends Authenticatable {

    use SoftDeletes,Notifiable; 

    protected $guard = 'admin';
    // use Notification;
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable 
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ]; 

    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token',
        'status',
        'code',
        'number'
    ];

    public static function get_super_admin($request) { 
        // return Admin::where('status', 1)->first();
        return Admin::where('status', 1)->where('email',$request->email)->first();
    }
    

    /**
     * Custom password reset notification.
     * 
     * @return void
     */
    public function sendPasswordResetNotification($token) {
        $this->notify(new Notification($token));
    }

}
