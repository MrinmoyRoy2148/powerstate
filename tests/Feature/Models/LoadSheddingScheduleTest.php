<?php

namespace Tests\Feature\Models;

use App\Models\LoadSheddingSchedule;
use App\Models\Outage;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoadSheddingScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_schedule_relationships_resolve_zone_creator_and_outages(): void
    {
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $creator = User::factory()->create();
        $schedule = $zone->schedules()->create([
            'title' => 'Morning schedule',
            'scheduled_date' => '2026-10-13',
            'start_time' => '08:00',
            'end_time' => '09:00',
        ]);
        $creator->schedulesCreated()->save($schedule);
        $outage = new Outage([
            'zone_id' => $zone->id,
            'title' => 'Scheduled outage',
            'outage_type' => 'planned',
            'started_at' => '2026-10-13 08:00:00',
            'status' => 'active',
        ]);
        $schedule->outages()->save($outage);

        $this->assertTrue($schedule->zone->is($zone));
        $this->assertTrue($schedule->creator->is($creator));
        $this->assertTrue($schedule->outages->contains($outage));
    }

    public function test_scheduled_date_is_cast_to_a_date(): void
    {
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $schedule = $zone->schedules()->create([
            'title' => 'Morning schedule',
            'scheduled_date' => '2026-10-13',
            'start_time' => '08:00',
            'end_time' => '09:00',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $schedule->scheduled_date);
        $this->assertSame('2026-10-13', $schedule->scheduled_date->toDateString());
    }
}
