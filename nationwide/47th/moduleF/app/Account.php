<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'account',
        'bio',
    ];

    protected $table = 'accounts';

    /**
     * The "bootinh" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($account) {
            /* Generating a token */
            $length_of_token = 7;
            $characters = array_merge(range(0, 9), range('A', 'Z'), range('a', 'z'));
            shuffle($characters);
            $token = join(array_slice($characters, 0, $length_of_token), '');

            /* Saving to account */
            $account->attributes['token'] = $token;
        });
    }
}
