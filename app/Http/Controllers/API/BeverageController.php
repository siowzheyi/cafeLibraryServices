<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\BeverageService;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

use App\Http\Requests\BeverageRequest;
use App\Models\User;
use App\Models\Beverage;

use Auth;
use App;
use Validator;

class BeverageController extends BaseController
{
    public function __construct(BeverageService $beverage_service)
    {
        $this->services = $beverage_service;
    }

    // This api is for admin user to create Beverage
    public function store(BeverageRequest $request)
    {
        $result = $this->services->store($request);
        
        if($result != null)
            return $this->sendResponse("", "Data has been successfully created. ");
        else
            return $this->sendCustomValidationError(['Error'=>'Failed to create data. ']);

    }

    // This api is for admin user to view certain Beverage
    public function show(Beverage $beverage)
    {
        $result = $this->services->show($beverage);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to view list of Beverage
    public function index(Request $request)
    {
        $result = $this->services->index($request);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to update certain Beverage
    public function update(BeverageRequest $request, Beverage $beverage)
    {
        $result = $this->services->update($request, $beverage);

        return $this->sendResponse("", "Data has been successfully updated. ");
       
    }

    // This api is for admin user to view list of Beverage listing
    public function beverageListing(Request $request)
    {
        $input = $request->all();

        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'cafe_id' => array('required','exists:cafes,id'),
            'search' => array('nullable'),

        ]);

        if ($validator->fails()) {
            return $this->sendCustomValidationError($validator->errors());
        }

        $result = $this->services->beverageListing($input);

        return $this->sendResponse($result, "Data has been successfully retrieved. ");
    }


}
