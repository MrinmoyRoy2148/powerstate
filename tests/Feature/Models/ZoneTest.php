<?php

namespace Tests\Feature\Models;

use App\Models\Announcement;
use App\Models\LoadSheddingSchedule;
use App\Models\Outage;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ZoneTest extends TestCase
{
    use RefreshDatabase;

    public function test_zone_relationships_resolve_parent_children_and_related_records(): void
    {
        $parent = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $child = Zone::create(['name' => 'Mirpur', 'slug' => 'mirpur', 'parent_id' => $parent->id]);
        $schedule = $parent->schedules()->create([
            'title' => 'Morning schedule',
            'scheduled_date' => '2026-10-13',
            'start_time' => '08:00',
            'end_time' => '09:00',
        ]);
        $outage = $parent->outages()->create([
            'title' => 'Unexpected outage',
            'outage_type' => 'unexpected',
            'started_at' => '2026-10-12 08:00:00',
            'status' => 'active',
        ]);
        $announcement = $parent->announcements()->create([
            'title' => 'Service notice',
            'body' => 'Area-specific maintenance notice.',
            'status' => 'draft',
        ]);

        $this->assertTrue($child->parent->is($parent));
        $this->assertTrue($parent->children->contains($child));
        $this->assertTrue($parent->schedules->contains($schedule));
        $this->assertTrue($parent->outages->contains($outage));
        $this->assertTrue($parent->announcements->contains($announcement));
    }

    public function test_active_state_is_cast_to_boolean(): void
    {
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $zone->refresh();

        $this->assertIsBool($zone->is_active);
        $this->assertSame(true, $zone->is_active);
    }
}
