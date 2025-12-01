<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * @OA\Post(
 *     path="/api/user/login",
 *     summary="Login Pengguna",
 *     description="Login untuk pengguna layanan dan mendapatkan access token",
 *     operationId="userLogin",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login berhasil",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Login berhasil."),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="user",
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="John Doe"),
 *                     @OA\Property(property="email", type="string", example="user@example.com"),
 *                     @OA\Property(property="role", type="string", example="user")
 *                 ),
 *                 @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."),
 *                 @OA\Property(property="token_type", type="string", example="Bearer")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Kredensial tidak valid",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Email atau password salah.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Akun tidak aktif atau bukan pengguna",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Akun Anda tidak aktif.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validasi gagal",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The email field is required."),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/admin/login",
 *     summary="Login Admin",
 *     description="Login untuk administrator dan mendapatkan access token",
 *     operationId="adminLogin",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="admin123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login berhasil",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Login berhasil."),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="user",
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Administrator"),
 *                     @OA\Property(property="email", type="string", example="admin@example.com"),
 *                     @OA\Property(property="role", type="string", example="admin")
 *                 ),
 *                 @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."),
 *                 @OA\Property(property="token_type", type="string", example="Bearer")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Kredensial tidak valid",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Email atau password salah.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Bukan administrator",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Akun ini bukan akun administrator.")
 *         )
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/user/logout",
 *     summary="Logout Pengguna",
 *     description="Logout dan mencabut access token pengguna",
 *     operationId="userLogout",
 *     tags={"Authentication"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Logout berhasil",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Logout berhasil.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Tidak terautentikasi",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/admin/logout",
 *     summary="Logout Admin",
 *     description="Logout dan mencabut access token admin",
 *     operationId="adminLogout",
 *     tags={"Authentication"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Logout berhasil",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Logout berhasil.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Tidak terautentikasi",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/user/profile",
 *     summary="Profil Pengguna",
 *     description="Mendapatkan data profil pengguna yang sedang login",
 *     operationId="userProfile",
 *     tags={"User"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Berhasil mendapatkan profil",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="email", type="string", example="user@example.com"),
 *                 @OA\Property(property="jabatan", type="string", example="Kepala Bagian"),
 *                 @OA\Property(property="nip", type="string", example="199001012020011001"),
 *                 @OA\Property(property="nik", type="string", example="6201010101010001"),
 *                 @OA\Property(property="no_whatsapp", type="string", example="081234567890"),
 *                 @OA\Property(property="jenis_kelamin", type="string", example="Laki-laki"),
 *                 @OA\Property(property="instansi", type="string", example="Dinas Komunikasi dan Informatika"),
 *                 @OA\Property(property="biro_bagian", type="string", example="Bagian Umum"),
 *                 @OA\Property(property="role", type="string", example="user"),
 *                 @OA\Property(property="is_active", type="boolean", example=true)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Tidak terautentikasi",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/admin/profile",
 *     summary="Profil Admin",
 *     description="Mendapatkan data profil admin yang sedang login",
 *     operationId="adminProfile",
 *     tags={"Admin"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Berhasil mendapatkan profil",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Administrator"),
 *                 @OA\Property(property="email", type="string", example="admin@example.com"),
 *                 @OA\Property(property="role", type="string", example="admin"),
 *                 @OA\Property(property="is_active", type="boolean", example=true)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Tidak terautentikasi",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/test",
 *     summary="Test API",
 *     description="Endpoint untuk testing apakah API berfungsi",
 *     operationId="testApi",
 *     tags={"Public"},
 *     @OA\Response(
 *         response=200,
 *         description="API berfungsi",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="API is working!"),
 *             @OA\Property(property="timestamp", type="string", example="2025-12-01 12:00:00")
 *         )
 *     )
 * )
 */
class AuthController extends Controller
{
    // Dokumentasi API diatas hanya untuk referensi Swagger
    // Implementasi sebenarnya ada di:
    // - App\Http\Controllers\User\AuthController
    // - App\Http\Controllers\Admin\AuthController
}
