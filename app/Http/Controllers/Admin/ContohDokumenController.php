<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContohDokumen;
use App\Models\JenisLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContohDokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contohDokumen = ContohDokumen::with('jenisLayanan')
            ->orderBy('urutan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.contoh-dokumen.index', compact('contohDokumen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisLayanan = JenisLayanan::where('is_active', true)->get();
        return view('admin.contoh-dokumen.create', compact('jenisLayanan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'jenis_layanan_id' => 'nullable|exists:jenis_layanans,id',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->store('contoh-dokumen', 'public');
        $fileSize = $file->getSize();

        ContohDokumen::create([
            'nama' => $validated['nama'],
            'keterangan' => $validated['keterangan'] ?? null,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'jenis_layanan_id' => $validated['jenis_layanan_id'] ?? null,
            'urutan' => $validated['urutan'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.contoh-dokumen.index')
            ->with('success', 'Contoh dokumen berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContohDokumen $contohDokumen)
    {
        $jenisLayanan = JenisLayanan::where('is_active', true)->get();
        return view('admin.contoh-dokumen.edit', compact('contohDokumen', 'jenisLayanan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContohDokumen $contohDokumen)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'jenis_layanan_id' => 'nullable|exists:jenis_layanans,id',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = [
            'nama' => $validated['nama'],
            'keterangan' => $validated['keterangan'] ?? null,
            'jenis_layanan_id' => $validated['jenis_layanan_id'] ?? null,
            'urutan' => $validated['urutan'] ?? 0,
            'is_active' => $request->has('is_active'),
        ];

        // Update file jika ada file baru
        if ($request->hasFile('file')) {
            // Hapus file lama
            if ($contohDokumen->file_path && Storage::disk('public')->exists($contohDokumen->file_path)) {
                Storage::disk('public')->delete($contohDokumen->file_path);
            }

            $file = $request->file('file');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_path'] = $file->store('contoh-dokumen', 'public');
            $data['file_size'] = $file->getSize();
        }

        $contohDokumen->update($data);

        return redirect()->route('admin.contoh-dokumen.index')
            ->with('success', 'Contoh dokumen berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContohDokumen $contohDokumen)
    {
        // Hapus file
        if ($contohDokumen->file_path && Storage::disk('public')->exists($contohDokumen->file_path)) {
            Storage::disk('public')->delete($contohDokumen->file_path);
        }

        $contohDokumen->delete();

        return redirect()->route('admin.contoh-dokumen.index')
            ->with('success', 'Contoh dokumen berhasil dihapus!');
    }

    /**
     * Download file
     */
    public function download(ContohDokumen $contohDokumen)
    {
        $contohDokumen->incrementDownload();

        $filePath = storage_path('app/public/' . $contohDokumen->file_path);

        if (!file_exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan!');
        }

        return response()->download($filePath, $contohDokumen->file_name);
    }

    /**
     * Preview/View file (untuk tracking jumlah dilihat)
     */
    public function preview(ContohDokumen $contohDokumen)
    {
        $contohDokumen->incrementView();

        return redirect(Storage::url($contohDokumen->file_path));
    }
}
