<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

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

    public function getCovers()
    {
        $covers = json_decode($this->covers)->cover;
        return $this->images()->whereIn('image_id', $covers)->get();
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

        static::deleting(function ($album) {
            $images = $album->images;
            foreach ($images as $image) {
                Storage::disk('upload')->delete($image->filename);
                $image->forceDelete();
            }
        });
    }
}
