<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class UserAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_registration_and_login_forms(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee(__('Create an account'));

        $this->get(route('login'))
            ->assertOk()
            ->assertSee(__('Sign in to your account'));
    }

    public function test_registration_creates_an_active_user_without_honoring_privileged_input(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'New Member',
            'email' => 'NEW-MEMBER@example.com',
            'password' => 'Strong-password-2026!',
            'password_confirmation' => 'Strong-password-2026!',
            'role' => 'admin',
            'is_active' => false,
        ]);

        $response->assertRedirectToRoute('home');

        $user = User::query()->where('email', 'new-member@example.com')->firstOrFail();

        $this->assertModelExists($user);
        $this->assertSame('user', $user->role);
        $this->assertTrue($user->is_active);
        $this->assertTrue(Hash::check('Strong-password-2026!', $user->password));
        $this->assertAuthenticatedAs($user);
    }

    public function test_registration_rejects_a_duplicate_email_even_when_case_differs(): void
    {
        User::factory()->create(['email' => 'member@example.com']);

        $response = $this->post(route('register.store'), [
            'name' => 'Another Member',
            'email' => 'MEMBER@example.com',
            'password' => 'Strong-password-2026!',
            'password_confirmation' => 'Strong-password-2026!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertSame(1, User::query()->count());
        $this->assertGuest();
    }

    public function test_registration_rejects_an_invalid_email_address(): void
    {
        $this->post(route('register.store'), [
            'name' => 'New Member',
            'email' => 'not-an-email',
            'password' => 'Strong-password-2026!',
            'password_confirmation' => 'Strong-password-2026!',
        ])
            ->assertSessionHasErrors('email');

        $this->assertSame(0, User::query()->count());
    }

    public function test_registration_rejects_mismatched_password_confirmation(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'New Member',
            'email' => 'new-member@example.com',
            'password' => 'Strong-password-2026!',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertSame(0, User::query()->count());
    }

    public function test_active_user_can_sign_in_and_sign_out(): void
    {
        $user = $this->createRegularUser([
            'email' => 'member@example.com',
            'password' => 'Strong-password-2026!',
        ]);

        $this->post(route('login.store'), [
            'email' => 'MEMBER@example.com',
            'password' => 'Strong-password-2026!',
        ])->assertRedirectToRoute('home');

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))
            ->assertRedirectToRoute('home');

        $this->assertGuest();
    }

    public function test_incorrect_or_inactive_user_credentials_are_rejected(): void
    {
        $user = $this->createRegularUser([
            'email' => 'member@example.com',
            'password' => 'Strong-password-2026!',
            'is_active' => false,
        ]);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'Strong-password-2026!',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_regular_user_cannot_sign_in_through_user_login_or_open_admin_pages(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'Strong-password-2026!',
        ]);

        $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'Strong-password-2026!',
        ])->assertSessionHasErrors('email');

        $user = $this->createRegularUser();

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_login_attempts_are_rate_limited(): void
    {
        $user = $this->createRegularUser([
            'email' => 'member@example.com',
            'password' => 'Strong-password-2026!',
        ]);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('login.store'), [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])->assertRedirect();
        }

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertTooManyRequests();
    }

    public function test_password_reset_link_is_sent_for_a_registered_user(): void
    {
        Notification::fake();
        $user = $this->createRegularUser(['email' => 'member@example.com']);

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertRedirect()
            ->assertSessionHas('status', __('If an account matches that email address, reset instructions will be sent when available.'));

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_password_reset_request_does_not_disclose_unknown_emails(): void
    {
        Notification::fake();

        $this->post(route('password.email'), ['email' => 'unknown@example.com'])
            ->assertRedirect()
            ->assertSessionHas('status', __('If an account matches that email address, reset instructions will be sent when available.'));

        Notification::assertNothingSent();
    }

    public function test_password_reset_throttling_does_not_disclose_account_presence(): void
    {
        $user = $this->createRegularUser(['email' => 'member@example.com']);
        $status = __('If an account matches that email address, reset instructions will be sent when available.');

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertSessionHas('status', $status);

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertSessionHas('status', $status);
    }

    public function test_user_can_reset_password_with_a_valid_token(): void
    {
        $user = $this->createRegularUser(['email' => 'member@example.com']);
        $token = Password::createToken($user);

        $this->get(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]))
            ->assertOk()
            ->assertViewHas('token', $token);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'Another-strong-password-2026!',
            'password_confirmation' => 'Another-strong-password-2026!',
        ])
            ->assertRedirectToRoute('login')
            ->assertSessionHas('status', __('Your password has been reset.'));

        $this->assertTrue(Hash::check(
            'Another-strong-password-2026!',
            $user->fresh()->password,
        ));
    }

    public function test_guest_is_redirected_to_user_login_for_user_only_routes(): void
    {
        $this->post(route('logout'))
            ->assertRedirectToRoute('login');
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createRegularUser(array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        $user->forceFill(['role' => 'user'])->save();

        return $user;
    }
}
