<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Config;
use DateTime;
use App\Models\User;
use App\Models\Roles;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function login($request)
    {
        $data['status'] = "fail";
        $data['message'] = ['email' => 'Wrong email/password'];
        $data['data'] = "";

        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {

            $user = Auth::user();

            if ($user->status != 1) {
                $data['message'] = ['email' => 'Your account has been locked. Please contact customer service for enquiry.'];
            }
            else{
                $oauth_access_token = $user->createToken('CLS Access Token');
                $result['token'] =  $oauth_access_token->accessToken;
                $result['name'] =  $user->name;
                $result['id'] = $user->id;
                $user_role = $user->roles()->first();
                $result['role_id'] = $user_role->id;
                $result['role'] = $user_role->name;
                if($user->library_id != null)
                $result['cafe_or_library'] = "library staff";
                elseif($user->cafe_id != null)
                $result['cafe_or_library'] = "cafe staff";
                else
                $result['cafe_or_library'] = null;
                // dd($oauth_access_token);
                $token = $oauth_access_token->token;
                $token->access_token = $result['token'];
                $token->status = "full login";

                $token->save();

                $data['data'] = $result;
                $data['status'] = "success";
                $data['message'] = 'User login successfully.';
            }
        } 

        return $data;
    }

    public function register($request)
    {
        $role = Roles::where('name','user')->first();
        $user = new User();
        $user->email = $request['email'];
        $user->plain_password = $request['password'];
        $user->password = bcrypt($request['password']);
        $user->role_id = $role->id;
        $user->name = $request['name'];
        $user->phone_no = $request['phone_no'];
        $user->save();

        return $user;
    }

    public function index($request)
    {
        $search_arr = $request->input('search');
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $order_arr = $request->input('order');
        $columnSortOrder = isset($order_arr) ? $order_arr : 'desc';

        $records = User::join('roles','roles.id','=','users.role_id');
        // dd($request);
        if($request->input('type') != null)
        {
            $role = Roles::where('name','staff')->first();

            if($request->input('type') == 'cafe')
            {
                $records = $records->where('users.role_id',$role->id)
                                    ->whereNotNull('users.cafe_id');
            }
            elseif($request->input('type') == 'library')
            {
                $records = $records->where('users.role_id',$role->id)
                                    ->whereNotNull('users.library_id');
            }
        }

        $totalRecords = $records->count();

        $records = $records->where(function ($query) use ($searchValue) {
            $query->orWhere('users.name', 'like', '%' . $searchValue . '%')
            ->orWhere('users.email', 'like', '%' . $searchValue . '%')
            ->orWhere('users.phone_no', 'like', '%' . $searchValue . '%');
        });

        if ($request->input('startDate') != null && $request->input('endDate') != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request->input('startDate')));
            $endDate = date('Y-m-d', strtotime($request->input('endDate'))) . ' 23:59:59';

            $records = $records->whereBetween('users.created_at', [$startDate, $endDate]);
        }
        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'users.*',
            'roles.name as role_name'
        )
            ->orderBy('users.created_at', $columnSortOrder)
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
               "id" => $record->id,
               "name" => $record->name,
               "status" => $record->status,
               "role_name" => $record->role_name,
               "email" => $record->email,
               "phone_no" => $record->phone_no,
           );
        }

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }

    public function show($user)
    {
        $role = Roles::find($user->role_id);
        $data = [
            "id"    =>  $user->id,
            "name"    =>  $user->name,
            "email"    =>  $user->email,
            "phone_no"    =>  $user->phone_no,
            "status"    =>  $user->status,
            "library_id"    =>  $user->library_id,
            "cafe_id"    =>  $user->cafe_id,
            "role"    =>  $role->name,

        ];

        return $data;
    }

    public function store($request)
    {
        $request = $request->validated();

        $user = new User();
        $user->name = $request['name'];
        $user->phone_no = $request['phone_no'];
        $user->email = $request['email'];
        $user->plain_password = $request['password'];
        $user->password = bcrypt($request['password']);

        $role = Roles::where('name','staff')->first();
        $user->role_id = $role->id;
        $user->save();

        return $user;
    }

    public function update($request, $user)
    {
        $request = $request->validated();

        if (isset($request['type'])) {
            if ($request['type'] === 'status') {
                $user->status = $user->status === 1 ? 0 : 1;
            }
            $user->save();
            return;
        }
        $user->name = $request['name'];
        $user->status = $request['status'];
        $user->email = $request['email'];
        $user->phone_no = $request['phone_no'];

        $user->save();

        return;
    }

}
?>