<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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

    public function test_admin_registration_is_unavailable_until_a_long_key_is_configured(): void
    {
        foreach ([null, str_repeat('k', 31)] as $key) {
            config(['admin.registration_key' => $key]);

            $this->get(route('admin.register'))->assertNotFound();
            $this->get(route('admin.login'))
                ->assertOk()
                ->assertDontSee(route('admin.register'));

            $this->post(route('admin.register.store'), [
                'name' => 'New Administrator',
                'email' => 'new-admin@example.com',
                'password' => 'secure-admin-password',
                'password_confirmation' => 'secure-admin-password',
                'registration_key' => str_repeat('k', 32),
            ])->assertNotFound();
        }

        $this->assertDatabaseCount('users', 0);
    }

    public function test_configured_registration_key_allows_admin_creation_and_sign_in(): void
    {
        $registrationKey = str_repeat('k', 32);
        config(['admin.registration_key' => $registrationKey]);

        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee(route('admin.register'));

        $this->get(route('admin.register'))
            ->assertOk()
            ->assertSee(__('Administrator registration key'));

        $this->post(route('admin.register.store'), [
            'name' => 'New Administrator',
            'email' => ' NEW-ADMIN@example.com ',
            'password' => 'secure-admin-password',
            'password_confirmation' => 'secure-admin-password',
            'registration_key' => $registrationKey,
        ])
            ->assertRedirectToRoute('admin.dashboard')
            ->assertSessionHasNoErrors();

        $admin = User::query()->where('email', 'new-admin@example.com')->firstOrFail();

        $this->assertSame('admin', $admin->role);
        $this->assertTrue($admin->is_active);
        $this->assertTrue(Hash::check('secure-admin-password', $admin->password));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_invalid_registration_key_is_rejected_without_flashing_the_secret(): void
    {
        config(['admin.registration_key' => str_repeat('k', 32)]);

        $this->from(route('admin.register'))
            ->post(route('admin.register.store'), [
                'name' => 'New Administrator',
                'email' => 'new-admin@example.com',
                'password' => 'secure-admin-password',
                'password_confirmation' => 'secure-admin-password',
                'registration_key' => 'incorrect-key',
            ])
            ->assertRedirectToRoute('admin.register')
            ->assertSessionHasErrors('registration_key');

        $this->assertArrayNotHasKey('registration_key', session()->getOldInput());
        $this->assertDatabaseCount('users', 0);
        $this->assertGuest();
    }

    public function test_admin_registration_rejects_a_short_password(): void
    {
        config(['admin.registration_key' => str_repeat('k', 32)]);

        $this->post(route('admin.register.store'), [
            'name' => 'New Administrator',
            'email' => 'new-admin@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
            'registration_key' => str_repeat('k', 32),
        ])
            ->assertRedirect()
            ->assertSessionHasErrors('password');

        $this->assertDatabaseCount('users', 0);
        $this->assertGuest();
    }

    public function test_admin_registration_attempts_are_rate_limited(): void
    {
        config(['admin.registration_key' => str_repeat('k', 32)]);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('admin.register.store'), [
                'name' => 'New Administrator',
                'email' => 'new-admin@example.com',
                'password' => 'secure-admin-password',
                'password_confirmation' => 'secure-admin-password',
                'registration_key' => 'incorrect-key',
            ])->assertRedirect();
        }

        $this->post(route('admin.register.store'), [
            'name' => 'New Administrator',
            'email' => 'new-admin@example.com',
            'password' => 'secure-admin-password',
            'password_confirmation' => 'secure-admin-password',
            'registration_key' => 'incorrect-key',
        ])->assertTooManyRequests();
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

    public function test_regular_user_visiting_admin_login_is_not_logged_out(): void
    {
        $user = User::factory()->create();
        $user->forceFill(['role' => 'user'])->save();

        $this->actingAs($user)
            ->get(route('admin.login'))
            ->assertRedirectToRoute('home');

        $this->assertAuthenticatedAs($user);
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
