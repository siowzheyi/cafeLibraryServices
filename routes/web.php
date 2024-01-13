<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
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
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/blog/create/post', [\App\Http\Controllers\BlogPostController::class, 'create']); 
Route::get('/login/index', [\App\Http\Controllers\API\UserController::class, 'indexLogin']); 
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post'); 
Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
Route::get('cafe/dashboard', [CafeController::class, 'dashboard'])->name('cafe.dashboard');
Route::get('library/dashboard', [LibraryController::class, 'dashboard'])->name('library.dashboard');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');
// Route::get('announcement/index', [AnnouncementController::class, 'index'])->name('announcement.index');

// Route::get('cafe/staff/index', [UserController::class, 'index'])->name('cafe.staff.index');
Route::get('user/getUserDatatable', [UserController::class, 'getUserDatatable'])->name('user.getUserDatatable');
Route::get('table/getTableDatatable', [TableController::class, 'getTableDatatable'])->name('table.getTableDatatable');
Route::get('room/getRoomDatatable', [RoomController::class, 'getRoomDatatable'])->name('room.getRoomDatatable');
Route::get('equipment/getEquipmentDatatable', [EquipmentController::class, 'getEquipmentDatatable'])->name('equipment.getEquipmentDatatable');
Route::get('book/getBookDatatable', [BookController::class, 'getBookDatatable'])->name('book.getBookDatatable');
Route::get('beverage/getBeverageDatatable', [BeverageController::class, 'getBeverageDatatable'])->name('beverage.getBeverageDatatable');

Route::resource('user', UserController::class);

Route::middleware(['staffauthentication'])->group(function () {

    Route::prefix('staff')->group(function () {
        
        Route::resource('library', LibraryController::class);
        Route::resource('cafe', CafeController::class);
        
        Route::middleware(['ensurestaffhaslibrarycafeid'])->group(function () {
            
            Route::resource('announcement', AnnouncementController::class);
            Route::resource('item', ItemController::class);
            Route::resource('table', TableController::class);
            Route::resource('beverage', BeverageController::class);
            Route::resource('book', BookController::class);
            Route::resource('equipment', EquipmentController::class);
            Route::resource('room', RoomController::class);
            Route::post('importbook', [BookController::class, 'importBook'])->name('importbook');
            
            Route::prefix('report')->group(function () {
                Route::get('detail_sales_report', [OrderController::class, 'detailSalesReport'])->name('detail_sales_report');
                Route::get('daily_sales_report', [OrderController::class, 'dailySalesReport'])->name('daily_sales_report');

            });


        });
    });
});
