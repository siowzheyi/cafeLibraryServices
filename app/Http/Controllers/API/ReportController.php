<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\ReportService;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;
use DB;
use App\Http\Requests\ReportRequest;
use App\Models\User;
use App\Models\Report;
use App\Models\Book;
use App\Models\Equipment;
use App\Models\Room;
use App\Services\Service;

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
        // $result = $this->services->index($request);

        return view('library.faulty_item.index');
    }

    // This api is for admin user to update certain Report
    public function update(ReportRequest $request, Report $report)
    {
        $result = $this->services->update($request, $report);

        return $this->sendResponse("", "Report has been successfully updated. ");      
    }

    public function edit(Report $report)
    {
        $service = new Service();
        $user = $report->user()->first();
        if($report->book_id != null)
        {
            $item_id = $report->book_id;
            $item = Book::find($item_id);
            $item_name = $item->name;
            $item_picture = $item->picture ? $service->getImage('book',$item->id) : null;
        }
        elseif($report->equipment_id != null)
        {
            $item_id = $report->equipment_id;
            $item = Equipment::find($item_id);
            $item_name = $item->name;
            $item_picture = $item->picture ? $service->getImage('equipment',$item->id) : null;

        }
        elseif($report->room_id != null)
        {
            $item_id = $report->room_id;
            $item = Room::find($item_id);
            $item_name = $item->room_no;
            $item_picture = $item->picture ? $service->getImage('room',$item->id) : null;

        }
        $data = [
            "id" => $report->id,
               "user_name" => $report->user_name,
               "item_id" => $item_id,
               "item_name" => $item_name,
               "item_picture" => $item_picture,
               "name" => $report->name,
               "description" => $report->description,
               "remark" => $report->remark,
               "picture" => $report->picture ? $service->getImage('report',$report->id) : null,
               "status" => $report->status,
               "created_at" => $report->created_at != null ? date('Y-m-d H:i:s',strtotime($report->created_at)) : null,

        ];
        return $data;
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

    public function getReportDatatable(Request $request)
    {
        $service = new Service();
        if (request()->ajax()) {
            $type = $request->type;

            $user = auth()->user();
            
            $data = Report::leftjoin('rooms','rooms.id','=','reports.room_id')
            ->leftjoin('equipments','equipments.id','=','reports.equipment_id')
            ->leftjoin('books','books.id','=','reports.book_id')
            ->join('users','users.id','=','reports.user_id')
            ->select('reports.*','users.name as user_name',
                DB::raw('CASE 
                    WHEN books.name IS NOT NULL THEN books.name
                    WHEN equipments.name IS NOT NULL THEN equipments.name
                    WHEN rooms.room_no IS NOT NULL THEN rooms.room_no
                    ELSE NULL END AS item_name')
            )
            ->orderBy('reports.created_at','asc');



            $data = $data->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('reports.status', 1)
                        ->whereDate('reports.created_at', now()->format('Y-m-d'));
                })
                ->orWhere(function ($query) {
                    $query->where('reports.status', 0);
                });
            });

            if($user->hasRole('admin')) {
                $data = $data->where('rooms.library_id', $request->library_id)
                             ->orWhere('equipments.library_id', $request->library_id)
                             ->orWhere('books.library_id', $request->library_id);
            } else {
                $data = $data->where('rooms.library_id', $user->library_id)
                             ->orWhere('equipments.library_id', $user->library_id)
                             ->orWhere('books.library_id', $user->library_id);
            }

            $data = $data->get();

            
            foreach ($data as $item) {
                // dd($item);
                $item->picture = $item->picture ? $service->getImage('report', $item->id) : null;
                
            }
            

            $table = Datatables::of($data);


            $table->addColumn('status', function ($row) {
                $checked = $row->status == 1 ? 'checked' : '';
                $status = $row->status == 1 ? 'Completed' : 'Pending';
            
                $btn = '<div class="form-check form-switch">';
                $btn .= '<input class="form-check-input data-status" type="checkbox" data-id="'.$row->id.'" '.$checked.'>';
                $btn .= '<label class="form-check-label">'.$status.'</label>';
                $btn .= '</div>';
            
                return $btn;
            });
            

            $table->addColumn('action', function ($row) {
                $token = csrf_token();

                $btn ='<button id="'.$row->id.'" data_id="' . $row->id . '" data-token="' . $token . '" class="btn btn-primary m-1 showData" data-bs-toggle="modal" data-bs-target="#orderModal">View</button>';
                return $btn;
            });

            $table->rawColumns(['status','action']);
            return $table->make(true);
        }
    }


}
