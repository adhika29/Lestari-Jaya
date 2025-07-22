<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SugarCaneShipment;
use App\Models\SugarInput;
use App\Models\SugarOutput;
use App\Models\BiayaKonsumsi;
use App\Models\BiayaOperasional;
use App\Models\Karyawan;
use App\Models\GajiKaryawan;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil aktivitas terbaru dari semua model
        $recentSugarCanes = SugarCaneShipment::latest()->take(15)->get()->map(function ($item) {
            return [
                'type' => 'sugar_cane',
                'title' => 'Pengiriman tebu dari ' . $item->nama_petani,
                'created_at' => $item->created_at,
                'icon' => 'ph-fill ph-tree',
                'color' => 'green',
                'data' => $item
            ];
        });

        $recentSugarInputs = SugarInput::latest()->take(15)->get()->map(function ($item) {
            return [
                'type' => 'sugar_input',
                'title' => 'Gula masuk ' . $item->sak . ' sak',
                'created_at' => $item->created_at,
                'icon' => 'ph-fill ph-arrow-down-right',
                'color' => 'blue',
                'data' => $item
            ];
        });

        $recentSugarOutputs = SugarOutput::latest()->take(15)->get()->map(function ($item) {
            return [
                'type' => 'sugar_output',
                'title' => 'Gula keluar ' . $item->sak . ' sak untuk ' . $item->nama_pembeli,
                'created_at' => $item->created_at,
                'icon' => 'ph-fill ph-arrow-up-right',
                'color' => 'red',
                'data' => $item
            ];
        });

        $recentBiayaKonsumsi = BiayaKonsumsi::latest()->take(15)->get()->map(function ($item) {
            return [
                'type' => 'biaya_konsumsi',
                'title' => 'Biaya konsumsi: ' . $item->nama_barang . ' - Rp ' . number_format($item->total_harga, 0, ',', '.'),
                'created_at' => $item->created_at,
                'icon' => 'ph-fill ph-shopping-cart',
                'color' => 'yellow',
                'data' => $item
            ];
        });

        $recentBiayaOperasional = BiayaOperasional::latest()->take(15)->get()->map(function ($item) {
            return [
                'type' => 'biaya_operasional',
                'title' => 'Biaya operasional: ' . $item->nama_barang . ' - Rp ' . number_format($item->total_harga, 0, ',', '.'),
                'created_at' => $item->created_at,
                'icon' => 'ph-fill ph-gear',
                'color' => 'purple',
                'data' => $item
            ];
        });

        $recentKaryawan = Karyawan::latest()->take(15)->get()->map(function ($item) {
            return [
                'type' => 'karyawan',
                'title' => 'Data karyawan baru: ' . $item->nama . ' (' . $item->jabatan . ')',
                'created_at' => $item->created_at,
                'icon' => 'ph-fill ph-user',
                'color' => 'indigo',
                'data' => $item
            ];
        });

        $recentGajiKaryawan = GajiKaryawan::with('karyawan')->latest()->take(15)->get()->map(function ($item) {
            // Since karyawan is a many-to-many relationship, we need to handle the collection
            $karyawanNames = $item->karyawan->pluck('nama')->join(', ');
            $karyawanNama = $karyawanNames ?: 'Tidak ada karyawan';
            return [
                'type' => 'gaji_karyawan',
                'title' => 'Gaji karyawan: ' . $karyawanNama . ' - Rp ' . number_format($item->total_gaji, 0, ',', '.'),
                'created_at' => $item->created_at,
                'icon' => 'ph-fill ph-money',
                'color' => 'orange',
                'data' => $item
            ];
        });

        // Gabungkan semua aktivitas dan urutkan berdasarkan waktu terbaru
        $recentActivities = collect()
            ->merge($recentSugarCanes)
            ->merge($recentSugarInputs)
            ->merge($recentSugarOutputs)
            ->merge($recentBiayaKonsumsi)
            ->merge($recentBiayaOperasional)
            ->merge($recentKaryawan)
            ->merge($recentGajiKaryawan)
            ->sortByDesc('created_at')
            ->take(15); // Ambil 15 aktivitas terbaru

        return view('dashboard', compact('recentActivities'));
    }
}