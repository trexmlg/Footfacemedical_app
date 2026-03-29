@php
    $hideNav = false;
    $title = __('messages.brand') . ' | ' . __('messages.nav.calendar');
@endphp
@extends('layouts.app')

@section('content')
<div class="w-full flex flex-col items-center min-h-[60vh] pt-8">
    <section class="w-full max-w-7xl rounded-2xl border border-gray-200 bg-white p-8 shadow-lg dark:border-gray-700 dark:bg-gray-900">
        <h2 class="font-display text-4xl mb-4 text-center">{{ $pageHeading ?? __('messages.calendar.title') }}</h2>
        <p class="mb-8 text-gray-600 dark:text-gray-300 text-center">{{ $pageText ?? __('messages.calendar.text') }}</p>

        <div id="calendar" class="overflow-x-auto">
            <div class="flex justify-between items-center mb-4">
                <button id="prevWeek" class="rounded-full px-3 py-1 bg-gray-200 hover:bg-gray-300 text-xl font-bold" type="button" aria-label="Previous week">&#8592;</button>
                <div class="text-center">
                    <div id="calendarMonthTitle" class="font-display text-2xl"></div>
                    <span id="calendarRange" class="font-semibold text-sm text-[#446457] dark:text-[#b6cbc0]"></span>
                </div>
                <button id="nextWeek" class="rounded-full px-3 py-1 bg-gray-200 hover:bg-gray-300 text-xl font-bold" type="button" aria-label="Next week">&#8594;</button>
            </div>
            @if (!empty($canManage))
                <div class="mb-4 flex flex-wrap items-center justify-center gap-3 text-xs font-semibold text-[#446457] dark:text-[#b6cbc0]">
                    <span class="inline-flex items-center gap-2 rounded-full bg-[#e9f7f2] px-3 py-1 dark:bg-[#18372f]"><span class="h-2.5 w-2.5 rounded-full bg-[#0e8f6f]"></span>{{ __('messages.podolog.legend_booked') }}</span>
                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 dark:bg-slate-800"><span class="h-2.5 w-2.5 rounded-full bg-slate-500"></span>{{ __('messages.podolog.legend_done') }}</span>
                    <span class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1 dark:bg-amber-900/40"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>{{ __('messages.podolog.legend_no_show') }}</span>
                    <span class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-3 py-1 dark:bg-rose-900/30"><span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>{{ __('messages.podolog.legend_cancelled') }}</span>
                </div>
            @endif
            <div id="calendarStatus" class="mb-4 hidden rounded-lg px-3 py-2 text-sm"></div>
            <div id="calendarDays"></div>
            <div id="calendarSavedStatus" class="mt-4 hidden rounded-lg bg-green-100 px-3 py-2 text-sm font-semibold text-green-800"></div>
        </div>

        @if (!empty($canManage))
            <div id="manageEmptyState" class="mt-8 rounded-2xl border border-dashed border-[#13302433] bg-[#f6fbf8] p-6 text-center text-sm text-[#446457] dark:border-[#d7e7dd30] dark:bg-[#1b2926] dark:text-[#b6cbc0]">
                {{ __('messages.podolog.select_reservation') }}
            </div>

            <div id="managePanel" class="mt-8 hidden grid gap-6 lg:grid-cols-2">
                <article class="rounded-2xl border border-[#13302426] bg-[#f8fffc] p-6 dark:border-[#d7e7dd30] dark:bg-[#1f2f2b]">
                    <h3 class="font-display text-2xl">{{ __('messages.podolog.patient_card_title') }}</h3>
                    <p class="mt-2 text-sm text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.podolog.patient_card_text') }}</p>

                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-semibold">{{ __('messages.auth.name') }}</label>
                            <input type="text" id="detailName" class="w-full rounded-lg border border-[#13302433] bg-gray-100 px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#283a36]" readonly>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold">{{ __('messages.auth.surname') }}</label>
                            <input type="text" id="detailSurname" class="w-full rounded-lg border border-[#13302433] bg-gray-100 px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#283a36]" readonly>
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm font-semibold">{{ __('messages.auth.email') }}</label>
                            <input type="text" id="detailEmail" class="w-full rounded-lg border border-[#13302433] bg-gray-100 px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#283a36]" readonly>
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm font-semibold">{{ __('messages.auth.phone') }}</label>
                            <input type="text" id="detailPhone" class="w-full rounded-lg border border-[#13302433] bg-gray-100 px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#283a36]" readonly>
                        </div>
                        <div class="md:col-span-2 flex justify-end">
                            <a id="patientCardLink" href="#" class="hidden rounded-lg border border-[#13302433] px-4 py-2 text-sm font-semibold text-[#133024] transition hover:bg-[#eef7f2] dark:border-[#d7e7dd30] dark:text-[#d7e7dd] dark:hover:bg-[#274039]">{{ __('messages.podolog.open_full_card') }}</a>
                        </div>
                    </div>
                </article>

                <article class="rounded-2xl border border-[#13302426] bg-[#f8fffc] p-6 dark:border-[#d7e7dd30] dark:bg-[#1f2f2b]">
                    <h3 class="font-display text-2xl">{{ __('messages.podolog.edit_reservation_title') }}</h3>
                    <p class="mt-2 text-sm text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.podolog.edit_reservation_text') }}</p>

                    <form id="manageReservationForm" class="mt-5 grid gap-4 md:grid-cols-2">
                        <input type="hidden" id="manageEventId">
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm font-semibold">{{ __('messages.podolog.service') }}</label>
                            <input type="text" id="manageTitle" class="w-full rounded-lg border border-[#13302433] px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]" required>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold">{{ __('messages.podolog.date') }}</label>
                            <div class="mb-2 text-xs font-semibold uppercase tracking-[0.18em] text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.podolog.choose_date_quick') }}</div>
                            <div class="relative">
                                <select id="manageDate" class="w-full appearance-none rounded-xl border border-[#13302433] bg-white px-3 py-3 pr-10 text-sm font-semibold text-[#133024] shadow-sm transition hover:border-[#0e8f6f66] focus:border-[#0e8f6f] focus:outline-none dark:border-[#d7e7dd30] dark:bg-[#21332f] dark:text-[#e2f1e8]" required></select>
                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-[#446457] dark:text-[#b6cbc0]">&#9662;</span>
                            </div>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold">{{ __('messages.podolog.time') }}</label>
                            <select id="manageTime" class="w-full rounded-lg border border-[#13302433] px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]" required>
                                @for ($hour = 8; $hour <= 19; $hour++)
                                    <option value="{{ str_pad((string) $hour, 2, '0', STR_PAD_LEFT) }}:00">{{ str_pad((string) $hour, 2, '0', STR_PAD_LEFT) }}:00</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold">{{ __('messages.podolog.status') }}</label>
                            <select id="manageStatus" class="w-full rounded-lg border border-[#13302433] px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]" required>
                                @foreach (['booked', 'done', 'no_show', 'cancelled'] as $value)
                                    <option value="{{ $value }}">{{ __('messages.roles.' . $value) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold">{{ __('messages.calendar.time_slot') }}</label>
                            <input type="text" id="manageEndTimePreview" class="w-full rounded-lg border border-[#13302433] bg-gray-100 px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#283a36]" readonly>
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm font-semibold">{{ __('messages.podolog.notes') }}</label>
                            <textarea id="manageNotes" rows="4" class="w-full rounded-lg border border-[#13302433] px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]" placeholder="{{ __('messages.podolog.optional_note') }}"></textarea>
                        </div>
                        <div class="md:col-span-2 flex justify-end gap-3">
                            <button type="button" id="manageDelete" class="rounded-lg border border-rose-300 px-6 py-2.5 font-bold text-rose-700 transition hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-60 dark:border-rose-700 dark:text-rose-300 dark:hover:bg-rose-950/30">{{ __('messages.podolog.delete_reservation') }}</button>
                            <button type="submit" id="manageSubmit" data-default-text="{{ __('messages.podolog.save_changes') }}" class="rounded-lg bg-[#0e8f6f] px-6 py-2.5 font-bold text-white disabled:cursor-not-allowed disabled:opacity-60">{{ __('messages.podolog.save_changes') }}</button>
                        </div>
                    </form>
                </article>
            </div>
        @endif
    </section>
</div>

@if (empty($canManage))
<div id="modalOverlay" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/40">
    <div class="relative z-[10000] w-full max-w-md mx-auto rounded-2xl border border-[#13302426] bg-white p-8 shadow-2xl">
        <button type="button" id="closeModal" class="absolute right-4 top-4 text-2xl font-bold text-gray-500 hover:text-gray-900">&times;</button>
        <h3 class="font-display text-2xl mb-6 text-center">{{ __('messages.calendar.add_reservation') }}</h3>
        <form id="bookingForm" class="space-y-6">
            <input type="hidden" id="modalDate">
            <input type="hidden" id="modalTime">
            <div>
                <label class="mb-1 block text-sm font-semibold">{{ __('messages.calendar.patient_service_name') }}</label>
                <select id="modalProcedure" class="w-full rounded-lg border border-[#13302433] px-3 py-2" required>
                    <option value="" disabled selected>{{ __('messages.calendar.patient_placeholder') }}</option>
                    <option value="Pēdu aprūpe">Pēdu aprūpe</option>
                    <option value="Konsultācija">Konsultācija</option>
                    <option value="Masāža">Masāža</option>
                    <option value="Cits">Cits</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold">Datums</label>
                <input type="text" id="modalDatePreview" class="w-full rounded-lg border border-[#13302433] px-3 py-2 bg-gray-100" readonly>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold">{{ __('messages.calendar.time_slot') }}</label>
                <input type="text" id="modalTimePreview" class="w-full rounded-lg border border-[#13302433] px-3 py-2 bg-gray-100" readonly>
            </div>
            <div class="flex justify-end gap-2">
                <button type="submit" id="bookingSubmit" data-default-text="{{ __('messages.calendar.save') }}" class="rounded-lg bg-[#0e8f6f] px-6 py-2.5 font-bold text-white disabled:cursor-not-allowed disabled:opacity-60">{{ __('messages.calendar.save') }}</button>
                <button type="button" id="cancelModal" class="rounded-lg border border-[#13302426] bg-white px-6 py-2.5 font-bold text-[#133024]">{{ __('messages.calendar.close') }}</button>
            </div>
        </form>
        <div id="modalSuccess" class="hidden mt-6 text-green-700 font-bold text-center">{{ __('messages.calendar.reservation_created') }}</div>
        <div id="modalError" class="hidden mt-3 text-red-700 font-bold text-center"></div>
    </div>
</div>
@endif

<script>
const canManage = @json(!empty($canManage));
const appLocale = @json(app()->getLocale());
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
const fetchEventsUrl = "{{ route('calendar.events') }}";
const saveEventUrl = "{{ route('calendar.events.ajax') }}";
const patientShowUrlTemplate = "{{ route('patients.show', ['user' => '__USER__']) }}";

const messages = {
    fillFields: "{{ __('messages.calendar.fill_fields') }}",
    chooseSlot: "{{ __('messages.calendar.choose_slot') }}",
    reservationCreateFailed: "{{ __('messages.calendar.reservation_create_failed') }}",
    reservationCreated: "{{ __('messages.calendar.reservation_created') }}",
    savedShort: "{{ __('messages.calendar.saved_short') }}",
    slotRule: "{{ __('messages.calendar.slot_rule') }}",
    slotTaken: "{{ __('messages.calendar.slot_taken') }}",
    reservationUpdated: "{{ __('messages.calendar.reservation_updated') }}",
    reservationUpdateFailed: "{{ __('messages.calendar.reservation_update_failed') }}",
    reservationDeleted: "{{ __('messages.calendar.reservation_deleted') }}",
    reservationDeleteFailed: "{{ __('messages.calendar.reservation_delete_failed') }}",
    deleteConfirm: "{{ __('messages.calendar.delete_confirm') }}",
    selectReservation: "{{ __('messages.podolog.select_reservation') }}",
};

let weekOffset = 0;
let busySlots = new Set();
let eventsBySlot = new Map();
let eventsById = new Map();
let selectedSlot = null;
let isSaving = false;
let selectedManagedEventId = null;
let isManageSaving = false;
let isManageDeleting = false;

const localeMap = {
    lv: 'lv-LV',
    en: 'en-GB',
    ru: 'ru-RU',
};

const activeLocale = localeMap[appLocale] || 'en-GB';
const weekdayFormatter = new Intl.DateTimeFormat(activeLocale, { weekday: 'short' });
const dateLabelFormatter = new Intl.DateTimeFormat(activeLocale, {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
});
const compactDateFormatter = new Intl.DateTimeFormat(activeLocale, {
    day: 'numeric',
    month: 'short',
});
const rangeFormatter = new Intl.DateTimeFormat(activeLocale, {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
});
const monthTitleFormatter = new Intl.DateTimeFormat(activeLocale, {
    month: 'long',
    year: 'numeric',
});

function pad(value) {
    return String(value).padStart(2, '0');
}

function formatYmd(date) {
    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
}

function parseYmd(value) {
    const [year, month, day] = value.split('-').map(Number);
    return new Date(year, month - 1, day);
}

function formatCalendarDay(date) {
    return {
        weekday: weekdayFormatter.format(date),
        shortDate: compactDateFormatter.format(date),
        fullDate: dateLabelFormatter.format(date),
    };
}

function formatDateRange(start, end) {
    return `${rangeFormatter.format(start)} - ${rangeFormatter.format(end)}`;
}

function formatMonthTitle(start, end) {
    const startTitle = monthTitleFormatter.format(start);
    const endTitle = monthTitleFormatter.format(end);

    return startTitle === endTitle ? startTitle : `${startTitle} / ${endTitle}`;
}

function formatManageOption(value) {
    return dateLabelFormatter.format(parseYmd(value));
}

function buildManageDateOptions(selectedValue = '') {
    const select = document.getElementById('manageDate');
    if (!select) {
        return;
    }

    const { start } = startAndEndForWeek();
    const optionValues = [];

    for (let offset = -3; offset < 18; offset++) {
        const date = new Date(start);
        date.setDate(start.getDate() + offset);
        optionValues.push(formatYmd(date));
    }

    if (selectedValue && !optionValues.includes(selectedValue)) {
        optionValues.push(selectedValue);
        optionValues.sort();
    }

    const activeValue = selectedValue && optionValues.includes(selectedValue)
        ? selectedValue
        : optionValues[0] || '';

    select.innerHTML = optionValues
        .map(value => {
            const date = parseYmd(value);
            const day = formatCalendarDay(date);
            const isToday = value === formatYmd(new Date());
            const todaySuffix = isToday ? ` • {{ __('messages.calendar.today') }}` : '';
            return `<option value="${value}">${day.weekday} • ${formatManageOption(value)}${todaySuffix}</option>`;
        })
        .join('');

    select.value = activeValue;
}

function startAndEndForWeek() {
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const start = new Date(today);
    start.setDate(today.getDate() + weekOffset * 7);

    const end = new Date(start);
    end.setDate(start.getDate() + 6);

    return { start, end };
}

function setStatus(message, isError = false) {
    const status = document.getElementById('calendarStatus');
    if (!status) {
        return;
    }

    if (!message) {
        status.classList.add('hidden');
        status.textContent = '';
        status.className = 'mb-4 hidden rounded-lg px-3 py-2 text-sm';
        return;
    }

    status.textContent = message;
    status.className = isError
        ? 'mb-4 rounded-lg px-3 py-2 text-sm bg-red-100 text-red-800'
        : 'mb-4 rounded-lg px-3 py-2 text-sm bg-green-100 text-green-800';
}

let savedTimer = null;

function showSavedConfirmation(message) {
    const footer = document.getElementById('calendarSavedStatus');
    if (!footer) {
        return;
    }

    footer.textContent = message;
    footer.classList.remove('hidden');

    if (savedTimer) {
        clearTimeout(savedTimer);
    }

    savedTimer = setTimeout(() => {
        footer.classList.add('hidden');
    }, 3000);
}

async function loadBusySlots() {
    const { start, end } = startAndEndForWeek();
    const startDate = `${formatYmd(start)} 00:00:00`;
    const endDate = `${formatYmd(end)} 23:59:59`;

    const url = new URL(fetchEventsUrl, window.location.origin);
    url.searchParams.set('start', startDate);
    url.searchParams.set('end', endDate);

    const response = await fetch(url.toString(), {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
    });

    if (!response.ok) {
        throw new Error(messages.reservationCreateFailed);
    }

    const events = await response.json();
    eventsBySlot = new Map();
    eventsById = new Map();

    events.forEach(event => {
        if (!event?.slot_date || !event?.slot_time) {
            return;
        }

        const key = `${event.slot_date}|${event.slot_time}`;
        eventsBySlot.set(key, event);
        eventsById.set(String(event.id), event);
    });

    busySlots = new Set(eventsBySlot.keys());
}

function statusClass(status) {
    switch (status) {
        case 'done':
            return 'bg-slate-500 text-white hover:bg-slate-600';
        case 'no_show':
            return 'bg-amber-500 text-white hover:bg-amber-600';
        case 'cancelled':
            return 'bg-rose-500 text-white hover:bg-rose-600';
        default:
            return 'bg-[#0e8f6f] text-white hover:bg-[#0c7a5c]';
    }
}

function renderCalendar() {
    const calendarDays = document.getElementById('calendarDays');
    const calendarRange = document.getElementById('calendarRange');
    const calendarMonthTitle = document.getElementById('calendarMonthTitle');
    const { start, end } = startAndEndForWeek();

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    let html = '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">';

    for (let d = 0; d < 7; d++) {
        const date = new Date(start);
        date.setDate(start.getDate() + d);

        const ymd = formatYmd(date);
        const isPast = date < today;
        const isToday = ymd === formatYmd(today);
        const display = formatCalendarDay(date);

        html += `<div class="rounded-xl border p-2 ${isToday ? 'border-[#f2994a] shadow-[0_0_0_2px_rgba(242,153,74,0.25)]' : ''} ${isPast ? 'bg-gray-300 text-gray-400 pointer-events-none opacity-60' : 'bg-white'}">`;
        html += `<div class="mb-3 rounded-lg px-2 py-2 text-center ${isToday ? 'bg-[#fff4e8] dark:bg-[#4a3014]' : 'bg-[#f4faf7] dark:bg-[#20332d]'}">`;
        html += `<div class="text-xs font-semibold uppercase tracking-[0.2em] ${isPast ? 'text-gray-500' : 'text-[#446457] dark:text-[#b6cbc0]'}">${display.weekday}</div>`;
        html += `<div class="mt-1 font-bold ${isPast ? 'text-gray-600' : 'text-[#133024] dark:text-[#e2f1e8]'}">${display.shortDate}</div>`;
        if (isToday) {
            html += `<div class="mt-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-[#c86b14] dark:text-[#ffd08f]">{{ __('messages.calendar.today') }}</div>`;
        }
        html += `</div>`;

        for (let h = 8; h <= 19; h++) {
            const time = `${pad(h)}:00`;
            const slotKey = `${ymd}|${time}`;
            const eventData = eventsBySlot.get(slotKey);
            const isBusy = Boolean(eventData);

            if (canManage) {
                const disabled = isPast || !isBusy;
                html += `<button class="block w-full mb-1 rounded py-1 ${disabled ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : statusClass(eventData.status)}" ${disabled ? 'disabled' : ''} data-event-id="${eventData ? eventData.id : ''}" type="button">${time}</button>`;
                continue;
            }

            const disabled = isPast || isBusy;
            html += `<button class="block w-full mb-1 rounded py-1 ${disabled ? 'bg-gray-300 text-gray-400 cursor-not-allowed' : 'bg-[#0e8f6f] text-white hover:bg-[#0c7a5c]'}" ${disabled ? 'disabled' : ''} data-date="${ymd}" data-time="${time}" type="button">${time}</button>`;
        }

        html += '</div>';
    }

    html += '</div>';
    calendarDays.innerHTML = html;

    if (calendarMonthTitle) {
        calendarMonthTitle.textContent = formatMonthTitle(start, end);
    }

    if (calendarRange) {
        calendarRange.textContent = formatDateRange(start, end);
    }

    if (canManage) {
        calendarDays.querySelectorAll('button[data-event-id]:not([disabled])').forEach(btn => {
            btn.addEventListener('click', function () {
                selectManagedEvent(this.getAttribute('data-event-id'));
            });
        });
        return;
    }

    calendarDays.querySelectorAll('button:not([disabled])').forEach(btn => {
        btn.addEventListener('click', function () {
            openModal(this.getAttribute('data-date'), this.getAttribute('data-time'));
        });
    });
}

function openModal(date, time) {
    selectedSlot = { date, time };
    document.getElementById('modalOverlay').classList.remove('hidden');
    document.getElementById('modalDate').value = date;
    document.getElementById('modalTime').value = time;
    document.getElementById('modalDatePreview').value = formatManageOption(date);
    document.getElementById('modalTimePreview').value = `${time} - ${pad(Number(time.slice(0, 2)) + 1)}:00`;
    document.getElementById('modalProcedure').value = '';
    document.getElementById('modalSuccess').classList.add('hidden');
    document.getElementById('modalError').classList.add('hidden');
    document.getElementById('modalError').textContent = '';
}

function closeModal() {
    if (canManage) {
        return;
    }

    document.getElementById('modalOverlay').classList.add('hidden');
    selectedSlot = null;
    setSubmitState(false);
}

function setSubmitState(loading) {
    if (canManage) {
        return;
    }

    const submit = document.getElementById('bookingSubmit');
    if (!submit) {
        return;
    }

    const defaultText = submit.dataset.defaultText || submit.textContent;
    submit.disabled = loading;
    submit.textContent = loading ? 'Saglabā...' : defaultText;
}

function updateManageEndTimePreview() {
    const time = document.getElementById('manageTime')?.value;
    const preview = document.getElementById('manageEndTimePreview');
    if (!time || !preview) {
        return;
    }

    const endHour = Number(time.slice(0, 2)) + 1;
    preview.value = `${time} - ${pad(endHour)}:00`;
}

function setManageSubmitState(loading) {
    const submit = document.getElementById('manageSubmit');
    if (!submit) {
        return;
    }

    const defaultText = submit.dataset.defaultText || submit.textContent;
    submit.disabled = loading;
    submit.textContent = loading ? 'Saglabā...' : defaultText;
}

function setManageDeleteState(loading) {
    const button = document.getElementById('manageDelete');
    if (!button) {
        return;
    }

    button.disabled = loading;
}

function selectManagedEvent(eventId) {
    const eventData = eventsById.get(String(eventId));
    const panel = document.getElementById('managePanel');
    const emptyState = document.getElementById('manageEmptyState');

    if (!eventData || !panel || !emptyState) {
        return;
    }

    selectedManagedEventId = String(eventData.id);
    emptyState.classList.add('hidden');
    panel.classList.remove('hidden');

    const user = eventData.user || {};
    const fullName = eventData.patient_name || '';
    const [fallbackName, ...surnameParts] = fullName.split(' ');
    const patientCardLink = document.getElementById('patientCardLink');

    document.getElementById('detailName').value = user.name || fallbackName || '-';
    document.getElementById('detailSurname').value = user.surname || surnameParts.join(' ') || '-';
    document.getElementById('detailEmail').value = user.email || '-';
    document.getElementById('detailPhone').value = user.phone || eventData.patient_phone || '-';

    if (patientCardLink) {
        if (user.id) {
            patientCardLink.href = patientShowUrlTemplate.replace('__USER__', String(user.id));
            patientCardLink.classList.remove('hidden');
        } else {
            patientCardLink.href = '#';
            patientCardLink.classList.add('hidden');
        }
    }

    document.getElementById('manageEventId').value = eventData.id;
    document.getElementById('manageTitle').value = eventData.title || '';
    buildManageDateOptions(eventData.slot_date || '');
    document.getElementById('manageDate').value = eventData.slot_date || '';
    document.getElementById('manageTime').value = eventData.slot_time || '08:00';
    document.getElementById('manageStatus').value = eventData.status || 'booked';
    document.getElementById('manageNotes').value = eventData.notes || '';
    updateManageEndTimePreview();
}

async function updateManagedReservation(event) {
    event.preventDefault();

    if (isManageSaving) {
        return;
    }

    const eventId = document.getElementById('manageEventId').value;
    const title = document.getElementById('manageTitle').value.trim();
    const date = document.getElementById('manageDate').value;
    const time = document.getElementById('manageTime').value;
    const status = document.getElementById('manageStatus').value;
    const notes = document.getElementById('manageNotes').value.trim();

    if (!eventId || !title || !date || !time || !status) {
        setStatus(messages.reservationUpdateFailed, true);
        return;
    }

    const endTime = `${pad(Number(time.slice(0, 2)) + 1)}:00`;
    const payload = new URLSearchParams({
        type: 'update',
        id: eventId,
        title,
        start: `${date} ${time}:00`,
        end: `${date} ${endTime}:00`,
        status,
        notes,
    });

    isManageSaving = true;
    setManageSubmitState(true);
    setManageDeleteState(true);

    const response = await fetch(saveEventUrl, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: payload.toString(),
        credentials: 'same-origin',
    });

    const body = await response.json().catch(() => ({}));

    if (!response.ok) {
        setStatus(body.message || messages.reservationUpdateFailed, true);
        isManageSaving = false;
        setManageSubmitState(false);
        setManageDeleteState(false);
        return;
    }

    setStatus(messages.reservationUpdated);
    showSavedConfirmation(messages.savedShort);

    await loadBusySlots();
    renderCalendar();

    if (body.status === 'cancelled' || !eventsById.has(String(body.id))) {
        document.getElementById('managePanel')?.classList.add('hidden');
        document.getElementById('manageEmptyState')?.classList.remove('hidden');
        selectedManagedEventId = null;
    } else {
        selectManagedEvent(String(body.id));
    }

    isManageSaving = false;
    setManageSubmitState(false);
    setManageDeleteState(false);
}

async function deleteManagedReservation() {
    if (isManageDeleting || !selectedManagedEventId) {
        return;
    }

    if (!window.confirm(messages.deleteConfirm)) {
        return;
    }

    isManageDeleting = true;
    setManageSubmitState(true);
    setManageDeleteState(true);

    const payload = new URLSearchParams({
        type: 'delete',
        id: selectedManagedEventId,
    });

    const response = await fetch(saveEventUrl, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: payload.toString(),
        credentials: 'same-origin',
    });

    const body = await response.json().catch(() => ({}));

    if (!response.ok) {
        setStatus(body.message || messages.reservationDeleteFailed, true);
        isManageDeleting = false;
        setManageSubmitState(false);
        setManageDeleteState(false);
        return;
    }

    await loadBusySlots();
    renderCalendar();
    document.getElementById('managePanel')?.classList.add('hidden');
    document.getElementById('manageEmptyState')?.classList.remove('hidden');
    selectedManagedEventId = null;
    showSavedConfirmation(messages.reservationDeleted);
    setStatus(messages.reservationDeleted);
    isManageDeleting = false;
    setManageSubmitState(false);
    setManageDeleteState(false);
}

async function createReservation(event) {
    event.preventDefault();

    if (isSaving) {
        return;
    }

    const date = selectedSlot?.date || document.getElementById('modalDate').value;
    const time = selectedSlot?.time || document.getElementById('modalTime').value;
    const title = document.getElementById('modalProcedure').value;

    if (!date || !time) {
        showModalError(messages.chooseSlot);
        return;
    }

    if (!title) {
        showModalError(messages.fillFields);
        return;
    }

    const hour = Number(time.slice(0, 2));
    const endTime = `${pad(hour + 1)}:00`;

    const payload = new URLSearchParams({
        type: 'add',
        title,
        start: `${date} ${time}:00`,
        end: `${date} ${endTime}:00`,
    });

    isSaving = true;
    setSubmitState(true);

    const response = await fetch(saveEventUrl, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: payload.toString(),
        credentials: 'same-origin',
    });

    const body = await response.json().catch(() => ({}));

    if (!response.ok) {
        const backendMessage = body.message || messages.reservationCreateFailed;
        showModalError(backendMessage === messages.slotRule ? messages.reservationCreateFailed : backendMessage);
        isSaving = false;
        setSubmitState(false);
        return;
    }

    document.getElementById('modalSuccess').textContent = messages.reservationCreated;
    document.getElementById('modalSuccess').classList.remove('hidden');
    setStatus(messages.reservationCreated);
    showSavedConfirmation(messages.savedShort);

    await loadBusySlots();
    renderCalendar();
    isSaving = false;
    setSubmitState(false);
    setTimeout(closeModal, 900);
}

function showModalError(message) {
    const modalError = document.getElementById('modalError');
    modalError.textContent = message;
    modalError.classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', async function () {
    if (canManage) {
        document.getElementById('manageReservationForm')?.addEventListener('submit', updateManagedReservation);
        document.getElementById('manageTime')?.addEventListener('change', updateManageEndTimePreview);
        document.getElementById('manageDelete')?.addEventListener('click', deleteManagedReservation);
        buildManageDateOptions();
    } else {
        document.getElementById('closeModal').onclick = closeModal;
        document.getElementById('cancelModal').onclick = closeModal;
        document.getElementById('bookingForm').onsubmit = createReservation;
    }

    document.getElementById('prevWeek').onclick = async function () {
        weekOffset--;
        try {
            await loadBusySlots();
            renderCalendar();
            buildManageDateOptions();
            setStatus('');
            if (canManage) {
                document.getElementById('managePanel')?.classList.add('hidden');
                document.getElementById('manageEmptyState')?.classList.remove('hidden');
                selectedManagedEventId = null;
            }
        } catch (error) {
            setStatus(error.message, true);
        }
    };

    document.getElementById('nextWeek').onclick = async function () {
        weekOffset++;
        try {
            await loadBusySlots();
            renderCalendar();
            buildManageDateOptions();
            setStatus('');
            if (canManage) {
                document.getElementById('managePanel')?.classList.add('hidden');
                document.getElementById('manageEmptyState')?.classList.remove('hidden');
                selectedManagedEventId = null;
            }
        } catch (error) {
            setStatus(error.message, true);
        }
    };

    try {
        await loadBusySlots();
        renderCalendar();
    } catch (error) {
        setStatus(error.message, true);
    }
});
</script>
@endsection