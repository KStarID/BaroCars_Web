<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Firebase\CarsController;
use App\Http\Controllers\UploadImageController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\Firebase\NewsController;
use Illuminate\Support\Facades\Storage;

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

use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/news', [App\Http\Controllers\HomeController::class, 'news'])->name('news');

Route::get('/email/verify', [App\Http\Controllers\Auth\ResetController::class, 'verify_email'])->name('verify')->middleware('fireauth');

Route::post('login/{provider}/callback', 'Auth\LoginController@handleCallback');

Route::get('/home/iamadmin', [App\Http\Controllers\MakeAdminController::class, 'index'])->middleware('user', 'fireauth');

Route::resource('/home/profile', App\Http\Controllers\Auth\ProfileController::class)->middleware('user', 'fireauth');

Route::resource('/home/admin', App\Http\Controllers\Auth\AdminController::class)->middleware(['user', 'fireauth']);

Route::get('/search', [App\Http\Controllers\HomeController::class, 'search'])->name('search');

Route::middleware(['auth', 'fireauth'])->group(function () {

    Route::get('/home/cars', [App\Http\Controllers\Firebase\CarsController::class, 'index'])->name('cars');
    Route::get('/home/cars/add_cars', function () {
        return view('adminpage.add_cars');
    })->name('add_cars');
    Route::post('/home/cars/add_cars', [App\Http\Controllers\Firebase\CarsController::class, 'store'])->name('add_cars');

    Route::delete('/home/cars/delete_cars/{id}', [App\Http\Controllers\Firebase\CarsController::class, 'delete_cars'])->name('delete_cars');
    Route::get('/home/cars/edit_cars/{id}', [App\Http\Controllers\Firebase\CarsController::class, 'edit_cars'])->name('edit_cars');
    Route::put('/home/cars/update_cars/{id}', [App\Http\Controllers\Firebase\CarsController::class, 'update_cars'])->name('update_cars');

    Route::get('/home/upload_image/{id}', [App\Http\Controllers\Firebase\ImageController::class, 'index'])->name('upload_image');
    Route::put('/home/update_image/{id}', [App\Http\Controllers\Firebase\ImageController::class, 'storeimage'])->name('update_image');

    Route::get('/home/product_details/{id}', [App\Http\Controllers\Firebase\CarsController::class, 'show'])->name('product_details');

    Route::get('/home/news', [App\Http\Controllers\Firebase\NewsController::class, 'index'])->name('news_admin');
    Route::get('/home/add_news', function () {
        return view('news.add_news');
    })->name('add_news');
    Route::post('/home/add_news', [App\Http\Controllers\Firebase\NewsController::class, 'store'])->name('add_news');

    Route::delete('/home/delete_news/{id}', [App\Http\Controllers\Firebase\NewsController::class, 'delete_news'])->name('delete_news');
    Route::get('/home/edit_news/{id}', [App\Http\Controllers\Firebase\NewsController::class, 'edit_news'])->name('edit_news');
    Route::put('/home/update_news/{id}', [App\Http\Controllers\Firebase\NewsController::class, 'update_news'])->name('update_news');

    Route::get('/home/upload_image/{id}', [App\Http\Controllers\Firebase\ImageController::class, 'news'])->name('upload_image_news');
    Route::put('/home/update_image/{id}', [App\Http\Controllers\Firebase\ImageController::class, 'storenews'])->name('update_image_news');

    Route::get('/home/news_details/{id}', [App\Http\Controllers\Firebase\NewsController::class, 'show_news'])->name('product_details');
});

Route::resource('/password/reset', App\Http\Controllers\Auth\ResetController::class);
