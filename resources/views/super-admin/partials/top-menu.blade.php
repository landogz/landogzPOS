<!-- BEGIN: Top Menu -->
<nav class="top-nav relative z-50 -mt-4 hidden pt-32 opacity-0 md:block">
    <ul class="flex flex-wrap px-6 xl:px-[50px]">
        <li>
            <a href="{{ route('dashboard.dashboard') }}" class="top-menu {{ request()->routeIs('dashboard.dashboard') ? 'top-menu--active' : '' }}">
                <div class="top-menu__icon">
                    <i data-tw-merge="" data-lucide="home" class="stroke-1.5 w-5 h-5"></i>
                </div>
                <div class="top-menu__title">Dashboard</div>
            </a>
        </li>
    </ul>
</nav>
<!-- END: Top Menu -->
