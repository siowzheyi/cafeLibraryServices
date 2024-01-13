<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\CafeService;

use App\Http\Requests\CafeRequest;
use App\Models\User;
use App\Models\Cafe;

use Auth;
use App;
use Validator;

class CafeController extends BaseController
{
    public function __construct(CafeService $cafe_service)
    {
        $this->services = $cafe_service;
    }

    public function dashboard()
    {
        if(Auth::check()){
            return view('dashboard.cafe');
        }
  
        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    // This api is for admin user to create Cafe
    public function store(CafeRequest $request)
    {
        $result = $this->services->store($request);
        
        if($result != null)
            return $this->sendResponse("", "Cafe has been successfully registered. ");
        else
            return $this->sendCustomValidationError(['Error'=>'Failed to register account. ']);

    }

    // This api is for admin user to view certain cafe
    public function show(Cafe $cafe)
    {
        $result = $this->services->show($cafe);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to view list of cafe
    public function index(Request $request)
    {
        $result = $this->services->index($request);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
        // $result = $this->sendHTMLResponse($result, "Data successfully retrieved. "); 
        
        // return view('cafe.index',["data" =>  $result['data']['aaData']]);
    }

    // This api is for admin user to update certain cafe
    public function update(CafeRequest $request, Cafe $cafe)
    {
        $result = $this->services->update($request, $cafe);

        return $this->sendResponse("", "Cafe has been successfully updated. ");
       
}


}
