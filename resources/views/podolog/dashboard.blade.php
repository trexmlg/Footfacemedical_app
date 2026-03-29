@extends('layouts.app')

@php
    $title = __('messages.nav.podolog') . ' | ' . __('messages.brand');
@endphp

@section('content')
<section class="mt-6 rounded-2xl border border-[#13302426] bg-white/90 p-6 shadow-[0_12px_30px_rgba(19,48,36,0.12)] dark:border-[#d7e7dd30] dark:bg-[#1d2c29e0]">
    <h2 class="font-display text-3xl">{{ __('messages.podolog.title') }}</h2>
    <p class="mt-2 text-sm text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.podolog.text') }}</p>

    <div class="mt-5 overflow-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-[#13302426] text-left dark:border-[#d7e7dd30]">
                    <th class="px-3 py-2">{{ __('messages.podolog.user_card') }}</th>
                    <th class="px-3 py-2">{{ __('messages.podolog.title_col') }}</th>
                    <th class="px-3 py-2">{{ __('messages.podolog.time') }}</th>
                    <th class="px-3 py-2">{{ __('messages.podolog.status') }}</th>
                    <th class="px-3 py-2">{{ __('messages.podolog.notes') }}</th>
                    <th class="px-3 py-2">{{ __('messages.podolog.save_col') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                    <tr class="border-b border-[#1330241f] align-top dark:border-[#d7e7dd20]">
                        <td class="px-3 py-2">
                            @if($event->user)
                                <div class="font-semibold">{{ $event->user->name }} {{ $event->user->surname }}</div>
                                <div class="text-xs text-[#446457] dark:text-[#b6cbc0]">{{ $event->user->email }}</div>
                                <div class="text-xs text-[#446457] dark:text-[#b6cbc0]">{{ $event->user->phone }}</div>
                            @else
                                <div class="font-semibold">{{ $event->patient_name ?: '-' }}</div>
                                <div class="text-xs text-[#446457] dark:text-[#b6cbc0]">{{ $event->patient_phone ?: '-' }}</div>
                            @endif
                        </td>
                        <td class="px-3 py-2">{{ $event->title }}</td>
                        <td class="px-3 py-2">{{ optional($event->start_at)->format('Y-m-d H:i') }} - {{ optional($event->end_at)->format('H:i') }}</td>
                        <td class="px-3 py-2">
                            <form method="POST" action="{{ route('podolog.reservations.update', $event) }}" class="space-y-2">
                                @csrf
                                @method('PUT')
                                <select name="status" class="w-full rounded border border-[#13302433] px-2 py-1 dark:border-[#d7e7dd30] dark:bg-[#21332f]">
                                    @foreach(['booked', 'done', 'no_show', 'cancelled'] as $value)
                                        <option value="{{ $value }}" @selected($event->status === $value)>{{ __('messages.roles.' . $value) }}</option>
                                    @endforeach
                                </select>
                        </td>
                        <td class="px-3 py-2">
                                <input type="text" name="notes" value="{{ $event->notes }}" class="w-56 rounded border border-[#13302433] px-2 py-1 dark:border-[#d7e7dd30] dark:bg-[#21332f]" placeholder="{{ __('messages.podolog.optional_note') }}">
                        </td>
                        <td class="px-3 py-2">
                                <button type="submit" class="rounded bg-[#0e8f6f] px-3 py-1.5 text-white">{{ __('messages.podolog.update') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-3 py-6 text-center text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.podolog.empty') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
