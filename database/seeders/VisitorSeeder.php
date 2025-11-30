<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visitor;
use Carbon\Carbon;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data like the image reference
        $sampleData = [
            // 2024 data
            2024 => [
                1 => 291472,  // Jan
                2 => 223877,  // Feb
                3 => 319108,  // Mar
                4 => 467860,  // Apr
                5 => 528631,  // Mei
                6 => 625819,  // Jun
                7 => 663697,  // Jul
                8 => 414572,  // Agu
                9 => 466936,  // Sep
                10 => 398873, // Okt
                11 => 243572, // Nov
                12 => 359185, // Des
            ],
            // 2025 data (partial - up to current month)
            2025 => [
                1 => 312450,  // Jan
                2 => 245890,  // Feb
                3 => 356780,  // Mar
                4 => 489650,  // Apr
                5 => 545230,  // Mei
                6 => 634520,  // Jun
                7 => 698450,  // Jul
                8 => 456780,  // Agu
                9 => 512340,  // Sep
                10 => 478900, // Okt
                11 => 387650, // Nov (current month sample)
            ],
        ];

        foreach ($sampleData as $year => $months) {
            foreach ($months as $month => $count) {
                // Create sample visitors spread across the month
                $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
                $visitorsPerDay = intval($count / $daysInMonth);
                $remainder = $count % $daysInMonth;

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    // Skip future dates
                    $date = Carbon::create($year, $month, $day);
                    if ($date->isFuture()) {
                        continue;
                    }

                    $dailyCount = $visitorsPerDay + ($day <= $remainder ? 1 : 0);

                    // Create a summary record instead of individual records for performance
                    // We'll create batches of 100 records per day to simulate traffic
                    $batchSize = min($dailyCount, 100);
                    $multiplier = ceil($dailyCount / 100);

                    for ($i = 0; $i < $batchSize; $i++) {
                        Visitor::create([
                            'ip_address' => $this->randomIp(),
                            'user_agent' => $this->randomUserAgent(),
                            'page_visited' => $this->randomPage(),
                            'device_type' => $this->randomDevice(),
                            'browser' => $this->randomBrowser(),
                            'os' => $this->randomOs(),
                            'visit_date' => $date->toDateString(),
                            'created_at' => $date->copy()->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                            'updated_at' => $date->copy()->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                        ]);
                    }
                }

                $this->command->info("Seeded visitors for {$year}-{$month}");
            }
        }

        $this->command->info('Visitor seeding completed!');
    }

    private function randomIp(): string
    {
        return rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 255);
    }

    private function randomUserAgent(): string
    {
        $agents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Linux; Android 14) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36',
        ];
        return $agents[array_rand($agents)];
    }

    private function randomPage(): string
    {
        $pages = ['/', '/berita', '/layanan', '/struktur-organisasi', '/informasi/profil', '/informasi/apbd'];
        return $pages[array_rand($pages)];
    }

    private function randomDevice(): string
    {
        $devices = ['desktop', 'mobile', 'tablet'];
        $weights = [50, 45, 5]; // Desktop 50%, Mobile 45%, Tablet 5%
        return $this->weightedRandom($devices, $weights);
    }

    private function randomBrowser(): string
    {
        $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera'];
        return $browsers[array_rand($browsers)];
    }

    private function randomOs(): string
    {
        $os = ['Windows 10', 'Windows 11', 'Mac OS X', 'Android', 'iOS', 'Linux'];
        return $os[array_rand($os)];
    }

    private function weightedRandom(array $items, array $weights): string
    {
        $total = array_sum($weights);
        $rand = rand(1, $total);
        $current = 0;

        foreach ($items as $i => $item) {
            $current += $weights[$i];
            if ($rand <= $current) {
                return $item;
            }
        }

        return $items[0];
    }
}
