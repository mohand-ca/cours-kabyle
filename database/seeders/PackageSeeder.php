<?php

namespace Database\Seeders;

use App\Models\SessionPackage;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            ['name' => 'Pack 5 séances', 'sessions_count' => 5, 'price_cents' => 4900],
            ['name' => 'Pack 10 séances', 'sessions_count' => 10, 'price_cents' => 8900],
            ['name' => 'Pack 20 séances', 'sessions_count' => 20, 'price_cents' => 15900],
        ];

        foreach ($packages as $data) {
            SessionPackage::firstOrCreate(
                ['sessions_count' => $data['sessions_count']],
                $data
            );
        }
    }
}
