<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasManyThrough as HasManyThrough;
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

    public function bookBooking(): HasManyThrough
    {
        return $this->hasManyThrough(
            Booking::class,
            Book::class,
            'library_id', // Foreign key on the environments table...
            'book_id', // Foreign key on the order table...
            'id', // Local key on the projects table...
            'id' // Local key on the environments table...
        );
    }
    public function roomBooking(): HasManyThrough //\Illuminate\Database\Eloquent\Relations\HasManyThrough//
    {
        return $this->hasManyThrough(
            Booking::class,
            Room::class,
            'library_id', // Foreign key on the environments table...
            'room_id', // Foreign key on the order table...
            'id', // Local key on the projects table...
            'id' // Local key on the environments table...
        );
    }

    public function equipmentBooking(): HasManyThrough
    {
        return $this->hasManyThrough(
            Booking::class,
            Equipment::class,
            'library_id', // Foreign key on the environments table...
            'equipment_id', // Foreign key on the order table...
            'id', // Local key on the projects table...
            'id' // Local key on the environments table...
        );
    }
    
    public function bookReport(): HasManyThrough
    {
        return $this->hasManyThrough(
            Report::class,
            Book::class,
            'library_id', // Foreign key on the environments table...
            'book_id', // Foreign key on the order table...
            'id', // Local key on the projects table...
            'id' // Local key on the environments table...
        );
    }
    public function roomReport(): HasManyThrough
    {
        return $this->hasManyThrough(
            Report::class,
            Room::class,
            'library_id', // Foreign key on the environments table...
            'room_id', // Foreign key on the order table...
            'id', // Local key on the projects table...
            'id' // Local key on the environments table...
        );
    }
    public function equipmentReport(): HasManyThrough
    {
        return $this->hasManyThrough(
            Report::class,
            Equipment::class,
            'library_id', // Foreign key on the environments table...
            'equipment_id', // Foreign key on the order table...
            'id', // Local key on the projects table...
            'id' // Local key on the environments table...
        );
    }
}
