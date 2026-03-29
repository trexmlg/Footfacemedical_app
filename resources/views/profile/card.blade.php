@extends('layouts.app')

@php
    $title = __('messages.nav.my_card') . ' | ' . __('messages.brand');
@endphp

@section('content')
<section class="mx-auto mt-6 w-full max-w-2xl rounded-2xl border border-[#13302426] bg-white/90 p-6 shadow-[0_12px_30px_rgba(19,48,36,0.12)] dark:border-[#d7e7dd30] dark:bg-[#1d2c29e0]">
    <h2 class="font-display text-3xl">{{ __('messages.profile.title') }}</h2>
    <p class="mt-2 text-sm text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.profile.text') }}</p>

    <form method="POST" action="{{ route('profile.card.update') }}" class="mt-5 grid gap-4 md:grid-cols-2">
        @csrf
        @method('PUT')

        <div>
            <label class="mb-1 block text-sm font-semibold">{{ __('messages.auth.name') }}</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border border-[#13302433] px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]" required>
        </div>
        <div>
            <label class="mb-1 block text-sm font-semibold">{{ __('messages.auth.surname') }}</label>
            <input type="text" name="surname" value="{{ old('surname', $user->surname) }}" class="w-full rounded-lg border border-[#13302433] px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]" required>
        </div>
        <div class="md:col-span-2">
            <label class="mb-1 block text-sm font-semibold">{{ __('messages.profile.email_read_only') }}</label>
            <input type="email" value="{{ $user->email }}" class="w-full rounded-lg border border-[#13302433] bg-gray-100 px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#283a36]" readonly>
        </div>
        <div class="md:col-span-2">
            <label class="mb-1 block text-sm font-semibold">{{ __('messages.auth.phone') }}</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-lg border border-[#13302433] px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]" required>
        </div>
        <div class="md:col-span-2">
            <button type="submit" class="w-full rounded-lg bg-[#0e8f6f] px-4 py-2.5 font-bold text-white">{{ __('messages.profile.save') }}</button>
        </div>
    </form>
</section>
@endsection
