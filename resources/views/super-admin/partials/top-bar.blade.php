@php
    $base = $midoneBase ?? asset('midone-html.vercel.app');
    $profileUser = auth()->user();
    if ($profileUser) {
        $profileUser->loadMissing('branch');
    }
    $profileName = $profileUser?->name ?? 'Guest';
    $profileRole = $profileUser?->role ? ucfirst(str_replace('_', ' ', $profileUser->role)) : 'Not logged in';
    $profileBranch = $profileUser?->branch?->name;
    $profileSubtitle = $profileBranch ? $profileRole . ' • ' . $profileBranch : $profileRole;
@endphp
<!-- BEGIN: Top Bar -->
<div class="relative z-[51] flex h-[67px] items-center justify-between border-b border-slate-200 dark:border-darkmode-400 gap-4">
    <!-- BEGIN: Breadcrumb -->
    <nav aria-label="breadcrumb" class="flex -intro-x mr-auto hidden sm:flex min-w-0">
        <ol class="flex items-center text-theme-1 dark:text-slate-300">
            <li class="">
                <a href="{{ route('dashboard.dashboard') }}">Application</a>
            </li>
            <li class="relative ml-5 pl-0.5 before:content-[''] before:w-[14px] before:h-[14px] before:bg-chevron-black before:transform before:rotate-[-90deg] before:bg-[length:100%] before:-ml-[1.125rem] before:absolute before:my-auto before:inset-y-0 dark:before:bg-chevron-white text-slate-800 dark:text-slate-400">
                <span>@yield('breadcrumb', 'Dashboard')</span>
            </li>
        </ol>
    </nav>
    <!-- END: Breadcrumb -->
    <!-- Right: role badge + notification bell + profile -->
    <div class="flex items-center shrink-0 gap-2 sm:gap-3 min-w-0">
        <span id="super-admin-role-badge" class="hidden sm:inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary border border-primary/20">{{ $profileRole }}</span>
        <a href="{{ route('dashboard.dashboard') }}#dashboard-low-stock-list" class="relative inline-flex items-center gap-1.5 shrink-0 h-10 px-3 rounded-xl border-2 border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-darkmode-600 transition-colors" aria-label="Alerts" title="View alerts (low stock &amp; expiring)">
            <span class="flex h-8 w-8 items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
            </span>
            <span class="hidden sm:inline text-sm font-medium">Alerts</span>
            <span id="notification-badge" class="absolute top-0 right-0 flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-rose-500 px-1.5 text-[11px] font-bold text-white hidden">0</span>
        </a>
        <!-- BEGIN: Profile dropdown -->
    <div data-tw-merge="" data-tw-placement="bottom-end" class="dropdown relative">
        <button data-tw-toggle="dropdown" aria-expanded="false" class="cursor-pointer image-fit zoom-in intro-x block h-8 w-8 scale-110 overflow-hidden rounded-full shadow-lg">
            <img src="{{ $base }}/dist/images/fakers/profile-4.jpg" alt="Profile">
        </button>
        <div data-transition="" data-selector=".show" data-enter="transition-all ease-linear duration-150" data-enter-from="absolute !mt-5 invisible opacity-0 translate-y-1" data-enter-to="!mt-1 visible opacity-100 translate-y-0" data-leave="transition-all ease-linear duration-150" data-leave-from="!mt-1 visible opacity-100 translate-y-0" data-leave-to="absolute !mt-5 invisible opacity-0 translate-y-1" class="dropdown-menu absolute z-[9999] hidden">
            <div data-tw-merge="" class="dropdown-content rounded-md border-transparent p-2 shadow-[0px_3px_10px_#00000017] dark:border-transparent dark:bg-darkmode-600 relative mt-px w-56 bg-theme-1 text-white">
                <div class="p-2 font-medium font-normal">
                    <div class="font-medium" id="super-admin-profile-name">{{ $profileName }}</div>
                    <div class="mt-0.5 text-xs text-white/70 dark:text-slate-500" id="super-admin-profile-subtitle">{{ $profileSubtitle }}</div>
                </div>
                <div class="h-px my-2 -mx-2 bg-white/[0.08]"></div>
                <a class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-white/5 dropdown-item" href="javascript:;"><i data-tw-merge="" data-lucide="user" class="stroke-1.5 mr-2 h-4 w-4"></i> Profile</a>
                <a class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-white/5 dropdown-item" href="javascript:;" id="super-admin-logout"><i data-tw-merge="" data-lucide="toggle-right" class="stroke-1.5 mr-2 h-4 w-4"></i> Logout</a>
            </div>
        </div>
    </div>
    </div>
</div>
<!-- END: Top Bar -->
