<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Config;
use DateTime;
use App\Models\User;
use App\Models\Roles;
use App\Models\Book;
use App\Models\Library;
use App\Models\ItemCategory;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class BookService
{
   
    public function index($request)
    {
        $search_arr = $request->input('search');
        $searchValue = isset($search_arr) ? $search_arr : '';
        
        $order_arr = $request->input('order');
        $columnSortOrder = isset($order_arr) ? $order_arr : 'desc';
        $library_id = $request->input('library_id');

        $user = auth()->user();
        if($user->hasRole('staff'))
        {
            $library = Library::find($user->library_id);
        }
        else
        {
            if(!isset($library_id) || $library_id == null)
                return [];
            else
                $library = Library::find($library_id);
        }
        $records = $library->book()->join('libraries','libraries.id','=','books.library_id');
        // dd($request);
        $service = new Service();

        $totalRecords = $records->count();

        $records = $records->where(function ($query) use ($searchValue) {
            $query->orWhere('books.name', 'like', '%' . $searchValue . '%');

        });

        if ($request->input('startDate') != null && $request->input('endDate') != null) {
            $startDate = date('Y-m-d H:i:s', strtotime($request->input('startDate')));
            $endDate = date('Y-m-d', strtotime($request->input('endDate'))) . ' 23:59:59';

            $records = $records->whereBetween('books.created_at', [$startDate, $endDate]);
        }
        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'books.*',
            'libraries.name as library_name',
            'libraries.address as library_address'
        )
            ->orderBy('books.created_at', $columnSortOrder)
            ->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
               "id" => $record->id,
               "name" => $record->name,
               "genre" => $record->genre,
               "author_name" => $record->author_name,
               "publisher_name" => $record->publisher_name,
               "remark" => $record->remark,
               "availability" => $record->availability,
               "stock_count" => $record->stock_count,
               "remainder_count" => $record->remainder_count,
               "price" => $record->price,
               "picture"    => $record->picture ? $service->getImage('book',$record->id) : null,      

               "status" => $record->status,
            //    "library_name" => $record->library_name,
            //    "library_address" => $record->library_address,

           );
        }

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }

    public function show($book)
    {
        $library = $book->library()->first();
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

        return $data;
    }

    public function store($request)
    {
        $raw_request = $request;
        $request = $request->validated();

        $category_id = ItemCategory::where('name','book')->first();

        $book = new Book();
        $book->name = $request['name'];
        $book->genre = $request['genre'];
        $book->author_name = $request['author_name'];
        $book->publisher_name = $request['publisher_name'];

        if(isset($request['remark']))
        $book->remark = $request['remark'];
        $book->stock_count = $request['stock_count'];
        $book->remainder_count = $request['stock_count'];

        $book->price = $request['price'];
        $book->item_category_id = $category_id->id;


        $user = auth()->user();
        if($user->hasRole('staff'))
        {
            $book->library_id = $user->library_id;
        }
        else
        {
            $book->library_id = $request['library_id'];

        }
        $book->save();
        if ($raw_request->hasfile('picture')) {
            $service = new Service();
            $service->storeImage('book',$raw_request->file('picture'),$book->id);
        }

        return $book;
    }

    public function update($request, $book)
    {
        $raw_request = $request;

        $request = $request->validated();

        if (isset($request['type'])) {
            if ($request['type'] === 'status') {
                $book->status = $book->status === 1 ? 0 : 1;
            }
            $book->save();
            return;
        }
        $book->name = $request['name'];
        $book->status = $request['status'];

        $book->genre = $request['genre'];
        $book->author_name = $request['author_name'];
        $book->publisher_name = $request['publisher_name'];

        if(isset($request['remark']))
        $book->remark = $request['remark'];
        else
        $book->remark = null;

        $book->stock_count = $request['stock_count'];
        $book->price = $request['price'];

        $book->save();

        //update image of books
        if($raw_request->hasfile('picture')) {
            $file = $raw_request->file('picture');
            $media = Media::where('model_type', 'App\Models\Book')->where('name', Config::get('main.book_image_path'))->where('model_id', $book->id)->first();
            if($media == null) {
                $service = new Service();
                $service->storeImage('book', $file, $book->id);
                $book->save();
                return;
            }
            $previous_file = Storage::disk('public')->get($media->name . $media->file_name);
            // $previous_file = $service->getImage('main',$media->id);


            // Create a temporary file in the server's tmp directory
            $tmpFilePath = tempnam(sys_get_temp_dir(), 'uploaded_file');
            $tmpFile = new UploadedFile($tmpFilePath, $media->file_name, null, null, true);

            // Write the file contents to the temporary file
            file_put_contents($tmpFilePath, $previous_file);

            $previous_file_name = preg_replace('/^[0-9]+_/', '', $tmpFile->getClientOriginalName());

            $uploaded_file_name = $file->getClientOriginalName();
            $uploaded_file_size = $file->getSize();
            // dd($tmpFile->getSize(),$previous_file_size, $file,$previous_file_size , $uploaded_file_size);
            //compare
            if($previous_file_name != $uploaded_file_name || $tmpFile->getSize() != $uploaded_file_size) {
                // $service->storeImage('main',$file, $request['display_name']);
                $mime_type = $file->getClientOriginalExtension();
                $storage_path = $media->name;

                $path = Storage::disk('public')->putFileAs($storage_path, $file, $uploaded_file_name, ['visibility' => 'public']);
                // dd($storage_path, $file, $uploaded_file_name,$path);

                $media->file_name = $uploaded_file_name;
                $media->mime_type = $mime_type;
                // $media->display_name = $request['display_name'];
                $book->picture = $uploaded_file_name;
                $media->save();
                $book->save();
            }

        }

        return;
    }

    public function bookListing($request)
    {
        
        $search_arr = $request['search'] ?? null;
        $searchValue = isset($search_arr) ? $search_arr : '';
        $service = new Service();

                        
        
        $records = Book::where('library_id',$request['library_id'])
                            ->where('status',1)
                            ->where('remainder_count','>=',1)
                            ->where('availability',1);
        // dd($records->get());

        

        $totalRecords = $records->count();

        if($searchValue != null)
        {
            $records = $records->where(function ($query) use ($searchValue) {
                $query->orWhere('books.name', 'like', '%' . $searchValue . '%')
                ->orWhere('books.genre', 'like', '%' . $searchValue . '%')
                ->orWhere('books.author_name', 'like', '%' . $searchValue . '%')
                ->orWhere('books.publisher_name', 'like', '%' . $searchValue . '%');

            });
        }

        $totalRecordswithFilter = $records->count();

        $records = $records->select(
            'books.genre'
        )
        ->groupBy('genre')
        // ->orderBy('books.created_at', 'asc')
        ->get();

        // dd($records);

        $data_arr = array();
        foreach ($records as $key => $record) {
            
            $book_related = Book::where('genre',$record->genre)->where('status',1)
            ->where('remainder_count','>=',1)
            ->where('availability',1);
                            // ->get();
            if($searchValue != null)
            {
                $book_related = $book_related->where(function ($query) use ($searchValue) {
                    $query->orWhere('books.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('books.genre', 'like', '%' . $searchValue . '%')
                    ->orWhere('books.author_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('books.publisher_name', 'like', '%' . $searchValue . '%');
    
                });
            }
            $book_related = $book_related->get();
            $book_arr = array();

            foreach ($book_related as $key => $book) {
                $book_arr[] = [
                    "id" => $book->id,
                    "name" => $book->name,
                   "author_name" => $book->author_name,
                   "publisher_name" => $book->publisher_name,
                   "remark" => $book->remark,
                   "remainder_count" => $book->remainder_count,
                   "price" => $book->price,
                   "picture"    => $book->picture ? $service->getImage('book',$book->id) : null,      

                ];
            }

            
            $data_arr[] = array(
                "genre" => $record->genre,
                "books" =>  $book_arr
                

           );
        }
        // dd($data_arr);

        $result['iTotalRecords']  = $totalRecords;
        $result["iTotalDisplayRecords"] = intval($totalRecordswithFilter);
        $result['aaData'] =  $data_arr;

        return $result;
    }


}
?>