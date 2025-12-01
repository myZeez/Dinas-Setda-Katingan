<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\ContohDokumen;
use App\Models\DokumenLayanan;
use App\Models\JenisLayanan;
use App\Models\LogPengajuan;
use App\Models\PengajuanLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="User - Dashboard",
 *     description="Endpoint untuk dashboard pengguna"
 * )
 * @OA\Tag(
 *     name="User - Layanan",
 *     description="Endpoint untuk layanan dan pengajuan"
 * )
 * @OA\Tag(
 *     name="User - Profile",
 *     description="Endpoint untuk profil pengguna"
 * )
 */
class ApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user/dashboard",
     *     summary="Dashboard Pengguna",
     *     description="Mendapatkan data statistik dashboard pengguna",
     *     operationId="userDashboard",
     *     tags={"User - Dashboard"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mendapatkan data dashboard",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="user@example.com")
     *                 ),
     *                 @OA\Property(
     *                     property="stats",
     *                     type="object",
     *                     @OA\Property(property="total_pengajuan", type="integer", example=10),
     *                     @OA\Property(property="pengajuan_diproses", type="integer", example=3),
     *                     @OA\Property(property="pengajuan_selesai", type="integer", example=5),
     *                     @OA\Property(property="pengajuan_revisi", type="integer", example=2)
     *                 ),
     *                 @OA\Property(
     *                     property="recent_pengajuan",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/PengajuanLayanan")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Tidak terautentikasi")
     * )
     */
    public function dashboard()
    {
        $user = Auth::user();

        $stats = [
            'total_pengajuan' => PengajuanLayanan::where('user_id', $user->id)->count(),
            'pengajuan_diproses' => PengajuanLayanan::where('user_id', $user->id)
                ->whereNotIn('status', ['selesai', 'ditolak'])
                ->count(),
            'pengajuan_selesai' => PengajuanLayanan::where('user_id', $user->id)
                ->where('status', 'selesai')
                ->count(),
            'pengajuan_revisi' => PengajuanLayanan::where('user_id', $user->id)
                ->whereIn('status', ['sp_revisi', 'kak_revisi'])
                ->count(),
        ];

        $recentPengajuan = PengajuanLayanan::with('jenisLayanan')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'stats' => $stats,
                'recent_pengajuan' => $recentPengajuan,
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/user/jenis-layanan",
     *     summary="Daftar Jenis Layanan",
     *     description="Mendapatkan daftar jenis layanan yang tersedia",
     *     operationId="getJenisLayanan",
     *     tags={"User - Layanan"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mendapatkan daftar jenis layanan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/JenisLayanan")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Tidak terautentikasi")
     * )
     */
    public function jenisLayanan()
    {
        $jenisLayanan = JenisLayanan::where('is_active', true)->get();

        return response()->json([
            'success' => true,
            'data' => $jenisLayanan,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/user/contoh-dokumen",
     *     summary="Daftar Contoh Dokumen",
     *     description="Mendapatkan daftar contoh/template dokumen yang tersedia untuk didownload",
     *     operationId="getContohDokumen",
     *     tags={"User - Layanan"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mendapatkan daftar contoh dokumen",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/ContohDokumen")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Tidak terautentikasi")
     * )
     */
    public function contohDokumen()
    {
        $contohDokumen = ContohDokumen::where('is_active', true)
            ->orderBy('urutan')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $contohDokumen,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/user/pengajuan",
     *     summary="Buat Pengajuan Baru",
     *     description="Membuat pengajuan layanan baru dengan upload Surat Penawaran",
     *     operationId="createPengajuan",
     *     tags={"User - Layanan"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"jenis_layanan_kode", "nama_pihak", "tentang", "surat_penawaran"},
     *                 @OA\Property(property="jenis_layanan_kode", type="string", example="konsultasi-produk-hukum", description="Kode jenis layanan"),
     *                 @OA\Property(property="nama_pihak", type="string", example="PEMERINTAH KABUPATEN KATINGAN", description="Nama daerah/pihak ketiga"),
     *                 @OA\Property(property="tentang", type="string", example="KERJA SAMA BIDANG HUKUM", description="Tentang kerja sama"),
     *                 @OA\Property(property="instansi_terkait", type="string", example="DINAS KOMINFO", description="Instansi terkait (opsional)"),
     *                 @OA\Property(property="surat_penawaran", type="string", format="binary", description="File Surat Penawaran (PDF, max 5MB)")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pengajuan berhasil dibuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pengajuan berhasil dibuat"),
     *             @OA\Property(property="data", ref="#/components/schemas/PengajuanLayanan")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Tidak terautentikasi"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function storePengajuan(Request $request)
    {
        $validated = $request->validate([
            'jenis_layanan_kode' => 'required|string|exists:jenis_layanans,kode',
            'nama_pihak' => 'required|string|max:255',
            'tentang' => 'required|string',
            'instansi_terkait' => 'nullable|string',
            'surat_penawaran' => 'required|file|mimes:pdf|max:5120',
        ]);

        $jenisLayanan = JenisLayanan::where('kode', $validated['jenis_layanan_kode'])
            ->where('is_active', true)
            ->firstOrFail();

        $user = Auth::user();

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

        LogPengajuan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'user_id' => $user->id,
            'status' => 'menunggu_review_sp',
            'keterangan' => 'Pengajuan dibuat via API. Surat Penawaran diupload, menunggu review admin.',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan berhasil dibuat dengan nomor: ' . $pengajuan->nomor_pengajuan,
            'data' => $pengajuan->load(['jenisLayanan', 'dokumens']),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/user/pengajuan",
     *     summary="Riwayat Pengajuan",
     *     description="Mendapatkan daftar riwayat pengajuan layanan pengguna",
     *     operationId="getPengajuan",
     *     tags={"User - Layanan"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter berdasarkan status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"menunggu_review_sp", "sp_revisi", "sp_disetujui", "menunggu_review_kak", "kak_revisi", "kak_disetujui", "dokumen_lengkap", "selesai", "ditolak"})
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Cari berdasarkan nomor pengajuan, nama pihak, atau tentang",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Halaman",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Jumlah data per halaman",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mendapatkan riwayat pengajuan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/PengajuanLayanan")
     *             ),
     *             @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Tidak terautentikasi")
     * )
     */
    public function listPengajuan(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->get('per_page', 10);

        $query = PengajuanLayanan::with(['jenisLayanan'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_pengajuan', 'like', '%' . $search . '%')
                    ->orWhere('nama_pihak', 'like', '%' . $search . '%')
                    ->orWhere('tentang', 'like', '%' . $search . '%');
            });
        }

        $pengajuans = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $pengajuans->items(),
            'meta' => [
                'current_page' => $pengajuans->currentPage(),
                'from' => $pengajuans->firstItem(),
                'last_page' => $pengajuans->lastPage(),
                'per_page' => $pengajuans->perPage(),
                'to' => $pengajuans->lastItem(),
                'total' => $pengajuans->total(),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/user/pengajuan/{id}",
     *     summary="Detail Pengajuan",
     *     description="Mendapatkan detail pengajuan beserta dokumen dan log",
     *     operationId="getPengajuanDetail",
     *     tags={"User - Layanan"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID Pengajuan",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mendapatkan detail pengajuan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 allOf={
     *                     @OA\Schema(ref="#/components/schemas/PengajuanLayanan"),
     *                     @OA\Schema(
     *                         @OA\Property(property="jenis_layanan", ref="#/components/schemas/JenisLayanan"),
     *                         @OA\Property(
     *                             property="dokumens",
     *                             type="array",
     *                             @OA\Items(ref="#/components/schemas/DokumenLayanan")
     *                         ),
     *                         @OA\Property(
     *                             property="logs",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer"),
     *                                 @OA\Property(property="status", type="string"),
     *                                 @OA\Property(property="keterangan", type="string"),
     *                                 @OA\Property(property="created_at", type="string", format="date-time")
     *                             )
     *                         )
     *                     )
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Tidak terautentikasi"),
     *     @OA\Response(response=404, description="Pengajuan tidak ditemukan")
     * )
     */
    public function detailPengajuan($id)
    {
        $user = Auth::user();
        $pengajuan = PengajuanLayanan::with(['jenisLayanan', 'dokumens', 'logs'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $pengajuan,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/user/pengajuan/{id}/reupload-sp",
     *     summary="Upload Ulang Surat Penawaran",
     *     description="Upload ulang Surat Penawaran jika diminta revisi oleh admin",
     *     operationId="reuploadSuratPenawaran",
     *     tags={"User - Layanan"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID Pengajuan",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"surat_penawaran"},
     *                 @OA\Property(property="surat_penawaran", type="string", format="binary", description="File Surat Penawaran (PDF, max 5MB)")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Surat Penawaran berhasil diupload ulang",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Surat Penawaran berhasil diupload ulang"),
     *             @OA\Property(property="data", ref="#/components/schemas/PengajuanLayanan")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Status tidak valid untuk reupload"),
     *     @OA\Response(response=401, description="Tidak terautentikasi"),
     *     @OA\Response(response=404, description="Pengajuan tidak ditemukan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function reuploadSuratPenawaran(Request $request, $id)
    {
        $user = Auth::user();
        $pengajuan = PengajuanLayanan::where('user_id', $user->id)->findOrFail($id);

        if ($pengajuan->status !== 'sp_revisi') {
            return response()->json([
                'success' => false,
                'message' => 'Surat Penawaran tidak dalam status revisi.',
            ], 400);
        }

        $request->validate([
            'surat_penawaran' => 'required|file|mimes:pdf|max:5120',
        ]);

        $dokumenSP = $pengajuan->dokumens()->where('jenis_dokumen', 'surat_penawaran')->first();

        if ($dokumenSP && Storage::disk('public')->exists($dokumenSP->file_path)) {
            Storage::disk('public')->delete($dokumenSP->file_path);
        }

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
        }

        $pengajuan->update([
            'status' => 'menunggu_review_sp',
            'catatan_revisi_sp' => null,
        ]);

        LogPengajuan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'user_id' => $user->id,
            'status' => 'menunggu_review_sp',
            'keterangan' => 'Surat Penawaran (Revisi v' . $versi . ') diupload ulang via API.',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Surat Penawaran berhasil diupload ulang. Menunggu review admin.',
            'data' => $pengajuan->fresh()->load(['jenisLayanan', 'dokumens']),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/user/pengajuan/{id}/upload-kak",
     *     summary="Upload KAK",
     *     description="Upload Kerangka Acuan Kerja (KAK) setelah Surat Penawaran disetujui",
     *     operationId="uploadKAK",
     *     tags={"User - Layanan"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID Pengajuan",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"file_kak"},
     *                 @OA\Property(property="file_kak", type="string", format="binary", description="File KAK (PDF, max 5MB)")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="KAK berhasil diupload",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="KAK berhasil diupload"),
     *             @OA\Property(property="data", ref="#/components/schemas/PengajuanLayanan")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Status tidak valid untuk upload KAK"),
     *     @OA\Response(response=401, description="Tidak terautentikasi"),
     *     @OA\Response(response=404, description="Pengajuan tidak ditemukan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function uploadKAK(Request $request, $id)
    {
        $user = Auth::user();
        $pengajuan = PengajuanLayanan::where('user_id', $user->id)->findOrFail($id);

        if (!in_array($pengajuan->status, ['sp_disetujui', 'kak_revisi'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mengupload KAK pada status ini.',
            ], 400);
        }

        $request->validate([
            'file_kak' => 'required|file|mimes:pdf|max:5120',
        ]);

        $dokumenKAK = $pengajuan->dokumens()->where('jenis_dokumen', 'kerangka_acuan_kerja')->first();

        if ($dokumenKAK && Storage::disk('public')->exists($dokumenKAK->file_path)) {
            Storage::disk('public')->delete($dokumenKAK->file_path);
        }

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

        $pengajuan->update([
            'file_kak' => $filePath,
            'status' => 'menunggu_review_kak',
            'catatan_revisi_kak' => null,
        ]);

        $logMessage = $versi > 1
            ? 'KAK (Revisi v' . $versi . ') diupload ulang via API.'
            : 'KAK berhasil diupload via API.';

        LogPengajuan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'user_id' => $user->id,
            'status' => 'menunggu_review_kak',
            'keterangan' => $logMessage,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'KAK berhasil diupload. Menunggu review admin.',
            'data' => $pengajuan->fresh()->load(['jenisLayanan', 'dokumens']),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/user/pengajuan/{id}/upload-nota",
     *     summary="Upload Link Nota Kesepakatan",
     *     description="Submit link Nota Kesepakatan setelah KAK disetujui",
     *     operationId="uploadNotaKesepakatan",
     *     tags={"User - Layanan"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID Pengajuan",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"link_nota_kesepakatan"},
     *             @OA\Property(property="link_nota_kesepakatan", type="string", format="url", example="https://drive.google.com/file/d/xxx/view", description="Link ke dokumen Nota Kesepakatan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Link Nota Kesepakatan berhasil disubmit",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Link Nota Kesepakatan berhasil disubmit"),
     *             @OA\Property(property="data", ref="#/components/schemas/PengajuanLayanan")
     *         )
     *     ),
     *     @OA\Response(response=400, description="KAK belum disetujui"),
     *     @OA\Response(response=401, description="Tidak terautentikasi"),
     *     @OA\Response(response=404, description="Pengajuan tidak ditemukan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function uploadNotaKesepakatan(Request $request, $id)
    {
        $user = Auth::user();
        $pengajuan = PengajuanLayanan::where('user_id', $user->id)->findOrFail($id);

        if ($pengajuan->status !== 'kak_disetujui') {
            return response()->json([
                'success' => false,
                'message' => 'KAK belum disetujui oleh admin.',
            ], 400);
        }

        $request->validate([
            'link_nota_kesepakatan' => 'required|url|max:500',
        ]);

        $pengajuan->update([
            'link_nota_kesepakatan' => $request->link_nota_kesepakatan,
            'status' => 'dokumen_lengkap',
        ]);

        LogPengajuan::create([
            'pengajuan_layanan_id' => $pengajuan->id,
            'user_id' => $user->id,
            'status' => 'dokumen_lengkap',
            'keterangan' => 'Link Nota Kesepakatan berhasil disubmit via API. Semua dokumen lengkap.',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Link Nota Kesepakatan berhasil disubmit. Dokumen Anda lengkap.',
            'data' => $pengajuan->fresh()->load(['jenisLayanan', 'dokumens']),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/user/profile",
     *     summary="Update Profil",
     *     description="Memperbarui data profil pengguna",
     *     operationId="updateProfile",
     *     tags={"User - Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "jabatan", "no_whatsapp", "jenis_kelamin", "instansi", "biro_bagian"},
     *             @OA\Property(property="name", type="string", example="John Doe", description="Nama lengkap"),
     *             @OA\Property(property="jabatan", type="string", example="Kepala Bagian", description="Jabatan"),
     *             @OA\Property(property="nip", type="string", example="199001012020011001", description="NIP (opsional)"),
     *             @OA\Property(property="no_whatsapp", type="string", example="081234567890", description="Nomor WhatsApp"),
     *             @OA\Property(property="jenis_kelamin", type="string", enum={"Laki-laki", "Perempuan"}, example="Laki-laki"),
     *             @OA\Property(property="instansi", type="string", example="Dinas Komunikasi dan Informatika"),
     *             @OA\Property(property="biro_bagian", type="string", example="Bagian Umum", description="Biro/Bagian/Bidang/Seksi")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profil berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Profil berhasil diperbarui"),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Tidak terautentikasi"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'no_whatsapp' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'instansi' => 'required|string|max:255',
            'biro_bagian' => 'required|string|max:255',
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => $user->fresh(),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/user/profile/password",
     *     summary="Update Password",
     *     description="Memperbarui password pengguna",
     *     operationId="updatePassword",
     *     tags={"User - Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"current_password", "password", "password_confirmation"},
     *             @OA\Property(property="current_password", type="string", format="password", example="oldpassword123", description="Password saat ini"),
     *             @OA\Property(property="password", type="string", format="password", example="newpassword123", description="Password baru (min 6 karakter)"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="newpassword123", description="Konfirmasi password baru")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Password berhasil diperbarui")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Password saat ini tidak sesuai"),
     *     @OA\Response(response=401, description="Tidak terautentikasi"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini tidak sesuai.',
            ], 400);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui.',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/user/contoh-dokumen/{id}/download",
     *     summary="Download Contoh Dokumen",
     *     description="Download file contoh/template dokumen",
     *     operationId="downloadContohDokumen",
     *     tags={"User - Layanan"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID Contoh Dokumen",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="File download",
     *         @OA\MediaType(
     *             mediaType="application/octet-stream",
     *             @OA\Schema(type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Tidak terautentikasi"),
     *     @OA\Response(response=404, description="File tidak ditemukan")
     * )
     */
    public function downloadContohDokumen($id)
    {
        $contohDokumen = ContohDokumen::where('is_active', true)->findOrFail($id);
        $contohDokumen->incrementDownload();

        $filePath = storage_path('app/public/' . $contohDokumen->file_path);

        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan.',
            ], 404);
        }

        return response()->download($filePath, $contohDokumen->file_name);
    }
}
