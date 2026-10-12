<?php

namespace Tests\Feature\Models;

use App\Models\Announcement;
use App\Models\LoadSheddingSchedule;
use App\Models\Outage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_relationships_resolve_records_created_or_reported_by_the_user(): void
    {
        $user = User::factory()->create();
        $zone = \App\Models\Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $schedule = $user->schedulesCreated()->create([
            'zone_id' => $zone->id,
            'title' => 'Morning schedule',
            'scheduled_date' => '2026-10-13',
            'start_time' => '08:00',
            'end_time' => '09:00',
        ]);
        $outage = $user->outagesReported()->create([
            'zone_id' => $zone->id,
            'title' => 'Unexpected outage',
            'outage_type' => 'unexpected',
            'started_at' => '2026-10-12 08:00:00',
            'status' => 'active',
        ]);
        $announcement = $user->announcementsCreated()->create([
            'title' => 'Service notice',
            'body' => 'Planned maintenance notice.',
            'status' => 'draft',
        ]);

        $this->assertTrue($user->schedulesCreated->contains($schedule));
        $this->assertTrue($user->outagesReported->contains($outage));
        $this->assertTrue($user->announcementsCreated->contains($announcement));
    }

    public function test_privileged_account_fields_are_not_mass_assignable(): void
    {
        $user = new User;
        $user->fill(['role' => 'admin', 'is_active' => false]);

        $this->assertNull($user->getAttribute('role'));
        $this->assertNull($user->getAttribute('is_active'));
    }
}
