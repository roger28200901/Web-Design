<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Hash;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $table = 'users';

    public $timestamps = false;

    /**
     * Set the user's password.
     *
     * @param  string  $password
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * Set the user's token in login status.
     *
     * @return void
     */
    public function login()
    {
        $this->token = md5($this->username);
        $this->save();
    }

    /**
     * Unset the user's token while logout.
     *
     * @return void
     */
    public function logout()
    {
        $this->token = '';
        $this->save();
    }
}
