<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Token;
use Laravel\Passport\Client;
use App\Models\User;

class PassportController extends Controller
{
    /**
     * Display passport monitoring dashboard
     */
    public function index(Request $request)
    {
        // Get statistics
        $stats = [
            'total_tokens' => DB::table('oauth_access_tokens')->count(),
            'active_tokens' => DB::table('oauth_access_tokens')
                ->where('revoked', false)
                ->where('expires_at', '>', now())
                ->count(),
            'revoked_tokens' => DB::table('oauth_access_tokens')->where('revoked', true)->count(),
            'expired_tokens' => DB::table('oauth_access_tokens')
                ->where('expires_at', '<', now())
                ->where('revoked', false)
                ->count(),
            'total_clients' => DB::table('oauth_clients')->count(),
            // Check grant_types for personal access clients (Passport v12+)
            'personal_clients' => DB::table('oauth_clients')
                ->where('grant_types', 'like', '%personal_access%')
                ->count(),
        ];

        // Get OAuth Clients
        $clients = DB::table('oauth_clients')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get recent tokens with user info
        $query = DB::table('oauth_access_tokens')
            ->join('users', 'oauth_access_tokens.user_id', '=', 'users.id')
            ->select(
                'oauth_access_tokens.id',
                'oauth_access_tokens.user_id',
                'oauth_access_tokens.client_id',
                'oauth_access_tokens.name',
                'oauth_access_tokens.scopes',
                'oauth_access_tokens.revoked',
                'oauth_access_tokens.created_at',
                'oauth_access_tokens.updated_at',
                'oauth_access_tokens.expires_at',
                'users.name as user_name',
                'users.email as user_email',
                'users.role as user_role'
            )
            ->orderBy('oauth_access_tokens.created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('oauth_access_tokens.revoked', false)
                    ->where('oauth_access_tokens.expires_at', '>', now());
            } elseif ($request->status === 'revoked') {
                $query->where('oauth_access_tokens.revoked', true);
            } elseif ($request->status === 'expired') {
                $query->where('oauth_access_tokens.expires_at', '<', now())
                    ->where('oauth_access_tokens.revoked', false);
            }
        }

        // Filter by user role
        if ($request->filled('role')) {
            $query->where('users.role', $request->role);
        }

        // Search by user name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        $tokens = $query->paginate(15)->withQueryString();

        // Get users with active tokens count
        $usersWithTokens = DB::table('oauth_access_tokens')
            ->join('users', 'oauth_access_tokens.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.role',
                DB::raw('COUNT(oauth_access_tokens.id) as total_tokens'),
                DB::raw('SUM(CASE WHEN oauth_access_tokens.revoked = 0 AND oauth_access_tokens.expires_at > NOW() THEN 1 ELSE 0 END) as active_tokens')
            )
            ->groupBy('users.id', 'users.name', 'users.email', 'users.role')
            ->orderBy('active_tokens', 'desc')
            ->limit(10)
            ->get();

        return view('admin.passport.index', compact('stats', 'clients', 'tokens', 'usersWithTokens'));
    }

    /**
     * View tokens for specific user
     */
    public function userTokens($userId)
    {
        $user = User::findOrFail($userId);

        $tokens = DB::table('oauth_access_tokens')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.passport.user-tokens', compact('user', 'tokens'));
    }

    /**
     * Revoke a specific token
     */
    public function revokeToken(Request $request, $tokenId)
    {
        DB::table('oauth_access_tokens')
            ->where('id', $tokenId)
            ->update(['revoked' => true]);

        return back()->with('success', 'Token berhasil dicabut.');
    }

    /**
     * Revoke all tokens for a user
     */
    public function revokeUserTokens(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        DB::table('oauth_access_tokens')
            ->where('user_id', $userId)
            ->update(['revoked' => true]);

        return back()->with('success', "Semua token untuk {$user->name} berhasil dicabut.");
    }

    /**
     * Revoke all expired tokens
     */
    public function revokeExpiredTokens()
    {
        $count = DB::table('oauth_access_tokens')
            ->where('expires_at', '<', now())
            ->where('revoked', false)
            ->update(['revoked' => true]);

        return back()->with('success', "{$count} token kadaluarsa berhasil dicabut.");
    }

    /**
     * Delete revoked tokens (cleanup)
     */
    public function cleanupTokens()
    {
        $count = DB::table('oauth_access_tokens')
            ->where('revoked', true)
            ->delete();

        return back()->with('success', "{$count} token yang dicabut berhasil dihapus.");
    }

    /**
     * View OAuth Clients
     */
    public function clients()
    {
        $clients = DB::table('oauth_clients')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.passport.clients', compact('clients'));
    }
}
