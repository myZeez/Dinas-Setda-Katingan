<?php

namespace App\Http\Controllers\User;

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
     * Step 1: Show pilihan layanan
     */
    public function index()
    {
        $jenisLayanan = JenisLayanan::where('is_active', true)->get();
        return view('user.layanan.index', compact('jenisLayanan'));
    }

    /**
     * Step 2: Show form pengajuan
     */
    public function create($kode)
    {
        $jenisLayanan = JenisLayanan::where('kode', $kode)->where('is_active', true)->firstOrFail();
        return view('user.layanan.create', compact('jenisLayanan'));
    }

    /**
     * Step 2: Store form data to session and go to step 3
     */
    public function storeStep2(Request $request, $kode)
    {
        $jenisLayanan = JenisLayanan::where('kode', $kode)->where('is_active', true)->firstOrFail();

        $validated = $request->validate([
            'nama_pihak' => 'required|string|max:255',
            'tentang' => 'required|string',
            'instansi_terkait' => 'nullable|string',
        ], [
            'nama_pihak.required' => 'Nama Daerah/Pihak Ketiga wajib diisi',
            'tentang.required' => 'Tentang kerja sama wajib diisi',
        ]);

        // Store to session
        session([
            'layanan_step2' => [
                'jenis_layanan_id' => $jenisLayanan->id,
                'jenis_layanan_kode' => $jenisLayanan->kode,
                'nama_pihak' => $validated['nama_pihak'],
                'tentang' => $validated['tentang'],
                'instansi_terkait' => $validated['instansi_terkait'],
            ]
        ]);

        return redirect()->route('user.layanan.upload', $kode);
    }

    /**
     * Step 3: Show upload dokumen form
     */
    public function upload($kode)
    {
        $jenisLayanan = JenisLayanan::where('kode', $kode)->where('is_active', true)->firstOrFail();

        // Check if step 2 data exists
        if (!session('layanan_step2')) {
            return redirect()->route('user.layanan.create', $kode)
                ->with('error', 'Silakan isi form terlebih dahulu.');
        }

        $step2Data = session('layanan_step2');

        return view('user.layanan.upload', compact('jenisLayanan', 'step2Data'));
    }

    /**
     * Step 3: Store pengajuan with documents
     */
    public function store(Request $request, $kode)
    {
        $jenisLayanan = JenisLayanan::where('kode', $kode)->where('is_active', true)->firstOrFail();

        // Check if step 2 data exists
        if (!session('layanan_step2')) {
            return redirect()->route('user.layanan.create', $kode)
                ->with('error', 'Silakan isi form terlebih dahulu.');
        }

        $validated = $request->validate([
            'surat_penawaran' => 'required|file|mimes:pdf|max:5120',
            'kerangka_acuan_kerja' => 'required|file|mimes:pdf|max:5120',
            'draft_naskah' => 'required|file|mimes:doc,docx|max:10240',
        ], [
            'surat_penawaran.required' => 'Surat Penawaran wajib diupload',
            'surat_penawaran.mimes' => 'Surat Penawaran harus format PDF',
            'surat_penawaran.max' => 'Ukuran file maksimal 5MB',
            'kerangka_acuan_kerja.required' => 'Kerangka Acuan Kerja wajib diupload',
            'kerangka_acuan_kerja.mimes' => 'Kerangka Acuan Kerja harus format PDF',
            'kerangka_acuan_kerja.max' => 'Ukuran file maksimal 5MB',
            'draft_naskah.required' => 'Draft Naskah wajib diupload',
            'draft_naskah.mimes' => 'Draft Naskah harus format DOC/DOCX',
            'draft_naskah.max' => 'Ukuran file maksimal 10MB',
        ]);

        $step2Data = session('layanan_step2');
        $user = Auth::user();

        // Create Pengajuan
        $pengajuan = PengajuanLayanan::create([
            'nomor_pengajuan' => PengajuanLayanan::generateNomorPengajuan($jenisLayanan->kode),
            'user_id' => $user->id,
            'jenis_layanan_id' => $jenisLayanan->id,
            'nama_pihak' => $step2Data['nama_pihak'],
            'tentang' => $step2Data['tentang'],
            'instansi_terkait' => $step2Data['instansi_terkait'],
            'status' => 'diajukan',
            'tanggal_pengajuan' => now(),
        ]);

        // Upload documents
        $dokumenTypes = [
            'surat_penawaran' => 'surat_penawaran',
            'kerangka_acuan_kerja' => 'kerangka_acuan_kerja',
            'draft_naskah' => 'draft_naskah',
        ];

        foreach ($dokumenTypes as $inputName => $jenisDokumen) {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $fileName = time() . '_' . $jenisDokumen . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('dokumen_layanan/' . $pengajuan->id, $fileName, 'public');

                DokumenLayanan::create([
                    'pengajuan_layanan_id' => $pengajuan->id,
                    'jenis_dokumen' => $jenisDokumen,
                    'nama_file' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'status' => 'diproses',
                ]);
            }
        }

        // Create log
        LogPengajuan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'user_id' => $user->id,
            'status' => 'diajukan',
            'keterangan' => 'Pengajuan layanan berhasil dibuat',
        ]);

        // Clear session
        session()->forget('layanan_step2');

        return redirect()->route('user.layanan.detail', $pengajuan->id)
            ->with('success', 'Pengajuan layanan berhasil dibuat dengan nomor: ' . $pengajuan->nomor_pengajuan);
    }

    /**
     * Show riwayat layanan
     */
    public function riwayat(Request $request)
    {
        $user = Auth::user();

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
        $user = Auth::user();
        $pengajuan = PengajuanLayanan::with(['jenisLayanan', 'dokumens', 'logs.user'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('user.layanan.detail', compact('pengajuan'));
    }

    /**
     * Re-upload dokumen yang perlu koreksi
     */
    public function reupload(Request $request, $id, $dokumenId)
    {
        $user = Auth::user();
        $pengajuan = PengajuanLayanan::where('user_id', $user->id)->findOrFail($id);
        $dokumen = DokumenLayanan::where('pengajuan_layanan_id', $id)->findOrFail($dokumenId);

        // Check if dokumen needs correction
        if ($dokumen->status !== 'koreksi') {
            return back()->with('error', 'Dokumen ini tidak memerlukan koreksi.');
        }

        // Determine allowed mimes based on jenis_dokumen
        $mimes = $dokumen->jenis_dokumen === 'draft_naskah' ? 'doc,docx' : 'pdf';
        $maxSize = $dokumen->jenis_dokumen === 'draft_naskah' ? 10240 : 5120;

        $request->validate([
            'file' => "required|file|mimes:{$mimes}|max:{$maxSize}",
        ]);

        // Delete old file
        if (Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        // Upload new file
        $file = $request->file('file');
        $fileName = time() . '_' . $dokumen->jenis_dokumen . '_v' . ($dokumen->versi + 1) . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('dokumen_layanan/' . $pengajuan->id, $fileName, 'public');

        // Update dokumen
        $dokumen->update([
            'nama_file' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'status' => 'diproses',
            'versi' => $dokumen->versi + 1,
            'catatan' => null,
        ]);

        // Create log
        LogPengajuan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'user_id' => $user->id,
            'status' => $pengajuan->status,
            'keterangan' => 'Dokumen ' . $dokumen->jenis_dokumen_label . ' (versi ' . $dokumen->versi . ') berhasil diupload ulang',
        ]);

        return back()->with('success', 'Dokumen berhasil diupload ulang.');
    }
}
