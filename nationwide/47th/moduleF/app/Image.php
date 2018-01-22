<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'album_id',
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
}
