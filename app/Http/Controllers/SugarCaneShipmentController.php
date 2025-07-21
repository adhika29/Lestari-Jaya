<?php

namespace App\Http\Controllers;

use App\Models\SugarCaneShipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SugarCaneShipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = SugarCaneShipment::latest();
        
        // Filter berdasarkan tanggal awal dan akhir
        if ($request->has('tanggal_awal') && $request->tanggal_awal) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }
        
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }
        
        // Filter berdasarkan jenis tebu
        if ($request->has('jenis_tebu') && !empty($request->jenis_tebu)) {
            $query->whereIn('jenis_tebu', $request->jenis_tebu);
        }
        
        // Filter berdasarkan pengirim
        if ($request->has('pengirim') && !empty($request->pengirim)) {
            $query->whereIn('nama_pengirim', $request->pengirim);
        }
        
        // Filter berdasarkan bulan dan tahun
        if ($request->has('bulan') && $request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        
        if ($request->has('tahun') && $request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        }
        
        $shipments = $query->paginate(10);
        
        // Data untuk chart pengirim
        $pengirimData = SugarCaneShipment::select('nama_pengirim', DB::raw('count(*) as total'))
            ->groupBy('nama_pengirim')
            ->get();
            
        // Data untuk chart jenis tebu
        $jenisTebuData = SugarCaneShipment::select('jenis_tebu', DB::raw('count(*) as total'))
            ->groupBy('jenis_tebu')
            ->get();
        
        // Debugging - log data
        \Log::info('Total SugarCaneShipment records: ' . SugarCaneShipment::count());
        \Log::info('Pengirim data count: ' . $pengirimData->count());
        \Log::info('Jenis Tebu data count: ' . $jenisTebuData->count());
        
        // Data untuk rata-rata bobot
        $avgBobot = SugarCaneShipment::avg('bobot_kg');
        
        // Data untuk rata-rata harga
        $avgHarga = SugarCaneShipment::avg('harga_per_kg');
        
        return view('sugar-cane.index', compact('shipments', 'pengirimData', 'jenisTebuData', 'avgBobot', 'avgHarga'));
    }

    public function create()
    {
        // Ambil daftar nama pengirim yang sudah ada (unique)
        $existingSenders = SugarCaneShipment::select('nama_pengirim')
            ->distinct()
            ->orderBy('nama_pengirim')
            ->pluck('nama_pengirim');
            
        return view('sugar-cane.create', compact('existingSenders'));
    }

    // Method baru untuk API autocomplete
    public function getSenders(Request $request)
    {
        $query = $request->get('q', '');
        
        $senders = SugarCaneShipment::select('nama_pengirim')
            ->distinct()
            ->where('nama_pengirim', 'LIKE', '%' . $query . '%')
            ->orderBy('nama_pengirim')
            ->limit(10)
            ->pluck('nama_pengirim');
            
        return response()->json($senders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pengirim' => 'required|string|max:255',
            'jenis_tebu' => 'required|in:Cening (CN),Bululawang (BL),Baru Rakyat (BR)',
            'bobot_kg' => 'required|integer',
            'harga_per_kg' => 'required|integer',
            'tanggal' => 'required|date',
        ]);

        $totalHarga = $request->bobot_kg * $request->harga_per_kg;

        SugarCaneShipment::create([
            'nama_pengirim' => $request->nama_pengirim,
            'jenis_tebu' => $request->jenis_tebu,
            'bobot_kg' => $request->bobot_kg,
            'harga_per_kg' => $request->harga_per_kg,
            'total_harga' => $totalHarga,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('sugar-cane.index')
            ->with('success', 'Data pengiriman tebu berhasil ditambahkan!');
    }

    public function edit(SugarCaneShipment $sugarCane)
    {
        return view('sugar-cane.edit', compact('sugarCane'));
    }

    public function update(Request $request, SugarCaneShipment $sugarCane)
    {
        $request->validate([
            'nama_pengirim' => 'required|string|max:255',
            'jenis_tebu' => 'required|in:Cening (CN),Bululawang (BL),Baru Rakyat (BR)',
            'bobot_kg' => 'required|integer',
            'harga_per_kg' => 'required|integer',
            'tanggal' => 'required|date',
        ]);

        $totalHarga = $request->bobot_kg * $request->harga_per_kg;

        $sugarCane->update([
            'nama_pengirim' => $request->nama_pengirim,
            'jenis_tebu' => $request->jenis_tebu,
            'bobot_kg' => $request->bobot_kg,
            'harga_per_kg' => $request->harga_per_kg,
            'total_harga' => $totalHarga,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('sugar-cane.index')
            ->with('success', 'Data pengiriman tebu berhasil diperbarui!');
    }

    public function destroy(SugarCaneShipment $sugarCane)
    {
        $sugarCane->delete();

        return redirect()->route('sugar-cane.index')
            ->with('success', 'Data pengiriman tebu berhasil dihapus!');
    }

    public function exportPdf(Request $request)
    {
        $query = SugarCaneShipment::latest();
        
        // Filter berdasarkan tanggal awal dan akhir
        if ($request->has('tanggal_awal') && $request->tanggal_awal) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }
        
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }
        
        // Filter berdasarkan jenis tebu
        if ($request->has('jenis_tebu') && !empty($request->jenis_tebu)) {
            $query->whereIn('jenis_tebu', $request->jenis_tebu);
        }
        
        // Filter berdasarkan pengirim
        if ($request->has('pengirim') && !empty($request->pengirim)) {
            $query->whereIn('nama_pengirim', $request->pengirim);
        }
        
        // Filter berdasarkan bulan dan tahun
        if ($request->has('bulan') && $request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        
        if ($request->has('tahun') && $request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        }
        
        $shipments = $query->get();
        
        $pdf = PDF::loadView('sugar-cane.pdf', compact('shipments'));
        return $pdf->download('laporan-pengiriman-tebu.pdf');
    }

    public function show(SugarCaneShipment $sugarCane)
    {
        return view('sugar-cane.show', compact('sugarCane'));
    }
}
