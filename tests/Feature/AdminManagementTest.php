<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Outage;
use App\Models\OutageReason;
use App\Models\User;
use App\Models\Zone;
use Database\Seeders\DatabaseSeeder;
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

        $this->actingAs($admin)->withSession(['locale' => 'en']);

        foreach ([
            route('admin.dashboard'),
            route('admin.profile.edit'),
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
            route('admin.outages.history'),
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

    public function test_admin_can_edit_and_delete_a_schedule(): void
    {
        $admin = User::factory()->create();
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $schedule = $zone->schedules()->create([
            'title' => 'Initial schedule',
            'scheduled_date' => '2030-01-01',
            'start_time' => '18:00',
            'end_time' => '19:00',
            'status' => 'draft',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.schedules.update', $schedule), [
                'zone_id' => $zone->id,
                'title' => 'Updated schedule',
                'scheduled_date' => '2030-01-02',
                'start_time' => '19:00',
                'end_time' => '20:00',
                'status' => 'published',
            ])
            ->assertRedirectToRoute('admin.schedules.index');
        $this->assertDatabaseHas('load_shedding_schedules', [
            'id' => $schedule->id,
            'title' => 'Updated schedule',
            'scheduled_date' => '2030-01-02 00:00:00',
            'status' => 'published',
        ]);

        $this->delete(route('admin.schedules.destroy', $schedule))
            ->assertRedirectToRoute('admin.schedules.index');
        $this->assertDatabaseMissing('load_shedding_schedules', ['id' => $schedule->id]);
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

    public function test_schedule_required_fields_cannot_be_empty(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->from(route('admin.schedules.create'))
            ->post(route('admin.schedules.store'), [])
            ->assertRedirectToRoute('admin.schedules.create')
            ->assertSessionHasErrors(['zone_id', 'title', 'scheduled_date', 'start_time', 'end_time', 'status']);

        $this->assertDatabaseCount('load_shedding_schedules', 0);
    }

    public function test_zone_names_must_be_unique_when_created_or_updated(): void
    {
        $admin = User::factory()->create();
        $firstZone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $secondZone = Zone::create(['name' => 'Chattogram', 'slug' => 'chattogram']);

        $this->actingAs($admin)
            ->from(route('admin.zones.create'))
            ->post(route('admin.zones.store'), [
                'name' => 'Dhaka',
                'slug' => 'dhaka-city',
                'is_active' => true,
            ])
            ->assertRedirectToRoute('admin.zones.create')
            ->assertSessionHasErrors('name');

        $this->actingAs($admin)
            ->put(route('admin.zones.update', $firstZone), [
                'name' => 'Dhaka',
                'slug' => 'dhaka',
                'is_active' => true,
            ])
            ->assertRedirectToRoute('admin.zones.index');

        $this->actingAs($admin)
            ->from(route('admin.zones.edit', $secondZone))
            ->put(route('admin.zones.update', $secondZone), [
                'name' => 'Dhaka',
                'slug' => 'chattogram',
                'is_active' => true,
            ])
            ->assertRedirectToRoute('admin.zones.edit', $secondZone)
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('zones', 2);
        $this->assertDatabaseHas('zones', ['id' => $firstZone->id, 'name' => 'Dhaka']);
        $this->assertDatabaseHas('zones', ['id' => $secondZone->id, 'name' => 'Chattogram']);
    }

    public function test_admin_can_filter_zones_by_search_and_activation_status(): void
    {
        $admin = User::factory()->create();
        Zone::create(['name' => 'Mirpur North', 'slug' => 'mirpur-north']);
        Zone::create(['name' => 'Mirpur East', 'slug' => 'mirpur-south', 'is_active' => false]);

        $this->actingAs($admin)
            ->get(route('admin.zones.index', ['search' => 'south', 'status' => 'inactive']))
            ->assertOk()
            ->assertSee('Mirpur East')
            ->assertDontSee('Mirpur North');
    }

    public function test_admin_can_filter_upcoming_schedules_by_zone_date_and_status(): void
    {
        $admin = User::factory()->create();
        $selectedZone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $otherZone = Zone::create(['name' => 'Chattogram', 'slug' => 'chattogram']);
        $selectedZone->schedules()->create([
            'title' => 'Matching schedule',
            'scheduled_date' => '2026-11-10',
            'start_time' => '18:00',
            'end_time' => '19:00',
            'status' => 'published',
        ]);
        $selectedZone->schedules()->create([
            'title' => 'Draft schedule',
            'scheduled_date' => '2026-11-10',
            'start_time' => '19:00',
            'end_time' => '20:00',
            'status' => 'draft',
        ]);
        $otherZone->schedules()->create([
            'title' => 'Other area schedule',
            'scheduled_date' => '2026-11-10',
            'start_time' => '18:00',
            'end_time' => '19:00',
            'status' => 'published',
        ]);
        $this->travelTo('2026-11-01 09:00:00');

        $this->actingAs($admin)
            ->get(route('admin.schedules.index', [
                'period' => 'upcoming',
                'zone_id' => $selectedZone->id,
                'scheduled_date' => '2026-11-10',
                'status' => 'published',
            ]))
            ->assertOk()
            ->assertSee('Matching schedule')
            ->assertDontSee('Draft schedule')
            ->assertDontSee('Other area schedule');
    }

    public function test_schedule_period_tabs_separate_upcoming_and_past_records(): void
    {
        $admin = User::factory()->create();
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $zone->schedules()->create([
            'title' => 'Past schedule',
            'scheduled_date' => '2026-10-31',
            'start_time' => '18:00',
            'end_time' => '19:00',
            'status' => 'published',
        ]);
        $zone->schedules()->create([
            'title' => 'Upcoming schedule',
            'scheduled_date' => '2026-11-02',
            'start_time' => '18:00',
            'end_time' => '19:00',
            'status' => 'published',
        ]);
        $this->travelTo('2026-11-01 09:00:00');

        $this->actingAs($admin)
            ->get(route('admin.schedules.index'))
            ->assertOk()
            ->assertSee('Upcoming schedule')
            ->assertDontSee('Past schedule');

        $this->get(route('admin.schedules.index', ['period' => 'past']))
            ->assertOk()
            ->assertSee('Past schedule')
            ->assertDontSee('Upcoming schedule');
    }

    public function test_admin_can_publish_and_cancel_schedules(): void
    {
        $admin = User::factory()->create();
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $scheduleToPublish = $zone->schedules()->create([
            'title' => 'Draft schedule',
            'scheduled_date' => '2026-11-10',
            'start_time' => '18:00',
            'end_time' => '19:00',
            'status' => 'draft',
        ]);
        $scheduleToCancel = $zone->schedules()->create([
            'title' => 'Published schedule',
            'scheduled_date' => '2026-11-11',
            'start_time' => '18:00',
            'end_time' => '19:00',
            'status' => 'published',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.schedules.publish', $scheduleToPublish))
            ->assertRedirectToRoute('admin.schedules.index');
        $this->assertDatabaseHas('load_shedding_schedules', [
            'id' => $scheduleToPublish->id,
            'status' => 'published',
        ]);

        $this->patch(route('admin.schedules.cancel', $scheduleToCancel))
            ->assertRedirectToRoute('admin.schedules.index');
        $this->assertDatabaseHas('load_shedding_schedules', [
            'id' => $scheduleToCancel->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_admin_can_filter_outages_by_zone_status_and_start_date(): void
    {
        $admin = User::factory()->create();
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $otherZone = Zone::create(['name' => 'Chattogram', 'slug' => 'chattogram']);
        $matchingOutage = $zone->outages()->create([
            'title' => 'Matching outage',
            'outage_type' => 'unexpected',
            'started_at' => '2026-11-10 08:00:00',
            'status' => 'active',
        ]);
        $zone->outages()->create([
            'title' => 'Other date outage',
            'outage_type' => 'unexpected',
            'started_at' => '2026-11-11 08:00:00',
            'status' => 'active',
        ]);
        $otherZone->outages()->create([
            'title' => 'Other zone outage',
            'outage_type' => 'planned',
            'started_at' => '2026-11-10 08:00:00',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.outages.index', [
                'zone' => $zone->id,
                'status' => 'active',
                'date' => '2026-11-10',
            ]))
            ->assertOk()
            ->assertSee($matchingOutage->title)
            ->assertDontSee('Other date outage')
            ->assertDontSee('Other zone outage');
    }

    public function test_admin_can_add_and_edit_an_outage(): void
    {
        $admin = User::factory()->create();
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $reason = OutageReason::create(['name' => 'Maintenance']);

        $this->actingAs($admin)
            ->post(route('admin.outages.store'), [
                'zone_id' => $zone->id,
                'reason_id' => $reason->id,
                'title' => 'Planned interruption',
                'outage_type' => 'planned',
                'started_at' => '2026-11-10T08:00',
                'expected_restoration_at' => '2026-11-10T12:00',
                'status' => 'active',
            ])
            ->assertRedirectToRoute('admin.outages.index');

        $outage = Outage::query()->where('title', 'Planned interruption')->firstOrFail();
        $this->assertSame($admin->id, $outage->reported_by);
        $this->assertDatabaseHas('outages', [
            'id' => $outage->id,
            'zone_id' => $zone->id,
            'reason_id' => $reason->id,
            'status' => 'active',
        ]);

        $this->put(route('admin.outages.update', $outage), [
            'zone_id' => $zone->id,
            'reason_id' => $reason->id,
            'title' => 'Updated interruption',
            'outage_type' => 'unexpected',
            'started_at' => '2026-11-10T09:00',
            'expected_restoration_at' => '2026-11-10T13:00',
            'status' => 'active',
        ])->assertRedirectToRoute('admin.outages.show', $outage);

        $this->assertDatabaseHas('outages', [
            'id' => $outage->id,
            'title' => 'Updated interruption',
            'outage_type' => 'unexpected',
        ]);
    }

    public function test_outages_cannot_be_permanently_deleted(): void
    {
        $admin = User::factory()->create();
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $outage = $zone->outages()->create([
            'title' => 'Historical outage',
            'outage_type' => 'unexpected',
            'started_at' => '2026-10-12 08:00:00',
            'status' => 'restored',
            'restored_at' => '2026-10-12 12:00:00',
        ]);

        $this->actingAs($admin)
            ->delete('/admin/outages/'.$outage->id)
            ->assertStatus(405);

        $this->assertModelExists($outage);
    }

    public function test_admin_can_activate_and_deactivate_outage_reasons(): void
    {
        $admin = User::factory()->create();
        $reason = OutageReason::create(['name' => 'Maintenance']);

        $this->actingAs($admin)
            ->patch(route('admin.reasons.activation.update', $reason), ['is_active' => false])
            ->assertRedirectToRoute('admin.reasons.index');
        $this->assertDatabaseHas('outage_reasons', ['id' => $reason->id, 'is_active' => false]);

        $this->patch(route('admin.reasons.activation.update', $reason), ['is_active' => true])
            ->assertRedirectToRoute('admin.reasons.index');
        $this->assertDatabaseHas('outage_reasons', ['id' => $reason->id, 'is_active' => true]);
    }

    public function test_outage_reason_names_cannot_be_duplicated(): void
    {
        $admin = User::factory()->create();
        $reason = OutageReason::create(['name' => 'Maintenance']);

        $this->actingAs($admin)
            ->from(route('admin.reasons.create'))
            ->post(route('admin.reasons.store'), ['name' => 'Maintenance'])
            ->assertRedirectToRoute('admin.reasons.create')
            ->assertSessionHasErrors('name');

        $this->post(route('admin.reasons.store'), ['name' => 'Emergency Repair'])
            ->assertRedirectToRoute('admin.reasons.index');

        $this->put(route('admin.reasons.update', $reason), ['name' => 'Emergency Repair'])
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('outage_reasons', 2);
        $this->assertDatabaseHas('outage_reasons', ['id' => $reason->id, 'name' => 'Maintenance']);
    }

    public function test_default_outage_reasons_are_seeded_without_duplicates(): void
    {
        config([
            'admin.initial_admin.name' => 'Seed Administrator',
            'admin.initial_admin.email' => 'seed-admin@example.com',
            'admin.initial_admin.password' => 'a-strong-seed-password',
        ]);

        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('outage_reasons', 6);
        foreach ([
            'Maintenance',
            'Equipment Failure',
            'Emergency Repair',
            'Weather',
            'Technical Fault',
            'Other',
        ] as $name) {
            $this->assertDatabaseHas('outage_reasons', ['name' => $name]);
        }
    }

    public function test_admin_can_create_search_publish_and_unpublish_announcements_with_expiry(): void
    {
        $admin = User::factory()->create();
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);

        $this->travelTo('2026-11-01 09:00:00');
        $this->actingAs($admin)
            ->post(route('admin.announcements.store'), [
                'title' => 'Scheduled maintenance',
                'body' => 'The service will be unavailable briefly.',
                'zone_id' => $zone->id,
                'status' => 'published',
                'published_at' => '2026-11-02T09:00',
                'expires_at' => '2026-11-04T09:00',
            ])
            ->assertRedirectToRoute('admin.announcements.index');

        $announcement = Announcement::query()->where('title', 'Scheduled maintenance')->firstOrFail();
        $this->assertSame($admin->id, $announcement->created_by);
        $this->assertDatabaseHas('announcements', [
            'id' => $announcement->id,
            'zone_id' => $zone->id,
            'status' => 'published',
            'published_at' => '2026-11-02 09:00:00',
            'expires_at' => '2026-11-04 09:00:00',
        ]);

        $this->put(route('admin.announcements.update', $announcement), [
            'title' => 'Updated scheduled maintenance',
            'body' => 'The service interruption time has changed.',
            'zone_id' => $zone->id,
            'status' => 'published',
            'published_at' => '2026-11-02T09:00',
            'expires_at' => '2026-11-04T09:00',
        ])->assertRedirectToRoute('admin.announcements.index');
        $this->assertDatabaseHas('announcements', [
            'id' => $announcement->id,
            'title' => 'Updated scheduled maintenance',
            'body' => 'The service interruption time has changed.',
        ]);

        $this->get(route('admin.announcements.index', [
            'search' => 'Updated scheduled',
            'zone_id' => $zone->id,
            'status' => 'published',
        ]))->assertOk()->assertSee('Updated scheduled maintenance');

        $this->patch(route('admin.announcements.unpublish', $announcement))
            ->assertRedirectToRoute('admin.announcements.index');
        $this->assertDatabaseHas('announcements', [
            'id' => $announcement->id,
            'status' => 'draft',
            'published_at' => null,
        ]);

        $this->patch(route('admin.announcements.publish', $announcement))
            ->assertRedirectToRoute('admin.announcements.index');
        $this->assertDatabaseHas('announcements', [
            'id' => $announcement->id,
            'status' => 'published',
            'published_at' => '2026-11-01 09:00:00',
        ]);
    }

    public function test_admin_can_delete_an_announcement_after_confirmation(): void
    {
        $admin = User::factory()->create();
        $announcement = Announcement::create([
            'title' => 'Announcement to delete',
            'body' => 'This announcement is no longer needed.',
            'status' => 'draft',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.announcements.destroy', $announcement))
            ->assertRedirectToRoute('admin.announcements.index');

        $this->assertDatabaseMissing('announcements', ['id' => $announcement->id]);
    }

    public function test_announcement_expiry_must_be_after_publication_date(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->from(route('admin.announcements.create'))
            ->post(route('admin.announcements.store'), [
                'title' => 'Invalid notice',
                'body' => 'This notice has invalid dates.',
                'status' => 'published',
                'published_at' => '2026-11-03T09:00',
                'expires_at' => '2026-11-02T09:00',
            ])
            ->assertRedirectToRoute('admin.announcements.create')
            ->assertSessionHasErrors('expires_at');

        $this->assertDatabaseMissing('announcements', ['title' => 'Invalid notice']);
    }

    public function test_expired_announcements_are_hidden_from_public_pages(): void
    {
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $announcement = Announcement::create([
            'title' => 'Expired notice',
            'body' => 'This notice has expired.',
            'zone_id' => $zone->id,
            'status' => 'published',
            'published_at' => '2026-10-01 09:00:00',
            'expires_at' => '2026-10-02 09:00:00',
        ]);
        Announcement::create([
            'title' => 'Current notice',
            'body' => 'This notice is current.',
            'status' => 'published',
            'published_at' => '2026-10-01 09:00:00',
            'expires_at' => '2026-12-01 09:00:00',
        ]);
        $this->travelTo('2026-11-01 09:00:00');

        $this->get(route('announcements.show', $announcement))->assertNotFound();
        $this->get(route('announcements.index'))->assertDontSee('Expired notice')->assertSee('Current notice');
        $this->get(route('home'))->assertDontSee('Expired notice')->assertSee('Current notice');
        $this->get(route('zones.show', $zone))->assertDontSee('Expired notice');
    }

    public function test_admin_can_filter_outage_history_by_zone_dates_and_type_and_see_valid_duration(): void
    {
        $admin = User::factory()->create();
        $zone = Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka']);
        $reason = OutageReason::create(['name' => 'Maintenance']);
        $matchingOutage = $zone->outages()->create([
            'reason_id' => $reason->id,
            'title' => 'Historical outage',
            'outage_type' => 'planned',
            'started_at' => '2026-10-10 08:00:00',
            'restored_at' => '2026-10-10 10:30:00',
            'status' => 'restored',
        ]);
        $zone->outages()->create([
            'title' => 'Outside date range',
            'outage_type' => 'planned',
            'started_at' => '2026-10-15 08:00:00',
            'restored_at' => '2026-10-15 09:00:00',
            'status' => 'restored',
        ]);
        $zone->outages()->create([
            'title' => 'Different outage type',
            'outage_type' => 'unexpected',
            'started_at' => '2026-10-10 08:00:00',
            'restored_at' => '2026-10-10 09:00:00',
            'status' => 'restored',
        ]);

        $this->actingAs($admin)->withSession(['locale' => 'en'])
            ->get(route('admin.outages.history', [
                'zone' => $zone->id,
                'start_date' => '2026-10-10',
                'end_date' => '2026-10-12',
                'outage_type' => 'planned',
            ]))
            ->assertOk()
            ->assertSee('Status')
            ->assertSee($matchingOutage->title)
            ->assertSee('2h 30m')
            ->assertDontSee('Outside date range')
            ->assertDontSee('Different outage type');
    }

    public function test_history_end_date_cannot_be_before_start_date(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->from(route('admin.outages.history'))
            ->get(route('admin.outages.history', [
                'start_date' => '2026-10-12',
                'end_date' => '2026-10-11',
            ]))
            ->assertRedirectToRoute('admin.outages.history')
            ->assertSessionHasErrors('end_date');
    }

    public function test_dashboard_shows_quick_actions_and_hides_future_announcements(): void
    {
        $admin = User::factory()->create();
        $admin->announcementsCreated()->create([
            'title' => 'Future notice',
            'body' => 'This notice is not yet available.',
            'status' => 'published',
            'published_at' => '2026-11-03 09:00:00',
        ]);
        $this->travelTo('2026-11-01 09:00:00');

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk()
            ->assertSee(route('admin.zones.create'))
            ->assertSee(route('admin.schedules.create'))
            ->assertSee(route('admin.outages.create'))
            ->assertSee(route('admin.announcements.create'))
            ->assertDontSee('Future notice');
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
