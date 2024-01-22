<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\CafeService;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use DB;
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
    public function dashboardCafe()
    {
        if(Auth::check()){
            return view('cafe.dashboard');
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

    public function edit(Cafe $cafe)
    {
        $data = [
            "name"  =>  $cafe->name,
            "id"    =>  $cafe->id
        ];

        return view('cafe.edit',compact('data'));
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

        // return $this->sendResponse($result, "Data successfully retrieved. "); 
        // $result = $this->sendHTMLResponse($result, "Data successfully retrieved. "); 
        
        return view('cafe.index');
    }

    // This api is for admin user to update certain cafe
    public function update(Request $request, Cafe $cafe)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => ['required'],
        ]);
        if ($validator->fails()) {
            Session::flash('message-error', $validator->errors());
            return redirect()->back()->withErrors($validator->errors());
        }

        $result = $this->services->update($input, $cafe);

        // return $this->sendResponse("", "Cafe has been successfully updated. ");
        return view('cafe.index');
    }

    public function getCafeDatatable(Request $request)
    {
        if (request()->ajax()) {
            $type = $request->type;

            $user = auth()->user();
            
            $data = Cafe::join('libraries','libraries.id','=','cafes.library_id')
            ->orderBy('cafes.created_at','desc')
            ->select('cafes.name','cafes.id','libraries.name as library_name',
            DB::raw('(CASE WHEN cafes.status = 1 THEN "Active" ELSE "Inactive" END) AS status'));
            

            $table = Datatables::of($data);

            $table->addColumn('action', function ($row) {
                $token = csrf_token();

                $btn = '<a href="' . route('cafe.edit', ['cafe'=>$row->id]) . '" class="btn btn-sm btn-info"><i class="fa fa-pen"></i> Update</a>';

                return $btn;
            });

            $table->rawColumns(['action']);
            return $table->make(true);
        }
    }


}
