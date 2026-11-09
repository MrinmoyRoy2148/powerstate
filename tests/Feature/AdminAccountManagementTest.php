<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccountManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_profile_and_change_password_with_current_password(): void
    {
        $admin = User::factory()->create([
            'name' => 'Original Admin',
            'email' => 'original@example.com',
            'password' => 'correct-current-password',
        ]);
        $newPassword = 'Strong-new-password-2026';

        $this->actingAs($admin)
            ->put(route('admin.profile.update'), [
                'name' => 'Updated Admin',
                'email' => 'UPDATED@example.com',
            ])
            ->assertRedirectToRoute('admin.profile.edit');
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Updated Admin',
            'email' => 'updated@example.com',
        ]);

        $this->put(route('admin.profile.password.update'), [
            'current_password' => 'correct-current-password',
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ])->assertRedirectToRoute('admin.profile.edit');

        $this->assertTrue(Hash::check($newPassword, $admin->fresh()->password));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_cannot_change_password_without_the_correct_current_password(): void
    {
        $admin = User::factory()->create(['password' => 'correct-current-password']);
        $originalHash = $admin->password;

        $this->actingAs($admin)
            ->from(route('admin.profile.edit'))
            ->put(route('admin.profile.password.update'), [
                'current_password' => 'wrong-current-password',
                'password' => 'Strong-new-password-2026',
                'password_confirmation' => 'Strong-new-password-2026',
            ])
            ->assertRedirectToRoute('admin.profile.edit')
            ->assertSessionHasErrors('current_password');

        $this->assertSame($originalHash, $admin->fresh()->password);
    }

    public function test_guests_cannot_access_admin_profile(): void
    {
        $this->get(route('admin.profile.edit'))
            ->assertRedirectToRoute('admin.login');
    }

    public function test_database_seeder_creates_configured_admin_without_a_default_demo_account(): void
    {
        config([
            'admin.initial_admin.name' => 'PowerStat Admin',
            'admin.initial_admin.email' => 'configured-admin@example.com',
            'admin.initial_admin.password' => 'Configured-strong-password',
        ]);

        $this->seed(DatabaseSeeder::class);

        $admin = User::query()->where('email', 'configured-admin@example.com')->firstOrFail();
        $this->assertSame('admin', $admin->role);
        $this->assertTrue($admin->is_active);
        $this->assertTrue(Hash::check('Configured-strong-password', $admin->password));
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }
}
