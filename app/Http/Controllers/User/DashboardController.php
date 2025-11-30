<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show dashboard
     */
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_layanan' => 0,
            'layanan_proses' => 0,
            'layanan_selesai' => 0,
        ];

        return view('user.dashboard', compact('user', 'stats'));
    }
}
