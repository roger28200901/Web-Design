<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'from_place_id',
        'to_place_id',
        'schedule_id'
    ];

    protected $table = 'histories';

    public $timestamps = false;
}
