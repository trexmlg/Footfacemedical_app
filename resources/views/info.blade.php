@extends('layouts.app')

@php
    $title = __('messages.brand') . ' | ' . __('messages.nav.info');
@endphp

@section('content')
<section class="mt-5 rounded-2xl border border-[#16342924] bg-[linear-gradient(135deg,#e4f6ef,#dcecf9)] p-6 shadow-[0_12px_30px_rgba(22,52,41,0.12)] dark:border-[#d7e7dd30] dark:bg-[linear-gradient(135deg,#1a2b28,#1f2f3d)]">
    <h2 class="font-display text-4xl leading-tight md:text-5xl">{{ __('messages.info.title') }}</h2>
    <p class="mt-3 max-w-3xl leading-7 text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.info.lead') }}</p>
</section>

<section class="mt-4 grid gap-3 md:grid-cols-3" aria-label="{{ __('messages.info.cards_aria') }}">
    <article class="rounded-xl border border-[#16342924] bg-white p-4 dark:border-[#d7e7dd30] dark:bg-[#21332f]">
        <span class="inline-block rounded-full bg-[#0b8c74] px-2.5 py-1 text-xs font-bold tracking-wide text-white">{{ __('messages.info.feet_badge') }}</span>
        <h3 class="mt-3 text-lg font-bold">{{ __('messages.info.feet_title') }}</h3>
        <p class="mt-2 text-sm leading-6 text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.info.feet_text') }}</p>
    </article>
    <article class="rounded-xl border border-[#16342924] bg-white p-4 dark:border-[#d7e7dd30] dark:bg-[#21332f]">
        <span class="inline-block rounded-full bg-[#0b8c74] px-2.5 py-1 text-xs font-bold tracking-wide text-white">{{ __('messages.info.face_badge') }}</span>
        <h3 class="mt-3 text-lg font-bold">{{ __('messages.info.face_title') }}</h3>
        <p class="mt-2 text-sm leading-6 text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.info.face_text') }}</p>
    </article>
    <article class="rounded-xl border border-[#16342924] bg-white p-4 dark:border-[#d7e7dd30] dark:bg-[#21332f]">
        <span class="inline-block rounded-full bg-[#0b8c74] px-2.5 py-1 text-xs font-bold tracking-wide text-white">{{ __('messages.info.follow_badge') }}</span>
        <h3 class="mt-3 text-lg font-bold">{{ __('messages.info.follow_title') }}</h3>
        <p class="mt-2 text-sm leading-6 text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.info.follow_text') }}</p>
    </article>
</section>

<section class="mt-4 rounded-2xl border border-[#16342924] bg-white p-5 dark:border-[#d7e7dd30] dark:bg-[#1d2c29e0]" aria-label="{{ __('messages.info.process_aria') }}">
    <h3 class="font-display text-3xl">{{ __('messages.info.how_title') }}</h3>
    <div class="mt-3 grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-[#16342924] bg-[#fbfffd] p-3 dark:border-[#d7e7dd30] dark:bg-[#21332f]"><strong class="mb-1 inline-block text-[#ee8d45]">{{ __('messages.info.step1') }}</strong><div class="text-sm leading-6 text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.info.step1_text') }}</div></div>
        <div class="rounded-xl border border-[#16342924] bg-[#fbfffd] p-3 dark:border-[#d7e7dd30] dark:bg-[#21332f]"><strong class="mb-1 inline-block text-[#ee8d45]">{{ __('messages.info.step2') }}</strong><div class="text-sm leading-6 text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.info.step2_text') }}</div></div>
        <div class="rounded-xl border border-[#16342924] bg-[#fbfffd] p-3 dark:border-[#d7e7dd30] dark:bg-[#21332f]"><strong class="mb-1 inline-block text-[#ee8d45]">{{ __('messages.info.step3') }}</strong><div class="text-sm leading-6 text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.info.step3_text') }}</div></div>
        <div class="rounded-xl border border-[#16342924] bg-[#fbfffd] p-3 dark:border-[#d7e7dd30] dark:bg-[#21332f]"><strong class="mb-1 inline-block text-[#ee8d45]">{{ __('messages.info.step4') }}</strong><div class="text-sm leading-6 text-[#446457] dark:text-[#b6cbc0]">{{ __('messages.info.step4_text') }}</div></div>
    </div>
</section>

<div class="mt-4 flex flex-wrap gap-3">
    <a href="{{ url('/calendar') }}" class="rounded-lg border border-[#0b8c74] bg-[#0b8c74] px-4 py-2.5 text-sm font-bold text-white">{{ __('messages.info.book') }}</a>
    <a href="{{ url('/') }}" class="rounded-lg border border-[#16342924] bg-white px-4 py-2.5 text-sm font-bold text-[#163429] dark:border-[#d7e7dd30] dark:bg-[#21332f] dark:text-[#d7e7dd]">{{ __('messages.info.back') }}</a>
</div>
@endsection
