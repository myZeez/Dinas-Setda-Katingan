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
            'menunggu_review_sp' => PengajuanLayanan::where('status', 'menunggu_review_sp')->count(),
            'sp_disetujui' => PengajuanLayanan::where('status', 'sp_disetujui')->count(),
            'sp_revisi' => PengajuanLayanan::where('status', 'sp_revisi')->count(),
            'dokumen_lengkap' => PengajuanLayanan::where('status', 'dokumen_lengkap')->count(),
            'diproses' => PengajuanLayanan::where('status', 'diproses')->count(),
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
            'user_id' => Auth::guard('admin')->id(),
            'status' => $validated['status'],
            'keterangan' => $validated['catatan'] ?? 'Status diubah dari ' . $oldStatus . ' ke ' . $validated['status'],
        ]);

        return back()->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    /**
     * Approve Surat Penawaran
     */
    public function approveSuratPenawaran($id)
    {
        $pengajuan = PengajuanLayanan::findOrFail($id);

        if ($pengajuan->status != 'menunggu_review_sp' && $pengajuan->status != 'sp_revisi') {
            return back()->with('error', 'Pengajuan tidak dalam status menunggu review.');
        }

        $pengajuan->update([
            'status' => 'sp_disetujui',
            'catatan_revisi_sp' => null,
        ]);

        // Create log
        LogPengajuan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'user_id' => Auth::guard('admin')->id(),
            'status' => 'sp_disetujui',
            'keterangan' => 'Surat Penawaran disetujui oleh admin',
        ]);

        return back()->with('success', 'Surat Penawaran berhasil disetujui. User dapat melanjutkan upload dokumen.');
    }

    /**
     * Request revision for Surat Penawaran
     */
    public function revisiSuratPenawaran(Request $request, $id)
    {
        $pengajuan = PengajuanLayanan::findOrFail($id);

        if ($pengajuan->status != 'menunggu_review_sp' && $pengajuan->status != 'sp_revisi') {
            return back()->with('error', 'Pengajuan tidak dalam status menunggu review.');
        }

        $validated = $request->validate([
            'catatan_revisi_sp' => 'required|string|max:1000',
        ]);

        $pengajuan->update([
            'status' => 'sp_revisi',
            'catatan_revisi_sp' => $validated['catatan_revisi_sp'],
        ]);

        // Create log
        LogPengajuan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'user_id' => Auth::guard('admin')->id(),
            'status' => 'sp_revisi',
            'keterangan' => 'Surat Penawaran perlu revisi: ' . $validated['catatan_revisi_sp'],
        ]);

        return back()->with('success', 'Permintaan revisi Surat Penawaran berhasil dikirim ke user.');
    }

    /**
     * Approve KAK (Kerangka Acuan Kerja)
     */
    public function approveKAK($id)
    {
        $pengajuan = PengajuanLayanan::findOrFail($id);

        if ($pengajuan->status != 'menunggu_review_kak' && $pengajuan->status != 'kak_revisi') {
            return back()->with('error', 'Pengajuan tidak dalam status menunggu review KAK.');
        }

        $pengajuan->update([
            'status' => 'kak_disetujui',
            'catatan_revisi_kak' => null,
        ]);

        // Create log
        LogPengajuan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'user_id' => Auth::guard('admin')->id(),
            'status' => 'kak_disetujui',
            'keterangan' => 'Kerangka Acuan Kerja (KAK) disetujui oleh admin',
        ]);

        return back()->with('success', 'KAK berhasil disetujui. User dapat melanjutkan upload Link Nota Kesepakatan.');
    }

    /**
     * Request revision for KAK
     */
    public function revisiKAK(Request $request, $id)
    {
        $pengajuan = PengajuanLayanan::findOrFail($id);

        if ($pengajuan->status != 'menunggu_review_kak' && $pengajuan->status != 'kak_revisi') {
            return back()->with('error', 'Pengajuan tidak dalam status menunggu review KAK.');
        }

        $validated = $request->validate([
            'catatan_revisi_kak' => 'required|string|max:1000',
        ]);

        $pengajuan->update([
            'status' => 'kak_revisi',
            'catatan_revisi_kak' => $validated['catatan_revisi_kak'],
        ]);

        // Create log
        LogPengajuan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'user_id' => Auth::guard('admin')->id(),
            'status' => 'kak_revisi',
            'keterangan' => 'KAK perlu revisi: ' . $validated['catatan_revisi_kak'],
        ]);

        return back()->with('success', 'Permintaan revisi KAK berhasil dikirim ke user.');
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
            'user_id' => Auth::guard('admin')->id(),
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

        // Validasi status - harus sudah dokumen lengkap atau sedang diproses
        if (!in_array($pengajuan->status, ['dokumen_lengkap', 'diproses', 'proses_ttd', 'penjadwalan_ttd'])) {
            return back()->with('error', 'Pengajuan tidak dalam status yang dapat diselesaikan.');
        }

        $validated = $request->validate([
            'dokumen_hasil' => 'required|file|mimes:pdf|max:10240',
            'catatan_selesai' => 'nullable|string|max:1000',
        ], [
            'dokumen_hasil.required' => 'Dokumen hasil wajib diupload',
            'dokumen_hasil.mimes' => 'Dokumen harus format PDF',
            'dokumen_hasil.max' => 'Ukuran file maksimal 10MB',
        ]);

        // Upload file
        $file = $request->file('dokumen_hasil');
        $fileName = time() . '_surat_bukti_selesai_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('dokumen_layanan/' . $pengajuan->id . '/hasil', $fileName, 'public');

        // Update pengajuan
        $pengajuan->update([
            'dokumen_hasil' => $filePath,
            'status' => 'selesai',
            'catatan_admin' => $request->catatan_selesai ?? 'Pengajuan telah selesai diproses.',
            'tanggal_selesai' => now(),
        ]);

        // Create log
        LogPengajuan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'user_id' => Auth::guard('admin')->id(),
            'status' => 'selesai',
            'keterangan' => 'Pengajuan selesai. Dokumen hasil/surat bukti telah diupload dan dikirim ke pemohon.' .
                ($request->catatan_selesai ? ' Catatan: ' . $request->catatan_selesai : ''),
        ]);

        return back()->with('success', 'Pengajuan berhasil diselesaikan! Dokumen hasil telah dikirim ke pemohon.');
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
