<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Eloquent
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

    protected $table = 'items';


    public function user()
    {
        return $this->hasMany('App\Models\User','item_id');
    }
    
    public function library()
    {
        return $this->belongsTo('App\Models\Library','library_id','id');
    }
      
    public function cafe()
    {
        return $this->belongsTo('App\Models\Cafe','cafe_id','id');
    }

    public function itemCategory()
    {
        return $this->belongsTo('App\Models\ItemCategory','item_category_id','id');
    }
}
