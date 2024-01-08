<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\PaymentService;

use App\Http\Requests\PaymentRequest;
use App\Models\User;
use App\Models\Payment;

use Auth;
use App;
use Validator;

class PaymentController extends BaseController
{
    public function __construct(PaymentService $payment_service)
    {
        $this->services = $payment_service;
    }

    // This api is for user to create Payment
    public function store(PaymentRequest $request)
    {
        $result = $this->services->store($request);
        
        if($result != null)
            return $this->sendResponse("", "Payment has been successfully created. ");
        else
            return $this->sendCustomValidationError(['Error'=>'Failed to create data. ']);

    }


    // This api is for user to view certain Payment
    public function show(Payment $payment)
    {
        $result = $this->services->show($payment);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for user to view list of Payment
    public function index(Request $request)
    {
        $result = $this->services->index($request);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for user to update certain Payment -> callback for payment gateway
    public function update(PaymentRequest $request, Payment $payment)
    {
        $result = $this->services->update($request, $payment);

        return $this->sendResponse("", "Payment has been successfully updated. ");      
    }

    // This api is for user to view list of Payment listing
    public function paymentListing(Request $request)
    {
        $input = $request->all();

        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'library_id' => array('nullable','exists:libraries,id'),
        ]);

        if ($validator->fails()) {
            return $this->sendCustomValidationError($validator->errors());
        }

        $result = $this->services->paymentListing($input);

        return $this->sendResponse($result, "Data has been successfully retrieved. ");
    }


}
