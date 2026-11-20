<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavedZonesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_save_an_area(): void
    {
        $zone = Zone::create(['name' => 'Mirpur', 'slug' => 'mirpur']);

        $this->put(route('zones.save', $zone))
            ->assertRedirect();
    }

    public function test_registered_user_can_save_and_remove_an_area(): void
    {
        $user = User::factory()->create();
        $zone = Zone::create(['name' => 'Mirpur', 'slug' => 'mirpur']);

        $this->actingAs($user)
            ->put(route('zones.save', $zone))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('saved_zones', [
            'user_id' => $user->id,
            'zone_id' => $zone->id,
        ]);

        $this->actingAs($user)
            ->delete(route('zones.unsave', $zone))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('saved_zones', [
            'user_id' => $user->id,
            'zone_id' => $zone->id,
        ]);
    }

    public function test_saved_areas_page_requires_authentication(): void
    {
        $this->get(route('zones.saved'))
            ->assertRedirectToRoute('login');
    }
}
