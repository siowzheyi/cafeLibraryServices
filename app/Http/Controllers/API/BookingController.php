<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\BookingService;

use App\Http\Requests\BookingRequest;
use App\Models\User;
use App\Models\Booking;

use Auth;
use App;
use Validator;

class BookingController extends BaseController
{
    public function __construct(BookingService $booking_service)
    {
        $this->services = $booking_service;
    }

    // This api is for admin user to create Booking
    public function store(BookingRequest $request)
    {
        $result = $this->services->store($request);
        
        if($result != null)
            return $this->sendResponse("", "Booking has been successfully created. ");
        else
            return $this->sendCustomValidationError(['Error'=>'Failed to create data. ']);

    }


    // This api is for admin user to view certain Booking
    public function show(Booking $booking)
    {
        $result = $this->services->show($booking);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to view list of Booking
    public function index(Request $request)
    {
        $result = $this->services->index($request);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to update certain Booking
    public function update(BookingRequest $request, Booking $booking)
    {
        $result = $this->services->update($request, $booking);

        return $this->sendResponse($result['data'], $result['message']);     
    }

    // This api is for admin user to view list of Booking listing
    public function bookingListing(Request $request)
    {
        $input = $request->all();

        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'library_id' => array('nullable','exists:libraries,id'),
        ]);

        if ($validator->fails()) {
            return $this->sendCustomValidationError($validator->errors());
        }

        $result = $this->services->bookingListing($input);

        return $this->sendResponse($result, "Data has been successfully retrieved. ");
    }


}
