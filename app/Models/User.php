<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Roles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

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
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    //     'password' => 'hashed',
    // ];

    protected $guarded = ['id'];
    protected $table = 'users';

    
    public function oAuthAccessToken()
    {
        return $this->hasMany('App\Models\OAuthAccessToken', 'user_id', 'id');
    }
    public function oAuthClient()
    {
        return $this->hasMany('App\Models\OAuthClient', 'user_id', 'id');
    }
    
    public function roles()
    {
        return $this->belongsTo('App\Models\Roles','role_id','id');
    }
       
    public function library()
    {
        return $this->belongsTo('App\Models\Library','library_id','id');
    }
   
    public function cafe()
    {
        return $this->belongsTo('App\Models\Cafe','cafe_id','id');
    }
    
    public function order()
    {
        return $this->hasMany('App\Models\Order','user_id');
    }
    
    public function booking()
    {
        return $this->hasMany('App\Models\Booking','user_id');
    }

    public function authorizeRoles($roles)
    {
        if ($this->hasAnyRole($roles)) {
            return true;
        }
        abort(401, 'This action is unauthorized.');
    }

    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole($role)
    {
        if ($this->roles()->where('name', $role)->first()) {
            return true;
        }
        return false;
    }


}
