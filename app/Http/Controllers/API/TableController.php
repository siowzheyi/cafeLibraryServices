<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\TableService;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

use App\Http\Requests\TableRequest;
use App\Models\User;
use App\Models\Table;

use Auth;
use App;
use Validator;

class TableController extends BaseController
{
    public function __construct(TableService $table_service)
    {
        $this->services = $table_service;
    }

    // This api is for admin user to create table
    public function create()
    {
        return view('library.table.create');
    }

     // This api is for admin user to create certain table
     public function store(Request $request)
     {
         $input = $request->all();
         
         App::setLocale($request->header('language'));
 
        
         $validator = Validator::make($input, [
            'table_no' => ['required'],

            "library_id"   =>  array('nullable','exists:libraries,id',
            Rule::requiredIf(function () use ($request) {
                return $request->user()->hasAnyRole(['superadmin', 'admin']);
            }))
         ]);
                 
 
         // dd($input);
         if ($validator->fails()) {
             return redirect()->back()->withErrors($validator->errors());
         }
         $result = $this->services->store($input);
         return view('library.table.index');
     }

    public function edit(Table $table)
    {
        $data = [
            "id"    =>  $table->id,
            "table_no"    =>  $table->table_no,
            "status"    =>  $table->status,
        ];
        // dd($data);
        return view('library.table.edit',compact('data'));
    }

     // This api is for admin user to update certain table
    public function update(Request $request, Table $table)
    {
        // dd($request);
        $input = $request->all();
        
        App::setLocale($request->header('language'));

        if($request->method() == "PUT")
        {
            $validator = Validator::make($input, [
                'table_no' => ['required'],
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
            return redirect()->back()->withErrors($validator->errors());
        }
        $result = $this->services->update($input, $table);
        // dd(1);
        return view('library.table.index');
    }



    // This api is for admin user to view certain Table
    public function show(Table $table)
    {
        $result = $this->services->show($table);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to view list of Table
    public function index(Request $request)
    {
        $result = $this->services->index($request);

        // return $this->sendResponse($result, "Data successfully retrieved. "); 
        $result = $this->sendHTMLResponse($result, "Data successfully retrieved. "); 
        
        return view('library.table.index');
    }

    

     // This api is for user to view list of Table
     public function tableListing(Request $request)
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

         $result = $this->services->tableListing($input);
 
         return $this->sendResponse($result, "Data has been successfully retrieved. ");   
     }

     public function getTableDatatable(Request $request)
    {
         if (request()->ajax()) {
            $type = $request->type;

            $user = auth()->user();
            
            $data = Table::whereNotNull('status')->orderBy('created_at','desc');

            if($user->hasRole('admin'))
                $data = $data->where('tables.library_id',$request->library_id);
            else
                $data = $data->where('tables.library_id',$user->library_id);

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

                $btn = '<a href="' . route('table.edit', ['table'=>$row->id]) . '" class="btn btn-sm btn-info"><i class="fa fa-pen"></i> Update</a>';
                return $btn;
            });

            $table->rawColumns(['status','action']);
            return $table->make(true);
        }
    }


}
