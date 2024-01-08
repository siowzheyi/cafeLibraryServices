<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\UserService;
use App\Http\Requests\UserRequest;
use App\Models\User;

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
    public function store(UserRequest $request)
    {
        $result = $this->services->store($request);
        
        if($result != null)
            return $this->sendResponse("", "User has been successfully registered. ");
        else
            return $this->sendCustomValidationError(['Error'=>'Failed to register account. ']);

    }

    // This api is for admin user to view certain user
    public function show(User $user)
    {
        $result = $this->services->show($user);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
        // return view('user.user',["userData" =>  $result]);
    }

    // This api is for admin user to view list of user
    public function index(Request $request)
    {
        $result = $this->services->index($request);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to update certain user
    public function update(UserRequest $request, User $user)
    {
        $result = $this->services->update($request, $user);

        return $this->sendResponse("", "User has been successfully updated. ");   
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
