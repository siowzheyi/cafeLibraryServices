<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Config;
use DateTime;
use App\Models\User;
use App\Models\Roles;
use App\Models\Cafe;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Library;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class ItemService
{
    public function itemCategory($request)
    {
        $search_arr = $request->input('search');
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $order_arr = $request->input('order');
        $columnSortOrder = isset($order_arr) ? $order_arr : 'asc';

        $records = ItemCategory::whereNotNull('name');
        // dd($request);

        $totalRecords = $records->count();

        $records = $records->where(function ($query) use ($searchValue) {
            $query->orWhere('item_categories.name', 'like', '%' . $searchValue . '%');
        });

        if ($request->input('startDate') != null && $request->input('endDate') != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request->input('startDate')));
            $endDate = date('Y-m-d', strtotime($request->input('endDate'))) . ' 23:59:59';

            $records = $records->whereBetween('item_categories.created_at', [$startDate, $endDate]);
        }
        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'item_categories.*'
        )
            ->orderBy('item_categories.created_at', $columnSortOrder)
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
               "id" => $record->id,
               "name" => $record->name,
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
        if($user->library_id != null)
        {
            $library = Library::find($user->library_id);
            $records = $library->item()->join('item_categories as ic','ic.id','=','items.item_category_id')
            ->join('libraries','libraries.id','=','items.library_id');
        }
        elseif($user->cafe_id != null)
        {
            $cafe = Cafe::find($user->cafe_id);
            $records = $cafe->item()->join('item_categories as ic','ic.id','=','items.item_category_id')
            ->join('cafes','cafes.id','=','items.cafe_id');
        }
        else
        return [];
        // dd($request);

        $totalRecords = $records->count();

        $records = $records->where(function ($query) use ($searchValue) {
            $query->orWhere('items.name', 'like', '%' . $searchValue . '%');
        });

        if ($request->input('startDate') != null && $request->input('endDate') != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request->input('startDate')));
            $endDate = date('Y-m-d', strtotime($request->input('endDate'))) . ' 23:59:59';

            $records = $records->whereBetween('items.created_at', [$startDate, $endDate]);
        }
        $totalRecordswithFilter = $records->count();

        if($user->library_id != null)
        {
            $records = $records->select(
                'items.*',
                'libraries.name as building_name',
                'libraries.id as building_id',
                'ic.id as category_id',
                'ic.name as category_name'
            )
                ->orderBy('items.created_at', $columnSortOrder)
                ->get();
        }
        elseif($user->cafe_id != null)
        {
            $records = $records->select(
                'items.*',
                'cafes.name as building_name',
                'cafes.id as building_id',
                'ic.id as category_id',
                'ic.name as category_name'
            )
                ->orderBy('items.created_at', $columnSortOrder)
                ->get();
        }

        

        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
               "id" => $record->id,
               "name" => $record->name,
               "status" => $record->status,
               "building_id" => $record->building_id,
               "building_name" => $record->building_name,
               "category_id" => $record->category_id,
               "category_name" => $record->category_name,
           );
        }

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }

    public function show($item)
    {
        $library = $item->library()->first();
        $cafe = $item->cafe()->first();

        $item_category = $item->itemCategory()->first();
        $data = [
            "id"    =>  $item->id,
            "name"    =>  $item->name,
            "status"    =>  $item->status,
            "library_id"    =>  $library ? $library->id : null,
            "library_name"    =>  $library ? $library->name : null,
            "cafe_id"    =>  $cafe ? $cafe->id : null,
            "cafe_name"    =>  $cafe ? $cafe->name : null,
            'category_id'  =>  $item_category->id,
            'category_name'  =>  $item_category->name,
        ];

        return $data;
    }

    public function store($request)
    {
        $request = $request->validated();

        $item = new Item();
        $item->name = $request['name'];
        $item->item_category_id = $request['item_category_id'];

        $user = auth()->user();
        $item->library_id = $user->library_id;
        $item->cafe_id = $user->cafe_id;

        if(isset($request['remark']))
            $item->remark = $request['remark'];

        $item->save();


        return $item;
    }

    public function update($request, $item)
    {
        $request = $request->validated();

        if (isset($request['type'])) {
            if ($request['type'] === 'status') {
                $item->status = $item->status === 1 ? 0 : 1;
            }
            $item->save();
            return;
        }
        $item->name = $request['name'];
        $item->status = $request['status'];
        $item->item_category_id = $request['item_category_id'];

        if(isset($request['remark']))
            $item->remark = $request['remark'];
        else
            $item->remark = null;

        $item->save();

        return;
    }

}
?>