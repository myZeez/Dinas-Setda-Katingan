<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="API Dinas Sekretariat Daerah Katingan",
 *     version="1.0.0",
 *     description="Dokumentasi API untuk sistem layanan Dinas Sekretariat Daerah Kabupaten Katingan. API ini menggunakan Laravel Passport untuk autentikasi berbasis token.",
 *     @OA\Contact(
 *         email="admin@setda-katingan.go.id",
 *         name="Tim IT Setda Katingan"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Masukkan token yang didapat dari endpoint login. Format: Bearer {token}"
 * )
 *
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoint untuk autentikasi pengguna (login, logout, register)"
 * )
 * @OA\Tag(
 *     name="User",
 *     description="Endpoint untuk pengguna layanan"
 * )
 * @OA\Tag(
 *     name="Admin",
 *     description="Endpoint untuk administrator"
 * )
 * @OA\Tag(
 *     name="Layanan",
 *     description="Endpoint untuk layanan dan pengajuan"
 * )
 * @OA\Tag(
 *     name="Public",
 *     description="Endpoint publik tanpa autentikasi"
 * )
 */
class SwaggerController extends Controller
{
    //
}
