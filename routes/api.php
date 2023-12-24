<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\LibraryController;
use App\Http\Controllers\API\CafeController;
use App\Http\Controllers\API\AnnouncementController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\TableController;
use App\Http\Controllers\API\BeverageController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\EquipmentController;
use App\Http\Controllers\API\RoomController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [UserController::class, 'login'])->name('login');
Route::post('register', [UserController::class, 'register'])->name('register');

Route::middleware('auth:api')->group(function () {
    Route::resource('user', UserController::class, array("as" => "api"));
    Route::get('item_category', [ItemController::class, 'itemCategory'])->name('item_category');
    Route::get('beverage_listing', [BeverageController::class, 'beverageListing'])->name('beverage_listing');
    Route::get('table_listing', [TableController::class, 'tableListing'])->name('table_listing');
    Route::get('announcement_listing', [AnnouncementController::class, 'announcementListing'])->name('announcement_listing');
    Route::get('book_listing', [BookController::class, 'bookListing'])->name('book_listing');
    Route::get('equipment_listing', [EquipmentController::class, 'equipmentListing'])->name('equipment_listing');
    Route::get('room_listing', [RoomController::class, 'roomListing'])->name('room_listing');


    Route::middleware(['staffauthentication'])->group(function () {

            Route::prefix('staff')->group(function () {
                
                Route::resource('library', LibraryController::class, array("as" => "api"));
                Route::resource('cafe', CafeController::class, array("as" => "api"));

                Route::middleware(['ensurestaffhaslibrarycafeid'])->group(function () {

                    Route::resource('announcement', AnnouncementController::class, array("as" => "api"));
                    Route::resource('item', ItemController::class, array("as" => "api"));
                    Route::resource('table', TableController::class, array("as" => "api"));
                    Route::resource('beverage', BeverageController::class, array("as" => "api"));
                    Route::resource('book', BookController::class, array("as" => "api"));
                    Route::resource('equipment', EquipmentController::class, array("as" => "api"));
                    Route::resource('room', RoomController::class, array("as" => "api"));


                });
            });
    });

});

