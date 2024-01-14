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
use App\Models\Media;
use Illuminate\Http\UploadedFile;

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
        $service = new Service();
        $records = $library->announcement();

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
        )
            ->orderBy('announcements.created_at', $columnSortOrder)
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
               "id" => $record->id,
               "title" => $record->title,
               "content" => $record->content,
               "picture"    => $record->picture ? $service->getImage('announcement',$record->id) : null,      
               "expired_at" => $record->expired_at != null ? date('Y-m-d H:i:s',strtotime($record->expired_at)) : null,
               "status" => $record->status,

           );
        }

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }

    public function show($announcement)
    {
        $service = new Service();
        $library = $announcement->library()->first();
        $data = [
            "id"    =>  $announcement->id,
            "title"    =>  $announcement->title,
            "content"    =>  $announcement->content,
            "picture"    => $announcement->picture ? $service->getImage('announcement',$announcement->id) : null,      

            "status"    =>  $announcement->status,
            "expired_at" => $announcement->expired_at != null ? date('Y-m-d H:i:s',strtotime($announcement->expired_at)) : null,

            "library_id"    =>  $announcement->library_id,
            "library_name"    =>  $library->name,
            "library_address"    =>  $library->address,

        ];

        return $data;
    }

    public function store($request, $input)
    {
        $raw_request = $request;
        // $request = $request->validated();
        // dd($request);

        $announcement = new Announcement();
        $announcement->title = $input['title'];
        $announcement->content = $input['content'];
        if(isset($input['expired_at']))
        $announcement->expired_at = $input['expired_at'];


        $user = auth()->user();
        if($user->hasRole('staff'))
        {
            $announcement->library_id = $user->library_id;
        }
        else
        {
            $announcement->library_id = $input['library_id'];

        }
        $announcement->save();

        if ($raw_request->hasfile('picture')) {
            $service = new Service();
            $service->storeImage('announcement',$raw_request->file('picture'),$announcement->id);
        }


        return $announcement;
    }

    public function update($request, $announcement, $input)
    {
        $raw_request = $request;

        if (isset($input['type'])) {
            if ($input['type'] === 'status') {
                $announcement->status = $announcement->status === 1 ? 0 : 1;
            }
            $announcement->save();
            return;
        }
        $announcement->title = $input['title'];
        $announcement->content = $input['content'];
        $announcement->status = $input['status'];

        if(isset($input['expired_at']))
        $announcement->expired_at = $input['expired_at'];
        else
        $announcement->expired_at = null;
        $announcement->save();

         //update image of announcements
         if($raw_request->hasfile('picture')) {
            $file = $raw_request->file('picture');
            $media = Media::where('model_type', 'App\Models\Announcement')->where('name', Config::get('main.announcement_image_path'))->where('model_id', $announcement->id)->first();
            if($media == null) {
                $service = new Service();
                $service->storeImage('announcement', $file, $announcement->id);
                $announcement->save();
                return;
            }
            $previous_file = Storage::disk('public')->get($media->name . $media->file_name);
            // $previous_file = $service->getImage('main',$media->id);


            // Create a temporary file in the server's tmp directory
            $tmpFilePath = tempnam(sys_get_temp_dir(), 'uploaded_file');
            $tmpFile = new UploadedFile($tmpFilePath, $media->file_name, null, null, true);

            // Write the file contents to the temporary file
            file_put_contents($tmpFilePath, $previous_file);

            $previous_file_name = preg_replace('/^[0-9]+_/', '', $tmpFile->getClientOriginalName());

            $uploaded_file_name = $file->getClientOriginalName();
            $uploaded_file_size = $file->getSize();
            // dd($tmpFile->getSize(),$previous_file_size, $file,$previous_file_size , $uploaded_file_size);
            //compare
            if($previous_file_name != $uploaded_file_name || $tmpFile->getSize() != $uploaded_file_size) {
                // $service->storeImage('main',$file, $request['display_name']);
                $mime_type = $file->getClientOriginalExtension();
                $storage_path = $media->name;

                $path = Storage::disk('public')->putFileAs($storage_path, $file, $uploaded_file_name, ['visibility' => 'public']);
                // dd($storage_path, $file, $uploaded_file_name,$path);

                $media->file_name = $uploaded_file_name;
                $media->mime_type = $mime_type;
                // $media->display_name = $request['display_name'];
                $announcement->picture = $uploaded_file_name;
                $media->save();
                $announcement->save();
            }

        }

        return;
    }

    public function announcementListing($request)
    {
        $search_arr = $request['search'] ?? null;
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $records = Announcement::where('library_id',$request['library_id'])
                        ->where('status',1);
        // dd($request);
        $service = new Service();

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
                "picture"    => $record->picture ? $service->getImage('announcement',$record->id) : null,      

           );
        }

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }

}
?>