<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = [
        'account_id',
        'title',
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
}
