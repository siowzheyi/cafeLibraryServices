<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Config;
use DateTime;
use App\Models\User;
use App\Models\Roles;
use App\Models\Room;
use App\Models\Library;
use App\Models\ItemCategory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class RoomService
{
   
    public function index($request)
    {
        $search_arr = $request->input('search');
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $order_arr = $request->input('order');
        $columnSortOrder = isset($order_arr) ? $order_arr : 'desc';

        $user = auth()->user();
        $library = Library::find($user->library_id);
        $records = $library->room()->join('libraries','libraries.id','=','rooms.library_id');
        // dd($request);

        $totalRecords = $records->count();

        $records = $records->where(function ($query) use ($searchValue) {
            $query->orWhere('rooms.room_no', 'like', '%' . $searchValue . '%');

        });

        if ($request->input('startDate') != null && $request->input('endDate') != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request->input('startDate')));
            $endDate = date('Y-m-d', strtotime($request->input('endDate'))) . ' 23:59:59';

            $records = $records->whereBetween('rooms.created_at', [$startDate, $endDate]);
        }
        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'rooms.*',
            'libraries.name as library_name',
            'libraries.address as library_address'
        )
            ->orderBy('rooms.created_at', $columnSortOrder)
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
               "id" => $record->id,
               "room_no" => $record->room_no,
               "room_type" => $record->type,
               "remark" => $record->remark,
               "availability" => $record->availability,
               "status" => $record->status,
            //    "library_name" => $record->library_name,
            //    "library_address" => $record->library_address,

           );
        }

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }

    public function show($room)
    {
        $library = $room->library()->first();
        $data = [
            "id" => $room->id,
               "room_no" => $room->room_no,
               "room_type" => $room->type,
               "remark" => $room->remark,
               "availability" => $room->availability,
               "status" => $room->status,

        ];

        return $data;
    }

    public function store($request)
    {
        $request = $request->validated();

        $category_id = ItemCategory::where('name','room')->first();

        $room = new Room();
        $room->room_no = $request['room_no'];
        $room->type = $request['room_type'];

        if(isset($request['remark']))
        $room->remark = $request['remark'];
        $room->item_category_id = $category_id->id;


        $user = auth()->user();
        $room->library_id = $user->library_id;
        $room->save();


        return $room;
    }

    public function update($request, $room)
    {
        $request = $request->validated();

        if (isset($request['type'])) {
            if ($request['type'] === 'status') {
                $room->status = $room->status === 1 ? 0 : 1;
            }
            $room->save();
            return;
        }
        $room->room_no = $request['room_no'];
        $room->status = $request['status'];

        $room->type = $request['room_type'];

        if(isset($request['remark']))
        $room->remark = $request['remark'];
        else
        $room->remark = null;
        $room->save();

        return;
    }

    public function roomListing($request)
    {
        
        $search_arr = $request['search'] ?? null;
        $searchValue = isset($search_arr) ? $search_arr : '';

        $records = Room::where('library_id',$request['library_id'])
                            ->where('status',1)
                            ->where('availability',1);
        $totalRecords = $records->count();

        if($searchValue != null)
        {
            $records = $records->where(function ($query) use ($searchValue) {
                $query->orWhere('rooms.room_no', 'like', '%' . $searchValue . '%');

            });
        }

        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'rooms.type'
        )
        ->groupBy('type')
        // ->orderBy('books.created_at', 'asc')
        ->get();

        // dd($records);

        $data_arr = array();
        foreach ($records as $key => $record) {
            
            $room_related = Room::where('type',$record->type)
                            ->get();
            $room_arr = array();

            foreach ($room_related as $key => $room) {
                $room_arr[] = [
                    "id" => $room->id,
                    "room_no" => $room->room_no,
                   "remark" => $room->remark,
                ];
            }

            
            $data_arr[] = array(
                "type" => $record->type,
                "rooms" =>  $room_arr
                

           );
        }
        // dd($data_arr);

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }


}
?>