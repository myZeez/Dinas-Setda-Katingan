<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController as UserAuthController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\User\ApiController as UserApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes - No authentication required
Route::prefix('user')->group(function () {
    Route::post('/login', [UserAuthController::class, 'apiLogin']);
});

Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'apiLogin']);
});

// Protected routes - Requires Passport token authentication
Route::middleware('auth:api')->group(function () {

    // User routes
    Route::prefix('user')->group(function () {
        // Auth
        Route::post('/logout', [UserAuthController::class, 'apiLogout']);
        Route::get('/profile', [UserAuthController::class, 'profile']);

        // Dashboard
        Route::get('/dashboard', [UserApiController::class, 'dashboard']);

        // Layanan
        Route::get('/jenis-layanan', [UserApiController::class, 'jenisLayanan']);
        Route::get('/contoh-dokumen', [UserApiController::class, 'contohDokumen']);
        Route::get('/contoh-dokumen/{id}/download', [UserApiController::class, 'downloadContohDokumen']);

        // Pengajuan
        Route::get('/pengajuan', [UserApiController::class, 'listPengajuan']);
        Route::post('/pengajuan', [UserApiController::class, 'storePengajuan']);
        Route::get('/pengajuan/{id}', [UserApiController::class, 'detailPengajuan']);
        Route::post('/pengajuan/{id}/reupload-sp', [UserApiController::class, 'reuploadSuratPenawaran']);
        Route::post('/pengajuan/{id}/upload-kak', [UserApiController::class, 'uploadKAK']);
        Route::post('/pengajuan/{id}/upload-nota', [UserApiController::class, 'uploadNotaKesepakatan']);

        // Profile
        Route::put('/profile', [UserApiController::class, 'updateProfile']);
        Route::put('/profile/password', [UserApiController::class, 'updatePassword']);
    });

    // Admin routes
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'apiLogout']);
        Route::get('/profile', [AdminAuthController::class, 'profile']);

        // Add more admin API routes here
        // Route::apiResource('/layanan', AdminLayananController::class);
        // Route::apiResource('/berita', AdminBeritaController::class);
    });
});

// Test route to verify API is working
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working!',
        'timestamp' => now()->toDateTimeString(),
    ]);
});
