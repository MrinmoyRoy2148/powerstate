<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Admin sign in') }} | PowerStat BD</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-950 px-4 py-10 text-slate-800 antialiased">
    <main class="w-full max-w-md">
        <a href="{{ route('home') }}" class="mb-6 inline-flex items-center gap-3 text-white">
            <span class="flex size-11 items-center justify-center rounded-2xl bg-emerald-500 text-xl font-black">P</span>
            <span class="text-lg font-extrabold">PowerStat <span class="text-emerald-400">BD</span></span>
        </a>
        <form method="POST" action="{{ route('language.update') }}" class="mb-4 flex justify-end">
            @csrf
            <input type="hidden" name="locale" value="{{ app()->getLocale() === 'bn' ? 'en' : 'bn' }}">
            <button class="rounded-xl border border-slate-700 px-3 py-2 text-sm font-bold text-white hover:border-emerald-400" type="submit" aria-label="{{ __('Switch language') }}">{{ app()->getLocale() === 'bn' ? 'English' : 'বাংলা' }}</button>
        </form>
        <section class="rounded-3xl bg-white p-7 shadow-2xl sm:p-9">
            <p class="text-xs font-bold text-emerald-700">{{ __('Administration') }}</p>
            <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-950">{{ __('Welcome back') }}</h1>
            <p class="mt-2 text-sm leading-6 text-slate-600">{{ __('Sign in with an authorized administrator account.') }}</p>

            @if ($errors->any())
                <div class="mt-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900" role="alert">
                    @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}" class="mt-7 grid gap-5">
                @csrf
                <div>
                    <label for="email" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Email address') }}</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                </div>
                <div>
                    <label for="password" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Password') }}</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                </div>
                <button class="mt-1 rounded-xl bg-emerald-600 px-5 py-3 font-bold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" type="submit">{{ __('Sign in securely') }}</button>
            </form>
        </section>
        <a href="{{ route('home') }}" class="mt-5 block text-center text-sm font-semibold text-slate-300 hover:text-white">{{ __('← Return to public site') }}</a>
    </main>
</body>
</html>
