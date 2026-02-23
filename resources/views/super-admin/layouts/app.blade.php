<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('super-admin.partials.head')
</head>
<body>
    @php
        $midoneBase = $midoneBase ?? asset('midone-html.vercel.app');
    @endphp
    @php
        $currentRoute = \Illuminate\Support\Facades\Route::currentRouteName();
        $menuRole = \App\Services\SuperAdminMenuService::currentRole();
    @endphp
    <!-- Preloader: shown until page fully loaded -->
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

    <div class="rubick px-5 sm:px-8 py-5 before:content-[''] before:bg-gradient-to-b before:from-theme-1 before:to-theme-2 dark:before:from-darkmode-800 dark:before:to-darkmode-800 before:fixed before:inset-0 before:z-[-1]">
        @include('super-admin.partials.mobile-menu')
        <div class="mt-[4.7rem] flex md:mt-0 overflow-visible">
            @include('super-admin.partials.side-menu')
            <script>(function(){if(localStorage.getItem('super_admin_token')){var el=document.querySelectorAll('[data-menu-permission]');for(var i=0;i<el.length;i++)el[i].style.display='none';}})();</script>
            <!-- BEGIN: Content -->
            <div class="relative z-[1] md:max-w-auto min-h-screen min-w-0 max-w-full flex-1 rounded-[30px] bg-slate-100 px-4 pb-10 before:block before:h-px before:w-full before:content-[''] dark:bg-darkmode-700 md:px-[22px]">
                @include('super-admin.partials.top-bar')
                @yield('content')
            </div>
            <!-- END: Content -->
        </div>
    </div>

    {{-- Modals: wrapper ensures they always stack in front of layout (sidebar + content) --}}
    <div style="position: fixed; inset: 0; z-index: 99999; pointer-events: none;" aria-hidden="true">
        {{-- Modals use pointer-events: auto so they receive clicks despite wrapper --}}
        @stack('modals')
    </div>

    @include('super-admin.partials.footer-scripts')
</body>
</html>
