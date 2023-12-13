<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Config;
use DateTime;
use App\Models\User;
use App\Models\Roles;
use App\Models\Cafe;
use App\Models\Beverage;
use App\Models\ItemCategory;
use App\Models\Library;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class BeverageService
{
    public function beverageListing($request)
    {
        $search_arr = $request['search'] ?? null;
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $records = Beverage::where('cafe_id',$request['cafe_id'])
                        ->where('status',1);
        // dd($request);

        $totalRecords = $records->count();

        if($searchValue != null)
        {
            $records = $records->where(function ($query) use ($searchValue) {
                $query->orWhere('beverages.name', 'like', '%' . $searchValue . '%');
            });
        }
        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'beverages.*'
        )
            ->orderBy('beverages.created_at', 'asc')
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
               "id" => $record->id,
               "name" => $record->name,
                "remark" => $record->remark,
                "price" => $record->price,

               "status" => $record->status,
           );
        }

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }
   
    public function index($request)
    {
        $search_arr = $request->input('search');
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $order_arr = $request->input('order');
        $columnSortOrder = isset($order_arr) ? $order_arr : 'desc';

        $user = auth()->user();
     
        $cafe = Cafe::find($user->cafe_id);
        $records = $cafe->beverage();
    
        // dd($request);

        $totalRecords = $records->count();

        $records = $records->where(function ($query) use ($searchValue) {
            $query->orWhere('beverages.name', 'like', '%' . $searchValue . '%');
        });

        if ($request->input('startDate') != null && $request->input('endDate') != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request->input('startDate')));
            $endDate = date('Y-m-d', strtotime($request->input('endDate'))) . ' 23:59:59';

            $records = $records->whereBetween('beverages.created_at', [$startDate, $endDate]);
        }
        $totalRecordswithFilter = $records->count();

        if($user->library_id != null)
        {
            $records = $records->select(
                'beverages.*',
                
            )
                ->orderBy('beverages.created_at', $columnSortOrder)
                ->get();
        }
        elseif($user->cafe_id != null)
        {
            $records = $records->select(
                'beverages.*'
                
            )
                ->orderBy('beverages.created_at', $columnSortOrder)
                ->get();
        }

        

        $data_arr = array();
        foreach ($records as $key => $record) {
            if($record->status == 1)
                $status = "active";
            else
                $status = "inactive";
            $data_arr[] = array(
               "id" => $record->id,
               "name" => $record->name,
               "status" => $status,
               "remark" => $record->remark,
               "price" => $record->price,
           );
        }

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }

    public function show($beverage)
    {
        $cafe = $beverage->cafe()->first();

        if($beverage->status == 1)
            $status = "active";
        else
            $status = "inactive";
        // $beverage_category = $beverage->beverageCategory()->first();
        $data = [
            "id"    =>  $beverage->id,
            "name"    =>  $beverage->name,
            "status"    =>  $status,
            "remark"    =>  $beverage->remark,
            "price"    =>  $beverage->price,

        ];

        return $data;
    }

    public function store($request)
    {
        $request = $request->validated();

        $item_category = ItemCategory::where('name','beverage')->first();

        $beverage = new Beverage();
        $beverage->name = $request['name'];
        $beverage->item_category_id = $item_category->id;
        $beverage->price = $request['price'];

        $user = auth()->user();
        $beverage->cafe_id = $user->cafe_id;

        if(isset($request['remark']))
            $beverage->remark = $request['remark'];

        

        $beverage->save();


        return $beverage;
    }

    public function update($request, $beverage)
    {
        $request = $request->validated();

        if (isset($request['type'])) {
            if ($request['type'] === 'status') {
                $beverage->status = $beverage->status === 1 ? 0 : 1;
            }
            $beverage->save();
            return;
        }
        $beverage->name = $request['name'];
        $beverage->status = $request['status'];
        $beverage->price = $request['price'];

        if(isset($request['remark']))
            $beverage->remark = $request['remark'];
        else
            $beverage->remark = null;

        $beverage->save();

        return;
    }

   
}
?>