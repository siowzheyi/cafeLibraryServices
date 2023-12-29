<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/blog/create/post', [\App\Http\Controllers\BlogPostController::class, 'create']); 
Route::get('/login/index', [\App\Http\Controllers\API\UserController::class, 'indexLogin']); 
