<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Config;
use DateTime;
use App\Models\User;
use App\Models\Roles;
use App\Models\Cafe;
use App\Models\Library;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class LibraryService
{
   
    public function index($request)
    {
        $search_arr = $request->input('search');
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $order_arr = $request->input('order');
        $columnSortOrder = isset($order_arr) ? $order_arr : 'desc';

        $records = Library::whereNotNull('name');
        // dd($request);

        $totalRecords = $records->count();

        $records = $records->where(function ($query) use ($searchValue) {
            $query->orWhere('libraries.name', 'like', '%' . $searchValue . '%')
            ->orWhere('libraries.address', 'like', '%' . $searchValue . '%');
        });

        if ($request->input('startDate') != null && $request->input('endDate') != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request->input('startDate')));
            $endDate = date('Y-m-d', strtotime($request->input('endDate'))) . ' 23:59:59';

            $records = $records->whereBetween('libraries.created_at', [$startDate, $endDate]);
        }
        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'libraries.*'
        )
            ->orderBy('libraries.created_at', $columnSortOrder)
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
               "id" => $record->id,
               "name" => $record->name,
               "status" => $record->status,
               "address" => $record->address,
           );
        }

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }

    public function show($library)
    {
        $data = [
            "id"    =>  $library->id,
            "name"    =>  $library->name,
            "address"    =>  $library->address,
            "status"    =>  $library->status,

        ];

        return $data;
    }

    public function store($request)
    {
        $request = $request->validated();

        $library = new Library();
        $library->name = $request['name'];
        $library->address = $request['address'];
        $library->save();

        $user = auth()->user();

        $user->library_id = $library->id;
        $user->save();

        return $library;
    }

    public function update($request, $library)
    {
        $request = $request->validated();

        if (isset($request['type'])) {
            if ($request['type'] === 'status') {
                $library->status = $library->status === 1 ? 0 : 1;
            }
            $library->save();
            return;
        }
        $library->name = $request['name'];
        $library->status = $request['status'];
        $library->address = $request['address'];

        $library->save();

        return;
    }

}
?>