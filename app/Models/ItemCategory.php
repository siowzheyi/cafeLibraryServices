<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemCategory extends Eloquent
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

    protected $table = 'item_categories';

   
    public function item()
    {
        return $this->hasMany('App\Models\Item','item_category_id');
    }

}
