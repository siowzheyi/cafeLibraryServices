<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\ItemService;

use App\Http\Requests\ItemRequest;
use App\Models\User;
use App\Models\Item;

use Auth;
use App;
use Validator;

class ItemController extends BaseController
{
    public function __construct(ItemService $item_service)
    {
        $this->services = $item_service;
    }

    // This api is for admin user to create Item
    public function store(ItemRequest $request)
    {
        $result = $this->services->store($request);
        
        if($result != null)
            return $this->sendResponse("", "Data has been successfully created. ");
        else
            return $this->sendCustomValidationError(['Error'=>'Failed to create data. ']);

    }

    // This api is for admin user to view certain Item
    public function show(Item $item)
    {
        $result = $this->services->show($item);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to view list of Item
    public function index(Request $request)
    {
        $result = $this->services->index($request);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to update certain Item
    public function update(ItemRequest $request, Item $item)
    {
        $result = $this->services->update($request, $item);

        return $this->sendResponse("", "Data has been successfully updated. ");
       
    }

    // This api is for admin user to view list of item category
    public function itemCategory(Request $request)
    {
        $result = $this->services->itemCategory($request);

        return $this->sendResponse($result, "Data has been successfully retrieved. ");
    }


}
