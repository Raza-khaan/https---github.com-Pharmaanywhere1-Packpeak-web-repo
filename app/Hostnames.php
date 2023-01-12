<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hostnames extends Model
{
    use SoftDeletes;

    public $table = 'hostnames';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'fqdn',
        'website_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
