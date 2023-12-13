<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cafe extends Eloquent
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

    protected $table = 'cafes';


    public function user()
    {
        return $this->hasMany('App\Models\User','cafe_id');
    }
    
    public function library()
    {
        return $this->belongsTo('App\Models\Library','library_id','id');
    }
    
    public function item()
    {
        return $this->hasMany('App\Models\Item','cafe_id');
    }
     
    public function beverage()
    {
        return $this->hasMany('App\Models\Beverage','cafe_id');
    }
    

}
