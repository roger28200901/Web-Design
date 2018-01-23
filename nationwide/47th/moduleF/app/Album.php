<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use Traits\TokenGenerator;

    protected $fillable = [
        'title',
        'description',
        'covers',
    ];

    protected $table = 'albums';

    public function images()
    {
        return $this->hasMany(Image::class, 'album_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($album) {
            /* Generating an album ID */
            $length_of_album_id = rand(5, 11);
            $album_id = $album->randomTokenWithLength($length_of_album_id);

            /* Saving into album */
            $album->attributes['album_id'] = $album_id;
        });
    }
}
