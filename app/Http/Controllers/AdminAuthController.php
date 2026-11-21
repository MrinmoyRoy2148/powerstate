<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home');
        }

        return view('auth.admin-login', [
            'registrationEnabled' => $this->hasRegistrationKey(),
        ]);
    }

    public function showRegistrationForm(): View
    {
        abort_unless($this->hasRegistrationKey(), 404);

        return view('auth.admin-register');
    }

    public function register(Request $request): RedirectResponse
    {
        $registrationKey = config('admin.registration_key');
        abort_unless(is_string($registrationKey) && strlen($registrationKey) >= 32, 404);

        $email = $request->input('email');

        if (is_string($email)) {
            $request->merge(['email' => Str::lower(trim($email))]);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::min(12)],
            'registration_key' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput($request->except(['password', 'password_confirmation', 'registration_key']))
                ->withErrors($validator);
        }

        $validated = $validator->validated();

        if (! hash_equals($registrationKey, $validated['registration_key'])) {
            return back()
                ->withInput($request->except(['password', 'password_confirmation', 'registration_key']))
                ->withErrors(['registration_key' => __('The administrator registration key is invalid.')]);
        }

        $admin = new User;
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->password = $validated['password'];
        $admin->role = 'admin';
        $admin->is_active = true;
        $admin->save();

        Auth::login($admin);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $email = Str::lower($validated['email']);
        $user = User::query()->where('email', $email)->first();

        if (
            $user === null
            || ! Hash::check($validated['password'], $user->password)
            || ! $user->isAdmin()
        ) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __('These credentials do not match an active admin account.')]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    private function hasRegistrationKey(): bool
    {
        $registrationKey = config('admin.registration_key');

        return is_string($registrationKey) && strlen($registrationKey) >= 32;
    }
}
