<?php

namespace App;

use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Hyn\Tenancy\Traits\UsesSystemConnection; 
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class User extends Authenticatable
{
    use SoftDeletes, Notifiable, HasApiTokens;
    use UsesSystemConnection,AuthenticableTrait; 
    
    public $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];

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
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
        'company_name',
        'sign',
        'dob',
        'phone',
        'registration_no',
        'address',
        'latitude',
        'longitude',
        'favourite',
        'website_id',
        'username',
        'pin',
        'host_name',
        'email_verified_at',
        'subscription',
        'expired_date',
        'status',
        'Allowedsms',
        'usedsms',
        'isverified'
    ];

    public function website(): BelongsTo
    {
        return $this->belongsTo(config('tenancy.models.website'));
    }

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function authorProjects()
    {
        return $this->hasMany(Project::class, 'author_id', 'id');
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }


    public static function get_user_details($request){
        return User::where('email' , $request->email)->first();
     }
     public static function get_all_users()
     {
         // return User::where('status' , '1')->where('roll_id', '!=' , '1')->get();
         return User::where('status' , '1')->get();
     }
 
     public static function get_by_id($request){
         return User::where('id' ,$request->row_id)->first();
     }

     public static function get_by_column($column_name,$website_id)
     {
        return User::where($column_name,$website_id)->get();
     }

}
