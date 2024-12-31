<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use phpDocumentor\Reflection\Types\True_;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    protected $table = 'jn_register_users';

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function generateTwoFactorCode(): void
    {
            $this->timestamps = false;  // Prevent updating the 'updated_at' column
            $this->verification_code = rand(100000, 999999);  // Generate a random code
            $this->save();
    }
    public function resetTwoFactorCode(): void
    {
        $this->timestamps = false;
        $this->verification_code = null;
        $this->save();
    }

    public function isAdmin(){
        $roles = $this->getRoleNames();
        if ($roles[0] == 'Admin'){
            return true;
        }
        if ($roles[0] == 'Sub-Admin'){
            return true;
        }

        return  false;
    }

    static $rules = [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ];


    public function userSubscription(){
        return $this->hasOne('App\Models\UserSubscription','user_id');
    }

    public function routeNotificationForMail()
    {
        return $this->emailaddress; // This will return the custom column name
    }

    public function getCountry(){
        return $this->belongsTo('App\Models\Country','country','id')->withDefault(['name'=>'Pakistan','phonecode'=>'92']);
    }

}
