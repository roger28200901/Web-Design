<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use Traits\TokenGenerator;

    use SoftDeletes;

    protected $fillable = [
        'album_id',
        'filename',
        'title',
        'description',
        'width',
        'height',
        'size',
        'link',
    ];

    protected $table = 'images';

    public function album()
    {
        return $this->belongsTo(Album::class, 'album_id');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($image) {
            /* Generating an image ID */
            $length_of_image_id = 10;
            $image_id = $image->randomTokenWithLength($length_of_image_id);

            /* Saving into image */
            $image->attributes['image_id'] = $image_id;
        });

        static::deleting(function ($image) {
            /* Generating an delete token */
            $length_of_delete_token = 16;
            $delete_token = $image->randomTokenWithLength($length_of_delete_token);

            /* Saving into image */
            $image->delete_token = $delete_token; // Deleting event will ignore attributes
            $image->save();
        });
    }
}
