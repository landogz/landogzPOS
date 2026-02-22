@php
    use App\Services\SuperAdminMenuService;
    $base = $midoneBase ?? asset('midone-html.vercel.app');
    $current = $currentRoute ?? \Illuminate\Support\Facades\Route::currentRouteName();
    $reportsOpen = str_starts_with($current ?? '', 'dashboard.reports.');
    $productsOpen = ($current === 'dashboard.products' || $current === 'dashboard.categories');
    $can = fn(string $p) => SuperAdminMenuService::canAccess($menuRole ?? null, $p);
@endphp
<!-- BEGIN: Mobile Menu -->
<div class="mobile-menu group top-0 inset-x-0 fixed bg-theme-1/90 z-[60] border-b border-white/[0.08] dark:bg-darkmode-800/90 md:hidden before:content-[''] before:w-full before:h-screen before:z-10 before:fixed before:inset-x-0 before:bg-black/90 before:transition-opacity before:duration-200 before:ease-in-out before:invisible before:opacity-0 [&.mobile-menu--active]:before:visible [&.mobile-menu--active]:before:opacity-100">
    <div class="flex h-[70px] items-center px-3 sm:px-8">
        <a class="mr-auto flex" href="{{ route('dashboard.dashboard') }}">
            <img class="w-6" src="{{ asset('images/logo.png') }}" alt="Landogz POS">
            <span class="ml-3 text-lg text-white">Super Admin</span>
        </a>
        <a class="mobile-menu-toggler" href="javascript:;">
            <i data-lucide="bar-chart2" class="stroke-1.5 h-8 w-8 -rotate-90 transform text-white"></i>
        </a>
    </div>
    <div class="scrollable h-screen z-20 top-0 left-0 w-[270px] -ml-[100%] bg-primary transition-all duration-300 ease-in-out dark:bg-darkmode-800 [&[data-simplebar]]:fixed [&_.simplebar-scrollbar]:before:bg-black/50 group-[.mobile-menu--active]:ml-0">
        <a href="javascript:;" class="fixed top-0 right-0 mt-4 mr-4 transition-opacity duration-200 ease-in-out invisible opacity-0 group-[.mobile-menu--active]:visible group-[.mobile-menu--active]:opacity-100 mobile-menu-toggler">
            <i data-lucide="x-circle" class="stroke-1.5 h-8 w-8 -rotate-90 transform text-white"></i>
        </a>
        <ul class="py-2">
            <li data-menu-permission="dashboard">
                <a class="menu {{ $current === 'dashboard.dashboard' ? 'menu--active' : '' }}" href="{{ route('dashboard.dashboard') }}">
                    <div class="menu__icon"><i data-lucide="layout-dashboard" class="stroke-1.5 w-5 h-5"></i></div>
                    <div class="menu__title">Dashboard</div>
                </a>
            </li>
            <li data-menu-permission="dashboard">
                <a class="menu {{ $current === 'dashboard.chain' ? 'menu--active' : '' }}" href="{{ route('dashboard.chain') }}">
                    <div class="menu__icon"><i data-lucide="git-branch" class="stroke-1.5 w-5 h-5"></i></div>
                    <div class="menu__title">Chain</div>
                </a>
            </li>
            <li data-menu-permission="companies">
                <a class="menu {{ $current === 'dashboard.companies' ? 'menu--active' : '' }}" href="{{ route('dashboard.companies') }}">
                    <div class="menu__icon"><i data-lucide="building" class="stroke-1.5 w-5 h-5"></i></div>
                    <div class="menu__title">Companies</div>
                </a>
            </li>
            <li data-menu-permission="users">
                <a class="menu {{ $current === 'dashboard.users' ? 'menu--active' : '' }}" href="{{ route('dashboard.users') }}">
                    <div class="menu__icon"><i data-lucide="users" class="stroke-1.5 w-5 h-5"></i></div>
                    <div class="menu__title">Users</div>
                </a>
            </li>
            <li data-menu-permission="suppliers">
                <a class="menu {{ $current === 'dashboard.suppliers' ? 'menu--active' : '' }}" href="{{ route('dashboard.suppliers') }}">
                    <div class="menu__icon"><i data-lucide="truck" class="stroke-1.5 w-5 h-5"></i></div>
                    <div class="menu__title">Suppliers</div>
                </a>
            </li>
            <li data-menu-permission="products">
                <a class="menu" href="javascript:;">
                    <div class="menu__icon"><i data-lucide="package" class="stroke-1.5 w-5 h-5"></i></div>
                    <div class="menu__title">
                        Products
                        <div class="menu__sub-icon {{ $productsOpen ? 'transform rotate-180' : '' }}"><i data-lucide="chevron-down" class="stroke-1.5 w-5 h-5"></i></div>
                    </div>
                </a>
                <ul class="{{ $productsOpen ? 'menu__sub-open' : '' }}" style="{{ $productsOpen ? '' : 'display: none;' }}">
                    <li><a class="menu {{ $current === 'dashboard.products' ? 'menu--active' : '' }}" href="{{ route('dashboard.products') }}"><div class="menu__icon"><i data-lucide="package" class="stroke-1.5 w-5 h-5"></i></div><div class="menu__title">Products</div></a></li>
                    <li><a class="menu {{ $current === 'dashboard.categories' ? 'menu--active' : '' }}" href="{{ route('dashboard.categories') }}"><div class="menu__icon"><i data-lucide="folder" class="stroke-1.5 w-5 h-5"></i></div><div class="menu__title">Categories</div></a></li>
                </ul>
            </li>
            <li data-menu-permission="transactions">
                <a class="menu {{ $current === 'dashboard.transactions' ? 'menu--active' : '' }}" href="{{ route('dashboard.transactions') }}">
                    <div class="menu__icon"><i data-lucide="receipt" class="stroke-1.5 w-5 h-5"></i></div>
                    <div class="menu__title">Transactions</div>
                </a>
            </li>
            <li data-menu-permission="inventory">
                <a class="menu {{ $current === 'dashboard.inventory' ? 'menu--active' : '' }}" href="{{ route('dashboard.inventory') }}">
                    <div class="menu__icon"><i data-lucide="boxes" class="stroke-1.5 w-5 h-5"></i></div>
                    <div class="menu__title">Inventory</div>
                </a>
            </li>
            <li data-menu-permission="reports">
                <a class="menu" href="javascript:;">
                    <div class="menu__icon"><i data-lucide="file-bar-chart" class="stroke-1.5 w-5 h-5"></i></div>
                    <div class="menu__title">
                        Reports
                        <div class="menu__sub-icon {{ $reportsOpen ? 'transform rotate-180' : '' }}"><i data-lucide="chevron-down" class="stroke-1.5 w-5 h-5"></i></div>
                    </div>
                </a>
                <ul class="{{ $reportsOpen ? 'menu__sub-open' : '' }}" style="{{ $reportsOpen ? '' : 'display: none;' }}">
                    <li><a class="menu {{ $current === 'dashboard.reports.z-reading' ? 'menu--active' : '' }}" href="{{ route('dashboard.reports.z-reading') }}"><div class="menu__icon"><i data-lucide="file-text" class="stroke-1.5 w-5 h-5"></i></div><div class="menu__title">Z-Reading (End of Day)</div></a></li>
                    <li><a class="menu {{ $current === 'dashboard.reports.x-reading' ? 'menu--active' : '' }}" href="{{ route('dashboard.reports.x-reading') }}"><div class="menu__icon"><i data-lucide="file-text" class="stroke-1.5 w-5 h-5"></i></div><div class="menu__title">X-Reading (Interim)</div></a></li>
                    <li><a class="menu {{ $current === 'dashboard.reports.sales' ? 'menu--active' : '' }}" href="{{ route('dashboard.reports.sales') }}"><div class="menu__icon"><i data-lucide="activity" class="stroke-1.5 w-5 h-5"></i></div><div class="menu__title">Sales</div></a></li>
                    <li><a class="menu {{ $current === 'dashboard.reports.vat-relief' ? 'menu--active' : '' }}" href="{{ route('dashboard.reports.vat-relief') }}"><div class="menu__icon"><i data-lucide="file-check" class="stroke-1.5 w-5 h-5"></i></div><div class="menu__title">VAT Relief / Relief Data</div></a></li>
                    <li><a class="menu {{ $current === 'dashboard.reports.alphalist-payees' ? 'menu--active' : '' }}" href="{{ route('dashboard.reports.alphalist-payees') }}"><div class="menu__icon"><i data-lucide="users" class="stroke-1.5 w-5 h-5"></i></div><div class="menu__title">Alphalist of Payees (MAP)</div></a></li>
                    <li><a class="menu {{ $current === 'dashboard.reports.audit-trail' ? 'menu--active' : '' }}" href="{{ route('dashboard.reports.audit-trail') }}"><div class="menu__icon"><i data-lucide="clipboard-list" class="stroke-1.5 w-5 h-5"></i></div><div class="menu__title">Audit Trail</div></a></li>
                    <li><a class="menu {{ $current === 'dashboard.reports.inventory' ? 'menu--active' : '' }}" href="{{ route('dashboard.reports.inventory') }}"><div class="menu__icon"><i data-lucide="activity" class="stroke-1.5 w-5 h-5"></i></div><div class="menu__title">Inventory</div></a></li>
                    <li><a class="menu {{ $current === 'dashboard.reports.profit-margin' ? 'menu--active' : '' }}" href="{{ route('dashboard.reports.profit-margin') }}"><div class="menu__icon"><i data-lucide="activity" class="stroke-1.5 w-5 h-5"></i></div><div class="menu__title">Profit Margin</div></a></li>
                    <li><a class="menu {{ $current === 'dashboard.reports.expiring-products' ? 'menu--active' : '' }}" href="{{ route('dashboard.reports.expiring-products') }}"><div class="menu__icon"><i data-lucide="activity" class="stroke-1.5 w-5 h-5"></i></div><div class="menu__title">Expiring Products</div></a></li>
                    <li><a class="menu {{ $current === 'dashboard.reports.top-selling' ? 'menu--active' : '' }}" href="{{ route('dashboard.reports.top-selling') }}"><div class="menu__icon"><i data-lucide="activity" class="stroke-1.5 w-5 h-5"></i></div><div class="menu__title">Top Selling</div></a></li>
                    <li><a class="menu {{ $current === 'dashboard.reports.cashier-summary' ? 'menu--active' : '' }}" href="{{ route('dashboard.reports.cashier-summary') }}"><div class="menu__icon"><i data-lucide="activity" class="stroke-1.5 w-5 h-5"></i></div><div class="menu__title">Cashier Summary</div></a></li>
                    <li><a class="menu {{ $current === 'dashboard.reports.vat-summary' ? 'menu--active' : '' }}" href="{{ route('dashboard.reports.vat-summary') }}"><div class="menu__icon"><i data-lucide="activity" class="stroke-1.5 w-5 h-5"></i></div><div class="menu__title">VAT Summary</div></a></li>
                    <li><a class="menu {{ $current === 'dashboard.reports.consolidated' ? 'menu--active' : '' }}" href="{{ route('dashboard.reports.consolidated') }}"><div class="menu__icon"><i data-lucide="activity" class="stroke-1.5 w-5 h-5"></i></div><div class="menu__title">Consolidated</div></a></li>
                </ul>
            </li>
            <li data-menu-permission="branches">
                <a class="menu {{ $current === 'dashboard.branches' ? 'menu--active' : '' }}" href="{{ route('dashboard.branches') }}">
                    <div class="menu__icon"><i data-lucide="building-2" class="stroke-1.5 w-5 h-5"></i></div>
                    <div class="menu__title">Branches</div>
                </a>
            </li>
            <li data-menu-permission="terminals">
                <a class="menu {{ $current === 'dashboard.terminals' ? 'menu--active' : '' }}" href="{{ route('dashboard.terminals') }}">
                    <div class="menu__icon"><i data-lucide="monitor" class="stroke-1.5 w-5 h-5"></i></div>
                    <div class="menu__title">Terminals</div>
                </a>
            </li>
            <li class="menu__divider my-6"></li>
            <li data-menu-permission="bir">
                <a class="menu {{ $current === 'dashboard.bir-settings' ? 'menu--active' : '' }}" href="{{ route('dashboard.bir-settings') }}">
                    <div class="menu__icon"><i data-lucide="settings" class="stroke-1.5 w-5 h-5"></i></div>
                    <div class="menu__title">BIR Settings</div>
                </a>
            </li>
            <li data-menu-permission="receipts">
                <a class="menu {{ $current === 'dashboard.receipts' ? 'menu--active' : '' }}" href="{{ route('dashboard.receipts') }}">
                    <div class="menu__icon"><i data-lucide="printer" class="stroke-1.5 w-5 h-5"></i></div>
                    <div class="menu__title">Receipts</div>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- END: Mobile Menu -->
