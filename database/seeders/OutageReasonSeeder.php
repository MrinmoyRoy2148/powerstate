<?php

namespace Database\Seeders;

use App\Models\OutageReason;
use Illuminate\Database\Seeder;

class OutageReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            'Maintenance',
            'Equipment Failure',
            'Emergency Repair',
            'Weather',
            'Technical Fault',
            'Other',
        ] as $name) {
            OutageReason::query()->firstOrCreate(['name' => $name]);
        }
    }
}
