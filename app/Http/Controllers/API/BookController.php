<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\BookService;
use Yajra\DataTables\DataTables;
use App\Services\Service;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

use App\Http\Requests\BookRequest;
use App\Models\User;
use App\Models\Book;

use Auth;
use App;
use Validator;

class BookController extends BaseController
{
    public function __construct(BookService $book_service)
    {
        $this->services = $book_service;
    }
    // This api is for admin user to create certain Book
    public function store(Request $request)
    {
        $input = $request->all();
        
        App::setLocale($request->header('language'));

       
        $validator = Validator::make($input, [
            'name' => ['required'],
            'genre' => ['required'],
            'author_name' => ['required'],
            'publisher_name' => ['required'],
            'remark' => ['nullable'],
            'price' => ['required'],
            'stock_count' => ['required','numeric'],
            // 'status'   =>  array('required','in:1,0'),
            'picture' => ['required'],
            "library_id"   =>  array('nullable','exists:libraries,id',
                Rule::requiredIf(function () use ($request) {
                    return $request->user()->hasAnyRole(['superadmin', 'admin']);
                }))
        ]);
                

        // dd($input);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        $result = $this->services->store($request, $input);
        return view('library.book.index');
    }

    // This api is for admin user to create Book
    public function create()
    {
        return view('library.book.create');
    }

    public function edit(Book $book)
    {
        $service = new Service();
        $data = [
            "id" => $book->id,
               "name" => $book->name,
               "genre" => $book->genre,
               "author_name" => $book->author_name,
               "publisher_name" => $book->publisher_name,
               "remark" => $book->remark,
               "availability" => $book->availability,
               "stock_count" => $book->stock_count,
               "remainder_count" => $book->remainder_count,
               "price" => $book->price,
               "picture"    => $book->picture ? $service->getImage('book',$book->id) : null,      

               "status" => $book->status,

        ];
        // dd($data);
        return view('library.book.edit',compact('data'));
    }

    // This api is for admin user to view certain Book
    public function show(Book $book)
    {
        $result = $this->services->show($book);

        return $this->sendResponse($result, "Data successfully retrieved. "); 
        // dd($result);
        
        // return view("",[])
    }

    // This api is for admin user to view list of Book
    public function index(Request $request)
    {
        $result = $this->services->index($request);

        // return $this->sendResponse($result, "Data successfully retrieved. "); 
        
        $result = $this->sendHTMLResponse($result, "Data successfully retrieved. "); 
        
        return view('library.book.index');
    }

    // This api is for admin user to update certain Book
    public function update(Request $request, Book $book)
    {
        $input = $request->all();
        
        App::setLocale($request->header('language'));

        if($request->method() == "PUT")
        {
            $validator = Validator::make($input, [
                'name' => ['required'],
                'genre' => ['required'],
                'author_name' => ['required'],
                'publisher_name' => ['required'],
                'remark' => ['nullable'],
                'price' => ['required'],
                'stock_count' => ['required','numeric'],
                // 'status'   =>  array('required','in:1,0'),
                'picture' => ['nullable'],
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
        $result = $this->services->update($request, $book, $input);
        // dd(1);
        return view('library.book.index');
    }

    // This api is for user to view list of Book
    public function bookListing(Request $request)
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

        $result = $this->services->bookListing($input);

        return $this->sendResponse($result, "Data has been successfully retrieved. ");   
    }

    public function importBook(Request $request)
    {
    $input = $request->all();

    App::setLocale($request->header('language'));

    $validator = Validator::make($input, [
        'excel' => array('required',function ($attribute, $value, $fail) {
            $etx = $value->getClientOriginalExtension();
            $formats = ['xls', 'xlsx', 'ods', 'csv'];
            if (!in_array($etx, $formats)) {
                $fail('Only supports upload .xlsx, .xls files');
            }
        }),
        'library_id'    =>  array('nullable')
    ]);

    if ($validator->fails()) {
        // return $this->sendCustomValidationError($validator->errors());
        return redirect('/staff/book')->withErros("Data has not been successfully imported");

    }

    $result = $this->services->importBook($request);
    // dd($result['details']);
    if($result['status'] == "success")
    // return $this->sendResponse("", "Data has been successfully imported. ");   
    return redirect('/staff/book')->withSuccess("Data has been successfully imported");

    else
    // return $this->sendCustomValidationError($result['details']);
    return redirect('/staff/book')->withErros("Data has not been successfully imported");

    }

    public function getBookDatatable(Request $request)
    {
        if (request()->ajax()) {
            $type = $request->type;

            $user = auth()->user();
            
            $data = Book::whereNotNull('status')->orderBy('created_at','desc');

            if($user->hasRole('admin'))
                $data = $data->where('books.library_id',$request->library_id);
            else
                $data = $data->where('books.library_id',$user->library_id);

            $table = Datatables::of($data);

            $table->addColumn('availability', function ($row) {
                $btn = '<div class="d-flex justify-content-center">';
                if ($row->availability == 0) {
                    $btn = $btn . '<span class="badge bg-danger"> Out of stock </span></div>';
                } elseif ($row->availability == 1) {
                    $btn = $btn . '<span class="badge bg-success"> Available </span></div>';
                }

                return $btn;
            });

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

                $btn = '<a href="' . route('book.edit', ['book'=>$row->id]) . '" class="btn btn-sm btn-info"><i class="fa fa-pen"></i> Update</a>';
                return $btn;
            });

            $table->rawColumns(['availability','status','action']);
            return $table->make(true);
        }
    }


}
