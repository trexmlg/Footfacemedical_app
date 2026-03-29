@extends('layouts.app')

@php
    $title = __('messages.brand') . ' | ' . __('messages.nav.home');
@endphp

@section('content')
@php
    $slides = ($promotions ?? collect())->isNotEmpty()
        ? $promotions
        : collect(__('messages.home.fallback_slides'));

    $backgrounds = [
        'bg-[linear-gradient(135deg,#e8f7ef,#d7edf7)]',
        'bg-[linear-gradient(135deg,#fef3db,#fce6c9)]',
        'bg-[linear-gradient(135deg,#e9f4ff,#dbe6ff)]',
    ];
@endphp

<section class="mt-5 grid gap-4 lg:grid-cols-[1.2fr_1fr]">
    <article class="rounded-2xl border border-[#13302426] bg-white/88 p-6 shadow-[0_12px_35px_rgba(19,48,36,0.16)] dark:border-[#d7e7dd30] dark:bg-[#1d2c29e0]">
        <p class="text-sm font-bold tracking-[0.08em] text-[#0e8f6f] uppercase">{{ __('messages.home.welcome') }}</p>
        <h2 class="font-display mt-2 text-4xl leading-tight md:text-5xl">{{ __('messages.home.hero_title') }}</h2>
        <p class="mt-3 max-w-2xl leading-7 text-[#3e5d4f] dark:text-[#b6cbc0]">{{ __('messages.home.hero_text') }}</p>

        <ul class="mt-6 grid gap-3">
            <li class="flex items-center gap-3 rounded-xl border border-[#13302426] bg-white px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]">
                <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-[#f2994a] ring-4 ring-[#f2994a33]"></span>
                {{ __('messages.home.point_1') }}
            </li>
            <li class="flex items-center gap-3 rounded-xl border border-[#13302426] bg-white px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]">
                <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-[#f2994a] ring-4 ring-[#f2994a33]"></span>
                {{ __('messages.home.point_2') }}
            </li>
            <li class="flex items-center gap-3 rounded-xl border border-[#13302426] bg-white px-3 py-2 dark:border-[#d7e7dd30] dark:bg-[#21332f]">
                <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-[#f2994a] ring-4 ring-[#f2994a33]"></span>
                {{ __('messages.home.point_3') }}
            </li>
        </ul>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ url('/calendar') }}" class="rounded-lg border border-[#0e8f6f] bg-[#0e8f6f] px-4 py-2.5 text-sm font-bold text-white">{{ __('messages.home.reserve') }}</a>
            <a href="{{ url('/info') }}" class="rounded-lg border border-[#13302426] bg-white px-4 py-2.5 text-sm font-bold text-[#133024] dark:border-[#d7e7dd30] dark:bg-[#21332f] dark:text-[#d7e7dd]">{{ __('messages.home.read_treatments') }}</a>
        </div>
    </article>

    <aside class="rounded-2xl border border-[#13302426] bg-white/88 p-5 shadow-[0_12px_35px_rgba(19,48,36,0.16)] dark:border-[#d7e7dd30] dark:bg-[#1d2c29e0]">
        <div id="promoCarousel" class="relative overflow-hidden rounded-2xl border border-[#13302426] bg-white dark:border-[#d7e7dd30] dark:bg-[#21332f]">
            <div class="slides flex h-full w-full">
                @foreach ($slides as $idx => $slide)
                    <article class="slide min-w-full p-6 pb-14 {{ $backgrounds[$idx % count($backgrounds)] }}">
                        <small class="inline-block rounded-full bg-[#0e8f6f] px-3 py-1 text-xs font-bold tracking-wide text-white">{{ data_get($slide, 'badge') ?: __('messages.home.promo_default') }}</small>
                        <h3 class="font-display mt-3 text-3xl">{{ data_get($slide, 'title') }}</h3>
                        <p class="mt-3 leading-7 text-[#355b4f]">{{ data_get($slide, 'description') }}</p>
                    </article>
                @endforeach
            </div>

            <div class="pointer-events-none absolute inset-x-3 top-1/2 flex -translate-y-1/2 justify-between">
                <button type="button" class="prev pointer-events-auto grid h-9 w-9 place-items-center rounded-full bg-[#103328c7] text-white" aria-label="{{ __('messages.home.promo_aria_prev') }}">&#10094;</button>
                <button type="button" class="next pointer-events-auto grid h-9 w-9 place-items-center rounded-full bg-[#103328c7] text-white" aria-label="{{ __('messages.home.promo_aria_next') }}">&#10095;</button>
            </div>

            <div class="absolute bottom-3 left-1/2 flex -translate-x-1/2 gap-2">
                @foreach ($slides as $idx => $slide)
                    <button type="button" class="indicator {{ $idx === 0 ? 'active' : '' }} h-2.5 w-2.5 rounded-full bg-[#1033284d]" aria-label="{{ __('messages.home.promo_aria_go', ['number' => $idx + 1]) }}"></button>
                @endforeach
            </div>
        </div>
    </aside>
</section>
@endsection

@push('head')
<style>
    .slides { transition: transform 450ms ease; }
    .indicator.active { background: #0e8f6f; }
</style>
@endpush

@push('scripts')
<script>
    (function () {
        const carousel = document.getElementById('promoCarousel');
        if (!carousel) return;

        const slides = carousel.querySelector('.slides');
        const total = carousel.querySelectorAll('.slide').length;
        const indicators = Array.from(carousel.querySelectorAll('.indicator'));
        const prevButton = carousel.querySelector('.prev');
        const nextButton = carousel.querySelector('.next');

        let index = 0;
        let autoRotateId;

        function renderSlide(nextIndex) {
            index = (nextIndex + total) % total;
            slides.style.transform = `translateX(-${index * 100}%)`;
            indicators.forEach((dot, dotIndex) => {
                dot.classList.toggle('active', dotIndex === index);
            });
        }

        function startAutoRotate() {
            autoRotateId = window.setInterval(function () {
                renderSlide(index + 1);
            }, 4200);
        }

        function stopAutoRotate() {
            window.clearInterval(autoRotateId);
        }

        prevButton.addEventListener('click', function () {
            renderSlide(index - 1);
        });

        nextButton.addEventListener('click', function () {
            renderSlide(index + 1);
        });

        indicators.forEach(function (dot, dotIndex) {
            dot.addEventListener('click', function () {
                renderSlide(dotIndex);
            });
        });

        carousel.addEventListener('mouseenter', stopAutoRotate);
        carousel.addEventListener('mouseleave', startAutoRotate);

        renderSlide(0);
        startAutoRotate();
    })();
</script>
@endpush
