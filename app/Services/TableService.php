<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Config;
use DateTime;
use App\Models\User;
use App\Models\Roles;
use App\Models\Table;
use App\Models\Library;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class TableService
{
   
    public function index($request)
    {
        $search_arr = $request->input('search');
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $order_arr = $request->input('order');
        $columnSortOrder = isset($order_arr) ? $order_arr : 'desc';
        $library_id = $request->input('library_id');

        $user = auth()->user();
        if($user->hasRole('staff'))
        {
            $library = Library::find($user->library_id);
        }
        else
        {
            if(!isset($library_id) || $library_id == null)
                return [];
            else
                $library = Library::find($library_id);
        }
        $records = $library->table()->join('libraries','libraries.id','=','tables.library_id');
        // dd($request);

        $totalRecords = $records->count();

        $records = $records->where(function ($query) use ($searchValue) {
            $query->orWhere('tables.table_no', 'like', '%' . $searchValue . '%');

        });

        if ($request->input('startDate') != null && $request->input('endDate') != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request->input('startDate')));
            $endDate = date('Y-m-d', strtotime($request->input('endDate'))) . ' 23:59:59';

            $records = $records->whereBetween('tables.created_at', [$startDate, $endDate]);
        }
        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'tables.*',
            'libraries.name as library_name',
            'libraries.address as library_address'
        )
            ->orderBy('tables.created_at', $columnSortOrder)
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
               "id" => $record->id,
               "table_no" => $record->table_no,

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

    public function show($table)
    {
        $library = $table->library()->first();
        $data = [
            "id"    =>  $table->id,
            "table_no"    =>  $table->table_no,
            "status"    =>  $table->status,

            "library_id"    =>  $table->library_id,
            "library_name"    =>  $library->name,
            "library_address"    =>  $library->address,

        ];

        return $data;
    }

    public function store($request)
    {
        $raw_request = $request;

        $request = $request->validated();

        $table = new Table();
        $table->table_no = $request['table_no'];


        $user = auth()->user();
        if($user->hasRole('staff'))
        {
            $table->library_id = $user->library_id;
        }
        else
        {
            $table->library_id = $request['library_id'];

        }
        $table->save();


        return $table;
    }

    public function update($request, $table)
    {
        $raw_request = $request;

        $request = $request->validated();

        if (isset($request['type'])) {
            if ($request['type'] === 'status') {
                $table->status = $table->status === 1 ? 0 : 1;
            }
            $table->save();
            return;
        }
        $table->table_no = $request['table_no'];
        $table->status = $request['status'];

        $table->save();

        return;
    }

    public function tableListing($request)
    {
        
        $search_arr = $request['search'] ?? null;
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $records = Table::where('library_id',$request['library_id'])
                            ->where('status',1);
        // dd($records->get());

        $totalRecords = $records->count();

        if($searchValue != null)
        {
            $records = $records->where(function ($query) use ($searchValue) {
                $query->orWhere('tables.table_no', 'like', '%' . $searchValue . '%');
            });
        }

        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'tables.*'
        )
        ->orderBy('tables.created_at', 'asc')
        ->get();

        // dd($records);

        $data_arr = array();
        foreach ($records as $key => $record) {
            
            $data_arr[] = array(
               "id" => $record->id,
               "table_no" => $record->table_no,
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