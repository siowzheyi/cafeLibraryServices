<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Config;
use DateTime;
use App\Models\User;
use App\Models\Roles;
use App\Models\Announcement;
use App\Models\Library;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class AnnouncementService
{
   
    public function index($request)
    {
        $search_arr = $request->input('search');
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $order_arr = $request->input('order');
        $columnSortOrder = isset($order_arr) ? $order_arr : 'desc';

        $user = auth()->user();
        $library = Library::find($user->library_id);
        $records = $library->announcement();
        // dd($request);

        $totalRecords = $records->count();

        $records = $records->where(function ($query) use ($searchValue) {
            $query->orWhere('announcements.title', 'like', '%' . $searchValue . '%')
            ->orWhere('announcements.content', 'like', '%' . $searchValue . '%');

        });

        if ($request->input('startDate') != null && $request->input('endDate') != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request->input('startDate')));
            $endDate = date('Y-m-d', strtotime($request->input('endDate'))) . ' 23:59:59';

            $records = $records->whereBetween('announcements.created_at', [$startDate, $endDate]);
        }
        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'announcements.*',
            'libraries.name as library_name',
            'libraries.address as library_address'
        )
            ->orderBy('announcements.created_at', $columnSortOrder)
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
               "id" => $record->id,
               "title" => $record->title,
               "content" => $record->content,
               "expired_at" => date('Y-m-d H:i:s',strtotime($record->expired_at)),

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

    public function show($announcement)
    {
        $library = $announcement->library()->first();
        $data = [
            "id"    =>  $announcement->id,
            "title"    =>  $announcement->title,
            "content"    =>  $announcement->content,
            "status"    =>  $announcement->status,
            "expired_at" => date('Y-m-d H:i:s',strtotime($announcement->expired_at)),

            "library_id"    =>  $announcement->library_id,
            "library_name"    =>  $library->name,
            "library_address"    =>  $library->address,

        ];

        return $data;
    }

    public function store($request)
    {
        $request = $request->validated();

        $announcement = new Announcement();
        $announcement->title = $request['title'];
        $announcement->content = $request['content'];
        if(isset($request['expired_at']))
        $announcement->expired_at = $request['expired_at'];


        $user = auth()->user();
        $announcement->library_id = $user->library_id;
        $announcement->save();


        return $announcement;
    }

    public function update($request, $announcement)
    {
        $request = $request->validated();

        if (isset($request['type'])) {
            if ($request['type'] === 'status') {
                $announcement->status = $announcement->status === 1 ? 0 : 1;
            }
            $announcement->save();
            return;
        }
        $announcement->title = $request['title'];
        $announcement->content = $request['content'];
        $announcement->status = $request['status'];

        if(isset($request['expired_at']))
        $announcement->expired_at = $request['expired_at'];
        else
        $announce->expired_at = null;
        $announcement->save();

        return;
    }

    public function announcementListing($request)
    {
        $search_arr = $request['search'] ?? null;
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $records = Announcement::where('library_id',$request['library_id'])
                        ->where('status',1);
        // dd($request);

        $totalRecords = $records->count();

        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'announcements.*'
        )
            ->orderBy('announcements.created_at', 'asc')
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
               "id" => $record->id,
               "title" => $record->title,
                "content" => $record->content,
               
           );
        }

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }

}
?>