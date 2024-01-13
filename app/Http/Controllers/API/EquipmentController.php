<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\EquipmentService;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use App\Services\Service;

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

    public function create()
    {
        return view('library.equipment.create');
    }

    // This api is for admin user to create Equipment
    public function store(Request $request)
    {
        $input = $request->all();

        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'name' => ['required'],
            'remark' => ['nullable'],
            'picture' => ['required'],

            "library_id"   =>  array('nullable','exists:libraries,id',
            Rule::requiredIf(function () use ($request) {
                return $request->user()->hasAnyRole(['superadmin', 'admin']);
            }))
        ]);

        if ($validator->fails()) {
            Session::flash('message-error', $validator->errors());

            return redirect()->back()->withErrors($validator->errors());
        }

        $result = $this->services->store($request, $input);
        
        if($result != null)
            return view('library.equipment.index')->withSuccess("Data successfully created. ");
        else
            return redirect()->back()->withErrors("Data is not created.");

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
        // $result = $this->sendHTMLResponse($result, "Data successfully retrieved. "); 
        
        // return view('library.equipment.index',["data" =>  $result['data']['aaData']]);
    }

    public function edit(Equipment $equipment)
    {
        $service = new Service();
        $data = [
            "id" => $equipment->id,
               "name" => $equipment->name,
               "remark" => $equipment->remark,
               "availability" => $equipment->availability,
               "status" => $equipment->status,
               "picture"    => $equipment->picture ? $service->getImage('equipment',$equipment->id) : null,      

        ];

        return view('library.equipment.edit',compact('data'));
    }

    // This api is for admin user to update certain Equipment
    public function update(Request $request, Equipment $equipment)
    {
        $input = $request->all();
        
        App::setLocale($request->header('language'));

        if($request->method() == "PUT")
        {
            $validator = Validator::make($input, [
                'name' => ['required'],
                'remark' => ['nullable'],
                'picture' => ['nullable'],
            ]);
        }
        else
        {
            $validator = Validator::make($input, [
                'type' => array('required','in:status'),
            ]);
        }
        
        if ($validator->fails()) {
            Session::flash('message-error', $validator->errors());
            return redirect()->back()->withErrors($validator->errors());
        }
        $result = $this->services->update($request, $equipment, $input);
        return view('library.equipment.index');
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

    public function getEquipmentDatatable(Request $request)
    {
        if (request()->ajax()) {
            $type = $request->type;

            $user = auth()->user();
            
            $data = Equipment::whereNotNull('status')->orderBy('created_at','desc');

            if($user->hasRole('admin'))
                $data = $data->where('equipments.library_id',$request->library_id);
            else
                $data = $data->where('equipments.library_id',$user->library_id);

            $table = Datatables::of($data);

            $table->addColumn('status', function ($row) {
                $checked = $row->status == 1 ? 'checked' : '';
                $status = $row->status == 1 ? 'Active' : 'Inactive';
            
                $btn = '<div class="form-check form-switch">';
                $btn .= '<input class="form-check-input data-status" type="checkbox" data-id="'.$row->id.'" '.$checked.'>';
                $btn .= '<label class="form-check-label">'.$status.'</label>';
                $btn .= '</div>';
            
                return $btn;
            });
            

            $table->addColumn('action', function ($row) {
                $token = csrf_token();

                $btn = '<a href="' . route('equipment.edit', ['equipment'=>$row->id]) . '" class="btn btn-sm btn-info"><i class="fa fa-pen"></i> Update</a>';
                return $btn;
            });

            $table->rawColumns(['status','action']);
            return $table->make(true);
        }
    }


}
