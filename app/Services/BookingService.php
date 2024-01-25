<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Config;
use DateTime;
use App\Models\User;
use App\Models\Roles;
use App\Models\Booking;
use App\Models\Cafe;
use App\Models\Media;
use Illuminate\Http\UploadedFile;
use App\Models\Table;
use App\Models\Library;
use App\Models\Beverage;
use App\Models\Room;
use App\Models\Book;
use App\Models\Equipment;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class BookingService
{
   
    public function index($request)
    {
        $search_arr = $request->input('search');
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $order_arr = $request->input('order');
        $columnSortOrder = isset($order_arr) ? $order_arr : 'desc';
        $type = $request->input('type');
        
        $user = auth()->user();
        // dd($user);
        $library = Library::find($user->library_id);
        $service = new Service();
        if(isset($type) && $type != null)
        {
            if($type == "room")
            {
                $records = $library->roomBooking()->join('users','users.id','=','bookings.user_id');
                // ->where('bookings.is_handled',0);
            }
            elseif($type == "equipment")
            {
                $records = $library->equipmentBooking()->join('users','users.id','=','bookings.user_id');
                // ->where('bookings.is_handled',0);                
            }
            elseif($type == "book")
            {
                $records = $library->bookBooking()->join('users','users.id','=','bookings.user_id');
                // ->where('bookings.is_handled',0);
            }
        }
        else
        {
            $records = Booking::join('users','users.id','=','bookings.user_id')
                        ->leftjoin('books', function ($join) use ($library) {
                            $join->on('books.id', '=', 'bookings.book_id')
                            ->where('books.library_id', $library->id);
                        })
                        ->leftjoin('equipments', function ($join) use ($library) {
                            $join->on('equipments.id', '=', 'bookings.equipment_id')
                            ->where('equipments.library_id', $library->id);
                        })
                        ->leftjoin('rooms', function ($join) use ($library) {
                            $join->on('rooms.id', '=', 'bookings.room_id')
                            ->where('rooms.library_id', $library->id);
                        });

        }
        $records = $records->where(function ($query) {
            $query->where(function ($query) {
                $query->whereIn('is_handled', ['approved', 'rejected'])
                    ->whereDate('bookings.created_at', now()->format('Y-m-d'));
            })
            ->orWhere(function ($query) {
                $query->where('is_handled', 'pending');
            });
        });
        
        
        $totalRecords = $records->count();


        if ($request->input('startDate') != null && $request->input('endDate') != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request->input('startDate')));
            $endDate = date('Y-m-d', strtotime($request->input('endDate'))) . ' 23:59:59';

            $records = $records->whereBetween('bookings.created_at', [$startDate, $endDate]);
        }
        $totalRecordswithFilter = $records->count();

        if(isset($type) && $type != null)
        {
            if($type == "room")
            {
                $records = $records->select(
                    'bookings.*',
                    'rooms.room_no',
                    'rooms.availability as room_availability',
                    'users.name as user_name'
        
                )
                    ->orderBy('bookings.created_at', $columnSortOrder)
                    ->get();
            }
            if($type == "book")
            {
                $records = $records->select(
                    'bookings.*',
                    'books.name as book_name',
                    'books.availability as book_availability',
                    'books.remainder_count',
                    'users.name as user_name'
        
                )
                    ->orderBy('bookings.created_at', $columnSortOrder)
                    ->get();
            }
            if($type == "equipment")
            {
                $records = $records->select(
                    'bookings.*',
                    'equipments.name as equipment_name',
                    'equipments.availability as equipment_availability',
                    'users.name as user_name'
        
                )
                    ->orderBy('bookings.created_at', $columnSortOrder)
                    ->get();
            }
        }
        else
        {
            $records = $records->select(
                'bookings.*',
                'books.name as book_name',
                'equipments.name as equipment_name',
                'rooms.room_no',
                'books.availability as book_availability',
                'books.remainder_count',
                'rooms.availability as room_availability',
                'equipments.availability as equipment_availability',
                'users.name as user_name'
    
            )
                ->orderBy('bookings.created_at', $columnSortOrder)
                ->get();

        }

        $data_arr = array();
        foreach ($records as $key => $record) {
            if($record->book_id != null)
            {
                $item_id = $record->book_id;
                $item_name = $record->book_name;
            }
            elseif($record->equipment_id != null)
            {
                $item_id = $record->equipment_id;
                $item_name = $record->equipment_name;
            }
            elseif($record->room_id != null)
            {
                $item_id = $record->room_id;
                $item_name = $record->room_no;
            }
            $data_arr[] = array(
               "id" => $record->id,
               "user_name" => $record->user_name,
               "item_id" => $item_id,
               "item_name" => $item_name,
               "quantity" => $record->quantity,
               "start_booked_at" => $record->start_booked_at,
               "end_booked_at" => $record->end_booked_at,
               "is_handled" => $record->is_handled,

               "created_at" => $record->created_at != null ? date('Y-m-d H:i:s',strtotime($record->created_at)) : null,

           );
        }

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }

    public function show($booking)
    {
        $service = new Service();
        $user = $booking->user()->first();
        if($booking->book_id != null)
        {
            $item_id = $booking->book_id;
            $item = Book::find($item_id);
            $item_name = $item->name;
            $item_picture = $item->picture ? $service->getImage('book',$item->id) : null;
        }
        elseif($booking->equipment_id != null)
        {
            $item_id = $booking->equipment_id;
            $item = Equipment::find($item_id);
            $item_name = $item->name;
            $item_picture = $item->picture ? $service->getImage('equipment',$item->id) : null;

        }
        elseif($booking->room_id != null)
        {
            $item_id = $booking->room_id;
            $item = Room::find($item_id);
            $item_name = $item->room_no;
            $item_picture = $item->picture ? $service->getImage('room',$item->id) : null;

        }
        $data = [
            "id" => $booking->id,
               "user_name" => $booking->user_name,
               "item_id" => $item_id,
               "item_name" => $item_name,
               "item_picture" => $item_picture,

            //    "item"   =>  $item,
               "quantity" => $booking->quantity,
               "start_booked_at" => $booking->start_booked_at,
               "end_booked_at" => $booking->end_booked_at,
               "start_at" => $booking->start_at,
               "end_at" => $booking->end_at,
               "penalty_status" =>  $booking->penalty_status,
               "penalty_amount" =>  $booking->penalty_amount,
               "penalty_paid_status" =>  $booking->penalty_paid_status,

               "is_handled" => $booking->is_handled,
               "created_at" => $booking->created_at != null ? date('Y-m-d H:i:s',strtotime($booking->created_at)) : null,

        ];

        return $data;
    }

    public function store($request)
    {
        $raw_request = $request;
        $request = $request->validated();
        // dd($request);

        $booking = new Booking();
        if(isset($request['book_id']))
        {
            $booking->book_id = $request['book_id'];
            $book = Book::find($request['book_id']);
            $booking->unit_price = $book->price;
        }
        if(isset($request['equipment_id']))
        {
            $booking->equipment_id = $request['equipment_id'];
            $equipment = Equipment::find($request['equipment_id']);
        }
        if(isset($request['room_id']))
        {
            $booking->room_id = $request['room_id'];
            $room = Room::find($request['room_id']);
        }

        $user = auth()->user();
        $booking->user_id = $user->id;

        $booking->start_booked_at = $request['start_booked_at'];
        $booking->end_booked_at = $request['end_booked_at'];

        $booking->quantity = $request['quantity'];
        $booking->is_handled = "pending";

        $booking->save();

        // update the item's remainder stock count
        if(isset($book))
        {
            $book->remainder_count = $book->remainder_count - $request['quantity'];
            if($book->remainder_count == 0)
            {
                $book->availability = 0;
            }
            $book->save();
        }
        elseif(isset($room))
        {
            $room->availability = 0;
            $room->save();
        }
        elseif(isset($equipment))
        {
            $equipment->availability = 0;
            $equipment->save();
        }

        return $booking;
    }

    public function update($request, $booking, $return = null)
    {
        $raw_request = $request;
        if($return == null)
        $request = $request->validated();
        $now = date('Y-m-d H:i:s',strtotime(now()));

        $result['status'] = "success";
        $result['data'] = "";
        $result['message'] = "Successfully changed booking status";

        if (isset($request['type'])) {
            if ($request['type'] === 'status') {
                $booking->is_handled = $request['action'];

                if($request['action'] == "approved")
                {
                    if($booking->start_booked_at <= $now)
                        $booking->start_at = $now;
                    else
                        $booking->start_at = $booking->start_booked_at;
                }
                elseif($request['action'] == "rejected")
                {
                    $booking->start_at = null;
                    $booking->end_at = null;
                }
            }
            elseif($request['type'] == "return")
            {
                $booking->end_at = $now;
            }

            $booking->save();
            if($booking->book_id != null)
            {
                // add back remainder count
                $book = Book::find($booking->book_id);
                $remainder = $book->remainder_count;
                $book->remainder_count =  $remainder+  $booking->quantity;
                $book->save();
                // dd($book);
            }

            if($booking->end_at > $booking->end_booked_at)
            {
                $booking->penalty_status = 1;
                if($booking->book_id != null)
                    $rate = Setting::where('name','penalty book')->first();
                elseif($booking->equipment_id != null)
                    $rate = Setting::where('name','penalty equipment')->first();
                elseif($booking->room_id != null)
                    $rate = Setting::where('name','penalty room')->first();
                
                $rate_value = $rate ? $rate->value : 1;
                $rate_time = $rate ? $rate->remark : "per hour";
                $rate_time = Str::afterLast($rate_time,'per ');
                
                $start_penalty_date = Carbon::parse($booking->end_booked_at);
                $end_penalty_date = Carbon::parse($booking->end_at);

                if($rate_time == "hour")
                {
                    $penalty_time_diff = $start_penalty_date->diffInHours($end_penalty_date);                    
                }
                else
                $penalty_time_diff = $start_penalty_date->diffInDays($end_penalty_date);     
            
                $penalty_amount = $penalty_time_diff * $rate_value;

                $booking->penalty_amount = $penalty_amount;
                $booking->save();

                // dd($booking);

                $result['status'] = "failure";
                $result['message'] = "Please pay penalty";
                $result['data'] = $booking;
            }
            // dd($book);
            return $result;
        }

        return;
    }

    public function bookingListing($request)
    {
        $library_id = $request['library_id'] ?? null;
        // $searchValue = isset($search_arr) ? $search_arr : '';
        
        $user = auth()->user();

        $records =  Booking::join('users','users.id','=','bookings.user_id')
        ->leftjoin('books', function ($join) {
            $join->on('books.id', '=', 'bookings.book_id');
        })
        ->leftjoin('equipments', function ($join) {
            $join->on('equipments.id', '=', 'bookings.equipment_id');
        })
        ->leftjoin('rooms', function ($join) {
            $join->on('rooms.id', '=', 'bookings.room_id');
        })
        ->where('bookings.user_id',$user->id);
        

        if($library_id != null)
        {
            $records =  Booking::join('users','users.id','=','bookings.user_id')
            ->leftjoin('books', function ($join) use ($library_id) {
                $join->on('books.id', '=', 'bookings.book_id')
                ->where('books.library_id',$library_id);
            })
            ->leftjoin('equipments', function ($join) use ($library_id) {
                $join->on('equipments.id', '=', 'bookings.equipment_id')
                ->where('equipments.library_id',$library_id);
            })
            ->leftjoin('rooms', function ($join) use ($library_id) {
                $join->on('rooms.id', '=', 'bookings.room_id')
                ->where('rooms.library_id',$library_id);
            })
            ->where('bookings.user_id',$user->id);
        }
        // $records = $records->where('libraries.id',$library_id);

        $service = new Service();

        $totalRecords = $records->count();

        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'bookings.*',
            // 'libraries.name as library_name'
        )
            // ->orderBy('bookings.is_handled', 'asc')
            ->orderBy('bookings.created_at','desc')
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
           

            if($record->book_id != null)
            {
                $item_id = $record->book_id;
                $item = Book::find($item_id);
                $item_name = $item->name;
                $item_picture = $item->picture ? $service->getImage('book',$item->id) : null;

            }
            elseif($record->equipment_id != null)
            {
                $item_id = $record->equipment_id;
                $item = Equipment::find($item_id);
                $item_name = $item->name;
                $item_picture = $item->picture ? $service->getImage('equipment',$item->id) : null;

            }
            elseif($record->room_id != null)
            {
                $item_id = $record->room_id;
                $item = Room::find($item_id);
                $item_name = $item->room_no;
                $item_picture = $item->picture ? $service->getImage('room',$item->id) : null;

            }
            $library = $item->library->first();

            $data_arr[] = array(
               "id" => $record->id,
               "status" => $record->is_handled,
                // "item" => $item,
                "item_id" => $item_id,
                "item_name" => $item_name,
                "item_picture" => $item_picture,

                "library_name" => $library->name,
                "library_id" => $library->id,

                "penalty_status" => $record->penalty_status,
                "quantity" => $record->quantity,
                // "total_price" => $record->total_price,
                "created_at" => $record->created_at != null ? date('Y-m-d H:i:s',strtotime($record->created_at)) : null,


           );
        }

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }

}
?>