<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Library extends Eloquent
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $guarded = [];

    protected $table = 'libraries';


    public function user()
    {
        return $this->hasMany('App\Models\User','library_id');
    }
    
    public function cafe()
    {
        return $this->hasOne('App\Models\Cafe','library_id');
    }
    
    public function announcement()
    {
        return $this->hasMany('App\Models\Announcement','library_id');
    }
    
    public function item()
    {
        return $this->hasMany('App\Models\Item','library_id');
    }
    
    public function table()
    {
        return $this->hasMany('App\Models\Table','library_id');
    }
     
    public function book()
    {
        return $this->hasMany('App\Models\Book','library_id');
    }
  
    public function equipment()
    {
        return $this->hasMany('App\Models\Equipment','library_id');
    }  

    public function room()
    {
        return $this->hasMany('App\Models\Room','library_id');
    }
}
