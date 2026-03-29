<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? __('messages.brand') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;700&family=Manrope:wght@400;500;700&family=Noto+Sans:wght@400;500;700&family=Noto+Serif:wght@500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')

    <script>
        (function () {
            const saved = localStorage.getItem('ffm-theme');
            const useDark = saved ? saved === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (useDark) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="min-h-screen bg-[radial-gradient(circle_at_10%_20%,rgba(242,153,74,0.20),transparent_34%),radial-gradient(circle_at_80%_8%,rgba(14,143,111,0.22),transparent_30%),linear-gradient(135deg,#f2f8ef,#d5ecf4)] font-sans text-[#133024] transition-colors dark:bg-[linear-gradient(135deg,#0f1716,#162220)] dark:text-[#d7e7dd]">
    @if (empty($hideNav))
    <nav class="sticky top-0 z-50 mx-auto mt-0 mb-4 w-[min(1080px,92vw)] flex flex-wrap items-center justify-between gap-4 rounded-xl border border-[#13302426] bg-white/85 px-4 py-3 backdrop-blur dark:border-[#d7e7dd30] dark:bg-[#16211ecc]">
            <h1 class="font-display text-xl tracking-wide">{{ __('messages.brand') }}</h1>
            <div class="flex flex-wrap items-center gap-2 text-sm font-bold">
                <a href="{{ url('/') }}" class="rounded-full border border-transparent px-4 py-2 transition hover:border-[#13302426] hover:bg-white dark:hover:border-[#d7e7dd30] dark:hover:bg-[#243530]">{{ __('messages.nav.home') }}</a>
                @auth
                    <a href="{{ url('/calendar') }}" class="rounded-full border border-transparent px-4 py-2 transition hover:border-[#13302426] hover:bg-white dark:hover:border-[#d7e7dd30] dark:hover:bg-[#243530]">{{ __('messages.nav.calendar') }}</a>
                    <a href="{{ url('/info') }}" class="rounded-full border border-transparent px-4 py-2 transition hover:border-[#13302426] hover:bg-white dark:hover:border-[#d7e7dd30] dark:hover:bg-[#243530]">{{ __('messages.nav.info') }}</a>
                    <a href="{{ route('profile.card') }}" class="rounded-full border border-transparent px-4 py-2 transition hover:border-[#13302426] hover:bg-white dark:hover:border-[#d7e7dd30] dark:hover:bg-[#243530]">{{ __('messages.nav.my_card') }}</a>

                    @if (auth()->user()->role === 'podolog' || auth()->user()->role === 'admin')
                        <a href="{{ route('podolog.dashboard') }}" class="rounded-full border border-transparent px-4 py-2 transition hover:border-[#13302426] hover:bg-white dark:hover:border-[#d7e7dd30] dark:hover:bg-[#243530]">{{ __('messages.nav.podolog') }}</a>
                    @endif

                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="rounded-full border border-transparent px-4 py-2 transition hover:border-[#13302426] hover:bg-white dark:hover:border-[#d7e7dd30] dark:hover:bg-[#243530]">{{ __('messages.nav.admin') }}</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="inline-block">
                        @csrf
                        <button type="submit" class="rounded-full border border-[#13302426] bg-white px-4 py-2 dark:border-[#d7e7dd30] dark:bg-[#22302c]">{{ __('messages.nav.logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-full border border-transparent px-4 py-2 transition hover:border-[#13302426] hover:bg-white dark:hover:border-[#d7e7dd30] dark:hover:bg-[#243530]">{{ __('messages.nav.login') }}</a>
                    <a href="{{ route('register') }}" class="rounded-full border border-[#13302426] bg-white px-4 py-2 transition hover:bg-[#f8fffc] dark:border-[#d7e7dd30] dark:bg-[#22302c] dark:hover:bg-[#2c3e38]">{{ __('messages.nav.register') }}</a>
                @endauth

                <button type="button" id="themeToggle" class="rounded-full border border-[#13302426] bg-white px-3 py-2 text-xs dark:border-[#d7e7dd30] dark:bg-[#22302c]"></button>

                <div class="flex items-center gap-1 rounded-full border border-[#13302426] bg-white px-2 py-1 text-xs dark:border-[#d7e7dd30] dark:bg-[#22302c]">
                    {{-- Valodas izvēle, tikai LV un EN --}}
                    <span>{{ __('messages.nav.language') }}:</span>
                    @foreach (['lv' => 'LV', 'en' => 'EN'] as $code => $label)
                        <a href="{{ route('locale.switch', $code) }}"
                           class="rounded px-1.5 py-0.5 {{ app()->getLocale() === $code ? 'bg-[#0e8f6f] text-white' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </nav>
    @endif

        @if (session('status'))
            <div class="mt-4 rounded-lg border border-[#0e8f6f55] bg-[#e8f7ef] px-4 py-3 text-sm font-medium text-[#0e8f6f] dark:bg-[#1f3a32] dark:text-[#9ae6cc]">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 rounded-lg border border-[#c43f2b55] bg-[#fff2ef] px-4 py-3 text-sm text-[#7a2516] dark:bg-[#3c2220] dark:text-[#ffb4a7]">
                {{ $errors->first() }}
            </div>
        @endif

    <main class="mx-auto w-[min(1080px,92vw)] py-7 pb-12 mt-0">
        @yield('content')
    </main>

    @stack('scripts')
    <script>
        (function () {
            const button = document.getElementById('themeToggle');
            if (!button) return;

            function renderLabel() {
                const isDark = document.documentElement.classList.contains('dark');
                button.textContent = `{{ __('messages.nav.theme') }}: ${isDark ? "{{ __('messages.nav.dark') }}" : "{{ __('messages.nav.light') }}"}`;
            }

            button.addEventListener('click', function () {
                const html = document.documentElement;
                const isDark = html.classList.toggle('dark');
                localStorage.setItem('ffm-theme', isDark ? 'dark' : 'light');
                renderLabel();
            });

            renderLabel();
        })();
    </script>
</body>
</html>
