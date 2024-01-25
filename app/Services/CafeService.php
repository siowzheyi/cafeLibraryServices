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

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class CafeService
{
   
    public function index($request)
    {
        $search_arr = $request->input('search');
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $order_arr = $request->input('order');
        $columnSortOrder = isset($order_arr) ? $order_arr : 'desc';

        $records = Cafe::join('libraries','libraries.id','=','cafes.library_id');
        // dd($request);

        $totalRecords = $records->count();

        $records = $records->where(function ($query) use ($searchValue) {
            $query->orWhere('cafes.name', 'like', '%' . $searchValue . '%');
        });

        if ($request->input('startDate') != null && $request->input('endDate') != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request->input('startDate')));
            $endDate = date('Y-m-d', strtotime($request->input('endDate'))) . ' 23:59:59';

            $records = $records->whereBetween('cafes.created_at', [$startDate, $endDate]);
        }
        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'cafes.*',
            'libraries.name as library_name',
            'libraries.address as library_address'
        )
            ->orderBy('cafes.created_at', $columnSortOrder)
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
               "id" => $record->id,
               "name" => $record->name,
               "status" => $record->status,
               "library_name" => $record->library_name,
               "library_address" => $record->library_address,

           );
        }

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }

    public function show($cafe)
    {
        $library = $cafe->library()->first();
        $data = [
            "id"    =>  $cafe->id,
            "name"    =>  $cafe->name,
            "status"    =>  $cafe->status,

            "library_id"    =>  $cafe->library_id,
            "library_name"    =>  $library->name,
            "library_address"    =>  $library->address,

        ];

        return $data;
    }

    public function store($request)
    {
        $request = $request->validated();

        $cafe = new Cafe();
        $cafe->name = $request['name'];
        $cafe->library_id = $request['library_id'];
        $cafe->save();

        $user = auth()->user();

        $user->cafe_id = $cafe->id;
        $user->save();

        return $cafe;
    }

    public function update($request, $cafe)
    {
        // $request = $request->validated();

        if (isset($request['type'])) {
            if ($request['type'] === 'status') {
                $cafe->status = $cafe->status === 1 ? 0 : 1;
            }
            $cafe->save();
            return;
        }
        $cafe->name = $request['name'];
        // $cafe->status = $request['status'];

        $cafe->save();

        return;
    }

}
?>