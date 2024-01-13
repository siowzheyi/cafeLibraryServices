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

use App\Models\Media;
use Illuminate\Http\UploadedFile;
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
        $records = $library->equipment()->join('libraries','libraries.id','=','equipments.library_id');
        // dd($request);
        $service = new Service();

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
               "picture"    => $record->picture ? $service->getImage('equipment',$record->id) : null,      

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
        $service = new Service();
        $data = [
            "id" => $equipment->id,
               "name" => $equipment->name,
               "remark" => $equipment->remark,
               "availability" => $equipment->availability,
               "status" => $equipment->status,
               "picture"    => $equipment->picture ? $service->getImage('equipment',$equipment->id) : null,      

        ];

        return $data;
    }

    public function store($request, $input)
    {
        $raw_request = $request;

        $category_id = ItemCategory::where('name','equipment')->first();

        $equipment = new equipment();
        $equipment->name = $input['name'];
        $equipment->availability = 1;

        if(isset($input['remark']))
        $equipment->remark = $input['remark'];
        $equipment->item_category_id = $category_id->id;


        $user = auth()->user();
        if($user->hasRole('staff'))
        {
            $equipment->library_id = $user->library_id;
        }
        else
        {
            $equipment->library_id = $input['library_id'];

        }
        $equipment->save();

        if ($raw_request->hasfile('picture')) {
            $service = new Service();
            $service->storeImage('equipment',$raw_request->file('picture'),$equipment->id);
        }
        return $equipment;
    }

    public function update($request, $equipment, $input)
    {
        $raw_request = $request;

        if (isset($input['type'])) {
            if ($input['type'] === 'status') {
                $equipment->status = $equipment->status === 1 ? 0 : 1;
            }
            $equipment->save();
            return;
        }
        $equipment->name = $input['name'];
        // $equipment->status = $input['status'];

        if(isset($input['remark']))
        $equipment->remark = $input['remark'];
        else
        $equipment->remark = null;

        $equipment->save();
        //update image of equipments
        if($raw_request->hasfile('picture')) {
            $file = $raw_request->file('picture');
            $media = Media::where('model_type', 'App\Models\Equipment')->where('name', Config::get('main.equipment_image_path'))->where('model_id', $equipment->id)->first();
            if($media == null) {
                $service = new Service();
                $service->storeImage('equipment', $file, $equipment->id);
                $equipment->save();
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
                // $service->storeImage('main',$file, $input['display_name']);
                $mime_type = $file->getClientOriginalExtension();
                $storage_path = $media->name;

                $path = Storage::disk('public')->putFileAs($storage_path, $file, $uploaded_file_name, ['visibility' => 'public']);
                // dd($storage_path, $file, $uploaded_file_name,$path);

                $media->file_name = $uploaded_file_name;
                $media->mime_type = $mime_type;
                // $media->display_name = $request['display_name'];
                $equipment->picture = $uploaded_file_name;
                $media->save();
                $equipment->save();
            }

        }

        return;
    }

    public function equipmentListing($request)
    {
        
        $search_arr = $request['search'] ?? null;
        $searchValue = isset($search_arr) ? $search_arr : '';
        $service = new Service();
                        
        
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
                "picture"    => $record->picture ? $service->getImage('equipment',$record->id) : null,      

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