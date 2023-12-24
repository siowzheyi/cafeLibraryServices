<?php

namespace App\Models;
use App\Models\Order;
use App\Models\Cafe;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Eloquent
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

    protected $table = 'orders';

 
    public function cafe()
    {
        return $this->belongsTo('App\Models\Cafe','cafe_id','id');
    }

    
    public function beverage()
    {
        return $this->hasMany('App\Models\Beverage','beverage_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function table()
    {
        return $this->belongsTo('App\Models\Table','table_id','id');
    }

    public function generateOrderNo($cafe_id)
    {
        $today = date('Y-m-d', strtotime(now()));

        $cafe = Cafe::find($cafe_id);
        $no = $cafe->order()->where('orders.created_at','>',$today)->get()->count(); 
        
        $digit = 4;
        
        $no++;
        
        $padded_number = str_pad((string)$no,$digit,'0',STR_PAD_LEFT);
        
        return $padded_number;
    }
   

}
