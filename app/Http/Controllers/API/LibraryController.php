<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\LibraryService;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

use App\Http\Requests\LibraryRequest;
use App\Models\User;
use App\Models\Library;

use Auth;
use App;
use Validator;

class LibraryController extends BaseController
{
    public function __construct(LibraryService $library_service)
    {
        $this->services = $library_service;
    }

    public function dashboard()
    {
        if(Auth::check()){
            return view('dashboard.library');
        }
  
        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    // This api is for admin user to create library
    public function store(LibraryRequest $request)
    {
        $result = $this->services->store($request);
        
        if($result != null)
            return $this->sendResponse("", "Library has been successfully registered. ");
        else
            return $this->sendCustomValidationError(['Error'=>'Failed to register account. ']);

    }

    // This api is for admin user to view certain library
    public function show(Library $library)
    {
        $result = $this->services->show($library);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to view list of library
    public function index(Request $request)
    {
        $result = $this->services->index($request);

        // return $this->sendResponse($result, "Data successfully retrieved. "); 
        $result = $this->sendHTMLResponse($result, "Data successfully retrieved. "); 
        
        return view('library.index',["data" =>  $result['data']['aaData']]);
    }

    // This api is for admin user to update certain library
    public function update(LibraryRequest $request, Library $library)
    {
        $result = $this->services->update($request, $library);

        return $this->sendResponse("", "Library has been successfully updated. ");
       
}


}
