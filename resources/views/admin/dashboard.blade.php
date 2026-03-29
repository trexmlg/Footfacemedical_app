@extends('layouts.app')

@php
    $title = __('messages.nav.admin') . ' | ' . __('messages.brand');
@endphp

@section('content')
<section class="mt-6 space-y-5">
    <article class="rounded-2xl border border-[#13302426] bg-white/90 p-6 shadow-[0_12px_30px_rgba(19,48,36,0.12)] dark:border-[#d7e7dd30] dark:bg-[#1d2c29e0]">
        <h2 class="font-display text-3xl">{{ __('messages.admin.console') }}</h2>
        <p class="mt-2 text-sm text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.admin.text') }}</p>
    </article>

    <article class="rounded-2xl border border-[#13302426] bg-white/90 p-6 dark:border-[#d7e7dd30] dark:bg-[#1d2c29e0]">
        <h3 class="font-display text-2xl">{{ __('messages.admin.users_roles') }}</h3>
        <div class="mt-4 overflow-auto">
            <table class="min-w-full text-sm">
                <thead><tr class="border-b border-[#13302426] text-left dark:border-[#d7e7dd30]"><th class="px-3 py-2">{{ __('messages.admin.name') }}</th><th class="px-3 py-2">{{ __('messages.admin.surname') }}</th><th class="px-3 py-2">{{ __('messages.admin.email') }}</th><th class="px-3 py-2">{{ __('messages.admin.phone') }}</th><th class="px-3 py-2">{{ __('messages.admin.role') }}</th><th class="px-3 py-2">{{ __('messages.admin.actions') }}</th></tr></thead>
                <tbody>
                @foreach($users as $user)
                    <tr class="border-b border-[#1330241f] dark:border-[#d7e7dd20]">
                        <form method="POST" action="{{ route('admin.users.update', $user) }}">
                            @csrf
                            @method('PUT')
                            <td class="px-3 py-2"><input type="text" name="name" value="{{ $user->name }}" class="w-36 rounded border border-[#13302433] px-2 py-1 dark:border-[#d7e7dd30] dark:bg-[#21332f]" required></td>
                            <td class="px-3 py-2"><input type="text" name="surname" value="{{ $user->surname }}" class="w-36 rounded border border-[#13302433] px-2 py-1 dark:border-[#d7e7dd30] dark:bg-[#21332f]" required></td>
                            <td class="px-3 py-2"><input type="email" name="email" value="{{ $user->email }}" class="w-56 rounded border border-[#13302433] px-2 py-1 dark:border-[#d7e7dd30] dark:bg-[#21332f]" required></td>
                            <td class="px-3 py-2"><input type="text" name="phone" value="{{ $user->phone }}" class="w-40 rounded border border-[#13302433] px-2 py-1 dark:border-[#d7e7dd30] dark:bg-[#21332f]" required></td>
                            <td class="px-3 py-2">
                                <select name="role" class="rounded border border-[#13302433] px-2 py-1 dark:border-[#d7e7dd30] dark:bg-[#21332f]">
                                    @foreach(['user', 'podolog', 'admin'] as $value)
                                        <option value="{{ $value }}" @selected($user->role === $value)>{{ __('messages.roles.' . $value) }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <button type="submit" class="rounded bg-[#0e8f6f] px-3 py-1.5 text-white">{{ __('messages.admin.update') }}</button>
                        </form>
                                    <form method="POST" action="{{ route('admin.users.delete', $user) }}" onsubmit="return confirm('{{ __('messages.admin.delete_user_confirm') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded bg-[#b12d1f] px-3 py-1.5 text-white">{{ __('messages.admin.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </article>

    <article class="rounded-2xl border border-[#13302426] bg-white/90 p-6 dark:border-[#d7e7dd30] dark:bg-[#1d2c29e0]">
        <h3 class="font-display text-2xl">{{ __('messages.admin.deleted_users') }}</h3>
        <div class="mt-4 overflow-auto">
            <table class="min-w-full text-sm">
                <thead><tr class="border-b border-[#13302426] text-left dark:border-[#d7e7dd30]"><th class="px-3 py-2">{{ __('messages.admin.name') }}</th><th class="px-3 py-2">{{ __('messages.admin.email') }}</th><th class="px-3 py-2">{{ __('messages.admin.deleted_at') }}</th><th class="px-3 py-2">{{ __('messages.admin.actions') }}</th></tr></thead>
                <tbody>
                @forelse($deletedUsers as $user)
                    <tr class="border-b border-[#1330241f] dark:border-[#d7e7dd20]">
                        <td class="px-3 py-2">{{ $user->name }} {{ $user->surname }}</td>
                        <td class="px-3 py-2">{{ $user->email }}</td>
                        <td class="px-3 py-2">{{ optional($user->deleted_at)->format('Y-m-d H:i') }}</td>
                        <td class="px-3 py-2">
                            <form method="POST" action="{{ route('admin.users.restore', $user->id) }}">
                                @csrf
                                <button type="submit" class="rounded bg-[#0e8f6f] px-3 py-1.5 text-white">{{ __('messages.admin.restore') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-3 py-4 text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.admin.no_deleted_users') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </article>

    <article class="rounded-2xl border border-[#13302426] bg-white/90 p-6 dark:border-[#d7e7dd30] dark:bg-[#1d2c29e0]">
        <h3 class="font-display text-2xl">{{ __('messages.admin.reservations_monitoring') }}</h3>
        <div class="mt-4 overflow-auto">
            <table class="min-w-full text-sm">
                <thead><tr class="border-b border-[#13302426] text-left dark:border-[#d7e7dd30]"><th class="px-3 py-2">{{ __('messages.admin.patient') }}</th><th class="px-3 py-2">{{ __('messages.admin.title_col') }}</th><th class="px-3 py-2">{{ __('messages.admin.time') }}</th><th class="px-3 py-2">{{ __('messages.admin.status') }}</th><th class="px-3 py-2">{{ __('messages.admin.notes') }}</th><th class="px-3 py-2">{{ __('messages.admin.actions') }}</th></tr></thead>
                <tbody>
                @foreach($events as $event)
                    <tr class="border-b border-[#1330241f] align-top dark:border-[#d7e7dd20]">
                        <td class="px-3 py-2">{{ $event->patient_name ?: '-' }}</td>
                        <td class="px-3 py-2">{{ $event->title }}</td>
                        <td class="px-3 py-2">{{ optional($event->start_at)->format('Y-m-d H:i') }} - {{ optional($event->end_at)->format('H:i') }}</td>
                        <td class="px-3 py-2">
                            <form method="POST" action="{{ route('admin.reservations.update', $event) }}" class="space-y-2">
                                @csrf
                                @method('PUT')
                                <select name="status" class="w-full rounded border border-[#13302433] px-2 py-1 dark:border-[#d7e7dd30] dark:bg-[#21332f]">
                                    @foreach(['booked', 'done', 'no_show', 'cancelled'] as $value)
                                        <option value="{{ $value }}" @selected($event->status === $value)>{{ __('messages.roles.' . $value) }}</option>
                                    @endforeach
                                </select>
                        </td>
                        <td class="px-3 py-2"><input type="text" name="notes" value="{{ $event->notes }}" class="w-56 rounded border border-[#13302433] px-2 py-1 dark:border-[#d7e7dd30] dark:bg-[#21332f]"></td>
                        <td class="px-3 py-2">
                            <div class="flex items-center gap-2">
                                <button type="submit" class="rounded bg-[#0e8f6f] px-3 py-1.5 text-white">{{ __('messages.admin.save') }}</button>
                            </form>
                                <form method="POST" action="{{ route('admin.reservations.delete', $event) }}" onsubmit="return confirm('{{ __('messages.admin.delete_reservation_confirm') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded bg-[#b12d1f] px-3 py-1.5 text-white">{{ __('messages.admin.delete') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </article>

    <article class="rounded-2xl border border-[#13302426] bg-white/90 p-6 dark:border-[#d7e7dd30] dark:bg-[#1d2c29e0]">
        <h3 class="font-display text-2xl">{{ __('messages.admin.deleted_reservations') }}</h3>
        <div class="mt-4 overflow-auto">
            <table class="min-w-full text-sm">
                <thead><tr class="border-b border-[#13302426] text-left dark:border-[#d7e7dd30]"><th class="px-3 py-2">{{ __('messages.admin.patient') }}</th><th class="px-3 py-2">{{ __('messages.admin.title_col') }}</th><th class="px-3 py-2">{{ __('messages.admin.time') }}</th><th class="px-3 py-2">{{ __('messages.admin.deleted_at') }}</th><th class="px-3 py-2">{{ __('messages.admin.actions') }}</th></tr></thead>
                <tbody>
                @forelse($deletedEvents as $event)
                    <tr class="border-b border-[#1330241f] dark:border-[#d7e7dd20]">
                        <td class="px-3 py-2">{{ $event->patient_name ?: '-' }}</td>
                        <td class="px-3 py-2">{{ $event->title }}</td>
                        <td class="px-3 py-2">{{ optional($event->start_at)->format('Y-m-d H:i') }} - {{ optional($event->end_at)->format('H:i') }}</td>
                        <td class="px-3 py-2">{{ optional($event->deleted_at)->format('Y-m-d H:i') }}</td>
                        <td class="px-3 py-2">
                            <form method="POST" action="{{ route('admin.reservations.restore', $event->id) }}">
                                @csrf
                                <button type="submit" class="rounded bg-[#0e8f6f] px-3 py-1.5 text-white">{{ __('messages.admin.restore') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-3 py-4 text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.admin.no_deleted_reservations') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </article>

    <article class="rounded-2xl border border-[#13302426] bg-white/90 p-6 dark:border-[#d7e7dd30] dark:bg-[#1d2c29e0]">
        <h3 class="font-display text-2xl">{{ __('messages.admin.ads') }}</h3>

        <form method="POST" action="{{ route('admin.promotions.create') }}" class="mt-4 grid gap-3 rounded-xl border border-[#13302424] p-4 md:grid-cols-4 dark:border-[#d7e7dd30]">
            @csrf
            <input type="text" name="badge" placeholder="{{ __('messages.admin.badge') }}" class="rounded border border-[#13302433] px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]">
            <input type="text" name="title" placeholder="{{ __('messages.admin.promotion_title') }}" class="rounded border border-[#13302433] px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]" required>
            <input type="number" name="sort_order" placeholder="{{ __('messages.admin.order') }}" class="rounded border border-[#13302433] px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]">
            <button type="submit" class="rounded bg-[#0e8f6f] px-3 py-2 text-white">{{ __('messages.admin.add_promotion') }}</button>
            <textarea name="description" placeholder="{{ __('messages.admin.description') }}" class="md:col-span-4 rounded border border-[#13302433] px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]"></textarea>
        </form>

        <div class="mt-4 space-y-3">
            @forelse($promotions as $promotion)
                <div class="rounded-xl border border-[#13302424] p-4 dark:border-[#d7e7dd30]">
                    <form method="POST" action="{{ route('admin.promotions.update', $promotion) }}" class="grid gap-2 md:grid-cols-5">
                        @csrf
                        @method('PUT')
                        <input type="text" name="badge" value="{{ $promotion->badge }}" class="rounded border border-[#13302433] px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]">
                        <input type="text" name="title" value="{{ $promotion->title }}" class="rounded border border-[#13302433] px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]" required>
                        <input type="number" name="sort_order" value="{{ $promotion->sort_order }}" class="rounded border border-[#13302433] px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]">
                        <button type="submit" class="rounded bg-[#0e8f6f] px-3 py-2 text-white">{{ __('messages.admin.update') }}</button>
                        <div></div>
                        <textarea name="description" class="md:col-span-5 rounded border border-[#13302433] px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]">{{ $promotion->description }}</textarea>
                    </form>

                    <form method="POST" action="{{ route('admin.promotions.delete', $promotion) }}" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded bg-[#b12d1f] px-3 py-2 text-white">{{ __('messages.admin.delete') }}</button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.admin.no_promotions') }}</p>
            @endforelse
        </div>

        <h4 class="mt-8 font-display text-xl">{{ __('messages.admin.deleted_promotions') }}</h4>
        <div class="mt-3 space-y-3">
            @forelse($deletedPromotions as $promotion)
                <div class="rounded-xl border border-[#13302424] p-4 dark:border-[#d7e7dd30]">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <div class="font-semibold">{{ $promotion->title }}</div>
                            <div class="text-xs text-[#446457] dark:text-[#b6cbc0]">{{ optional($promotion->deleted_at)->format('Y-m-d H:i') }}</div>
                        </div>
                        <form method="POST" action="{{ route('admin.promotions.restore', $promotion->id) }}">
                            @csrf
                            <button type="submit" class="rounded bg-[#0e8f6f] px-3 py-2 text-white">{{ __('messages.admin.restore') }}</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-sm text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.admin.no_deleted_promotions') }}</p>
            @endforelse
        </div>
    </article>

    <article class="rounded-2xl border border-[#13302426] bg-white/90 p-6 dark:border-[#d7e7dd30] dark:bg-[#1d2c29e0]">
        <h3 class="font-display text-2xl">{{ __('messages.admin.audit_log') }}</h3>
        <div class="mt-4 overflow-auto">
            <table class="min-w-full text-sm">
                <thead><tr class="border-b border-[#13302426] text-left dark:border-[#d7e7dd30]"><th class="px-3 py-2">{{ __('messages.admin.time') }}</th><th class="px-3 py-2">{{ __('messages.admin.admin_user') }}</th><th class="px-3 py-2">{{ __('messages.admin.action') }}</th><th class="px-3 py-2">{{ __('messages.admin.target') }}</th><th class="px-3 py-2">{{ __('messages.admin.details') }}</th></tr></thead>
                <tbody>
                @forelse($auditLogs as $log)
                    <tr class="border-b border-[#1330241f] dark:border-[#d7e7dd20]">
                        <td class="px-3 py-2">{{ optional($log->created_at)->format('Y-m-d H:i:s') }}</td>
                        <td class="px-3 py-2">{{ trim(($log->admin->name ?? '-') . ' ' . ($log->admin->surname ?? '')) }}</td>
                        <td class="px-3 py-2">{{ $log->action }}</td>
                        <td class="px-3 py-2">{{ $log->target_type }} #{{ $log->target_id }}</td>
                        <td class="px-3 py-2">{{ $log->description }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-3 py-4 text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.admin.no_audit_entries') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </article>
</section>
@endsection
