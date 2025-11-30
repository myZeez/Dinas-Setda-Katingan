<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use App\Models\VideoYoutube;
use App\Models\PemimpinDaerah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KontenPublikController extends Controller
{
    // ==================== CAROUSEL ====================

    public function carouselIndex()
    {
        $carousels = Carousel::ordered()->get();
        return view('admin.konten-publik.carousel.index', compact('carousels'));
    }

    public function carouselCreate()
    {
        return view('admin.konten-publik.carousel.create');
    }

    public function carouselStore(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'link' => 'nullable|url',
            'tombol_text' => 'nullable|string|max:50',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $data = $request->only(['judul', 'deskripsi', 'link', 'tombol_text', 'urutan']);
        $data['is_active'] = $request->has('is_active');
        $data['urutan'] = $data['urutan'] ?? Carousel::max('urutan') + 1;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('carousel', 'public');
        }

        Carousel::create($data);

        return redirect()->route('admin.konten-publik.carousel')
            ->with('success', 'Carousel berhasil ditambahkan');
    }

    public function carouselEdit($id)
    {
        $carousel = Carousel::findOrFail($id);
        return view('admin.konten-publik.carousel.edit', compact('carousel'));
    }

    public function carouselUpdate(Request $request, $id)
    {
        $carousel = Carousel::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'link' => 'nullable|url',
            'tombol_text' => 'nullable|string|max:50',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $data = $request->only(['judul', 'deskripsi', 'link', 'tombol_text', 'urutan']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('gambar')) {
            if ($carousel->gambar) {
                Storage::disk('public')->delete($carousel->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('carousel', 'public');
        }

        $carousel->update($data);

        return redirect()->route('admin.konten-publik.carousel')
            ->with('success', 'Carousel berhasil diperbarui');
    }

    public function carouselDestroy($id)
    {
        $carousel = Carousel::findOrFail($id);

        if ($carousel->gambar) {
            Storage::disk('public')->delete($carousel->gambar);
        }

        $carousel->delete();

        return redirect()->route('admin.konten-publik.carousel')
            ->with('success', 'Carousel berhasil dihapus');
    }

    public function carouselUpdateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:carousels,id',
            'orders.*.urutan' => 'required|integer',
        ]);

        foreach ($request->orders as $order) {
            Carousel::where('id', $order['id'])->update(['urutan' => $order['urutan']]);
        }

        return response()->json(['success' => true]);
    }

    // ==================== VIDEO YOUTUBE ====================

    public function videoIndex()
    {
        $videos = VideoYoutube::ordered()->get();
        return view('admin.konten-publik.video.index', compact('videos'));
    }

    public function videoCreate()
    {
        return view('admin.konten-publik.video.create');
    }

    public function videoStore(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'youtube_url' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $youtubeId = $this->extractYoutubeId($request->youtube_url);
        if (!$youtubeId) {
            return back()->withInput()->withErrors(['youtube_url' => 'URL YouTube tidak valid']);
        }

        $data = $request->only(['judul', 'deskripsi', 'urutan']);
        $data['youtube_id'] = $youtubeId;
        $data['is_active'] = $request->has('is_active');
        $data['urutan'] = $data['urutan'] ?? VideoYoutube::max('urutan') + 1;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('video-thumbnails', 'public');
        }

        VideoYoutube::create($data);

        return redirect()->route('admin.konten-publik.video')
            ->with('success', 'Video berhasil ditambahkan');
    }

    public function videoEdit($id)
    {
        $video = VideoYoutube::findOrFail($id);
        return view('admin.konten-publik.video.edit', compact('video'));
    }

    public function videoUpdate(Request $request, $id)
    {
        $video = VideoYoutube::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'youtube_url' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $youtubeId = $this->extractYoutubeId($request->youtube_url);
        if (!$youtubeId) {
            return back()->withInput()->withErrors(['youtube_url' => 'URL YouTube tidak valid']);
        }

        $data = $request->only(['judul', 'deskripsi', 'urutan']);
        $data['youtube_id'] = $youtubeId;
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('thumbnail')) {
            if ($video->thumbnail) {
                Storage::disk('public')->delete($video->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('video-thumbnails', 'public');
        }

        $video->update($data);

        return redirect()->route('admin.konten-publik.video')
            ->with('success', 'Video berhasil diperbarui');
    }

    public function videoDestroy($id)
    {
        $video = VideoYoutube::findOrFail($id);

        if ($video->thumbnail) {
            Storage::disk('public')->delete($video->thumbnail);
        }

        $video->delete();

        return redirect()->route('admin.konten-publik.video')
            ->with('success', 'Video berhasil dihapus');
    }

    private function extractYoutubeId($url)
    {
        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
            '/^([a-zA-Z0-9_-]{11})$/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    // ==================== PEMIMPIN DAERAH ====================

    public function pemimpinIndex()
    {
        $pemimpins = PemimpinDaerah::ordered()->get();
        return view('admin.konten-publik.pemimpin.index', compact('pemimpins'));
    }

    public function pemimpinCreate()
    {
        return view('admin.konten-publik.pemimpin.create');
    }

    public function pemimpinStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:png,jpeg,jpg,webp|max:5120',
            'deskripsi' => 'nullable|string',
            'periode' => 'nullable|string|max:50',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $data = $request->only(['nama', 'jabatan', 'deskripsi', 'periode', 'urutan']);
        $data['is_active'] = $request->has('is_active');
        $data['urutan'] = $data['urutan'] ?? PemimpinDaerah::max('urutan') + 1;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('pemimpin', 'public');
        }

        PemimpinDaerah::create($data);

        return redirect()->route('admin.konten-publik.pemimpin')
            ->with('success', 'Pemimpin daerah berhasil ditambahkan');
    }

    public function pemimpinEdit($id)
    {
        $pemimpin = PemimpinDaerah::findOrFail($id);
        return view('admin.konten-publik.pemimpin.edit', compact('pemimpin'));
    }

    public function pemimpinUpdate(Request $request, $id)
    {
        $pemimpin = PemimpinDaerah::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:png,jpeg,jpg,webp|max:5120',
            'deskripsi' => 'nullable|string',
            'periode' => 'nullable|string|max:50',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $data = $request->only(['nama', 'jabatan', 'deskripsi', 'periode', 'urutan']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('foto')) {
            if ($pemimpin->foto) {
                Storage::disk('public')->delete($pemimpin->foto);
            }
            $data['foto'] = $request->file('foto')->store('pemimpin', 'public');
        }

        $pemimpin->update($data);

        return redirect()->route('admin.konten-publik.pemimpin')
            ->with('success', 'Pemimpin daerah berhasil diperbarui');
    }

    public function pemimpinDestroy($id)
    {
        $pemimpin = PemimpinDaerah::findOrFail($id);

        if ($pemimpin->foto) {
            Storage::disk('public')->delete($pemimpin->foto);
        }

        $pemimpin->delete();

        return redirect()->route('admin.konten-publik.pemimpin')
            ->with('success', 'Pemimpin daerah berhasil dihapus');
    }

    public function pemimpinReorder(Request $request)
    {
        $positions = $request->input('positions', []);

        foreach ($positions as $item) {
            PemimpinDaerah::where('id', $item['id'])->update([
                'grid_position' => $item['position'],
                'urutan' => $item['position']
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Posisi berhasil diperbarui']);
    }
}
