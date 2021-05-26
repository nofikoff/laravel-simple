<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 *
 * @property int id
 * @property string name
 * @property string email
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @var string[]
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @param $value
     * @return Carbon
     */
    public function getCreatedAtAttribute($value): Carbon
    {
        return Carbon::parse($value);
    }

    /**
     * @param $value
     * @return Carbon
     */
    public function getUpdatedAtAttribute($value): Carbon
    {
        return Carbon::parse($value);
    }
}
