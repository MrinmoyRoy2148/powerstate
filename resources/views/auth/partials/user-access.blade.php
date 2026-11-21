@php
    $isRegistration = $mode === 'register';
@endphp

<section class="mx-auto grid max-w-5xl overflow-hidden rounded-2xl border border-slate-200 bg-white lg:grid-cols-[0.9fr_1.1fr]">
    <aside class="flex flex-col justify-between gap-10 bg-emerald-700 p-7 text-white sm:p-10">
        <div>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 text-white" aria-label="{{ __('PowerStat BD home') }}">
                <span class="flex size-11 items-center justify-center rounded-xl bg-white text-xl font-black text-emerald-700">P</span>
                <span class="text-lg font-extrabold">PowerStat BD</span>
            </a>
            <p class="mt-12 text-sm font-bold text-emerald-100">{{ __('Area-wise power updates') }}</p>
            <h1 class="mt-3 max-w-sm text-3xl font-black leading-tight tracking-tight sm:text-4xl">{{ __('Know what is happening in your area.') }}</h1>
            <p class="mt-4 max-w-md text-sm leading-6 text-emerald-50">{{ __('Browse published schedules, outage updates, and notices for service areas across Bangladesh.') }}</p>
        </div>

        <div class="border-t border-emerald-500 pt-6">
            <p class="text-sm text-emerald-50">
                {{ $isRegistration ? __('Already have an account?') : __('New to PowerStat BD?') }}
            </p>
            <a
                href="{{ $isRegistration ? route('login') : route('register') }}"
                class="mt-3 inline-flex min-h-11 items-center justify-center rounded-lg border border-white px-4 py-2 text-sm font-bold text-white hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-emerald-700"
            >
                {{ $isRegistration ? __('Log in') : __('Create an account') }}
            </a>
        </div>
    </aside>

    <div class="p-6 sm:p-10">
        <nav class="mb-8 grid grid-cols-2 rounded-lg bg-slate-100 p-1" aria-label="{{ __('Account access') }}">
            <a
                href="{{ route('login') }}"
                @if (! $isRegistration) aria-current="page" @endif
                class="flex min-h-10 items-center justify-center rounded-md px-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-emerald-600 {{ ! $isRegistration ? 'bg-white text-emerald-800 shadow-sm' : 'text-slate-600 hover:text-slate-950' }}"
            >{{ __('Log in') }}</a>
            <a
                href="{{ route('register') }}"
                @if ($isRegistration) aria-current="page" @endif
                class="flex min-h-10 items-center justify-center rounded-md px-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-emerald-600 {{ $isRegistration ? 'bg-white text-emerald-800 shadow-sm' : 'text-slate-600 hover:text-slate-950' }}"
            >{{ __('Create an account') }}</a>
        </nav>

        <p class="text-sm font-bold text-emerald-700">{{ __('PowerStat BD account') }}</p>
        <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-950">
            {{ $isRegistration ? __('Create an account') : __('Sign in to your account') }}
        </h2>
        <p class="mt-2 text-sm leading-6 text-slate-600">
            {{ $isRegistration ? __('Create a public account to save the areas you follow.') : __('Sign in to view the areas you have saved.') }}
        </p>

        @if (session('status'))
            <p class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900" role="status">{{ session('status') }}</p>
        @endif

        @if ($isRegistration)
            <form method="POST" action="{{ route('register.store') }}" class="mt-7 grid gap-5">
                @csrf
                <div>
                    <label for="user-register-name" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Name') }}</label>
                    <input id="user-register-name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                </div>
                <div>
                    <label for="user-register-email" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Email address') }}</label>
                    <input id="user-register-email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                </div>
                <div>
                    <label for="user-register-password" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Password') }}</label>
                    <input id="user-register-password" name="password" type="password" required autocomplete="new-password" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                    <p class="mt-2 text-xs text-slate-500">{{ __('Use at least 12 characters.') }}</p>
                </div>
                <div>
                    <label for="user-register-password-confirmation" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Confirm password') }}</label>
                    <input id="user-register-password-confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                </div>
                <button class="min-h-12 rounded-lg bg-emerald-700 px-5 py-3 font-bold text-white hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2" type="submit">{{ __('Create an account') }}</button>
                <p class="text-xs leading-5 text-slate-500">{{ __('Public accounts do not include administrator access.') }}</p>
            </form>
        @else
            <form method="POST" action="{{ route('login.store') }}" class="mt-7 grid gap-5">
                @csrf
                <div>
                    <label for="user-login-email" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Email address') }}</label>
                    <input id="user-login-email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                </div>
                <div>
                    <label for="user-login-password" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Password') }}</label>
                    <input id="user-login-password" name="password" type="password" required autocomplete="current-password" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                    <a class="mt-2 inline-block text-sm font-semibold text-emerald-700 hover:text-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600" href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                </div>
                <button class="min-h-12 rounded-lg bg-emerald-700 px-5 py-3 font-bold text-white hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2" type="submit">{{ __('Log in') }}</button>
            </form>
        @endif

        <p class="mt-7 border-t border-slate-200 pt-5 text-sm text-slate-600">
            {{ __('Administrator?') }}
            <a class="font-bold text-emerald-700 hover:text-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600" href="{{ route('admin.login') }}">{{ __('Admin sign in') }}</a>
        </p>
    </div>
</section>
