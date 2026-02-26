@extends('super-admin.layouts.app')

@section('title', 'Inventory')
@section('breadcrumb', 'Inventory')

@section('content')
    <div class="intro-y mt-6 sm:mt-10 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
        <h2 class="text-base font-semibold text-slate-800 dark:text-slate-100 sm:text-lg">Inventory</h2>

        <div class="flex flex-wrap items-center gap-2 min-w-0">
            {{-- Company filter --}}
            <div class="relative flex-shrink-0 min-h-[44px] sm:min-h-0 flex items-center">
                <select id="inventory-company-filter" class="appearance-none rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 py-2.5 sm:py-2 pl-3 pr-8 text-sm text-slate-700 dark:text-slate-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition cursor-pointer min-h-[44px] sm:min-h-0">
                    <option value="">All companies</option>
                </select>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            {{-- Branch filter (super_admin / admin see all) --}}
            <div class="relative flex-shrink-0 min-h-[44px] sm:min-h-0 flex items-center">
                <select id="inventory-branch-filter" class="appearance-none rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 py-2.5 sm:py-2 pl-3 pr-8 text-sm text-slate-700 dark:text-slate-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition cursor-pointer min-h-[44px] sm:min-h-0">
                    <option value="">All branches</option>
                </select>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400"><polyline points="6 9 12 15 18 9"/></svg>
            </div>

            {{-- Status filter --}}
            <div class="relative flex-shrink-0 min-h-[44px] sm:min-h-0 flex items-center">
                <select id="inventory-status-filter" class="appearance-none rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 py-2.5 sm:py-2 pl-3 pr-8 text-sm text-slate-700 dark:text-slate-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition cursor-pointer min-h-[44px] sm:min-h-0">
                    <option value="all">All</option>
                    <option value="ok">OK</option>
                    <option value="low">Low</option>
                    <option value="critical">Critical</option>
                    <option value="out">Out of stock</option>
                    <option value="attention">Need attention (Critical + Low)</option>
                </select>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400"><polyline points="6 9 12 15 18 9"/></svg>
            </div>

            {{-- Search --}}
            <div class="relative text-slate-500 flex-1 min-w-0 sm:flex-initial sm:w-52">
                <input type="text" id="inventory-search" placeholder="Search product or barcode…" class="transition duration-200 ease-in-out text-sm border-slate-200 dark:border-transparent shadow-sm rounded-lg placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary dark:bg-darkmode-800 dark:placeholder:text-slate-500/80 box w-full min-w-0 sm:w-52 pr-9 py-2.5 sm:py-2 min-h-[44px] sm:min-h-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>

            <button type="button" id="inventory-refresh-btn" class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-4 py-2.5 sm:py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-600 focus:ring-2 focus:ring-primary/20 transition-colors touch-manipulation min-h-[44px] w-full sm:w-auto flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21h5v-5"/></svg>
                Refresh
            </button>
        </div>
    </div>

    {{-- Summary stat cards: single row of 5, clickable to filter --}}
    <div class="intro-y mt-4 grid grid-cols-2 md:grid-cols-5 gap-3 sm:gap-4">
        <button type="button" class="inv-stat-card rounded-xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm p-4 text-left hover:bg-slate-50 dark:hover:bg-darkmode-700/80 focus:ring-2 focus:ring-primary/20 focus:ring-offset-2 dark:focus:ring-offset-darkmode-800 transition-colors cursor-pointer touch-manipulation" data-filter="all" aria-label="Filter by all items">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Total</p>
            <p id="inv-stat-total" class="mt-1 text-2xl font-bold text-slate-800 dark:text-slate-100">—</p>
        </button>
        <button type="button" class="inv-stat-card rounded-xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm p-4 text-left hover:bg-slate-50 dark:hover:bg-darkmode-700/80 focus:ring-2 focus:ring-primary/20 focus:ring-offset-2 dark:focus:ring-offset-darkmode-800 transition-colors cursor-pointer touch-manipulation" data-filter="critical" aria-label="Filter by critical stock">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Critical</p>
            <p id="inv-stat-critical" class="mt-1 text-2xl font-bold text-rose-600 dark:text-rose-400">—</p>
        </button>
        <button type="button" class="inv-stat-card rounded-xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm p-4 text-left hover:bg-slate-50 dark:hover:bg-darkmode-700/80 focus:ring-2 focus:ring-primary/20 focus:ring-offset-2 dark:focus:ring-offset-darkmode-800 transition-colors cursor-pointer touch-manipulation" data-filter="low" aria-label="Filter by low stock">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Low stock</p>
            <p id="inv-stat-low" class="mt-1 text-2xl font-bold text-amber-600 dark:text-amber-400">—</p>
        </button>
        <button type="button" class="inv-stat-card rounded-xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm p-4 text-left hover:bg-slate-50 dark:hover:bg-darkmode-700/80 focus:ring-2 focus:ring-primary/20 focus:ring-offset-2 dark:focus:ring-offset-darkmode-800 transition-colors cursor-pointer touch-manipulation" data-filter="out" aria-label="Filter by out of stock">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Out of stock</p>
            <p id="inv-stat-out" class="mt-1 text-2xl font-bold text-slate-500 dark:text-slate-500">—</p>
        </button>
        <div class="rounded-xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm p-4">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Total value</p>
            <p id="inv-stat-value" class="mt-1 text-xl sm:text-2xl font-bold text-slate-800 dark:text-slate-100">—</p>
        </div>
    </div>

    <div class="intro-y mt-4 flex flex-wrap items-center gap-2">
        <button type="button" id="inventory-attention-btn" class="hidden inline-flex items-center gap-1.5 rounded-full bg-amber-100 dark:bg-amber-900/30 px-3 py-1.5 text-xs font-medium text-amber-800 dark:text-amber-200 hover:bg-amber-200 dark:hover:bg-amber-800/50 transition-colors border border-amber-200 dark:border-amber-800">
            <span id="inventory-attention-count">0</span> need attention
        </button>
        <span id="inventory-summary-badge" class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 dark:bg-darkmode-600 px-3 py-1 text-xs font-medium text-slate-600 dark:text-slate-400">
            <span id="inventory-summary-text">Loading…</span>
        </span>
        <a href="{{ route('dashboard.products') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-primary border border-primary px-3 py-1.5 text-xs font-medium text-white hover:bg-primary/90 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add item
        </a>
        <button type="button" id="inventory-export-csv" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-1.5 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export CSV
        </button>
        <div class="relative" id="inventory-columns-wrap">
            <button type="button" id="inventory-columns-btn" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-1.5 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3z"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/></svg>
                Columns
            </button>
            <div id="inventory-columns-dropdown" class="hidden absolute right-0 top-full mt-1 z-20 min-w-[11rem] rounded-xl border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-700 p-2 shadow-xl">
                <p class="px-2 py-1 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">Show columns</p>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-darkmode-600 cursor-pointer"><input type="checkbox" id="col-product" class="rounded border-slate-300 text-primary focus:ring-primary/20" checked><span class="text-sm">Product</span></label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-darkmode-600 cursor-pointer"><input type="checkbox" id="col-generic" class="rounded border-slate-300 text-primary focus:ring-primary/20" checked><span class="text-sm">Generic name</span></label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-darkmode-600 cursor-pointer"><input type="checkbox" id="col-barcode" class="rounded border-slate-300 text-primary focus:ring-primary/20" checked><span class="text-sm">Barcode</span></label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-darkmode-600 cursor-pointer"><input type="checkbox" id="col-brand" class="rounded border-slate-300 text-primary focus:ring-primary/20"><span class="text-sm">Brand</span></label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-darkmode-600 cursor-pointer"><input type="checkbox" id="col-quantity" class="rounded border-slate-300 text-primary focus:ring-primary/20" checked><span class="text-sm">Quantity</span></label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-darkmode-600 cursor-pointer"><input type="checkbox" id="col-reorder" class="rounded border-slate-300 text-primary focus:ring-primary/20" checked><span class="text-sm">Reorder</span></label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-darkmode-600 cursor-pointer"><input type="checkbox" id="col-unit" class="rounded border-slate-300 text-primary focus:ring-primary/20"><span class="text-sm">Unit</span></label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-darkmode-600 cursor-pointer"><input type="checkbox" id="col-status" class="rounded border-slate-300 text-primary focus:ring-primary/20" checked><span class="text-sm">Status</span></label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-darkmode-600 cursor-pointer"><input type="checkbox" id="col-price" class="rounded border-slate-300 text-primary focus:ring-primary/20"><span class="text-sm">Price</span></label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-darkmode-600 cursor-pointer"><input type="checkbox" id="col-cost" class="rounded border-slate-300 text-primary focus:ring-primary/20"><span class="text-sm">Cost</span></label>
                <label class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-darkmode-600 cursor-pointer"><input type="checkbox" id="col-category" class="rounded border-slate-300 text-primary focus:ring-primary/20"><span class="text-sm">Category</span></label>
            </div>
        </div>
    </div>

    <div id="inventory-table-wrap" class="intro-y mt-4 sm:mt-5 rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto overflow-y-visible">
            <table id="inventory-table" class="w-full text-sm text-left text-slate-700 dark:text-slate-300">
                <thead class="text-xs uppercase bg-slate-50 dark:bg-darkmode-700 text-slate-500 dark:text-slate-400 sticky top-0 z-10">
                    <tr>
                        <th scope="col" class="inv-th-group px-4 py-3 sm:px-5 sm:py-3 font-medium w-10"></th>
                        <th scope="col" class="inv-th-product inventory-sort px-4 py-3 sm:px-5 sm:py-3 font-medium cursor-pointer hover:bg-slate-100 dark:hover:bg-darkmode-600 transition-colors select-none" data-sort="product">Product <span class="sort-icon inline-block ml-0.5 opacity-50">↕</span></th>
                        <th scope="col" class="inv-th-generic px-4 py-3 sm:px-5 sm:py-3 font-medium">Generic name</th>
                        <th scope="col" class="inv-th-barcode inventory-sort px-4 py-3 sm:px-5 sm:py-3 font-medium cursor-pointer hover:bg-slate-100 dark:hover:bg-darkmode-600 transition-colors select-none" data-sort="barcode">Barcode <span class="sort-icon inline-block ml-0.5 opacity-50">↕</span></th>
                        <th scope="col" class="inv-th-brand px-4 py-3 sm:px-5 sm:py-3 font-medium hidden">Brand</th>
                        <th scope="col" class="inv-th-quantity inventory-sort px-4 py-3 sm:px-5 sm:py-3 font-medium text-right cursor-pointer hover:bg-slate-100 dark:hover:bg-darkmode-600 transition-colors select-none" data-sort="quantity">Quantity <span class="sort-icon inline-block ml-0.5 opacity-50">↕</span></th>
                        <th scope="col" class="inv-th-reorder px-4 py-3 sm:px-5 sm:py-3 font-medium text-right">Reorder</th>
                        <th scope="col" class="inv-th-unit px-4 py-3 sm:px-5 sm:py-3 font-medium hidden">Unit</th>
                        <th scope="col" class="inv-th-status inventory-sort px-4 py-3 sm:px-5 sm:py-3 font-medium cursor-pointer hover:bg-slate-100 dark:hover:bg-darkmode-600 transition-colors select-none" data-sort="status">Status <span class="sort-icon inline-block ml-0.5 opacity-50">↕</span></th>
                        <th scope="col" class="inv-th-price px-4 py-3 sm:px-5 sm:py-3 font-medium text-right hidden">Price</th>
                        <th scope="col" class="inv-th-cost px-4 py-3 sm:px-5 sm:py-3 font-medium text-right hidden">Cost</th>
                        <th scope="col" class="inv-th-category px-4 py-3 sm:px-5 sm:py-3 font-medium hidden">Category</th>
                        <th scope="col" class="inv-th-active px-4 py-3 sm:px-5 sm:py-3 font-medium w-16 text-center">Active</th>
                        <th scope="col" class="px-4 py-3 sm:px-5 sm:py-3 w-10"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody id="inventory-tbody" class="divide-y divide-slate-100 dark:divide-darkmode-600">
                    <tr><td colspan="14" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">Loading…</td></tr>
                </tbody>
            </table>
        </div>
        <div id="inventory-pagination" class="hidden border-t border-slate-200 dark:border-darkmode-600 px-4 py-3 flex flex-wrap items-center justify-between gap-2 bg-slate-50/50 dark:bg-darkmode-700/50">
            <p id="inventory-pagination-info" class="text-xs text-slate-500 dark:text-slate-400"></p>
            <div id="inventory-pagination-btns" class="flex items-center gap-1"></div>
        </div>
    </div>

    <div id="inventory-empty" class="intro-y mt-6 hidden text-center py-16 text-slate-400 dark:text-slate-500">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3 opacity-40"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
        <p class="text-sm">No inventory items found.</p>
    </div>

    {{-- Action menu (right-click or ⋮ button) — positioned inside viewport --}}
    <div id="inventory-context-menu" class="fixed hidden z-[99999] min-w-[11rem] max-w-[min(90vw,18rem)] rounded-xl border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-600 p-1.5 shadow-xl whitespace-nowrap">
        <button type="button" id="inventory-context-view" class="inventory-context-item flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-darkmode-400 transition-colors text-left">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            <span>View product</span>
        </button>
        <a id="inventory-context-low-stock" href="#" class="inventory-context-item flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-amber-600 dark:text-amber-400 hover:bg-slate-100 dark:hover:bg-darkmode-400 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <span>Dashboard alerts</span>
        </a>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    var apiBase = '{{ url("/api/v1") }}';
    function getToken() { return localStorage.getItem('super_admin_token'); }
    function authHeaders() { var t = getToken(); return { headers: { Authorization: t ? 'Bearer ' + t : '', Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } }; }
    function escapeHtml(s) { if (s == null) return ''; var div = document.createElement('div'); div.textContent = s; return div.innerHTML; }

    var tableWrap = document.getElementById('inventory-table-wrap');
    var tbody = document.getElementById('inventory-tbody');
    var summaryText = document.getElementById('inventory-summary-text');
    var emptyEl = document.getElementById('inventory-empty');
    var companyFilter = document.getElementById('inventory-company-filter');
    var branchFilter = document.getElementById('inventory-branch-filter');
    var statusFilter = document.getElementById('inventory-status-filter');
    var searchInput = document.getElementById('inventory-search');
    var refreshBtn = document.getElementById('inventory-refresh-btn');
    var contextMenu = document.getElementById('inventory-context-menu');
    var contextView = document.getElementById('inventory-context-view');
    var contextLowStock = document.getElementById('inventory-context-low-stock');

    function showInventoryActionMenu(anchorX, anchorY, fromButton) {
        if (!contextMenu) return;
        var pad = 8;
        var menuW = 220;
        var menuH = 100;
        var vw = window.innerWidth;
        var vh = window.innerHeight;
        var left = fromButton ? anchorX : anchorX;
        var top = fromButton ? anchorY + 4 : anchorY + 2;
        if (left + menuW > vw - pad) left = vw - menuW - pad;
        if (top + menuH > vh - pad) top = vh - menuH - pad;
        if (left < pad) left = pad;
        if (top < pad) top = pad;
        contextMenu.style.left = left + 'px';
        contextMenu.style.top = top + 'px';
        contextMenu.classList.remove('hidden');
    }

    var allRows = [];
    var branchesList = [];
    var selectedRowData = null;
    var currentPage = 1;
    var perPage = 25;
    var sortKey = 'product';
    var sortDir = 1;
    var totalValuation = 0;
    var appUrl = '{{ url("/") }}';

    function getStatusType(row) {
        var qty = parseFloat(row.total_quantity) || 0;
        var reorder = row.reorder_level != null ? parseFloat(row.reorder_level) : 0;
        if (qty === 0) return 'out';
        if (reorder > 0 && qty <= reorder) return 'critical';
        if (reorder > 0 && qty <= reorder * 1.5) return 'low';
        return 'ok';
    }

    function getStatusBadge(type) {
        if (type === 'out') return '<span class="inline-flex items-center rounded-full bg-slate-200 dark:bg-darkmode-500 px-2.5 py-0.5 text-xs font-medium text-slate-600 dark:text-slate-400">Out of stock</span>';
        if (type === 'critical') return '<span class="inline-flex items-center rounded-full bg-rose-100 dark:bg-rose-900/30 px-2.5 py-0.5 text-xs font-semibold text-rose-800 dark:text-rose-300">Critical</span>';
        if (type === 'low') return '<span class="inline-flex items-center rounded-full bg-amber-100 dark:bg-amber-900/30 px-2.5 py-0.5 text-xs font-medium text-amber-800 dark:text-amber-300">Low</span>';
        return '<span class="inline-flex items-center rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:text-emerald-300">OK</span>';
    }

    function getStatusSortValue(type) {
        return { out: 0, critical: 1, low: 2, ok: 3 }[type] || 3;
    }

    function quantityProgressBar(qty, reorder, type) {
        var tip = (qty != null ? qty : 0) + ' / ' + (reorder != null && reorder > 0 ? reorder : '—') + ' reorder level';
        if (type === 'out' || (reorder != null && reorder <= 0)) return '<div class="inv-progress h-[6px] w-full max-w-[4rem] rounded-full bg-slate-200 dark:bg-darkmode-500 overflow-hidden" title="' + escapeHtml(tip) + '"><div class="h-full rounded-full bg-slate-400 dark:bg-slate-500" style="width:0%"></div></div>';
        var max = Math.max(reorder * 2, qty, 1);
        var pct = Math.min(100, (qty / max) * 100);
        var barColor = type === 'critical' ? 'bg-rose-500' : type === 'low' ? 'bg-amber-500' : 'bg-emerald-500';
        return '<div class="inv-progress h-[6px] w-full max-w-[4rem] rounded-full bg-slate-200 dark:bg-darkmode-500 overflow-hidden" title="' + escapeHtml(tip) + '"><div class="h-full rounded-full ' + barColor + '" style="width:' + pct + '%"></div></div>';
    }
    function productThumbnail(imagePath) {
        var wrap = '<div class="flex items-center justify-center w-10 h-10 flex-shrink-0">';
        if (imagePath) {
            var src = appUrl + '/' + (imagePath.charAt(0) === '/' ? imagePath.slice(1) : imagePath);
            return wrap + '<img src="' + escapeHtml(src) + '" alt="" class="w-8 h-8 object-cover rounded-lg bg-slate-100 dark:bg-darkmode-600" onerror="this.style.display=\'none\';if(this.nextElementSibling)this.nextElementSibling.classList.remove(\'hidden\');"/>' + '<span class="inv-thumb-placeholder hidden w-8 h-8 rounded-lg bg-slate-200 dark:bg-darkmode-500 flex items-center justify-center text-slate-400 dark:text-slate-500 text-xs">—</span></div>';
        }
        return wrap + '<span class="inv-thumb-placeholder w-8 h-8 rounded-lg bg-slate-200 dark:bg-darkmode-500 flex items-center justify-center text-slate-400 dark:text-slate-500 text-xs">—</span></div>';
    }

    function populateCompanyFilter() {
        if (!companyFilter) return;
        var seen = {};
        var companies = [];
        allRows.forEach(function (row) {
            var c = row.company || (row.branch && row.branch.company);
            var cid = c && c.id;
            var cname = c && c.name;
            if (cid && !seen[cid]) {
                seen[cid] = true;
                companies.push({ id: cid, name: cname || 'Company ' + cid });
            }
        });
        companies.sort(function (a, b) { return (a.name || '').localeCompare(b.name || ''); });
        var cur = companyFilter.value;
        companyFilter.innerHTML = '<option value="">All companies</option>' + companies.map(function (c) {
            return '<option value="' + c.id + '">' + escapeHtml(c.name) + '</option>';
        }).join('');
        companyFilter.value = cur || '';
    }

    function loadBranches() {
        if (!getToken()) return;
        axios.get(apiBase + '/branches', authHeaders())
            .then(function (r) {
                var list = (r.data && r.data.data) ? r.data.data : [];
                if (!Array.isArray(list)) list = [];
                branchesList = list;
                var cur = branchFilter.value;
                branchFilter.innerHTML = '<option value="">All branches</option>' + list.map(function (b) {
                    return '<option value="' + b.id + '">' + escapeHtml(b.name || 'Branch ' + b.id) + '</option>';
                }).join('');
                branchFilter.value = cur || '';
            })
            .catch(function () {});
    }

    function loadStockLevels() {
        if (!getToken()) {
            summaryText.textContent = 'Please log in.';
            tbody.innerHTML = '<tr><td colspan="14" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">Please log in.</td></tr>';
            emptyEl.classList.add('hidden');
            return;
        }
        summaryText.textContent = 'Loading…';
            tbody.innerHTML = '<tr><td colspan="14" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">Loading…</td></tr>';
        axios.get(apiBase + '/inventory/stock-levels', authHeaders())
            .then(function (r) {
                var list = (r.data && r.data.data) ? r.data.data : [];
                if (!Array.isArray(list)) list = [];
                allRows = list;
                totalValuation = (r.data && r.data.total_valuation != null) ? parseFloat(r.data.total_valuation) : 0;
                populateCompanyFilter();
                filterAndRender();
            })
            .catch(function (err) {
                var msg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'Failed to load inventory.';
                if (err.response && err.response.status === 404) msg = 'Inventory API not available on this server.';
                summaryText.textContent = msg;
                tbody.innerHTML = '<tr><td colspan="14" class="px-4 py-8 text-center text-red-500 dark:text-red-400">' + escapeHtml(msg) + '</td></tr>';
                emptyEl.classList.remove('hidden');
            });
    }

    function isLowStock(row) {
        var reorder = row.reorder_level != null ? parseFloat(row.reorder_level) : 0;
        var qty = parseFloat(row.total_quantity) || 0;
        return reorder > 0 && qty <= reorder;
    }

    function filterRows() {
        var companyId = companyFilter && companyFilter.value ? companyFilter.value : '';
        var branchId = branchFilter.value;
        var status = statusFilter.value;
        var search = searchInput.value.trim().toLowerCase();
        return allRows.filter(function (row) {
            if (companyId) {
                var cid = (row.company && row.company.id) || (row.branch && row.branch.company_id);
                if (String(cid) !== String(companyId)) return false;
            }
            if (branchId && String(row.branch_id) !== String(branchId)) return false;
            var st = getStatusType(row);
            if (status === 'low' && st !== 'low') return false;
            if (status === 'ok' && st !== 'ok') return false;
            if (status === 'critical' && st !== 'critical') return false;
            if (status === 'out' && st !== 'out') return false;
            if (status === 'attention' && st !== 'critical' && st !== 'low') return false;
            if (search) {
                var name = (row.name || '').toLowerCase();
                var generic = (row.generic_name || '').toLowerCase();
                var barcode = (row.barcode || '').toLowerCase();
                var brand = (row.brand || '').toLowerCase();
                var branchName = (row.branch && row.branch.name ? row.branch.name : '').toLowerCase();
                var companyName = (row.company && row.company.name ? row.company.name : (row.branch && row.branch.company && row.branch.company.name ? row.branch.company.name : '')).toLowerCase();
                var label = getCompanyBranchLabel(row).toLowerCase();
                if (name.indexOf(search) === -1 && generic.indexOf(search) === -1 && barcode.indexOf(search) === -1 && brand.indexOf(search) === -1 && branchName.indexOf(search) === -1 && companyName.indexOf(search) === -1 && label.indexOf(search) === -1) return false;
            }
            return true;
        });
    }

    function sortRows(list) {
        var key = sortKey;
        var dir = sortDir;
        return list.slice().sort(function (a, b) {
            var branchA = (a.branch && a.branch.name) ? a.branch.name : '';
            var branchB = (b.branch && b.branch.name) ? b.branch.name : '';
            var va, vb;
            if (key === 'product') { va = (a.name || '').toLowerCase(); vb = (b.name || '').toLowerCase(); return dir * (va.localeCompare(vb) || branchA.localeCompare(branchB)); }
            if (key === 'barcode') { va = (a.barcode || '').toLowerCase(); vb = (b.barcode || '').toLowerCase(); return dir * va.localeCompare(vb); }
            if (key === 'quantity') { va = parseFloat(a.total_quantity) || 0; vb = parseFloat(b.total_quantity) || 0; return dir * (va - vb); }
            if (key === 'status') { va = getStatusSortValue(getStatusType(a)); vb = getStatusSortValue(getStatusType(b)); return dir * (va - vb); }
            return 0;
        });
    }

    function getCompanyBranchLabel(row) {
        var company = row.company || (row.branch && row.branch.company) || null;
        var companyName = (company && company.name) ? company.name : '';
        var branchName = (row.branch && row.branch.name) ? row.branch.name : 'Branch ' + (row.branch_id || '');
        return companyName ? companyName + ' > ' + branchName : branchName;
    }

    function buildGroupsForPage(sortedList) {
        var start = (currentPage - 1) * perPage;
        var pageRows = sortedList.slice(start, start + perPage);
        var byBranch = {};
        pageRows.forEach(function (row) {
            var bid = row.branch_id;
            var label = getCompanyBranchLabel(row);
            if (!byBranch[bid]) byBranch[bid] = { name: label, rows: [] };
            byBranch[bid].rows.push(row);
        });
        return byBranch;
    }

    function renderTable() {
        var filtered = filterRows();
        var sorted = sortRows(filtered);

        var total = filtered.length;
        var lowCount = filtered.filter(function (r) { return getStatusType(r) === 'low'; }).length;
        var criticalCount = filtered.filter(function (r) { return getStatusType(r) === 'critical'; }).length;
        var outCount = filtered.filter(function (r) { return getStatusType(r) === 'out'; }).length;

        document.getElementById('inv-stat-total').textContent = total;
        document.getElementById('inv-stat-critical').textContent = criticalCount;
        document.getElementById('inv-stat-low').textContent = lowCount;
        document.getElementById('inv-stat-out').textContent = outCount;
        var valueEl = document.getElementById('inv-stat-value');
        if (valueEl) valueEl.textContent = totalValuation > 0 ? '₱' + Number(totalValuation).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '—';

        var attentionCount = lowCount + criticalCount + outCount;
        summaryText.textContent = total + ' item' + (total !== 1 ? 's' : '');
        var summaryBadge = document.getElementById('inventory-summary-badge');
        var attentionBtn = document.getElementById('inventory-attention-btn');
        var attentionCountEl = document.getElementById('inventory-attention-count');
        if (attentionBtn && attentionCountEl) {
            if (attentionCount > 0) {
                attentionCountEl.textContent = attentionCount;
                attentionBtn.classList.remove('hidden');
                if (summaryBadge) summaryBadge.classList.add('hidden');
            } else {
                attentionBtn.classList.add('hidden');
                if (summaryBadge) summaryBadge.classList.remove('hidden');
            }
        } else if (summaryBadge) { summaryBadge.classList.remove('hidden'); }

        if (filtered.length === 0) {
            tbody.innerHTML = '<tr><td colspan="14" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">No items match filters.</td></tr>';
            tableWrap.classList.remove('hidden');
            emptyEl.classList.remove('hidden');
            document.getElementById('inventory-pagination').classList.add('hidden');
            return;
        }

        emptyEl.classList.add('hidden');
        var totalPages = Math.max(1, Math.ceil(sorted.length / perPage));
        if (currentPage > totalPages) currentPage = totalPages;
        var groups = buildGroupsForPage(sorted);

        var html = '';
        Object.keys(groups).forEach(function (bid) {
            var g = groups[bid];
            var count = g.rows.length;
            var companyBranchLabel = g.name;
            var groupId = 'inv-group-' + bid;
            var branchNameOnly = (g.rows[0] && g.rows[0].branch && g.rows[0].branch.name) ? g.rows[0].branch.name : 'Branch ' + bid;
            var companyNameOnly = (g.rows[0] && g.rows[0].company && g.rows[0].company.name) ? g.rows[0].company.name : (g.rows[0] && g.rows[0].branch && g.rows[0].branch.company && g.rows[0].branch.company.name) ? g.rows[0].branch.company.name : '';
            html += '<tr class="inv-group-header bg-slate-100 dark:bg-darkmode-600 border-y border-slate-200 dark:border-darkmode-500" data-branch-id="' + escapeHtml(String(bid)) + '">'
                + '<td class="px-4 py-2 sm:px-5 sm:py-2 w-10"><button type="button" class="inv-group-toggle p-1 rounded text-slate-500 hover:bg-slate-200 dark:hover:bg-darkmode-500 transition-colors" aria-expanded="true" data-target="' + groupId + '"><svg class="inv-group-chevron w-4 h-4 transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></button></td>'
                + '<td colspan="12" class="px-4 py-2 sm:px-5 sm:py-2"><div class="font-semibold text-slate-800 dark:text-slate-100">' + escapeHtml(branchNameOnly) + '</div>' + (companyNameOnly ? '<div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">' + escapeHtml(companyNameOnly) + '</div>' : '') + ' <span class="text-slate-500 dark:text-slate-400 font-normal text-xs">(' + count + ' item' + (count !== 1 ? 's' : '') + ')</span></td>'
                + '<td class="px-4 py-2 sm:px-5 sm:py-2 w-10"></td></tr>';
            g.rows.forEach(function (row) {
                var qty = parseFloat(row.total_quantity) || 0;
                var reorder = row.reorder_level != null ? parseFloat(row.reorder_level) : 0;
                var st = getStatusType(row);
                var qtyDisplay = (Number(qty) === qty && qty % 1 === 0 ? qty : qty.toFixed(2));
                var priceVal = row.price != null ? parseFloat(row.price) : null;
                var costVal = row.cost != null ? parseFloat(row.cost) : null;
                var catName = (row.category && row.category.name) ? row.category.name : '';
                var inactiveClass = (row.is_active === false) ? ' opacity-60 bg-slate-50/50 dark:bg-darkmode-700/30' : '';
                html += '<tr class="inv-group-row inventory-row hover:bg-slate-50 dark:hover:bg-darkmode-700/50 transition-colors ' + groupId + inactiveClass + '" data-product-id="' + escapeHtml(String(row.product_id)) + '" data-branch-id="' + escapeHtml(String(row.branch_id)) + '" data-name="' + escapeHtml(row.name || '') + '" data-barcode="' + escapeHtml(row.barcode || '') + '">'
                    + '<td class="px-4 py-2 sm:px-5 sm:py-2 w-10"></td>'
                    + '<td class="inv-td-product px-4 py-2 sm:px-5 sm:py-2">' + escapeHtml(row.name || '—') + '</td>'
                    + '<td class="inv-td-generic px-4 py-2 sm:px-5 sm:py-2 text-slate-600 dark:text-slate-400">' + escapeHtml(row.generic_name || '—') + '</td>'
                    + '<td class="inv-td-barcode px-4 py-2 sm:px-5 sm:py-2 text-slate-500 dark:text-slate-400">' + escapeHtml(row.barcode || '—') + '</td>'
                    + '<td class="inv-td-brand px-4 py-2 sm:px-5 sm:py-2 text-slate-500 dark:text-slate-400 hidden">' + escapeHtml(row.brand || '—') + '</td>'
                    + '<td class="inv-td-quantity px-4 py-2 sm:px-5 sm:py-2 text-right"><div class="flex flex-col items-end gap-0.5"><span class="font-medium">' + qtyDisplay + '</span>' + quantityProgressBar(qty, reorder, st) + '</div></td>'
                    + '<td class="inv-td-reorder px-4 py-2 sm:px-5 sm:py-2 text-right text-slate-500 dark:text-slate-400">' + (reorder > 0 ? reorder : '—') + '</td>'
                    + '<td class="inv-td-unit px-4 py-2 sm:px-5 sm:py-2 text-slate-500 dark:text-slate-400 hidden">' + escapeHtml(row.unit || '—') + '</td>'
                    + '<td class="inv-td-status px-4 py-2 sm:px-5 sm:py-2">' + getStatusBadge(st) + '</td>'
                    + '<td class="inv-td-price px-4 py-2 sm:px-5 sm:py-2 text-right hidden">' + (priceVal != null ? '₱' + priceVal.toFixed(2) : '—') + '</td>'
                    + '<td class="inv-td-cost px-4 py-2 sm:px-5 sm:py-2 text-right hidden">' + (costVal != null ? '₱' + costVal.toFixed(2) : '—') + '</td>'
                    + '<td class="inv-td-category px-4 py-2 sm:px-5 sm:py-2 text-slate-500 dark:text-slate-400 hidden">' + escapeHtml(catName || '—') + '</td>'
                    + '<td class="inv-td-active px-4 py-2 sm:px-5 sm:py-2 text-center">' + (row.is_active === false ? '<span class="text-xs text-slate-400 dark:text-slate-500">Inactive</span>' : '<span class="text-slate-400 dark:text-slate-500">—</span>') + '</td>'
                    + '<td class="px-4 py-2 sm:px-5 sm:py-2"><button type="button" class="inventory-row-menu-btn p-1.5 rounded-lg text-slate-400 hover:bg-slate-200 dark:hover:bg-darkmode-500 hover:text-slate-600 transition-colors" aria-label="Actions"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg></button></td>'
                    + '</tr>';
            });
        });
        tbody.innerHTML = html;

        var paginationEl = document.getElementById('inventory-pagination');
        var paginationInfo = document.getElementById('inventory-pagination-info');
        var paginationBtns = document.getElementById('inventory-pagination-btns');
        if (totalPages > 1) {
            paginationEl.classList.remove('hidden');
            var start = (currentPage - 1) * perPage + 1;
            var end = Math.min(currentPage * perPage, sorted.length);
            paginationInfo.textContent = 'Showing ' + start + '–' + end + ' of ' + sorted.length;
            paginationBtns.innerHTML = '';
            if (currentPage > 1) {
                var prevBtn = document.createElement('button');
                prevBtn.type = 'button';
                prevBtn.className = 'px-2.5 py-1.5 rounded border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors';
                prevBtn.textContent = 'Previous';
                prevBtn.addEventListener('click', function () { currentPage--; renderTable(); });
                paginationBtns.appendChild(prevBtn);
            }
            var nextBtn = document.createElement('button');
            nextBtn.type = 'button';
            nextBtn.className = 'px-2.5 py-1.5 rounded border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors';
            nextBtn.textContent = 'Next';
            nextBtn.addEventListener('click', function () { if (currentPage < totalPages) { currentPage++; renderTable(); } });
            paginationBtns.appendChild(nextBtn);
        } else {
            paginationEl.classList.add('hidden');
        }

        tableWrap.classList.remove('hidden');

        document.querySelectorAll('.inv-group-toggle').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var target = btn.getAttribute('data-target');
                var rows = document.querySelectorAll('tr.' + target);
                var expanded = btn.getAttribute('aria-expanded') !== 'false';
                rows.forEach(function (r) { r.style.display = expanded ? 'none' : ''; });
                btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
                var chevron = btn.querySelector('.inv-group-chevron');
                if (chevron) chevron.style.transform = expanded ? 'rotate(-90deg)' : '';
            });
        });

        document.querySelectorAll('.inventory-row').forEach(function (tr) {
            tr.addEventListener('contextmenu', function (e) {
                e.preventDefault();
                e.stopPropagation();
                selectedRowData = { productId: tr.getAttribute('data-product-id'), branchId: tr.getAttribute('data-branch-id'), name: tr.getAttribute('data-name'), barcode: tr.getAttribute('data-barcode') };
                showInventoryActionMenu(e.clientX, e.clientY, false);
            });
        });
        document.querySelectorAll('.inventory-row-menu-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                e.preventDefault();
                var tr = btn.closest('tr');
                if (!tr) return;
                selectedRowData = { productId: tr.getAttribute('data-product-id'), branchId: tr.getAttribute('data-branch-id'), name: tr.getAttribute('data-name'), barcode: tr.getAttribute('data-barcode') };
                var rect = btn.getBoundingClientRect();
                showInventoryActionMenu(rect.left, rect.bottom, true);
            });
        });
    }

    function filterAndRender() {
        currentPage = 1;
        renderTable();
    }

    document.addEventListener('click', function () { contextMenu.classList.add('hidden'); });
    contextMenu.addEventListener('click', function (e) { e.stopPropagation(); });

    contextView.addEventListener('click', function () {
        if (selectedRowData && selectedRowData.productId) {
            Swal.fire({
                title: selectedRowData.name || 'Product',
                html: '<p class="text-slate-600 dark:text-slate-400">Product ID: ' + escapeHtml(selectedRowData.productId) + '</p><p class="text-slate-500 dark:text-slate-500 text-sm mt-1">Barcode: ' + escapeHtml(selectedRowData.barcode || '—') + '</p><p class="text-sm mt-2">Full product edit is available in Products module.</p>',
                icon: 'info'
            });
        }
        contextMenu.classList.add('hidden');
    });

    if (contextLowStock) {
        contextLowStock.href = '{{ route("dashboard.dashboard") }}#dashboard-low-stock-list';
    }

    var attentionBtn = document.getElementById('inventory-attention-btn');
    if (attentionBtn) {
        attentionBtn.addEventListener('click', function () {
            if (statusFilter) {
                statusFilter.value = 'attention';
                filterAndRender();
            }
        });
    }
    var columnsBtn = document.getElementById('inventory-columns-btn');
    var columnsDropdown = document.getElementById('inventory-columns-dropdown');
    if (columnsBtn && columnsDropdown) {
        columnsBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            columnsDropdown.classList.toggle('hidden');
        });
        document.addEventListener('click', function () { columnsDropdown.classList.add('hidden'); });
        columnsDropdown.addEventListener('click', function (e) { e.stopPropagation(); });
        var colMap = { 'col-product': 'product', 'col-generic': 'generic', 'col-barcode': 'barcode', 'col-brand': 'brand', 'col-quantity': 'quantity', 'col-reorder': 'reorder', 'col-unit': 'unit', 'col-status': 'status', 'col-price': 'price', 'col-cost': 'cost', 'col-category': 'category' };
        Object.keys(colMap).forEach(function (id) {
            var check = document.getElementById(id);
            var key = colMap[id];
            if (!check) return;
            check.addEventListener('change', function () {
                var show = check.checked;
                document.querySelectorAll('.inv-th-' + key).forEach(function (el) { el.classList.toggle('hidden', !show); });
                document.querySelectorAll('.inv-td-' + key).forEach(function (el) { el.classList.toggle('hidden', !show); });
            });
        });
    }
    document.querySelectorAll('.inv-stat-card').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var filter = btn.getAttribute('data-filter');
            if (statusFilter && (filter === 'all' || filter === 'critical' || filter === 'low' || filter === 'out')) {
                statusFilter.value = filter;
                filterAndRender();
            }
        });
    });
    if (companyFilter) companyFilter.addEventListener('change', filterAndRender);
    branchFilter.addEventListener('change', filterAndRender);
    statusFilter.addEventListener('change', filterAndRender);
    searchInput.addEventListener('input', function () {
        if (searchInput._timer) clearTimeout(searchInput._timer);
        searchInput._timer = setTimeout(filterAndRender, 300);
    });
    refreshBtn.addEventListener('click', function () {
        refreshBtn.disabled = true;
        loadStockLevels();
        setTimeout(function () { refreshBtn.disabled = false; }, 500);
    });

    document.addEventListener('click', function (e) {
        var th = e.target && e.target.closest && e.target.closest('.inventory-sort');
        if (!th) return;
        var key = th.getAttribute('data-sort');
        if (key === sortKey) sortDir = -sortDir; else { sortKey = key; sortDir = 1; }
        filterAndRender();
    });

    var exportBtn = document.getElementById('inventory-export-csv');
    if (exportBtn) {
        exportBtn.addEventListener('click', function () {
            var filtered = filterRows();
            var sorted = sortRows(filtered);
            if (sorted.length === 0) {
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'info', title: 'No data', text: 'No inventory items to export.' });
                return;
            }
            var headers = ['Company', 'Branch', 'Product', 'Generic name', 'Barcode', 'Brand', 'Quantity', 'Reorder level', 'Unit', 'Status', 'Price', 'Cost', 'Category', 'Active'];
            var csv = headers.join(',') + '\n';
            sorted.forEach(function (row) {
                var companyName = (row.company && row.company.name) ? row.company.name : (row.branch && row.branch.company && row.branch.company.name) ? row.branch.company.name : '';
                var branchName = (row.branch && row.branch.name) ? row.branch.name : '';
                var qty = parseFloat(row.total_quantity) || 0;
                var reorder = row.reorder_level != null ? row.reorder_level : '';
                var st = getStatusType(row);
                var statusLabel = { out: 'Out of stock', critical: 'Critical', low: 'Low', ok: 'OK' }[st] || 'OK';
                var catName = (row.category && row.category.name) ? row.category.name : '';
                var price = row.price != null ? row.price : '';
                var cost = row.cost != null ? row.cost : '';
                csv += '"' + String(companyName).replace(/"/g, '""') + '","' + String(branchName).replace(/"/g, '""') + '","' + String(row.name || '').replace(/"/g, '""') + '","' + String(row.generic_name || '').replace(/"/g, '""') + '","' + String(row.barcode || '').replace(/"/g, '""') + '","' + String(row.brand || '').replace(/"/g, '""') + '",' + qty + ',' + reorder + ',"' + String(row.unit || '').replace(/"/g, '""') + '","' + statusLabel + '",' + price + ',' + cost + ',"' + String(catName).replace(/"/g, '""') + '","' + (row.is_active === false ? 'No' : 'Yes') + '"\n';
            });
            var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'inventory-' + new Date().toISOString().slice(0, 10) + '.csv';
            link.click();
            URL.revokeObjectURL(link.href);
            if (typeof showToastNotification === 'function') showToastNotification('success', 'Exported', 'Inventory CSV downloaded.');
        });
    }

    document.addEventListener('contextmenu', function (e) {
        if (!contextMenu.contains(e.target)) contextMenu.classList.add('hidden');
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') contextMenu.classList.add('hidden');
    });
    if (tableWrap) tableWrap.addEventListener('contextmenu', function (e) {
        if (e.target.closest && e.target.closest('.inventory-row')) return;
        e.preventDefault();
    });

    loadBranches();
    loadStockLevels();
})();
</script>
@endpush
