<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenLayanan;
use App\Models\JenisLayanan;
use App\Models\LogPengajuan;
use App\Models\PengajuanLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LayananController extends Controller
{
    /**
     * Dashboard layanan - statistik
     */
    public function index()
    {
        $stats = [
            'total' => PengajuanLayanan::count(),
            'diajukan' => PengajuanLayanan::where('status', 'diajukan')->count(),
            'diproses' => PengajuanLayanan::where('status', 'diproses')->count(),
            'koreksi' => PengajuanLayanan::where('status', 'koreksi')->count(),
            'proses_ttd' => PengajuanLayanan::where('status', 'proses_ttd')->count(),
            'penjadwalan_ttd' => PengajuanLayanan::where('status', 'penjadwalan_ttd')->count(),
            'selesai' => PengajuanLayanan::where('status', 'selesai')->count(),
            'ditolak' => PengajuanLayanan::where('status', 'ditolak')->count(),
        ];

        $recentPengajuans = PengajuanLayanan::with(['user', 'jenisLayanan'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.layanan.index', compact('stats', 'recentPengajuans'));
    }

    /**
     * Daftar semua pengajuan
     */
    public function pengajuan(Request $request)
    {
        $query = PengajuanLayanan::with(['user', 'jenisLayanan'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by jenis layanan
        if ($request->jenis) {
            $query->whereHas('jenisLayanan', function ($q) use ($request) {
                $q->where('kode', $request->jenis);
            });
        }

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_pengajuan', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_pihak', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', function ($q2) use ($request) {
                        $q2->where('nama', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $pengajuans = $query->paginate(15);
        $jenisLayanans = JenisLayanan::all();

        return view('admin.layanan.pengajuan', compact('pengajuans', 'jenisLayanans'));
    }

    /**
     * Detail pengajuan
     */
    public function detail($id)
    {
        $pengajuan = PengajuanLayanan::with(['user', 'jenisLayanan', 'dokumens', 'logs.user'])
            ->findOrFail($id);

        return view('admin.layanan.detail', compact('pengajuan'));
    }

    /**
     * Update status pengajuan
     */
    public function updateStatus(Request $request, $id)
    {
        $pengajuan = PengajuanLayanan::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:diproses,koreksi,proses_ttd,penjadwalan_ttd,selesai,ditolak',
            'catatan' => 'nullable|string',
        ]);

        $oldStatus = $pengajuan->status;
        $pengajuan->update([
            'status' => $validated['status'],
            'catatan_admin' => $validated['catatan'],
        ]);

        // Create log
        LogPengajuan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'user_id' => Auth::id(),
            'status' => $validated['status'],
            'keterangan' => $validated['catatan'] ?? 'Status diubah dari ' . $oldStatus . ' ke ' . $validated['status'],
        ]);

        return back()->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    /**
     * Update status dokumen
     */
    public function updateDokumen(Request $request, $id, $dokumenId)
    {
        $pengajuan = PengajuanLayanan::findOrFail($id);
        $dokumen = DokumenLayanan::where('pengajuan_layanan_id', $id)->findOrFail($dokumenId);

        $validated = $request->validate([
            'status' => 'required|in:diterima,diproses,koreksi,ditolak',
            'catatan' => 'nullable|string',
        ]);

        $dokumen->update([
            'status' => $validated['status'],
            'catatan' => $validated['catatan'],
        ]);

        // Create log
        LogPengajuan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'user_id' => Auth::id(),
            'status' => $pengajuan->status,
            'keterangan' => 'Status dokumen ' . $dokumen->jenis_dokumen_label . ' diubah menjadi ' . $validated['status'],
        ]);

        return back()->with('success', 'Status dokumen berhasil diperbarui.');
    }

    /**
     * Upload dokumen hasil (setelah selesai)
     */
    public function uploadHasil(Request $request, $id)
    {
        $pengajuan = PengajuanLayanan::findOrFail($id);

        $validated = $request->validate([
            'dokumen_hasil' => 'required|file|mimes:pdf|max:10240',
        ]);

        // Upload file
        $file = $request->file('dokumen_hasil');
        $fileName = time() . '_hasil_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('dokumen_layanan/' . $pengajuan->id . '/hasil', $fileName, 'public');

        $pengajuan->update([
            'dokumen_hasil' => $filePath,
            'status' => 'selesai',
        ]);

        // Create log
        LogPengajuan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'user_id' => Auth::id(),
            'status' => 'selesai',
            'keterangan' => 'Dokumen hasil telah diupload',
        ]);

        return back()->with('success', 'Dokumen hasil berhasil diupload dan pengajuan selesai.');
    }

    /**
     * Download dokumen
     */
    public function downloadDokumen($id, $dokumenId)
    {
        $dokumen = DokumenLayanan::where('pengajuan_layanan_id', $id)->findOrFail($dokumenId);

        return Storage::disk('public')->download($dokumen->file_path, $dokumen->nama_file);
    }

    /**
     * Kelola jenis layanan
     */
    public function jenisLayanan()
    {
        $jenisLayanans = JenisLayanan::withCount('pengajuans')->get();
        return view('admin.layanan.jenis', compact('jenisLayanans'));
    }

    /**
     * Update jenis layanan
     */
    public function updateJenisLayanan(Request $request, $id)
    {
        $jenisLayanan = JenisLayanan::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $jenisLayanan->update($validated);

        return back()->with('success', 'Jenis layanan berhasil diperbarui.');
    }
}
