<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

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

// Welcome Default
Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/profile', ProfileController::class)->name('profile');
    Route::resource('employees', EmployeeController::class);
    Route::get('download-file/{employeeId}', [EmployeeController::class, 'downloadFile'])->name('employees.downloadFile');
});

// Meletakkan File pada Local Disk
Route::get('/local-disk', function () {
    Storage::disk('local')->put('local-example.txt', 'This is local example content');
    return asset('storage/local-example.txt');
});

// Meletakkan File pada Public Disk
Route::get('/public-disk', function () {
    Storage::disk('public')->put('public-example.txt', 'This is public example content');
    return asset('storage/public-example.txt');
});

// Menampilkan Isi File Local
Route::get('/retrieve-local-file', function () {
    if (Storage::disk('local')->exists('local-example.txt')) {
        $contents = Storage::disk('local')->get('local-example.txt');
    } else {
        $contents = 'File does not exist';
    }

    return $contents;
});

// Menampilkan Isi File Public
Route::get('/retrieve-public-file', function () {
    if (Storage::disk('public')->exists('public-example.txt')) {
        $contents = Storage::disk('public')->get('public-example.txt');
    } else {
        $contents = 'File does not exist';
    }

    return $contents;
});

// Mendownload File Local
Route::get('/download-local-file', function () {
    return Storage::download('local-example.txt', 'local file');
});

// Mendownload File Public
Route::get('/download-public-file', function () {
    return Storage::download('public/public-example.txt', 'public file');
});

// Menampilkan URL
Route::get('/file-url', function () {
    $url = Storage::url('local-example.txt');
    return $url;
});
// Menampilkan Size
Route::get('/file-size', function () {
    $size = Storage::size('local-example.txt');
    return $size;
});

// Menampilkan Path
Route::get('/file-path', function () {
    $path = Storage::path('local-example.txt');
    return $path;
});

//=== menampilkan file via form
Route::get('/upload-example', function () {
    return view('upload_example');
});

Route::post('/upload-example', function (Request $request) {
    // if ($request->hasFile('avatar')) {
    //     dd('test');
    // } else {
    //     dd('file not found');
    // }
    $path = $request->file('cv')->store('public');
    return $path;
})->name('upload-example');


//=== hapus file local
Route::get('/delete-local-file', function (Request $request) {
    Storage::disk('local')->delete('local-example.txt');
    return 'Deleted';
});

// ===menghapus file public
Route::get('/delete-public-file', function (Request $request) {
    Storage::disk('public')->delete('public-example.txt');
    return 'Deleted';
});

// server-side processing data tables
Route::get('getEmployees', [EmployeeController::class, 'getData'])->name('employees.getData');

// Route Export Excel
Route::get('exportExcel', [EmployeeController::class, 'exportExcel'])->name('employees.exportExcel');

// // Home Controller
// Route::get('/home', [HomeController::class, 'index'])->name('home');

// // Profile Controller
// Route::get('/profile', ProfileController::class)->name('profile');

// // Employee Controller
// Route::resource('employees', EmployeeController::class);


Route::post('/logout', [LoginController::class, 'logout'])->name('logout');