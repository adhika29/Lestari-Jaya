<?php

namespace App\Http\Controllers;

use App\Models\SugarOutput;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\DB;

class SugarOutputController extends Controller
{
    public function index(Request $request)
    {
        $query = SugarOutput::latest();
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('nama_pembeli', 'like', '%' . $request->search . '%');
        }
        
        // Date range filter
        if ($request->has('tanggal_dari') && $request->tanggal_dari) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        
        if ($request->has('tanggal_sampai') && $request->tanggal_sampai) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }
        
        // Filter berdasarkan bulan dan tahun jika ada
        if ($request->has('bulan') && $request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        
        if ($request->has('tahun') && $request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        }
        
        // Filter berdasarkan nama pembeli
        if ($request->has('pembeli') && !empty($request->pembeli)) {
            $query->whereIn('nama_pembeli', $request->pembeli);
        }
        
        $sugarOutputs = $query->paginate(10);
        
        // Data pembeli untuk filter
        $pembeliData = SugarOutput::select('nama_pembeli')
            ->distinct()
            ->orderBy('nama_pembeli')
            ->get();
        
        // Hitung total sak dan total bobot
        $totalSak = SugarOutput::sum('sak');
        $totalBobot = SugarOutput::sum('bobot');
        $totalHarga = SugarOutput::sum('total_harga');
        
        // Data untuk chart (contoh: data per bulan)
        $chartData = SugarOutput::select(
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw('SUM(sak) as total_sak')
        )
        ->whereYear('tanggal', date('Y'))
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();
        
        // Hitung total sak minggu ini
        $currentWeekSakTotal = SugarOutput::where('tanggal', '>=', now()->startOfWeek())
                                         ->where('tanggal', '<=', now()->endOfWeek())
                                         ->sum('sak') ?: 0;
        
        // Hitung total sak minggu lalu
        $lastWeekSakTotal = SugarOutput::where('tanggal', '>=', now()->subWeek()->startOfWeek())
                                      ->where('tanggal', '<=', now()->subWeek()->endOfWeek())
                                      ->sum('sak') ?: 0;
        
        // Hitung perubahan sak
        $sakChange = $lastWeekSakTotal > 0 ? (($currentWeekSakTotal - $lastWeekSakTotal) / $lastWeekSakTotal) * 100 : 0;
        $sakChangeText = $sakChange >= 0 ? '+ ' . number_format(abs($sakChange), 1) : '- ' . number_format(abs($sakChange), 1);
        $sakChangeClass = $sakChange >= 0 ? 'text-green-600' : 'text-red-600';
        
        // Hitung rata-rata bobot minggu ini
        $currentWeekBobotAvg = SugarOutput::where('tanggal', '>=', now()->startOfWeek())
                                         ->where('tanggal', '<=', now()->endOfWeek())
                                         ->avg('bobot') ?: 0;
        
        // Hitung rata-rata bobot minggu lalu
        $lastWeekBobotAvg = SugarOutput::where('tanggal', '>=', now()->subWeek()->startOfWeek())
                                      ->where('tanggal', '<=', now()->subWeek()->endOfWeek())
                                      ->avg('bobot') ?: 0;
        
        // Hitung perubahan bobot
        $bobotChange = $lastWeekBobotAvg > 0 ? (($currentWeekBobotAvg - $lastWeekBobotAvg) / $lastWeekBobotAvg) * 100 : 0;
        $bobotChangeText = $bobotChange >= 0 ? '+ ' . number_format(abs($bobotChange), 1) : '- ' . number_format(abs($bobotChange), 1);
        $bobotChangeClass = $bobotChange >= 0 ? 'text-green-600' : 'text-red-600';
        
        // Data untuk chart sak keluar (15 hari terakhir)
        $sakChartData = SugarOutput::select('tanggal', 'sak')
            ->orderBy('tanggal', 'desc')
            ->take(15)
            ->get()
            ->sortBy('tanggal')
            ->values();
        
        // Data untuk chart pembeli (total sak per pembeli)
        $pembeliChartData = SugarOutput::select('nama_pembeli', DB::raw('SUM(sak) as total_sak'))
            ->groupBy('nama_pembeli')
            ->orderBy('total_sak', 'desc')
            ->get();
        
        return view('sugar-output.index', compact(
            'sugarOutputs', 
            'totalSak', 
            'totalBobot',
            'totalHarga',
            'chartData',
            'sakChartData',
            'pembeliChartData',
            'sakChangeText',
            'sakChangeClass',
            'bobotChangeText',
            'bobotChangeClass',
            'pembeliData'
        ));
    }

    public function create()
    {
        // Ambil data pembeli yang sudah ada untuk dropdown
        $pembeliData = SugarOutput::select('nama_pembeli')
            ->distinct()
            ->orderBy('nama_pembeli')
            ->pluck('nama_pembeli')
            ->toArray();
            
        return view('sugar-output.create', compact('pembeliData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_pembeli' => 'required|string|max:255',
            'sak' => 'required|integer|min:1',
            'harga_per_kg' => 'required|integer|min:1',
        ]);

        // Hitung bobot otomatis: 1 sak = 50kg
        $bobot = $request->sak * 50;
        $totalHarga = $bobot * $request->harga_per_kg;

        SugarOutput::create([
            'tanggal' => $request->tanggal,
            'nama_pembeli' => $request->nama_pembeli,
            'sak' => $request->sak,
            'bobot' => $bobot,
            'harga_per_kg' => $request->harga_per_kg,
            'total_harga' => $totalHarga,
        ]);

        return redirect()->route('sugar-output.index')
            ->with('success', 'Data gula keluar berhasil ditambahkan!');
    }

    public function edit(SugarOutput $sugarOutput)
    {
        // Ambil data pembeli yang sudah ada untuk dropdown
        $pembeliData = SugarOutput::select('nama_pembeli')
            ->distinct()
            ->orderBy('nama_pembeli')
            ->pluck('nama_pembeli')
            ->toArray();
            
        return view('sugar-output.edit', compact('sugarOutput', 'pembeliData'));
    }

    public function update(Request $request, SugarOutput $sugarOutput)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_pembeli' => 'required|string|max:255',
            'sak' => 'required|integer|min:1',
            'harga_per_kg' => 'required|integer|min:1',
        ]);

        // Hitung bobot otomatis: 1 sak = 50kg
        $bobot = $request->sak * 50;
        $totalHarga = $bobot * $request->harga_per_kg;

        $sugarOutput->update([
            'tanggal' => $request->tanggal,
            'nama_pembeli' => $request->nama_pembeli,
            'sak' => $request->sak,
            'bobot' => $bobot,
            'harga_per_kg' => $request->harga_per_kg,
            'total_harga' => $totalHarga,
        ]);

        return redirect()->route('sugar-output.index')
            ->with('success', 'Data gula keluar berhasil diperbarui!');
    }

    public function destroy(SugarOutput $sugarOutput)
    {
        $sugarOutput->delete();

        return redirect()->route('sugar-output.index')
            ->with('success', 'Data gula keluar berhasil dihapus!');
    }
    
    // Tambahkan method ini untuk autocomplete buyers
    public function getBuyers(Request $request)
    {
        $search = $request->get('q');
        $buyers = SugarOutput::where('nama_pembeli', 'like', '%' . $search . '%')
                            ->distinct()
                            ->pluck('nama_pembeli')
                            ->take(10);
        
        return response()->json($buyers);
    }
    
    public function exportPdf(Request $request)
    {
        $query = SugarOutput::query();
        
        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pembeli', 'like', '%' . $search . '%')
                  ->orWhere('sak', 'like', '%' . $search . '%')
                  ->orWhere('bobot', 'like', '%' . $search . '%')
                  ->orWhere('harga_per_kg', 'like', '%' . $search . '%')
                  ->orWhere('total_harga', 'like', '%' . $search . '%');
            });
        }
        
        // Filter berdasarkan tanggal
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        
        if ($request->has('tanggal_selesai') && $request->tanggal_selesai) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }
        
        // Filter berdasarkan pembeli
        if ($request->has('nama_pembeli') && $request->nama_pembeli) {
            $query->where('nama_pembeli', $request->nama_pembeli);
        }
        
        // Filter berdasarkan bulan
        if ($request->has('bulan') && $request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        
        // Filter berdasarkan tahun
        if ($request->has('tahun') && $request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        }
        
        $outputs = $query->orderBy('tanggal', 'desc')->get();
        
        $pdf = PDF::loadView('sugar-output.pdf', compact('outputs'));
        return $pdf->download('laporan-gula-keluar.pdf');
    }
}