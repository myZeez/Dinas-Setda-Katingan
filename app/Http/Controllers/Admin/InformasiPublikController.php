<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriInformasi;
use App\Models\JenisDokumen;
use App\Models\InformasiPublik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InformasiPublikController extends Controller
{
    /**
     * Display listing by kategori
     */
    public function index($kategoriSlug)
    {
        $kategori = KategoriInformasi::where('slug', $kategoriSlug)->firstOrFail();

        $query = InformasiPublik::with(['jenisDokumen', 'user'])
            ->where('kategori_informasi_id', $kategori->id)
            ->orderBy('created_at', 'desc');

        // Filter by jenis dokumen
        if (request('jenis_dokumen_id')) {
            $query->where('jenis_dokumen_id', request('jenis_dokumen_id'));
        }

        // Filter by status
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Search
        if (request('search')) {
            $query->where(function ($q) {
                $q->where('judul', 'like', '%' . request('search') . '%')
                  ->orWhere('nomor', 'like', '%' . request('search') . '%');
            });
        }

        $informasis = $query->paginate(10);
        $jenisDokumens = JenisDokumen::where('kategori_informasi_id', $kategori->id)->orderBy('urutan')->get();

        $stats = [
            'total' => InformasiPublik::where('kategori_informasi_id', $kategori->id)->count(),
            'berlaku' => InformasiPublik::where('kategori_informasi_id', $kategori->id)->where('status', 'berlaku')->count(),
            'views' => InformasiPublik::where('kategori_informasi_id', $kategori->id)->sum('views'),
            'downloads' => InformasiPublik::where('kategori_informasi_id', $kategori->id)->sum('downloads'),
        ];

        return view('admin.informasi-publik.index', compact('kategori', 'informasis', 'jenisDokumens', 'stats'));
    }

    /**
     * Show create form
     */
    public function create($kategoriSlug)
    {
        $kategori = KategoriInformasi::where('slug', $kategoriSlug)->firstOrFail();
        $jenisDokumens = JenisDokumen::where('kategori_informasi_id', $kategori->id)->orderBy('urutan')->get();

        return view('admin.informasi-publik.create', compact('kategori', 'jenisDokumens'));
    }

    /**
     * Store new informasi
     */
    public function store(Request $request, $kategoriSlug)
    {
        $kategori = KategoriInformasi::where('slug', $kategoriSlug)->firstOrFail();

        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'nomor' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'file_dokumen' => 'nullable|file|mimes:pdf|max:10240',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'lampiran_label' => 'nullable|string|max:255',
            'jenis_dokumen_id' => 'nullable|exists:jenis_dokumens,id',
            'status' => 'required|in:berlaku,tidak_berlaku,terealisasi',
        ]);

        $validated['kategori_informasi_id'] = $kategori->id;
        $validated['user_id'] = Auth::guard('admin')->id();

        // Handle file dokumen
        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $validated['file_dokumen'] = $file->storeAs('informasi-publik/' . $kategori->slug, $fileName, 'public');
        }

        // Handle file lampiran
        if ($request->hasFile('file_lampiran')) {
            $file = $request->file('file_lampiran');
            $fileName = time() . '_lampiran_' . $file->getClientOriginalName();
            $validated['file_lampiran'] = $file->storeAs('informasi-publik/' . $kategori->slug, $fileName, 'public');
        }

        InformasiPublik::create($validated);

        return redirect()->route('admin.informasi-publik.index', $kategoriSlug)
            ->with('success', 'Informasi berhasil ditambahkan.');
    }

    /**
     * Show detail
     */
    public function show($kategoriSlug, $id)
    {
        $kategori = KategoriInformasi::where('slug', $kategoriSlug)->firstOrFail();
        $informasi = InformasiPublik::with(['jenisDokumen', 'user'])
            ->where('kategori_informasi_id', $kategori->id)
            ->findOrFail($id);

        return view('admin.informasi-publik.show', compact('kategori', 'informasi'));
    }

    /**
     * Show edit form
     */
    public function edit($kategoriSlug, $id)
    {
        $kategori = KategoriInformasi::where('slug', $kategoriSlug)->firstOrFail();
        $informasi = InformasiPublik::where('kategori_informasi_id', $kategori->id)->findOrFail($id);
        $jenisDokumens = JenisDokumen::where('kategori_informasi_id', $kategori->id)->orderBy('urutan')->get();

        return view('admin.informasi-publik.edit', compact('kategori', 'informasi', 'jenisDokumens'));
    }

    /**
     * Update informasi
     */
    public function update(Request $request, $kategoriSlug, $id)
    {
        $kategori = KategoriInformasi::where('slug', $kategoriSlug)->firstOrFail();
        $informasi = InformasiPublik::where('kategori_informasi_id', $kategori->id)->findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'nomor' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'file_dokumen' => 'nullable|file|mimes:pdf|max:10240',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'lampiran_label' => 'nullable|string|max:255',
            'jenis_dokumen_id' => 'nullable|exists:jenis_dokumens,id',
            'status' => 'required|in:berlaku,tidak_berlaku,terealisasi',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        // Handle file dokumen
        if ($request->hasFile('file_dokumen')) {
            // Delete old file
            if ($informasi->file_dokumen) {
                Storage::disk('public')->delete($informasi->file_dokumen);
            }
            $file = $request->file('file_dokumen');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $validated['file_dokumen'] = $file->storeAs('informasi-publik/' . $kategori->slug, $fileName, 'public');
        }

        // Handle file lampiran
        if ($request->hasFile('file_lampiran')) {
            // Delete old file
            if ($informasi->file_lampiran) {
                Storage::disk('public')->delete($informasi->file_lampiran);
            }
            $file = $request->file('file_lampiran');
            $fileName = time() . '_lampiran_' . $file->getClientOriginalName();
            $validated['file_lampiran'] = $file->storeAs('informasi-publik/' . $kategori->slug, $fileName, 'public');
        }

        $informasi->update($validated);

        return redirect()->route('admin.informasi-publik.index', $kategoriSlug)
            ->with('success', 'Informasi berhasil diperbarui.');
    }

    /**
     * Delete informasi
     */
    public function destroy($kategoriSlug, $id)
    {
        $kategori = KategoriInformasi::where('slug', $kategoriSlug)->firstOrFail();
        $informasi = InformasiPublik::where('kategori_informasi_id', $kategori->id)->findOrFail($id);

        // Delete files
        if ($informasi->file_dokumen) {
            Storage::disk('public')->delete($informasi->file_dokumen);
        }
        if ($informasi->file_lampiran) {
            Storage::disk('public')->delete($informasi->file_lampiran);
        }

        $informasi->delete();

        return redirect()->route('admin.informasi-publik.index', $kategoriSlug)
            ->with('success', 'Informasi berhasil dihapus.');
    }

    /**
     * Download file
     */
    public function download($kategoriSlug, $id, $type = 'dokumen')
    {
        $kategori = KategoriInformasi::where('slug', $kategoriSlug)->firstOrFail();
        $informasi = InformasiPublik::where('kategori_informasi_id', $kategori->id)->findOrFail($id);

        if ($type === 'lampiran' && $informasi->file_lampiran) {
            $informasi->incrementDownload();
            return Storage::disk('public')->download($informasi->file_lampiran);
        } elseif ($informasi->file_dokumen) {
            $informasi->incrementDownload();
            return Storage::disk('public')->download($informasi->file_dokumen);
        }

        return back()->with('error', 'File tidak ditemukan.');
    }

    /**
     * View file (increment views)
     */
    public function view($kategoriSlug, $id)
    {
        $kategori = KategoriInformasi::where('slug', $kategoriSlug)->firstOrFail();
        $informasi = InformasiPublik::where('kategori_informasi_id', $kategori->id)->findOrFail($id);

        $informasi->incrementView();

        if ($informasi->file_dokumen) {
            return response()->file(storage_path('app/public/' . $informasi->file_dokumen));
        }

        return back()->with('error', 'File tidak ditemukan.');
    }

    /**
     * Manage Jenis Dokumen
     */
    public function jenisDokumen($kategoriSlug)
    {
        $kategori = KategoriInformasi::where('slug', $kategoriSlug)->firstOrFail();
        $jenisDokumens = JenisDokumen::where('kategori_informasi_id', $kategori->id)
            ->withCount('informasiPubliks')
            ->orderBy('urutan')
            ->get();

        return view('admin.informasi-publik.jenis-dokumen', compact('kategori', 'jenisDokumens'));
    }

    /**
     * Store Jenis Dokumen
     */
    public function storeJenisDokumen(Request $request, $kategoriSlug)
    {
        $kategori = KategoriInformasi::where('slug', $kategoriSlug)->firstOrFail();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'urutan' => 'nullable|integer',
        ]);

        $validated['slug'] = JenisDokumen::generateSlug($validated['nama']);
        $validated['kategori_informasi_id'] = $kategori->id;
        $validated['urutan'] = $validated['urutan'] ?? 0;

        JenisDokumen::create($validated);

        return redirect()->route('admin.informasi-publik.jenis-dokumen', $kategoriSlug)
            ->with('success', 'Jenis dokumen berhasil ditambahkan.');
    }

    /**
     * Update Jenis Dokumen
     */
    public function updateJenisDokumen(Request $request, $kategoriSlug, $id)
    {
        $kategori = KategoriInformasi::where('slug', $kategoriSlug)->firstOrFail();
        $jenisDokumen = JenisDokumen::where('kategori_informasi_id', $kategori->id)->findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'urutan' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $jenisDokumen->update($validated);

        return redirect()->route('admin.informasi-publik.jenis-dokumen', $kategoriSlug)
            ->with('success', 'Jenis dokumen berhasil diperbarui.');
    }

    /**
     * Delete Jenis Dokumen
     */
    public function destroyJenisDokumen($kategoriSlug, $id)
    {
        $kategori = KategoriInformasi::where('slug', $kategoriSlug)->firstOrFail();
        $jenisDokumen = JenisDokumen::where('kategori_informasi_id', $kategori->id)->findOrFail($id);

        if ($jenisDokumen->informasiPubliks()->count() > 0) {
            return redirect()->route('admin.informasi-publik.jenis-dokumen', $kategoriSlug)
                ->with('error', 'Jenis dokumen tidak dapat dihapus karena masih memiliki dokumen terkait.');
        }

        $jenisDokumen->delete();

        return redirect()->route('admin.informasi-publik.jenis-dokumen', $kategoriSlug)
            ->with('success', 'Jenis dokumen berhasil dihapus.');
    }
}
