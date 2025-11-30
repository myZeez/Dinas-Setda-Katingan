<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorStat extends Model
{
    protected $fillable = [
        'year',
        'month',
        'total_visits',
        'unique_visitors',
    ];

    /**
     * Update or create monthly stats
     */
    public static function updateMonthlyStats($year, $month)
    {
        $stats = Visitor::whereYear('visit_date', $year)
            ->whereMonth('visit_date', $month)
            ->selectRaw('COUNT(*) as total, COUNT(DISTINCT ip_address) as unique_count')
            ->first();

        return self::updateOrCreate(
            ['year' => $year, 'month' => $month],
            [
                'total_visits' => $stats->total ?? 0,
                'unique_visitors' => $stats->unique_count ?? 0,
            ]
        );
    }

    /**
     * Get stats for a specific year (all months)
     */
    public static function getYearStats($year)
    {
        $stats = self::where('year', $year)
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Fill missing months with 0
        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $result[$i] = [
                'total_visits' => $stats->get($i)->total_visits ?? 0,
                'unique_visitors' => $stats->get($i)->unique_visitors ?? 0,
            ];
        }

        return $result;
    }
}
