<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', ['admin' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $admin = $request->user();
        if (is_string($email = $request->input('email'))) {
            $request->merge(['email' => Str::lower(trim($email))]);
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($admin)],
        ]);

        $admin->update($validated);

        return redirect()->route('admin.profile.edit')->with('success', __('Profile updated successfully.'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(12)],
        ]);

        $request->user()->update(['password' => $validated['password']]);
        $request->session()->regenerate();

        return redirect()->route('admin.profile.edit')->with('success', __('Password updated successfully.'));
    }
}
