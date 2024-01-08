<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Config;
use DateTime;
use App\Models\User;
use App\Models\Roles;
use App\Models\Announcement;
use App\Models\Library;
use App\Models\Media;
use Illuminate\Http\UploadedFile;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Cafe;
use App\Models\Book;
use App\Models\Room;
use App\Models\Equipment;
use App\Models\FeesCategory;
use App\Models\Beverage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class PaymentService
{
   
    public function index($request)
    {
        $search_arr = $request->input('search');
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $order_arr = $request->input('order');
        $columnSortOrder = isset($order_arr) ? $order_arr : 'desc';
        $library_id = $request->input('library_id');
        $cafe_id = $request->input('cafe_id');
        $type = $request->input('type');

        $user = auth()->user();
        if($user->hasRole('staff'))
        {
            if($user->library_id != null)
            $library = Library::find($user->library_id);
            else
            $cafe = Cafe::find($user->cafe_id);
        }
        else
        {
            if(isset($library_id) && $library_id != null)
                $library = Library::find($library_id);
            elseif(isset($cafe_id) && $cafe_id != null)
                $cafe = Cafe::find($cafe_id);
        }

        $service = new Service();
        if(isset($cafe) && $cafe != null)
        {
            // only display order income
            $fees_category = FeesCategory::where('name','order')->first();
            $records = Payment::where('payments.fees_category_id',$fees_category->id)->where('payments.cafe_id',$cafe->id);
        }
        elseif(isset($library) && $library != null)
        {
            // only display penalty income
            $fees_category = FeesCategory::where('name','penalty')->first();
            $records = Payment::where('payments.fees_category_id',$fees_category->id)->where('payments.library_id',$library->id);
        }
        else
        {
            if(isset($type) && $type != null)
            {
                if($type == "penalty")
                {
                     // only display penalty income
                    $fees_category = FeesCategory::where('name','penalty')->first();
                    $records = Payment::where('payments.fees_category_id',$fees_category->id);
                }
                elseif($type == "order")
                {
                     // only display order income
                    $fees_category = FeesCategory::where('name','order')->first();
                    $records = Payment::where('payments.fees_category_id',$fees_category->id);
                }
                else
                {
                    $records = Payment::whereNotNull('payments.user_id');
                }
            }
        }

        $user = auth()->user();
        if($user->hasRole('user'))
        {
            $records = $records->where('payments.user_id',$user->id);
        }

        // join with cafe, library, order, booking
        $records = $records->leftJoin('libraries', function ($join) {
            $join->on('libraries.id', '=', 'payments.library_id');
        })
        ->leftJoin('cafes', function ($join) {
            $join->on('cafes.id', '=', 'payments.cafe_id');
        })
        ->leftJoin('bookings', function ($join) {
            $join->on('bookings.id', '=', 'payments.booking_id');
        })
        ->leftJoin('orders', function ($join) {
            $join->on('orders.id', '=', 'payments.order_id');
        })
        ->join('users','users.id','=','payments.user_id');

        $totalRecords = $records->count();

        $records = $records->where(function ($query) use ($searchValue) {
            $query->orWhere('libraries.name', 'like', '%' . $searchValue . '%')
            ->orWhere('cafes.name', 'like', '%' . $searchValue . '%')
            ->orWhere('users.name', 'like', '%' . $searchValue . '%');

        });

        if ($request->input('startDate') != null && $request->input('endDate') != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request->input('startDate')));
            $endDate = date('Y-m-d', strtotime($request->input('endDate'))) . ' 23:59:59';

            $records = $records->whereBetween('payments.created_at', [$startDate, $endDate]);
        }
        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'payments.*',
            'libraries.name as library_name',
            'cafes.name as cafe_name',
            'orders.order_no',
            'users.name as  user_name'
        )
            ->orderBy('payments.created_at', $columnSortOrder)
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
               "id" => $record->id,
               "cafe_name" => $record->cafe_name,
               "library_name" => $record->library_name,
               "user_name" => $record->user_name,
               "order_no"    => $record->order_no,   
               "item_name"    => $record->item_name,      
               "unit_price"    => $record->unit_price,      
               "quantity"    => $record->quantity,      
               "receipt_no"    => $record->receipt_no,      
               "subtotal"    => $record->subtotal,      
               "service_charge_amount"    => $record->service_charge_amount,      
               "sst_amount"    => $record->sst_amount,      
               "total_price"    => $record->total_price,      
               "description"    => $record->description,      
               "receipt_no"    => $record->receipt_no,      

               "created_at" => $record->created_at != null ? date('Y-m-d H:i:s',strtotime($record->created_at)) : null,
               "status" => $record->status,

           );
        }

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }

    public function show($payment)
    {
        $service = new Service();
        $library = $payment->library()->first();
        $cafe = $payment->cafe()->first();
        $user = $payment->user()->first();
        $order = $payment->order()->first();

        $data = [
            "id"    =>  $payment->id,
            "cafe_name" => $cafe != null ? $cafe->name : null,
            "library_name" => $library != null ? $library->name : null,
            "user_name" => $user->name,
            "order_no"    => $order != null ? $order->order_no : null,   
            "item_name"    => $payment->item_name,      
            "unit_price"    => $payment->unit_price,      
            "quantity"    => $payment->quantity,      
            "receipt_no"    => $payment->receipt_no,      
            "subtotal"    => $payment->subtotal,      
            "service_tax_amount"    => $payment->service_tax_amount,      
            "sst_amount"    => $payment->sst_amount,      
            "total_price"    => $payment->total_price,      
            "description"    => $payment->description,      
            "receipt_no"    => $payment->receipt_no,      

            "created_at" => $payment->created_at != null ? date('Y-m-d H:i:s',strtotime($payment->created_at)) : null,
            "status" => $payment->status,

        ];

        return $data;
    }

    public function store($request)
    {
        $raw_request = $request;
        $request = $request->validated();
        // dd($request);

        $payment = new Payment();
        if($request['type'] == "penalty")
        $fees_category = FeesCategory::where('name','penalty')->first();
        else
        $fees_category = FeesCategory::where('name','order')->first();

        $payment->fees_category_id = $fees_category->id;
        if(isset($request['booking_id']))
        {
            // penalty payment
            $payment->booking_id = $request['booking_id'];
            $booking = Booking::find($request['booking_id']);
            $room = $booking->room()->first();
            $book = $booking->book()->first();
            $equipment = $booking->equipment()->first();
            if($room != null)
            {
                $item_name = $room->room_no;
                $library = $room->library()->first();
            }
            if($book != null)
            {
                $item_name = $book->name;
                $library = $book->library()->first();
            }
            if($equipment != null)
            {
                $item_name = $equipment->name;
                $library = $equipment->library()->first();
            }

            $payment->library_id = $library->id;

            $subtotal = $booking->penalty_amount;
            $description = "Penalty Payment";

            // update booking penalty paid status
            $booking->penalty_paid_status = 1;
            $booking->save();
        }
        if(isset($request['order_id']))
        {
            $payment->order_id = $request['order_id'];
            $order = Order::find($request['order_id']);
            $beverage = $order->beverage()->first();
            $cafe = $beverage->cafe()->first();
            $payment->cafe_id = $cafe->id;
            $payment->quantity = $order->quantity;
            $payment->unit_price = $order->unit_price;
            $subtotal = $order->unit_price * $order->quantity;
            $item_name = $beverage->name;
            $description = "Order Payment";
            
            // update order payment status
            $order->payment_status = "success";
            $order->save();
        }
        $payment->subtotal = $subtotal;
        $sst =  0.06 * $subtotal;
        $service =  0.1 * $subtotal;
        
        $payment->sst_amount = $sst;
        $payment->service_charge_amount = $service;
        $payment->total_price = $subtotal + $sst + $service;

        $user = auth()->user();
        $payment->user_id = $user->id;
        $payment->item_name = $item_name;
        $payment->description = $description;

        $payment->save();

        if(isset($library))
        $payment->receipt_no = $payment->generateReceiptNo($library->id, "library");
        else
        $payment->receipt_no = $payment->generateReceiptNo($cafe->id, "cafe");

        $payment->save();
        return $payment;
    }

    public function update($request, $announcement)
    {
        $raw_request = $request;
        $request = $request->validated();

        if (isset($request['type'])) {
            if ($request['type'] === 'status') {
                $announcement->status = $announcement->status === 1 ? 0 : 1;
            }
            $announcement->save();
            return;
        }
        $announcement->title = $request['title'];
        $announcement->content = $request['content'];
        $announcement->status = $request['status'];

        if(isset($request['expired_at']))
        $announcement->expired_at = $request['expired_at'];
        else
        $announcement->expired_at = null;
        $announcement->save();

        

        return;
    }


}
?>