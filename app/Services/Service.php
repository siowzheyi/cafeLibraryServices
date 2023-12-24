<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Config;
use DateTime;
use App\Models\Media;
use App\Models\Menu;
use App\Models\Announcement;
use App\Models\Equipment;
use App\Models\Room;
use App\Models\Book;
use Log;
use App\Models\Beverage;

use Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Service
{
    public function __construct() {}

    public function storeImage($type, $file, $id)
    {
        if($type == 'announcement') {
            $storage_path = Config::get('main.announcement_image_path');
            // dd($storage_path);
            $model_type = 'App\Models\Announcement';
            $collection_name = 'announcement';
        }  elseif($type == 'beverage') {
            $storage_path = Config::get('main.beverage_image_path');
            $model_type = 'App\Models\Beverage';
            $collection_name = 'beverage';
           
        }  elseif($type == 'book') {
            $storage_path = Config::get('main.book_image_path');
            $model_type = 'App\Models\Book';
            $collection_name = 'book';
           
        }   elseif($type == 'equipment') {
            $storage_path = Config::get('main.equipment_image_path');
            $model_type = 'App\Models\Equipment';
            $collection_name = 'equipment';
           
        }   elseif($type == 'room') {
            $storage_path = Config::get('main.room_image_path');
            $model_type = 'App\Models\Room';
            $collection_name = 'room';
           
        }  elseif($type == 'logo') {
            // store logo of system server
            $storage_path = "images/logo/";
            $model_type = 'App\Models\Media';
            $collection_name = 'system';
        } 

        $fileName = time() . "_" . $file->getClientOriginalName();
        $mime_type = $file->getClientOriginalExtension();
        // dd($storage_path, $file, $fileName);

        $path = Storage::disk('public')->putFileAs($storage_path, $file, $fileName, ['visibility' => 'public']);

        if($type == 'announcement') {
            $announcement = Announcement::find($id);
            $announcement->picture = $fileName;
            $announcement->save();
        }  elseif($type == 'beverage') {
            $beverage = Beverage::find($id);
            $beverage->picture = $fileName;
            $beverage->save();
        } elseif($type == 'room') {
            $room = Room::find($id);
            $room->picture = $fileName;
            $room->save();
        }elseif($type == 'book') {
            $book = Book::find($id);
            $book->picture = $fileName;
            $book->save();
        } elseif($type == 'equipment') {
            $equipment = Equipment::find($id);
            $equipment->picture = $fileName;
            $equipment->save();
        } elseif($type == 'main' || $type == 'logo') {

            $media = new Media();
            $media->model_type = $model_type;
            $media->model_id = 1;
            $media->name = $storage_path;
            $media->collection_name = $collection_name;
            $media->file_name = $fileName;
            $media->display_name = $id;
            $media->mime_type = $mime_type;

            $media->save();
            $media->model_id = $media->id;
            $media->save();
            return;
        }
        


        $media = new Media();
        $media->model_type = $model_type;
        $media->model_id = $id;
        $media->name = $storage_path;
        $media->collection_name = $collection_name;
        $media->file_name = $fileName;
        $media->mime_type = $mime_type;

        $media->save();
    }

    public function getImage($type, $id)
    {
        if($type == 'announcement') {

            $model = Announcement::find($id);
           
            $media = Media::where('model_type', 'App\Models\Announcement')->where('collection_name','announcement')->where('model_id', $id)->first();
            if($media == null) {
                return null;
            }
            return Storage::disk('public')->url($media->name . $model->picture);
        }elseif($type == 'beverage') {
            $model = Beverage::find($id);
            
            $media = Media::where('model_type', 'App\Models\Beverage')->where('name', Config::get('main.beverage_image_path'))->where('model_id', $id)->first();
            
            if($media == null) {
                return null;
            }
            return Storage::disk('public')->url($media->name . $model->picture);
        }  elseif($type == 'book') {
            $model = Book::find($id);
            
            $media = Media::where('model_type', 'App\Models\Book')->where('name', Config::get('main.book_image_path'))->where('model_id', $id)->first();
            
            if($media == null) {
                return null;
            }
            return Storage::disk('public')->url($media->name . $model->picture);
        }  elseif($type == 'room') {
            $model = Room::find($id);
            
            $media = Media::where('model_type', 'App\Models\Room')->where('name', Config::get('main.room_image_path'))->where('model_id', $id)->first();
            
            if($media == null) {
                return null;
            }
            return Storage::disk('public')->url($media->name . $model->picture);
        }  elseif($type == 'equipment') {
            $model = Equipment::find($id);
            
            $media = Media::where('model_type', 'App\Models\Equipment')->where('name', Config::get('main.equipment_image_path'))->where('model_id', $id)->first();
            
            if($media == null) {
                return null;
            }
            return Storage::disk('public')->url($media->name . $model->picture);
        }  
        elseif($type == 'main' || $type == 'logo') {
            $model = Media::find($id);
            // $media = Media::where('model_type','App\Models\Media')->where('model_id',$id)->first();
            return Storage::disk('public')->url($model->name . $model->file_name);
        }

    }
}
?>