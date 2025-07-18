<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BiayaOperasional;
use Illuminate\Support\Facades\DB;

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
        
        $biayaOperasional = $query->orderBy('tanggal', 'desc')->paginate(10);
        
        // Ambil daftar keterangan unik untuk dropdown
        $keteranganList = BiayaOperasional::select('keterangan')
                            ->distinct()
                            ->orderBy('keterangan')
                            ->pluck('keterangan');
        
        return view('biaya-pengeluaran.operasional.index', compact('biayaOperasional', 'keteranganList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('biaya-pengeluaran.operasional.create');
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
        return view('biaya-pengeluaran.operasional.edit', compact('biayaOperasional'));
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
}
