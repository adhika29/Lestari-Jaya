<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/karyawan/count', function (\Illuminate\Http\Request $request) {
    $tanggal = $request->query('tanggal');
    
    if (!$tanggal) {
        return response()->json(['error' => 'Tanggal diperlukan'], 400);
    }
    
    // Ubah query untuk hanya menghitung karyawan yang bergabung pada tanggal yang dipilih
    $jumlahKaryawan = \App\Models\Karyawan::whereDate('tanggal_bergabung', $tanggal)
                                         ->where('status_aktif', true)
                                         ->count();
    
    // Jika tidak ada karyawan aktif, set minimal 1
    if ($jumlahKaryawan < 1) {
        $jumlahKaryawan = 1;
    }
    
    return response()->json(['jumlah_karyawan' => $jumlahKaryawan]);
});
