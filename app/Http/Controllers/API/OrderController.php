<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\OrderService;
use DB;
use Yajra\DataTables\DataTables;
use App\Services\Service;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

use App\Http\Requests\OrderRequest;
use App\Models\User;
use App\Models\Order;
use App\Models\Beverage;
use App\Models\Cafe;

use Auth;
use App;
use Validator;

class OrderController extends BaseController
{
    public function __construct(OrderService $order_service)
    {
        $this->services = $order_service;
    }

    // This api is for admin user to create Order
    public function store(OrderRequest $request)
    {
        $result = $this->services->store($request);
        
        if($result != null)
            return $this->sendResponse($result, "Order has been successfully created. ");
        else
            return $this->sendCustomValidationError(['Error'=>'Failed to create data. ']);

    }


    // This api is for admin user to view certain Order
    public function show(Order $order)
    {
        $result = $this->services->show($order);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to view list of Order
    public function index(Request $request)
    {
        // $result = $this->services->index($request);

        // return $this->sendResponse($result, "Data successfully retrieved. "); 
        return view('cafe.order.index');
    }

    // This api is for admin user to update certain Order
    public function update(OrderRequest $request, Order $order)
    {
        $result = $this->services->update($request, $order);

        return $this->sendResponse("", "Order has been successfully updated. ");      
    }

    public function edit(Order $order)
    {
        $service = new Service();
        $user = $order->user()->first();
        $beverage = Beverage::find($order->beverage_id);
        $payment = $order->payment()->first();

        if($order->status == 1)
            $status = "Completed";
        else
            $status = "Pending";

        
        $data = [
            "id" => $order->id,
               "order_no" => $order->order_no,
               "payment_status" => $order->payment_status,
               "status" => $status,
               "beverage_id" => $order->beverage_id,
               "beverage_name" => $order->beverage_name,
               "beverage_picture"   =>  $beverage->picture ? $service->getImage('beverage',$beverage->id) : null,
               "unit_price" => $payment->unit_price ?? 0,
               "quantity" => $payment->quantity ?? 0,
               "subtotal" => $payment->subtotal ?? 0,
               "sst_amount" => $payment->sst_amount ?? 0,
               "service_charge_amount" => $payment->service_charge_amount ?? 0,
               "total_price" => $payment->total_price ?? 0,
               "receipt_no" => $payment->receipt_no ?? null,

               "table_no" => $order->table_no,
               "table_id" => $order->table_id,
               "user_id" => $order->user_id,
               "user_name" => $user->name,
               "user_phone_no" => $user->phone_no,

               "created_at" => $order->created_at != null ? date('Y-m-d H:i:s',strtotime($order->created_at)) : null,

        ];

        return $data;
    }

    // This api is for admin user to view list of Order listing
    public function orderListing(Request $request)
    {
        $input = $request->all();

        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'cafe_id' => array('nullable','exists:cafes,id'),
        ]);

        if ($validator->fails()) {
            return $this->sendCustomValidationError($validator->errors());
        }

        $result = $this->services->orderListing($input);

        return $this->sendResponse($result, "Data has been successfully retrieved. ");
    }

    // This api is for admin user to view transaction report
    public function detailSalesReport(Request $request)
    {
        $result = $this->services->detailSalesReport($request);

        $table = Datatables::of($result['aaData']);

        $table->addColumn('action', function ($row) {
            $token = csrf_token();

            $btn ='<button id="'.$row['id'].'" data_id="' . $row['id'] . '" data-token="' . $token . '" class="btn btn-primary m-1 showData" data-bs-toggle="modal" data-bs-target="#orderModal">View</button>';
            return $btn;
        });

        $table->rawColumns(['status','action']);
        return $table->make(true);
    }

    // This api is for admin user to view daily transaction report
    public function dailySalesReport(Request $request)
    {
        $result = $this->services->dailySalesReport($request);

        // return $this->sendResponse($result, "Data successfully retrieved. "); 
        return $result;
    }

    public function dailySalesReportIndex()
    {
        return view('cafe.report.dailySalesReport');
    }

    public function detailSalesReportIndex()
    {
        return view('cafe.report.detailSalesReport');
    }

    public function cafeDailySalesReportIndex()
    {
        return view('dashboard.report.dailySalesReport');
    }

    public function cafeDetailSalesReportIndex()
    {
        return view('dashboard.report.detailSalesReport');
    }

    public function getOrderDatatable(Request $request)
    {
        if (request()->ajax()) {
            $type = $request->type;

            $user = auth()->user();
            
            $data = Order::join('beverages','beverages.id','=','orders.beverage_id')
            ->select('orders.*','beverages.cafe_id')
            ->orderBy('orders.created_at','asc');

            $data = $data->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('orders.status', 1)
                        ->whereDate('orders.created_at', now()->format('Y-m-d'));
                })
                ->orWhere(function ($query) {
                    $query->where('orders.status', 0);
                });
            });

            if($user->hasRole('admin'))
                $data = $data->where('beverages.cafe_id',$request->cafe_id);
            else
                $data = $data->where('beverages.cafe_id',$user->cafe_id);

            $table = Datatables::of($data);


            $table->addColumn('status', function ($row) {
                $checked = $row->status == 1 ? 'checked' : '';
                $status = $row->status == 1 ? 'Completed' : 'Pending';
            
                $btn = '<div class="form-check form-switch">';
                $btn .= '<input class="form-check-input data-status" type="checkbox" data-id="'.$row->id.'" '.$checked.'>';
                $btn .= '<label class="form-check-label">'.$status.'</label>';
                $btn .= '</div>';
            
                return $btn;
            });
            

            $table->addColumn('action', function ($row) {
                $token = csrf_token();

                $btn ='<button id="'.$row->id.'" data_id="' . $row->id . '" data-token="' . $token . '" class="btn btn-primary m-1 showData" data-bs-toggle="modal" data-bs-target="#orderModal">View</button>';
                return $btn;
            });

            $table->rawColumns(['status','action']);
            return $table->make(true);
        }
    }

    //orderController
    //for graph
    public function getDailySales()
    {
        // dd( now()->toDateString());
        // Fetch daily sales data from the database
        $dailySalesData = Order::selectRaw('DATE(created_at) as date, SUM(total_price) as total_price')
        ->groupBy('date');

        if(auth()->user()->cafe_id != null)
        {
            $cafe = Cafe::find(auth()->user()->cafe_id);
            $dailySalesData = Order::join('beverages','beverages.id','=','orders.beverage_id')
                            ->join('cafes','cafes.id','=','beverages.cafe_id')
                            ->where('cafes.id',$cafe->id)
                            ->selectRaw('DATE(orders.created_at) as date, SUM(orders.total_price) as total_price')
                            ->groupBy('date');
        }


            $dailySalesData = $dailySalesData->get();
        // dd($dailySalesData[0]);
        return response()->json($dailySalesData);
    }

    public function getMonthlySales()
    {
        // Fetch monthly sales data from the database
        $monthlySalesData = Order::selectRaw('MONTH(created_at) as month, SUM(total_price) as total_sales') 
            ->whereYear('created_at', now()->year) // Adjust 'date_column' to your actual date column
            ->groupByRaw('MONTH(created_at)')
            ->get();

        if(auth()->user()->cafe_id != null)
        {
            $cafe = Cafe::find(auth()->user()->cafe_id);
            $monthlySalesData = Order::selectRaw('MONTH(orders.created_at) as month, SUM(orders.total_price) as total_sales') 
            ->join('beverages','beverages.id','=','orders.beverage_id')
            ->where('beverages.cafe_id',$cafe->id)
            ->whereYear('orders.created_at', now()->year) // Adjust 'date_column' to your actual date column
            ->groupByRaw('MONTH(orders.created_at)')
            ->get();
                            
        }
        // dd($monthlySalesData);
        return response()->json($monthlySalesData);
    }


}
