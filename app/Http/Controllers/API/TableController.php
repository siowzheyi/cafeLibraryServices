<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\TableService;

use App\Http\Requests\TableRequest;
use App\Models\User;
use App\Models\Table;

use Auth;
use App;
use Validator;

class TableController extends BaseController
{
    public function __construct(TableService $table_service)
    {
        $this->services = $table_service;
    }

    // This api is for admin user to create Table
    public function store(TableRequest $request)
    {
        $result = $this->services->store($request);
        
        if($result != null)
            return $this->sendResponse("", "Table has been successfully created. ");
        else
            return $this->sendCustomValidationError(['Error'=>'Failed to create Table. ']);

    }

    // This api is for admin user to view certain Table
    public function show(Table $table)
    {
        $result = $this->services->show($table);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to view list of Table
    public function index(Request $request)
    {
        $result = $this->services->index($request);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to update certain Table
    public function update(TableRequest $request, Table $table)
    {
        $result = $this->services->update($request, $table);

        return $this->sendResponse("", "Table has been successfully updated. ");   
    }

     // This api is for user to view list of Table
     public function tableListing(Request $request)
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

         $result = $this->services->tableListing($input);
 
         return $this->sendResponse($result, "Data has been successfully retrieved. ");   
     }


}
