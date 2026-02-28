<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('super-admin.partials.head')
</head>
<body>
    @php
        $midoneBase = $midoneBase ?? asset('midone-html.vercel.app');
    @endphp

    <!-- Preloader: shared with main super admin layout -->
    <div id="super-admin-preloader" class="fixed inset-0 z-[99998] flex items-center justify-center bg-white transition-opacity duration-300 ease-out" aria-hidden="true">
        <span class="h-10 w-10 text-slate-600" aria-label="Loading">
            <svg class="h-full w-full" width="40" height="40" viewBox="0 0 135 135" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                <path d="M67.447 58c5.523 0 10-4.477 10-10s-4.477-10-10-10-10 4.477-10 10 4.477 10 10 10zm9.448 9.447c0 5.523 4.477 10 10 10 5.522 0 10-4.477 10-10s-4.478-10-10-10c-5.523 0-10 4.477-10 10zm-9.448 9.448c-5.523 0-10 4.477-10 10 0 5.522 4.477 10 10 10s10-4.478 10-10c0-5.523-4.477-10-10-10zM58 67.447c0-5.523-4.477-10-10-10s-10 4.477-10 10 4.477 10 10 10 10-4.477 10-10z">
                    <animateTransform type="rotate" attributeName="transform" from="0 67 67" to="-360 67 67" dur="2.5s" repeatCount="indefinite"></animateTransform>
                </path>
                <path d="M28.19 40.31c6.627 0 12-5.374 12-12 0-6.628-5.373-12-12-12-6.628 0-12 5.372-12 12 0 6.626 5.372 12 12 12zm30.72-19.825c4.686 4.687 12.284 4.687 16.97 0 4.686-4.686 4.686-12.284 0-16.97-4.686-4.687-12.284-4.687-16.97 0-4.687 4.686-4.687 12.284 0 16.97zm35.74 7.705c0 6.627 5.37 12 12 12 6.626 0 12-5.373 12-12 0-6.628-5.374-12-12-12-6.63 0-12 5.372-12 12zm19.822 30.72c-4.686 4.686-4.686 12.284 0 16.97 4.687 4.686 12.285 4.686 16.97 0 4.687-4.686 4.687-12.284 0-16.97-4.685-4.687-12.283-4.687-16.97 0zm-7.704 35.74c-6.627 0-12 5.37-12 12 0 6.626 5.373 12 12 12s12-5.374 12-12c0-6.63-5.373-12-12-12zm-30.72 19.822c-4.686-4.686-12.284-4.686-16.97 0-4.686 4.687-4.686 12.285 0 16.97 4.686 4.687 12.284 4.687 16.97 0 4.687-4.685 4.687-12.283 0-16.97zm-35.74-7.704c0-6.627-5.372-12-12-12-6.626 0-12 5.373-12 12s5.374 12 12 12c6.628 0 12-5.373 12-12zm-19.823-30.72c4.687-4.686 4.687-12.284 0-16.97-4.686-4.686-12.284-4.686-16.97 0-4.687 4.686-4.687 12.284 0 16.97 4.686 4.687 12.284 4.687 16.97 0z">
                    <animateTransform type="rotate" attributeName="transform" from="0 67 67" to="360 67 67" dur="8s" repeatCount="indefinite"></animateTransform>
                </path>
            </svg>
        </span>
    </div>

    {{-- Fullscreen POS canvas (no sidebar, full-width content) --}}
    <div class="min-h-screen w-full bg-slate-100 dark:bg-darkmode-800 px-2 sm:px-4 py-2 sm:py-3">
        <div class="w-full">
            @yield('content')
        </div>
    </div>

    {{-- Modals overlay --}}
    <div style="position: fixed; inset: 0; z-index: 99999; pointer-events: none;" aria-hidden="true">
        @stack('modals')
    </div>

    @include('super-admin.partials.footer-scripts')
</body>
</html>

