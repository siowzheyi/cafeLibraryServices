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

        $user = auth()->user();
        $library = Library::find($user->library_id);
        $records = $library->book()->join('libraries','libraries.id','=','books.library_id');
        // dd($request);

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

               "status" => $book->status,

        ];

        return $data;
    }

    public function store($request)
    {
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
        $book->library_id = $user->library_id;
        $book->save();


        return $book;
    }

    public function update($request, $book)
    {
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

        return;
    }

    public function bookListing($request)
    {
        
        $search_arr = $request['search'] ?? null;
        $searchValue = isset($search_arr) ? $search_arr : '';

                        
        
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
            
            $book_related = Book::where('genre',$record->genre)
                            ->get();
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