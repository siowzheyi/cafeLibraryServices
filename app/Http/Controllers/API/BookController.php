<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\BookService;

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

    // This api is for admin user to create Book
    public function store(BookRequest $request)
    {
        $result = $this->services->store($request);
        
        if($result != null)
            return $this->sendResponse("", "Book has been successfully created. ");
        else
            return $this->sendCustomValidationError(['Error'=>'Failed to create data. ']);

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

        return $this->sendResponse($result, "Data successfully retrieved. "); 
    }

    // This api is for admin user to update certain Book
    public function update(BookRequest $request, Book $book)
    {
        $result = $this->services->update($request, $book);

        return $this->sendResponse("", "Book has been successfully updated. ");   
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


}
