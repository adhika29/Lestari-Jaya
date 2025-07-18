<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaperController extends Controller
{
    public function index()
    {
        $papers = Paper::latest()->paginate(10);
        return view('papers.index', compact('papers'));
    }

    public function create()
    {
        return view('papers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'abstract' => 'required',
            'author' => 'required',
            'publication_date' => 'required|date',
            'category' => 'required',
            'keywords' => 'nullable',
            'file' => 'nullable|mimes:pdf|max:10240',
        ]);

        $paper = new Paper($request->except('file'));
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('papers', $filename, 'public');
            $paper->file_path = 'papers/' . $filename;
        }

        $paper->save();
        return redirect()->route('papers.index')->with('success', 'Paper berhasil ditambahkan!');
    }

    public function show(Paper $paper)
    {
        return view('papers.show', compact('paper'));
    }

    public function edit(Paper $paper)
    {
        return view('papers.edit', compact('paper'));
    }

    public function update(Request $request, Paper $paper)
    {
        $request->validate([
            'title' => 'required',
            'abstract' => 'required',
            'author' => 'required',
            'publication_date' => 'required|date',
            'category' => 'required',
            'keywords' => 'nullable',
            'file' => 'nullable|mimes:pdf|max:10240',
        ]);

        $paper->fill($request->except('file'));
        
        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($paper->file_path) {
                Storage::disk('public')->delete($paper->file_path);
            }
            
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('papers', $filename, 'public');
            $paper->file_path = 'papers/' . $filename;
        }

        $paper->save();
        return redirect()->route('papers.index')->with('success', 'Paper berhasil diperbarui!');
    }

    public function destroy(Paper $paper)
    {
        // Hapus file jika ada
        if ($paper->file_path) {
            Storage::disk('public')->delete($paper->file_path);
        }
        
        $paper->delete();
        return redirect()->route('papers.index')->with('success', 'Paper berhasil dihapus!');
    }
}
