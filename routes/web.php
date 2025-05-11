<?php

use Illuminate\Support\Facades\Route;

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
    // return view('welcome');
    // diarahkan ke login customer
    return view('login');
});


// login customer
Route::get('/depan', [App\Http\Controllers\KeranjangController::class, 'daftarbarang'])
     ->middleware('customer')
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
    ->middleware('customer')
    ->name('ubahpassword');
Route::post('/prosesubahpassword', [App\Http\Controllers\AuthController::class, 'prosesubahpassword'])
    ->middleware('customer')
;
// prosesubahpassword
// untuk contoh pdf
use App\Http\Controllers\PDFController;
Route::get('/contohpdf', [PDFController::class, 'contohpdf']);

// contoh simpan users ke pdf
Route::get('/downloadpdfuser', function () {
    return 'Latihan PDF';
})->name('downloadpdf.user');


// contoh mengirim email
// use Illuminate\Support\Facades\Mail; sudah ada di atas
use App\Mail\TesMail;

Route::get('/kirim-email', function () {
    $nama = 'Bambang';

    Mail::to('bams@gmail.com')->send(new TesMail($nama));

    return 'Email berhasil dikirim ke Mailtrap!';
});
// proses pengiriman email
use App\Http\Controllers\PengirimanEmailController;
Route::get('/proses_kirim_email_pembayaran', [PengirimanEmailController::class, 'proses_kirim_email_pembayaran']);
