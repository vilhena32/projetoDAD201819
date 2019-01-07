<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPasswordNotification;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username', 'photo_url', 'blocked','shift_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 
    ];

    public function order()
    {
        return $this->belongsToMany(Order::class, 'responsible_cook_id');
    }

    public function meal()
    {
        return $this->hasMany(Meal::class);
    }

    public function findForPassport($username) //funcao quer permite o login com username/email 
    {
        if($this->where('username', $username)->first()!=null)
        {
            return $this->where('username', $username)->first();
        }
        return $this->where('email', $username)->first();
        
    }   



}
