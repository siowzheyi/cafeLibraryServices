<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Config;
use DateTime;
use App\Models\User;
use App\Models\Roles;
use App\Models\Report;
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

class ReportService
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
        // $records = Order::join('beverages','beverages.id','=','orders.beverage_id')
        //                 ->join('cafes','cafes.id','=','beverages.cafe_id')
        //                 ->where('cafes.id',$cafe->id);
        // $records = $library->through('beverage')->has('order')->join('tables','tables.id','=','orders.table_id');
        // $orders = $cafe->beverage()->with('order')->get()->pluck('order')->collapse();
        if(isset($type) && $type != null)
        {
            if($type == "room")
            {
                $records = $library->roomReport()->join('users','users.id','=','reports.user_id')
                ->join('rooms','rooms.id','=','reports.room_id')
                ->where('reports.status',0);
            }
            elseif($type == "equipment")
            {
                $records = $library->equipmentReport()->join('users','users.id','=','reports.user_id')
                ->join('equipments','equipments.id','=','reports.equipment_id')
                ->where('reports.status',0);                
            }
            elseif($type == "book")
            {
                $records = $library->bookReport()->join('users','users.id','=','reports.user_id')
                ->join('books','books.id','=','reports.book_id')
                ->where('reports.status',0);
            }
        }
        else
        {
            // dd($user, $library);
            $records = Report::join('users','users.id','=','reports.user_id')
                        ->leftjoin('books', function ($join) use ($library) {
                            $join->on('books.id', '=', 'reports.book_id')
                            ->where('books.library_id', $library->id);
                        })
                        ->leftjoin('equipments', function ($join) use ($library) {
                            $join->on('equipments.id', '=', 'reports.equipment_id')
                            ->where('equipments.library_id', $library->id);
                        })
                        ->leftjoin('rooms', function ($join) use ($library) {
                            $join->on('rooms.id', '=', 'reports.room_id')
                            ->where('rooms.library_id', $library->id);
                        });
                        // ->where('reports.is_handled','pending');

        }
        $records = $records->where(function ($query) {
            $query->where(function ($query) {
                $query->where('reports.status', 1)
                    ->whereDate('reports.created_at', now()->format('Y-m-d'));
            })
            ->orWhere(function ($query) {
                $query->where('reports.status', 0);
            });
        });
        
        

        // dd($records->get(), $orders);
        
        $totalRecords = $records->count();

        // $records = $records->where(function ($query) use ($searchValue) {
        //     $query->orWhere('orders.title', 'like', '%' . $searchValue . '%')
        //     ->orWhere('orders.content', 'like', '%' . $searchValue . '%');

        // });

        if ($request->input('startDate') != null && $request->input('endDate') != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request->input('startDate')));
            $endDate = date('Y-m-d', strtotime($request->input('endDate'))) . ' 23:59:59';

            $records = $records->whereBetween('reports.created_at', [$startDate, $endDate]);
        }
        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'reports.*',
            'books.name as book_name',
            'equipments.name as equipment_name',
            'rooms.room_no',
            // 'books.availability as book_availability',
            // 'books.remainder_count',
            // 'rooms.availability as room_availability',
            // 'equipments.availability as equipment_availability',
            'users.name as user_name'

        )
            ->orderBy('reports.created_at', $columnSortOrder)
            ->get();

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
               "name" => $record->name,
               "description" => $record->description,
               "status" => $record->status,
            //    "picture"    =>  $record->picture ? 

               "created_at" => $record->created_at != null ? date('Y-m-d H:i:s',strtotime($record->created_at)) : null,

           );
        }

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }

    public function show($report)
    {
        $service = new Service();
        $user = $report->user()->first();
        if($report->book_id != null)
        {
            $item_id = $report->book_id;
            $item = Book::find($item_id);
            $item_name = $item->name;
            $item_picture = $item->picture ? $service->getImage('book',$item->id) : null;
        }
        elseif($report->equipment_id != null)
        {
            $item_id = $report->equipment_id;
            $item = Equipment::find($item_id);
            $item_name = $item->name;
            $item_picture = $item->picture ? $service->getImage('equipment',$item->id) : null;

        }
        elseif($report->room_id != null)
        {
            $item_id = $report->room_id;
            $item = Room::find($item_id);
            $item_name = $item->room_no;
            $item_picture = $item->picture ? $service->getImage('room',$item->id) : null;

        }
        $data = [
            "id" => $report->id,
               "user_name" => $report->user_name,
               "item_id" => $item_id,
               "item_name" => $item_name,
               "item_picture" => $item_picture,

            //    "item"   =>  $item,
               "name" => $report->name,
               "description" => $report->description,
               "remark" => $report->remark,
               "picture" => $report->picture ? $service->getImage('report',$report->id) : null,
               "status" => $report->status,
               "created_at" => $report->created_at != null ? date('Y-m-d H:i:s',strtotime($report->created_at)) : null,

        ];

        return $data;
    }

    public function store($request)
    {
        $raw_request = $request;
        $request = $request->validated();
        // dd($request);

        $report = new Report();
        if(isset($request['book_id']))
        $report->book_id = $request['book_id'];
        if(isset($request['equipment_id']))
        $report->equipment_id = $request['equipment_id'];
        if(isset($request['room_id']))
        $report->room_id = $request['room_id'];

        $user = auth()->user();
        $report->user_id = $user->id;

        $report->name = $request['name'];
        $report->description = $request['description'];

        
        $report->save();
        
        if ($raw_request->hasfile('picture')) {
            $service = new Service();
            $service->storeImage('report',$raw_request->file('picture'),$report->id);
        }
        return $report;
    }

    public function update($request, $report)
    {
        $raw_request = $request;
        $request = $request->validated();
        if (isset($request['type'])) {
            if ($request['type'] === 'status') {
                $report->status = $report->status === 1 ? 0 : 1;
            }
            $report->save();
            return;
        }

        if(isset($request['remark']))
        $report->remark = $request['remark'];
        else
        $report->remark = null;
        $report->save();

        return;
    }

    public function reportListing($request)
    {
        $library_id = $request['library_id'] ?? null;
        // $searchValue = isset($search_arr) ? $search_arr : '';
        
        $user = auth()->user();

        $records =  Report::join('users','users.id','=','reports.user_id')
        ->leftjoin('books', function ($join) {
            $join->on('books.id', '=', 'reports.book_id');
        })
        ->leftjoin('equipments', function ($join) {
            $join->on('equipments.id', '=', 'reports.equipment_id');
        })
        ->leftjoin('rooms', function ($join) {
            $join->on('rooms.id', '=', 'reports.room_id');
        })
        ->where('reports.user_id',$user->id);
        

        if($library_id != null)
        {
            $records =  Report::join('users','users.id','=','reports.user_id')
            ->leftjoin('books', function ($join) use ($library_id) {
                $join->on('books.id', '=', 'reports.book_id')
                ->where('books.library_id',$library_id);
            })
            ->leftjoin('equipments', function ($join) use ($library_id) {
                $join->on('equipments.id', '=', 'reports.equipment_id')
                ->where('equipments.library_id',$library_id);
            })
            ->leftjoin('rooms', function ($join) use ($library_id) {
                $join->on('rooms.id', '=', 'reports.room_id')
                ->where('rooms.library_id',$library_id);
            })
            ->where('reports.user_id',$user->id);
        }
        // $records = $records->where('libraries.id',$library_id);

        $service = new Service();

        $totalRecords = $records->count();

        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'reports.*',
            // 'libraries.name as library_name'
        )
            ->orderBy('reports.status', 'asc')
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

            if($record->status == 0)
            $status = "pending";
            else
            $status = "completed";

            $data_arr[] = array(
               "id" => $record->id,
               "status" => $status,
                // "item" => $item,
                "item_id" => $item_id,
                "item_name" => $item_name,
                "item_picture" => $item_picture,

                "library_name" => $library->name,
                "library_id" => $library->id,

                "name" => $record->name,
                "description" => $record->description,
                "picture" => $record->picture ? $service->getImage('report',$record->id):null,
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