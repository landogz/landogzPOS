@extends('super-admin.layouts.app')

@section('title', $company->name . ' — Summary')
@section('breadcrumb', 'Company Summary')

@push('styles')
<style>
    /* Preset dropdown floats above page (TomSelect dropdownParent: "body") */
    .ts-dropdown { z-index: 9999 !important; }
    /* Responsive & iOS: prevent zoom on input focus (16px+), touch scroll, safe area */
    @media (max-width: 639px) {
        #summary-preset, #summary-date-from, #summary-date-to { font-size: 16px !important; min-height: 44px; }
    }
    .company-summary-table-wrap {
        -webkit-overflow-scrolling: touch;
        overflow-x: auto;
        overflow-y: visible;
    }
    .company-summary-content { padding-bottom: env(safe-area-inset-bottom, 0); }
    /* Safari flex: ensure flex children can shrink */
    .company-summary-header-stats { min-width: 0; }
    .company-summary-widget-inner { min-height: 0; -webkit-flex: 1; flex: 1; }
    /* Chart containers: responsive height on small viewports */
    @media (max-width: 767px) {
        .company-summary-chart { height: 280px !important; max-height: 50vh; }
    }
    @media (min-width: 768px) and (max-width: 1023px) {
        .company-summary-chart { height: 320px !important; }
    }
</style>
@endpush

@section('content')
    <div class="company-summary-content intro-y mt-4 sm:mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-base sm:text-lg font-medium text-slate-800 dark:text-slate-100">Company Summary</h2>
        <div class="flex flex-col gap-3 w-full min-w-0 sm:flex-row sm:flex-wrap sm:items-center sm:w-auto">
            <div class="company-summary-preset-wrap flex items-center gap-2 w-full min-w-0 sm:w-auto">
                <label for="summary-preset" class="text-sm text-slate-600 dark:text-slate-400 whitespace-nowrap flex-shrink-0">Preset</label>
                <div class="mt-0 flex-1 min-w-0 sm:w-36">
                    <select id="summary-preset" data-placeholder="Preset" class="tom-select w-full" aria-label="Date preset">
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-2 w-full min-w-0 sm:w-auto">
                <label for="summary-date-from" class="text-sm text-slate-600 dark:text-slate-400 whitespace-nowrap flex-shrink-0">From</label>
                <input type="date" id="summary-date-from" class="rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2 text-sm flex-1 min-w-0 sm:w-36 touch-manipulation" aria-label="Date from">
            </div>
            <div class="flex items-center gap-2 w-full min-w-0 sm:w-auto">
                <label for="summary-date-to" class="text-sm text-slate-600 dark:text-slate-400 whitespace-nowrap flex-shrink-0">To</label>
                <input type="date" id="summary-date-to" class="rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2 text-sm flex-1 min-w-0 sm:w-36 touch-manipulation" aria-label="Date to">
            </div>
            <button type="button" id="summary-apply-btn" class="rounded-lg bg-primary px-4 py-3 sm:py-2 text-sm font-medium text-white hover:bg-primary/90 active:bg-primary/80 transition-colors touch-manipulation min-h-[44px] w-full sm:w-auto">Apply</button>
        </div>
    </div>

    <div id="summary-branch-notice" class="intro-y mt-4 hidden rounded-lg border border-primary/30 bg-primary/5 px-4 py-3 text-sm text-slate-700 dark:text-slate-300">
        Viewing branch: <span id="summary-branch-name" class="font-medium"></span>.
        <a id="summary-view-all-branches" href="#" class="ml-1 font-medium text-primary hover:underline">View all branches</a>
    </div>

    <div id="summary-loading" class="intro-y mt-6 flex justify-center py-12">
        <span class="text-slate-500 dark:text-slate-400">Loading summary...</span>
    </div>

    <div id="summary-content" class="hidden">
        {{-- BEGIN: Profile-style header box (rubick-side-menu-profile-overview-2) --}}
        <div class="intro-y box mt-5 px-4 sm:px-5 pt-5">
            <div class="-mx-4 sm:-mx-5 flex flex-col border-b border-slate-200/60 pb-5 dark:border-darkmode-400 lg:flex-row">
                <div class="flex flex-1 items-center justify-center px-4 sm:px-5 lg:justify-start min-w-0">
                    <a id="summary-back-link" href="{{ route('dashboard.companies') }}" class="rounded-lg border border-slate-200 dark:border-darkmode-500 p-2.5 min-w-[44px] min-h-[44px] flex items-center justify-center gap-2 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-darkmode-600 active:bg-slate-200 dark:active:bg-darkmode-500 transition-colors mr-3 flex-shrink-0 touch-manipulation" aria-label="Back to Companies" title="Back to Companies">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0"><path d="m15 18-6-6 6-6"/></svg>
                        <span id="summary-back-label" class="hidden sm:inline text-sm font-medium">Back to Companies</span>
                    </a>
                    @if($company->logo_url)
                        <img src="{{ $company->logo_url }}" alt="" class="image-fit h-16 w-16 flex-none rounded-full border border-slate-200 dark:border-darkmode-500 sm:h-20 sm:w-20 lg:h-24 lg:w-24 object-cover">
                    @else
                        <div class="flex h-16 w-16 sm:h-20 sm:w-20 lg:h-24 lg:w-24 flex-none items-center justify-center rounded-full bg-primary/10 text-primary border border-slate-200 dark:border-darkmode-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"/><path d="M9 8h1"/><path d="M9 12h1"/><path d="M9 16h1"/><path d="M14 8h1"/><path d="M14 12h1"/><path d="M14 16h1"/><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/></svg>
                        </div>
                    @endif
                    <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                        <div class="truncate text-base sm:text-lg font-medium text-slate-800 dark:text-slate-100">{{ $company->name }}</div>
                        <div class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm">Sales &amp; records summary</div>
                    </div>
                </div>
                <div class="company-summary-header-stats mt-6 flex flex-1 flex-wrap items-center justify-center gap-3 sm:gap-4 border-t border-slate-200/60 px-4 sm:px-5 pt-5 dark:border-darkmode-400 lg:mt-0 lg:border-0 lg:pt-0">
                    <div class="min-w-[4rem] sm:min-w-[4.5rem] rounded-md py-2 sm:py-3 text-center touch-manipulation">
                        <div id="header-total-sales" class="text-lg font-medium text-primary sm:text-xl">0</div>
                        <div class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm">Total Sales</div>
                    </div>
                    <div class="min-w-[4rem] sm:min-w-[4.5rem] rounded-md py-2 sm:py-3 text-center touch-manipulation">
                        <div id="header-transaction-count" class="text-lg font-medium text-primary sm:text-xl">0</div>
                        <div class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm">Transactions</div>
                    </div>
                    <div class="min-w-[4rem] sm:min-w-[4.5rem] rounded-md py-2 sm:py-3 text-center touch-manipulation">
                        <div id="header-branches-count" class="text-lg font-medium text-primary sm:text-xl">0</div>
                        <div class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm">Branches</div>
                    </div>
                    <div class="min-w-[4rem] sm:min-w-[4.5rem] rounded-md py-2 sm:py-3 text-center touch-manipulation">
                        <div id="header-low-stock" class="text-lg font-medium text-primary sm:text-xl">0</div>
                        <div class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm">Low stock alerts</div>
                    </div>
                    <div class="min-w-[4rem] sm:min-w-[4.5rem] rounded-md py-2 sm:py-3 text-center touch-manipulation">
                        <div id="header-expiring-soon" class="text-lg font-medium text-primary sm:text-xl">0</div>
                        <div class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm">Expiring soon</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- END: Profile header --}}

        {{-- KPI Cards --}}
        <div class="grid grid-cols-12 gap-3 sm:gap-4 mt-5">
            <div class="intro-y col-span-6 sm:col-span-4 lg:col-span-2 min-w-0">
                <div class="box p-3 sm:p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Total Revenue</span>
                        <span id="kpi-revenue-change" class="text-xs font-medium"></span>
                    </div>
                    <div id="kpi-total-sales" class="mt-1 text-lg font-semibold text-slate-800 dark:text-slate-100">₱0</div>
                </div>
            </div>
            <div class="intro-y col-span-6 sm:col-span-4 lg:col-span-2 min-w-0">
                <div class="box p-3 sm:p-4">
                    <span class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Avg Transaction</span>
                    <div id="kpi-avg-tx" class="mt-1 text-lg font-semibold text-slate-800 dark:text-slate-100">₱0</div>
                </div>
            </div>
            <div class="intro-y col-span-6 sm:col-span-4 lg:col-span-2 min-w-0">
                <div class="box p-3 sm:p-4">
                    <span class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Products Sold</span>
                    <div id="kpi-products-sold" class="mt-1 text-lg font-semibold text-slate-800 dark:text-slate-100">0</div>
                </div>
            </div>
            <div class="intro-y col-span-6 sm:col-span-4 lg:col-span-2 min-w-0">
                <div class="box p-3 sm:p-4">
                    <span class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Discounts Given</span>
                    <div id="kpi-discount" class="mt-1 text-lg font-semibold text-slate-800 dark:text-slate-100">₱0</div>
                </div>
            </div>
            <div class="intro-y col-span-6 sm:col-span-4 lg:col-span-2 min-w-0">
                <div class="box p-3 sm:p-4">
                    <span class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">VAT Collected</span>
                    <div id="kpi-vat" class="mt-1 text-lg font-semibold text-slate-800 dark:text-slate-100">₱0</div>
                </div>
            </div>
            <div class="intro-y col-span-6 sm:col-span-4 lg:col-span-2 min-w-0">
                <div class="box p-3 sm:p-4">
                    <span class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Net Sales</span>
                    <div id="kpi-net-sales" class="mt-1 text-lg font-semibold text-slate-800 dark:text-slate-100">₱0</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-4 sm:gap-6 mt-5 min-w-0">
            {{-- BEGIN: Daily Sales Chart --}}
            <div class="intro-y box col-span-12 lg:col-span-8">
                <div class="flex items-center border-b border-slate-200/60 px-5 py-5 dark:border-darkmode-400 sm:py-3">
                    <h2 class="mr-auto text-base font-medium text-slate-800 dark:text-slate-100">Sales over time</h2>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="company-summary-chart h-[400px] w-full min-h-0">
                        <canvas id="chart-sales-over-time" aria-label="Sales over time"></canvas>
                    </div>
                </div>
            </div>
            {{-- END: Daily Sales Chart --}}

            {{-- BEGIN: Sales by branch (donut) --}}
            <div class="intro-y box col-span-12 lg:col-span-4">
                <div class="flex items-center border-b border-slate-200/60 px-5 py-5 dark:border-darkmode-400 sm:py-3">
                    <h2 class="mr-auto text-base font-medium text-slate-800 dark:text-slate-100">Sales by branch</h2>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="company-summary-chart mx-auto h-[400px] w-full max-w-[400px] min-h-0">
                        <canvas id="chart-sales-by-branch" aria-label="Sales by branch"></canvas>
                    </div>
                </div>
            </div>
            {{-- END: Sales by branch --}}

            {{-- Monthly comparison (this year vs last year) --}}
            <div class="intro-y box col-span-12">
                <div class="flex items-center border-b border-slate-200/60 px-5 py-3 dark:border-darkmode-400">
                    <h2 class="mr-auto text-base font-medium text-slate-800 dark:text-slate-100">Monthly sales comparison</h2>
                </div>
                <div class="p-4 sm:p-5 pt-3">
                    <div class="company-summary-chart h-[400px] w-full min-h-0">
                        <canvas id="chart-monthly-comparison" aria-label="Monthly comparison"></canvas>
                    </div>
                </div>
            </div>

            {{-- Payment method & Top cashiers --}}
            <div class="intro-y box col-span-12 lg:col-span-6">
                <div class="flex items-center border-b border-slate-200/60 px-5 py-5 dark:border-darkmode-400 sm:py-3">
                    <h2 class="mr-auto text-base font-medium text-slate-800 dark:text-slate-100">Sales by payment method</h2>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="company-summary-chart mx-auto h-[400px] w-full max-w-[400px] min-h-0">
                        <canvas id="chart-payment-method" aria-label="Payment method"></canvas>
                    </div>
                </div>
            </div>
            <div class="intro-y box col-span-12 lg:col-span-6">
                <div class="flex items-center border-b border-slate-200/60 px-5 py-5 dark:border-darkmode-400 sm:py-3">
                    <h2 class="mr-auto text-base font-medium text-slate-800 dark:text-slate-100">Top cashiers</h2>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="company-summary-chart h-[400px] w-full min-h-0">
                        <canvas id="chart-top-cashiers" aria-label="Top cashiers"></canvas>
                    </div>
                </div>
            </div>

            {{-- Top 5 products --}}
            <div class="intro-y box col-span-12">
                <div class="flex items-center border-b border-slate-200/60 px-5 py-3 dark:border-darkmode-400">
                    <h2 class="mr-auto text-base font-medium text-slate-800 dark:text-slate-100">Top 5 products</h2>
                </div>
                <div class="p-4 sm:p-5 pt-3">
                    <div class="company-summary-chart h-[400px] w-full min-h-0">
                        <canvas id="chart-top-products" aria-label="Top products"></canvas>
                    </div>
                </div>
            </div>

            {{-- BEGIN: Sales by branch table --}}
            <div class="intro-y box col-span-12">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center border-b border-slate-200/60 px-4 sm:px-5 py-4 sm:py-5 dark:border-darkmode-400 sm:py-3">
                    <h2 class="mr-auto text-base font-medium text-slate-800 dark:text-slate-100">Branch breakdown</h2>
                    <button type="button" id="branch-export-excel" class="rounded-lg border border-slate-200 dark:border-darkmode-500 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-600 active:bg-slate-100 min-h-[44px] touch-manipulation w-full sm:w-auto">Export Excel</button>
                </div>
                <div class="p-4 sm:p-5 company-summary-table-wrap overflow-x-auto">
                    <table class="w-full min-w-[600px] sm:min-w-[700px] text-left text-sm">
                        <thead class="border-b border-slate-200 dark:border-darkmode-500">
                            <tr>
                                <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">Branch</th>
                                <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">Address</th>
                                <th class="pb-3 font-medium text-slate-700 dark:text-slate-300 text-right">Transactions</th>
                                <th class="pb-3 font-medium text-slate-700 dark:text-slate-300 text-right">Total sales</th>
                                <th class="pb-3 font-medium text-slate-700 dark:text-slate-300 text-right">Avg transaction</th>
                                <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">Top product</th>
                                <th class="pb-3 font-medium text-slate-700 dark:text-slate-300 text-right">Cashiers</th>
                            </tr>
                        </thead>
                        <tbody id="summary-branches-tbody" class="divide-y divide-slate-200 dark:divide-darkmode-500">
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- END: Sales by branch table --}}

            {{-- BEGIN: Sales by terminal table (grouped by branch, subtotals, grand total) --}}
            <div class="intro-y box col-span-12 mt-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center border-b border-slate-200/60 px-4 sm:px-5 py-4 sm:py-5 dark:border-darkmode-400 sm:py-3">
                    <h2 class="mr-auto text-base font-medium text-slate-800 dark:text-slate-100">Terminals &amp; sales</h2>
                    <button type="button" id="terminals-export-excel" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 dark:border-darkmode-500 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-600 active:bg-slate-100 min-h-[44px] touch-manipulation w-full sm:w-auto transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export Excel
                    </button>
                </div>
                <div class="p-4 sm:p-5 company-summary-table-wrap overflow-x-auto">
                    <table class="w-full min-w-[400px] sm:min-w-[480px] text-sm">
                        <thead class="border-b border-slate-200 dark:border-darkmode-500">
                            <tr>
                                <th class="pb-2.5 pt-0 font-medium text-slate-700 dark:text-slate-300 text-left">Terminal</th>
                                <th class="pb-2.5 pt-0 font-medium text-slate-700 dark:text-slate-300 text-right w-28">Transactions</th>
                                <th class="pb-2.5 pt-0 font-medium text-slate-700 dark:text-slate-300 text-right w-32">Total sales</th>
                                <th class="pb-2.5 pt-0 font-medium text-slate-700 dark:text-slate-300 text-right w-28">Avg transaction</th>
                            </tr>
                        </thead>
                        <tbody id="summary-terminals-tbody" class="divide-y divide-slate-200 dark:divide-darkmode-500">
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- END: Sales by terminal table --}}

            {{-- Widgets row: Inventory, Top products, BIR, User activity (equal height cards) --}}
            <div class="col-span-12 mt-6 sm:mt-8 grid grid-cols-12 gap-4 sm:gap-6 items-stretch">
                <div class="intro-y col-span-12 sm:col-span-6 2xl:col-span-3 flex">
                    <div class="box zoom-in p-6 h-full w-full flex flex-col min-h-[230px]">
                        <div class="flex items-start flex-shrink-0 gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="text-base font-semibold text-slate-800 dark:text-slate-100">Inventory Summary</div>
                                <div id="widget-inventory-subtitle" class="mt-1.5 text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Low stock: 0 · Expiring: 0 · Out: 0</div>
                            </div>
                            <div class="relative flex-none">
                                <div class="w-[90px] h-[90px]">
                                    <canvas id="widget-chart-inventory" class="chart" width="90" height="90" aria-label="Inventory"></canvas>
                                </div>
                                <div id="widget-inventory-center" class="absolute left-0 top-0 flex h-full w-full items-center justify-center text-sm font-semibold text-slate-700 dark:text-slate-300">0</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="intro-y col-span-12 sm:col-span-6 2xl:col-span-3 flex">
                    <div class="box zoom-in p-6 h-full w-full flex flex-col min-h-[230px]">
                        <div class="flex items-start flex-shrink-0 gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="text-base font-semibold text-slate-800 dark:text-slate-100">Top Products</div>
                                <div id="widget-top-products-subtitle" class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">This period</div>
                            </div>
                            <div class="relative flex-none">
                                <div class="w-[90px] h-[90px]">
                                    <canvas id="widget-chart-top-products" class="chart" width="90" height="90" aria-label="Top products"></canvas>
                                </div>
                                <div id="widget-top-products-center" class="absolute left-0 top-0 flex h-full w-full items-center justify-center text-sm font-semibold text-slate-700 dark:text-slate-300">0</div>
                            </div>
                        </div>
                        <ul id="widget-top-products-list" class="company-summary-widget-inner mt-4 space-y-2 border-t border-slate-200/60 pt-4 text-sm text-slate-600 dark:border-darkmode-400 dark:text-slate-400 flex-1 min-h-0 overflow-auto"></ul>
                    </div>
                </div>
                <div class="intro-y col-span-12 sm:col-span-6 2xl:col-span-3 flex">
                    <div class="box zoom-in p-6 h-full w-full flex flex-col min-h-[230px]">
                        <div class="flex items-start flex-shrink-0 gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="text-base font-semibold text-slate-800 dark:text-slate-100">BIR Compliance</div>
                                <div id="widget-vat-month" class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">VAT this month ₱0</div>
                            </div>
                            <div class="relative flex-none">
                                <div class="w-[90px] h-[90px]">
                                    <canvas id="widget-chart-bir" class="chart" width="90" height="90" aria-label="BIR"></canvas>
                                </div>
                                <div id="widget-bir-center" class="absolute left-0 top-0 flex h-full w-full items-center justify-center text-xs font-semibold text-slate-700 dark:text-slate-300 text-center px-1">VAT</div>
                            </div>
                        </div>
                        <div id="widget-or-range" class="company-summary-widget-inner mt-4 border-t border-slate-200/60 pt-4 text-sm text-slate-500 dark:border-darkmode-400 dark:text-slate-400 flex-1 min-h-0 overflow-auto space-y-1.5"></div>
                    </div>
                </div>
                <div class="intro-y col-span-12 sm:col-span-6 2xl:col-span-3 flex">
                    <div class="box zoom-in p-6 h-full w-full flex flex-col min-h-[230px]">
                        <div class="flex items-start flex-shrink-0 gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="text-base font-semibold text-slate-800 dark:text-slate-100">User Activity</div>
                                <div class="mt-1.5 flex items-center gap-2">
                                    <span id="widget-active-cashiers" class="rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400">0 active</span>
                                </div>
                            </div>
                            <div class="relative flex-none">
                                <div class="w-[90px] h-[90px]">
                                    <canvas id="widget-chart-activity" class="chart" width="90" height="90" aria-label="Activity"></canvas>
                                </div>
                                <div id="widget-activity-center" class="absolute left-0 top-0 flex h-full w-full items-center justify-center text-xs font-semibold text-slate-700 dark:text-slate-300 text-center px-1">Today</div>
                            </div>
                        </div>
                        <div id="widget-most-active" class="company-summary-widget-inner mt-4 border-t border-slate-200/60 pt-4 text-sm text-slate-500 dark:border-darkmode-400 dark:text-slate-400 flex-1 min-h-0">Most active: —</div>
                    </div>
                </div>
            </div>

            {{-- BEGIN: Recent transactions --}}
            <div class="intro-y box col-span-12">
                <div class="flex flex-col gap-3 border-b border-slate-200/60 px-4 sm:px-5 py-4 sm:py-5 dark:border-darkmode-400 sm:flex-row sm:items-center sm:justify-between sm:py-3">
                    <h2 class="text-base font-medium text-slate-800 dark:text-slate-100">Recent transactions</h2>
                    <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2 w-full sm:w-auto">
                        <input type="text" id="transactions-search" placeholder="Search OR, branch, cashier..." class="rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm w-full min-w-0 sm:w-56 min-h-[44px] touch-manipulation" aria-label="Search transactions">
                        <button type="button" id="transactions-export-excel" class="rounded-lg border border-slate-200 dark:border-darkmode-500 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-600 active:bg-slate-100 min-h-[44px] touch-manipulation w-full sm:w-auto">Export Excel</button>
                    </div>
                </div>
                <div class="p-4 sm:p-5 company-summary-table-wrap overflow-x-auto">
                    <table class="w-full min-w-[700px] text-left text-sm">
                        <thead class="border-b border-slate-200 dark:border-darkmode-500">
                            <tr>
                                <th class="pb-3 pr-4 font-medium text-slate-700 dark:text-slate-300 whitespace-nowrap">OR #</th>
                                <th class="pb-3 pr-4 font-medium text-slate-700 dark:text-slate-300">Branch</th>
                                <th class="pb-3 pr-4 font-medium text-slate-700 dark:text-slate-300">Cashier</th>
                                <th class="pb-3 pr-4 font-medium text-slate-700 dark:text-slate-300">Payment</th>
                                <th class="pb-3 pr-4 font-medium text-slate-700 dark:text-slate-300">Status</th>
                                <th class="pb-3 pr-4 font-medium text-slate-700 dark:text-slate-300 text-right whitespace-nowrap">Total</th>
                                <th class="pb-3 pr-4 font-medium text-slate-700 dark:text-slate-300 text-right whitespace-nowrap">VAT</th>
                                <th class="pb-3 pr-4 font-medium text-slate-700 dark:text-slate-300 text-right whitespace-nowrap">Discount</th>
                                <th class="pb-3 font-medium text-slate-700 dark:text-slate-300 whitespace-nowrap">Date</th>
                            </tr>
                        </thead>
                        <tbody id="summary-transactions-tbody" class="divide-y divide-slate-200 dark:divide-darkmode-500">
                        </tbody>
                    </table>
                </div>
                {{-- Pagination controls --}}
                <div id="tx-pagination" class="flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-slate-200 dark:border-darkmode-500 px-4 sm:px-5 py-3 text-sm text-slate-600 dark:text-slate-400">
                    <span id="tx-page-info">—</span>
                    <div class="flex items-center gap-2">
                        <button id="tx-prev" type="button" class="rounded-lg border border-slate-200 dark:border-darkmode-500 px-3 py-1.5 text-sm hover:bg-slate-50 dark:hover:bg-darkmode-600 disabled:opacity-40 disabled:cursor-not-allowed transition-colors touch-manipulation" disabled>← Prev</button>
                        <span id="tx-page-btns" class="flex items-center gap-1"></span>
                        <button id="tx-next" type="button" class="rounded-lg border border-slate-200 dark:border-darkmode-500 px-3 py-1.5 text-sm hover:bg-slate-50 dark:hover:bg-darkmode-600 disabled:opacity-40 disabled:cursor-not-allowed transition-colors touch-manipulation" disabled>Next →</button>
                    </div>
                </div>
            </div>
            {{-- END: Recent transactions --}}
        </div>
    </div>

    <div id="summary-error" class="intro-y mt-6 hidden rounded-lg border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 p-4 text-red-700 dark:text-red-300">
        <p id="summary-error-text"></p>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js" crossorigin="anonymous"></script>
<script>
    // Keep ChartDataLabels registered but globally hidden by default.
    // Charts that need it explicitly set plugins: { datalabels: { display: true, ... } }.
    // This avoids any scale/axis interference caused by the plugin running on all charts.
    if (typeof ChartDataLabels !== 'undefined' && typeof Chart !== 'undefined') {
        if (!Chart.defaults.plugins.datalabels) Chart.defaults.plugins.datalabels = {};
        Chart.defaults.plugins.datalabels.display = false;
    }
</script>
<script>
(function() {
    var companyId = {{ $company->id }};
    var apiBase = '{{ url("/api/v1") }}';
    var dashboardBase = '{{ url("/dashboard") }}';
    window.summaryUrl = function(cId) { return dashboardBase + '/companies/' + (cId || companyId) + '/summary'; };
    var token = localStorage.getItem('super_admin_token');
    if (!token) {
        window.location.href = '{{ route("dashboard.login") }}';
        return;
    }

    var loadingEl = document.getElementById('summary-loading');
    var contentEl = document.getElementById('summary-content');
    var errorEl = document.getElementById('summary-error');
    var errorText = document.getElementById('summary-error-text');

    var chartSalesOverTime = null;
    var chartSalesByBranch = null;
    var chartMonthly = null;
    var chartPayment = null;
    var chartTopCashiers = null;
    var chartTopProducts = null;
    var widgetChartInventory = null;
    var widgetChartTopProducts = null;
    var widgetChartBir = null;
    var widgetChartActivity = null;
    var lastSummaryData = null;

    function authHeaders() {
        return { headers: { Authorization: 'Bearer ' + token, Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } };
    }

    function setDateRange(from, to) {
        document.getElementById('summary-date-from').value = from;
        document.getElementById('summary-date-to').value = to;
    }
    function defaultDates() {
        var now = new Date();
        var start = new Date(now.getFullYear(), now.getMonth(), 1);
        setDateRange(start.toISOString().slice(0, 10), now.toISOString().slice(0, 10));
    }
    function presetToday() {
        var d = new Date().toISOString().slice(0, 10);
        setDateRange(d, d);
        loadSummary();
    }
    function presetWeek() {
        var now = new Date();
        var start = new Date(now);
        start.setDate(now.getDate() - now.getDay());
        setDateRange(start.toISOString().slice(0, 10), now.toISOString().slice(0, 10));
        loadSummary();
    }
    function presetMonth() {
        var now = new Date();
        var start = new Date(now.getFullYear(), now.getMonth(), 1);
        setDateRange(start.toISOString().slice(0, 10), now.toISOString().slice(0, 10));
        loadSummary();
    }
    function presetYear() {
        var now = new Date();
        var start = new Date(now.getFullYear(), 0, 1);
        setDateRange(start.toISOString().slice(0, 10), now.toISOString().slice(0, 10));
        loadSummary();
    }

    function formatMoney(n) {
        return parseFloat(n).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function escapeHtml(s) {
        if (!s) return '';
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    var monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    function destroyCharts() {
        [chartSalesOverTime, chartSalesByBranch, chartMonthly, chartPayment, chartTopCashiers, chartTopProducts,
         widgetChartInventory, widgetChartTopProducts, widgetChartBir, widgetChartActivity].forEach(function(c) {
            if (c) { c.destroy(); }
        });
        chartSalesOverTime = chartSalesByBranch = chartMonthly = chartPayment = chartTopCashiers = chartTopProducts = null;
        widgetChartInventory = widgetChartTopProducts = widgetChartBir = widgetChartActivity = null;
    }

    function renderWidgetCharts(data) {
        if (widgetChartInventory) { widgetChartInventory.destroy(); widgetChartInventory = null; }
        if (widgetChartTopProducts) { widgetChartTopProducts.destroy(); widgetChartTopProducts = null; }
        if (widgetChartBir) { widgetChartBir.destroy(); widgetChartBir = null; }
        if (widgetChartActivity) { widgetChartActivity.destroy(); widgetChartActivity = null; }

        var inv = data.inventory_summary || {};
        var low = inv.low_stock_count ?? 0, exp = inv.expiring_soon_count ?? 0, out = inv.out_of_stock_count ?? 0;
        var ctxInv = document.getElementById('widget-chart-inventory');
        if (ctxInv) {
            widgetChartInventory = new Chart(ctxInv, {
                type: 'doughnut',
                data: {
                    labels: ['Low stock', 'Expiring', 'Out'],
                    datasets: [{
                        data: low + exp + out === 0 ? [1] : [low, exp, out],
                        backgroundColor: ['#f59e0b', '#ef4444', '#64748b'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '65%',
                    plugins: { legend: { display: false } }
                }
            });
        }

        var topList = data.top_5_products || [];
        var topVals = topList.map(function(p) { return p.quantity_sold || 0; });
        if (topVals.length === 0) topVals = [1];
        var ctxTop = document.getElementById('widget-chart-top-products');
        if (ctxTop) {
            widgetChartTopProducts = new Chart(ctxTop, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: topVals,
                        backgroundColor: ['#6366f1', '#8b5cf6', '#a855f7', '#c084fc', '#d8b4fe'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '65%',
                    plugins: { legend: { display: false } }
                }
            });
        }

        var bir = data.bir_summary || {};
        var vat = bir.vat_this_month || 0;
        var ctxBir = document.getElementById('widget-chart-bir');
        if (ctxBir) {
            widgetChartBir = new Chart(ctxBir, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [100],
                        backgroundColor: [vat > 0 ? '#22c55e' : '#e2e8f0'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '65%',
                    plugins: { legend: { display: false } }
                }
            });
        }

        var ua = data.user_activity || {};
        var active = ua.active_cashiers_today ?? 0;
        var ctxAct = document.getElementById('widget-chart-activity');
        if (ctxAct) {
            widgetChartActivity = new Chart(ctxAct, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [100],
                        backgroundColor: [active > 0 ? '#3b82f6' : '#e2e8f0'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '65%',
                    plugins: { legend: { display: false } }
                }
            });
        }
    }

    function renderCharts(data) {
        destroyCharts();
        var salesByDay = data.sales_by_day || [];
        var branches = data.branches || [];
        var monthly = data.monthly_comparison || [];
        var payment = data.sales_by_payment_method || [];
        var topCashiers = data.top_cashiers || [];
        var topProducts = data.top_5_products || [];

        var ctxLine = document.getElementById('chart-sales-over-time');
        if (ctxLine && salesByDay.length > 0) {
            var labels = salesByDay.map(function(r) { return r.date; });
            var totals = salesByDay.map(function(r) { return r.total; });
            chartSalesOverTime = new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales (₱)',
                        data: totals,
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        datalabels: { display: false }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            min: 0,
                            grid: { color: 'rgba(148,163,184,0.15)' },
                            ticks: {
                                callback: function(v) {
                                    var n = Math.round(parseFloat(v));
                                    if (isNaN(n)) return '';
                                    if (n >= 1000000) return '\u20B1' + (n / 1000000).toFixed(1) + 'M';
                                    if (n >= 1000)    return '\u20B1' + Math.round(n / 1000) + 'k';
                                    return '\u20B1' + n;
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 30,
                                maxTicksLimit: 14,
                                font: { size: 11 }
                            }
                        }
                    }
                }
            });
        } else if (ctxLine) {
            chartSalesOverTime = new Chart(ctxLine, {
                type: 'bar',
                data: { labels: ['No data'], datasets: [{ label: 'Sales', data: [0], backgroundColor: 'rgba(148,163,184,0.3)' }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });
        }

        var ctxDonut = document.getElementById('chart-sales-by-branch');
        if (ctxDonut && branches.length > 0) {
            var names = branches.map(function(b) { return b.name || '—'; });
            var values = branches.map(function(b) { return b.total_sales || 0; });
            var colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f43f5e', '#f97316', '#eab308', '#22c55e', '#14b8a6'];
            chartSalesByBranch = new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    labels: names,
                    datasets: [{ data: values, backgroundColor: names.map(function(_, i) { return colors[i % colors.length]; }), borderWidth: 2, borderColor: '#fff' }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
            });
        } else if (ctxDonut) {
            chartSalesByBranch = new Chart(ctxDonut, {
                type: 'doughnut',
                data: { labels: ['No data'], datasets: [{ data: [1], backgroundColor: ['#cbd5e1'] }] },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }

        var ctxMonthly = document.getElementById('chart-monthly-comparison');
        if (ctxMonthly) {
            // Pad to always show all 12 months, filling 0 for months with no data
            var thisYearData = Array(12).fill(0);
            var lastYearData = Array(12).fill(0);
            (monthly || []).forEach(function(r) {
                var m = parseInt(r.month, 10);
                if (m >= 1 && m <= 12) {
                    thisYearData[m - 1] = parseFloat(r.this_year) || 0;
                    lastYearData[m - 1] = parseFloat(r.last_year) || 0;
                }
            });
            var curYear  = new Date().getFullYear();
            var prevYear = curYear - 1;
            // Always enforce a minimum Y-axis range of ₱5,000 so ticks show as
            // ₱1k, ₱2k … rather than ₱20, ₱40 (which looks like a percent scale
            // when demo/small data is present).
            var monthlyMax = Math.max.apply(null, thisYearData.concat(lastYearData));
            var monthlySuggestedMax = Math.max(monthlyMax * 1.15, 5000);
            chartMonthly = new Chart(ctxMonthly, {
                type: 'bar',
                data: {
                    labels: monthNames,
                    datasets: [
                        {
                            label: String(curYear),
                            data: thisYearData,
                            backgroundColor: 'rgba(99,102,241,0.82)',
                            borderColor: 'rgba(99,102,241,1)',
                            borderWidth: 1.5,
                            borderRadius: 4,
                            borderSkipped: false,
                        },
                        {
                            label: String(prevYear),
                            data: lastYearData,
                            backgroundColor: 'rgba(148,163,184,0.55)',
                            borderColor: 'rgba(148,163,184,0.8)',
                            borderWidth: 1.5,
                            borderRadius: 4,
                            borderSkipped: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            type: 'linear',
                            min: 0,
                            suggestedMax: monthlySuggestedMax,
                            grid: { color: 'rgba(148,163,184,0.15)' },
                            ticks: {
                                // Use pure arithmetic — avoids locale-specific %/comma issues
                                callback: function(v) {
                                    var n = Math.round(parseFloat(v));
                                    if (isNaN(n)) return '';
                                    if (n >= 1000000) return '\u20B1' + (n / 1000000).toFixed(1) + 'M';
                                    if (n >= 1000)    return '\u20B1' + Math.round(n / 1000) + 'k';
                                    return '\u20B1' + n;
                                }
                            }
                        },
                        x: { grid: { display: false } }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { boxWidth: 12, padding: 16, font: { size: 12 } }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return ' ' + ctx.dataset.label + ': ₱' + parseFloat(ctx.parsed.y).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                }
                            }
                        },
                        datalabels: { display: false }
                    }
                }
            });
        }

        var ctxPay = document.getElementById('chart-payment-method');
        if (ctxPay && payment.length > 0) {
            var payLabels = payment.map(function(p) { return (p.method || 'cash').charAt(0).toUpperCase() + (p.method || '').slice(1); });
            var payVals = payment.map(function(p) { return p.total; });
            chartPayment = new Chart(ctxPay, {
                type: 'doughnut',
                data: {
                    labels: payLabels,
                    datasets: [{ data: payVals, backgroundColor: ['#22c55e', '#3b82f6', '#f59e0b'], borderWidth: 2, borderColor: '#fff' }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
            });
        }

        var ctxCash = document.getElementById('chart-top-cashiers');
        if (ctxCash && topCashiers.length > 0) {
            var cashierPalette = [
                { bg: 'rgba(16,185,129,0.85)',  border: 'rgba(16,185,129,1)'  },  // emerald
                { bg: 'rgba(59,130,246,0.85)',  border: 'rgba(59,130,246,1)'  },  // blue
                { bg: 'rgba(245,158,11,0.85)',  border: 'rgba(245,158,11,1)'  },  // amber
                { bg: 'rgba(139,92,246,0.85)',  border: 'rgba(139,92,246,1)'  },  // violet
                { bg: 'rgba(239,68,68,0.85)',   border: 'rgba(239,68,68,1)'   },  // red
            ];
            var cashBg  = topCashiers.map(function(_, i) { return cashierPalette[i % cashierPalette.length].bg; });
            var cashBd  = topCashiers.map(function(_, i) { return cashierPalette[i % cashierPalette.length].border; });
            chartTopCashiers = new Chart(ctxCash, {
                plugins: [ChartDataLabels],
                type: 'bar',
                data: {
                    labels: topCashiers.map(function(c) { return c.cashier_name || '—'; }),
                    datasets: [{
                        label: 'Sales (₱)',
                        data: topCashiers.map(function(c) { return c.total_sales; }),
                        backgroundColor: cashBg,
                        borderColor: cashBd,
                        borderWidth: 2,
                        borderRadius: 5,
                        borderSkipped: false,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { color: 'rgba(148,163,184,0.15)' },
                            ticks: { callback: function(v) { return '₱' + v.toLocaleString(); } }
                        },
                        y: { grid: { display: false }, ticks: { font: { size: 12 } } }
                    },
                    plugins: {
                        legend: { display: false },
                        datalabels: {
                            display: true,
                            anchor: 'end',
                            align: 'right',
                            clamp: false,
                            clip: false,
                            offset: 6,
                            color: '#1e293b',
                            backgroundColor: 'rgba(255,255,255,0.92)',
                            borderRadius: 4,
                            padding: { left: 5, right: 5, top: 2, bottom: 2 },
                            font: { weight: 'bold', size: 11 },
                            formatter: function(v) {
                                var n = Math.round(parseFloat(v));
                                return '\u20B1' + n.toLocaleString();
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) { return ' ₱' + parseFloat(ctx.parsed.x).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
                            }
                        }
                    }
                }
            });
        }

        var ctxProd = document.getElementById('chart-top-products');
        if (ctxProd && topProducts.length > 0) {
            var barColors = [
                { bg: 'rgba(59,130,246,0.85)',  border: 'rgba(59,130,246,1)'  },  // blue
                { bg: 'rgba(16,185,129,0.85)',  border: 'rgba(16,185,129,1)'  },  // emerald
                { bg: 'rgba(245,158,11,0.85)',  border: 'rgba(245,158,11,1)'  },  // amber
                { bg: 'rgba(239,68,68,0.85)',   border: 'rgba(239,68,68,1)'   },  // red
                { bg: 'rgba(139,92,246,0.85)',  border: 'rgba(139,92,246,1)'  },  // violet
            ];
            var bgColors    = topProducts.map(function(_, i) { return barColors[i % barColors.length].bg; });
            var bdColors    = topProducts.map(function(_, i) { return barColors[i % barColors.length].border; });
            chartTopProducts = new Chart(ctxProd, {
                plugins: [ChartDataLabels],
                type: 'bar',
                data: {
                    labels: topProducts.map(function(p) { return (p.product_name || '—').slice(0, 22); }),
                    datasets: [{
                        label: 'Qty sold',
                        data: topProducts.map(function(p) { return p.quantity_sold; }),
                        backgroundColor: bgColors,
                        borderColor: bdColors,
                        borderWidth: 2,
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(148,163,184,0.15)' },
                            ticks: { precision: 0 }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                maxRotation: 30,
                                minRotation: 0,
                                font: { size: 11 }
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        datalabels: {
                            display: true,
                            anchor: 'end',
                            align: 'end',
                            clamp: false,
                            clip: false,
                            offset: 3,
                            color: '#1e293b',
                            backgroundColor: 'rgba(255,255,255,0.92)',
                            borderRadius: 4,
                            padding: { left: 5, right: 5, top: 2, bottom: 2 },
                            font: { weight: 'bold', size: 11 },
                            formatter: function(value) {
                                return Math.round(parseFloat(value)).toLocaleString() + ' sold';
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return ' ' + ctx.parsed.y.toLocaleString() + ' units sold';
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    function statusBadge(status) {
        var s = (status || 'completed').toLowerCase();
        var cls = 'px-2 py-0.5 rounded text-xs font-medium ';
        if (s === 'completed') return '<span class="' + cls + ' bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">Completed</span>';
        if (s === 'voided') return '<span class="' + cls + ' bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Voided</span>';
        if (s === 'refunded') return '<span class="' + cls + ' bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">Refunded</span>';
        return '<span class="' + cls + ' bg-slate-100 text-slate-600 dark:bg-slate-600 dark:text-slate-300">' + escapeHtml(status) + '</span>';
    }

    var txCurrentList = [];
    var txPage = 1;
    var txPerPage = 25;

    function filterTransactions() {
        if (!lastSummaryData) return;
        var q = (document.getElementById('transactions-search').value || '').toLowerCase().trim();
        var tx = lastSummaryData.recent_transactions || [];
        txCurrentList = q ? tx.filter(function(t) {
            return (t.or_number || '').toLowerCase().indexOf(q) >= 0 ||
                (t.branch_name || '').toLowerCase().indexOf(q) >= 0 ||
                (t.cashier_name || '').toLowerCase().indexOf(q) >= 0;
        }) : tx;
        txPage = 1;
        renderTransactionsRows(txCurrentList);
    }

    function renderTransactionsRows(list) {
        txCurrentList = list;
        var total = list.length;
        var totalPages = Math.max(1, Math.ceil(total / txPerPage));
        if (txPage > totalPages) txPage = totalPages;
        var start = (txPage - 1) * txPerPage;
        var end = Math.min(start + txPerPage, total);
        var page = list.slice(start, end);

        var txBody = document.getElementById('summary-transactions-tbody');
        txBody.innerHTML = page.length === 0
            ? '<tr><td colspan="9" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">No transactions match.</td></tr>'
            : page.map(function(t) {
                var date = t.created_at ? new Date(t.created_at).toLocaleString() : '—';
                var pay = (t.payment_method || 'cash').charAt(0).toUpperCase() + (t.payment_method || 'cash').slice(1);
                return '<tr class="hover:bg-slate-50 dark:hover:bg-darkmode-600/40 transition-colors">'
                    + '<td class="py-3 pr-4 font-medium text-slate-800 dark:text-slate-100 whitespace-nowrap">' + escapeHtml(t.or_number || '—') + '</td>'
                    + '<td class="py-3 pr-4 text-slate-600 dark:text-slate-400">' + escapeHtml(t.branch_name || '—') + '</td>'
                    + '<td class="py-3 pr-4 text-slate-600 dark:text-slate-400">' + escapeHtml(t.cashier_name || '—') + '</td>'
                    + '<td class="py-3 pr-4 text-slate-600 dark:text-slate-400">' + escapeHtml(pay) + '</td>'
                    + '<td class="py-3 pr-4">' + statusBadge(t.status) + '</td>'
                    + '<td class="py-3 pr-4 text-right font-semibold text-slate-800 dark:text-slate-100 whitespace-nowrap">₱' + formatMoney(t.total || 0) + '</td>'
                    + '<td class="py-3 pr-4 text-right text-slate-500 dark:text-slate-400 whitespace-nowrap">₱' + formatMoney(t.vat_amount || 0) + '</td>'
                    + '<td class="py-3 pr-4 text-right text-slate-500 dark:text-slate-400 whitespace-nowrap">₱' + formatMoney(t.discount_amount || 0) + '</td>'
                    + '<td class="py-3 text-sm text-slate-500 dark:text-slate-400 whitespace-nowrap">' + date + '</td>'
                    + '</tr>';
            }).join('');

        // Update pagination info
        var infoEl = document.getElementById('tx-page-info');
        if (total === 0) {
            infoEl.textContent = 'No transactions';
        } else {
            infoEl.textContent = 'Showing ' + (start + 1) + '–' + end + ' of ' + total.toLocaleString() + ' transactions';
        }

        // Prev / Next buttons
        var prevBtn = document.getElementById('tx-prev');
        var nextBtn = document.getElementById('tx-next');
        prevBtn.disabled = txPage <= 1;
        nextBtn.disabled = txPage >= totalPages;

        // Page number buttons (show up to 7)
        var btnsEl = document.getElementById('tx-page-btns');
        var pages = [];
        if (totalPages <= 7) {
            for (var i = 1; i <= totalPages; i++) pages.push(i);
        } else {
            pages = [1];
            var s = Math.max(2, txPage - 2), e = Math.min(totalPages - 1, txPage + 2);
            if (s > 2) pages.push('…');
            for (var j = s; j <= e; j++) pages.push(j);
            if (e < totalPages - 1) pages.push('…');
            pages.push(totalPages);
        }
        btnsEl.innerHTML = pages.map(function(p) {
            if (p === '…') return '<span class="px-1 text-slate-400">…</span>';
            var active = p === txPage;
            return '<button type="button" data-page="' + p + '" class="rounded-lg min-w-[32px] px-2 py-1.5 text-sm font-medium transition-colors touch-manipulation '
                + (active ? 'bg-primary text-white' : 'border border-slate-200 dark:border-darkmode-500 hover:bg-slate-50 dark:hover:bg-darkmode-600 text-slate-700 dark:text-slate-300')
                + '">' + p + '</button>';
        }).join('');
    }

    function exportTableToCSV(tbodyId, filename) {
        var tbody = document.getElementById(tbodyId);
        var table = tbody && tbody.closest('table');
        if (!table) return;
        var rows = table.querySelectorAll('tr');
        var csv = [];
        for (var i = 0; i < rows.length; i++) {
            var cells = rows[i].querySelectorAll('td, th');
            var row = [];
            for (var j = 0; j < cells.length; j++) row.push('"' + (cells[j].textContent || '').replace(/"/g, '""') + '"');
            csv.push(row.join(','));
        }
        var a = document.createElement('a');
        a.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv.join('\n'));
        a.download = (filename || 'export') + '.csv';
        a.click();
    }

    function getBranchIdFromUrl() {
        var p = new URLSearchParams(window.location.search);
        var b = p.get('branch_id');
        return (b !== null && b !== undefined && b !== '') ? b : null;
    }

    function applySummaryBackLink() {
        var branchId = getBranchIdFromUrl();
        var backLink = document.getElementById('summary-back-link');
        var backLabel = document.getElementById('summary-back-label');
        if (!backLink) return;
        if (branchId) {
            backLink.href = dashboardBase + '/branches?company_id=' + encodeURIComponent(companyId);
            backLink.setAttribute('aria-label', 'Back to Branches');
            backLink.setAttribute('title', 'Back to Branches');
            if (backLabel) backLabel.textContent = 'Back to Branches';
        } else {
            backLink.href = dashboardBase + '/companies';
            backLink.setAttribute('aria-label', 'Back to Companies');
            backLink.setAttribute('title', 'Back to Companies');
            if (backLabel) backLabel.textContent = 'Back to Companies';
        }
    }

    function loadSummary() {
        var from = document.getElementById('summary-date-from').value;
        var to = document.getElementById('summary-date-to').value;
        if (!from || !to) { defaultDates(); from = document.getElementById('summary-date-from').value; to = document.getElementById('summary-date-to').value; }
        loadingEl.classList.remove('hidden');
        contentEl.classList.add('hidden');
        errorEl.classList.add('hidden');
        var url = apiBase + '/companies/' + companyId + '/summary?date_from=' + encodeURIComponent(from) + '&date_to=' + encodeURIComponent(to);
        var branchId = getBranchIdFromUrl();
        if (branchId) url += '&branch_id=' + encodeURIComponent(branchId);
        axios.get(url, authHeaders())
            .then(function(r) {
                var d = r.data && r.data.data ? r.data.data : {};
                lastSummaryData = d;
                var sum = d.summary || {};

                document.getElementById('header-total-sales').textContent = '₱' + formatMoney(sum.total_sales || 0);
                document.getElementById('header-transaction-count').textContent = (sum.transaction_count || 0).toLocaleString();
                document.getElementById('header-branches-count').textContent = (sum.branches_count || 0).toLocaleString();
                document.getElementById('header-low-stock').textContent = (sum.low_stock_alerts ?? 0).toLocaleString();
                document.getElementById('header-expiring-soon').textContent = (sum.expiring_soon_count ?? 0).toLocaleString();

                var revPct = sum.revenue_change_pct != null ? sum.revenue_change_pct : 0;
                var revEl = document.getElementById('kpi-revenue-change');
                if (revEl) {
                    if (revPct > 0) revEl.innerHTML = '<span class="text-emerald-600">↑ ' + revPct + '%</span>';
                    else if (revPct < 0) revEl.innerHTML = '<span class="text-red-600">↓ ' + Math.abs(revPct) + '%</span>';
                    else revEl.textContent = '0%';
                }
                document.getElementById('kpi-total-sales').textContent = '₱' + formatMoney(sum.total_sales || 0);
                document.getElementById('kpi-avg-tx').textContent = '₱' + formatMoney(sum.avg_transaction_value || 0);
                document.getElementById('kpi-products-sold').textContent = (sum.total_products_sold || 0).toLocaleString();
                document.getElementById('kpi-discount').textContent = '₱' + formatMoney(sum.total_discount || 0);
                document.getElementById('kpi-vat').textContent = '₱' + formatMoney(sum.total_vat || 0);
                document.getElementById('kpi-net-sales').textContent = '₱' + formatMoney(sum.net_sales || 0);

                var branches = d.branches || [];
                var summaryUrlFn = window.summaryUrl || function(id) { return '#'; };
                var branchNoticeEl = document.getElementById('summary-branch-notice');
                var branchNameEl = document.getElementById('summary-branch-name');
                var viewAllLink = document.getElementById('summary-view-all-branches');
                if (branchId && branches.length > 0) {
                    branchNameEl.textContent = branches[0].name || 'Branch';
                    viewAllLink.href = summaryUrlFn(companyId);
                    branchNoticeEl.classList.remove('hidden');
                } else {
                    branchNoticeEl.classList.add('hidden');
                }
                var tbody = document.getElementById('summary-branches-tbody');
                tbody.innerHTML = branches.length === 0
                    ? '<tr><td colspan="7" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">No branches or no sales in this period.</td></tr>'
                    : branches.map(function(b) {
                        var cashCount = b.cashiers_count || 0;
                        var cashiersPill = cashCount > 0
                            ? '<a href="' + escapeHtml(summaryUrlFn(companyId) + (b.id ? '?branch_id=' + b.id : '')) + '" class="inline-flex items-center gap-1 rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-semibold text-primary hover:bg-primary/20 transition-colors">' + cashCount + ' cashier' + (cashCount !== 1 ? 's' : '') + '</a>'
                            : '<span class="text-slate-400">—</span>';
                        var topProd = b.top_product_name
                            ? '<span class="inline-flex items-center rounded bg-slate-100 dark:bg-darkmode-500 px-2 py-0.5 text-xs text-slate-700 dark:text-slate-300 max-w-[140px] truncate" title="' + escapeHtml(b.top_product_name) + '">' + escapeHtml(b.top_product_name) + '</span>'
                            : '<span class="text-slate-400">—</span>';
                        return '<tr class="cursor-pointer hover:bg-slate-50 dark:hover:bg-darkmode-600/50" data-branch-id="' + (b.id || '') + '">'
                            + '<td class="py-3 font-medium text-slate-800 dark:text-slate-100">' + escapeHtml(b.name || '—') + '</td>'
                            + '<td class="py-3 text-sm text-slate-500 dark:text-slate-400">' + escapeHtml(b.address || '—') + '</td>'
                            + '<td class="py-3 text-right">' + (b.transaction_count || 0).toLocaleString() + '</td>'
                            + '<td class="py-3 text-right font-semibold text-slate-800 dark:text-slate-100">₱' + formatMoney(b.total_sales || 0) + '</td>'
                            + '<td class="py-3 text-right text-slate-600 dark:text-slate-400">₱' + formatMoney(b.avg_transaction_value || 0) + '</td>'
                            + '<td class="py-3">' + topProd + '</td>'
                            + '<td class="py-3 text-right">' + cashiersPill + '</td>'
                            + '</tr>';
                    }).join('');

                var terminals = d.terminals || [];
                var terminalsTbody = document.getElementById('summary-terminals-tbody');
                if (terminalsTbody) {
                    if (terminals.length === 0) {
                        terminalsTbody.innerHTML = '<tr><td colspan="4" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">No terminals or no sales in this period.</td></tr>';
                    } else {
                        var byBranch = {};
                        terminals.forEach(function(t) {
                            var bid = t.branch_id ?? ('_' + (t.branch_name || ''));
                            if (!byBranch[bid]) byBranch[bid] = { name: t.branch_name || '—', rows: [] };
                            byBranch[bid].rows.push(t);
                        });
                        var branchIds = Object.keys(byBranch).sort(function(a, b) {
                            var na = byBranch[a].name, nb = byBranch[b].name;
                            return (na || '').localeCompare(nb || '');
                        });
                        var grandTx = 0, grandSales = 0;
                        var html = [];
                        branchIds.forEach(function(bid) {
                            var group = byBranch[bid];
                            var branchName = group.name;
                            var rows = group.rows;
                            var subTx = 0, subSales = 0;
                            html.push('<tr class="bg-slate-100/80 dark:bg-darkmode-600/50 border-y border-slate-200 dark:border-darkmode-500">'
                                + '<td colspan="4" class="py-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">' + escapeHtml(branchName) + '</td></tr>');
                            rows.forEach(function(t) {
                                subTx += t.transaction_count || 0;
                                subSales += parseFloat(t.total_sales) || 0;
                                var terminalLabel = t.name || t.code || ('Terminal #' + t.id);
                                if (t.code) terminalLabel += ' <span class="text-slate-400 dark:text-slate-500 font-normal">(' + escapeHtml(t.code) + ')</span>';
                                html.push('<tr class="hover:bg-slate-50/80 dark:hover:bg-darkmode-600/50 transition-colors" data-terminal-row="1">'
                                    + '<td class="py-2.5 pl-3 pr-2 font-medium text-slate-800 dark:text-slate-100">' + terminalLabel + '</td>'
                                    + '<td class="py-2.5 px-2 text-right tabular-nums">' + (t.transaction_count || 0).toLocaleString() + '</td>'
                                    + '<td class="py-2.5 px-2 text-right font-semibold text-slate-800 dark:text-slate-100 tabular-nums">₱' + formatMoney(t.total_sales || 0) + '</td>'
                                    + '<td class="py-2.5 pr-3 pl-2 text-right text-slate-600 dark:text-slate-400 tabular-nums">₱' + formatMoney(t.avg_transaction_value || 0) + '</td>'
                                    + '</tr>');
                            });
                            grandTx += subTx;
                            grandSales += subSales;
                            var subAvg = subTx > 0 ? subSales / subTx : 0;
                            html.push('<tr class="bg-slate-50/80 dark:bg-darkmode-700/40 font-medium">'
                                + '<td class="py-2 pl-3 pr-2 text-slate-600 dark:text-slate-400 text-right">Subtotal</td>'
                                + '<td class="py-2 px-2 text-right tabular-nums text-slate-800 dark:text-slate-200">' + subTx.toLocaleString() + '</td>'
                                + '<td class="py-2 px-2 text-right tabular-nums text-slate-800 dark:text-slate-200">₱' + formatMoney(subSales) + '</td>'
                                + '<td class="py-2 pr-3 pl-2 text-right tabular-nums text-slate-600 dark:text-slate-400">₱' + formatMoney(subAvg) + '</td>'
                                + '</tr>');
                        });
                        var grandAvg = grandTx > 0 ? grandSales / grandTx : 0;
                        html.push('<tr class="border-t-2 border-slate-300 dark:border-darkmode-500 bg-slate-100/80 dark:bg-darkmode-600/50 font-semibold">'
                            + '<td class="py-3 pl-3 pr-2 text-slate-800 dark:text-slate-100 text-right">Grand total</td>'
                            + '<td class="py-3 px-2 text-right tabular-nums text-slate-800 dark:text-slate-100">' + grandTx.toLocaleString() + '</td>'
                            + '<td class="py-3 px-2 text-right tabular-nums text-primary dark:text-primary">₱' + formatMoney(grandSales) + '</td>'
                            + '<td class="py-3 pr-3 pl-2 text-right tabular-nums text-slate-700 dark:text-slate-300">₱' + formatMoney(grandAvg) + '</td>'
                            + '</tr>');
                        terminalsTbody.innerHTML = html.join('');
                    }
                }

                var inv = d.inventory_summary || {};
                var low = inv.low_stock_count ?? 0, exp = inv.expiring_soon_count ?? 0, out = inv.out_of_stock_count ?? 0;
                document.getElementById('widget-inventory-subtitle').textContent = 'Low stock: ' + low + ' · Expiring: ' + exp + ' · Out: ' + out;
                document.getElementById('widget-inventory-center').textContent = (low + exp + out).toLocaleString();

                var topList = d.top_5_products || [];
                document.getElementById('widget-top-products-center').textContent = topList.length.toString();
                var listEl = document.getElementById('widget-top-products-list');
                listEl.innerHTML = topList.length === 0 ? '<li class="text-slate-500">No data</li>' : topList.map(function(p) {
                    return '<li class="flex justify-between gap-2"><span class="truncate">' + escapeHtml(p.product_name || '—') + '</span><span class="font-medium flex-shrink-0">' + (p.quantity_sold || 0) + ' sold</span></li>';
                }).join('');

                var bir = d.bir_summary || {};
                document.getElementById('widget-vat-month').textContent = 'VAT this month ₱' + formatMoney(bir.vat_this_month || 0);
                var orEl = document.getElementById('widget-or-range');
                var orRanges = bir.or_ranges || [];
                if (orRanges.length === 0) {
                    orEl.innerHTML = '<span class="text-slate-400">No OR ranges configured</span>';
                } else {
                    orEl.innerHTML = orRanges.map(function(r) {
                        return '<div class="flex items-center justify-between gap-2">'
                            + '<span class="truncate text-sm font-semibold text-slate-700 dark:text-slate-300">' + escapeHtml(r.branch_name || 'Branch') + '</span>'
                            + '<span class="flex-shrink-0 rounded bg-primary/10 px-2.5 py-0.5 text-sm font-semibold text-primary dark:bg-primary/20 dark:text-primary">' + escapeHtml(r.or_from || '—') + ' – ' + escapeHtml(r.or_to || '—') + '</span>'
                            + '</div>';
                    }).join('');
                }

                var ua = d.user_activity || {};
                document.getElementById('widget-active-cashiers').textContent = (ua.active_cashiers_today ?? 0) + ' active';
                document.getElementById('widget-most-active').textContent = 'Most active: ' + (ua.most_active_cashier_today || '—');

                renderWidgetCharts(d);

                renderTransactionsRows(d.recent_transactions || []);

                renderCharts(d);

                loadingEl.classList.add('hidden');
                contentEl.classList.remove('hidden');
            })
            .catch(function(err) {
                loadingEl.classList.add('hidden');
                contentEl.classList.add('hidden');
                errorEl.classList.remove('hidden');
                errorText.textContent = (err.response && err.response.data && err.response.data.message) || 'Failed to load summary.';
            });
    }

    defaultDates();
    var summaryPresetEl = document.getElementById('summary-preset');
    summaryPresetEl.value = 'month';
    if (typeof TomSelect !== 'undefined') new TomSelect(summaryPresetEl, { plugins: { dropdown_input: {} }, placeholder: 'Preset', dropdownParent: 'body' });
    summaryPresetEl.addEventListener('change', function() {
        var v = this.value;
        if (v === 'today') presetToday();
        else if (v === 'week') presetWeek();
        else if (v === 'month') presetMonth();
        else if (v === 'year') presetYear();
    });
    document.getElementById('summary-apply-btn').addEventListener('click', function() {
        loadSummary();
    });
    var txSearch = document.getElementById('transactions-search');
    if (txSearch) txSearch.addEventListener('input', function() { filterTransactions(); });
    document.getElementById('tx-prev').addEventListener('click', function() {
        if (txPage > 1) { txPage--; renderTransactionsRows(txCurrentList); }
    });
    document.getElementById('tx-next').addEventListener('click', function() {
        var totalPages = Math.max(1, Math.ceil(txCurrentList.length / txPerPage));
        if (txPage < totalPages) { txPage++; renderTransactionsRows(txCurrentList); }
    });
    document.getElementById('tx-page-btns').addEventListener('click', function(e) {
        var btn = e.target.closest('button[data-page]');
        if (btn) { txPage = parseInt(btn.getAttribute('data-page'), 10); renderTransactionsRows(txCurrentList); }
    });
    document.getElementById('branch-export-excel').addEventListener('click', function() { exportTableToCSV('summary-branches-tbody', 'branch-breakdown'); });
    document.getElementById('terminals-export-excel').addEventListener('click', function() { exportTableToCSV('summary-terminals-tbody', 'terminals-sales'); });
    document.getElementById('transactions-export-excel').addEventListener('click', function() { exportTableToCSV('summary-transactions-tbody', 'recent-transactions'); });
    document.getElementById('summary-branches-tbody').addEventListener('click', function(e) {
        var row = e.target.closest('tr[data-branch-id]');
        if (row && row.getAttribute('data-branch-id')) {
            var bid = row.getAttribute('data-branch-id');
            if (bid) window.location.href = window.summaryUrl(companyId) + '?branch_id=' + bid;
        }
    });
    applySummaryBackLink();
    loadSummary();
})();
</script>
@endpush
