<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'x',
        'y',
        'image_path',
        'description'
    ];

    protected $table = 'places';

    public $timestamps = false;
}
