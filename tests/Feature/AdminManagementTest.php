<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\LoadSheddingSchedule;
use App\Models\Outage;
use App\Models\OutageReason;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_management_pages_render_for_each_resource(): void
    {
        $admin = User::factory()->create();
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $reason = OutageReason::create(['name' => 'Maintenance']);
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
            'reason_id' => $reason->id,
            'title' => 'Current interruption',
            'outage_type' => 'unexpected',
            'started_at' => now()->subHour(),
            'status' => 'active',
        ]);
        $announcement = $admin->announcementsCreated()->create([
            'title' => 'Service notice',
            'body' => 'A public service notice.',
            'zone_id' => $zone->id,
            'status' => 'draft',
        ]);

        $this->actingAs($admin);

        foreach ([
            route('admin.dashboard'),
            route('admin.zones.index'),
            route('admin.zones.create'),
            route('admin.zones.edit', $zone),
            route('admin.reasons.index'),
            route('admin.reasons.create'),
            route('admin.reasons.edit', $reason),
            route('admin.schedules.index'),
            route('admin.schedules.create'),
            route('admin.schedules.edit', $schedule),
            route('admin.outages.index'),
            route('admin.outages.create'),
            route('admin.outages.show', $outage),
            route('admin.outages.edit', $outage),
            route('admin.announcements.index'),
            route('admin.announcements.create'),
            route('admin.announcements.edit', $announcement),
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_guest_cannot_access_admin_management_routes(): void
    {
        $this->get(route('admin.zones.index'))->assertRedirectToRoute('admin.login');
        $this->post(route('admin.zones.store'), [])->assertRedirectToRoute('admin.login');
    }

    public function test_admin_can_create_a_schedule_and_creator_is_set_from_session(): void
    {
        $admin = User::factory()->create();
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);

        $this->actingAs($admin)->post(route('admin.schedules.store'), [
            'zone_id' => $zone->id,
            'title' => 'Evening schedule',
            'scheduled_date' => '2030-01-01',
            'start_time' => '18:00',
            'end_time' => '19:00',
            'status' => 'published',
        ])->assertRedirectToRoute('admin.schedules.index');

        $this->assertDatabaseHas('load_shedding_schedules', [
            'zone_id' => $zone->id,
            'created_by' => $admin->id,
            'title' => 'Evening schedule',
        ]);
    }

    public function test_schedule_end_time_must_follow_start_time(): void
    {
        $admin = User::factory()->create();
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);

        $this->actingAs($admin)->from(route('admin.schedules.create'))
            ->post(route('admin.schedules.store'), [
                'zone_id' => $zone->id,
                'title' => 'Invalid schedule',
                'scheduled_date' => '2030-01-01',
                'start_time' => '19:00',
                'end_time' => '18:00',
                'status' => 'published',
            ])
            ->assertRedirectToRoute('admin.schedules.create')
            ->assertSessionHasErrors('end_time');

        $this->assertDatabaseMissing('load_shedding_schedules', ['title' => 'Invalid schedule']);
    }

    public function test_admin_can_create_a_zone_specific_announcement_with_author(): void
    {
        $admin = User::factory()->create();
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);

        $this->actingAs($admin)->post(route('admin.announcements.store'), [
            'title' => 'Maintenance notice',
            'body' => 'Fictional scheduled maintenance information.',
            'zone_id' => $zone->id,
            'status' => 'published',
        ])->assertRedirectToRoute('admin.announcements.index');

        $this->assertDatabaseHas('announcements', [
            'title' => 'Maintenance notice',
            'zone_id' => $zone->id,
            'created_by' => $admin->id,
            'status' => 'published',
        ]);

        $announcement = Announcement::query()->where('title', 'Maintenance notice')->firstOrFail();
        $this->assertNotNull($announcement->published_at);
    }

    public function test_restoring_an_active_outage_preserves_it_and_sets_restored_time(): void
    {
        $admin = User::factory()->create();
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $outage = $zone->outages()->create([
            'title' => 'Current interruption',
            'outage_type' => 'unexpected',
            'started_at' => '2026-10-12 08:00:00',
            'status' => 'active',
        ]);
        $this->travelTo(now()->setDate(2026, 10, 12)->setTime(12, 0));

        $this->actingAs($admin)->patch(route('admin.outages.restore', $outage))
            ->assertRedirectToRoute('admin.outages.show', $outage);

        $this->assertDatabaseHas('outages', [
            'id' => $outage->id,
            'status' => 'restored',
            'restored_at' => '2026-10-12 12:00:00',
        ]);
    }

    public function test_zone_in_use_cannot_be_deleted(): void
    {
        $admin = User::factory()->create();
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $zone->schedules()->create([
            'title' => 'Published schedule',
            'scheduled_date' => '2030-01-01',
            'start_time' => '08:00',
            'end_time' => '09:00',
            'status' => 'published',
        ]);

        $this->actingAs($admin)->from(route('admin.zones.index'))
            ->delete(route('admin.zones.destroy', $zone))
            ->assertRedirectToRoute('admin.zones.index')
            ->assertSessionHasErrors('zone');

        $this->assertModelExists($zone);
    }

    public function test_outage_reason_in_use_cannot_be_deleted(): void
    {
        $admin = User::factory()->create();
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $reason = OutageReason::create(['name' => 'Maintenance']);
        $zone->outages()->create([
            'reason_id' => $reason->id,
            'title' => 'Maintenance interruption',
            'outage_type' => 'planned',
            'started_at' => '2026-10-12 08:00:00',
            'status' => 'active',
        ]);

        $this->actingAs($admin)->from(route('admin.reasons.index'))
            ->delete(route('admin.reasons.destroy', $reason))
            ->assertRedirectToRoute('admin.reasons.index')
            ->assertSessionHasErrors('reason');

        $this->assertModelExists($reason);
    }
}
