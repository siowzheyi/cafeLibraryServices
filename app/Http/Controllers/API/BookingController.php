<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\BookingService;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use App\Services\Service;

use App\Http\Requests\BookingRequest;
use App\Models\User;
use App\Models\Booking;
use App\Models\Book;
use App\Models\Equipment;
use App\Models\Room;

use Auth;
use App;
use Validator;

class BookingController extends BaseController
{
    public function __construct(BookingService $booking_service)
    {
        $this->services = $booking_service;
    }

    // This api is for admin user to create Booking
    public function store(BookingRequest $request)
    {
        $result = $this->services->store($request);
        
        if($result != null)
            return $this->sendResponse("", "Booking has been successfully created. ");
        else
            return $this->sendCustomValidationError(['Error'=>'Failed to create data. ']);

    }

    // This api is for admin user to view certain Booking
    public function show(Booking $booking)
    {
        $result = $this->services->show($booking);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    public function edit(Booking $booking)
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
               "picture" => $item_picture,

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

    // This api is for admin user to view list of Booking
    public function index(Request $request)
    {
        // $result = $this->services->index($request);
        // dd($request->library_id);

        // return $this->sendResponse($result, "Data successfully retrieved. "); 
        return view('library.booking.index');
    }

    // This api is for admin user to update certain Booking
    public function update(BookingRequest $request, Booking $booking)
    {
        $input = $request->all();
        $input['booking_id'] = $booking->id;

        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'booking_id' => array('required','exists:bookings,id',function ($attribute, $value, $fail) use($request){
                if($request['type'] == "return")
                {
                    $booking = Booking::find($value);
                    if($booking->is_handled == "pending")
                    $fail("Cannot return book before staff approved to borrow");
                }
            }),
        ]);

        if ($validator->fails()) {
            return $this->sendCustomValidationError($validator->errors());
        }


        $result = $this->services->update($request, $booking);

        return $this->sendResponse($result['data'], $result['message']);     
    }

    public function returnBooking(Request $request, Booking $booking)
    {
        $input = $request->all();
        $input['booking_id'] = $booking->id;

        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'booking_id' => array('required','exists:bookings,id',function ($attribute, $value, $fail) use($request){
                if($request['type'] == "return")
                {
                    $booking = Booking::find($value);
                    if($booking->is_handled == "pending")
                    $fail("Cannot return book before staff approved to borrow");
                }
            }),
        ]);

        if ($validator->fails()) {
            return $this->sendCustomValidationError($validator->errors());
        }


        $result = $this->services->update($request, $booking);

        return $this->sendResponse($result['data'], $result['message']);   
    }

    // This api is for admin user to view list of Booking listing
    public function bookingListing(Request $request)
    {
        $input = $request->all();

        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'library_id' => array('nullable','exists:libraries,id'),
        ]);

        if ($validator->fails()) {
            return $this->sendCustomValidationError($validator->errors());
        }

        $result = $this->services->bookingListing($input);

        return $this->sendResponse($result, "Data has been successfully retrieved. ");
    }

    public function getBookingDatatable(Request $request)
    {
        if (request()->ajax()) {
            $type = $request->type;

            $user = auth()->user();
            
            $service = new Service();
            if(isset($type) && $type != null)
            {
                if($type == "room")
                {
                    $data = Booking::join('rooms','rooms.id','=','bookings.room_id')
                    ->join('libraries','libraries.id','=','rooms.library_id')
                    ->join('users','users.id','=','bookings.user_id')->select(
                        'bookings.*',
                        'rooms.room_no as name',
                        'users.name as user_name'
            
                    )
                        ->orderBy('bookings.created_at', 'asc');
                        if($user->hasRole('admin'))
                            $data = $data->where('rooms.library_id',$request->library_id);
                        else
                            $data = $data->where('rooms.library_id',$user->library_id);
                }
                elseif($type == "equipment")
                {
                    $data = Booking::join('equipments','equipments.id','=','bookings.equipment_id')
                    ->join('libraries','libraries.id','=','equipments.library_id')
                    ->join('users','users.id','=','bookings.user_id')->select(
                        'bookings.*',
                        'equipments.name as name',
                        'users.name as user_name'
            
                    )
                        ->orderBy('bookings.created_at', 'asc');
                        if($user->hasRole('admin'))
                            $data = $data->where('equipments.library_id',$request->library_id);
                        else
                            $data = $data->where('equipments.library_id',$user->library_id);
                }
                elseif($type == "book")
                {
                    $data = Booking::join('books','books.id','=','bookings.book_id')
                    ->join('libraries','libraries.id','=','books.library_id')
                    ->join('users','users.id','=','bookings.user_id')->select(
                        'bookings.*',
                        'books.name as name',
                        'users.name as user_name'
            
                    )
                        ->orderBy('bookings.created_at', 'asc');
                        if($user->hasRole('admin'))
                            $data = $data->where('books.library_id',$request->library_id);
                        else
                            $data = $data->where('books.library_id',$user->library_id);

                    }
                }
                // dd($data->get());
            $data = $data->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereIn('is_handled', ['approved', 'rejected'])
                        ->whereDate('bookings.created_at', now()->format('Y-m-d'));
                })
                ->orWhere(function ($query) {
                    $query->where('is_handled', 'pending');
                });
            });
            // dd($data->get());
            $table = Datatables::of($data);

           

            $table->addColumn('status', function ($row) {
                $checked = $row->is_handled == "approved" ? 'checked' : '';
                $status = $row->is_handled == "approved" ? 'Completed' : 'Pending';
            
                $btn = '<div class="form-check form-switch">';
                $btn .= '<input class="form-check-input data-status" type="checkbox" data-id="'.$row->id.'" '.$checked.'>';
                $btn .= '<label class="form-check-label">'.$status.'</label>';
                $btn .= '</div>';
            
                return $btn;
            });
            

            $table->addColumn('action', function ($row) {
                $token = csrf_token();

                $btn ='<button id="'.$row->id.'" data_id="' . $row->id . '" data-token="' . $token . '" class="btn btn-primary m-1 showData" data-bs-toggle="modal" data-bs-target="#orderModal">View</button>';
                return $btn;
            });

            $table->rawColumns(['status','action']);
            return $table->make(true);
        }
    }

}
