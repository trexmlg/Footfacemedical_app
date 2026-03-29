@extends('layouts.app')

@php
    $title = __('messages.profile.patient_card_title') . ' | ' . __('messages.brand');
@endphp

@section('content')
<section class="mx-auto mt-6 w-full max-w-2xl rounded-2xl border border-[#13302426] bg-white/90 p-6 shadow-[0_12px_30px_rgba(19,48,36,0.12)] dark:border-[#d7e7dd30] dark:bg-[#1d2c29e0]">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h2 class="font-display text-3xl">{{ __('messages.profile.patient_card_title') }}</h2>
            <p class="mt-2 text-sm text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.profile.patient_card_text') }}</p>
        </div>
        <a href="{{ route('podolog.dashboard') }}" class="rounded-lg border border-[#13302433] px-4 py-2 text-sm font-semibold text-[#133024] transition hover:bg-[#eef7f2] dark:border-[#d7e7dd30] dark:text-[#d7e7dd] dark:hover:bg-[#274039]">{{ __('messages.profile.back_to_calendar') }}</a>
    </div>

    <div class="mt-6 grid gap-4 md:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-semibold">{{ __('messages.auth.name') }}</label>
            <input type="text" value="{{ $user->name }}" class="w-full rounded-lg border border-[#13302433] bg-gray-100 px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#283a36]" readonly>
        </div>
        <div>
            <label class="mb-1 block text-sm font-semibold">{{ __('messages.auth.surname') }}</label>
            <input type="text" value="{{ $user->surname }}" class="w-full rounded-lg border border-[#13302433] bg-gray-100 px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#283a36]" readonly>
        </div>
        <div class="md:col-span-2">
            <label class="mb-1 block text-sm font-semibold">{{ __('messages.auth.email') }}</label>
            <input type="email" value="{{ $user->email }}" class="w-full rounded-lg border border-[#13302433] bg-gray-100 px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#283a36]" readonly>
        </div>
        <div class="md:col-span-2">
            <label class="mb-1 block text-sm font-semibold">{{ __('messages.auth.phone') }}</label>
            <input type="text" value="{{ $user->phone }}" class="w-full rounded-lg border border-[#13302433] bg-gray-100 px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#283a36]" readonly>
        </div>
        <div>
            <label class="mb-1 block text-sm font-semibold">{{ __('messages.admin.role') }}</label>
            <input type="text" value="{{ __('messages.roles.' . $user->role) }}" class="w-full rounded-lg border border-[#13302433] bg-gray-100 px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#283a36]" readonly>
        </div>
        <div>
            <label class="mb-1 block text-sm font-semibold">ID</label>
            <input type="text" value="{{ $user->id }}" class="w-full rounded-lg border border-[#13302433] bg-gray-100 px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#283a36]" readonly>
        </div>
    </div>
</section>
@endsection
