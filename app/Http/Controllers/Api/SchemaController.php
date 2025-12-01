<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * @OA\Schema(
 *     schema="JenisLayanan",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nama", type="string", example="Konsultasi Penyusunan Produk Hukum Daerah"),
 *     @OA\Property(property="kode", type="string", example="konsultasi-produk-hukum"),
 *     @OA\Property(property="deskripsi", type="string", example="Layanan konsultasi untuk penyusunan produk hukum daerah"),
 *     @OA\Property(property="persyaratan", type="string", example="1. Surat Permohonan\n2. Draft Dokumen"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="PengajuanLayanan",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nomor_pengajuan", type="string", example="PL-2025-0001"),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="jenis_layanan_id", type="integer", example=1),
 *     @OA\Property(property="perihal", type="string", example="Konsultasi Penyusunan Perda"),
 *     @OA\Property(property="keterangan", type="string", example="Mohon konsultasi terkait penyusunan Perda tentang..."),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"menunggu_review_sp", "revisi_sp", "menunggu_upload_kak", "menunggu_review_kak", "revisi_kak", "menunggu_upload_nota", "diproses", "selesai", "ditolak"},
 *         example="diproses"
 *     ),
 *     @OA\Property(property="catatan_admin", type="string", example="Dokumen sudah lengkap"),
 *     @OA\Property(property="tanggal_pengajuan", type="string", format="date", example="2025-12-01"),
 *     @OA\Property(property="tanggal_selesai", type="string", format="date", example="2025-12-15"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="DokumenLayanan",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="pengajuan_layanan_id", type="integer", example=1),
 *     @OA\Property(property="jenis_dokumen", type="string", enum={"surat_penawaran", "kak", "nota_kesepakatan", "dokumen_hasil"}, example="surat_penawaran"),
 *     @OA\Property(property="nama_file", type="string", example="surat_penawaran.pdf"),
 *     @OA\Property(property="path_file", type="string", example="dokumen_layanan/surat_penawaran.pdf"),
 *     @OA\Property(property="status", type="string", enum={"pending", "approved", "rejected"}, example="approved"),
 *     @OA\Property(property="catatan", type="string", example="Dokumen sudah sesuai"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *     @OA\Property(property="jabatan", type="string", example="Kepala Bagian"),
 *     @OA\Property(property="nip", type="string", example="199001012020011001"),
 *     @OA\Property(property="nik", type="string", example="6201010101010001"),
 *     @OA\Property(property="no_whatsapp", type="string", example="081234567890"),
 *     @OA\Property(property="jenis_kelamin", type="string", enum={"Laki-laki", "Perempuan"}, example="Laki-laki"),
 *     @OA\Property(property="instansi", type="string", example="Dinas Komunikasi dan Informatika"),
 *     @OA\Property(property="biro_bagian", type="string", example="Bagian Umum"),
 *     @OA\Property(property="role", type="string", enum={"user", "admin", "super_admin"}, example="user"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Berita",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="judul", type="string", example="Rapat Koordinasi Penyusunan RPJMD"),
 *     @OA\Property(property="slug", type="string", example="rapat-koordinasi-penyusunan-rpjmd"),
 *     @OA\Property(property="ringkasan", type="string", example="Dinas Setda menggelar rapat koordinasi..."),
 *     @OA\Property(property="konten", type="string", example="<p>Isi berita lengkap...</p>"),
 *     @OA\Property(property="gambar", type="string", example="berita/gambar.jpg"),
 *     @OA\Property(property="penulis", type="string", example="Admin"),
 *     @OA\Property(property="is_published", type="boolean", example=true),
 *     @OA\Property(property="published_at", type="string", format="date-time"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="ContohDokumen",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nama", type="string", example="Template Surat Penawaran"),
 *     @OA\Property(property="deskripsi", type="string", example="Template untuk surat penawaran layanan"),
 *     @OA\Property(property="jenis_dokumen", type="string", enum={"surat_penawaran", "kak", "nota_kesepakatan"}, example="surat_penawaran"),
 *     @OA\Property(property="file_path", type="string", example="contoh_dokumen/template_sp.docx"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Error message")
 * )
 *
 * @OA\Schema(
 *     schema="ValidationError",
 *     type="object",
 *     @OA\Property(property="message", type="string", example="The given data was invalid."),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         @OA\AdditionalProperties(
 *             type="array",
 *             @OA\Items(type="string")
 *         )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="PaginationMeta",
 *     type="object",
 *     @OA\Property(property="current_page", type="integer", example=1),
 *     @OA\Property(property="from", type="integer", example=1),
 *     @OA\Property(property="last_page", type="integer", example=10),
 *     @OA\Property(property="per_page", type="integer", example=15),
 *     @OA\Property(property="to", type="integer", example=15),
 *     @OA\Property(property="total", type="integer", example=150)
 * )
 */
class SchemaController extends Controller
{
    // File ini hanya untuk definisi schema Swagger
}
