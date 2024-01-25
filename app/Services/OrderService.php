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
use App\Models\Payment;
use App\Models\FeesCategory;

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
        // dd($user);
        $cafe = Cafe::find($user->cafe_id);
        $service = new Service();
        // $records = Order::join('beverages','beverages.id','=','orders.beverage_id')
        //                 ->join('cafes','cafes.id','=','beverages.cafe_id')
        //                 ->where('cafes.id',$cafe->id)
        //                 ->join('tables','tables.id','=','orders.table_id')
        //             ->join('payments','payments.order_id','=','orders.id');
        // $records = $cafe->through('beverage')->has('order')->join('tables','tables.id','=','orders.table_id');
        // $orders = $cafe->beverage()->with('order')->get()->pluck('order')->collapse();
        // $records = $cafe->through('beverage')->has('order');
        $records = $cafe->order()->join('tables','tables.id','=','orders.table_id')
                    ->join('payments','payments.order_id','=','orders.id');
        // dd($records->get(), $record2->get());
        // dd($records->get());

        $records = $records->where(function ($query) {
            $query->where(function ($query) {
                $query->where('orders.status', 1)
                    ->whereDate('orders.created_at', now()->format('Y-m-d'));
            })
            ->orWhere(function ($query) {
                $query->where('orders.status', 0);
            });
        });
        
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
            'orders.*',
            'payments.receipt_no',
            'payments.unit_price',
            'payments.subtotal',
            'payments.sst_amount',
            'payments.service_charge_amount',
            'payments.total_price',
            'payments.receipt_no'
        )
            ->orderBy('orders.status', 'asc')
            ->orderBy('orders.created_at', $columnSortOrder)
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            if($record->status == 0)
            $status = "Pending";
            else
            $status = "Completed";
            $data_arr[] = array(
               "id" => $record->id,
               "order_no" => $record->order_no,
               "payment_status" => $record->payment_status,
               "status" => $status,
               "beverage_id" => $record->beverage_id,
               "beverage_name" => $record->beverage_name,
               "unit_price" => $record->unit_price,
               "quantity" => $record->quantity,
               "subtotal" => $record->subtotal,
               "sst_amount" => $record->sst_amount,
               "service_charge_amount" => $record->service_charge_amount,

               "total_price" => $record->total_price,
               "receipt_no" => $record->receipt_no,

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
        $beverage = Beverage::find($order->beverage_id);
        $payment = $order->payment()->first();

        if($order->status == 0)
            $status = "pending";
        else
            $status = "completed";
        $data = [
            "id" => $order->id,
               "order_no" => $order->order_no,
               "payment_status" => $order->payment_status,
               "status" => $status,
               "beverage_id" => $order->beverage_id,
               "beverage_name" => $order->beverage_name,
               "beverage_picture"   =>  $beverage->picture ? $service->getImage('beverage',$beverage->id) : null,
               "unit_price" => $payment->unit_price,
               "quantity" => $payment->quantity,
               "subtotal" => $payment->subtotal,
               "sst_amount" => $payment->sst_amount,
               "service_charge_amount" => $payment->service_charge_amount,
               "total_price" => $payment->total_price,
               "receipt_no" => $payment->receipt_no,

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
        $user = auth()->user();
        $order->user_id = $user->id;
        $order->table_id = $request['table_id'];
        $table = Table::find($request['table_id']);
        $order->table_no = $table->table_no;

        $order->quantity = $request['quantity'];
        $order->unit_price = $beverage->price;
        $order->total_price = $beverage->price * $request['quantity'];
        $order->payment_status = "success"; // haven't payment success
        $order->status = 0; // haven't settle order
        $order->save();

        $order->order_no = $order->generateOrderNo($beverage->cafe_id);
        $order->save();

        // payment success
        $payment = new Payment();
        $fees_category = FeesCategory::where('name','order')->first();
        $payment->fees_category_id = $fees_category->id;

        $payment->order_id = $order->id;
        $beverage = $order->beverage()->first();
        $cafe = $beverage->cafe()->first();
        $payment->cafe_id = $cafe->id;
        $payment->quantity = $order->quantity;
        $payment->unit_price = $order->unit_price;
        $subtotal = $order->unit_price * $order->quantity;
        $item_name = $beverage->name;
        $description = "Order Payment";
        
        // update order payment status
        $order->payment_status = "success";
        $order->save();

        $payment->subtotal = $subtotal;
        $sst =  0.06 * $subtotal;
        $service =  0.1 * $subtotal;
        
        $payment->sst_amount = $sst;
        $payment->service_charge_amount = $service;
        $payment->total_price = $subtotal + $sst + $service;

        $user = auth()->user();
        $payment->user_id = $user->id;
        $payment->item_name = $item_name;
        $payment->description = $description;

        $payment->save();

        $payment->receipt_no = $payment->generateReceiptNo($cafe->id, "cafe");

        $payment->save();

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
        
        return;
    }

    public function orderListing($request)
    {
        $cafe_id = $request['cafe_id'] ?? null;
        
        $user = auth()->user();
        $records = $user->order()->where('payment_status','success')
                        ->join('beverages','beverages.id','=','orders.beverage_id')
                        ->join('cafes','cafes.id','=','beverages.cafe_id');
    
        if($cafe_id != null)
        $records = $records->where('cafes.id',$cafe_id);
        // dd($request);
        $service = new Service();

        $totalRecords = $records->count();

        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'orders.*',
            'cafes.name as cafe_name'
        )
            ->orderBy('orders.status', 'asc')
            ->orderBy('orders.created_at', 'desc')

            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            if($record->status == 0)
            $status = "pending";
            else
            $status = "completed";
            $data_arr[] = array(
               "id" => $record->id,
               "status" => $status,
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

    public function detailSalesReport($request)
    {
        $search_arr = $request->input('search');
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $order_arr = $request->input('order');
        $cafe_id = $request->input('cafe_id');

        $columnSortOrder = isset($order_arr) ? $order_arr : 'desc';
        // $library_id = $request->input('library_id');
        
        $user = auth()->user();
        // dd($user);
        // $cafe = Cafe::find($user->cafe_id);
        $service = new Service();
        // $records = Order::join('beverages','beverages.id','=','orders.beverage_id')
        //                 ->join('cafes','cafes.id','=','beverages.cafe_id')
        //                 ->where('cafes.id',$cafe->id);
        // $records = $cafe->through('beverage')->has('order')->join('tables','tables.id','=','orders.table_id');
        // $orders = $cafe->beverage()->with('order')->get()->pluck('order')->collapse();
        // $records = $cafe->through('beverage')->has('order');
        $records = Order::join('tables','tables.id','=','orders.table_id')
                    ->join('payments','payments.order_id','=','orders.id')
                    ->join('users','users.id','=','orders.user_id')
                    ->join('beverages','beverages.id','=','orders.beverage_id')
                    ->join('cafes','cafes.id','=','beverages.cafe_id');
        
        if(auth()->user()->hasRole('staff'))
            $cafe = Cafe::find($user->cafe_id);
        else
        {
            if($cafe_id != null)
                $cafe = Cafe::find($cafe_id);
        }

        if(isset($cafe) && $cafe != null)
                $records = $records->where('cafes.id',$cafe->id);
        
        $totalRecords = $records->count();

        // if($searchValue != null && $searchValue != "")
        // {

        //     $records = $records->where(function ($query) use ($searchValue) {
        //         $query->orWhere('users.name', 'like', '%' . $searchValue . '%')
        //         ->orWhere('orders.order_no', 'like', '%' . $searchValue . '%')
        //         ->orWhere('payments.receipt_no', 'like', '%' . $searchValue . '%');
    
        //     });
        // }

        if ($request->input('startDate') != null && $request->input('endDate') != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request->input('startDate')));
            $endDate = date('Y-m-d', strtotime($request->input('endDate'))) . ' 23:59:59';

            $records = $records->whereBetween('orders.created_at', [$startDate, $endDate]);
        }
        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'orders.*',
            'users.name as user_name',
            'payments.receipt_no',
            'payments.unit_price',
            'payments.subtotal',
            'payments.sst_amount',
            'payments.service_charge_amount',
            'payments.total_price',
            'payments.receipt_no',
            'cafes.id as cafe_id',
            'cafes.name as cafe_name'
        )
            ->orderBy('orders.created_at', 'desc')
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            if($record->status == 0)
            $status = "Pending";
            else
            $status = "Completed";
            $data_arr[] = array(
               "id" => $record->id,
               "order_no" => $record->order_no,
               "cafe_id" => $record->cafe_id,
               "cafe_name" => $record->cafe_name,

               "user_name" => $record->user_name,

               "payment_status" => $record->payment_status,
               "status" => $status,
               "beverage_id" => $record->beverage_id,
               "beverage_name" => $record->beverage_name,
               "unit_price" => $record->unit_price,
               "quantity" => $record->quantity,
               "subtotal" => $record->subtotal,
               "sst_amount" => $record->sst_amount,
               "service_charge_amount" => $record->service_charge_amount,

               "total_price" => $record->total_price,
               "receipt_no" => $record->receipt_no,

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

    public function dailySalesReport($request)
    {
        $search_arr = $request->input('search');
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $order_arr = $request->input('order');
        $cafe_id = $request->input('cafe_id');

        $columnSortOrder = isset($order_arr) ? $order_arr : 'desc';
        // $library_id = $request->input('library_id');
        
        $user = auth()->user();
        // dd($user);
        $service = new Service();

        $records = Order::join('beverages','beverages.id','=','orders.beverage_id')
                        ->join('cafes','cafes.id','=','beverages.cafe_id')
                        ->join('payments','payments.order_id','=','orders.id');

        $total_records = Order::join('beverages','beverages.id','=','orders.beverage_id')
        ->join('cafes','cafes.id','=','beverages.cafe_id')
        ->join('payments','payments.order_id','=','orders.id')
        ->selectRaw('COUNT(orders.id) AS total_transaction, 
        SUM(payments.subtotal) AS subtotal, 
        SUM(payments.sst_amount) AS sst, 
        SUM(payments.service_charge_amount) AS service,
        SUM(payments.total_price) AS total_price')->first();

        if(auth()->user()->hasRole('staff'))
            $cafe = Cafe::find($user->cafe_id);
        else
        {
            if($cafe_id != null)
                $cafe = Cafe::find($cafe_id);
        }

        if(isset($cafe) && $cafe != null)
                $records = $records->where('cafes.id',$cafe->id);
       

        $totalRecords = $records->count();


        if ($request->input('startDate') != null && $request->input('endDate') != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request->input('startDate')));
            $endDate = date('Y-m-d', strtotime($request->input('endDate'))) . ' 23:59:59';

            $records = $records->whereBetween('date', [$startDate, $endDate]);
        }
        $totalRecordswithFilter = $records->count();

        $records = $records->selectRaw(
            'DATE(orders.created_at) AS date,
            COUNT(orders.id) AS total_transaction, 
            SUM(payments.subtotal) AS subtotal, 
            SUM(payments.sst_amount) AS sst, 
            SUM(payments.service_charge_amount) AS service,
            SUM(payments.total_price) AS total_price,
            cafes.name as cafe_name,
            cafes.id as cafe_id '

        )
            ->groupBy('date','cafe_id','cafe_name')
            ->orderBy('date', 'desc')
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
          
            $data_arr[] = array(
               "date" => date('Y-m-d',strtotime($record->date)),
                "cafe_id"    => $record->cafe_id,
                "cafe_name" =>  $record->cafe_name,
               "total_transaction" => $record->total_transaction,
               "subtotal" => $record->subtotal,
               "sst" => $record->sst,
               "service" => $record->service,
               "total_price" => $record->total_price,

           );
        }

        $result['total_transaction'] =  $total_records->total_transaction;
        $result['total_subtotal'] =  $total_records->subtotal;
        $result['total_grand_total'] =  $total_records->total_price;
        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }


}
?>