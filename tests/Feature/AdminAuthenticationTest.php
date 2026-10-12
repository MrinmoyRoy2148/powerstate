<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login_from_dashboard(): void
    {
        $this->get(route('admin.login'))->assertOk()->assertSee('স্বাগতম');
        $this->get(route('admin.dashboard'))
            ->assertRedirectToRoute('admin.login');
    }

    public function test_active_admin_can_sign_in_and_reach_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'correct-password',
        ]);

        $this->post(route('admin.login.store'), [
            'email' => $admin->email,
            'password' => 'correct-password',
        ])
            ->assertRedirectToRoute('admin.dashboard')
            ->assertSessionHasNoErrors();

        $this->assertAuthenticatedAs($admin);
        $this->get(route('admin.dashboard'))->assertOk()->assertSee('ড্যাশবোর্ড');
    }

    public function test_non_admin_cannot_sign_in_or_reach_admin_dashboard(): void
    {
        $user = User::factory()->create(['password' => 'correct-password']);
        $user->forceFill(['role' => 'user'])->save();

        $this->post(route('admin.login.store'), [
            'email' => $user->email,
            'password' => 'correct-password',
        ])
            ->assertRedirect()
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_inactive_admin_cannot_sign_in_or_reach_dashboard(): void
    {
        $admin = User::factory()->create([
            'password' => 'correct-password',
            'is_active' => false,
        ]);

        $this->post(route('admin.login.store'), [
            'email' => $admin->email,
            'password' => 'correct-password',
        ])
            ->assertRedirect()
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_sign_out_using_post_and_session_is_invalidated(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        $this->post(route('admin.logout'))
            ->assertRedirectToRoute('admin.login');

        $this->assertGuest();
        $this->get(route('admin.dashboard'))->assertRedirectToRoute('admin.login');
    }

    public function test_invalid_login_attempts_are_rate_limited(): void
    {
        $admin = User::factory()->create();

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('admin.login.store'), [
                'email' => $admin->email,
                'password' => 'incorrect-password',
            ])->assertRedirect()->assertSessionHasErrors('email');
        }

        $this->post(route('admin.login.store'), [
            'email' => $admin->email,
            'password' => 'incorrect-password',
        ])->assertTooManyRequests();
    }
}
