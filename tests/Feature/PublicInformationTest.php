<?php

namespace Tests\Feature;

use App\Http\Controllers\PublicAnnouncementController;
use App\Http\Controllers\PublicOutageController;
use App\Http\Controllers\PublicScheduleController;
use App\Models\Announcement;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class PublicInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_schedule_listing_excludes_drafts_and_inactive_zones(): void
    {
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $inactiveZone = Zone::create(['name' => 'Inactive', 'slug' => 'inactive', 'is_active' => false]);
        $publishedSchedule = $zone->schedules()->create([
            'title' => 'Published schedule',
            'scheduled_date' => '2030-01-01',
            'start_time' => '08:00',
            'end_time' => '09:00',
            'status' => 'published',
        ]);
        $zone->schedules()->create([
            'title' => 'Draft schedule',
            'scheduled_date' => '2030-01-01',
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => 'draft',
        ]);
        $inactiveZone->schedules()->create([
            'title' => 'Inactive area schedule',
            'scheduled_date' => '2030-01-01',
            'start_time' => '12:00',
            'end_time' => '13:00',
            'status' => 'published',
        ]);

        $view = app(PublicScheduleController::class)->index(Request::create('/schedules'));

        $this->assertSame(
            [$publishedSchedule->id],
            $view->getData()['schedules']->getCollection()->modelKeys()
        );
    }

    public function test_public_active_outage_listing_does_not_include_restored_outages(): void
    {
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $activeOutage = $zone->outages()->create([
            'title' => 'Current interruption',
            'outage_type' => 'unexpected',
            'started_at' => '2026-10-12 08:00:00',
            'status' => 'active',
        ]);
        $zone->outages()->create([
            'title' => 'Restored interruption',
            'outage_type' => 'unexpected',
            'started_at' => '2026-10-11 08:00:00',
            'restored_at' => '2026-10-11 09:00:00',
            'status' => 'restored',
        ]);

        $view = app(PublicOutageController::class)->index(Request::create('/outages'));

        $this->assertSame(
            [$activeOutage->id],
            $view->getData()['outages']->getCollection()->modelKeys()
        );
    }

    public function test_public_announcement_detail_hides_draft_records(): void
    {
        $draft = Announcement::create([
            'title' => 'Unpublished notice',
            'body' => 'This content must remain private.',
            'status' => 'draft',
        ]);

        $this->get(route('announcements.show', $draft))->assertNotFound();
    }

    public function test_public_announcement_detail_hides_scheduled_notices_until_publish_time(): void
    {
        $this->travelTo(now()->setDate(2026, 10, 12)->setTime(12, 0));
        $announcement = Announcement::create([
            'title' => 'Future notice',
            'body' => 'This notice is scheduled.',
            'status' => 'published',
            'published_at' => now()->addHour(),
        ]);

        $this->get(route('announcements.show', $announcement))->assertNotFound();
    }

    public function test_public_information_pages_render_with_published_records(): void
    {
        $admin = User::factory()->create();
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $schedule = $admin->schedulesCreated()->create([
            'zone_id' => $zone->id,
            'title' => 'Evening schedule',
            'scheduled_date' => '2030-01-01',
            'start_time' => '18:00',
            'end_time' => '19:00',
            'status' => 'published',
        ]);
        $outage = $admin->outagesReported()->create([
            'zone_id' => $zone->id,
            'title' => 'Current interruption',
            'outage_type' => 'unexpected',
            'started_at' => now()->subHour(),
            'status' => 'active',
        ]);
        $announcement = $admin->announcementsCreated()->create([
            'title' => 'Service notice',
            'body' => 'A public service notice.',
            'zone_id' => $zone->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        foreach ([
            route('home'),
            route('zones.index'),
            route('zones.show', $zone),
            route('schedules.index'),
            route('outages.index'),
            route('outages.show', $outage),
            route('outages.history'),
            route('announcements.index'),
            route('announcements.show', $announcement),
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }
}
