<?php

namespace App\Http\Controllers;

use App\Models\SugarInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SugarInputController extends Controller
{
    public function index(Request $request)
    {
        $query = SugarInput::latest();
        
        // Tambahkan fungsionalitas pencarian
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('sak', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('bobot', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhereDate('tanggal', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        
        // Filter berdasarkan tanggal awal dan akhir
        if ($request->has('tanggal_awal') && $request->tanggal_awal) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }
        
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }
        
        // Filter berdasarkan bulan dan tahun jika ada
        if ($request->has('bulan') && $request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        
        if ($request->has('tahun') && $request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        }
        
        $inputs = $query->paginate(10);
        
        // Hitung total sak dan total bobot
        $totalSak = SugarInput::sum('sak');
        $totalBobot = SugarInput::sum('bobot');
        
        // Hitung rata-rata sak minggu ini
        $currentWeekSakAvg = SugarInput::where('tanggal', '>=', now()->startOfWeek())
                                      ->where('tanggal', '<=', now()->endOfWeek())
                                      ->avg('sak') ?: 0;
        
        // Hitung rata-rata sak minggu lalu
        $lastWeekSakAvg = SugarInput::where('tanggal', '>=', now()->subWeek()->startOfWeek())
                                   ->where('tanggal', '<=', now()->subWeek()->endOfWeek())
                                   ->avg('sak') ?: 0;
        
        // Hitung perubahan sak
        $sakChange = $lastWeekSakAvg > 0 ? (($currentWeekSakAvg - $lastWeekSakAvg) / $lastWeekSakAvg) * 100 : 0;
        $sakChangeText = $sakChange >= 0 ? '+ ' . number_format(abs($sakChange), 1) : '- ' . number_format(abs($sakChange), 1);
        $sakChangeClass = $sakChange >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
        
        // Hitung rata-rata bobot minggu ini
        $currentWeekBobotAvg = SugarInput::where('tanggal', '>=', now()->startOfWeek())
                                         ->where('tanggal', '<=', now()->endOfWeek())
                                         ->avg('bobot') ?: 0;
        
        // Hitung rata-rata bobot minggu lalu
        $lastWeekBobotAvg = SugarInput::where('tanggal', '>=', now()->subWeek()->startOfWeek())
                                     ->where('tanggal', '<=', now()->subWeek()->endOfWeek())
                                     ->avg('bobot') ?: 0;
        
        // Hitung perubahan bobot
        $bobotChange = $lastWeekBobotAvg > 0 ? (($currentWeekBobotAvg - $lastWeekBobotAvg) / $lastWeekBobotAvg) * 100 : 0;
        $bobotChangeText = $bobotChange >= 0 ? '+ ' . number_format(abs($bobotChange), 1) : '- ' . number_format(abs($bobotChange), 1);
        $bobotChangeClass = $bobotChange >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
        
        // Data untuk chart gula masuk (15 hari terakhir)
        $rawData = SugarInput::select('tanggal', 'sak')->get();
        
        // Debugging - cek jumlah data yang tersedia
        \Log::info('Total SugarInput records: ' . $rawData->count());
        
        $chartData = SugarInput::select('tanggal', 'sak')
            ->orderBy('tanggal', 'desc') // Urutkan dari tanggal terbaru
            ->take(15) // Ambil 15 data terakhir
            ->get();
            
        // Debugging - cek data yang diambil
        \Log::info('Chart data count: ' . $chartData->count());
        
        $chartData = $chartData->sortBy('tanggal') // Urutkan kembali untuk tampilan chart dari kiri ke kanan
            ->values() // Reset indeks array
            ->map(function ($item) {
                return [
                    'tanggal' => $item->tanggal->format('d/m/Y'),
                    'sak' => $item->sak
                ];
            });
        
        return view('sugar-input.index', compact(
            'inputs', 
            'totalSak', 
            'totalBobot', 
            'chartData',
            'sakChangeText',
            'sakChangeClass',
            'bobotChangeText',
            'bobotChangeClass'
        ));
    }

    public function create()
    {
        return view('sugar-input.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'sak' => 'required|integer|min:1',
        ]);

        // Hitung bobot otomatis: 1 sak = 50 kg
        $bobot = $request->sak * 50;

        SugarInput::create([
            'tanggal' => $request->tanggal,
            'sak' => $request->sak,
            'bobot' => $bobot,
        ]);

        return redirect()->route('sugar-input.index')
            ->with('success', 'Data gula masuk berhasil ditambahkan!');
    }

    public function edit(SugarInput $sugarInput)
    {
        return view('sugar-input.edit', compact('sugarInput'));
    }

    public function update(Request $request, SugarInput $sugarInput)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'sak' => 'required|integer|min:1',
        ]);

        // Hitung bobot otomatis: 1 sak = 50 kg
        $bobot = $request->sak * 50;

        $sugarInput->update([
            'tanggal' => $request->tanggal,
            'sak' => $request->sak,
            'bobot' => $bobot,
        ]);

        return redirect()->route('sugar-input.index')
            ->with('success', 'Data gula masuk berhasil diperbarui!');
    }

    public function destroy(SugarInput $sugarInput)
    {
        $sugarInput->delete();

        return redirect()->route('sugar-input.index')
            ->with('success', 'Data gula masuk berhasil dihapus!');
    }

    // Tambahkan fungsi exportPdf di bawah fungsi destroy
    public function exportPdf(Request $request)
    {
        $query = SugarInput::latest();
        
        // Filter berdasarkan tanggal awal dan akhir
        if ($request->has('tanggal_awal') && $request->tanggal_awal) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }
        
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }
        
        // Filter berdasarkan bulan dan tahun
        if ($request->has('bulan') && $request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        
        if ($request->has('tahun') && $request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        }
        
        $inputs = $query->get();
        
        $pdf = PDF::loadView('sugar-input.pdf', compact('inputs'));
        return $pdf->download('laporan-gula-masuk.pdf');
    }
}