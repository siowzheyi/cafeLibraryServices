<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\BeverageService;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use App\Services\Service;

use App\Http\Requests\BeverageRequest;
use App\Models\User;
use App\Models\Beverage;

use Auth;
use App;
use Validator;

class BeverageController extends BaseController
{
    public function __construct(BeverageService $beverage_service)
    {
        $this->services = $beverage_service;
    }

    public function create()
    {
        return view('cafe.menu.create');
    }

    // This api is for admin user to create Beverage
    public function store(Request $request)
    {
        $input = $request->all();
        
        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'name' => ['required'],
            'price' => ['required'],
            'name' => ['required'],
            'remark' => ['nullable'],
            'category' => ['required'],
            'picture' => ['required'],
            "cafe_id"   =>  array('nullable','exists:cafes,id',
            Rule::requiredIf(function () use ($request) {
                return $request->user()->hasAnyRole(['superadmin', 'admin']);
            }))
        ]);
                

        // dd($input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        $result = $this->services->store($request, $input);
        return view('cafe.menu.index')->withSuccess("Successfully added new menu!");

    }

    // This api is for admin user to view certain Beverage
    public function show(Beverage $beverage)
    {
        $result = $this->services->show($beverage);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to view list of Beverage
    public function index(Request $request)
    {
        $result = $this->services->index($request);

        // return $this->sendResponse($result, "Data successfully retrieved. "); 
        return view('cafe.menu.index');
    }

    public function edit(Beverage $beverage)
    {
        $service = new Service();
        $data = [
            "id"    =>  $beverage->id,
            "name"    =>  $beverage->name,
            "category"    =>  $beverage->category,

            "status"    =>  $beverage->status,
            "remark"    =>  $beverage->remark,
            "price"    =>  $beverage->price,
            "picture"    => $beverage->picture ? $service->getImage('beverage',$beverage->id) : null,      

        ];
        return view('cafe.menu.edit',compact('data'));
    }

    // This api is for admin user to update certain Beverage
    public function update(Request $request, Beverage $beverage)
    {
        $input = $request->all();
        
        App::setLocale($request->header('language'));

        if($request->method() == "PUT")
        {
            $validator = Validator::make($input, [
                'name' => array('required'),
                // 'status'   =>  array('required','in:1,0'),
                'price' => ['required'],
                'category' => ['required'],
                'picture' => ['nullable'],

                'remark' => ['nullable'],
            ]);
        }
        else
        {
            $validator = Validator::make($input, [
                'type' => array('required','in:status'),

            ]);
        }
        
        // dd($input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        $result = $this->services->update($request, $beverage, $input);
        // dd(1);
        return view('cafe.menu.index');
       
    }

    // This api is for admin user to view list of Beverage listing
    public function beverageListing(Request $request)
    {
        $input = $request->all();

        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'cafe_id' => array('required','exists:cafes,id'),
            'search' => array('nullable'),

        ]);

        if ($validator->fails()) {
            return $this->sendCustomValidationError($validator->errors());
        }

        $result = $this->services->beverageListing($input);

        return $this->sendResponse($result, "Data has been successfully retrieved. ");
    }

    public function getBeverageDatatable(Request $request)
    {
        if (request()->ajax()) {
            $type = $request->type;

            $user = auth()->user();
            
            $data = Beverage::whereNotNull('status')->orderBy('created_at','desc')->where('category',$type);

            if($user->hasRole('admin'))
                $data = $data->where('beverages.cafe_id',$request->cafe_id);
            else
                $data = $data->where('beverages.cafe_id',$user->cafe_id);

            

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

                $btn = '<a href="' . route('beverage.edit', ['beverage'=>$row->id]) . '" class="btn btn-sm btn-info"><i class="fa fa-pen"></i> Update</a>';
                return $btn;
            });

            $table->rawColumns(['status','action']);
            return $table->make(true);
        }
    }


}
