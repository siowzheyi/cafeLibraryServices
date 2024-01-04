<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Config;
use DateTime;
use App\Models\User;
use App\Models\Roles;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Book;
use App\Models\Equipment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function login($request)
    {
        $data['status'] = "fail";
        $data['message'] = ['email' => 'Wrong email/password'];
        $data['data'] = "";

        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {

            $user = Auth::user();

            if ($user->status != 1) {
                $data['message'] = ['email' => 'Your account has been locked. Please contact customer service for enquiry.'];
            }
            else{
                $oauth_access_token = $user->createToken('CLS Access Token');
                $result['token'] =  $oauth_access_token->accessToken;
                $result['name'] =  $user->name;
                $result['id'] = $user->id;
                $user_role = $user->roles()->first();
                $result['role_id'] = $user_role->id;
                $result['role'] = $user_role->name;
                if($user->library_id != null)
                $result['cafe_or_library'] = "library staff";
                elseif($user->cafe_id != null)
                $result['cafe_or_library'] = "cafe staff";
                else
                $result['cafe_or_library'] = null;
                // dd($oauth_access_token);
                $token = $oauth_access_token->token;
                $token->access_token = $result['token'];
                $token->status = "full login";

                $token->save();

                $data['data'] = $result;
                $data['status'] = "success";
                $data['message'] = 'User login successfully.';
            }
        } 

        return $data;
    }

    public function register($request)
    {
        $role = Roles::where('name','user')->first();
        $user = new User();
        $user->email = $request['email'];
        $user->plain_password = $request['password'];
        $user->password = bcrypt($request['password']);
        $user->role_id = $role->id;
        $user->name = $request['name'];
        $user->phone_no = $request['phone_no'];
        $user->save();

        return $user;
    }

    public function index($request)
    {
        $search_arr = $request->input('search');
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $order_arr = $request->input('order');
        $columnSortOrder = isset($order_arr) ? $order_arr : 'desc';

        $records = User::join('roles','roles.id','=','users.role_id');
        // dd($request);
        if($request->input('type') != null)
        {
            $role = Roles::where('name','staff')->first();

            if($request->input('type') == 'cafe')
            {
                $records = $records->where('users.role_id',$role->id)
                                    ->whereNotNull('users.cafe_id');
            }
            elseif($request->input('type') == 'library')
            {
                $records = $records->where('users.role_id',$role->id)
                                    ->whereNotNull('users.library_id');
            }
        }

        $totalRecords = $records->count();

        $records = $records->where(function ($query) use ($searchValue) {
            $query->orWhere('users.name', 'like', '%' . $searchValue . '%')
            ->orWhere('users.email', 'like', '%' . $searchValue . '%')
            ->orWhere('users.phone_no', 'like', '%' . $searchValue . '%');
        });

        if ($request->input('startDate') != null && $request->input('endDate') != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request->input('startDate')));
            $endDate = date('Y-m-d', strtotime($request->input('endDate'))) . ' 23:59:59';

            $records = $records->whereBetween('users.created_at', [$startDate, $endDate]);
        }
        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'users.*',
            'roles.name as role_name'
        )
            ->orderBy('users.created_at', $columnSortOrder)
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
               "id" => $record->id,
               "name" => $record->name,
               "status" => $record->status,
               "role_name" => $record->role_name,
               "email" => $record->email,
               "phone_no" => $record->phone_no,
           );
        }

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }

    public function show($user)
    {
        $role = Roles::find($user->role_id);
        $data = [
            "id"    =>  $user->id,
            "name"    =>  $user->name,
            "email"    =>  $user->email,
            "phone_no"    =>  $user->phone_no,
            "status"    =>  $user->status,
            "library_id"    =>  $user->library_id,
            "cafe_id"    =>  $user->cafe_id,
            "role"    =>  $role->name,

        ];

        return $data;
    }

    public function store($request)
    {
        $request = $request->validated();

        $user = new User();
        $user->name = $request['name'];
        $user->phone_no = $request['phone_no'];
        $user->email = $request['email'];
        $user->plain_password = $request['password'];
        $user->password = bcrypt($request['password']);

        $role = Roles::where('name','staff')->first();
        $user->role_id = $role->id;
        $user->save();

        return $user;
    }

    public function update($request, $user)
    {
        $request = $request->validated();

        if (isset($request['type'])) {
            if ($request['type'] === 'status') {
                $user->status = $user->status === 1 ? 0 : 1;
            }
            $user->save();
            return;
        }
        $user->name = $request['name'];
        $user->status = $request['status'];
        $user->email = $request['email'];
        $user->phone_no = $request['phone_no'];

        $user->save();

        return;
    }

    public function penaltyReport($request)
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
        ->where('bookings.penalty_status',1);
        

        if($library_id != null || $user->library_id != null)
        {
            if($user->library_id != null)
            $library_id = $user->library_id;

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
            ->where('bookings.penalty_status',1);
        }

        if($user->hasRole('user'))
            $records = $records->where('bookings.user_id',$user->id);


        $service = new Service();

        $totalRecords = $records->count();

        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'bookings.*',
            'users.name as user_name'
        )
            ->orderBy('bookings.penalty_paid_status', 'asc')
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

            if($record->penalty_paid_status == 1)
                $penalty_paid_status = "Paid";
            else
            $penalty_paid_status = "Haven't pay";


            $data_arr[] = array(
               "id" => $record->id,
               "user_name" => $record->user_name,
                // "item" => $item,
                "item_id" => $item_id,
                "item_name" => $item_name,
                "item_picture" => $item_picture,

                "library_name" => $library->name,
                "library_id" => $library->id,

                // "penalty_status" => $record->penalty_status,
                "penalty_amount" => $record->penalty_amount,
                "penalty_paid_status" => $penalty_paid_status,

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

    public function penaltyReportItem($request, $booking_id)
    {
        // $library_id = $request['library_id'] ?? null;
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
        ->where('bookings.penalty_status',1)
        ->where('bookings.id',$booking_id);        

        $service = new Service();

        $totalRecords = $records->count();

        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'bookings.*',
            'users.name as user_name',
            'users.phone_no as user_phone_no'
        )
            ->orderBy('bookings.penalty_paid_status', 'asc')
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
           

            if($record->book_id != null)
            {
                $item_id = $record->book_id;
                $item = Book::find($item_id);
                $item_picture = $item->picture ? $service->getImage('book',$item->id) : null;

            }
            elseif($record->equipment_id != null)
            {
                $item_id = $record->equipment_id;
                $item = Equipment::find($item_id);
                $item_picture = $item->picture ? $service->getImage('equipment',$item->id) : null;

            }
            elseif($record->room_id != null)
            {
                $item_id = $record->room_id;
                $item = Room::find($item_id);
                $item_picture = $item->picture ? $service->getImage('room',$item->id) : null;

            }
            $library = $item->library->first();

            if($record->penalty_paid_status == 1)
                $penalty_paid_status = "Paid";
            else
            $penalty_paid_status = "Haven't pay";


            $data_arr[] = array(
               "id" => $record->id,
               "user_name" => $record->user_name,
               "user_phone_no" => $record->user_phone_no,
                "item_id" => $item_id,
                "item_name" => $record->item_name,
                "item_picture" => $item_picture,
                "unit_price"    =>  $record->unit_price,
                "quantity"  =>  $record->quantity,
                "subtotal"  =>  $record->subtotal,  
                "sst_amount"  =>  $record->sst_amount,  
                "service_charge_amount"  =>  $record->service_charge_amount,  
                "total_price"  =>  $record->total_price,  

                "library_name" => $library->name,
                "library_id" => $library->id,

                // "penalty_status" => $record->penalty_status,
                "penalty_amount" => $record->penalty_amount,
                "penalty_paid_status" => $penalty_paid_status,

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