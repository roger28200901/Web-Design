<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'line',
        'from_place_id',
        'to_place_id',
        'departure_time',
        'arrival_time',
        'distance',
        'speed',
        'status'
    ];

    protected $table = 'schedules';

    public $timestamps = false;

    /**
     * Get the from place owns the schedule.
     */
    public function from_place()
    {
        return $this->belongsTo(Place::class, 'from_place_id', 'place_id');
    }

    /**
     * Get the to place owns the schedule.
     */
    public function to_place()
    {
        return $this->belongsTo(Place::class, 'to_place_id', 'place_id');
    }
}
