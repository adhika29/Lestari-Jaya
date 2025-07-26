<?php

namespace App\Http\Controllers;

use App\Models\BiayaKonsumsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BiayaKonsumsiController extends Controller
{
    public function index(Request $request)
    {
        $query = BiayaKonsumsi::latest();
        
        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search) {
            $query->where('keterangan', 'like', '%' . $request->search . '%');
        }
        
        // Filter berdasarkan bulan dan tahun
        if ($request->has('bulan') && $request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        
        if ($request->has('tahun') && $request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        }
        
        // Filter berdasarkan rentang tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        
        if ($request->has('tanggal_sampai') && $request->tanggal_sampai) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }
        
        // Filter berdasarkan keterangan
        if ($request->has('keterangan') && !empty($request->keterangan)) {
            $query->whereIn('keterangan', $request->keterangan);
        }
        
        // Buat query terpisah untuk chart dengan filter yang sama
        $chartQuery = BiayaKonsumsi::query();
        
        // Terapkan filter yang sama untuk chart
        if ($request->has('search') && $request->search) {
            $chartQuery->where('keterangan', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('bulan') && $request->bulan) {
            $chartQuery->whereMonth('tanggal', $request->bulan);
        }
        
        if ($request->has('tahun') && $request->tahun) {
            $chartQuery->whereYear('tanggal', $request->tahun);
        }
        
        if ($request->has('tanggal_dari') && $request->tanggal_dari) {
            $chartQuery->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        
        if ($request->has('tanggal_sampai') && $request->tanggal_sampai) {
            $chartQuery->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }
        
        if ($request->has('keterangan') && !empty($request->keterangan)) {
            $chartQuery->whereIn('keterangan', $request->keterangan);
        }
        
        // Data untuk chart berdasarkan tanggal dengan filter
        $chartDataTanggal = (clone $chartQuery)->select(
            DB::raw('DATE_FORMAT(tanggal, "%d/%m/%Y") as tanggal_formatted'),
            DB::raw('SUM(total_harga) as total')
        )
            ->groupBy('tanggal_formatted')
            ->orderBy('tanggal')
            ->limit(10)
            ->get();
        
        // Data untuk chart berdasarkan keterangan dengan filter
        $chartDataKeterangan = (clone $chartQuery)->select(
            'keterangan',
            DB::raw('SUM(total_harga) as total')
        )
            ->groupBy('keterangan')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // Calculate total overall price dengan filter
        $totalKeseluruhanHarga = (clone $chartQuery)->sum('total_harga');
        
        $biayaKonsumsi = $query->paginate(10);
        
        // Ambil daftar keterangan unik untuk dropdown
        $keteranganList = BiayaKonsumsi::select('keterangan')
                            ->distinct()
                            ->orderBy('keterangan')
                            ->pluck('keterangan');
        
        return view('biaya-pengeluaran.index', compact(
            'biayaKonsumsi',
            'keteranganList',
            'chartDataTanggal',
            'chartDataKeterangan',
            'totalKeseluruhanHarga'
        ));
    }

    public function create()
    {
        // Ambil daftar keterangan unik untuk dropdown
        $keteranganList = BiayaKonsumsi::select('keterangan')
                            ->distinct()
                            ->orderBy('keterangan')
                            ->pluck('keterangan');
                            
        return view('biaya-pengeluaran.create', compact('keteranganList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'volume' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
        ]);
        
        // Hitung total harga
        $totalHarga = $request->volume * $request->harga;
        
        BiayaKonsumsi::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'volume' => $request->volume,
            'satuan' => $request->satuan,
            'harga' => $request->harga,
            'total_harga' => $totalHarga
        ]);
        
        return redirect()->route('biaya-konsumsi.index')
            ->with('success', 'Data biaya konsumsi berhasil ditambahkan');
    }

    public function edit(BiayaKonsumsi $biayaKonsumsi)
    {
        // Ambil daftar keterangan unik untuk dropdown
        $keteranganList = BiayaKonsumsi::select('keterangan')
                            ->distinct()
                            ->orderBy('keterangan')
                            ->pluck('keterangan');
                            
        return view('biaya-pengeluaran.edit', compact('biayaKonsumsi', 'keteranganList'));
    }

    public function update(Request $request, BiayaKonsumsi $biayaKonsumsi)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'volume' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
        ]);
        
        // Hitung total harga
        $totalHarga = $request->volume * $request->harga;
        
        $biayaKonsumsi->update([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'volume' => $request->volume,
            'satuan' => $request->satuan,
            'harga' => $request->harga,
            'total_harga' => $totalHarga
        ]);
        
        return redirect()->route('biaya-konsumsi.index')
            ->with('success', 'Data biaya konsumsi berhasil diperbarui');
    }

    public function destroy(BiayaKonsumsi $biayaKonsumsi)
    {
        $biayaKonsumsi->delete();
        
        return redirect()->route('biaya-konsumsi.index')
            ->with('success', 'Data biaya konsumsi berhasil dihapus');
    }

    public function exportPdf(Request $request)
    {
        $query = BiayaKonsumsi::latest();
        
        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search) {
            $query->where('keterangan', 'like', '%' . $request->search . '%');
        }
        
        // Filter berdasarkan bulan dan tahun
        if ($request->has('bulan') && $request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        
        if ($request->has('tahun') && $request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        }
        
        // Filter berdasarkan rentang tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        
        if ($request->has('tanggal_sampai') && $request->tanggal_sampai) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }
        
        // Filter berdasarkan keterangan
        if ($request->has('keterangan') && !empty($request->keterangan)) {
            $query->whereIn('keterangan', $request->keterangan);
        }
        
        $biayaKonsumsi = $query->get();
        
        $pdf = PDF::loadView('biaya-pengeluaran.pdf', compact('biayaKonsumsi'));
        return $pdf->download('laporan-biaya-konsumsi.pdf');
    }

    public function getKeterangan(Request $request)
    {
        $query = BiayaKonsumsi::select('keterangan')
            ->distinct()
            ->orderBy('keterangan');
        
        if ($request->has('q') && $request->q) {
            $query->where('keterangan', 'like', '%' . $request->q . '%');
        }
        
        $keterangan = $query->pluck('keterangan');
        
        return response()->json($keterangan);
    }
}