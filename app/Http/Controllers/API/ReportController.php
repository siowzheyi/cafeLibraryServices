<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\ReportService;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

use App\Http\Requests\ReportRequest;
use App\Models\User;
use App\Models\Report;

use Auth;
use App;
use Validator;

class ReportController extends BaseController
{
    public function __construct(ReportService $report_service)
    {
        $this->services = $report_service;
    }

    // This api is for admin user to create Report
    public function store(ReportRequest $request)
    {
        $result = $this->services->store($request);
        
        if($result != null)
            return $this->sendResponse("", "Report has been successfully created. ");
        else
            return $this->sendCustomValidationError(['Error'=>'Failed to register Report. ']);

    }


    // This api is for admin user to view certain Report
    public function show(Report $report)
    {
        $result = $this->services->show($report);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to view list of Report
    public function index(Request $request)
    {
        $result = $this->services->index($request);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to update certain Report
    public function update(ReportRequest $request, Report $report)
    {
        $result = $this->services->update($request, $report);

        return $this->sendResponse("", "Report has been successfully updated. ");      
    }

    // This api is for admin user to view list of Report listing
    public function reportListing(Request $request)
    {
        // $input = $request->all();

        // App::setLocale($request->header('language'));

        // $validator = Validator::make($input, [
        //     'library_id' => array('required','exists:libraries,id'),
        // ]);

        // if ($validator->fails()) {
        //     return $this->sendCustomValidationError($validator->errors());
        // }

        $result = $this->services->reportListing($request);

        return $this->sendResponse($result, "Data has been successfully retrieved. ");
    }

    public function destroy(Report $report)
    {
        $report->delete();

        return $this->sendResponse("","Data successfully deleted. ");
    }


}
