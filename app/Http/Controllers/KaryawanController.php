<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Karyawan::query();
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%");
        }
        
        // Filter by karyawan_id
        if ($request->has('karyawan_id') && $request->karyawan_id != '') {
            $query->where('id', $request->karyawan_id);
        }
        
        $karyawan = $query->orderBy('nama')->paginate(10);
        
        // Get all karyawan for dropdown
        $allKaryawan = Karyawan::orderBy('nama')->get();
        
        return view('karyawan.index', compact('karyawan', 'allKaryawan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('karyawan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:karyawan,nama',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'status_aktif' => 'required|boolean',
        ], [
            'nama.unique' => 'Nama karyawan sudah ada sebelumnya.'
        ]);
        
        // Siapkan data
        $data = [
            'nama' => $request->nama,
            'tanggal_bergabung' => now(),
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'status_aktif' => $request->status_aktif
        ];
        
        Karyawan::create($data);
        
        return redirect()->route('karyawan.index')
                         ->with('success', 'Data karyawan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Karyawan $karyawan)
    {
        return view('karyawan.show', compact('karyawan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan)
    {
        return view('karyawan.edit', compact('karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:karyawan,nama,' . $karyawan->id,
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'status_aktif' => 'boolean'
        ], [
            'nama.unique' => 'Nama karyawan sudah ada sebelumnya.'
        ]);
        
        $karyawan->update($request->all());
        
        return redirect()->route('karyawan.index')
                         ->with('success', 'Data karyawan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        
        return redirect()->route('karyawan.index')
                         ->with('success', 'Data karyawan berhasil dihapus!');
    }
    
    /**
     * Export data to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Karyawan::query();
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%");
        }
        
        // Filter by karyawan_id
        if ($request->has('karyawan_id') && $request->karyawan_id != '') {
            $query->where('id', $request->karyawan_id);
        }
        
        $karyawan = $query->orderBy('nama')->get();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('karyawan.pdf', compact('karyawan'));
        return $pdf->download('laporan-data-karyawan.pdf');
    }
}
