<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\RoomService;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use App\Services\Service;

use App\Http\Requests\RoomRequest;
use App\Models\User;
use App\Models\Room;

use Auth;
use App;
use Validator;

class RoomController extends BaseController
{
    public function __construct(RoomService $room_service)
    {
        $this->services = $room_service;
    }

    public function create()
    {
        return view('library.room.create');
    }

    // This api is for admin user to create Room
    public function store(Request $request)
    {
        $input = $request->all();

        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'room_no' => ['required'],
            'room_type' => ['required'],
            'remark' => ['nullable'],
            'picture' => ['required'],

            "library_id"   =>  array('nullable','exists:libraries,id',
            Rule::requiredIf(function () use ($request) {
                return $request->user()->hasAnyRole(['superadmin', 'admin']);
            }))
        ]);
        
         if ($validator->fails()) {
             return redirect()->back()->withErrors($validator->errors());
         }
         $result = $this->services->store($request, $input);
         return view('library.room.index');

    }

    // This api is for admin user to view certain Room
    public function show(Room $room)
    {
        $result = $this->services->show($room);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to view list of Room
    public function index(Request $request)
    {
        $result = $this->services->index($request);

        // return $this->sendResponse($result, "Data successfully retrieved. "); 
        
        $result = $this->sendHTMLResponse($result, "Data successfully retrieved. "); 
        
        return view('library.room.index');
    }

    public function edit(Room $room)
    {
        $service = new Service();
        $data = [
            "id" => $room->id,
               "room_no" => $room->room_no,
               "room_type" => $room->type,
               "remark" => $room->remark,
               "availability" => $room->availability,
               "status" => $room->status,
               "picture"    => $room->picture ? $service->getImage('room',$room->id) : null,      

        ];
        return view('library.room.edit',compact('data'));
    }

    // This api is for admin user to update certain Room
    public function update(Request $request, Room $room)
    {
        $input = $request->all();

        App::setLocale($request->header('language'));
        // dd($input);
        if($request->method() == "PUT")
        {
            $validator = Validator::make($input, [
                'room_no' => ['required'],
                'room_type' => ['required'],
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
        $result = $this->services->update($request, $room, $input);
        return view('library.room.index');
    }

    // This api is for user to view list of Room
    public function roomListing(Request $request)
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

        $result = $this->services->roomListing($input);

        return $this->sendResponse($result, "Data has been successfully retrieved. ");   
    }

    public function getRoomDatatable(Request $request)
    {
        if (request()->ajax()) {
            $type = $request->type;

            $user = auth()->user();
            
            $data = Room::whereNotNull('status')->orderBy('created_at','desc');

            if($user->hasRole('admin'))
                $data = $data->where('rooms.library_id',$request->library_id);
            else
                $data = $data->where('rooms.library_id',$user->library_id);

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

                $btn = '<a href="' . route('room.edit', ['room'=>$row->id]) . '" class="btn btn-sm btn-info"><i class="fa fa-pen"></i> Update</a>';
                return $btn;
            });

            $table->rawColumns(['status','action']);
            return $table->make(true);
        }
    }


}
