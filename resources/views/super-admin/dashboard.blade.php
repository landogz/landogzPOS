@extends('super-admin.layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

@section('content')
    {{-- Header + date range + last updated --}}
    <div class="intro-y mt-6 sm:mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-col gap-1 sm:gap-2">
            <h1 class="text-xl sm:text-2xl font-semibold text-slate-800 dark:text-slate-100 tracking-tight" id="dashboard-welcome">Dashboard</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400" id="dashboard-subtitle">Overview of your key metrics.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-xs text-slate-400 dark:text-slate-500" id="dashboard-last-updated">—</span>
            <div class="flex rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 p-0.5" role="group" aria-label="Date range">
                <button type="button" id="dashboard-period-today" class="dashboard-period-btn rounded-md px-3 py-2 text-sm font-medium transition min-h-[44px] sm:min-h-0 bg-primary text-white border-transparent">Today</button>
                <button type="button" id="dashboard-period-week" class="dashboard-period-btn rounded-md px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-darkmode-600 transition min-h-[44px] sm:min-h-0">This Week</button>
                <button type="button" id="dashboard-period-month" class="dashboard-period-btn rounded-md px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-darkmode-600 transition min-h-[44px] sm:min-h-0">This Month</button>
            </div>
        </div>
    </div>

    {{-- KPI cards: stronger tint, left border, trend as green pill --}}
    <div class="intro-y mt-6 grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="rounded-2xl border-l-4 border-emerald-500 border border-emerald-200/60 dark:border-emerald-800/50 bg-emerald-50/80 dark:bg-emerald-900/20 shadow-sm p-5 sm:p-6 transition hover:shadow-md">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-500/25 text-emerald-600 dark:text-emerald-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <span id="sales-trend" class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-sm font-semibold text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300 whitespace-nowrap shrink-0">—</span>
                </div>
                <div class="mt-4">
                    <div class="text-3xl sm:text-4xl font-bold text-slate-800 dark:text-slate-100" id="sales-today">—</div>
                    <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">Sales</div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="rounded-2xl border-l-4 border-sky-500 border border-sky-200/60 dark:border-sky-800/50 bg-sky-50/80 dark:bg-sky-900/20 shadow-sm p-5 sm:p-6 transition hover:shadow-md">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sky-500/25 text-sky-600 dark:text-sky-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M12 8v8"/><path d="M8 12h8"/></svg>
                    </div>
                    <span id="transaction-trend" class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-sm font-semibold text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300 whitespace-nowrap shrink-0">—</span>
                </div>
                <div class="mt-4">
                    <div class="text-3xl sm:text-4xl font-bold text-slate-800 dark:text-slate-100" id="transaction-count">—</div>
                    <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">Transactions</div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="rounded-2xl border-l-4 border-amber-500 border border-amber-200/60 dark:border-amber-800/50 bg-amber-50/80 dark:bg-amber-900/20 shadow-sm p-5 sm:p-6 transition hover:shadow-md">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-500/25 text-amber-600 dark:text-amber-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-3xl sm:text-4xl font-bold text-slate-800 dark:text-slate-100" id="low-stock-count">—</div>
                    <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">Low stock alerts</div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="rounded-2xl border-l-4 border-rose-500 border border-rose-200/60 dark:border-rose-800/50 bg-rose-50/80 dark:bg-rose-900/20 shadow-sm p-5 sm:p-6 transition hover:shadow-md">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-500/25 text-rose-600 dark:text-rose-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-3xl sm:text-4xl font-bold text-slate-800 dark:text-slate-100" id="expiring-count">—</div>
                    <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">Expiring soon</div>
                </div>
            </div>
        </div>
    </div>

    <div class="intro-y mt-8 grid grid-cols-12 gap-6">
        {{-- Main column --}}
        <div class="col-span-12 2xl:col-span-9">
            {{-- Branch overview (super_admin / admin only) --}}
            <div id="dashboard-branch-section" class="hidden">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-base font-semibold text-slate-800 dark:text-slate-100 sm:text-lg">Branch overview</h2>
                    <div class="flex flex-wrap items-center gap-2">
                        <div class="flex rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 p-0.5" role="group" aria-label="View">
                            <button type="button" id="dashboard-branch-view-list" class="dashboard-branch-view-btn rounded-md px-3 py-2 text-sm font-medium transition min-h-[44px] sm:min-h-0 bg-primary text-white border-transparent">List</button>
                            <button type="button" id="dashboard-branch-view-chart" class="dashboard-branch-view-btn rounded-md px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-darkmode-600 transition min-h-[44px] sm:min-h-0">Chart</button>
                        </div>
                        <button type="button" id="dashboard-branch-export-csv" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition min-h-[44px] sm:min-h-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Export CSV
                        </button>
                        <a href="{{ route('dashboard.branches') }}" class="text-sm font-medium text-primary hover:underline">View all</a>
                    </div>
                </div>
                <div id="dashboard-branch-chart-wrap" class="mt-4 rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm p-5 hidden" aria-hidden="true">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-3">Sales by branch — hover for full name</p>
                    <div id="dashboard-branch-chart" class="space-y-3"></div>
                </div>
                <div id="dashboard-branch-list-wrap" class="mt-4 rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm overflow-hidden">
                    <div id="dashboard-branch-list" class="divide-y divide-slate-100 dark:divide-darkmode-600">
                        <div class="px-5 py-8 text-center text-slate-500 dark:text-slate-400 text-sm">Loading…</div>
                    </div>
                </div>
            </div>

            {{-- Official Store (map) --}}
            <div class="mt-6 sm:mt-8">
                <h2 class="text-base font-semibold text-slate-800 dark:text-slate-100 sm:text-lg">Official Store</h2>
                <div class="mt-3 w-full">
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 z-10 flex items-center pl-3 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </span>
                        <input type="text" id="dashboard-store-filter-city" placeholder="Filter by city or branch name" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 py-2.5 pl-10 pr-4 text-sm text-slate-700 dark:text-slate-300 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition dark:placeholder-slate-500" aria-label="Filter stores by city">
                    </div>
                </div>
                <div class="mt-4 rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm p-5">
                    <p class="text-sm text-slate-600 dark:text-slate-400" id="dashboard-official-store-desc">
                        <span id="dashboard-store-count">—</span> Official stores — click a marker for location details.
                    </p>
                    <div id="dashboard-official-store-map" class="mt-5 h-[420px] w-full rounded-lg overflow-hidden bg-slate-200 dark:bg-darkmode-600 z-0" data-lat="14.5995" data-long="120.9842"></div>
                    <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
                        <span class="inline-flex items-center gap-1.5"><span class="h-3 w-3 rounded-full bg-primary shrink-0"></span> Branch location</span>
                        <span>Map data © OpenStreetMap contributors</span>
                    </div>
                </div>
            </div>

            {{-- Low stock alerts --}}
            <div class="mt-6 sm:mt-8">
                <div class="flex h-10 items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-800 dark:text-slate-100 sm:text-lg">Low stock alerts</h2>
                    <a href="{{ route('dashboard.inventory') }}" class="text-sm font-medium text-primary hover:underline">View inventory</a>
                </div>
                <div class="mt-4 rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm overflow-hidden">
                    <div id="dashboard-low-stock-list" class="divide-y divide-slate-100 dark:divide-darkmode-600">
                        <div class="px-5 py-8 text-center text-slate-500 dark:text-slate-400 text-sm">Loading…</div>
                    </div>
                </div>
            </div>

            {{-- Expiring soon --}}
            <div class="mt-6 sm:mt-8">
                <div class="flex h-10 items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-800 dark:text-slate-100 sm:text-lg">Expiring soon</h2>
                    <a href="{{ route('dashboard.reports.expiring-products') }}" class="text-sm font-medium text-primary hover:underline">View report</a>
                </div>
                <div class="mt-4 rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm overflow-hidden">
                    <div id="dashboard-expiring-list" class="divide-y divide-slate-100 dark:divide-darkmode-600">
                        <div class="px-5 py-8 text-center text-slate-500 dark:text-slate-400 text-sm">Loading…</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-span-12 mt-6 2xl:col-span-3 2xl:mt-0 2xl:pl-6 2xl:border-l 2xl:border-slate-200 dark:2xl:border-darkmode-600">
            {{-- Quick actions: vertical list to avoid overlap --}}
            <div class="rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm p-5">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-300">Quick actions</h2>
                <div class="mt-4 flex flex-col gap-2">
                    <a href="{{ route('dashboard.transactions') }}" class="flex items-center gap-3 rounded-xl border-2 border-primary/30 bg-transparent px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-primary/10 hover:border-primary/50 hover:text-primary transition min-h-[48px] touch-manipulation w-full">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border-2 border-primary/40 text-primary"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M12 8v8"/><path d="M8 12h8"/></svg></span>
                        <span class="truncate">Transactions</span>
                    </a>
                    <a href="{{ route('dashboard.inventory') }}" class="flex items-center gap-3 rounded-xl border-2 border-primary/30 bg-transparent px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-primary/10 hover:border-primary/50 hover:text-primary transition min-h-[48px] touch-manipulation w-full">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border-2 border-primary/40 text-primary"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></span>
                        <span class="truncate">Inventory</span>
                    </a>
                    <a href="{{ route('dashboard.branches') }}" class="flex items-center gap-3 rounded-xl border-2 border-primary/30 bg-transparent px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-primary/10 hover:border-primary/50 hover:text-primary transition min-h-[48px] touch-manipulation w-full">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border-2 border-primary/40 text-primary"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span>
                        <span class="truncate">Branches</span>
                    </a>
                    <a href="{{ route('dashboard.reports.z-reading') }}" class="flex items-center gap-3 rounded-xl border-2 border-primary/30 bg-transparent px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-primary/10 hover:border-primary/50 hover:text-primary transition min-h-[48px] touch-manipulation w-full">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border-2 border-primary/40 text-primary"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></span>
                        <span class="truncate">Z-Reading</span>
                    </a>
                    <a href="{{ route('dashboard.reports.sales') }}" class="flex items-center gap-3 rounded-xl border-2 border-primary/30 bg-transparent px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-primary/10 hover:border-primary/50 hover:text-primary transition min-h-[48px] touch-manipulation w-full">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border-2 border-primary/40 text-primary"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></span>
                        <span class="truncate">Sales report</span>
                    </a>
                </div>
            </div>
            {{-- Logged in as (muted below actions) --}}
            <div class="mt-4 rounded-xl border border-slate-200/80 dark:border-darkmode-600 bg-slate-50/50 dark:bg-darkmode-700/50 px-4 py-3">
                <p class="text-xs text-slate-500 dark:text-slate-400">Logged in as</p>
                <p class="mt-0.5 text-sm font-medium text-slate-600 dark:text-slate-300" id="dashboard-role-label">—</p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function() {
    var apiBase = '{{ url("/api/v1") }}';
    var dashboardBase = '{{ url("/dashboard") }}';
    var token = localStorage.getItem('super_admin_token');
    if (!token) {
        window.location.href = '{{ route("dashboard.login") }}';
        return;
    }
    var headers = { headers: { Authorization: 'Bearer ' + token, Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } };

    function fmtMoney(n) {
        if (n == null || isNaN(n)) return '—';
        var x = parseFloat(n);
        return '₱' + x.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    function escapeHtml(s) {
        if (!s) return '';
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }
    function formatQuantity(n) {
        if (n == null || n === '' || isNaN(Number(n))) return '—';
        var x = Number(n);
        if (x === Math.floor(x)) return String(Math.floor(x));
        return Number(x.toFixed(2));
    }

    var roleLabels = { super_admin: 'Super Admin', admin: 'Admin', manager: 'Manager', pharmacist: 'Pharmacist', cashier: 'Cashier' };
    var currentPeriod = 'today';
    function trendHtml(pct) {
        if (pct == null || isNaN(pct)) return '—';
        var up = pct >= 0;
        var sign = up ? '+' : '';
        var pillClass = up ? 'rounded-full bg-emerald-100 px-2.5 py-1 text-sm font-semibold text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' : 'rounded-full bg-rose-100 px-2.5 py-1 text-sm font-semibold text-rose-700 dark:bg-rose-900/50 dark:text-rose-300';
        return '<span class="inline-flex items-center gap-0.5 ' + pillClass + '">' + (up ? '\u2191' : '\u2193') + ' ' + sign + pct + '%</span>';
    }
    function formatLastUpdated(iso) {
        if (!iso) return '—';
        var d = new Date(iso);
        var now = new Date();
        var diffMs = now - d;
        var diffMins = Math.floor(diffMs / 60000);
        if (diffMins < 1) return 'Just now';
        if (diffMins < 60) return diffMins + ' min' + (diffMins !== 1 ? 's' : '') + ' ago';
        var diffHrs = Math.floor(diffMins / 60);
        return diffHrs + ' hr' + (diffHrs !== 1 ? 's' : '') + ' ago';
    }
    function loadSummary() {
        axios.get(apiBase + '/dashboard/summary', { params: { period: currentPeriod }, headers: headers.headers })
            .then(function(r) {
                var d = r.data && r.data.data ? r.data.data : r.data;
                if (!d) return;
                document.getElementById('sales-today').textContent = fmtMoney(d.sales != null ? d.sales : d.sales_today);
                document.getElementById('transaction-count').textContent = d.transaction_count != null ? d.transaction_count : '—';
                document.getElementById('low-stock-count').textContent = d.low_stock_count != null ? d.low_stock_count : '—';
                document.getElementById('expiring-count').textContent = d.expiring_soon_count != null ? d.expiring_soon_count : '—';
                var salesTrendEl = document.getElementById('sales-trend');
                var transTrendEl = document.getElementById('transaction-trend');
                if (salesTrendEl) salesTrendEl.innerHTML = trendHtml(d.sales_trend_pct);
                if (transTrendEl) transTrendEl.innerHTML = trendHtml(d.transaction_trend_pct);
                if (d.last_updated) {
                    var lu = document.getElementById('dashboard-last-updated');
                    if (lu) lu.textContent = 'Last updated: ' + formatLastUpdated(d.last_updated);
                }
                var role = (d.role || '').replace(/_/g, ' ');
                role = role ? role.charAt(0).toUpperCase() + role.slice(1) : (roleLabels[d.role] || '—');
                var roleBadge = document.getElementById('super-admin-role-badge');
                if (roleBadge) roleBadge.textContent = role;
                var roleLabel = document.getElementById('dashboard-role-label');
                if (roleLabel) roleLabel.textContent = role;
            })
            .catch(function() {
                document.getElementById('sales-today').textContent = '—';
                document.getElementById('transaction-count').textContent = '—';
                document.getElementById('low-stock-count').textContent = '—';
                document.getElementById('expiring-count').textContent = '—';
            });
    }
    loadSummary();
    document.getElementById('dashboard-period-today').addEventListener('click', function() { currentPeriod = 'today'; document.querySelectorAll('.dashboard-period-btn').forEach(function(btn) { btn.classList.remove('bg-primary', 'text-white'); btn.classList.add('text-slate-600', 'dark:text-slate-400'); }); this.classList.add('bg-primary', 'text-white'); this.classList.remove('text-slate-600', 'dark:text-slate-400'); loadSummary(); loadBranchOverview(); });
    document.getElementById('dashboard-period-week').addEventListener('click', function() { currentPeriod = 'week'; document.querySelectorAll('.dashboard-period-btn').forEach(function(btn) { btn.classList.remove('bg-primary', 'text-white'); btn.classList.add('text-slate-600', 'dark:text-slate-400'); }); this.classList.add('bg-primary', 'text-white'); this.classList.remove('text-slate-600', 'dark:text-slate-400'); loadSummary(); loadBranchOverview(); });
    document.getElementById('dashboard-period-month').addEventListener('click', function() { currentPeriod = 'month'; document.querySelectorAll('.dashboard-period-btn').forEach(function(btn) { btn.classList.remove('bg-primary', 'text-white'); btn.classList.add('text-slate-600', 'dark:text-slate-400'); }); this.classList.add('bg-primary', 'text-white'); this.classList.remove('text-slate-600', 'dark:text-slate-400'); loadSummary(); loadBranchOverview(); });

    var branchOverviewData = [];
    function loadBranchOverview() {
        axios.get(apiBase + '/dashboard/branch-overview', { params: { period: currentPeriod }, headers: headers.headers })
            .then(function(r) {
                var list = (r.data && r.data.data) ? r.data.data : (Array.isArray(r.data) ? r.data : []);
                branchOverviewData = list || [];
                var el = document.getElementById('dashboard-branch-list');
                var section = document.getElementById('dashboard-branch-section');
                var chartWrap = document.getElementById('dashboard-branch-chart-wrap');
                var chartEl = document.getElementById('dashboard-branch-chart');
                if (!list || list.length === 0) {
                    section.classList.add('hidden');
                    return;
                }
                section.classList.remove('hidden');
                var sorted = list.slice().sort(function(a, b) { return (b.sales_today || 0) - (a.sales_today || 0); });
                var maxSales = Math.max.apply(null, sorted.map(function(b) { return b.sales_today || 0; })) || 1;
                if (chartWrap && chartEl && sorted.length > 0) {
                    chartEl.innerHTML = sorted.slice(0, 8).map(function(b, i) {
                        var name = (b.company && b.company.name) ? b.company.name + ' — ' + (b.name || '') : (b.name || 'Branch');
                        var sales = b.sales_today || 0;
                        var pct = maxSales > 0 ? (sales / maxSales) * 100 : 0;
                        return '<div class="flex items-center gap-3"><div class="min-w-[7rem] max-w-[12rem] sm:max-w-[16rem] truncate text-xs font-medium text-slate-600 dark:text-slate-400" title="' + escapeHtml(name) + '">' + escapeHtml(name) + '</div><div class="flex-1 min-w-0 h-6 rounded bg-slate-100 dark:bg-darkmode-600 overflow-hidden"><div class="h-full rounded bg-primary transition-all" style="width:' + pct + '%"></div></div><div class="w-20 shrink-0 text-right text-xs font-semibold text-slate-700 dark:text-slate-300">' + fmtMoney(sales) + '</div></div>';
                    }).join('');
                }
                var html = '';
                sorted.slice(0, 8).forEach(function(b, idx) {
                    var rank = idx + 1;
                    var name = escapeHtml((b.company && b.company.name) ? b.company.name + ' — ' + (b.name || '') : (b.name || 'Branch'));
                    var sales = b.sales_today || 0;
                    var salesStr = fmtMoney(sales);
                    var count = b.transaction_count_today != null ? b.transaction_count_today : 0;
                    var barPct = maxSales > 0 ? (sales / maxSales) * 100 : 0;
                    var salesClass = sales === 0 ? 'text-slate-400 dark:text-slate-500' : 'text-primary font-semibold';
                    var rowClass = sales === 0 ? 'flex flex-col sm:flex-row sm:items-center gap-2 px-5 py-4 hover:bg-slate-50 dark:hover:bg-darkmode-700/50 transition bg-slate-50/50 dark:bg-darkmode-800/50 opacity-90' : 'flex flex-col sm:flex-row sm:items-center gap-2 px-5 py-4 hover:bg-slate-50 dark:hover:bg-darkmode-700/50 transition';
                    html += '<a href="' + dashboardBase + '/branches?branch=' + (b.id || '') + '" class="' + rowClass + '">';
                    html += '<div class="min-w-0 flex-1"><div class="flex items-center gap-2"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded bg-slate-200 dark:bg-darkmode-500 text-xs font-bold text-slate-600 dark:text-slate-400">' + rank + '</span><span class="font-medium ' + (sales === 0 ? 'text-slate-500 dark:text-slate-400' : 'text-slate-800 dark:text-slate-200') + ' truncate">' + name + '</span></div><div class="mt-1 h-1.5 w-full max-w-[200px] rounded-full bg-slate-100 dark:bg-darkmode-600 overflow-hidden"><div class="h-full rounded-full bg-primary/70" style="width:' + barPct + '%"></div></div><div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">' + count + ' transaction(s)</div></div>';
                    html += '<div class="flex-shrink-0 text-sm ' + salesClass + '">' + salesStr + '</div>';
                    html += '</a>';
                });
                el.innerHTML = html;
            })
            .catch(function() {
                document.getElementById('dashboard-branch-section').classList.add('hidden');
            });
    }
    loadBranchOverview();
    var listWrap = document.getElementById('dashboard-branch-list-wrap');
    var chartWrap = document.getElementById('dashboard-branch-chart-wrap');
    var viewListBtn = document.getElementById('dashboard-branch-view-list');
    var viewChartBtn = document.getElementById('dashboard-branch-view-chart');
    if (viewListBtn && viewChartBtn && listWrap && chartWrap) {
        viewListBtn.addEventListener('click', function() {
            listWrap.classList.remove('hidden');
            chartWrap.classList.add('hidden');
            chartWrap.setAttribute('aria-hidden', 'true');
            viewListBtn.classList.add('bg-primary', 'text-white'); viewListBtn.classList.remove('text-slate-600', 'dark:text-slate-400');
            viewChartBtn.classList.remove('bg-primary', 'text-white'); viewChartBtn.classList.add('text-slate-600', 'dark:text-slate-400');
        });
        viewChartBtn.addEventListener('click', function() {
            listWrap.classList.add('hidden');
            chartWrap.classList.remove('hidden');
            chartWrap.setAttribute('aria-hidden', 'false');
            viewChartBtn.classList.add('bg-primary', 'text-white'); viewChartBtn.classList.remove('text-slate-600', 'dark:text-slate-400');
            viewListBtn.classList.remove('bg-primary', 'text-white'); viewListBtn.classList.add('text-slate-600', 'dark:text-slate-400');
        });
    }
    document.getElementById('dashboard-branch-export-csv').addEventListener('click', function() {
        if (branchOverviewData.length === 0) return;
        var csvHeaders = ['Rank', 'Company', 'Branch', 'Sales', 'Transactions'];
        var rows = branchOverviewData.slice().sort(function(a, b) { return (b.sales_today || 0) - (a.sales_today || 0); }).map(function(b, i) {
            return [i + 1, (b.company && b.company.name) || '', b.name || '', (b.sales_today || 0).toFixed(2), (b.transaction_count_today || 0)];
        });
        var csv = [csvHeaders.join(',')].concat(rows.map(function(r) { return r.map(function(c) { return '"' + String(c).replace(/"/g, '""') + '"'; }).join(','); })).join('\n');
        var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        var a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'branch-overview-' + currentPeriod + '-' + new Date().toISOString().slice(0, 10) + '.csv';
        a.click();
        URL.revokeObjectURL(a.href);
    });

    // Official Store map (Leaflet) + pins from branch addresses
    var branchListForMap = [];
    var mapMarkersByBranchId = {};
    var mapMarkerLayer = null;
    function updateOfficialStoreCount(count) {
        var el = document.getElementById('dashboard-store-count');
        if (el) el.textContent = count != null ? count : '—';
    }
    function addBranchMarker(map, branch) {
        if (!map || !branch) return;
        var lat = branch.latitude != null ? parseFloat(branch.latitude) : null;
        var lng = branch.longitude != null ? parseFloat(branch.longitude) : null;
        if (lat == null || lng == null) return null;
        var name = (branch.company && branch.company.name) ? branch.company.name + ' — ' + (branch.name || '') : (branch.name || 'Branch');
        var addr = branch.address || '';
        var popupContent = '<div class="text-sm"><div class="font-semibold text-slate-800">' + escapeHtml(name) + '</div>' + (addr ? '<div class="mt-1 text-slate-600">' + escapeHtml(addr) + '</div>' : '') + '</div>';
        var marker = L.marker([lat, lng]).bindPopup(popupContent);
        marker.branchId = branch.id;
        if (mapMarkerLayer) mapMarkerLayer.addLayer(marker);
        else marker.addTo(map);
        return marker;
    }
    function updateMapMarkersFromFilter(map, branchesToShow) {
        if (!map) return;
        var ids = {};
        (branchesToShow || []).forEach(function(b) { ids[b.id] = true; });
        Object.keys(mapMarkersByBranchId).forEach(function(bid) {
            var m = mapMarkersByBranchId[bid];
            if (m && m[0]) {
                if (ids[bid]) { if (mapMarkerLayer) mapMarkerLayer.addLayer(m[0]); else m[0].addTo(map); }
                else { if (mapMarkerLayer) mapMarkerLayer.removeLayer(m[0]); else map.removeLayer(m[0]); }
            }
        });
    }
    function initOfficialStoreMap() {
        var container = document.getElementById('dashboard-official-store-map');
        if (!container || typeof L === 'undefined') return null;
        var lat = parseFloat(container.getAttribute('data-lat')) || 14.5995;
        var lng = parseFloat(container.getAttribute('data-long')) || 120.9842;
        var map = L.map(container, { center: [lat, lng], zoom: 6, scrollWheelZoom: true });
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);
        mapMarkerLayer = L.layerGroup().addTo(map);
        window.dashboardOfficialStoreMap = map;
        return map;
    }
    function geocodeAndAddMarker(map, branch, delayMs, thenRun) {
        var address = (branch.address || '').trim();
        if (!address) { if (thenRun) thenRun(); return; }
        setTimeout(function() {
            axios.get(apiBase + '/geocode', Object.assign({ params: { address: address } }, headers)).then(function(res) {
                var d = res.data && res.data.data;
                if (d && d.latitude != null && d.longitude != null) {
                    var lat = parseFloat(d.latitude);
                    var lng = parseFloat(d.longitude);
                    branch.latitude = lat;
                    branch.longitude = lng;
                    var m = addBranchMarker(map, branch);
                    if (m) mapMarkersByBranchId[branch.id] = [m, branch];
                    axios.put(apiBase + '/branches/' + branch.id, { latitude: lat, longitude: lng }, headers).catch(function() {});
                }
                if (thenRun) thenRun();
            }).catch(function() { if (thenRun) thenRun(); });
        }, delayMs);
    }
    function addAllBranchPins(map, branches) {
        if (!map || !branches.length) return;
        var withCoords = [];
        var withoutCoords = [];
        branches.forEach(function(b) {
            if (b.latitude != null && b.longitude != null) withCoords.push(b);
            else if ((b.address || '').trim()) withoutCoords.push(b);
        });
        var bounds = [];
        withCoords.forEach(function(b) {
            var m = addBranchMarker(map, b);
            if (m) mapMarkersByBranchId[b.id] = [m, b];
            bounds.push([parseFloat(b.latitude), parseFloat(b.longitude)]);
        });
        if (bounds.length > 0) try { map.fitBounds(bounds, { padding: [24, 24], maxZoom: 14 }); } catch (e) {}
        withoutCoords.forEach(function(b, i) {
            geocodeAndAddMarker(map, b, 1200 * (i + 1), function() {
                if (b.latitude != null && b.longitude != null && mapMarkerLayer && mapMarkerLayer.getBounds) {
                    try { map.fitBounds(mapMarkerLayer.getBounds(), { padding: [24, 24], maxZoom: 14 }); } catch (e) {}
                }
            });
        });
    }
    (function ensureMapThenFetch() {
        if (typeof L === 'undefined') {
            setTimeout(ensureMapThenFetch, 50);
            return;
        }
        var map = initOfficialStoreMap();
        if (!map) {
            setTimeout(ensureMapThenFetch, 50);
            return;
        }
        axios.get(apiBase + '/branches', headers)
            .then(function(r) {
                var list = (r.data && r.data.data) ? r.data.data : (Array.isArray(r.data) ? r.data : []);
                branchListForMap = list || [];
                updateOfficialStoreCount(branchListForMap.length);
                addAllBranchPins(map, branchListForMap);
                var filterInput = document.getElementById('dashboard-store-filter-city');
                if (filterInput) {
                    filterInput.addEventListener('input', function() {
                        var q = (this.value || '').toLowerCase().trim();
                        var filtered = q ? branchListForMap.filter(function(b) {
                            var addr = (b.address || '').toLowerCase();
                            var name = (b.name || '').toLowerCase();
                            var company = (b.company && b.company.name) ? b.company.name.toLowerCase() : '';
                            return addr.indexOf(q) >= 0 || name.indexOf(q) >= 0 || company.indexOf(q) >= 0;
                        }) : branchListForMap;
                        updateOfficialStoreCount(filtered.length);
                        updateMapMarkersFromFilter(map, filtered);
                    });
                }
            })
            .catch(function() {
                updateOfficialStoreCount(0);
            });
    })();

    // Low stock alerts (top 5)
    axios.get(apiBase + '/dashboard/low-stock-alerts', headers)
        .then(function(r) {
            var list = (r.data && r.data.data) ? r.data.data : (Array.isArray(r.data) ? r.data : []);
            var el = document.getElementById('dashboard-low-stock-list');
            var inventoryUrl = dashboardBase + '/inventory';
            if (!list || list.length === 0) {
                el.innerHTML = '<div class="px-5 py-10 text-center"><span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 mb-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></span><p class="text-sm font-medium text-slate-700 dark:text-slate-300">All stock levels are healthy</p><p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">No low stock alerts at this time.</p></div>';
                return;
            }
            var html = '';
            list.slice(0, 5).forEach(function(p) {
                var name = escapeHtml(p.name || p.product_name || 'Product');
                var companyName = (p.branch && p.branch.company && p.branch.company.name) ? escapeHtml(p.branch.company.name) : '';
                var branchName = (p.branch && p.branch.name) ? escapeHtml(p.branch.name) : '';
                var companyBranchLabel = companyName && branchName ? companyName + ' · ' + branchName : (branchName || companyName || '');
                var stockNum = p.batches_sum_quantity != null ? Number(p.batches_sum_quantity) : NaN;
                var reorderNum = p.reorder_level != null ? Number(p.reorder_level) : 0;
                var stockText = formatQuantity(p.batches_sum_quantity != null ? p.batches_sum_quantity : p.stock);
                var reorderText = formatQuantity(p.reorder_level);
                var isCritical = !isNaN(stockNum) && (stockNum <= 0 || (reorderNum > 0 && stockNum <= reorderNum / 2));
                var badgeClass = isCritical ? 'bg-rose-100 dark:bg-rose-900/30 text-rose-800 dark:text-rose-300' : 'bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300';
                var ratio = (reorderNum > 0 && !isNaN(stockNum)) ? Math.min(1, Math.max(0, stockNum / reorderNum)) : 0;
                var barWidth = Math.round(ratio * 100);
                html += '<a href="' + escapeHtml(inventoryUrl) + '" class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50 dark:hover:bg-darkmode-700/50 transition-colors min-h-[52px] touch-manipulation">';
                html += '<div class="min-w-0 flex-1">';
                html += '<div class="font-medium text-slate-800 dark:text-slate-200 text-sm truncate">' + name + '</div>';
                html += '<div class="flex items-center gap-2 mt-0.5 flex-wrap">';
                if (companyBranchLabel) html += '<span class="text-xs text-slate-500 dark:text-slate-400">' + companyBranchLabel + '</span>';
                html += '<span class="text-xs text-slate-500 dark:text-slate-400">Reorder at ' + reorderText + '</span>';
                html += '</div>';
                if (reorderNum > 0 && !isNaN(stockNum)) {
                    html += '<div class="mt-1.5 h-1 w-full max-w-[120px] rounded-full bg-slate-200 dark:bg-darkmode-500 overflow-hidden"><div class="h-full rounded-full ' + (isCritical ? 'bg-rose-500' : 'bg-amber-500') + '" style="width:' + barWidth + '%"></div></div>';
                }
                html += '</div>';
                html += '<span class="flex-shrink-0 rounded-full ' + badgeClass + ' px-2.5 py-1 text-xs font-medium">' + stockText + ' left</span>';
                html += '</a>';
            });
            el.innerHTML = html;
        })
        .catch(function() {
            document.getElementById('dashboard-low-stock-list').innerHTML = '<div class="px-5 py-8 text-center text-slate-500 dark:text-slate-400 text-sm">Unable to load.</div>';
        });

    // Expiring soon (top 5)
    axios.get(apiBase + '/dashboard/expiring-soon', headers)
        .then(function(r) {
            var list = (r.data && r.data.data) ? r.data.data : (Array.isArray(r.data) ? r.data : []);
            var el = document.getElementById('dashboard-expiring-list');
            if (!list || list.length === 0) {
                el.innerHTML = '<div class="px-5 py-10 text-center"><span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 mb-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></span><p class="text-sm font-medium text-slate-700 dark:text-slate-300">No items expiring soon</p><p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">All products are within expiry window.</p></div>';
                return;
            }
            var html = '';
            list.slice(0, 5).forEach(function(b) {
                var name = escapeHtml(b.product && b.product.name ? b.product.name : (b.product_name || 'Product'));
                var expiry = b.expiry_date ? (b.expiry_date.split('T')[0] || b.expiry_date) : '—';
                var qty = b.quantity != null ? b.quantity : '—';
                html += '<div class="flex items-center justify-between gap-3 px-5 py-3">';
                html += '<div class="min-w-0 flex-1"><div class="font-medium text-slate-800 dark:text-slate-200 text-sm truncate">' + name + '</div><div class="text-xs text-slate-500 dark:text-slate-400">Qty: ' + qty + '</div></div>';
                html += '<span class="flex-shrink-0 text-xs font-medium text-rose-600 dark:text-rose-400">' + expiry + '</span>';
                html += '</div>';
            });
            el.innerHTML = html;
        })
        .catch(function() {
            document.getElementById('dashboard-expiring-list').innerHTML = '<div class="px-5 py-8 text-center text-slate-500 dark:text-slate-400 text-sm">Unable to load.</div>';
        });
})();
</script>
@endpush
