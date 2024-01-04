<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Eloquent
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

    protected $table = 'payments';

    public function feesCategory()
    {
        return $this->belongsTo('App\Models\FeesCategory','fees_category_id','id');    
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order','order_id','id');    
    }

    public function booking()
    {
        return $this->belongsTo('App\Models\Booking','booking_id','id');    
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');    
    }

    public function generateReceiptNo($id, $type)
    {
        $today = date('Y-m-d', strtotime(now()));

        if($type == "library")
        {
            $library = Library::find($id);
            $room_booking = $library->roomBooking()->get()->count();
            $book_booking = $library->bookBooking()->get()->count();
            $equipment_booking = $library->equipmentBooking()->get()->count();
            $no = $room_booking + $book_booking + $equipment_booking;

        }
        else
        {
            $cafe = Cafe::find($id);
            $no = $cafe->through('beverage')->has('order')->get()->count();

        }
        // dd($no);
        // ->where('orders.created_at','>',$today)->get() 
        
        $digit = 4;
        
        $no++;
        
        $padded_number = str_pad((string)$no,$digit,'0',STR_PAD_LEFT);
        $receipt_no = "RECEIPT".$padded_number;

        
        return $receipt_no;
    }

}
