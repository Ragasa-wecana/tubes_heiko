<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
    //return view('welcome');
    //diarahkan ke login customer
    return view('login');
});

// login customer
Route::get('/depan', [App\Http\Controllers\KeranjangController::class, 'daftarbarang'])
     ->middleware(\App\Http\Middleware\CustomerMiddleware::class)
     ->name('depan');
Route::get('/login', function () {
    return view('login');
});

// tambahan route untuk proses login
use Illuminate\Http\Request;
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
// untuk ubah password
Route::get('/ubahpassword', [App\Http\Controllers\AuthController::class, 'ubahpassword'])
    ->middleware(\App\Http\Middleware\CustomerMiddleware::class)
    ->name('ubahpassword');
Route::post('/prosesubahpassword', [App\Http\Controllers\AuthController::class, 'prosesubahpassword'])
    ->middleware(\App\Http\Middleware\CustomerMiddleware::class);