<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Eloquent
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
    protected $dates = ['created_at', 'updated_at', 'deleted_at','expired_at'];
    protected $guarded = [];

    protected $table = 'tables';

 
    public function library()
    {
        return $this->belongsTo('App\Models\Library','library_id','id');
    }
   

    public function order()
    {
        return $this->hasMany('App\Models\Order','table_id');
    }
}
