<?php

namespace Database\Seeders;

use App\Models\Zone;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! app()->environment(['local', 'demo'])) {
            return;
        }

        Zone::query()->updateOrCreate(
            ['slug' => 'demo-central'],
            [
                'name' => 'Demo Central',
                'description' => 'Fictional area used only for local demonstration.',
                'is_active' => true,
            ]
        );
    }
}
