<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\OrderService;

use App\Http\Requests\OrderRequest;
use App\Models\User;
use App\Models\Order;

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
            return $this->sendResponse("", "Order has been successfully created. ");
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
        $result = $this->services->index($request);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to update certain Order
    public function update(OrderRequest $request, Order $order)
    {
        $result = $this->services->update($request, $order);

        return $this->sendResponse("", "Order has been successfully updated. ");      
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


}
