@extends('super-admin.layouts.pos')

@section('title', 'POS (Cashier)')
@section('breadcrumb', 'POS')

@section('content')
    {{-- POS status bar: compact single row --}}
    <div class="mt-1 mb-2 px-3 sm:px-0 print:hidden">
        <div class="mx-auto max-w-6xl">
            <div class="flex flex-wrap items-center justify-between gap-x-2 gap-y-1.5 rounded-xl bg-white/95 dark:bg-darkmode-800 shadow-sm ring-1 ring-slate-200/80 dark:ring-darkmode-600 px-2.5 py-1.5 sm:px-4 sm:py-2">
                <div class="flex flex-wrap items-center gap-x-1.5 gap-y-1 text-[10px] sm:text-[11px] text-slate-600 dark:text-slate-400">
                    <span class="inline-flex items-center gap-1 rounded px-1.5 py-0.5 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300 font-semibold"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span><span id="pos-status-ready">POS ready</span></span>
                    <span class="text-slate-400 dark:text-slate-500">·</span>
                    <span id="pos-shift-label">Shift: Day</span>
                    <span class="text-slate-400 dark:text-slate-500">·</span>
                    <span id="pos-branch-label" class="truncate max-w-[160px] sm:max-w-[220px]">Branch: —</span>
                    <span class="text-slate-400 dark:text-slate-500">·</span>
                    <span id="pos-terminal-label">Terminal: —</span>
                    <span class="text-slate-400 dark:text-slate-500">·</span>
                    <span id="pos-cashier-label" class="truncate max-w-[120px] sm:max-w-[160px]">Cashier: —</span>
                    <span class="hidden md:inline text-slate-400 dark:text-slate-500">·</span>
                    <span id="pos-status-printer" class="hidden md:inline whitespace-nowrap">Printer: Online</span>
                    <span id="pos-status-network" class="hidden lg:inline">· <span class="whitespace-nowrap">Network: Connected</span></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <button type="button" id="pos-lock-btn" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 dark:border-darkmode-600 bg-slate-50 dark:bg-darkmode-700 px-2 py-1 text-[11px] font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-darkmode-600 transition-colors" title="Lock POS"><svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg><span class="hidden sm:inline">Lock</span></button>
                    <button type="button" id="pos-logout-btn" class="inline-flex items-center gap-1 rounded-lg bg-rose-500 px-2.5 py-1 text-[11px] font-semibold text-white hover:bg-rose-600 transition-colors" title="Logout"><svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg><span class="hidden sm:inline">Logout</span></button>
                </div>
            </div>
        </div>
    </div>

    {{-- Page title and actions: compact header --}}
    <div class="intro-y mt-2 sm:mt-3">
        <div class="rounded-xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm">
            <div class="px-3 py-2 sm:px-4 sm:py-2.5 flex flex-wrap items-center justify-between gap-2">
                <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 min-w-0">
                    <h1 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-slate-100">POS</h1>
                    <span class="rounded-md bg-slate-100 dark:bg-darkmode-700 px-1.5 py-0.5 text-[10px] font-semibold text-slate-600 dark:text-slate-300 uppercase">Cashier</span>
                    <span class="text-slate-400 dark:text-slate-500 hidden sm:inline">·</span>
                    <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 hidden sm:inline">Search items, add to order, collect payment.</p>
                    <span class="rounded bg-emerald-50 dark:bg-emerald-900/20 px-1.5 py-0.5 text-[10px] font-medium text-emerald-700 dark:text-emerald-300">VAT 12%</span>
                </div>
                <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 shrink-0">
                    <span id="pos-date-label" class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 px-2 py-1 rounded-lg bg-slate-50 dark:bg-darkmode-700/80">Today · —</span>
                    <div id="pos-shortcuts-bar" class="inline-flex flex-wrap items-center gap-x-1.5 gap-y-0.5 px-2 py-1 rounded-lg bg-slate-100 dark:bg-darkmode-700 border border-slate-200/80 dark:border-darkmode-600 text-[10px] text-slate-600 dark:text-slate-300">
                        <span class="font-semibold text-slate-500 dark:text-slate-400">F1</span> New <span class="text-slate-400">·</span>
                        <span class="font-semibold">F2</span> Scan <span class="text-slate-400">·</span>
                        <span class="font-semibold">F3</span> Search <span class="text-slate-400">·</span>
                        <span class="font-semibold">F4</span> Hold <span class="text-slate-400">·</span>
                        <span class="font-semibold">F5</span> Disc <span class="text-slate-400">·</span>
                        <span class="font-semibold">F6</span> SC/PWD <span class="text-slate-400">·</span>
                        <span class="font-semibold">F7</span> Clear <span class="text-slate-400">·</span>
                        <span class="font-semibold">F8</span> Complete
                    </div>
                    <button type="button" id="pos-new-sale-btn" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-primary text-white px-3 py-1.5 text-xs font-semibold shadow-sm hover:bg-primary/90 focus:ring-2 focus:ring-primary/30 min-h-[32px]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        New sale
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Main POS layout: products on the left, order/payment on the right (always 2 columns) --}}
    <div class="intro-y mt-5 grid grid-cols-12 gap-4">
        {{-- Product catalog (≈60%) --}}
        <div class="col-span-8 flex flex-col min-h-[360px]">
            {{-- Search + filters: 2 rows — search+scan top, tabs+display bottom --}}
            <div class="rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm px-3 py-3 sm:px-5 sm:py-4">
                {{-- Row 1: Search + Scan (pair) --}}
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 w-full">
                    <div class="relative text-slate-500 flex-1 min-w-0">
                        <input
                            type="text"
                            id="pos-search-input"
                            placeholder="Search product name, generic name, or barcode…"
                            class="pos-search-input w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-slate-50/50 dark:bg-darkmode-700/50 px-4 py-3 pr-10 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-primary/20 focus:border-primary dark:focus:border-primary transition"
                            autocomplete="off"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 pointer-events-none text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                    <button type="button" id="pos-scan-btn" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-200 hover:border-primary hover:bg-primary/5 hover:text-primary transition whitespace-nowrap shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M7 8h10"/><path d="M7 12h6"/><path d="M17 12h.01"/><path d="M7 16h4"/></svg>
                        Scan
                    </button>
                </div>
                {{-- Row 2: Category tabs + Display toggle --}}
                <div class="mt-3 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2 overflow-x-auto pb-0.5">
                        <button type="button" class="pos-category-chip inline-flex items-center h-9 rounded-full border-[1.5px] border-primary bg-primary text-white font-semibold text-sm px-4 whitespace-nowrap transition shadow-sm" data-category="">
                            All <span id="pos-tab-count-all" class="pos-tab-badge ml-1 rounded-full bg-white/25 px-1.5 py-0.5 text-[0.7rem] font-medium">0</span>
                        </button>
                        <button type="button" class="pos-category-chip inline-flex items-center h-9 rounded-full border-[1.5px] border-slate-200 dark:border-darkmode-500 bg-transparent text-slate-500 dark:text-slate-400 font-medium text-sm px-4 whitespace-nowrap transition hover:border-primary hover:bg-primary/5 hover:text-primary dark:hover:bg-primary/10" data-category="rx" title="Prescription Items">
                            Rx <span id="pos-tab-count-rx" class="pos-tab-badge ml-1 rounded-full bg-slate-100 dark:bg-darkmode-600 text-slate-500 dark:text-slate-400 px-1.5 py-0.5 text-[0.7rem] font-medium">0</span>
                        </button>
                        <button type="button" class="pos-category-chip inline-flex items-center h-9 rounded-full border-[1.5px] border-slate-200 dark:border-darkmode-500 bg-transparent text-slate-500 dark:text-slate-400 font-medium text-sm px-4 whitespace-nowrap transition hover:border-primary hover:bg-primary/5 hover:text-primary dark:hover:bg-primary/10" data-category="otc">
                            OTC <span id="pos-tab-count-otc" class="pos-tab-badge ml-1 rounded-full bg-slate-100 dark:bg-darkmode-600 text-slate-500 dark:text-slate-400 px-1.5 py-0.5 text-[0.7rem] font-medium">0</span>
                        </button>
                        <button type="button" class="pos-category-chip inline-flex items-center h-9 rounded-full border-[1.5px] border-slate-200 dark:border-darkmode-500 bg-transparent text-slate-500 dark:text-slate-400 font-medium text-sm px-4 whitespace-nowrap transition hover:border-primary hover:bg-primary/5 hover:text-primary dark:hover:bg-primary/10" data-category="supplies">
                            Supplies <span id="pos-tab-count-supplies" class="pos-tab-badge ml-1 rounded-full bg-slate-100 dark:bg-darkmode-600 text-slate-500 dark:text-slate-400 px-1.5 py-0.5 text-[0.7rem] font-medium">0</span>
                        </button>
                    </div>
                    <div class="inline-flex items-center gap-1 rounded-full bg-slate-100 dark:bg-darkmode-700 px-1.5 py-1">
                        <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400 px-1.5">Display</span>
                        <button type="button" id="pos-view-grid" class="pos-display-btn inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-white shadow-sm" title="Grid view">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        </button>
                        <button type="button" id="pos-view-list" class="pos-display-btn inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:text-primary hover:bg-white dark:hover:bg-darkmode-600 transition" title="List view">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><circle cx="4" cy="6" r="1.5"/><circle cx="4" cy="12" r="1.5"/><circle cx="4" cy="18" r="1.5"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Product grid / list --}}
            <div class="mt-4 flex-1 min-h-[260px] rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm flex flex-col">
                <div id="pos-products-empty" class="flex-1 flex flex-col items-center justify-center text-center px-6 py-10 space-y-3">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 dark:bg-darkmode-700 text-slate-500 dark:text-slate-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="3"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                    </span>
                    <div>
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-200">No items loaded yet</p>
                        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Use search, categories, or barcode scan. Once connected to the API, products will appear here.</p>
                    </div>
                </div>
                <div id="pos-products-grid" class="hidden flex-1 overflow-auto">
                    <div class="grid grid-cols-3 gap-3 sm:gap-4 p-4 sm:p-5 w-full">
                        {{-- Product cards will be rendered here by JavaScript --}}
                    </div>
                </div>
                <div id="pos-products-list" class="hidden flex-1 overflow-auto">
                    <div class="divide-y divide-slate-100 dark:divide-darkmode-600 w-full">
                        {{-- Product list rows will be rendered here by JavaScript --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Current order / payment summary (≈40%) --}}
        <div class="col-span-4 flex flex-col gap-3 sticky top-4 self-start">
            {{-- Current order --}}
            <div class="pos-order-panel flex flex-col rounded-2xl border border-slate-200/90 bg-slate-50/90 shadow-lg backdrop-blur-sm">
                <div class="flex items-start justify-between gap-2 border-b border-slate-200 bg-white px-4 py-2.5 sm:px-5 sm:py-3">
                    <div>
                        <h2 class="text-sm sm:text-base font-semibold text-slate-900 flex items-center gap-2">
                            Current order
                            <span id="pos-order-item-badge" class="inline-flex items-center justify-center rounded-full bg-primary/15 text-primary px-2.5 py-0.5 text-[11px] font-semibold hidden">0 items</span>
                        </h2>
                        <p class="mt-0.5 text-xs text-slate-500">
                            <span id="pos-order-type-label">Walk-in</span> ·
                            <span id="pos-table-label">Counter sale</span>
                        </p>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span id="pos-or-badge" class="inline-flex items-center rounded-full border border-amber-300 bg-amber-50 px-3 py-1 text-[11px] font-semibold text-amber-700">
                            OR: <span id="pos-or-placeholder" class="ml-1 font-semibold tracking-wide">Pending</span>
                        </span>
                        <div class="flex items-center gap-1.5">
                            <button type="button" id="pos-hold-order-btn" class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-[11px] font-medium text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-colors" title="Hold current order. If cart is empty, click to retrieve a held order."><span id="pos-hold-btn-text">Hold</span></button>
                            <button type="button" id="pos-clear-order-btn" class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-[11px] font-medium text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6v12"/><path d="M16 6v12"/><path d="M10 6V4h4v2"/></svg>
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
                <div id="pos-order-items" class="flex-1 min-h-[140px] max-h-64 overflow-y-auto mx-3 sm:mx-4 my-2 px-3 sm:px-4 py-2 space-y-2 rounded-xl border border-dashed border-slate-200 bg-white font-mono text-[11px] leading-relaxed">
                    <div class="px-2 py-5 sm:px-3 text-center text-slate-400">
                        No items in the order yet. Tap <span class="font-semibold text-sky-600">Add</span> on items from the list to build an order.
                    </div>
                </div>
                <div class="border-t border-slate-200 px-4 py-2.5 sm:px-5 sm:py-3 space-y-1.5">
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span>Items</span>
                        <span id="pos-item-count">0</span>
                    </div>
                    <button
                        type="button"
                        id="pos-vat-toggle"
                        class="mt-0.5 flex w-full items-center justify-between text-xs text-slate-500 hover:text-slate-700"
                    >
                        <span class="inline-flex items-center gap-1.5">
                            <svg id="pos-vat-toggle-icon" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7 6a1 1 0 0 1 .707.293l3 3a1 1 0 0 1 0 1.414l-3 3A1 1 0 0 1 6 12.293L8.293 10 6 7.707A1 1 0 0 1 7 6z" clip-rule="evenodd" />
                            </svg>
                            <span>VAT details</span>
                        </span>
                        <span id="pos-vat-summary" class="text-xs font-semibold text-slate-600">₱0.00</span>
                    </button>
                    {{-- VAT breakdown: collapsible in UI, always visible on print --}}
                    <div id="pos-vat-breakdown" class="mt-1 space-y-0.5 hidden print:block">
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span>Total sales (VAT inclusive)</span>
                            <span id="pos-subtotal">₱0.00</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-slate-500">
                            <span>VAT Sales</span>
                            <span id="pos-vatable-sales">₱0.00</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-slate-500">
                            <span>Non‑VAT Sales</span>
                            <span id="pos-vat-exempt">₱0.00</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-slate-500">
                            <span>Zero-Rated Sales</span>
                            <span id="pos-zero-rated">₱0.00</span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span>Total VAT (12%)</span>
                            <span id="pos-vat-amount">₱0.00</span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span>Total Discount</span>
                            <span id="pos-total-discount">₱0.00</span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span>VAT Exemption</span>
                            <span id="pos-vat-exemption">₱0.00</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span>Discounts</span>
                        <div class="flex items-center gap-1">
                            <button type="button" id="pos-sc-pwd-btn" class="inline-flex items-center gap-1 rounded-lg bg-emerald-600 text-white px-2.5 py-1 text-[11px] font-semibold hover:bg-emerald-700 transition-colors">SC/PWD 20%</button>
                            <div class="relative inline-block">
                                <button type="button" id="pos-discount-dropdown-btn" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-slate-50 dark:bg-darkmode-700 px-2.5 py-1 text-[11px] font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-darkmode-600">Add</button>
                                <div id="pos-discount-dropdown" class="hidden absolute right-0 top-full mt-1 z-20 min-w-[160px] rounded-lg border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-lg py-1">
                                    <button type="button" class="pos-discount-option w-full text-left px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-darkmode-700" data-type="sc_pwd">SC/PWD (20%)</button>
                                    <button type="button" class="pos-discount-option w-full text-left px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-darkmode-700" data-type="employee">Employee discount</button>
                                    <button type="button" class="pos-discount-option w-full text-left px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-darkmode-700" data-type="promo">Promo discount</button>
                                    <button type="button" class="pos-discount-option w-full text-left px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-darkmode-700" data-type="manual">Manual discount</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="pos-applied-discounts" class="space-y-0.5 hidden"></div>
                    <div class="mt-2 flex items-center justify-between rounded-xl bg-sky-50 border border-sky-100 px-3 py-3">
                        <span class="text-xs font-semibold text-slate-600">Total due</span>
                        <span id="pos-total-due" class="text-lg sm:text-xl font-bold text-sky-900">₱0.00</span>
                    </div>
                    <div class="flex flex-col gap-2 pt-1 border-t border-dashed border-slate-200 mt-2">
                        <div class="space-y-1">
                            <label for="pos-customer-name" class="text-xs font-medium text-slate-600">
                                Customer (optional)
                            </label>
                            <input
                                type="text"
                                id="pos-customer-name"
                                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition"
                                placeholder="Customer name or ID"
                                autocomplete="off"
                            >
                        </div>
                        <div class="space-y-1">
                            <label for="pos-customer-address" class="text-xs font-medium text-slate-600">
                                Address (optional)
                            </label>
                            <input
                                type="text"
                                id="pos-customer-address"
                                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition"
                                placeholder="Customer address"
                                autocomplete="off"
                            >
                        </div>
                        <div class="space-y-1 hidden">
                            <label for="pos-order-note" class="text-xs font-medium text-slate-600">
                                Order notes (optional)
                            </label>
                            <textarea
                                id="pos-order-note"
                                rows="2"
                                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition resize-none"
                                placeholder="Add special instructions (e.g. loyalty ID, allergy notes)"
                            ></textarea>
                        </div>
                    </div>
                    <div class="hidden flex items-center justify-between text-xs text-slate-500">
                        <div class="flex items-center gap-1.5">
                            <span>Service charge</span>
                            <span id="pos-service-charge-amount" class="hidden text-xs font-semibold text-slate-700">₱0.00</span>
                        </div>
                        <button type="button" id="pos-service-charge-btn" class="text-xs font-medium text-primary hover:underline">Add</button>
                    </div>
                </div>
                <div id="pos-bir-footer" class="hidden border-t border-amber-100 bg-amber-50 px-4 py-2 sm:px-5 text-[10px] text-amber-700 space-y-0.5">
                    <div id="pos-bir-tin-ptu"></div>
                    <p id="pos-bir-disclaimer" class="italic">This document is not valid for claim of input tax.</p>
                </div>
            </div>

            {{-- Payment panel --}}
            <div class="rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm p-3.5 sm:p-4 space-y-2.5">
                <div class="flex items-center justify-between gap-2">
                    <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                        Payment
                    </h3>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 dark:bg-darkmode-700 px-2.5 py-1 text-[11px] font-medium text-slate-600 dark:text-slate-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                        <span id="pos-tender-label">Mode: Cash</span>
                    </span>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="button" class="pos-tender-type-btn flex-1 min-w-[0] inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 dark:border-darkmode-500 bg-slate-100 dark:bg-darkmode-700 px-3 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-200 hover:border-slate-300 dark:hover:bg-darkmode-600 dark:hover:border-darkmode-500 transition-colors" data-type="cash">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="12" cy="12" r="2.5"/></svg>
                        <span>Cash</span>
                    </button>
                    <button type="button" class="pos-tender-type-btn flex-1 min-w-[0] inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 dark:border-darkmode-500 bg-slate-100 dark:bg-darkmode-700 px-3 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-200 hover:border-slate-300 dark:hover:bg-darkmode-600 dark:hover:border-darkmode-500 transition-colors" data-type="card">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/><path d="M7 15h3"/></svg>
                        <span>Card</span>
                    </button>
                    <button type="button" class="pos-tender-type-btn flex-1 min-w-[0] inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 dark:border-darkmode-500 bg-slate-100 dark:bg-darkmode-700 px-3 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-200 hover:border-slate-300 dark:hover:bg-darkmode-600 dark:hover:border-darkmode-500 transition-colors" data-type="ewallet">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/>
                        </svg>
                        <span>E-wallet / QR</span>
                    </button>
                </div>
                <div class="space-y-2 hidden">
                    <label for="pos-amount-received" class="text-xs font-medium text-slate-600 dark:text-slate-300">
                        Amount received (₱)
                    </label>
                    <input
                        type="tel"
                        inputmode="decimal"
                        id="pos-amount-received"
                        class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-slate-50/70 dark:bg-darkmode-700/60 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition"
                        placeholder="Enter amount received"
                    >
                    <div class="flex items-center justify-between rounded-xl bg-slate-100 dark:bg-darkmode-700 px-3 py-2.5 mt-2">
                        <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Change</span>
                        <span id="pos-change-amount" class="text-lg font-bold text-slate-800 dark:text-slate-100">₱0.00</span>
                    </div>
                </div>
                <button
                    type="button"
                    id="pos-complete-sale-btn"
                    class="mt-1 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 py-3 text-sm sm:text-base font-semibold text-white shadow-sm hover:bg-primary/90 focus:ring-4 focus:ring-primary/20 transition-colors disabled:opacity-60 disabled:cursor-not-allowed"
                    title="Add items to complete a sale"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    <span id="pos-complete-sale-label">Complete sale</span>
                </button>
                <p class="mt-1 text-[11px] text-slate-400 dark:text-slate-500">
                    Official receipt number and BIR-required details will be generated after completing the sale.
                </p>
            </div>
        </div>
    </div>

    {{-- Line void manager approval modal (custom, not Swal) --}}
    <div id="pos-void-modal" class="fixed inset-0 z-[100] hidden" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="pos-void-modal-title">
        <div id="pos-void-modal-backdrop" class="absolute inset-0 bg-slate-900/50 dark:bg-slate-900/70 backdrop-blur-sm transition-opacity"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="relative w-full max-w-md rounded-2xl border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-xl" role="document">
                <div class="p-6 sm:p-6">
                    <h2 id="pos-void-modal-title" class="text-lg font-semibold text-slate-800 dark:text-slate-100">Void line?</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                        Remove this item from the order. Enter manager PIN or password for this branch.
                    </p>
                    <div class="mt-4 space-y-2">
                        <div>
                            <label for="pos-void-manager-input-modal" class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">
                                Manager PIN or password
                            </label>
                            <input
                                type="password"
                                id="pos-void-manager-input-modal"
                                class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition"
                                placeholder="••••••••"
                                autocomplete="off"
                                maxlength="255"
                            >
                            <p id="pos-void-manager-error" class="mt-1 text-xs text-rose-600 dark:text-rose-400 hidden">
                                Enter manager PIN or password.
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-wrap items-center justify-end gap-2">
                        <button type="button" id="pos-void-modal-cancel" class="rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors">
                            Cancel
                        </button>
                        <button type="button" id="pos-void-modal-confirm" class="rounded-lg bg-rose-600 hover:bg-rose-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors focus:ring-4 focus:ring-rose-500/30">
                            Void
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SC/PWD Discount modal (custom, not Swal) --}}
    <div id="pos-sc-pwd-modal" class="fixed inset-0 z-[100] hidden" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="pos-sc-pwd-modal-title">
        <div id="pos-sc-pwd-modal-backdrop" class="absolute inset-0 bg-slate-900/50 dark:bg-slate-900/70 backdrop-blur-sm transition-opacity"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-xl" role="document">
                <div class="p-6 sm:p-6">
                    <h2 id="pos-sc-pwd-modal-title" class="text-lg font-semibold text-slate-800 dark:text-slate-100">SC / PWD Discount</h2>
                    <p class="mt-0.5 text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">20% under RA 9994</p>
                    <div class="mt-4 rounded-xl border border-slate-200 dark:border-darkmode-600 bg-slate-50 dark:bg-darkmode-700/60 p-3">
                        <p class="text-sm text-slate-600 dark:text-slate-300">ID and customer details are required for BIR audit. Enter valid SC/PWD ID to apply 20% discount on qualifying items.</p>
                    </div>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="pos-sc-pwd-type" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1.5">ID type</label>
                            <select id="pos-sc-pwd-type" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition">
                                <option value="senior_citizen">Senior Citizen</option>
                                <option value="pwd">PWD</option>
                            </select>
                        </div>
                        <div>
                            <label for="pos-sc-pwd-id" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1.5">SC/PWD ID number <span class="text-rose-500">*</span></label>
                            <input type="text" id="pos-sc-pwd-id" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition" placeholder="e.g. 1234-5678-9012" maxlength="50" autocomplete="off">
                            <p id="pos-sc-pwd-id-error" class="mt-1 text-xs text-rose-600 dark:text-rose-400 hidden">Please enter SC/PWD ID number.</p>
                        </div>
                        <div>
                            <label for="pos-sc-pwd-name" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1.5">Customer name <span class="text-slate-400 font-normal">(optional)</span></label>
                            <input type="text" id="pos-sc-pwd-name" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition" placeholder="Full name" maxlength="255" autocomplete="off">
                        </div>
                        <div class="rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/20 p-3 flex items-center justify-between gap-2">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Discount amount</span>
                            <span id="pos-sc-pwd-discount-amount" class="text-lg font-bold text-emerald-700 dark:text-emerald-300">₱0.00</span>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-wrap items-center justify-end gap-2">
                        <button type="button" id="pos-sc-pwd-modal-cancel" class="rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors">
                            Cancel
                        </button>
                        <button type="button" id="pos-sc-pwd-modal-apply" class="rounded-lg bg-emerald-600 hover:bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors focus:ring-4 focus:ring-emerald-500/30">
                            Apply 20% discount
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment amount modal (custom, not Swal) --}}
    <div id="pos-payment-modal" class="fixed inset-0 z-[100] hidden" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="pos-payment-modal-title">
        <div id="pos-payment-modal-backdrop" class="absolute inset-0 bg-slate-900/50 dark:bg-slate-900/70 backdrop-blur-sm transition-opacity"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="relative w-full max-w-md rounded-2xl border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-xl" role="document">
                <div class="p-6 sm:p-6">
                    <h2 id="pos-payment-modal-title" class="text-lg font-semibold text-slate-800 dark:text-slate-100">Payment</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                        Total due: <span id="pos-payment-modal-total" class="font-semibold">₱0.00</span>
                    </p>
                    <div class="mt-4 space-y-3">
                        <div>
                            <label for="pos-payment-modal-amount" class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">
                                Amount received (₱)
                            </label>
                            <input
                                type="tel"
                                inputmode="decimal"
                                id="pos-payment-modal-amount"
                                class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition"
                                placeholder="Enter amount received"
                            >
                            <p id="pos-payment-modal-error" class="mt-1 text-xs text-rose-600 dark:text-rose-400 hidden"></p>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 dark:bg-darkmode-700 px-3 py-2.5">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Change</span>
                            <span id="pos-payment-modal-change" class="text-lg font-bold text-slate-800 dark:text-slate-100">₱0.00</span>
                        </div>
                        <div id="pos-payment-modal-card-ewallet-fields" class="hidden space-y-3 pt-2 border-t border-slate-200 dark:border-darkmode-600">
                            <div>
                                <label for="pos-payment-modal-reference" class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">Reference / Approval no.</label>
                                <input type="text" id="pos-payment-modal-reference" maxlength="100" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition" placeholder="e.g. 123456789">
                            </div>
                            <div>
                                <label for="pos-payment-modal-provider" class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">Provider</label>
                                <input type="text" id="pos-payment-modal-provider" maxlength="100" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition" placeholder="e.g. GCash, Maya, Visa, Mastercard">
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-wrap items-center justify-end gap-2">
                        <button type="button" id="pos-payment-modal-cancel" class="rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors">
                            Cancel
                        </button>
                        <button type="button" id="pos-payment-modal-apply" class="rounded-lg bg-primary hover:bg-primary/90 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors focus:ring-4 focus:ring-primary/30">
                            Set amount
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Rx prescription modal (custom, not Swal) --}}
    <div id="pos-rx-modal" class="fixed inset-0 z-[100] hidden" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="pos-rx-modal-title">
        <div id="pos-rx-modal-backdrop" class="absolute inset-0 bg-slate-900/50 dark:bg-slate-900/70 backdrop-blur-sm transition-opacity"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-xl" role="document">
                <div class="p-6 sm:p-6">
                    <h2 id="pos-rx-modal-title" class="text-lg font-semibold text-slate-800 dark:text-slate-100">Prescription required</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                        This order contains Rx items. Enter prescription details before continuing.
                    </p>
                    <ul id="pos-rx-modal-list" class="mt-3 list-disc pl-5 space-y-0.5 text-sm text-slate-700 dark:text-slate-200"></ul>
                    <div class="mt-4 space-y-3">
                        <div>
                            <label for="pos-rx-number" class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">
                                Prescription number <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="pos-rx-number"
                                class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition"
                                placeholder="e.g. RX-2024-001"
                                maxlength="100"
                                autocomplete="off"
                            >
                            <p id="pos-rx-number-error" class="mt-1 text-xs text-rose-600 dark:text-rose-400 hidden">Enter prescription number for Rx items.</p>
                        </div>
                        <div>
                            <label for="pos-rx-doctor" class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">
                                Prescribing doctor <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="pos-rx-doctor"
                                class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition"
                                placeholder="Doctor name"
                                maxlength="120"
                                autocomplete="off"
                            >
                            <p id="pos-rx-doctor-error" class="mt-1 text-xs text-rose-600 dark:text-rose-400 hidden">Enter prescribing doctor name.</p>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-wrap items-center justify-end gap-2">
                        <button type="button" id="pos-rx-modal-cancel" class="rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors">
                            Cancel
                        </button>
                        <button type="button" id="pos-rx-modal-apply" class="rounded-lg bg-primary hover:bg-primary/90 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors focus:ring-4 focus:ring-primary/30">
                            Proceed with sale
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    var apiBase = '{{ url('/api/v1') }}';
    var dashboardBase = '{{ url('/dashboard') }}';
    var token = localStorage.getItem('super_admin_token');
    if (!token) {
        window.location.href = '{{ route('dashboard.login') }}';
        return;
    }
    var headers = {
        headers: {
            Authorization: 'Bearer ' + token,
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };

    function formatMoney(n) {
        if (n == null || isNaN(n)) return '₱0.00';
        var x = parseFloat(n);
        return '₱' + x.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function parseAmount(value) {
        if (value == null) return 0;
        var v = String(value).replace(/,/g, '').trim();
        var n = parseFloat(v);
        return isNaN(n) ? 0 : n;
    }

    var VAT_RATE = 0.12;
    var allProducts = [];
    var filteredProducts = [];
    var currentCategory = '';
    var viewMode = 'grid';
    var cartItems = [];
    var appliedDiscounts = [];
    var serviceChargeAmount = 0;
    var heldOrders = [];
    var currentTerminalId = null;
    var currentTenderType = 'cash';
    var birDisplay = { tin: '', ptu_number: '', footer_text: 'This document is not valid for claim of input tax.' };
    var STOCK_LOW = 10;
    var STOCK_CRITICAL = 2;
    var pendingVoidProductId = null;
    var POS_CART_STORAGE_KEY = 'landogz_pos_cart';

    function saveCartToStorage() {
        try {
            var state = { cartItems: cartItems, appliedDiscounts: appliedDiscounts, serviceChargeAmount: serviceChargeAmount };
            localStorage.setItem(POS_CART_STORAGE_KEY, JSON.stringify(state));
        } catch (e) {}
    }

    function loadCartFromStorage() {
        try {
            var raw = localStorage.getItem(POS_CART_STORAGE_KEY);
            if (!raw) return;
            var state = JSON.parse(raw);
            if (state && Array.isArray(state.cartItems)) {
                cartItems = state.cartItems;
                appliedDiscounts = state.appliedDiscounts || [];
                serviceChargeAmount = parseFloat(state.serviceChargeAmount) || 0;
                renderCart();
                renderAppliedDiscounts();
            }
        } catch (e) {}
    }

    function getStockBadgeHtml(stock) {
        if (stock <= 0) {
            return '<span class="inline-flex items-center gap-1 rounded-full border border-slate-200 dark:border-darkmode-500 bg-slate-100 dark:bg-darkmode-600 px-1.5 py-0.5 text-[10px] font-medium text-slate-500 dark:text-slate-400">Out of stock</span>';
        }
        if (stock <= STOCK_CRITICAL) {
            return '<span class="inline-flex items-center gap-1 rounded-full border border-rose-200 dark:border-rose-800 bg-rose-50 dark:bg-rose-900/30 px-1.5 py-0.5 text-[10px] font-medium text-rose-700 dark:text-rose-300 animate-pulse">' + stock + ' in stock</span>';
        }
        if (stock <= STOCK_LOW) {
            return '<span class="inline-flex items-center gap-1 rounded-full border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/30 px-1.5 py-0.5 text-[10px] font-medium text-amber-700 dark:text-amber-300 animate-pulse">' + stock + ' in stock</span>';
        }
        return '<span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 px-1.5 py-0.5 text-[10px] font-medium text-emerald-700 dark:text-emerald-300">' + stock + ' in stock</span>';
    }

    function getCategoryPillClass(cat) {
        if (!cat) return 'bg-slate-100 dark:bg-darkmode-700 text-slate-600 dark:text-slate-400';
        var t = (cat.type || '').toLowerCase();
        var n = (cat.name || '').toLowerCase();
        if (t === 'rx' || n.indexOf('rx') >= 0 || n.indexOf('prescription') >= 0) return 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300';
        if (t === 'otc' || n.indexOf('otc') >= 0) return 'bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300';
        if (n.indexOf('vitamin') >= 0) return 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300';
        if (n.indexOf('supplies') >= 0 || n.indexOf('first aid') >= 0) return 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300';
        return 'bg-slate-100 dark:bg-darkmode-700 text-slate-600 dark:text-slate-400';
    }
    var productsPerPage = 12;
    var productsCurrentPage = 1;

    // Header: date/time
    var dateLabel = document.getElementById('pos-date-label');
    if (dateLabel) {
        var now = new Date();
        var options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' };
        dateLabel.textContent = now.toLocaleString('en-PH', options);
    }

    // Detect logged-in user, company, branch
    axios.get(apiBase + '/auth/me', headers)
        .then(function (r) {
            var d = r.data && r.data.data ? r.data.data : r.data;
            if (!d || !d.user) return;
            var user = d.user;
            var branch = d.branch || user.branch || null;
            var company = branch && branch.company ? branch.company : null;

            var cashierLabel = document.getElementById('pos-cashier-label');
            if (cashierLabel) {
                var roleLabel = (user.role || '').replace(/_/g, ' ');
                roleLabel = roleLabel ? roleLabel.charAt(0).toUpperCase() + roleLabel.slice(1) : '';
                cashierLabel.textContent = (user.name || user.email || 'Cashier') + (roleLabel ? ' · ' + roleLabel : '');
            }

            var branchLabel = document.getElementById('pos-branch-label');
            if (branchLabel) {
                var companyName = company && company.name ? company.name : '';
                var branchName = branch && branch.name ? branch.name : '';
                var label = '';
                if (companyName && branchName) label = companyName + ' · ' + branchName;
                else if (branchName) label = branchName;
                else if (companyName) label = companyName;
                else label = '—';
                branchLabel.textContent = 'Branch: ' + label;
            }
        })
        .catch(function () {
            // Leave defaults if /auth/me fails
        });

    // Init floating menu events
    initFloatingMenu();

    // Resolve terminal from backend using TERMINAL_API_KEY and load products for the cashier's branch
    function loadTerminalAndProducts() {
        axios.get(apiBase + '/pos/terminal/current', headers)
            .then(function (r) {
                var t = r.data && r.data.data ? r.data.data : r.data;
                if (!t) return;
                currentTerminalId = t.id;
                var terminalLabel = document.getElementById('pos-terminal-label');
                var terminalLabelInline = document.getElementById('pos-terminal-label-inline');
                if (terminalLabel || terminalLabelInline) {
                    var code = t.code || ('T' + t.id);
                    var name = t.name || '';
                    var text = 'Terminal: ' + code + (name ? ' · ' + name : '');
                    if (terminalLabel) terminalLabel.textContent = text;
                    if (terminalLabelInline) terminalLabelInline.textContent = text;
                }
                if (t.bir_display) {
                    birDisplay.tin = t.bir_display.tin || '';
                    birDisplay.ptu_number = t.bir_display.ptu_number || '';
                    birDisplay.footer_text = t.bir_display.footer_text || birDisplay.footer_text;
                }
                renderBirFooter();
                loadProducts();
                loadHeldOrders();
            })
            .catch(function (err) {
                var msg = err.response && err.response.data && err.response.data.message
                    ? err.response.data.message
                    : 'POS terminal is not registered. Please configure TERMINAL_API_KEY and register the terminal.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'POS not ready', text: msg });
                }
                var completeBtn = document.getElementById('pos-complete-sale-btn');
                if (completeBtn) completeBtn.disabled = true;
            });
    }

    function loadHeldOrders() {
        if (!currentTerminalId) return;
        axios.get(apiBase + '/pos/held-orders', { headers: headers.headers, params: { terminal_id: currentTerminalId } })
            .then(function (r) {
                var list = r.data && r.data.data ? r.data.data : (Array.isArray(r.data) ? r.data : []);
                heldOrders = list.map(function (h) {
                    var p = h.payload || {};
                    return {
                        id: h.id,
                        items: p.items || [],
                        discounts: p.discounts || [],
                        serviceCharge: p.serviceCharge || 0
                    };
                });
                updateHoldButtonText();
            })
            .catch(function () {
                heldOrders = [];
                updateHoldButtonText();
            });
    }

    function applyFilters() {
        var searchInput = document.getElementById('pos-search-input');
        var q = searchInput && searchInput.value ? searchInput.value.trim().toLowerCase() : '';
        filteredProducts = allProducts.filter(function (p) {
            var ok = true;
            if (q) {
                var name = (p.name || '').toLowerCase();
                var generic = (p.generic_name || '').toLowerCase();
                var barcode = (p.barcode || '').toLowerCase();
                ok = name.indexOf(q) !== -1 || generic.indexOf(q) !== -1 || barcode.indexOf(q) !== -1;
            }
            if (!ok) return false;
            if (!currentCategory) return true;
            var catName = p.category && p.category.name ? p.category.name.toLowerCase() : '';
            if (currentCategory === 'rx') return /rx|prescription/.test(catName);
            if (currentCategory === 'otc') return /otc|over[- ]the[- ]counter/.test(catName);
            if (currentCategory === 'supplies') return /supply|supplies|disposables?/.test(catName);
            return true;
        });
        productsCurrentPage = 1;
        renderProducts();
    }

    function countByCategory(list) {
        var all = list.length;
        var rx = 0, otc = 0, supplies = 0;
        list.forEach(function (p) {
            var catName = (p.category && p.category.name ? p.category.name : '').toLowerCase();
            if (/rx|prescription/.test(catName)) rx++;
            else if (/otc|over[- ]the[- ]counter/.test(catName)) otc++;
            else if (/supply|supplies|disposables?/.test(catName)) supplies++;
        });
        return { all: all, rx: rx, otc: otc, supplies: supplies };
    }

    function updateCategoryTabCounts() {
        var counts = countByCategory(allProducts);
        var elAll = document.getElementById('pos-tab-count-all');
        var elRx = document.getElementById('pos-tab-count-rx');
        var elOtc = document.getElementById('pos-tab-count-otc');
        var elSupplies = document.getElementById('pos-tab-count-supplies');
        if (elAll) elAll.textContent = counts.all;
        if (elRx) elRx.textContent = counts.rx;
        if (elOtc) elOtc.textContent = counts.otc;
        if (elSupplies) elSupplies.textContent = counts.supplies;
    }

    function renderProducts() {
        var emptyEl = document.getElementById('pos-products-empty');
        var gridOuter = document.getElementById('pos-products-grid');
        var gridInner = gridOuter ? gridOuter.querySelector('.grid') : null;
        var listOuter = document.getElementById('pos-products-list');
        var listInner = listOuter ? listOuter.querySelector('.divide-y') : null;
        if (!gridInner || !listInner) return;

        gridInner.innerHTML = '';
        listInner.innerHTML = '';

        if (!filteredProducts.length) {
            if (emptyEl) emptyEl.classList.remove('hidden');
            if (gridOuter) gridOuter.classList.add('hidden');
            if (listOuter) listOuter.classList.add('hidden');
            return;
        }

        if (emptyEl) emptyEl.classList.add('hidden');

        var totalFiltered = filteredProducts.length;
        var totalPages = Math.max(1, Math.ceil(totalFiltered / productsPerPage));
        if (productsCurrentPage > totalPages) productsCurrentPage = totalPages;
        var pageStart = (productsCurrentPage - 1) * productsPerPage;
        var pageProducts = filteredProducts.slice(pageStart, pageStart + productsPerPage);

        if (viewMode === 'grid') {
            if (gridOuter) gridOuter.classList.remove('hidden');
            if (listOuter) listOuter.classList.add('hidden');
            pageProducts.forEach(function (p) {
                var stock = Array.isArray(p.batches)
                    ? p.batches.reduce(function (sum, b) { return sum + (parseFloat(b.quantity) || 0); }, 0)
                    : 0;
                var price = formatMoney(p.price || 0);
                var name = p.name || 'Product';
                var generic = p.generic_name || '';
                var unit = p.unit || '';
                var cat = p.category;
                var catName = cat && cat.name ? cat.name : '';
                var catPillClass = getCategoryPillClass(cat);
                var stockBadge = getStockBadgeHtml(stock);
                var outOfStock = stock <= 0;
                var isRx = isProductRx(p);
                var card = document.createElement('button');
                card.type = 'button';
                card.className = 'pos-product-card group relative flex flex-col rounded-[14px] border-[1.5px] border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 text-left overflow-hidden shadow-sm transition-all duration-200 hover:border-primary/40 hover:shadow-md hover:-translate-y-0.5' + (outOfStock ? ' opacity-60 cursor-not-allowed' : '');
                card.setAttribute('data-product-id', p.id);
                card.disabled = outOfStock;
                card.innerHTML =
                    '<div class="flex-1 p-3 flex flex-col gap-1">' +
                        '<div class="flex items-center justify-between gap-2">' +
                            '<div class="flex items-center gap-1.5 min-w-0">' +
                                (isRx ? '<span class="inline-flex items-center rounded-md border border-rose-200 dark:border-rose-800 bg-rose-50 dark:bg-rose-900/30 px-1.5 py-0.5 text-[10px] font-bold text-rose-600 dark:text-rose-300 uppercase">Rx</span>' : '<span class="text-[11px] font-medium rounded-full px-2 py-0.5 inline-flex shrink-0 ' + catPillClass + '">' + (catName || '—') + '</span>') +
                            '</div>' +
                            (!outOfStock ? '<span class="pos-add-circle flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary text-white shadow-sm transition-all hover:scale-110 hover:bg-primary/90" aria-label="Add to order"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></span>' : '') +
                        '</div>' +
                        '<div class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-tight line-clamp-2 min-h-[2.5rem] mb-0.5">' + name + '</div>' +
                        (generic ? '<div class="text-xs text-slate-600 dark:text-slate-400 truncate">' + generic + '</div>' : '') +
                        '<div class="mt-auto flex items-center justify-between flex-wrap gap-1.5 text-[11px]">' +
                            '<span class="flex items-center gap-1 min-w-0">' + stockBadge + (unit ? ' <span class="text-slate-400 dark:text-slate-500 truncate">' + unit + '</span>' : '') + '</span>' +
                            '<span class="text-base font-bold text-primary shrink-0">' + price + '</span>' +
                        '</div>' +
                    '</div>';
                if (!outOfStock) {
                    card.addEventListener('click', function () {
                        addToCart(p);
                        card.classList.add('pos-product-card-just-added', 'border-emerald-300', 'bg-emerald-50/50', 'dark:border-emerald-700', 'dark:bg-emerald-900/20');
                        setTimeout(function () {
                            card.classList.remove('pos-product-card-just-added', 'border-emerald-300', 'bg-emerald-50/50', 'dark:border-emerald-700', 'dark:bg-emerald-900/20');
                        }, 400);
                    });
                }
                gridInner.appendChild(card);
            });
            var paginationWrap = gridOuter.querySelector('.pos-products-pagination');
            if (paginationWrap) paginationWrap.remove();
            if (totalPages > 1) {
                var pagEl = document.createElement('div');
                pagEl.className = 'pos-products-pagination flex items-center justify-between gap-2 px-4 py-2 border-t border-slate-200 dark:border-darkmode-600 bg-slate-50/50 dark:bg-darkmode-800/50';
                var prevDisabled = productsCurrentPage <= 1 ? ' disabled' : '';
                var nextDisabled = productsCurrentPage >= totalPages ? ' disabled' : '';
                pagEl.innerHTML = '<span class="text-xs text-slate-500 dark:text-slate-400">Showing ' + (pageStart + 1) + '–' + Math.min(pageStart + productsPerPage, totalFiltered) + ' of ' + totalFiltered + '</span>' +
                    '<div class="flex gap-1">' +
                    '<button type="button" class="pos-products-prev rounded-lg border border-slate-200 dark:border-darkmode-500 px-2.5 py-1 text-xs font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-darkmode-600 disabled:opacity-50"' + prevDisabled + '>Previous</button>' +
                    '<button type="button" class="pos-products-next rounded-lg border border-slate-200 dark:border-darkmode-500 px-2.5 py-1 text-xs font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-darkmode-600 disabled:opacity-50"' + nextDisabled + '>Next</button>' +
                    '</div>';
                gridOuter.appendChild(pagEl);
                pagEl.querySelector('.pos-products-prev').addEventListener('click', function () { if (productsCurrentPage > 1) { productsCurrentPage--; renderProducts(); } });
                pagEl.querySelector('.pos-products-next').addEventListener('click', function () { if (productsCurrentPage < totalPages) { productsCurrentPage++; renderProducts(); } });
            }
        } else {
            if (listOuter) listOuter.classList.remove('hidden');
            if (gridOuter) gridOuter.classList.add('hidden');
            pageProducts.forEach(function (p) {
                var stock = Array.isArray(p.batches)
                    ? p.batches.reduce(function (sum, b) { return sum + (parseFloat(b.quantity) || 0); }, 0)
                    : 0;
                var price = formatMoney(p.price || 0);
                var name = p.name || 'Product';
                var generic = p.generic_name || '';
                var unit = p.unit || '';
                var stockBadge = getStockBadgeHtml(stock);
                var outOfStock = stock <= 0;
                var row = document.createElement('div');
                row.className = 'flex items-center justify-between gap-3 px-4 py-3 sm:px-5 hover:bg-slate-50 dark:hover:bg-darkmode-700/60 transition-colors';
                row.innerHTML =
                    '<div class="min-w-0 flex-1">' +
                        '<div class="text-sm font-semibold text-slate-800 dark:text-slate-100">' + name + '</div>' +
                        (generic ? '<div class="text-xs text-slate-500 dark:text-slate-400 truncate">' + generic + '</div>' : '') +
                        '<div class="mt-0.5 text-[11px]">' + stockBadge + (unit ? ' · ' + unit : '') + '</div>' +
                    '</div>' +
                    '<div class="flex items-center gap-3 flex-shrink-0">' +
                        '<span class="text-sm font-semibold text-slate-800 dark:text-slate-100">' + price + '</span>' +
                        '<button type="button" class="inline-flex items-center justify-center rounded-lg border-2 border-primary text-primary px-2.5 py-1 text-xs font-semibold pos-add-btn bg-transparent hover:bg-primary/10' + (outOfStock ? ' opacity-50 cursor-not-allowed' : '') + '"' + (outOfStock ? ' disabled' : '') + '>Add</button>' +
                    '</div>';
                var btn = row.querySelector('.pos-add-btn');
                if (!outOfStock) {
                    btn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        addToCart(p);
                    });
                }
                listInner.appendChild(row);
            });
        }
    }

    function findCartItem(productId) {
        for (var i = 0; i < cartItems.length; i++) {
            if (cartItems[i].product_id === productId) return cartItems[i];
        }
        return null;
    }

    function isProductRx(product) {
        if (!product || !product.category) return false;
        var cat = product.category;
        var type = (cat.type || '').toLowerCase();
        var name = (cat.name || '').toLowerCase();
        return type === 'rx' || name.indexOf('rx') >= 0 || name.indexOf('prescription') >= 0;
    }

    function addToCart(product) {
        if (!product || !product.id) return;
        var existing = findCartItem(product.id);
        if (existing) {
            existing.quantity += 1;
        } else {
            cartItems.push({
                product_id: product.id,
                name: product.name || 'Product',
                generic_name: product.generic_name || '',
                unit: product.unit || '',
                unit_price: parseFloat(product.price) || 0,
                quantity: 1,
                is_rx: isProductRx(product)
            });
        }
        renderCart();
        saveCartToStorage();
    }

    function updateCartQuantity(productId, delta) {
        for (var i = 0; i < cartItems.length; i++) {
            if (cartItems[i].product_id === productId) {
                var newQty = cartItems[i].quantity + delta;
                // Do not allow quantity to drop below 1 using +/- buttons.
                // Cashier must use the Void (×) action, which is manager-protected.
                if (newQty < 1) {
                    return;
                }
                cartItems[i].quantity = newQty;
                break;
            }
        }
        renderCart();
        saveCartToStorage();
    }

    function setCartQuantity(productId, quantity) {
        var qty = parseInt(quantity, 10);
        if (isNaN(qty) || qty < 1) {
            removeCartItem(productId);
            return;
        }
        for (var i = 0; i < cartItems.length; i++) {
            if (cartItems[i].product_id === productId) {
                cartItems[i].quantity = qty;
                break;
            }
        }
        renderCart();
        saveCartToStorage();
    }

    function clearCart() {
        cartItems = [];
        appliedDiscounts = [];
        serviceChargeAmount = 0;
        var customerNameEl = document.getElementById('pos-customer-name');
        var customerAddressEl = document.getElementById('pos-customer-address');
        if (customerNameEl) customerNameEl.value = '';
        if (customerAddressEl) customerAddressEl.value = '';
        renderCart();
        renderAppliedDiscounts();
        saveCartToStorage();
    }

    function removeCartItem(productId) {
        cartItems = cartItems.filter(function (item) { return item.product_id !== productId; });
        renderCart();
        saveCartToStorage();
    }

    function renderBirFooter() {
        var tinEl = document.getElementById('pos-bir-tin-ptu');
        var disclaimerEl = document.getElementById('pos-bir-disclaimer');
        if (tinEl) {
            var parts = [];
            if (birDisplay.tin) parts.push('TIN: ' + birDisplay.tin);
            if (birDisplay.ptu_number) parts.push('PTU: ' + birDisplay.ptu_number);
            tinEl.textContent = parts.length ? parts.join(' · ') : 'TIN / PTU: Configure in BIR Settings';
        }
        if (disclaimerEl && birDisplay.footer_text) {
            disclaimerEl.textContent = birDisplay.footer_text;
        }
    }

    function renderAppliedDiscounts() {
        var container = document.getElementById('pos-applied-discounts');
        if (!container) return;
        if (!appliedDiscounts.length) {
            container.innerHTML = '';
            container.classList.add('hidden');
            return;
        }
        container.classList.remove('hidden');
        var html = '';
        appliedDiscounts.forEach(function (d, idx) {
            var label = d.type === 'sc_pwd' || d.type === 'senior_citizen' || d.type === 'pwd' ? 'SC/PWD' : (d.type === 'employee' ? 'Employee' : (d.type === 'promo' ? 'Promo' : 'Discount'));
            html += '<div class="flex items-center justify-between text-[11px] text-slate-600 dark:text-slate-300">' +
                '<span>' + label + (d.reference_id ? ' (' + d.reference_id + ')' : '') + '</span>' +
                '<span class="font-medium">-' + formatMoney(d.amount) + '</span>' +
                '<button type="button" class="pos-remove-discount ml-1 text-rose-500 hover:text-rose-600" data-idx="' + idx + '">×</button>' +
            '</div>';
        });
        container.innerHTML = html;
        container.querySelectorAll('.pos-remove-discount').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var idx = parseInt(this.getAttribute('data-idx'), 10);
                appliedDiscounts.splice(idx, 1);
                renderAppliedDiscounts();
                renderCart();
                saveCartToStorage();
            });
        });
    }

    function renderCart() {
        var container = document.getElementById('pos-order-items');
        var badgeEl = document.getElementById('pos-order-item-badge');
        if (!container) return;
        if (!cartItems.length) {
            container.innerHTML = '<div class="px-4 py-6 sm:px-5 text-center text-slate-400 text-xs">No items in the order yet. Tap <span class="font-semibold text-sky-600">Add</span> on items from the list to build an order.</div>';
            if (badgeEl) { badgeEl.classList.add('hidden'); badgeEl.textContent = '0 items'; }
        } else {
            var html = '';
            cartItems.forEach(function (item) {
                var lineTotal = item.unit_price * item.quantity;
                html += '<div class="pos-order-item flex items-center justify-between gap-3 group rounded-xl border border-slate-200 bg-white px-3 py-2 shadow-sm hover:shadow-md transition-shadow">' +
                    '<div class="min-w-0 flex-1">' +
                        '<div class="flex items-center gap-1.5">' +
                            (item.is_rx ? '<span class="inline-flex items-center justify-center rounded-full bg-rose-100 px-1.5 py-0.5 text-[10px] font-semibold text-rose-700 uppercase tracking-wide">Rx</span>' : '') +
                            '<span class="pos-order-item-name text-sm font-semibold text-slate-900">' + item.name + '</span>' +
                        '</div>' +
                        (item.generic_name ? '<div class="text-xs text-slate-500 truncate">' + item.generic_name + '</div>' : '') +
                        '<div class="mt-0.5 text-[11px] text-slate-500">₱' + (item.unit_price.toFixed ? item.unit_price.toFixed(2) : item.unit_price) + ' · x ' + item.quantity + '</div>' +
                    '</div>' +
                    '<div class="flex items-center gap-2 flex-shrink-0">' +
                        '<div class="pos-qty-control inline-flex items-center gap-1.5 bg-slate-50 rounded-full px-1.5 py-1">' +
                            '<button type="button" class="pos-cart-qty-btn inline-flex h-7 w-7 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700 hover:bg-slate-50" data-id="' + item.product_id + '" data-delta="-1">-</button>' +
                            '<input type="number" class="pos-cart-qty-input w-12 min-w-[2.5rem] text-center text-sm font-semibold text-slate-800 rounded-lg border border-slate-200 bg-white py-1 px-1 focus:ring-2 focus:ring-primary/20 focus:border-primary" min="1" value="' + item.quantity + '" data-id="' + item.product_id + '" inputmode="numeric">' +
                            '<button type="button" class="pos-cart-qty-btn inline-flex h-7 w-7 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700 hover:bg-slate-50" data-id="' + item.product_id + '" data-delta="1">+</button>' +
                        '</div>' +
                        '<span class="ml-2 text-sm font-semibold text-sky-900">' + formatMoney(lineTotal) + '</span>' +
                        '<button type="button" class="pos-cart-void-btn inline-flex h-7 w-7 items-center justify-center rounded-full border border-rose-200 text-rose-600 hover:bg-rose-50 ml-1" data-id="' + item.product_id + '" title="Void line">×</button>' +
                    '</div>' +
                '</div>';
            });
            container.innerHTML = html;
            container.querySelectorAll('.pos-cart-qty-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var id = parseInt(this.getAttribute('data-id'), 10);
                    var delta = parseInt(this.getAttribute('data-delta'), 10) || 0;
                    updateCartQuantity(id, delta);
                });
            });
            container.querySelectorAll('.pos-cart-qty-input').forEach(function (input) {
                input.addEventListener('change', function () {
                    var id = parseInt(this.getAttribute('data-id'), 10);
                    setCartQuantity(id, this.value);
                });
                input.addEventListener('blur', function () {
                    var id = parseInt(this.getAttribute('data-id'), 10);
                    setCartQuantity(id, this.value);
                });
                input.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        this.blur();
                    }
                });
            });
            container.querySelectorAll('.pos-cart-void-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var id = parseInt(this.getAttribute('data-id'), 10);
                    openVoidManagerModal(id);
                });
            });
            var itemsCount = cartItems.reduce(function (sum, item) { return sum + item.quantity; }, 0);
            if (badgeEl) { badgeEl.classList.remove('hidden'); badgeEl.textContent = itemsCount + ' item' + (itemsCount !== 1 ? 's' : ''); }
        }

        var itemsCount = 0;
        var subtotal = 0;
        cartItems.forEach(function (item) {
            itemsCount += item.quantity;
            subtotal += item.unit_price * item.quantity;
        });
        var vatableSales = Math.round((subtotal / (1 + VAT_RATE)) * 100) / 100;
        var vatAmount = Math.round((subtotal - vatableSales) * 100) / 100;
        var vatExempt = 0;
        var zeroRated = 0;
        var totalDiscount = appliedDiscounts.reduce(function (sum, d) { return sum + (parseFloat(d.amount) || 0); }, 0);
        var totalDue = Math.round((subtotal - totalDiscount + serviceChargeAmount) * 100) / 100;

        document.getElementById('pos-item-count').textContent = itemsCount;
        document.getElementById('pos-subtotal').textContent = formatMoney(subtotal);
        var vatableEl = document.getElementById('pos-vatable-sales');
        var vatExemptEl = document.getElementById('pos-vat-exempt');
        var zeroEl = document.getElementById('pos-zero-rated');
        var totalDiscountEl = document.getElementById('pos-total-discount');
        var vatExemptionTotalEl = document.getElementById('pos-vat-exemption');
        if (vatableEl) vatableEl.textContent = formatMoney(vatableSales);
        if (vatExemptEl) vatExemptEl.textContent = formatMoney(vatExempt);
        if (zeroEl) zeroEl.textContent = formatMoney(zeroRated);
        document.getElementById('pos-vat-amount').textContent = formatMoney(vatAmount);
        var vatSummaryEl = document.getElementById('pos-vat-summary');
        if (vatSummaryEl) vatSummaryEl.textContent = formatMoney(vatAmount);
        var totalDueEl = document.getElementById('pos-total-due');
        if (totalDueEl) {
            totalDueEl.textContent = formatMoney(totalDue);
            totalDueEl.classList.remove('pos-total-amount-animate');
            // Force reflow so animation can restart
            void totalDueEl.offsetWidth;
            totalDueEl.classList.add('pos-total-amount-animate');
        }
        if (totalDiscountEl) totalDiscountEl.textContent = formatMoney(totalDiscount);
        if (vatExemptionTotalEl) vatExemptionTotalEl.textContent = formatMoney(vatExempt);

        var svcAmt = document.getElementById('pos-service-charge-amount');
        if (svcAmt) {
            if (serviceChargeAmount > 0) {
                svcAmt.classList.remove('hidden');
                svcAmt.textContent = formatMoney(serviceChargeAmount);
            } else {
                svcAmt.classList.add('hidden');
            }
        }
        renderAppliedDiscounts();
        updateChange();
    }

    function updateChange() {
        var totalLabel = document.getElementById('pos-total-due');
        var amountInput = document.getElementById('pos-amount-received');
        var changeLabel = document.getElementById('pos-change-amount');
        if (!totalLabel || !amountInput || !changeLabel) return;
        var totalText = totalLabel.textContent.replace(/[^\d.,-]/g, '');
        var total = parseAmount(totalText);
        var received = parseAmount(amountInput.value);
        var change = received - total;
        if (change < 0) change = 0;
        changeLabel.textContent = formatMoney(change);
        updateCompleteSaleButtonState();
    }

    function updateCompleteSaleButtonState() {
        var btn = document.getElementById('pos-complete-sale-btn');
        if (!btn) return;
        // Disable when there is no order or terminal is not ready.
        // Amount validation is handled inside the payment modal / completeSale flow.
        var disabled = !cartItems.length || !currentTerminalId;
        btn.disabled = disabled;

        var hasRx = cartItems.some(function (i) { return i.is_rx; });
        var labelEl = document.getElementById('pos-complete-sale-label');
        if (hasRx) {
            btn.classList.remove('bg-primary', 'hover:bg-primary/90', 'focus:ring-primary/20');
            btn.classList.add('bg-amber-500', 'hover:bg-amber-600', 'focus:ring-amber-400/40');
            if (labelEl) labelEl.textContent = 'Complete sale (Rx required)';
        } else {
            btn.classList.remove('bg-amber-500', 'hover:bg-amber-600', 'focus:ring-amber-400/40');
            btn.classList.add('bg-primary', 'hover:bg-primary/90', 'focus:ring-primary/20');
            if (labelEl) labelEl.textContent = 'Complete sale';
        }
    }

    var paymentModalCallback = null;
    var paymentModalType = 'cash';
    var lastPaymentReference = '';
    var lastPaymentProvider = '';

    function updatePaymentModalChangeFromDom() {
        var totalEl = document.getElementById('pos-payment-modal-total');
        var amountInput = document.getElementById('pos-payment-modal-amount');
        var changeEl = document.getElementById('pos-payment-modal-change');
        if (!totalEl || !amountInput || !changeEl) return;
        var total = parseAmount(totalEl.textContent.replace(/[^\d.,-]/g, ''));
        var raw = amountInput.value || '';
        // Strip all non-numeric characters except decimal separators and minus sign
        var cleaned = raw.replace(/[^\d.,-]/g, '');
        if (cleaned !== raw) {
            amountInput.value = cleaned;
        }
        var received = parseAmount(cleaned);
        var change = received - total;
        if (change < 0) change = 0;
        changeEl.textContent = formatMoney(change);
    }

    // Floating action menu: lock and logout
    function initFloatingMenu() {
        var lockBtn = document.getElementById('pos-lock-btn');
        var logoutBtn = document.getElementById('pos-logout-btn');
        if (lockBtn) {
            lockBtn.addEventListener('click', function () {
                // Lock: keep token, remember lock time, then go to POS lock screen
                try {
                    localStorage.setItem('pos_locked_at', String(Date.now()));
                } catch (e) {}
                window.location.href = '{{ route('dashboard.pos.lock') }}';
            });
        }
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function () {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'question',
                        title: 'Logout from POS?',
                        text: 'Current POS session will be closed.',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, logout',
                        cancelButtonText: 'Cancel',
                    }).then(function (res) {
                        if (!res.isConfirmed) return;
                        localStorage.removeItem('super_admin_token');
                        window.location.href = dashboardBase + '/login';
                    });
                } else {
                    localStorage.removeItem('super_admin_token');
                    window.location.href = dashboardBase + '/login';
                }
            });
        }
    }

    function openPaymentModal(type, onDone) {
        paymentModalType = type || 'cash';
        paymentModalCallback = typeof onDone === 'function' ? onDone : null;

        var totalLabel = document.getElementById('pos-total-due');
        var total = totalLabel ? parseAmount(totalLabel.textContent.replace(/[^\d.,-]/g, '')) : 0;
        var existingAmountInput = document.getElementById('pos-amount-received');
        var existingAmount = existingAmountInput ? existingAmountInput.value : '';

        var modal = document.getElementById('pos-payment-modal');
        var titleEl = document.getElementById('pos-payment-modal-title');
        var totalEl = document.getElementById('pos-payment-modal-total');
        var amountInput = document.getElementById('pos-payment-modal-amount');
        var errorEl = document.getElementById('pos-payment-modal-error');

        if (!modal || !amountInput || !totalEl) {
            if (paymentModalCallback) paymentModalCallback(false);
            paymentModalCallback = null;
            return;
        }

        var title =
            paymentModalType === 'card'
                ? 'Card payment'
                : paymentModalType === 'ewallet'
                    ? 'E-wallet / QR payment'
                    : 'Cash payment';
        if (titleEl) titleEl.textContent = title;
        totalEl.textContent = formatMoney(total);

        amountInput.value = existingAmount || '';
        if (errorEl) errorEl.classList.add('hidden');

        var cardEwalletBlock = document.getElementById('pos-payment-modal-card-ewallet-fields');
        var refInput = document.getElementById('pos-payment-modal-reference');
        var provInput = document.getElementById('pos-payment-modal-provider');
        if (paymentModalType === 'card' || paymentModalType === 'ewallet') {
            if (cardEwalletBlock) cardEwalletBlock.classList.remove('hidden');
            if (refInput) { refInput.value = ''; refInput.placeholder = paymentModalType === 'card' ? 'Approval / reference no.' : 'Transaction / reference no.'; }
            if (provInput) { provInput.value = ''; provInput.placeholder = paymentModalType === 'card' ? 'e.g. Visa, Mastercard' : 'e.g. GCash, Maya'; }
        } else {
            if (cardEwalletBlock) cardEwalletBlock.classList.add('hidden');
            if (refInput) refInput.value = '';
            if (provInput) provInput.value = '';
        }

        updatePaymentModalChangeFromDom();

        modal.classList.remove('hidden');
        amountInput.focus();
        amountInput.select();
    }

    function closePaymentModal(success) {
        var modal = document.getElementById('pos-payment-modal');
        if (modal) modal.classList.add('hidden');
        var cb = paymentModalCallback;
        paymentModalCallback = null;
        if (typeof cb === 'function') cb(success === true);
    }

    function applyPaymentFromModal() {
        var totalLabel = document.getElementById('pos-total-due');
        var total = totalLabel ? parseAmount(totalLabel.textContent.replace(/[^\d.,-]/g, '')) : 0;
        var amountInput = document.getElementById('pos-payment-modal-amount');
        var errorEl = document.getElementById('pos-payment-modal-error');
        var hiddenAmountInput = document.getElementById('pos-amount-received');
        if (!amountInput || !hiddenAmountInput) {
            closePaymentModal(false);
            return;
        }
        var raw = amountInput.value || '';
        var cleaned = raw.replace(/[^\d.,-]/g, '');
        amountInput.value = cleaned;
        var val = cleaned.trim();
        var received = parseAmount(val);
        if (paymentModalType === 'cash' && received < total) {
            if (errorEl) {
                errorEl.textContent = 'Amount received is less than the total due.';
                errorEl.classList.remove('hidden');
            }
            amountInput.focus();
            return;
        }
        if (errorEl) errorEl.classList.add('hidden');
        hiddenAmountInput.value = val;
        var refInput = document.getElementById('pos-payment-modal-reference');
        var provInput = document.getElementById('pos-payment-modal-provider');
        lastPaymentReference = (refInput && refInput.value) ? String(refInput.value).trim().slice(0, 100) : '';
        lastPaymentProvider = (provInput && provInput.value) ? String(provInput.value).trim().slice(0, 100) : '';
        updateChange();
        closePaymentModal(true);
    }

    var rxModalCallback = null;

    function openRxModal(onDone) {
        rxModalCallback = typeof onDone === 'function' ? onDone : null;
        var rxItems = cartItems.filter(function (i) { return i.is_rx; });
        if (!rxItems.length) {
            if (rxModalCallback) rxModalCallback(null);
            rxModalCallback = null;
            return;
        }
        var modal = document.getElementById('pos-rx-modal');
        var listEl = document.getElementById('pos-rx-modal-list');
        var numInput = document.getElementById('pos-rx-number');
        var docInput = document.getElementById('pos-rx-doctor');
        var numError = document.getElementById('pos-rx-number-error');
        var docError = document.getElementById('pos-rx-doctor-error');
        if (!modal || !listEl || !numInput || !docInput) {
            if (rxModalCallback) rxModalCallback(null);
            rxModalCallback = null;
            return;
        }
        listEl.innerHTML = rxItems.map(function (i) {
            return '<li>' + i.name + '</li>';
        }).join('');
        numInput.value = '';
        docInput.value = '';
        if (numError) numError.classList.add('hidden');
        if (docError) docError.classList.add('hidden');
        modal.classList.remove('hidden');
        numInput.focus();
        numInput.select();
    }

    function closeRxModal(result) {
        var modal = document.getElementById('pos-rx-modal');
        if (modal) modal.classList.add('hidden');
        var cb = rxModalCallback;
        rxModalCallback = null;
        if (typeof cb === 'function') cb(result || null);
    }

    function applyRxFromModal() {
        var numInput = document.getElementById('pos-rx-number');
        var docInput = document.getElementById('pos-rx-doctor');
        var numError = document.getElementById('pos-rx-number-error');
        var docError = document.getElementById('pos-rx-doctor-error');
        if (!numInput || !docInput) {
            closeRxModal(null);
            return;
        }
        var num = (numInput.value || '').trim();
        var doc = (docInput.value || '').trim();
        var hasError = false;
        if (!num) {
            if (numError) numError.classList.remove('hidden');
            hasError = true;
        } else if (numError) {
            numError.classList.add('hidden');
        }
        if (!doc) {
            if (docError) docError.classList.remove('hidden');
            hasError = true;
        } else if (docError) {
            docError.classList.add('hidden');
        }
        if (hasError) {
            if (!num) numInput.focus();
            else if (!doc) docInput.focus();
            return;
        }
        closeRxModal({ number: num, doctor: doc });
    }

    function setTenderType(type) {
        currentTenderType = type;
        document.querySelectorAll('.pos-tender-type-btn').forEach(function (btn) {
            var t = btn.getAttribute('data-type');
            if (t === type) {
                btn.classList.remove('border-slate-200', 'dark:border-darkmode-500', 'border-slate-300', 'bg-slate-100', 'dark:bg-darkmode-700', 'text-slate-700', 'dark:text-slate-200', 'hover:bg-slate-200', 'hover:border-slate-300', 'dark:hover:bg-darkmode-600', 'dark:hover:border-darkmode-500');
                btn.classList.add('border-primary', 'bg-primary', 'text-white', 'shadow-sm', 'hover:bg-primary/90', 'hover:border-primary');
            } else {
                btn.classList.add('border-slate-200', 'dark:border-darkmode-500', 'bg-slate-100', 'dark:bg-darkmode-700', 'text-slate-700', 'dark:text-slate-200', 'hover:bg-slate-200', 'hover:border-slate-300', 'dark:hover:bg-darkmode-600', 'dark:hover:border-darkmode-500');
                btn.classList.remove('border-primary', 'bg-primary', 'text-white', 'shadow-sm', 'hover:bg-primary/90', 'hover:border-primary');
            }
        });
        var tenderLabel = document.getElementById('pos-tender-label');
        if (tenderLabel) {
            var map = {
                cash: 'Mode: Cash',
                card: 'Mode: Card',
                ewallet: 'Mode: E-wallet / QR',
            };
            tenderLabel.textContent = map[type] || 'Mode: Cash';
        }
        updateCompleteSaleButtonState();
    }

    function toggleVatDetails() {
        var panel = document.getElementById('pos-vat-breakdown');
        var icon = document.getElementById('pos-vat-toggle-icon');
        if (!panel) return;
        var willShow = panel.classList.contains('hidden');
        if (willShow) {
            panel.classList.remove('hidden');
        } else {
            panel.classList.add('hidden');
        }
        if (icon) {
            if (willShow) {
                icon.classList.add('rotate-90');
            } else {
                icon.classList.remove('rotate-90');
            }
        }
    }

    function setView(mode) {
        viewMode = mode === 'list' ? 'list' : 'grid';
        var gridBtn = document.getElementById('pos-view-grid');
        var listBtn = document.getElementById('pos-view-list');
        if (gridBtn && listBtn) {
            if (viewMode === 'grid') {
                gridBtn.classList.add('bg-primary', 'text-white', 'shadow-sm');
                gridBtn.classList.remove('text-slate-400');
                listBtn.classList.remove('bg-primary', 'text-white', 'shadow-sm');
                listBtn.classList.add('text-slate-400');
            } else {
                listBtn.classList.add('bg-primary', 'text-white', 'shadow-sm');
                listBtn.classList.remove('text-slate-400');
                gridBtn.classList.remove('bg-primary', 'text-white', 'shadow-sm');
                gridBtn.classList.add('text-slate-400');
            }
        }
        renderProducts();
    }

    function searchBarcode(code) {
        if (!code) return;
        axios.get(apiBase + '/pos/products/search', { headers: headers.headers, params: { q: code } })
            .then(function (r) {
                var list = r.data && r.data.data ? r.data.data : (Array.isArray(r.data) ? r.data : []);
                if (!Array.isArray(list) || !list.length) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Not found',
                            text: 'No product found for barcode "' + code + '".',
                        });
                    }
                    return;
                }
                if (list.length === 1) {
                    addToCart(list[0]);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Item added',
                            text: (list[0].name || 'Product') + ' was added to the order.',
                            timer: 900,
                            showConfirmButton: false,
                            timerProgressBar: true,
                        });
                    }
                    return;
                }
                // Multiple matches – let cashier choose
                if (typeof Swal === 'undefined') {
                    addToCart(list[0]);
                    return;
                }
                var optionsHtml = '';
                for (var i = 0; i < list.length; i++) {
                    var p = list[i];
                    var label = (p.barcode ? p.barcode + ' · ' : '') + (p.name || 'Product');
                    optionsHtml += '<option value="' + i + '">' + label + '</option>';
                }
                Swal.fire({
                    title: 'Select product',
                    html: '<p class="text-sm text-slate-600 mb-2">More than one product matches this barcode. Please choose the correct one.</p>' +
                          '<select id="pos-barcode-select" class="swal2-select" style="min-width: 240px;">' + optionsHtml + '</select>',
                    focusConfirm: false,
                    showCancelButton: true,
                    preConfirm: function () {
                        var sel = document.getElementById('pos-barcode-select');
                        return sel ? sel.value : null;
                    }
                }).then(function (res) {
                    if (!res.isConfirmed) return;
                    var idx = parseInt(res.value, 10);
                    if (!isNaN(idx) && list[idx]) {
                        addToCart(list[idx]);
                    }
                });
            })
            .catch(function (err) {
                var msg = err.response && err.response.data && err.response.data.message
                    ? err.response.data.message
                    : 'Unable to search products by barcode.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Scan failed', text: msg });
                }
            });
    }

    function triggerBarcodeScan() {
        if (typeof Swal === 'undefined') {
            var manual = window.prompt('Scan or enter barcode:');
            if (manual) searchBarcode(manual);
            return;
        }
        Swal.fire({
            title: 'Scan barcode',
            input: 'text',
            inputLabel: 'Place cursor here, then scan or type the barcode.',
            inputAttributes: {
                autocapitalize: 'off',
                autocomplete: 'off',
                'aria-label': 'Scan barcode'
            },
            showCancelButton: true,
            confirmButtonText: 'Search',
            showLoaderOnConfirm: true,
            allowOutsideClick: function () { return !Swal.isLoading(); },
            didOpen: function (modalEl) {
                var input = modalEl.querySelector('input.swal2-input');
                if (input) {
                    input.focus();
                    input.select();
                }
            },
            preConfirm: function (value) {
                var v = (value || '').trim();
                if (!v) {
                    Swal.showValidationMessage('Please scan or enter a barcode.');
                    return false;
                }
                return v;
            }
        }).then(function (res) {
            if (!res.isConfirmed) return;
            searchBarcode(res.value);
        });
    }

    function loadProducts() {
        axios.get(apiBase + '/pos/products', { headers: headers.headers, params: { per_page: 100 } })
            .then(function (r) {
                var d = r.data && r.data.data ? r.data.data : r.data;
                var items = Array.isArray(d.data) ? d.data : (Array.isArray(d) ? d : []);
                allProducts = items;
                updateCategoryTabCounts();
                applyFilters();
            })
            .catch(function (err) {
                var msg = err.response && err.response.data && err.response.data.message
                    ? err.response.data.message
                    : 'Unable to load products for this branch.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Products not available', text: msg });
                }
            });
    }

    function completeSale() {
        if (!cartItems.length) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'warning', title: 'No items', text: 'Add at least one item before completing the sale.' });
            }
            return;
        }
        if (!currentTerminalId) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Terminal not ready', text: 'POS terminal is not configured. Please contact your administrator.' });
            }
            return;
        }
        var totalText = document.getElementById('pos-total-due').textContent.replace(/[^\d.,-]/g, '');
        var total = parseAmount(totalText);
        var amountInput = document.getElementById('pos-amount-received');
        var received = parseAmount(amountInput.value);
        if (currentTenderType === 'cash' && received < total) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'warning', title: 'Insufficient amount', text: 'Amount received is less than the total due.' });
            }
            return;
        }
        var hasRx = cartItems.some(function (item) { return item.is_rx; });
        if (hasRx) {
            openRxModal(function (rxInfo) {
                if (!rxInfo) return;
                doCompleteSale(rxInfo);
            });
            return;
        }
        doCompleteSale(null);
    }

    function handleCompleteSaleClick() {
        if (!cartItems.length || !currentTerminalId) {
            completeSale();
            return;
        }
        openPaymentModal(currentTenderType || 'cash', function (ok) {
            if (!ok) return;
            completeSale();
        });
    }

    function doCompleteSale(prescriptionForRx) {
        var paymentMethod = (currentTenderType === 'ewallet' ? 'ewallet' : (currentTenderType === 'card' ? 'card' : 'cash'));
        var payload = {
            terminal_id: currentTerminalId,
            payment_method: paymentMethod,
            discount_amount: appliedDiscounts.reduce(function (sum, d) { return sum + (parseFloat(d.amount) || 0); }, 0),
            discounts: appliedDiscounts.map(function (d) {
                return { type: d.type, amount: d.amount, reference_id: d.reference_id || null, customer_name: d.customer_name || null };
            }),
            items: cartItems.map(function (item) {
                var rxInfo = prescriptionForRx || null;
                return {
                    product_id: item.product_id,
                    product_batch_id: null,
                    quantity: item.quantity,
                    unit_price: item.unit_price,
                    prescription_number: item.is_rx && rxInfo ? rxInfo.number : null
                };
            })
        };
        if (paymentMethod === 'card' || paymentMethod === 'ewallet') {
            if (lastPaymentReference) payload.payment_reference = lastPaymentReference;
            if (lastPaymentProvider) payload.payment_provider = lastPaymentProvider;
        }
        var customerNameEl = document.getElementById('pos-customer-name');
        var customerAddressEl = document.getElementById('pos-customer-address');
        if (customerNameEl && (customerNameEl.value || '').trim()) payload.customer_name = String(customerNameEl.value).trim().slice(0, 255);
        if (customerAddressEl && (customerAddressEl.value || '').trim()) payload.customer_address = String(customerAddressEl.value).trim().slice(0, 500);
        var btn = document.getElementById('pos-complete-sale-btn');
        if (btn) btn.disabled = true;
        axios.post(apiBase + '/pos/transactions', payload, headers)
            .then(function (r) {
                var d = r.data && r.data.data ? r.data.data : r.data;
                var orNumber = d && d.or_number ? d.or_number : null;
                var orLabel = document.getElementById('pos-or-placeholder');
                var orBadge = document.getElementById('pos-or-badge');
                if (orLabel && orNumber) {
                    orLabel.textContent = orNumber;
                }
                if (orBadge) {
                    orBadge.className = 'inline-flex items-center rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-3 py-1 text-[11px] font-medium text-emerald-800 dark:text-emerald-200';
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Sale completed',
                        text: orNumber ? 'OR #' + orNumber + ' has been recorded.' : 'Transaction has been recorded.',
                        timer: 2500,
                        showConfirmButton: false,
                        timerProgressBar: true,
                    });
                }
                var amountInput = document.getElementById('pos-amount-received');
                var received = amountInput ? parseAmount(amountInput.value) : 0;
                var totalAmount = parseFloat(d.total) || 0;
                var changeAmt = Math.max(0, received - totalAmount);
                var receiptPrintUrl = dashboardBase + '/pos/receipt-print';
                var qs = '?transaction_id=' + encodeURIComponent(d.id) + '&amount_received=' + encodeURIComponent(received) + '&change=' + encodeURIComponent(changeAmt);
                window.open(receiptPrintUrl + qs, 'pos_receipt_print', 'width=800,height=900,scrollbars=yes');
                clearCart();
                document.getElementById('pos-amount-received').value = '';
                updateChange();
            })
            .catch(function (err) {
                var msg = err.response && err.response.data && err.response.data.message
                    ? err.response.data.message
                    : 'Failed to complete sale.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Error', text: msg });
                }
            })
            .finally(function () {
                if (btn) btn.disabled = false;
            });
    }

    function openVoidManagerModal(productId) {
        pendingVoidProductId = productId;
        var modal = document.getElementById('pos-void-modal');
        var input = document.getElementById('pos-void-manager-input-modal');
        var errorEl = document.getElementById('pos-void-manager-error');
        if (!modal) return;
        if (input) {
            input.value = '';
            input.classList.remove('border-rose-500');
        }
        if (errorEl) errorEl.classList.add('hidden');
        modal.classList.remove('hidden');
        if (input) input.focus();
    }

    function closeVoidManagerModal() {
        var modal = document.getElementById('pos-void-modal');
        if (modal) modal.classList.add('hidden');
    }

    function confirmVoidManagerModal() {
        var input = document.getElementById('pos-void-manager-input-modal');
        var errorEl = document.getElementById('pos-void-manager-error');
        var pinOrPassword = input && input.value ? input.value.trim() : '';
        if (!pinOrPassword) {
            if (errorEl) errorEl.classList.remove('hidden');
            if (input) {
                input.classList.add('border-rose-500');
                input.focus();
            }
            return;
        }
        if (errorEl) errorEl.classList.add('hidden');
        if (input) input.classList.remove('border-rose-500');
        if (!pendingVoidProductId) {
            closeVoidManagerModal();
            return;
        }
        var id = pendingVoidProductId;
        axios.post(apiBase + '/pos/verify-manager', { pin_or_password: pinOrPassword }, headers)
            .then(function () {
                var item = findCartItem(id);
                var productName = item ? item.name : '';
                var reasonOptions = [
                    { value: 'wrong_item', label: 'Wrong item' },
                    { value: 'customer_changed_mind', label: 'Customer changed mind' },
                    { value: 'damaged', label: 'Damaged' },
                    { value: 'other', label: 'Other' }
                ];
                var optionsHtml = reasonOptions.map(function (o) { return '<option value="' + o.value + '">' + o.label + '</option>'; }).join('');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'question',
                        title: 'Reason for void',
                        html: '<p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Select reason for audit trail.</p>' +
                            '<select id="pos-void-reason" class="swal2-input mt-2" style="min-width: 220px;">' + optionsHtml + '</select>',
                        showCancelButton: true,
                        confirmButtonText: 'Void line',
                        cancelButtonText: 'Cancel',
                        preConfirm: function () {
                            var sel = document.getElementById('pos-void-reason');
                            return sel ? sel.value : 'other';
                        }
                    }).then(function (res2) {
                        if (!res2.isConfirmed) return;
                        var reason = res2.value || 'other';
                        axios.post(apiBase + '/pos/log-line-void', { product_id: id, product_name: productName, reason: reason }, headers)
                            .then(function () {
                                removeCartItem(id);
                                if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Line voided', text: 'Item removed from the order.', timer: 1200, showConfirmButton: false, timerProgressBar: true });
                            })
                            .catch(function () {
                                removeCartItem(id);
                                if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Line voided', text: 'Item removed.', timer: 1200, showConfirmButton: false, timerProgressBar: true });
                            });
                    });
                } else {
                    removeCartItem(id);
                }
                pendingVoidProductId = null;
                closeVoidManagerModal();
            })
            .catch(function (err) {
                var msg = err.response && err.response.data && err.response.data.message
                    ? err.response.data.message
                    : (err.response && err.response.data && err.response.data.errors && err.response.data.errors.pin_or_password && err.response.data.errors.pin_or_password[0])
                        ? err.response.data.errors.pin_or_password[0]
                        : 'Manager verification failed.';
                if (errorEl) {
                    errorEl.textContent = msg;
                    errorEl.classList.remove('hidden');
                }
                if (input) {
                    input.classList.add('border-rose-500');
                    input.focus();
                }
            });
    }

    var scPwdModalDiscountAmount = 0;

    function openScPwdModal() {
        var subtotal = cartItems.reduce(function (s, i) { return s + i.unit_price * i.quantity; }, 0);
        if (subtotal <= 0) {
            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'warning', title: 'No items', text: 'Add items before applying SC/PWD discount.' });
            return;
        }
        var discount20 = Math.round((subtotal * 0.20) * 100) / 100;
        scPwdModalDiscountAmount = discount20;
        var modal = document.getElementById('pos-sc-pwd-modal');
        var amountEl = document.getElementById('pos-sc-pwd-discount-amount');
        var idInput = document.getElementById('pos-sc-pwd-id');
        var nameInput = document.getElementById('pos-sc-pwd-name');
        var errorEl = document.getElementById('pos-sc-pwd-id-error');
        if (modal && amountEl) {
            amountEl.textContent = formatMoney(discount20);
            if (idInput) { idInput.value = ''; idInput.classList.remove('border-rose-500'); }
            if (nameInput) nameInput.value = '';
            if (errorEl) errorEl.classList.add('hidden');
            modal.classList.remove('hidden');
            if (idInput) {
                idInput.focus();
            }
        }
    }

    function closeScPwdModal() {
        var modal = document.getElementById('pos-sc-pwd-modal');
        if (modal) modal.classList.add('hidden');
    }

    function applyScPwdDiscount() {
        var idInput = document.getElementById('pos-sc-pwd-id');
        var nameInput = document.getElementById('pos-sc-pwd-name');
        var errorEl = document.getElementById('pos-sc-pwd-id-error');
        var id = idInput && idInput.value ? idInput.value.trim() : '';
        if (!id) {
            if (errorEl) errorEl.classList.remove('hidden');
            if (idInput) idInput.classList.add('border-rose-500');
            if (idInput) idInput.focus();
            return;
        }
        if (errorEl) errorEl.classList.add('hidden');
        if (idInput) idInput.classList.remove('border-rose-500');
        var name = nameInput && nameInput.value ? nameInput.value.trim() : '';
        appliedDiscounts.push({ type: 'sc_pwd', amount: scPwdModalDiscountAmount, reference_id: id, customer_name: name });
        renderCart();
        saveCartToStorage();
        closeScPwdModal();
        if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Applied', text: 'SC/PWD 20% discount applied.', timer: 1200, showConfirmButton: false, timerProgressBar: true });
    }

    function openManualDiscountModal(typeLabel, typeKey) {
        var subtotal = cartItems.reduce(function (s, i) { return s + i.unit_price * i.quantity; }, 0);
        if (subtotal <= 0) {
            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'warning', title: 'No items', text: 'Add items first.' });
            return;
        }
        if (typeof Swal === 'undefined') return;
        Swal.fire({
            title: typeLabel,
            html: '<input type="number" id="pos-manual-discount-amount" class="swal2-input" placeholder="Amount (₱)" min="0" step="0.01" value="0">',
            showCancelButton: true,
            confirmButtonText: 'Apply',
            preConfirm: function () {
                var amt = parseFloat(document.getElementById('pos-manual-discount-amount').value) || 0;
                if (amt <= 0) { Swal.showValidationMessage('Enter amount.'); return false; }
                if (amt > subtotal) { Swal.showValidationMessage('Amount cannot exceed subtotal.'); return false; }
                return amt;
            }
        }).then(function (res) {
            if (!res.isConfirmed || !res.value) return;
            appliedDiscounts.push({ type: typeKey, amount: res.value, reference_id: null, customer_name: null });
            renderCart();
            saveCartToStorage();
        });
    }

    function toggleDiscountDropdown() {
        var dd = document.getElementById('pos-discount-dropdown');
        if (dd) dd.classList.toggle('hidden');
    }

    function holdOrder() {
        if (cartItems.length > 0) {
            var snapshot = {
                items: JSON.parse(JSON.stringify(cartItems)),
                discounts: JSON.parse(JSON.stringify(appliedDiscounts)),
                serviceCharge: serviceChargeAmount
            };
            if (!currentTerminalId) {
                heldOrders.push(snapshot);
                clearCart();
                updateHoldButtonText();
                if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Order held (local)', text: 'Click Hold (' + heldOrders.length + ') to retrieve. Save to server when terminal is ready.', timer: 2500, showConfirmButton: false, timerProgressBar: true });
                return;
            }
            axios.post(apiBase + '/pos/held-orders', {
                terminal_id: currentTerminalId,
                payload: {
                    items: snapshot.items,
                    discounts: snapshot.discounts,
                    serviceCharge: snapshot.serviceCharge
                }
            }, headers)
                .then(function (r) {
                    var d = r.data && r.data.data ? r.data.data : r.data;
                    var id = d && d.id != null ? d.id : null;
                    heldOrders.push({
                        id: id,
                        items: snapshot.items,
                        discounts: snapshot.discounts,
                        serviceCharge: snapshot.serviceCharge
                    });
                    clearCart();
                    updateHoldButtonText();
                    if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Order held', text: 'Click Hold (' + heldOrders.length + ') to retrieve.', timer: 2000, showConfirmButton: false, timerProgressBar: true });
                })
                .catch(function (err) {
                    var msg = err.response && err.response.data && err.response.data.message ? err.response.data.message : 'Could not save held order.';
                    if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Hold failed', text: msg });
                });
            return;
        }
        if (heldOrders.length > 0) {
            showHeldOrdersModal();
            return;
        }
        if (typeof Swal !== 'undefined') Swal.fire({ icon: 'info', title: 'Nothing to hold', text: 'Add items to the order first, or click Hold when you have held orders to resume one.' });
    }

    function showHeldOrdersModal() {
        if (heldOrders.length === 0) return;
        var listHtml = '';
        heldOrders.forEach(function (h, idx) {
            var itemCount = h.items.reduce(function (sum, i) { return sum + (i.quantity || 0); }, 0);
            var subtotal = h.items.reduce(function (s, i) { return s + (i.unit_price || 0) * (i.quantity || 0); }, 0);
            var totalDiscount = (h.discounts || []).reduce(function (sum, d) { return sum + (parseFloat(d.amount) || 0); }, 0);
            var total = Math.round((subtotal - totalDiscount + (h.serviceCharge || 0)) * 100) / 100;
            listHtml += '<div class="flex items-center justify-between gap-3 py-2 px-3 rounded-lg bg-slate-50 dark:bg-darkmode-700/60 mb-2">' +
                '<div class="text-left">' +
                    '<span class="font-medium text-slate-800 dark:text-slate-200">Order ' + (idx + 1) + '</span>' +
                    '<span class="text-xs text-slate-500 dark:text-slate-400 block">' + itemCount + ' item' + (itemCount !== 1 ? 's' : '') + ' · ' + formatMoney(total) + '</span>' +
                '</div>' +
                '<button type="button" class="pos-resume-held-btn rounded-lg bg-primary text-white px-3 py-1.5 text-xs font-semibold hover:bg-primary/90" data-index="' + idx + '">Resume</button>' +
                '</div>';
        });
        if (typeof Swal === 'undefined') {
            resumeHeldOrder(0);
            return;
        }
        Swal.fire({
            title: 'Held orders',
            html: '<p class="text-sm text-slate-600 dark:text-slate-400 mb-3">Click Resume to put the order back in the cart.</p><div id="pos-held-orders-list">' + listHtml + '</div>',
            showConfirmButton: false,
            showCloseButton: true,
            didOpen: function (modalEl) {
                modalEl.querySelectorAll('.pos-resume-held-btn').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var index = parseInt(this.getAttribute('data-index'), 10);
                        Swal.close();
                        resumeHeldOrder(index);
                        if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Order restored', text: 'Continue with payment or add more items.', timer: 1500, showConfirmButton: false, timerProgressBar: true });
                    });
                });
            }
        });
    }

    function updateHoldButtonText() {
        var el = document.getElementById('pos-hold-btn-text');
        var btn = document.getElementById('pos-hold-order-btn');
        if (el) el.textContent = 'Hold (' + heldOrders.length + ')';
        if (btn) btn.title = heldOrders.length > 0 ? 'Click to retrieve a held order (or add items and click to hold current order).' : 'Hold current order. If cart is empty, click to retrieve a held order.';
    }

    function resumeHeldOrder(index) {
        if (!heldOrders[index]) return;
        var h = heldOrders[index];
        var heldId = h.id;
        if (heldId != null) {
            axios.delete(apiBase + '/pos/held-orders/' + heldId, headers)
                .catch(function () {});
        }
        cartItems = h.items || [];
        appliedDiscounts = h.discounts || [];
        serviceChargeAmount = h.serviceCharge || 0;
        heldOrders.splice(index, 1);
        updateHoldButtonText();
        renderCart();
        saveCartToStorage();
    }

    // Event bindings
    (function bindEvents() {
        var newSaleBtn = document.getElementById('pos-new-sale-btn');
        if (newSaleBtn) {
            newSaleBtn.addEventListener('click', function () {
                clearCart();
                document.getElementById('pos-amount-received').value = '';
                updateChange();
                var orLabel = document.getElementById('pos-or-placeholder');
                var orBadge = document.getElementById('pos-or-badge');
                if (orLabel) orLabel.textContent = 'Pending';
                if (orBadge) orBadge.className = 'inline-flex items-center rounded-full bg-amber-100 dark:bg-amber-900/30 px-3 py-1 text-[11px] font-medium text-amber-800 dark:text-amber-200';
            });
        }

        var clearBtn = document.getElementById('pos-clear-order-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'question',
                        title: 'Clear current order?',
                        text: 'This will remove all items from the order.',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, clear',
                        cancelButtonText: 'Cancel'
                    }).then(function (res) {
                        if (res.isConfirmed) clearCart();
                    });
                } else {
                    clearCart();
                }
            });
        }

        var holdBtn = document.getElementById('pos-hold-order-btn');
        if (holdBtn) holdBtn.addEventListener('click', holdOrder);

        var scPwdBtn = document.getElementById('pos-sc-pwd-btn');
        if (scPwdBtn) scPwdBtn.addEventListener('click', function () { document.getElementById('pos-discount-dropdown').classList.add('hidden'); openScPwdModal(); });

        var voidModal = document.getElementById('pos-void-modal');
        var voidModalBackdrop = document.getElementById('pos-void-modal-backdrop');
        var voidModalCancel = document.getElementById('pos-void-modal-cancel');
        var voidModalConfirm = document.getElementById('pos-void-modal-confirm');
        var voidManagerInput = document.getElementById('pos-void-manager-input-modal');
        if (voidModalBackdrop) voidModalBackdrop.addEventListener('click', closeVoidManagerModal);
        if (voidModalCancel) voidModalCancel.addEventListener('click', closeVoidManagerModal);
        if (voidModalConfirm) voidModalConfirm.addEventListener('click', confirmVoidManagerModal);
        if (voidManagerInput) {
            voidManagerInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); confirmVoidManagerModal(); }
            });
        }
        if (voidModal) {
            voidModal.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') { e.preventDefault(); closeVoidManagerModal(); }
            });
        }

        var vatToggleBtn = document.getElementById('pos-vat-toggle');
        if (vatToggleBtn) {
            vatToggleBtn.addEventListener('click', toggleVatDetails);
        }

        var scPwdModal = document.getElementById('pos-sc-pwd-modal');
        var scPwdModalBackdrop = document.getElementById('pos-sc-pwd-modal-backdrop');
        var scPwdModalCancel = document.getElementById('pos-sc-pwd-modal-cancel');
        var scPwdModalApply = document.getElementById('pos-sc-pwd-modal-apply');
        var posScPwdIdInput = document.getElementById('pos-sc-pwd-id');
        if (scPwdModalBackdrop) scPwdModalBackdrop.addEventListener('click', closeScPwdModal);
        if (scPwdModalCancel) scPwdModalCancel.addEventListener('click', closeScPwdModal);
        if (scPwdModalApply) scPwdModalApply.addEventListener('click', applyScPwdDiscount);
        if (posScPwdIdInput) {
            posScPwdIdInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); applyScPwdDiscount(); }
            });
        }
        if (scPwdModal) {
            scPwdModal.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') { e.preventDefault(); closeScPwdModal(); }
            });
        }

        var discountDropdownBtn = document.getElementById('pos-discount-dropdown-btn');
        var discountDropdown = document.getElementById('pos-discount-dropdown');
        if (discountDropdownBtn && discountDropdown) {
            discountDropdownBtn.addEventListener('click', function (e) { e.stopPropagation(); toggleDiscountDropdown(); });
            document.addEventListener('click', function () { discountDropdown.classList.add('hidden'); });
        }
        document.querySelectorAll('.pos-discount-option').forEach(function (opt) {
            opt.addEventListener('click', function (e) {
                e.stopPropagation();
                discountDropdown.classList.add('hidden');
                var type = this.getAttribute('data-type');
                if (type === 'sc_pwd') openScPwdModal();
                else if (type === 'employee') openManualDiscountModal('Employee discount', 'employee');
                else if (type === 'promo') openManualDiscountModal('Promo discount', 'promo');
                else if (type === 'manual') openManualDiscountModal('Manual discount', 'manual');
            });
        });

        var serviceChargeBtn = document.getElementById('pos-service-charge-btn');
        if (serviceChargeBtn) {
            serviceChargeBtn.addEventListener('click', function () {
                var subtotal = cartItems.reduce(function (s, i) { return s + i.unit_price * i.quantity; }, 0);
                if (subtotal <= 0) { if (typeof Swal !== 'undefined') Swal.fire({ icon: 'warning', title: 'No items', text: 'Add items first.' }); return; }
                if (typeof Swal === 'undefined') { serviceChargeAmount = Math.round(subtotal * 0.05 * 100) / 100; renderCart(); saveCartToStorage(); return; }
                Swal.fire({
                    title: 'Service charge',
                    input: 'number',
                    inputValue: serviceChargeAmount || '',
                    inputPlaceholder: 'Amount (₱)',
                    inputAttributes: { min: 0, step: 0.01 },
                    showCancelButton: true,
                    confirmButtonText: 'Add'
                }).then(function (res) {
                    if (res.isConfirmed && res.value !== undefined) { serviceChargeAmount = parseFloat(res.value) || 0; renderCart(); saveCartToStorage(); }
                });
            });
        }

        document.querySelectorAll('.pos-tender-type-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var type = this.getAttribute('data-type') || 'cash';
                setTenderType(type);
            });
        });
        setTenderType('cash');

        var paymentModal = document.getElementById('pos-payment-modal');
        var paymentModalBackdrop = document.getElementById('pos-payment-modal-backdrop');
        var paymentModalCancel = document.getElementById('pos-payment-modal-cancel');
        var paymentModalApply = document.getElementById('pos-payment-modal-apply');
        var paymentModalAmount = document.getElementById('pos-payment-modal-amount');
        if (paymentModalBackdrop) paymentModalBackdrop.addEventListener('click', function () { closePaymentModal(false); });
        if (paymentModalCancel) paymentModalCancel.addEventListener('click', function () { closePaymentModal(false); });
        if (paymentModalApply) paymentModalApply.addEventListener('click', applyPaymentFromModal);
        if (paymentModalAmount) {
            paymentModalAmount.addEventListener('input', updatePaymentModalChangeFromDom);
            paymentModalAmount.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyPaymentFromModal();
                }
            });
        }
        if (paymentModal) {
            paymentModal.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    e.preventDefault();
                    closePaymentModal(false);
                }
            });
        }

        var rxModal = document.getElementById('pos-rx-modal');
        var rxModalBackdrop = document.getElementById('pos-rx-modal-backdrop');
        var rxModalCancel = document.getElementById('pos-rx-modal-cancel');
        var rxModalApply = document.getElementById('pos-rx-modal-apply');
        var rxNumberInput = document.getElementById('pos-rx-number');
        var rxDoctorInput = document.getElementById('pos-rx-doctor');
        if (rxModalBackdrop) rxModalBackdrop.addEventListener('click', function () { closeRxModal(null); });
        if (rxModalCancel) rxModalCancel.addEventListener('click', function () { closeRxModal(null); });
        if (rxModalApply) rxModalApply.addEventListener('click', applyRxFromModal);
        if (rxNumberInput) {
            rxNumberInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyRxFromModal();
                }
            });
        }
        if (rxDoctorInput) {
            rxDoctorInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyRxFromModal();
                }
            });
        }
        if (rxModal) {
            rxModal.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    e.preventDefault();
                    closeRxModal(null);
                }
            });
        }

        var amountInput = document.getElementById('pos-amount-received');
        if (amountInput) {
            amountInput.addEventListener('input', updateChange);
        }

        var gridBtn = document.getElementById('pos-view-grid');
        var listBtn = document.getElementById('pos-view-list');
        if (gridBtn) gridBtn.addEventListener('click', function () { setView('grid'); });
        if (listBtn) listBtn.addEventListener('click', function () { setView('list'); });
        setView('grid');

        var searchInput = document.getElementById('pos-search-input');
        if (searchInput) {
            var searchTimer = null;
            searchInput.addEventListener('input', function () {
                if (searchTimer) clearTimeout(searchTimer);
                searchTimer = setTimeout(applyFilters, 250);
            });
        }

        var scanBtn = document.getElementById('pos-scan-btn');
        if (scanBtn) {
            scanBtn.addEventListener('click', function () {
                triggerBarcodeScan();
            });
        }

        document.querySelectorAll('.pos-category-chip').forEach(function (chip) {
            chip.addEventListener('click', function () {
                document.querySelectorAll('.pos-category-chip').forEach(function (c) {
                    c.classList.remove('border-primary', 'bg-primary', 'text-white', 'font-semibold', 'shadow-sm');
                    c.classList.add('border-slate-200', 'dark:border-darkmode-500', 'bg-transparent', 'text-slate-500', 'dark:text-slate-400', 'font-medium');
                    var badge = c.querySelector('.pos-tab-badge');
                    if (badge) {
                        badge.classList.remove('bg-white/25');
                        badge.classList.add('bg-slate-100', 'dark:bg-darkmode-600', 'text-slate-500', 'dark:text-slate-400');
                    }
                });
                this.classList.add('border-primary', 'bg-primary', 'text-white', 'font-semibold', 'shadow-sm');
                this.classList.remove('border-slate-200', 'dark:border-darkmode-500', 'bg-transparent', 'text-slate-500', 'dark:text-slate-400', 'font-medium');
                var badge = this.querySelector('.pos-tab-badge');
                if (badge) {
                    badge.classList.add('bg-white/25');
                    badge.classList.remove('bg-slate-100', 'dark:bg-darkmode-600', 'text-slate-500', 'dark:text-slate-400');
                }
                currentCategory = this.getAttribute('data-category') || '';
                applyFilters();
            });
        });

        var completeBtn = document.getElementById('pos-complete-sale-btn');
        if (completeBtn) {
            completeBtn.addEventListener('click', handleCompleteSaleClick);
        }

        document.addEventListener('keydown', function (e) {
            var tag = document.activeElement && document.activeElement.tagName ? document.activeElement.tagName.toUpperCase() : '';
            var inInput = tag === 'INPUT' || tag === 'TEXTAREA' || (document.activeElement && document.activeElement.isContentEditable);
            if (inInput && e.key !== 'F3') return;
            if (e.key === 'F1') {
                e.preventDefault();
                var newSaleBtn = document.getElementById('pos-new-sale-btn');
                if (newSaleBtn) newSaleBtn.click();
            } else if (e.key === 'F2') {
                e.preventDefault();
                triggerBarcodeScan();
            } else if (e.key === 'F3') {
                e.preventDefault();
                var searchInput = document.getElementById('pos-search-input');
                if (searchInput) { searchInput.focus(); searchInput.select(); }
            } else if (e.key === 'F4') {
                e.preventDefault();
                holdOrder();
            } else if (e.key === 'F5') {
                e.preventDefault();
                var discountBtn = document.getElementById('pos-discount-dropdown-btn');
                var discountDd = document.getElementById('pos-discount-dropdown');
                if (discountBtn && discountDd) {
                    discountDd.classList.toggle('hidden');
                    if (!discountDd.classList.contains('hidden')) discountBtn.focus();
                }
            } else if (e.key === 'F6') {
                e.preventDefault();
                var dd = document.getElementById('pos-discount-dropdown');
                if (dd && !dd.classList.contains('hidden')) dd.classList.add('hidden');
                openScPwdModal();
            } else if (e.key === 'F7') {
                e.preventDefault();
                var clearBtn = document.getElementById('pos-clear-order-btn');
                if (clearBtn) clearBtn.click();
            } else if (e.key === 'F8') {
                e.preventDefault();
                completeSale();
            }
        });
    })();

    // Initial load: restore cart from localStorage, then load terminal and products
    loadCartFromStorage();
    loadTerminalAndProducts();
})();
</script>
@endpush

