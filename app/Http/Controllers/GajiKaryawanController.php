<?php

namespace App\Http\Controllers;

use App\Models\GajiKaryawan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class GajiKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = GajiKaryawan::query();
        
        // Filter berdasarkan tanggal awal dan akhir
        if ($request->has('tanggal_awal') && $request->tanggal_awal) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }
        
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }
        
        // Filter berdasarkan karyawan
        if ($request->has('karyawan') && !empty($request->karyawan)) {
            $query->whereHas('karyawan', function($q) use ($request) {
                $q->whereIn('karyawan.id', $request->karyawan);
            });
        }
        
        // Filter berdasarkan bulan dan tahun
        if ($request->has('bulan') && $request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        
        if ($request->has('tahun') && $request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        }
        
        // Pencarian
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('sak', 'like', "%{$search}%")
                  ->orWhere('bobot_kg', 'like', "%{$search}%")
                  ->orWhere('jumlah_gula_ton', 'like', "%{$search}%")
                  ->orWhere('total_gaji', 'like', "%{$search}%");
            });
        }
        
        $gajiKaryawan = $query->with('karyawan')->orderBy('tanggal', 'desc')->paginate(10);
        
        // Hitung total keseluruhan gaji karyawan
        $totalKeseluruhanGaji = GajiKaryawan::when($request->has('tanggal_awal') && $request->tanggal_awal, function($q) use ($request) {
                return $q->whereDate('tanggal', '>=', $request->tanggal_awal);
            })
            ->when($request->has('tanggal_akhir') && $request->tanggal_akhir, function($q) use ($request) {
                return $q->whereDate('tanggal', '<=', $request->tanggal_akhir);
            })
            ->when($request->has('karyawan') && !empty($request->karyawan), function($q) use ($request) {
                return $q->whereHas('karyawan', function($subQ) use ($request) {
                    $subQ->whereIn('karyawan.id', $request->karyawan);
                });
            })
            ->when($request->has('bulan') && $request->bulan, function($q) use ($request) {
                return $q->whereMonth('tanggal', $request->bulan);
            })
            ->when($request->has('tahun') && $request->tahun, function($q) use ($request) {
                return $q->whereYear('tanggal', $request->tahun);
            })
            ->when($request->has('search') && $request->search, function($q) use ($request) {
                $search = $request->search;
                return $q->where(function($subQ) use ($search) {
                    $subQ->where('sak', 'like', "%{$search}%")
                        ->orWhere('bobot_kg', 'like', "%{$search}%")
                        ->orWhere('jumlah_gula_ton', 'like', "%{$search}%")
                        ->orWhere('total_gaji', 'like', "%{$search}%");
                });
            })
            ->sum('total_gaji');
        
        return view('gaji-karyawan.index', compact('gajiKaryawan', 'totalKeseluruhanGaji'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karyawan = Karyawan::where('status_aktif', true)->orderBy('nama')->get();
        return view('gaji-karyawan.create', compact('karyawan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'sak' => 'required|integer|min:1',
            'bobot_kg' => 'required|numeric|min:0',
            'gaji_per_ton' => 'required|numeric|min:0',
        ]);
        
        // Ambil semua karyawan aktif secara otomatis
        $karyawanAktif = Karyawan::where('status_aktif', true)->get();
        $karyawanIds = $karyawanAktif->pluck('id')->toArray();
        
        // Hitung jumlah karyawan
        $jumlahKaryawan = count($karyawanIds);
        
        // Jika tidak ada karyawan, set minimal 1 untuk menghindari division by zero
        if ($jumlahKaryawan < 1) {
            $jumlahKaryawan = 1;
        }
        
        // Hitung jumlah gula dalam ton
        $bobotKg = $request->bobot_kg;
        $jumlahGulaTon = $bobotKg / 1000; // Konversi kg ke ton
        
        // Hitung total gaji berdasarkan jumlah gula dan gaji per ton
        $gajiPerTon = $request->gaji_per_ton;
        $totalGaji = $jumlahGulaTon * $gajiPerTon;
        
        // Hitung gaji per karyawan
        $gajiPerKaryawan = $totalGaji / $jumlahKaryawan;
        
        // Buat data gaji karyawan
        $gajiKaryawan = GajiKaryawan::create([
            'tanggal' => $request->tanggal,
            'sak' => $request->sak,
            'bobot_kg' => $bobotKg,
            'jumlah_gula_ton' => $jumlahGulaTon,
            'gaji_per_ton' => $gajiPerTon,
            'total_gaji' => $totalGaji,
            'jumlah_karyawan' => $jumlahKaryawan,
            'gaji_per_karyawan' => $gajiPerKaryawan
        ]);
        
        // Attach karyawan ke gaji_karyawan
        $gajiKaryawan->karyawan()->attach($karyawanIds);
        
        return redirect()->route('gaji-karyawan.index')
                         ->with('success', 'Data gaji karyawan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GajiKaryawan $gajiKaryawan)
    {
        $karyawan = Karyawan::where('status_aktif', true)->orderBy('nama')->get();
        $selectedKaryawan = $gajiKaryawan->karyawan->pluck('id')->toArray();
        return view('gaji-karyawan.edit', compact('gajiKaryawan', 'karyawan', 'selectedKaryawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GajiKaryawan $gajiKaryawan)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'sak' => 'required|integer|min:1',
            'bobot_kg' => 'required|numeric|min:0',
            'gaji_per_ton' => 'required|numeric|min:0',
        ]);
        
        // Ambil semua karyawan aktif secara otomatis
        $karyawanAktif = Karyawan::where('status_aktif', true)->get();
        $karyawanIds = $karyawanAktif->pluck('id')->toArray();
        
        // Hitung jumlah karyawan
        $jumlahKaryawan = count($karyawanIds);
        
        // Jika tidak ada karyawan, set minimal 1 untuk menghindari division by zero
        if ($jumlahKaryawan < 1) {
            $jumlahKaryawan = 1;
        }
        
        // Hitung jumlah gula dalam ton
        $bobotKg = $request->bobot_kg;
        $jumlahGulaTon = $bobotKg / 1000; // Konversi kg ke ton
        
        // Hitung total gaji berdasarkan jumlah gula dan gaji per ton
        $gajiPerTon = $request->gaji_per_ton;
        $totalGaji = $jumlahGulaTon * $gajiPerTon;
        
        // Hitung gaji per karyawan
        $gajiPerKaryawan = $totalGaji / $jumlahKaryawan;
        
        // Update data gaji karyawan
        $gajiKaryawan->update([
            'tanggal' => $request->tanggal,
            'sak' => $request->sak,
            'bobot_kg' => $bobotKg,
            'jumlah_gula_ton' => $jumlahGulaTon,
            'gaji_per_ton' => $gajiPerTon,
            'total_gaji' => $totalGaji,
            'jumlah_karyawan' => $jumlahKaryawan,
            'gaji_per_karyawan' => $gajiPerKaryawan
        ]);
        
        // Sync karyawan
        $gajiKaryawan->karyawan()->sync($karyawanIds);
        
        return redirect()->route('gaji-karyawan.index')
                         ->with('success', 'Data gaji karyawan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GajiKaryawan $gajiKaryawan)
    {
        // Detach semua karyawan sebelum menghapus
        $gajiKaryawan->karyawan()->detach();
        $gajiKaryawan->delete();
        
        return redirect()->route('gaji-karyawan.index')
                         ->with('success', 'Data gaji karyawan berhasil dihapus!');
    }

    /**
     * Export data to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = GajiKaryawan::query();
        
        // Filter berdasarkan tanggal awal dan akhir
        if ($request->has('tanggal_awal') && $request->tanggal_awal) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }
        
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }
        
        // Filter berdasarkan karyawan
        if ($request->has('karyawan') && !empty($request->karyawan)) {
            $query->whereHas('karyawan', function($q) use ($request) {
                $q->whereIn('karyawan.id', $request->karyawan);
            });
        }
        
        // Filter berdasarkan bulan dan tahun
        if ($request->has('bulan') && $request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        
        if ($request->has('tahun') && $request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        }
        
        $gajiKaryawan = $query->with('karyawan')->orderBy('tanggal', 'desc')->get();
        
        $pdf = PDF::loadView('gaji-karyawan.pdf', compact('gajiKaryawan'));
        return $pdf->download('laporan-gaji-karyawan.pdf');
    }
}
