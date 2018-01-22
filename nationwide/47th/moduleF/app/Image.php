<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'album_id',
    ];
    
    protected $table = 'images';

    public function album()
    {
        return $this->belongsTo(Album::class, 'album_id');
    }
}
