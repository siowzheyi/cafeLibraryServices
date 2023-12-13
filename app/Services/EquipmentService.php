<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Config;
use DateTime;
use App\Models\User;
use App\Models\Roles;
use App\Models\Equipment;
use App\Models\Library;
use App\Models\ItemCategory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class EquipmentService
{
   
    public function index($request)
    {
        $search_arr = $request->input('search');
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $order_arr = $request->input('order');
        $columnSortOrder = isset($order_arr) ? $order_arr : 'desc';

        $user = auth()->user();
        $library = Library::find($user->library_id);
        $records = $library->equipment()->join('libraries','libraries.id','=','equipments.library_id');
        // dd($request);

        $totalRecords = $records->count();

        $records = $records->where(function ($query) use ($searchValue) {
            $query->orWhere('equipments.name', 'like', '%' . $searchValue . '%');

        });

        if ($request->input('startDate') != null && $request->input('endDate') != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request->input('startDate')));
            $endDate = date('Y-m-d', strtotime($request->input('endDate'))) . ' 23:59:59';

            $records = $records->whereBetween('equipments.created_at', [$startDate, $endDate]);
        }
        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'equipments.*',
            'libraries.name as library_name',
            'libraries.address as library_address'
        )
            ->orderBy('equipments.created_at', $columnSortOrder)
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
               "id" => $record->id,
               "name" => $record->name,
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

    public function show($equipment)
    {
        $library = $equipment->library()->first();
        $data = [
            "id" => $equipment->id,
               "name" => $equipment->name,
               "remark" => $equipment->remark,
               "availability" => $equipment->availability,
               "status" => $equipment->status,
        ];

        return $data;
    }

    public function store($request)
    {
        $request = $request->validated();

        $category_id = ItemCategory::where('name','equipment')->first();

        $equipment = new equipment();
        $equipment->name = $request['name'];
        $equipment->availability = 1;

        if(isset($request['remark']))
        $equipment->remark = $request['remark'];
        $equipment->item_category_id = $category_id->id;


        $user = auth()->user();
        $equipment->library_id = $user->library_id;
        $equipment->save();


        return $equipment;
    }

    public function update($request, $equipment)
    {
        $request = $request->validated();

        if (isset($request['type'])) {
            if ($request['type'] === 'status') {
                $equipment->status = $equipment->status === 1 ? 0 : 1;
            }
            $equipment->save();
            return;
        }
        $equipment->name = $request['name'];
        $equipment->status = $request['status'];

        if(isset($request['remark']))
        $equipment->remark = $request['remark'];
        else
        $equipment->remark = null;

        $equipment->save();

        return;
    }

    public function equipmentListing($request)
    {
        
        $search_arr = $request['search'] ?? null;
        $searchValue = isset($search_arr) ? $search_arr : '';

                        
        
        $records = Equipment::where('library_id',$request['library_id'])
                            ->where('status',1)
                            ->where('availability',1);
        

        $totalRecords = $records->count();

        if($searchValue != null)
        {
            $records = $records->where(function ($query) use ($searchValue) {
                $query->orWhere('equipments.name', 'like', '%' . $searchValue . '%');

            });
        }

        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'equipments.*'
        )
        // ->groupBy('genre')
        ->orderBy('equipments.created_at', 'asc')
        ->get();

        // dd($records);

        $data_arr = array();
        foreach ($records as $key => $record) {           
            if($record->status == 1)
                $status = "active";
            else
                $status = "inactive";

            if($record->availability == 1)
                $available = "available";
            else
                $available = "disabled";

            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "status" => $status,
                "availability" => $available,
                "remark" => $record->remark,
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