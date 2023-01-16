<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Phermacists extends Model
{
    use SoftDeletes;

    public $table = 'phermacist';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'company_name',
        'password',
        'subscription',
        'updated_at',
        'website_id'
    ];
}
