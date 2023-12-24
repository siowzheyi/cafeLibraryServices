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

use App\Models\Media;
use Illuminate\Http\UploadedFile;
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
        $service = new Service();
        $totalRecords = $records->count();

        if($searchValue != null)
        {
            $records = $records->where(function ($query) use ($searchValue) {
                $query->orWhere('beverages.name', 'like', '%' . $searchValue . '%');
            });
        }
        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'beverages.category'
        )
            // ->orderBy('beverages.created_at', 'asc')
            ->groupBy('beverages.category')

            ->get();

        $data_arr = array();
        
       foreach ($records as $key => $record) {
            
            $drink_related = Beverage::where('category',$record->category)
                            ->get();
            $drink_arr = array();

            foreach ($drink_related as $key => $drink) {
                $drink_arr[] = array(
                    "id" => $drink->id,
                    "name" => $drink->name,
                    "remark" => $drink->remark,
                    "category" => $drink->category,
        
                    "price" => $drink->price,
                    "picture"    => $drink->picture ? $service->getImage('beverage',$drink->id) : null,      
        
                    "status" => $drink->status,
                );
            }

            
            $data_arr[] = array(
                "category" => $record->category,
                "beverage" =>  $drink_arr
                

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
        $cafe_id = $request->input('cafe_id');

        $user = auth()->user();
        if($user->hasRole('staff'))
        {
            $cafe = Cafe::find($user->cafe_id);
        }
        else
        {
            if(!isset($cafe_id) || $cafe_id == null)
            return [];
            else
            $cafe = Cafe::find($cafe_id);
        }
     
        $records = $cafe->beverage();
        $service = new Service();
    
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

        if($user->library_id != null || $user->cafe_id != null || $user->hasAnyRole(['admin','superadmin']))
        {
            $records = $records->select(
                'beverages.*',
                
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
               "picture"    => $record->picture ? $service->getImage('beverage',$record->id) : null,      

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
        $service = new Service();
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
            "picture"    => $beverage->picture ? $service->getImage('beverage',$beverage->id) : null,      

        ];

        return $data;
    }

    public function store($request)
    {
        $raw_request = $request;

        $request = $request->validated();

        $item_category = ItemCategory::where('name','beverage')->first();

        $beverage = new Beverage();
        $beverage->name = $request['name'];
        $beverage->item_category_id = $item_category->id;
        $beverage->price = $request['price'];
        $beverage->category = $request['category'];

        $user = auth()->user();
        if($user->hasRole('staff'))
        {
            $beverage->cafe_id = $user->cafe_id;
        }
        else
        {
            $beverage->cafe_id = $request['cafe_id'];

        }

        if(isset($request['remark']))
            $beverage->remark = $request['remark'];

        

        $beverage->save();

        if ($raw_request->hasfile('picture')) {
            $service = new Service();
            $service->storeImage('beverage',$raw_request->file('picture'),$beverage->id);
        }


        return $beverage;
    }

    public function update($request, $beverage)
    {
        $raw_request = $request;

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
        $beverage->category = $request['category'];

        if(isset($request['remark']))
            $beverage->remark = $request['remark'];
        else
            $beverage->remark = null;

        $beverage->save();

          //update image of beverages
          if($raw_request->hasfile('picture')) {
            $file = $raw_request->file('picture');
            $media = Media::where('model_type', 'App\Models\Beverage')->where('name', Config::get('main.beverage_image_path'))->where('model_id', $beverage->id)->first();
            if($media == null) {
                $service = new Service();
                $service->storeImage('beverage', $file, $beverage->id);
                $beverage->save();
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
                $beverage->picture = $uploaded_file_name;
                $media->save();
                $beverage->save();
            }

        }

        return;
    }

   
}
?>