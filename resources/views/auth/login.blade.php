@extends('layouts.app')

@php
    $title = __('messages.auth.login_title') . ' | ' . __('messages.brand');
@endphp

@section('content')
<section class="mx-auto mt-6 w-full max-w-lg rounded-2xl border border-[#13302426] bg-white/90 p-6 shadow-[0_12px_30px_rgba(19,48,36,0.12)] dark:border-[#d7e7dd30] dark:bg-[#1d2c29e0]">
    <h2 class="font-display text-3xl">{{ __('messages.auth.login_title') }}</h2>
    <p class="mt-2 text-sm text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.auth.login_text') }}</p>

    <form method="POST" action="{{ route('login.submit') }}" class="mt-5 space-y-4">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-semibold">{{ __('messages.auth.email') }}</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-[#13302433] px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]" required>
        </div>
        <div>
            <label class="mb-1 block text-sm font-semibold">{{ __('messages.auth.password') }}</label>
            <input type="password" name="password" class="w-full rounded-lg border border-[#13302433] px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]" required>
        </div>
        <label class="inline-flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember" value="1"> {{ __('messages.auth.remember') }}
        </label>
        <button type="submit" class="w-full rounded-lg bg-[#0e8f6f] px-4 py-2.5 font-bold text-white">{{ __('messages.auth.sign_in') }}</button>
    </form>
</section>
@endsection
