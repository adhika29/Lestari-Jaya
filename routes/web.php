<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SugarCaneShipmentController;
use App\Http\Controllers\SugarInputController;
use App\Http\Controllers\SugarOutputController;
use App\Http\Controllers\BiayaKonsumsiController; // Tambahkan baris ini
use App\Http\Controllers\BiayaOperasionalController; // Tambahkan baris ini untuk BiayaOperasional
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\GajiKaryawanController;
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
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Route untuk export PDF (HARUS didefinisikan SEBELUM route resource)
    Route::get('/sugar-cane/export-pdf', [SugarCaneShipmentController::class, 'exportPdf'])->name('sugar-cane.export-pdf');
    Route::get('/sugar-input/export-pdf', [SugarInputController::class, 'exportPdf'])->name('sugar-input.export-pdf');
    
    // Definisi resource untuk sugar-cane
    Route::resource('sugar-cane', SugarCaneShipmentController::class);
    Route::resource('sugar-input', SugarInputController::class);
    Route::resource('sugar-output', SugarOutputController::class);
    
    // Tambahkan route untuk biaya-konsumsi
    Route::resource('biaya-konsumsi', BiayaKonsumsiController::class);
    Route::resource('biaya-operasional', BiayaOperasionalController::class);
    
    // Tambahkan route untuk karyawan
    Route::resource('karyawan', KaryawanController::class);
    Route::resource('gaji-karyawan', GajiKaryawanController::class);
}); // Close middleware group and Route::group