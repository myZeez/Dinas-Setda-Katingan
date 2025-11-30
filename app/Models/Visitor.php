<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Visitor extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'page_visited',
        'referer',
        'country',
        'city',
        'device_type',
        'browser',
        'os',
        'visit_date',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    /**
     * Get monthly visitor count for a specific year
     */
    public static function getMonthlyStats($year)
    {
        return self::select(
            DB::raw('MONTH(visit_date) as month'),
            DB::raw('COUNT(*) as total_visits'),
            DB::raw('COUNT(DISTINCT ip_address) as unique_visitors')
        )
        ->whereYear('visit_date', $year)
        ->groupBy(DB::raw('MONTH(visit_date)'))
        ->orderBy('month')
        ->get()
        ->keyBy('month');
    }

    /**
     * Get yearly visitor count
     */
    public static function getYearlyStats($startYear = null, $endYear = null)
    {
        $endYear = $endYear ?? date('Y');
        $startYear = $startYear ?? ($endYear - 5);

        return self::select(
            DB::raw('YEAR(visit_date) as year'),
            DB::raw('COUNT(*) as total_visits'),
            DB::raw('COUNT(DISTINCT ip_address) as unique_visitors')
        )
        ->whereYear('visit_date', '>=', $startYear)
        ->whereYear('visit_date', '<=', $endYear)
        ->groupBy(DB::raw('YEAR(visit_date)'))
        ->orderBy('year')
        ->get()
        ->keyBy('year');
    }

    /**
     * Get today's visitor count
     */
    public static function getTodayCount()
    {
        return self::whereDate('visit_date', today())->count();
    }

    /**
     * Get total visitor count
     */
    public static function getTotalCount()
    {
        return self::count();
    }

    /**
     * Get unique visitors today
     */
    public static function getTodayUniqueCount()
    {
        return self::whereDate('visit_date', today())
            ->distinct('ip_address')
            ->count('ip_address');
    }

    /**
     * Get this month visitor count
     */
    public static function getThisMonthCount()
    {
        return self::whereYear('visit_date', date('Y'))
            ->whereMonth('visit_date', date('m'))
            ->count();
    }

    /**
     * Get this year visitor count
     */
    public static function getThisYearCount()
    {
        return self::whereYear('visit_date', date('Y'))->count();
    }

    /**
     * Parse user agent to get device info
     */
    public static function parseUserAgent($userAgent)
    {
        $device = 'desktop';
        $browser = 'Unknown';
        $os = 'Unknown';

        // Detect device type
        if (preg_match('/mobile|android|iphone|ipad|ipod|blackberry|windows phone/i', $userAgent)) {
            $device = preg_match('/tablet|ipad/i', $userAgent) ? 'tablet' : 'mobile';
        }

        // Detect browser
        if (preg_match('/MSIE|Trident/i', $userAgent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/Edge/i', $userAgent)) {
            $browser = 'Microsoft Edge';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Opera|OPR/i', $userAgent)) {
            $browser = 'Opera';
        }

        // Detect OS
        if (preg_match('/Windows NT 10/i', $userAgent)) {
            $os = 'Windows 10';
        } elseif (preg_match('/Windows NT 6.3/i', $userAgent)) {
            $os = 'Windows 8.1';
        } elseif (preg_match('/Windows NT 6.2/i', $userAgent)) {
            $os = 'Windows 8';
        } elseif (preg_match('/Windows NT 6.1/i', $userAgent)) {
            $os = 'Windows 7';
        } elseif (preg_match('/Windows/i', $userAgent)) {
            $os = 'Windows';
        } elseif (preg_match('/Mac OS X/i', $userAgent)) {
            $os = 'Mac OS X';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            $os = 'Linux';
        } elseif (preg_match('/Android/i', $userAgent)) {
            $os = 'Android';
        } elseif (preg_match('/iOS|iPhone|iPad/i', $userAgent)) {
            $os = 'iOS';
        }

        return [
            'device_type' => $device,
            'browser' => $browser,
            'os' => $os,
        ];
    }
}
