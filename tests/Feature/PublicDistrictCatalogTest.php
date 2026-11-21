<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Zone;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DistrictSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicDistrictCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeding_creates_all_64_district_records_idempotently(): void
    {
        config([
            'admin.initial_admin.name' => 'Test Admin',
            'admin.initial_admin.email' => 'admin@example.test',
            'admin.initial_admin.password' => 'test-only-password-123',
        ]);

        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('zones', 64);
        $this->assertDatabaseHas('zones', [
            'slug' => 'district-dhaka',
            'name' => 'ঢাকা / Dhaka',
            'is_active' => true,
        ]);
    }

    public function test_public_search_finds_district_names_in_bangla_and_english(): void
    {
        $this->seed(DistrictSeeder::class);

        $this->get(route('zones.index', ['search' => 'ঢাকা']))
            ->assertOk()
            ->assertSee('ঢাকা / Dhaka');

        $this->get(route('zones.index', ['search' => 'Dhaka']))
            ->assertOk()
            ->assertSee('ঢাকা / Dhaka');

        $this->get(route('zones.show', 'district-dhaka'))
            ->assertOk()
            ->assertSee('Utility-specific service coverage is not yet verified.');
    }

    public function test_admin_created_neighborhood_is_parented_to_a_district_and_publicly_searchable(): void
    {
        $this->seed(DistrictSeeder::class);
        $admin = User::factory()->create();
        $rangpur = Zone::query()->where('slug', 'district-rangpur')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.zones.store'), [
                'name' => 'কেরানীপাড়া / Keranipara',
                'slug' => 'keranipara-rangpur',
                'parent_id' => $rangpur->id,
                'is_active' => '1',
            ])
            ->assertRedirectToRoute('admin.zones.index');

        $neighborhood = Zone::query()->where('slug', 'keranipara-rangpur')->firstOrFail();
        $this->assertSame($rangpur->id, $neighborhood->parent_id);

        $this->get(route('zones.index', ['search' => 'Keranipara']))
            ->assertOk()
            ->assertSee('কেরানীপাড়া / Keranipara');
    }

    public function test_rerunning_seeder_preserves_admin_edits(): void
    {
        $this->seed(DistrictSeeder::class);

        $district = Zone::query()->where('slug', 'district-dhaka')->firstOrFail();
        $district->update([
            'name' => 'Dhaka District',
            'is_active' => false,
        ]);

        $this->seed(DistrictSeeder::class);

        $this->assertDatabaseCount('zones', 64);
        $this->assertSame('Dhaka District', $district->fresh()->name);
        $this->assertFalse($district->fresh()->is_active);
    }
}
