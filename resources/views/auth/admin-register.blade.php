<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Register an administrator') }} | PowerStat BD</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-950 px-4 py-10 text-slate-800 antialiased">
    <main class="w-full max-w-lg">
        <a href="{{ route('home') }}" class="mb-6 inline-flex items-center gap-3 text-white">
            <span class="flex size-11 items-center justify-center rounded-xl bg-emerald-500 text-xl font-black">P</span>
            <span class="text-lg font-extrabold">PowerStat <span class="text-emerald-400">BD</span></span>
        </a>
        <section class="rounded-2xl bg-white p-7 sm:p-9">
            <p class="text-xs font-bold text-emerald-700">{{ __('Administration') }}</p>
            <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-950">{{ __('Register an administrator') }}</h1>
            <p class="mt-2 text-sm leading-6 text-slate-600">{{ __('This protected form creates an administrator account. Keep the registration key private.') }}</p>

            @if ($errors->any())
                <div class="mt-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900" role="alert">
                    @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.register.store') }}" class="mt-7 grid gap-5">
                @csrf
                <div>
                    <label for="admin-register-name" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Name') }}</label>
                    <input id="admin-register-name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                </div>
                <div>
                    <label for="admin-register-email" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Email address') }}</label>
                    <input id="admin-register-email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                </div>
                <div>
                    <label for="admin-register-password" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Password') }}</label>
                    <input id="admin-register-password" name="password" type="password" required autocomplete="new-password" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    <p class="mt-2 text-xs text-slate-500">{{ __('Use at least 12 characters.') }}</p>
                </div>
                <div>
                    <label for="admin-register-password-confirmation" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Confirm password') }}</label>
                    <input id="admin-register-password-confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                </div>
                <div>
                    <label for="admin-registration-key" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Administrator registration key') }}</label>
                    <input id="admin-registration-key" name="registration_key" type="password" required autocomplete="off" aria-describedby="admin-registration-key-help" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    <p id="admin-registration-key-help" class="mt-2 text-xs leading-5 text-slate-500">{{ __('The key is configured in the hosting environment and is never saved with form data.') }}</p>
                </div>
                <button class="min-h-12 rounded-lg bg-emerald-700 px-5 py-3 font-bold text-white hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2" type="submit">{{ __('Create administrator account') }}</button>
            </form>
        </section>
        <a href="{{ route('admin.login') }}" class="mt-5 block text-center text-sm font-semibold text-slate-300 hover:text-white">{{ __('Back to admin sign in') }}</a>
    </main>
</body>
</html>
