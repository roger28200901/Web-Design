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
        'place_id',
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

    /**
     * Get the schedules which depart from this place.
     */
    public function schedulesDepartFromPlace()
    {
        return $this->hasMany(Schedule::class, 'from_place_id', 'place_id');
    }

    /**
     * Get the schedules which arrive to this place.
     */
    public function schedulesArriveToPlace()
    {
        return $this->hasMany(Schedule::class, 'to_place_id', 'place_id');
    }

    /**
     * Get the histories which depart from this place.
     */
    public function historiesDepartFromPlace()
    {
        return $this->hasMany(History::class, 'from_place_id', 'place_id');
    }

    /**
     * Get the histories which arrive to this place.
     */
    public function historiesArriveToPlace()
    {
        return $this->hasMany(History::class, 'to_place_id', 'place_id');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($place) {
            $place->schedulesDepartFromPlace()->delete();
            $place->schedulesArriveToPlace()->delete();
            $place->historiesDepartFromPlace()->delete();
            $place->historiesArriveToPlace()->delete();
        });
    }
}
