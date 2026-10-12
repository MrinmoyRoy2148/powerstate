<?php

namespace Tests\Feature\Models;

use App\Models\Announcement;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementTest extends TestCase
{
    use RefreshDatabase;

    public function test_announcement_relationships_resolve_optional_zone_and_creator(): void
    {
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $creator = User::factory()->create();
        $announcement = $creator->announcementsCreated()->create([
            'title' => 'Service notice',
            'body' => 'Planned maintenance notice.',
            'zone_id' => $zone->id,
            'status' => 'published',
        ]);

        $this->assertTrue($announcement->zone->is($zone));
        $this->assertTrue($announcement->creator->is($creator));
    }

    public function test_published_at_is_cast_to_a_date_time(): void
    {
        $announcement = Announcement::create([
            'title' => 'Service notice',
            'body' => 'Planned maintenance notice.',
            'status' => 'published',
            'published_at' => '2026-10-12 10:00:00',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $announcement->published_at);
    }
}
