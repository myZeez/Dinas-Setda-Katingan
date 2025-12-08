<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ContohDokumen;
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
     * Step 1: Show pilihan layanan
     */
    public function index()
    {
        $jenisLayanan = JenisLayanan::where('is_active', true)->get();
        $contohDokumen = ContohDokumen::where('is_active', true)
            ->orderBy('urutan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.layanan.index', compact('jenisLayanan', 'contohDokumen'));
    }

    /**
     * Step 2: Show form pengajuan (Data + Upload Surat Penawaran saja)
     */
    public function create($kode)
    {
        $jenisLayanan = JenisLayanan::where('kode', $kode)->where('is_active', true)->firstOrFail();
        return view('user.layanan.create', compact('jenisLayanan'));
    }

    /**
     * Store pengajuan dengan Surat Penawaran saja
     * Status: menunggu_review_sp
     */
    public function store(Request $request, $kode)
    {
        $jenisLayanan = JenisLayanan::where('kode', $kode)->where('is_active', true)->firstOrFail();

        $validated = $request->validate([
            'nama_pihak' => 'required|string|max:255',
            'tentang' => 'required|string',
            'instansi_terkait' => 'nullable|string',
            'surat_penawaran' => 'required|file|mimes:pdf|max:5120',
        ], [
            'nama_pihak.required' => 'Nama Daerah/Pihak Ketiga wajib diisi',
            'tentang.required' => 'Tentang kerja sama wajib diisi',
            'surat_penawaran.required' => 'Surat Penawaran wajib diupload',
            'surat_penawaran.mimes' => 'Surat Penawaran harus format PDF',
            'surat_penawaran.max' => 'Ukuran file maksimal 5MB',
        ]);

        $user = Auth::guard('user')->user();

        // Create Pengajuan dengan status menunggu review surat penawaran
        $pengajuan = PengajuanLayanan::create([
            'nomor_pengajuan' => PengajuanLayanan::generateNomorPengajuan($jenisLayanan->kode),
            'user_id' => $user->id,
            'jenis_layanan_id' => $jenisLayanan->id,
            'nama_pihak' => strtoupper($validated['nama_pihak']),
            'tentang' => strtoupper($validated['tentang']),
            'instansi_terkait' => $validated['instansi_terkait'] ? strtoupper($validated['instansi_terkait']) : null,
            'status' => 'menunggu_review_sp',
            'tanggal_pengajuan' => now(),
        ]);

        // Upload Surat Penawaran
        $file = $request->file('surat_penawaran');
        $fileName = time() . '_surat_penawaran_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('dokumen_layanan/' . $pengajuan->id, $fileName, 'public');

        DokumenLayanan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'jenis_dokumen' => 'surat_penawaran',
            'nama_file' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'status' => 'diproses',
        ]);

        // Create log
        LogPengajuan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'user_id' => $user->id,
            'status' => 'menunggu_review_sp',
            'keterangan' => 'Pengajuan dibuat. Surat Penawaran diupload, menunggu review admin.',
        ]);

        return redirect()->route('user.layanan.detail', $pengajuan->id)
            ->with('success', 'Pengajuan berhasil dibuat dengan nomor: ' . $pengajuan->nomor_pengajuan . '. Silakan tunggu review dari admin untuk Surat Penawaran Anda.');
    }

    /**
     * Upload Surat Penawaran ulang (jika revisi)
     */
    public function reuploadSuratPenawaran(Request $request, $id)
    {
        $user = Auth::guard('user')->user();
        $pengajuan = PengajuanLayanan::where('user_id', $user->id)->findOrFail($id);

        // Check status harus sp_revisi
        if ($pengajuan->status !== 'sp_revisi') {
            return back()->with('error', 'Surat Penawaran tidak dalam status revisi.');
        }

        $request->validate([
            'surat_penawaran' => 'required|file|mimes:pdf|max:5120',
        ], [
            'surat_penawaran.required' => 'Surat Penawaran wajib diupload',
            'surat_penawaran.mimes' => 'Surat Penawaran harus format PDF',
            'surat_penawaran.max' => 'Ukuran file maksimal 5MB',
        ]);

        // Get existing surat penawaran
        $dokumenSP = $pengajuan->dokumens()->where('jenis_dokumen', 'surat_penawaran')->first();

        // Delete old file if exists
        if ($dokumenSP && Storage::disk('public')->exists($dokumenSP->file_path)) {
            Storage::disk('public')->delete($dokumenSP->file_path);
        }

        // Upload new file
        $file = $request->file('surat_penawaran');
        $versi = $dokumenSP ? $dokumenSP->versi + 1 : 1;
        $fileName = time() . '_surat_penawaran_v' . $versi . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('dokumen_layanan/' . $pengajuan->id, $fileName, 'public');

        if ($dokumenSP) {
            $dokumenSP->update([
                'nama_file' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'status' => 'diproses',
                'versi' => $versi,
                'catatan' => null,
            ]);
        } else {
            DokumenLayanan::create([
                'pengajuan_layanan_id' => $pengajuan->id,
                'jenis_dokumen' => 'surat_penawaran',
                'nama_file' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'status' => 'diproses',
                'versi' => $versi,
            ]);
        }

        // Update status pengajuan
        $pengajuan->update([
            'status' => 'menunggu_review_sp',
            'catatan_revisi_sp' => null,
        ]);

        // Create log
        LogPengajuan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'user_id' => $user->id,
            'status' => 'menunggu_review_sp',
            'keterangan' => 'Surat Penawaran (Revisi v' . $versi . ') diupload ulang, menunggu review admin.',
        ]);

        return back()->with('success', 'Surat Penawaran berhasil diupload ulang. Menunggu review admin.');
    }

    /**
     * Upload KAK (Kerangka Acuan Kerja)
     * Hanya bisa dilakukan setelah Surat Penawaran disetujui
     */
    public function uploadKAK(Request $request, $id)
    {
        $user = Auth::guard('user')->user();
        $pengajuan = PengajuanLayanan::where('user_id', $user->id)->findOrFail($id);

        // Check status harus sp_disetujui atau kak_revisi
        if (!in_array($pengajuan->status, ['sp_disetujui', 'kak_revisi'])) {
            return back()->with('error', 'Tidak dapat mengupload KAK pada status ini.');
        }

        $request->validate([
            'file_kak' => 'required|file|mimes:pdf|max:5120',
        ], [
            'file_kak.required' => 'Kerangka Acuan Kerja (KAK) wajib diupload',
            'file_kak.mimes' => 'Kerangka Acuan Kerja harus format PDF',
            'file_kak.max' => 'Ukuran file maksimal 5MB',
        ]);

        // Check if existing KAK dokumen
        $dokumenKAK = $pengajuan->dokumens()->where('jenis_dokumen', 'kerangka_acuan_kerja')->first();

        // Delete old file if exists
        if ($dokumenKAK && Storage::disk('public')->exists($dokumenKAK->file_path)) {
            Storage::disk('public')->delete($dokumenKAK->file_path);
        }

        // Also delete old file from file_kak column
        if ($pengajuan->file_kak && Storage::disk('public')->exists($pengajuan->file_kak)) {
            Storage::disk('public')->delete($pengajuan->file_kak);
        }

        // Upload KAK
        $file = $request->file('file_kak');
        $versi = $dokumenKAK ? $dokumenKAK->versi + 1 : 1;
        $fileName = time() . '_kak_v' . $versi . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('dokumen_layanan/' . $pengajuan->id, $fileName, 'public');

        if ($dokumenKAK) {
            $dokumenKAK->update([
                'nama_file' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'status' => 'diproses',
                'versi' => $versi,
                'catatan' => null,
            ]);
        } else {
            DokumenLayanan::create([
                'pengajuan_layanan_id' => $pengajuan->id,
                'jenis_dokumen' => 'kerangka_acuan_kerja',
                'nama_file' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'status' => 'diproses',
                'versi' => $versi,
            ]);
        }

        // Update pengajuan
        $pengajuan->update([
            'file_kak' => $filePath,
            'status' => 'menunggu_review_kak',
            'catatan_revisi_kak' => null,
        ]);

        // Create log
        $logMessage = $versi > 1
            ? 'KAK (Revisi v' . $versi . ') diupload ulang, menunggu review admin.'
            : 'KAK berhasil diupload, menunggu review admin.';

        LogPengajuan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'user_id' => $user->id,
            'status' => 'menunggu_review_kak',
            'keterangan' => $logMessage,
        ]);

        return back()->with('success', 'KAK berhasil diupload. Menunggu review admin.');
    }

    /**
     * Upload Link Nota Kesepakatan
     * Hanya bisa dilakukan setelah KAK disetujui
     */
    public function uploadNotaKesepakatan(Request $request, $id)
    {
        $user = Auth::guard('user')->user();
        $pengajuan = PengajuanLayanan::where('user_id', $user->id)->findOrFail($id);

        // Check status harus kak_disetujui
        if ($pengajuan->status !== 'kak_disetujui') {
            return back()->with('error', 'KAK belum disetujui oleh admin.');
        }

        $request->validate([
            'link_nota_kesepakatan' => 'required|url|max:500',
        ], [
            'link_nota_kesepakatan.required' => 'Link Nota Kesepakatan wajib diisi',
            'link_nota_kesepakatan.url' => 'Link Nota Kesepakatan harus berupa URL yang valid',
        ]);

        // Update pengajuan dengan link nota kesepakatan dan status
        $pengajuan->update([
            'link_nota_kesepakatan' => $request->link_nota_kesepakatan,
            'status' => 'dokumen_lengkap',
        ]);

        // Create log
        LogPengajuan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'user_id' => $user->id,
            'status' => 'dokumen_lengkap',
            'keterangan' => 'Link Nota Kesepakatan berhasil disubmit. Semua dokumen lengkap.',
        ]);

        return back()->with('success', 'Link Nota Kesepakatan berhasil disubmit. Dokumen Anda lengkap dan sedang diproses.');
    }

    /**
     * Show riwayat layanan
     */
    public function riwayat(Request $request)
    {
        $user = Auth::guard('user')->user();

        $query = PengajuanLayanan::with(['jenisLayanan'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_pengajuan', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_pihak', 'like', '%' . $request->search . '%')
                    ->orWhere('tentang', 'like', '%' . $request->search . '%');
            });
        }

        $pengajuans = $query->paginate(10);

        return view('user.layanan.riwayat', compact('pengajuans'));
    }

    /**
     * Show detail pengajuan
     */
    public function detail($id)
    {
        $user = Auth::guard('user')->user();
        $pengajuan = PengajuanLayanan::with(['jenisLayanan', 'dokumens', 'logs.user'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('user.layanan.detail', compact('pengajuan'));
    }

    /**
     * Download contoh dokumen
     */
    public function downloadContohDokumen($id)
    {
        $contohDokumen = ContohDokumen::where('is_active', true)->findOrFail($id);
        $contohDokumen->incrementDownload();

        $filePath = storage_path('app/public/' . $contohDokumen->file_path);

        if (!file_exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan!');
        }

        return response()->download($filePath, $contohDokumen->file_name);
    }
}
