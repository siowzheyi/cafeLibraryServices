<?php

namespace App\Imports;

use App\Models\Book;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\ValidationException;
use App\Models\ItemCategory;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Validation\Rule;

class BooksImport implements ToModel, WithHeadingRow, WithValidation
{
    // use Importable;

    /**
     * @param array $row
     *
     * @return Book|null
     */
    public function __construct($library_id)
    {
        $this->library_id = $library_id;
    }

    public function model(array $row)
    {
        // dd($row);
        if(!isset($row['name']) || !isset($row['genre'])||!isset($row['author_name'])||!isset($row['publisher_name'])||!isset($row['stock_count'])||!isset($row['price'])){
            // dd(2);
            throw ValidationException::withMessages(["error" => "Invalid headers or missing column"]);
        }
        // dd(1);
        // try{
            $item_category = ItemCategory::where('name','book')->first();
            if($row['stock_count'] > 0)
                $availability = 1;
            else
                $availability = 0;

            return new Book([
            'name'     => $row['name'],
            'genre'    => $row['genre'], 
            'author_name'    => $row['author_name'], 
            'publisher_name'    => $row['publisher_name'], 
            'stock_count'    => $row['stock_count'], 
            'price'    => $row['price'], 
            'library_id' =>  $this->library_id,
            'item_category_id'   =>  $item_category->id,
            'status' =>  1,
            'remainder_count'    =>  $row['stock_count'],
            'availability'  =>  $availability

            ]);

        // }catch (\Exception $e) {
        //     Log::error('Error importing book: ' . $e->getMessage());
        // }
        
    }

    public function rules(): array
    {
        // dd(3);
        return [
            // '*.Name' => ['required'],
            // '*.Genre' => ['required'],
            // '*.Author_Name' => ['required'],
            // '*.Publisher_Name' => ['required'],
            // '*.Stock_Count' => ['required'],
            // '*.Price' => ['required']
            'name' => ['required'],
            'genre' => ['required'],
            'author_name' => ['required'],
            'publisher_name' => ['required'],
            'stock_count' => ['required'],
            'price' => ['required']
        ];
        // dd(2);
        // return [
        //     'name' => [
        //         'required',
        //         'string',
        //     ],
        // ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Name of book is required',
            'genre.required' => 'Genre of book is required',
            'author_name.required' => 'Author name of book is required',
            'publisher_name.required' => 'Publisher name of book is required',
            'stock_count.required' => 'Stock count of book is required',
            'price.required' => 'Price of book is required',

        ];
    }
}