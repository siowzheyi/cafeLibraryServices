<?php

namespace App\Models;
use App\Models\Library;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Eloquent
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

    protected $table = 'bookings';

    
    public function room()
    {
        return $this->belongsTo('App\Models\Room','room_id','id');    
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function equipment()
    {
        return $this->belongsTo('App\Models\Equipment','equipment_id','id');    
    }

    public function book()
    {
        return $this->belongsTo('App\Models\Book','book_id','id');    
    }
   

}
