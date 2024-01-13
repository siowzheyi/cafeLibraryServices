<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\UserService;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use DB;
use Auth;
use App;
use Validator;

class UserController extends BaseController
{
    public function __construct(UserService $user_service)
    {
        $this->services = $user_service;
    }

    public function indexLogin()
    {
        return view('login',["data" =>  "123"]);
        // return redirect6
        // return $this->sendResponse("", "User has been successfully registered. ");
    }

    // This api is for for library normal user to register normal user account
    public function register(Request $request)
    {
        $input = $request->all();

        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'email' => array('required','unique:users,email'),
            'password' => array('required'),
            'confirm_password' => array('required','same:password'),
            'name' => array('required'),
            'phone_no' => array('required'),

        ]);

        if ($validator->fails()) {
            return $this->sendCustomValidationError($validator->errors());
        }

        $result = $this->services->register($input);

        if($result != null)
            return $this->sendResponse("", "User has been successfully registered. ");
        else
            return $this->sendCustomValidationError(['Error'=>'Failed to register account. ']);
    }

    // This api is for every user to login using email and password
    public function login(Request $request)
    {
        // dd($request);
        $input = $request->all();
        // dd($input);

        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'email' => array('required'),
            'password' => array('required'),
        ]);
        if ($validator->fails()) {
            return $this->sendCustomValidationError($validator->errors());
        }

        $result = $this->services->login($input);
        // dd($result);

        if($result['status'] == "success")
            return $this->sendResponse($result['data'], $result['message']);
        else
            return $this->sendCustomValidationError($result['message']);
    }

    // This api is for admin user to create library and cafe account
    public function store(Request $request)
    {
        $input = $request->all();

        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'name' => ['required'],
            'phone_no' => ['required','unique:users,phone_no'],
            'email' => ['required','unique:users,email'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator->errors())
                             ->withInput();
        }
        

        $result = $this->services->store($request);
        $data = $this->services->index($request);
        $data = $data['aaData'];
        Session::flash('message-success', 'Successfully create user');

        return view('staff.admin.index', compact('data'));

    }

    // This api is for admin user to view certain user
    public function show(User $user)
    {
        $result = $this->services->show($user);

        $result = $this->sendHTMLResponse($result, "Data successfully retrieved. "); 
        
        return view('staff.admin.view',["data" =>  $result['data']]);
    }

    // This api is for admin user to view list of user
    public function index(Request $request)
    {
        $result = $this->services->index($request);
        
        $result = $this->sendHTMLResponse($result, "Data successfully retrieved. "); 
        
        return view('staff.admin.index',["data" =>  $result['data']['aaData']]);

        // return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    public function getUserDatatable(Request $request)
    {
         if (request()->ajax()) {
            $type = $request->type;

            $userId = Auth::id();

            
            $data = User::leftjoin('cafes','cafes.id','=','users.cafe_id')
            ->leftjoin('libraries','libraries.id','=','users.library_id')
            ->select('users.*',
            DB::raw('(CASE WHEN users.cafe_id IS NOT NULL THEN cafes.name ELSE libraries.name END) AS building_name'))
            ->orderBy('users.id','desc');
            
            if ($type == "cafe") 
                $data = $data->whereNotNull('users.cafe_id');
            elseif($type == "library")
                $data = $data->whereNotNull('users.library_id');

            $table = Datatables::of($data);

            $table->addColumn('status', function ($row) {
                $checked = $row->status == 1 ? 'checked' : '';
                $status = $row->status == 1 ? 'Active' : 'Inactive';
            
                $btn = '<div class="form-check form-switch">';
                $btn .= '<input class="form-check-input user-status" type="checkbox" data-user-id="'.$row->id.'" '.$checked.'>';
                $btn .= '<label class="form-check-label">'.$status.'</label>';
                $btn .= '</div>';
            
                return $btn;
            });
            

            $table->addColumn('action', function ($row) {
                $token = csrf_token();

                $btn = '<a href="' . route('user.show', ['user'=>$row->id]) . '" class="btn btn-sm btn-info"><i class="fa fa-pen"></i> Update</a>';
                return $btn;
            });

            $table->rawColumns(['status','action']);
            return $table->make(true);
        }
    }

    public function create(Request $request)
    {
        return view('staff.admin.create');
    }

    // This api is for admin user to update certain user
    public function update(Request $request, User $user)
    {
        $input = $request->all();
        
        App::setLocale($request->header('language'));

        if($request->method() == "PUT")
        {
            $validator = Validator::make($input, [
                'email' => array('required','unique:users,email,'.$user->id.',id,deleted_at,NULL'),
                'name' => ['required'],
                'phone_no' => ['required','unique:users,phone_no,'.$user->id.',id,deleted_at,NULL']
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
            // $error = $this->sendHTMLCustomValidationError($validator->errors());
            // return view('staff.admin.view', compact('user'));
            Session::flash('message-error', $validator->errors());
            return redirect()->back();
        }
        
        $data = $this->services->update($request, $user);
        Session::flash('message-success', 'Successfully update data');
        $data = $this->services->index($request, $user);
        $data = $data['aaData'];

        if($request->method() == "PUT")
        return view('staff.admin.index', compact('data'));
        else
        return $data;

        // return $this->sendResponse("", "User has been successfully updated. ");   
    }

    public function penaltyReport(Request $request)
    {
        $input = $request->all();

        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'library_id' => array('nullable','exists:libraries,id'),
        ]);

        if ($validator->fails()) {
            return $this->sendCustomValidationError($validator->errors());
        }

        $result = $this->services->penaltyReport($input);

        return $this->sendResponse($result, "Successfully retrieve data. ");   
    }

    
    public function penaltyReportItem(Request $request, $booking_id)
    {
        $result = $this->services->penaltyReportItem($request, $booking_id);

        return $this->sendResponse($result, "Successfully retrieve data. ");   
    }


}
