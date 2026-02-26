@php
    use App\Services\SuperAdminMenuService;
    $base = $midoneBase ?? asset('midone-html.vercel.app');
    $current = $currentRoute ?? \Illuminate\Support\Facades\Route::currentRouteName();
    $reportsOpen = str_starts_with($current ?? '', 'dashboard.reports.');
    $productsOpen = ($current === 'dashboard.products' || $current === 'dashboard.categories');
    $companiesBranchesTerminalsOpen = in_array($current, ['dashboard.companies', 'dashboard.branches', 'dashboard.terminals'], true);
    $can = fn(string $p) => SuperAdminMenuService::canAccess($menuRole ?? null, $p);
@endphp
<!-- BEGIN: Side Menu (filtered by JS from /auth/me when using API login) -->
<nav class="side-nav hidden w-[80px] pb-16 pr-5 md:block xl:w-[230px]" data-simplebar data-super-admin-sidebar id="super-admin-side-nav">
    <a class="flex items-center pt-4 pl-5 intro-x" href="{{ route('dashboard.dashboard') }}">
        <img class="w-6 shrink-0" src="{{ asset('images/logo.png') }}" alt="Landogz POS">
        <span class="side-nav__logo-text hidden ml-3 text-lg text-white xl:block">Landogz POS</span>
    </a>
    <div class="my-6 side-nav__divider"></div>
    <ul>
        {{-- Dashboard --}}
        <li data-menu-permission="dashboard">
            <a href="{{ route('dashboard.dashboard') }}" class="side-menu {{ $current === 'dashboard.dashboard' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="layout-dashboard" class="stroke-1.5 w-5 h-5"></i>
                </div>
                <div class="side-menu__title">Dashboard</div>
            </a>
        </li>
        {{-- Chain Dashboard (cloud) --}}
        <li data-menu-permission="dashboard">
            <a href="{{ route('dashboard.chain') }}" class="side-menu {{ $current === 'dashboard.chain' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="git-branch" class="stroke-1.5 w-5 h-5"></i>
                </div>
                <div class="side-menu__title">Chain</div>
            </a>
        </li>
        {{-- Companies, Branches, Terminals (one dropdown) --}}
        <li data-menu-permission="branches">
            <a href="javascript:;" class="side-menu {{ $companiesBranchesTerminalsOpen ? 'side-menu--open' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="building-2" class="stroke-1.5 w-5 h-5"></i>
                </div>
                <div class="side-menu__title">
                    Organization
                    <div class="side-menu__sub-icon {{ $companiesBranchesTerminalsOpen ? 'transform rotate-180' : '' }}">
                        <i data-lucide="chevron-down" class="stroke-1.5 w-5 h-5"></i>
                    </div>
                </div>
            </a>
            <ul class="{{ $companiesBranchesTerminalsOpen ? 'side-menu__sub-open' : '' }}" style="{{ $companiesBranchesTerminalsOpen ? '' : 'display: none;' }}">
                <li data-menu-permission="companies">
                    <a href="{{ route('dashboard.companies') }}" class="side-menu {{ $current === 'dashboard.companies' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="building" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">Companies</div>
                    </a>
                </li>
                <li data-menu-permission="branches">
                    <a href="{{ route('dashboard.branches') }}" class="side-menu {{ $current === 'dashboard.branches' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="building-2" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">Branches</div>
                    </a>
                </li>
                <li data-menu-permission="terminals">
                    <a href="{{ route('dashboard.terminals') }}" class="side-menu {{ $current === 'dashboard.terminals' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="monitor" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">Terminals</div>
                    </a>
                </li>
            </ul>
        </li>
        {{-- Users --}}
        <li data-menu-permission="users">
            <a href="{{ route('dashboard.users') }}" class="side-menu {{ $current === 'dashboard.users' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="users" class="stroke-1.5 w-5 h-5"></i>
                </div>
                <div class="side-menu__title">Users</div>
            </a>
        </li>
        {{-- Suppliers --}}
        <li data-menu-permission="suppliers">
            <a href="{{ route('dashboard.suppliers') }}" class="side-menu {{ $current === 'dashboard.suppliers' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="truck" class="stroke-1.5 w-5 h-5"></i>
                </div>
                <div class="side-menu__title">Suppliers</div>
            </a>
        </li>
        {{-- Products (dropdown) --}}
        <li data-menu-permission="products">
            <a href="javascript:;" class="side-menu {{ $productsOpen ? 'side-menu--open' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="package" class="stroke-1.5 w-5 h-5"></i>
                </div>
                <div class="side-menu__title">
                    Products
                    <div class="side-menu__sub-icon {{ $productsOpen ? 'transform rotate-180' : '' }}">
                        <i data-lucide="chevron-down" class="stroke-1.5 w-5 h-5"></i>
                    </div>
                </div>
            </a>
            <ul class="{{ $productsOpen ? 'side-menu__sub-open' : '' }}" style="{{ $productsOpen ? '' : 'display: none;' }}">
                <li>
                    <a href="{{ route('dashboard.products') }}" class="side-menu {{ $current === 'dashboard.products' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="package" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">Products</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.categories') }}" class="side-menu {{ $current === 'dashboard.categories' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="folder" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">Categories</div>
                    </a>
                </li>
            </ul>
        </li>
        {{-- Transactions --}}
        <li data-menu-permission="transactions">
            <a href="{{ route('dashboard.transactions') }}" class="side-menu {{ $current === 'dashboard.transactions' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="receipt" class="stroke-1.5 w-5 h-5"></i>
                </div>
                <div class="side-menu__title">Transactions</div>
            </a>
        </li>
        {{-- Inventory --}}
        <li data-menu-permission="inventory">
            <a href="{{ route('dashboard.inventory') }}" class="side-menu {{ $current === 'dashboard.inventory' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="boxes" class="stroke-1.5 w-5 h-5"></i>
                </div>
                <div class="side-menu__title">Inventory</div>
            </a>
        </li>
        {{-- Reports --}}
        <li data-menu-permission="reports">
            <a href="javascript:;" class="side-menu {{ $reportsOpen ? 'side-menu--open' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="file-bar-chart" class="stroke-1.5 w-5 h-5"></i>
                </div>
                <div class="side-menu__title">
                    Reports
                    <div class="side-menu__sub-icon {{ $reportsOpen ? 'transform rotate-180' : '' }}">
                        <i data-lucide="chevron-down" class="stroke-1.5 w-5 h-5"></i>
                    </div>
                </div>
            </a>
            <ul class="{{ $reportsOpen ? 'side-menu__sub-open' : '' }}" style="{{ $reportsOpen ? '' : 'display: none;' }}">
                <li>
                    <a href="{{ route('dashboard.reports.z-reading') }}" class="side-menu {{ $current === 'dashboard.reports.z-reading' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="file-text" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">Z-Reading (End of Day)</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.reports.x-reading') }}" class="side-menu {{ $current === 'dashboard.reports.x-reading' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="file-text" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">X-Reading (Interim)</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.reports.sales') }}" class="side-menu {{ $current === 'dashboard.reports.sales' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="activity" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">Sales</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.reports.vat-relief') }}" class="side-menu {{ $current === 'dashboard.reports.vat-relief' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="file-check" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">VAT Relief / Relief Data</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.reports.alphalist-payees') }}" class="side-menu {{ $current === 'dashboard.reports.alphalist-payees' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="users" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">Alphalist of Payees (MAP)</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.reports.audit-trail') }}" class="side-menu {{ $current === 'dashboard.reports.audit-trail' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="clipboard-list" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">Audit Trail</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.reports.inventory') }}" class="side-menu {{ $current === 'dashboard.reports.inventory' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="activity" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">Inventory</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.reports.profit-margin') }}" class="side-menu {{ $current === 'dashboard.reports.profit-margin' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="activity" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">Profit Margin</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.reports.expiring-products') }}" class="side-menu {{ $current === 'dashboard.reports.expiring-products' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="activity" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">Expiring Products</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.reports.top-selling') }}" class="side-menu {{ $current === 'dashboard.reports.top-selling' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="activity" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">Top Selling</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.reports.cashier-summary') }}" class="side-menu {{ $current === 'dashboard.reports.cashier-summary' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="activity" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">Cashier Summary</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.reports.vat-summary') }}" class="side-menu {{ $current === 'dashboard.reports.vat-summary' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="activity" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">VAT Summary</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.reports.audit-log') }}" class="side-menu {{ $current === 'dashboard.reports.audit-log' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="activity" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">Audit Log</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.reports.consolidated') }}" class="side-menu {{ $current === 'dashboard.reports.consolidated' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="activity" class="stroke-1.5 w-5 h-5"></i></div>
                        <div class="side-menu__title">Consolidated</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="my-6 side-nav__divider"></li>
        {{-- BIR Settings --}}
        <li data-menu-permission="bir">
            <a href="{{ route('dashboard.bir-settings') }}" class="side-menu {{ $current === 'dashboard.bir-settings' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="settings" class="stroke-1.5 w-5 h-5"></i>
                </div>
                <div class="side-menu__title">BIR Settings</div>
            </a>
        </li>
        {{-- Receipts --}}
        <li data-menu-permission="receipts">
            <a href="{{ route('dashboard.receipts') }}" class="side-menu {{ $current === 'dashboard.receipts' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="printer" class="stroke-1.5 w-5 h-5"></i>
                </div>
                <div class="side-menu__title">Receipts</div>
            </a>
        </li>
        <li class="my-6 side-nav__divider"></li>
        <li>
            <button type="button" id="side-nav-collapse-btn" class="side-menu w-full cursor-pointer text-left" aria-label="Collapse sidebar" title="Collapse sidebar">
                <div class="side-menu__icon">
                    <i data-lucide="panel-left-close" class="stroke-1.5 w-5 h-5"></i>
                </div>
                <div class="side-menu__title">Collapse</div>
            </button>
        </li>
    </ul>
</nav>
<!-- END: Side Menu -->
