<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BiayaOperasional;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BiayaOperasionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = BiayaOperasional::query();
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('keterangan', 'like', "%{$search}%");
        }
        
        // Filter by month
        if ($request->has('bulan') && $request->bulan != '') {
            $query->whereMonth('tanggal', $request->bulan);
        }
        
        // Filter by year
        if ($request->has('tahun') && $request->tahun != '') {
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
        $chartQuery = BiayaOperasional::query();
        
        // Terapkan filter yang sama untuk chart
        if ($request->has('search')) {
            $search = $request->search;
            $chartQuery->where('keterangan', 'like', "%{$search}%");
        }
        
        if ($request->has('bulan') && $request->bulan != '') {
            $chartQuery->whereMonth('tanggal', $request->bulan);
        }
        
        if ($request->has('tahun') && $request->tahun != '') {
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
        
        $biayaOperasional = $query->paginate(10);
        
        // Ambil daftar keterangan unik untuk dropdown
        $keteranganList = BiayaOperasional::select('keterangan')
                            ->distinct()
                            ->orderBy('keterangan')
                            ->pluck('keterangan');
        
        return view('biaya-pengeluaran.operasional.index', compact(
            'biayaOperasional',
            'keteranganList', 
            'chartDataTanggal',
            'chartDataKeterangan',
            'totalKeseluruhanHarga'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Ambil daftar keterangan unik untuk dropdown
        $keteranganList = BiayaOperasional::select('keterangan')
                            ->distinct()
                            ->orderBy('keterangan')
                            ->pluck('keterangan');
                        
        return view('biaya-pengeluaran.operasional.create', compact('keteranganList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'volume' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ]);
        
        // Calculate total price
        $totalHarga = $request->volume * $request->harga;
        
        BiayaOperasional::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'volume' => $request->volume,
            'satuan' => $request->satuan,
            'harga' => $request->harga,
            'total_harga' => $totalHarga,
        ]);
        
        return redirect()->route('biaya-operasional.index')
            ->with('success', 'Data biaya operasional berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $biayaOperasional = BiayaOperasional::findOrFail($id);
        
        // Ambil daftar keterangan unik untuk dropdown
        $keteranganList = BiayaOperasional::select('keterangan')
                            ->distinct()
                            ->orderBy('keterangan')
                            ->pluck('keterangan');
                        
        return view('biaya-pengeluaran.operasional.edit', compact('biayaOperasional', 'keteranganList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'volume' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ]);
        
        $biayaOperasional = BiayaOperasional::findOrFail($id);
        
        // Calculate total price
        $totalHarga = $request->volume * $request->harga;
        
        $biayaOperasional->update([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'volume' => $request->volume,
            'satuan' => $request->satuan,
            'harga' => $request->harga,
            'total_harga' => $totalHarga,
        ]);
        
        return redirect()->route('biaya-operasional.index')
            ->with('success', 'Data biaya operasional berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $biayaOperasional = BiayaOperasional::findOrFail($id);
        $biayaOperasional->delete();
        
        return redirect()->route('biaya-operasional.index')
            ->with('success', 'Data biaya operasional berhasil dihapus.');
    }
    
    /**
     * Export data to PDF
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $query = BiayaOperasional::query();
        
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
        
        $biayaOperasional = $query->get();
        
        $pdf = PDF::loadView('biaya-pengeluaran.operasional.pdf', compact('biayaOperasional'));
        return $pdf->download('laporan-biaya-operasional.pdf');
    }

    public function getKeterangan(Request $request)
    {
        $query = BiayaOperasional::select('keterangan')
            ->distinct()
            ->orderBy('keterangan');
        
        if ($request->has('q') && $request->q) {
            $query->where('keterangan', 'like', '%' . $request->q . '%');
        }
        
        $keterangan = $query->pluck('keterangan');
        
        return response()->json($keterangan);
    }
}
