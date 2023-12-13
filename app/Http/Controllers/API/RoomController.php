<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\RoomService;

use App\Http\Requests\RoomRequest;
use App\Models\User;
use App\Models\Room;

use Auth;
use App;
use Validator;

class RoomController extends BaseController
{
    public function __construct(RoomService $room_service)
    {
        $this->services = $room_service;
    }

    // This api is for admin user to create Room
    public function store(RoomRequest $request)
    {
        $result = $this->services->store($request);
        
        if($result != null)
            return $this->sendResponse("", "Room has been successfully created. ");
        else
            return $this->sendCustomValidationError(['Error'=>'Failed to create data. ']);

    }

    // This api is for admin user to view certain Room
    public function show(Room $room)
    {
        $result = $this->services->show($room);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to view list of Room
    public function index(Request $request)
    {
        $result = $this->services->index($request);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to update certain Room
    public function update(RoomRequest $request, Room $room)
    {
        $result = $this->services->update($request, $room);

        return $this->sendResponse("", "Room has been successfully updated. ");   
    }

     // This api is for user to view list of Room
     public function roomListing(Request $request)
     {
        $input = $request->all();

        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'library_id' => array('required','exists:libraries,id'),
            'search'    =>  array('nullable')
        ]);

        if ($validator->fails()) {
            return $this->sendCustomValidationError($validator->errors());
        }

         $result = $this->services->roomListing($input);
 
         return $this->sendResponse($result, "Data has been successfully retrieved. ");   
     }


}
