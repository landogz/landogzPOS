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

    @include('super-admin.partials.footer-scripts')
</body>
</html>
