<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\EquipmentService;

use App\Http\Requests\EquipmentRequest;
use App\Models\User;
use App\Models\Equipment;

use Auth;
use App;
use Validator;

class EquipmentController extends BaseController
{
    public function __construct(EquipmentService $equipment_service)
    {
        $this->services = $equipment_service;
    }

    // This api is for admin user to create Equipment
    public function store(EquipmentRequest $request)
    {
        $result = $this->services->store($request);
        
        if($result != null)
            return $this->sendResponse("", "Equipment has been successfully created. ");
        else
            return $this->sendCustomValidationError(['Error'=>'Failed to create data. ']);

    }

    // This api is for admin user to view certain Equipment
    public function show(Equipment $equipment)
    {
        $result = $this->services->show($equipment);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to view list of Equipment
    public function index(Request $request)
    {
        $result = $this->services->index($request);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to update certain Equipment
    public function update(EquipmentRequest $request, Equipment $equipment)
    {
        $result = $this->services->update($request, $equipment);

        return $this->sendResponse("", "Equipment has been successfully updated. ");   
    }

     // This api is for user to view list of Equipment
     public function equipmentListing(Request $request)
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

         $result = $this->services->equipmentListing($input);
 
         return $this->sendResponse($result, "Data has been successfully retrieved. ");   
     }


}
