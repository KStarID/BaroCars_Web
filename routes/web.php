<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Firebase\CarsController;
use App\Http\Controllers\UploadImageController;
use App\Http\Controllers\StorageController;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('user', 'fireauth');

Route::get('/email/verify', [App\Http\Controllers\Auth\ResetController::class, 'verify_email'])->name('verify')->middleware('fireauth');

Route::post('login/{provider}/callback', 'Auth\LoginController@handleCallback');

Route::get('/home/iamadmin', [App\Http\Controllers\MakeAdminController::class, 'index'])->middleware('user', 'fireauth');

Route::resource('/home/profile', App\Http\Controllers\Auth\ProfileController::class)->middleware('user', 'fireauth');

Route::resource('/home/admin', App\Http\Controllers\Auth\AdminController::class)->middleware(['user', 'fireauth']);

Route::middleware(['auth', 'fireauth'])->group(function () {
    Route::get('/home/dashboard', function () {
        return view('adminpage.dashboard');
    })->name('dashboard');

    Route::get('/home/cars', [App\Http\Controllers\Firebase\CarsController::class, 'index'])
        ->name('cars');

    Route::get('/home/cars/add_cars', function () {
        return view('adminpage\add_cars');
    })->name('add_cars');

    Route::get('/home/cars/view_cars', function () {
        return view('adminpage\view_cars');
    })->name('view_cars');

    Route::post('/home/cars/add_cars', [App\Http\Controllers\Firebase\CarsController::class, 'store'])->name('add_cars');
});

Route::resource('/password/reset', App\Http\Controllers\Auth\ResetController::class);
