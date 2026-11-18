@extends('layouts.app')

@section('title', __('Register'))

@section('content')
    <section class="mx-auto max-w-lg rounded-3xl border border-slate-200 bg-white p-7 shadow-sm sm:p-9">
        <p class="text-sm font-bold text-emerald-700">{{ __('PowerStat BD account') }}</p>
        <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-950">{{ __('Create an account') }}</h1>

        @if ($errors->any())
            <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="mt-7 grid gap-5">
            @csrf
            <div>
                <label for="name" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Name') }}</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
            </div>
            <div>
                <label for="email" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Email address') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
            </div>
            <div>
                <label for="password" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                <p class="mt-2 text-xs text-slate-500">{{ __('Use at least 12 characters.') }}</p>
            </div>
            <div>
                <label for="password_confirmation" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Confirm password') }}</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
            </div>
            <button class="rounded-xl bg-emerald-600 px-5 py-3 font-bold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" type="submit">{{ __('Create an account') }}</button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-600">
            {{ __('Already have an account?') }}
            <a class="font-bold text-emerald-700 hover:text-emerald-800" href="{{ route('login') }}">{{ __('Log in') }}</a>
        </p>
    </section>
@endsection
