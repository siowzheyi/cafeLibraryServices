<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Config;
use DateTime;
use App\Models\User;
use App\Models\Roles;
use App\Models\Order;
use App\Models\Cafe;
use App\Models\Media;
use Illuminate\Http\UploadedFile;
use App\Models\Table;
use App\Models\Library;
use App\Models\Beverage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class OrderService
{
   
    public function index($request)
    {
        $search_arr = $request->input('search');
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $order_arr = $request->input('order');
        $columnSortOrder = isset($order_arr) ? $order_arr : 'desc';
        // $library_id = $request->input('library_id');
        
        $user = auth()->user();
        $cafe = Cafe::find($user->cafe_id);
        $service = new Service();
        $records = $cafe->order()->join('tables','tables.id','=','orders.table_id')
                        ->join('beverages','beverages.id','=','orders.beverage_id');

        $totalRecords = $records->count();

        // $records = $records->where(function ($query) use ($searchValue) {
        //     $query->orWhere('orders.title', 'like', '%' . $searchValue . '%')
        //     ->orWhere('orders.content', 'like', '%' . $searchValue . '%');

        // });

        if ($request->input('startDate') != null && $request->input('endDate') != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request->input('startDate')));
            $endDate = date('Y-m-d', strtotime($request->input('endDate'))) . ' 23:59:59';

            $records = $records->whereBetween('orders.created_at', [$startDate, $endDate]);
        }
        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'orders.*'
        )
            ->orderBy('orders.created_at', $columnSortOrder)
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
               "id" => $record->id,
               "order_no" => $record->order_no,
               "payment_status" => $record->payment_status,
               "status" => $record->status,
               "quantity" => $record->quantity,
               "beverage_id" => $record->beverage_id,
               "beverage_name" => $record->beverage_name,
               "unit_price" => $record->unit_price,
               "total_price" => $record->total_price,
               "table_no" => $record->table_no,
               "table_id" => $record->table_id,

               "created_at" => $record->created_at != null ? date('Y-m-d H:i:s',strtotime($record->created_at)) : null,

           );
        }

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }

    public function show($order)
    {
        $service = new Service();
        $user = $order->user()->first();
        $data = [
            "id" => $order->id,
               "order_no" => $order->order_no,
               "payment_status" => $order->payment_status,
               "status" => $order->status,
               "quantity" => $order->quantity,
               "beverage_id" => $order->beverage_id,
               "beverage_name" => $order->beverage_name,
               "unit_price" => $order->unit_price,
               "total_price" => $order->total_price,
               "table_no" => $order->table_no,
               "table_id" => $order->table_id,
               "user_id" => $order->user_id,
               "user_name" => $user->name,
               "user_phone_no" => $user->phone_no,

               "created_at" => $order->created_at != null ? date('Y-m-d H:i:s',strtotime($order->created_at)) : null,

        ];

        return $data;
    }

    public function store($request)
    {
        $raw_request = $request;
        $request = $request->validated();
        // dd($request);

        $order = new Order();
        $order->beverage_id = $request['beverage_id'];
        $beverage = Beverage::find($request['beverage_id']);
        $order->beverage_name = $beverage->name;
        $order->order_no = Order::generateOrderNo($beverage->cafe_id);
        $user = auth()->user();
        $order->user_id = $user->id;
        $order->table_id = $request['table_id'];
        $table = Table::find($request['table_id']);
        $order->table_no = $table->table_no;

        $order->quantity = $request['quantity'];
        $order->unit_price = $beverage->price;
        $order->total_price = $beverage->price * $request['quantity'];
        $order->payment_status = "pending"; // haven't payment success
        $order->status = 0; // haven't settle order
        $order->save();


        return $order;
    }

    public function update($request, $order)
    {
        $raw_request = $request;
        $request = $request->validated();

        if (isset($request['type'])) {
            if ($request['type'] === 'status') {
                $order->status = $order->status === 1 ? 0 : 1;
            }
            $order->save();
            return;
        }
        $announcement->title = $request['title'];
        $announcement->content = $request['content'];
        $announcement->status = $request['status'];

        if(isset($request['expired_at']))
        $announcement->expired_at = $request['expired_at'];
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

    public function orderListing($request)
    {
        $search_arr = $request['search'] ?? null;
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $user = $request->user();
        $records = $user->order()->where('payment_status','success')->join('beverages','beverages.id','=','orders.beverage_id')
                        ->join('cafes','cafes.id','=','beverages.cafe_id');
        // dd($request);
        $service = new Service();

        $totalRecords = $records->count();

        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'orders.*',
            'cafes.name as cafe_name'
        )
            ->orderBy('orders.status', 'asc')
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
               "id" => $record->id,
               "status" => $record->status,
                "order_no" => $record->order_no,
                "table_no" => $record->table_no,
                "cafe_name" => $record->cafe_name,

                "beverage_name" => $record->beverage_name,
                "quantity" => $record->quantity,
                "total_price" => $record->total_price,
                "created_at" => $record->created_at != null ? date('Y-m-d H:i:s',strtotime($record->created_at)) : null,


           );
        }

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }

}
?>