<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\AnnouncementService;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

use App\Http\Requests\AnnouncementRequest;
use App\Models\User;
use App\Models\Announcement;

use Auth;
use App;
use Validator;

class AnnouncementController extends BaseController
{
    public function __construct(AnnouncementService $announcement_service)
    {
        $this->services = $announcement_service;
    }

    // This api is for admin user to create Announcement
    public function store(AnnouncementRequest $request)
    {
        $result = $this->services->store($request);
        
        if($result != null)
            return $this->sendResponse("", "Announcement has been successfully created. ");
        else
            return $this->sendCustomValidationError(['Error'=>'Failed to register announcement. ']);

    }


    // This api is for admin user to view certain Announcement
    public function show(Announcement $announcement)
    {
        $result = $this->services->show($announcement);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to view list of Announcement
    public function index(Request $request)
    {
        // dd(1);
        $result = $this->services->index($request);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to update certain Announcement
    public function update(AnnouncementRequest $request, Announcement $announcement)
    {
        $result = $this->services->update($request, $announcement);

        return $this->sendResponse("", "Announcement has been successfully updated. ");      
    }

    // This api is for admin user to view list of announcement listing
    public function announcementListing(Request $request)
    {
        $input = $request->all();

        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'library_id' => array('required','exists:libraries,id'),
        ]);

        if ($validator->fails()) {
            return $this->sendCustomValidationError($validator->errors());
        }

        $result = $this->services->announcementListing($input);

        return $this->sendResponse($result, "Data has been successfully retrieved. ");
    }


}
