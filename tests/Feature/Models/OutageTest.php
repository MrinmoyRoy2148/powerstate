<?php

namespace Tests\Feature\Models;

use App\Models\LoadSheddingSchedule;
use App\Models\Outage;
use App\Models\OutageReason;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OutageTest extends TestCase
{
    use RefreshDatabase;

    public function test_outage_relationships_resolve_zone_schedule_reason_and_reporter(): void
    {
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $schedule = $zone->schedules()->create([
            'title' => 'Morning schedule',
            'scheduled_date' => '2026-10-13',
            'start_time' => '08:00',
            'end_time' => '09:00',
        ]);
        $reason = OutageReason::create(['name' => 'Maintenance']);
        $reporter = User::factory()->create();
        $outage = $zone->outages()->create([
            'schedule_id' => $schedule->id,
            'reason_id' => $reason->id,
            'title' => 'Scheduled outage',
            'outage_type' => 'planned',
            'started_at' => '2026-10-13 08:00:00',
            'status' => 'active',
        ]);
        $reporter->outagesReported()->save($outage);

        $this->assertTrue($outage->zone->is($zone));
        $this->assertTrue($outage->schedule->is($schedule));
        $this->assertTrue($outage->reason->is($reason));
        $this->assertTrue($outage->reporter->is($reporter));
    }

    public function test_outage_timestamps_are_cast_to_date_times(): void
    {
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $outage = $zone->outages()->create([
            'title' => 'Unexpected outage',
            'outage_type' => 'unexpected',
            'started_at' => '2026-10-12 08:00:00',
            'expected_restoration_at' => '2026-10-12 10:00:00',
            'status' => 'active',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $outage->started_at);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $outage->expected_restoration_at);
        $this->assertNull($outage->restored_at);
    }
}
