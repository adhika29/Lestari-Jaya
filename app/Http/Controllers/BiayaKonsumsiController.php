<?php

namespace App\Http\Controllers;

use App\Models\BiayaKonsumsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        
        $biayaKonsumsi = $query->paginate(10);
        
        // Ambil daftar keterangan unik untuk dropdown
        $keteranganList = BiayaKonsumsi::select('keterangan')
                            ->distinct()
                            ->orderBy('keterangan')
                            ->pluck('keterangan');
        
        return view('biaya-pengeluaran.index', compact('biayaKonsumsi', 'keteranganList'));
    }

    public function create()
    {
        return view('biaya-pengeluaran.create');
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
        return view('biaya-pengeluaran.edit', compact('biayaKonsumsi'));
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
}