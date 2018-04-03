<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use Traits\TokenGenerator;

    protected $fillable = [
        'account',
        'bio',
    ];

    protected $table = 'accounts';


    /**
     * The "albums" method of the model.
     *
     * @return albums
     */
    public function albums()
    {
        return $this->hasMany(Album::class, 'account_id');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($account) {
            /* Generating an account ID */
            $length_of_account_id = rand(5, 11);
            $account_id = $account->randomTokenWithLength($length_of_account_id);

            /* Generating a token */
            $length_of_token = 7;
            $token = $account->randomTokenWithLength($length_of_token);

            /* Saving into account */
            $account->attributes['account_id'] = $account_id;
            $account->attributes['token'] = $token;
        });
    }
}
