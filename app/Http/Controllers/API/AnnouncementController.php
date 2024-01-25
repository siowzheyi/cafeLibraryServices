<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\AnnouncementService;
use Yajra\DataTables\DataTables;
use App\Services\Service;

use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use View;
use App\Http\Requests\AnnouncementRequest;
use App\Models\User;
use App\Models\Announcement;
use DB;
use Auth;
use App;
use Validator;

class AnnouncementController extends BaseController
{
    public function __construct(AnnouncementService $announcement_service)
    {
        $this->services = $announcement_service;
    }

    public function create()
    {
        return view('library.announcement.create');
    }

    // This api is for admin user to create Announcement
    public function store(Request $request)
    {
        $input = $request->all();

        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'title' => ['required'],
            'content' => ['required'],
            'expired_at' => ['nullable'],
            'picture'   =>  ['required'],
            "library_id"   =>  array('nullable','exists:libraries,id',
            Rule::requiredIf(function () use ($request) {

                return $request->user()->hasAnyRole(['superadmin', 'admin']);
            }))
        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator->errors());
        }

        $result = $this->services->store($request, $input);
        
        if($result != null)
            return view('library.announcement.index')->withSuccess("Data successfully created. ");
        else
            return redirect()->back()->withErrors("Data is not created.");

    }

    public function edit(Announcement $announcement)
    {
        $service = new Service();
        $library = $announcement->library()->first();
        $data = [
            "id"    =>  $announcement->id,
            "title"    =>  $announcement->title,
            "content"    =>  $announcement->content,
            "picture"    => $announcement->picture ? $service->getImage('announcement',$announcement->id) : null,      

            "status"    =>  $announcement->status,
            "expired_at" => $announcement->expired_at != null ? date('Y-m-d H:i:s',strtotime($announcement->expired_at)) : null,

            "library_id"    =>  $announcement->library_id,
            "library_name"    =>  $library->name,
            "library_address"    =>  $library->address,

        ];
        return view('library.announcement.edit',compact('data'));
    }


    // This api is for admin user to view certain Announcement
    public function show(Announcement $announcement)
    {
        $result = $this->services->show($announcement);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to view list of Announcement
    public function index(Request $request)
    {
        // dd(1);
        $result = $this->services->index($request);
        return view('library.announcement.index');
        // return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to update certain Announcement
    public function update(Request $request, Announcement $announcement)
    {
        $input = $request->all();
        
        App::setLocale($request->header('language'));

        if($request->method() == "PUT")
        {
            $validator = Validator::make($input, [
                'title' => array('required'),
                // 'status'   =>  array('required','in:1,0'),
                'content' => ['required'],
                'expired_at' => ['nullable'],
                'picture'   =>  ['nullable'],
            ]);
        }
        else
        {
            $validator = Validator::make($input, [
                'type' => array('required','in:status'),
            ]);
        }
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        $result = $this->services->update($request, $announcement, $input);
        return view('library.announcement.index');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        Session::flash('success', 'Successfully delete announcement.');
        // return View::make('layouts/flash-messages');
        // return view('library.announcement.index');
        return;
    }

    // This api is for admin user to view list of announcement listing
    public function announcementListing(Request $request)
    {
        $input = $request->all();

        App::setLocale($request->header('language'));

        $validator = Validator::make($input, [
            'library_id' => array('required','exists:libraries,id'),
        ]);

        if ($validator->fails()) {
            return $this->sendCustomValidationError($validator->errors());
        }

        $result = $this->services->announcementListing($input);

        return $this->sendResponse($result, "Data has been successfully retrieved. ");
    }

    public function getAnnouncementDatatable(Request $request)
    {
        if (request()->ajax()) {
            $type = $request->type;

            $user = auth()->user();
            
            $data = Announcement::whereNotNull('status')->orderBy('created_at','desc')
            ->select('announcements.title','announcements.content','announcements.expired_at','announcements.id',
            DB::raw('(CASE WHEN announcements.status = 1 THEN "Active" ELSE "Inactive" END) AS status'));

            if($user->hasRole('admin'))
                $data = $data->where('announcements.library_id',$request->library_id);
            else
                $data = $data->where('announcements.library_id',$user->library_id);

            $table = Datatables::of($data);

            $table->addColumn('action', function ($row) {
                $token = csrf_token();

                $btn = '<a href="' . route('announcement.edit', ['announcement'=>$row->id]) . '" class="btn btn-sm btn-info"><i class="fa fa-pen"></i> Update</a>';
                $btn = $btn . '<button id="' . $row->id . '" data-token="' . $token . '" class="btn btn-danger m-1 deleteData">Delete</button>';

                return $btn;
            });

            $table->rawColumns(['action']);
            return $table->make(true);
        }
    }



}
