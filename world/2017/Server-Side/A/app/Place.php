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
        'image_path',
        'description'
    ];
    
    private $longitude_start = 54.2522;
    private $longitude_end = 54.66419;
    private $latitude_start = 24.35429;
    private $latitude_end = 24.59895;
    private $map_width = 1280;
    private $map_height = 800;

    protected $table = 'places';

    public $timestamps = false;

    /**
     * Set the place's longitude and calculate x with longitude.
     *
     * @param  string  $longitude
     * @return void
     */
    public function setLongitudeAttribute($longitude)
    {
        $this->attributes['longitude'] = $longitude;
        $this->attributes['x'] = ($longitude - $this->longitude_start) * $this->map_width / ($this->longitude_end - $this->longitude_start);
    }

    /**
     * Set the place's latitude and calculate y with latitude.
     *
     * @param  string  $latitude
     * @return void
     */
    public function setLatitudeAttribute($latitude)
    {
        $this->attributes['latitude'] = $latitude;
        $this->attributes['y'] = ($this->latitude_end - $latitude) * $this->map_height / ($this->latitude_end - $this->latitude_start);
    }

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
