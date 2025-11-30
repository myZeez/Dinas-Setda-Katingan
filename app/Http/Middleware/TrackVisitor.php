<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only track GET requests and exclude admin/api routes
        if ($request->isMethod('GET') && !$this->shouldExclude($request)) {
            $this->trackVisit($request);
        }

        return $next($request);
    }

    /**
     * Check if the request should be excluded from tracking
     */
    protected function shouldExclude(Request $request): bool
    {
        $excludedPaths = [
            'admin',
            'api',
            'sanctum',
            'livewire',
            '_debugbar',
            'storage',
            'favicon.ico',
        ];

        foreach ($excludedPaths as $path) {
            if ($request->is($path) || $request->is($path . '/*')) {
                return true;
            }
        }

        // Exclude static assets
        $excludedExtensions = ['js', 'css', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'eot'];
        $extension = pathinfo($request->path(), PATHINFO_EXTENSION);
        if (in_array(strtolower($extension), $excludedExtensions)) {
            return true;
        }

        return false;
    }

    /**
     * Track the visit
     */
    protected function trackVisit(Request $request): void
    {
        try {
            $ip = $request->ip();
            $userAgent = $request->userAgent() ?? '';
            $today = now()->toDateString();

            // Parse user agent for device info
            $deviceInfo = Visitor::parseUserAgent($userAgent);

            // Create visitor record
            Visitor::create([
                'ip_address' => $ip,
                'user_agent' => substr($userAgent, 0, 255),
                'page_visited' => $request->path(),
                'referer' => substr($request->header('referer') ?? '', 0, 255),
                'device_type' => $deviceInfo['device_type'],
                'browser' => $deviceInfo['browser'],
                'os' => $deviceInfo['os'],
                'visit_date' => $today,
            ]);
        } catch (\Exception $e) {
            // Silently fail - don't break the site if tracking fails
            \Log::error('Visitor tracking failed: ' . $e->getMessage());
        }
    }
}
