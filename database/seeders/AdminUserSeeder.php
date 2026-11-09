<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = config('admin.initial_admin.name');
        $email = config('admin.initial_admin.email');
        $password = config('admin.initial_admin.password');

        if (! is_string($name) || trim($name) === ''
            || ! is_string($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false
            || ! is_string($password) || strlen($password) < 12
        ) {
            throw new RuntimeException(
                'Set ADMIN_NAME, a valid ADMIN_EMAIL, and an ADMIN_PASSWORD of at least 12 characters before seeding the administrator.'
            );
        }

        $name = trim($name);
        $email = Str::lower(trim($email));
        $admin = User::query()->firstOrNew(['email' => $email]);
        $admin->forceFill([
            'name' => $name,
            'password' => $password,
            'role' => 'admin',
            'is_active' => true,
        ])->save();
    }
}
