<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Berita::with('user')->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('konten', 'like', '%' . $request->search . '%');
            });
        }

        $beritas = $query->paginate(10);

        $stats = [
            'total' => Berita::count(),
            'published' => Berita::where('status', 'published')->count(),
            'draft' => Berita::where('status', 'draft')->count(),
        ];

        return view('admin.berita.index', compact('beritas', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.berita.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'ringkasan' => 'nullable|string|max:1000',
            'konten' => 'required|string|min:10',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_caption' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
        ], [
            'judul.required' => 'Judul berita wajib diisi',
            'ringkasan.max' => 'Ringkasan maksimal 1000 karakter',
            'konten.required' => 'Konten berita wajib diisi',
            'konten.min' => 'Konten berita terlalu pendek',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp',
            'foto.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $validated['slug'] = Berita::generateSlug($validated['judul']);
        $validated['user_id'] = Auth::guard('admin')->id();

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('berita', $fileName, 'public');
            $validated['foto'] = $filePath;
        }

        // Set published_at if publishing
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        Berita::create($validated);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Berita $berita)
    {
        return view('admin.berita.show', compact('berita'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Berita $berita)
    {
        return view('admin.berita.edit', compact('berita'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Berita $berita)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'ringkasan' => 'nullable|string|max:1000',
            'konten' => 'required|string|min:10',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_caption' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
        ], [
            'judul.required' => 'Judul berita wajib diisi',
            'ringkasan.max' => 'Ringkasan maksimal 1000 karakter',
            'konten.required' => 'Konten berita wajib diisi',
            'konten.min' => 'Konten berita terlalu pendek',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp',
            'foto.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Update slug if judul changed
        if ($berita->judul !== $validated['judul']) {
            $validated['slug'] = Berita::generateSlug($validated['judul']);
        }

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old foto
            if ($berita->foto && Storage::disk('public')->exists($berita->foto)) {
                Storage::disk('public')->delete($berita->foto);
            }

            $file = $request->file('foto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('berita', $fileName, 'public');
            $validated['foto'] = $filePath;
        }

        // Set published_at if publishing for first time
        if ($validated['status'] === 'published' && !$berita->published_at) {
            $validated['published_at'] = now();
        }

        $berita->update($validated);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Berita $berita)
    {
        // Delete foto
        if ($berita->foto && Storage::disk('public')->exists($berita->foto)) {
            Storage::disk('public')->delete($berita->foto);
        }

        $berita->delete();

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    /**
     * Upload image for editor
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $file = $request->file('image');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('berita/content', $fileName, 'public');

        return response()->json([
            'success' => true,
            'url' => Storage::url($filePath),
        ]);
    }
}
