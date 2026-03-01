@extends('super-admin.layouts.pos')

@section('title', 'POS (Cashier)')
@section('breadcrumb', 'POS')

@section('content')
    <div class="pb-20 print:pb-0 flex justify-center @if(config('pos.touchscreen', false)) pos-touchscreen @endif" data-pos-touchscreen="{{ config('pos.touchscreen', false) ? '1' : '0' }}">
    {{-- Centered container: 1024×768 on large screens, centered --}}
    <div class="w-full max-w-[1024px] mx-auto px-2 sm:px-3 lg:max-h-[768px] lg:flex lg:flex-col lg:overflow-hidden">
    {{-- POS not ready: polished full-page state (gradient, card, status chip, footer) --}}
    <div id="pos-not-ready-container" class="hidden print:hidden w-full min-h-[320px] sm:min-h-[400px] flex flex-col items-center justify-center p-4 sm:p-6 pos-not-ready-bg">
        <div class="w-full max-w-lg mx-auto">
            <div class="rounded-2xl border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-xl border-t-4 border-t-primary p-6 sm:p-10 md:p-12 lg:p-16 text-center">
                {{-- Brand header: logo mark + bold name --}}
                <div class="flex items-center justify-center gap-2 mb-6">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </span>
                    <span class="text-sm font-bold tracking-tight text-gray-900 dark:text-slate-100">Landogz POS</span>
                </div>
                {{-- Icon + status chip grouped and centered --}}
                <div class="flex flex-col items-center mb-4">
                    {{-- Warning icon: outer glow ring + pulse ring + inner circle --}}
                    <div class="relative inline-flex items-center justify-center w-24 h-24 mb-3">
                        <span class="pos-not-ready-icon-glow absolute inline-flex h-full w-full rounded-full bg-amber-100/80 dark:bg-amber-900/20" aria-hidden="true"></span>
                        <span class="pos-not-ready-icon-pulse absolute inline-flex h-20 w-20 rounded-full bg-amber-400/40 dark:bg-amber-500/30" aria-hidden="true"></span>
                        <span class="pos-not-ready-icon-inner relative inline-flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                        </span>
                    </div>
                    {{-- Status chip directly below icon --}}
                    <div id="pos-not-ready-status-chip" class="inline-flex items-center gap-1.5 rounded-full bg-red-50 dark:bg-red-900/20 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-300">
                        <span class="pos-not-ready-status-dot relative flex h-2 w-2 rounded-full bg-red-500" id="pos-not-ready-status-dot"></span>
                        <span id="pos-not-ready-status-label">Inactive</span>
                    </div>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-slate-100">POS not ready</h2>
                <p id="pos-not-ready-message" class="mt-3 text-sm leading-relaxed text-gray-500 dark:text-slate-400">
                    <span id="pos-not-ready-line1">This terminal is not registered or inactive.</span><br>
                    <span id="pos-not-ready-line2">Contact the software provider to register this POS.</span>
                </p>
                <p id="pos-not-ready-terminal-info" class="mt-2 text-sm text-red-900/60 dark:text-red-300/70">Terminal: <span id="pos-not-ready-terminal-label">Not detected</span> · Branch: <span id="pos-not-ready-branch-label">Unassigned</span></p>
                <div class="mt-8 flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3 sm:gap-4">
                    <a href="mailto:rolan@landogzwebsolutions.com?subject=POS%20Registration%20%2F%20Terminal%20Support" class="inline-flex sm:min-w-[180px] w-full sm:w-auto items-center justify-center gap-2 rounded-lg bg-primary px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-primary/90 hover:scale-105 active:scale-100 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Contact support
                    </a>
                    <button type="button" id="pos-not-ready-retry-btn" class="inline-flex sm:min-w-[180px] w-full sm:w-auto items-center justify-center gap-2 rounded-lg border-2 border-primary/50 dark:border-primary/40 bg-white dark:bg-darkmode-700 px-6 py-3 text-sm font-medium text-slate-700 dark:text-slate-200 hover:border-primary hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed">
                        <span id="pos-not-ready-retry-icon" class="inline-flex shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </span>
                        <span id="pos-not-ready-retry-spinner" class="hidden h-4 w-4 shrink-0 animate-spin rounded-full border-2 border-slate-300 border-t-primary"></span>
                        <span id="pos-not-ready-retry-label">Retry</span>
                    </button>
                </div>
                <a href="#" id="pos-not-ready-learn-link" class="mt-4 inline-block text-xs text-primary hover:underline focus:outline-none focus:ring-2 focus:ring-primary/30 rounded">Learn how to register this terminal →</a>
                <footer class="mt-10 pt-8 my-6 border-t border-slate-200 dark:border-darkmode-600 text-xs space-y-1">
                    <p class="text-gray-500 dark:text-slate-400">Last checked: <span id="pos-not-ready-last-checked">just now</span></p>
                    <p class="text-gray-400 dark:text-slate-500">Terminal ID: <span id="pos-not-ready-footer-terminal">Not detected</span></p>
                    <p class="text-gray-400 dark:text-slate-500">App version: <span id="pos-not-ready-version">v1.0.0</span></p>
                </footer>
            </div>
        </div>
    </div>
    {{-- POS main content (hidden when terminal not ready) --}}
    <div id="pos-main-content" class="flex flex-col flex-1 min-h-0 min-w-0">
    {{-- POS header: two-tier (branding + total | OR + date + welcome + actions), brand primary --}}
    <div class="sticky top-0 z-30 mt-1 mb-2 w-full print:hidden shrink-0">
        <header class="w-full overflow-hidden rounded-lg shadow-md">
            {{-- Top bar: gradient, branding left, connection + total right --}}
            <div class="flex items-center justify-between gap-3 bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 px-3 py-2 sm:px-4 sm:py-2.5 shadow-sm">
                <div class="min-w-0">
                    <h1 class="text-base font-bold tracking-tight text-white sm:text-lg">Landogz POS</h1>
                    <p class="mt-0.5 text-[10px] sm:text-xs text-white/85">
                        Point of Sale<span id="pos-company-label" class="text-white/90"></span>
                    </p>
                </div>
                <div class="flex shrink-0 items-center gap-2">
                    <span class="hidden sm:inline text-[10px] font-medium uppercase tracking-wider text-white/80">Total</span>
                    <div class="flex items-center justify-end rounded-lg bg-slate-900/90 px-3 py-2 min-w-[88px] sm:min-w-[112px] ring-1 ring-white/10">
                        <span id="pos-header-total" class="text-base font-bold tabular-nums text-white sm:text-lg pos-header-total-count">₱0.00</span>
                    </div>
                </div>
            </div>
            {{-- Bottom bar: connection status, OR, branch/terminal, shift timer, date, welcome, actions --}}
            <div class="flex flex-nowrap items-center justify-between gap-1.5 bg-gradient-to-r from-slate-800 to-slate-900 px-3 py-1.5 sm:px-4 sm:py-2 overflow-x-auto">
                <div class="flex flex-nowrap items-center gap-x-2 sm:gap-x-3 min-w-0">
                    <span id="pos-connection-dot" class="shrink-0 inline-block h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_6px_rgba(52,211,153,0.8)]" title="Online" aria-hidden="true"></span>
                    <div class="flex items-center gap-1 text-white">
                        <span class="text-[10px] sm:text-xs font-medium">OR Number:</span>
                        <span id="pos-header-or" class="text-[10px] sm:text-xs font-semibold tracking-wide text-white">Pending</span>
                    </div>
                    <span class="hidden sm:inline text-slate-400">|</span>
                    <div class="flex items-center gap-1 text-[10px] sm:text-xs min-w-0 text-white">
                        <span id="pos-branch-label" class="truncate font-medium text-white">—</span>
                        <span class="shrink-0 text-slate-400">·</span>
                        <span id="pos-terminal-label" class="truncate text-white">—</span>
                        <span class="shrink-0 text-slate-400">·</span>
                        <span id="pos-shift-label" class="shrink-0 text-white">Day</span>
                        <span class="hidden sm:inline shrink-0 text-slate-400">·</span>
                        <span id="pos-shift-timer" class="hidden sm:inline shrink-0 text-white/90 font-medium" title="Session duration">Shift: 0m</span>
                    </div>
                    <span id="pos-status-ready" class="hidden md:inline text-[10px] text-slate-300">POS Ready</span>
                </div>
                <div class="flex flex-nowrap items-center justify-end gap-1.5 sm:gap-2 shrink-0">
                    <span id="pos-date-label" class="text-[10px] sm:text-xs text-white">—</span>
                    <span class="hidden sm:inline text-slate-400">|</span>
                    <span class="text-[10px] sm:text-xs text-white">Welcome : <span id="pos-cashier-label" class="font-semibold text-white">—</span></span>
                    <div class="flex items-center gap-0.5 border-l border-slate-600 pl-1.5 ml-0.5">
                        <button type="button" id="pos-new-sale-btn" class="inline-flex items-center justify-center gap-1 rounded-lg bg-slate-700 px-2 py-1 text-[10px] sm:text-xs font-semibold text-white hover:bg-slate-600 transition min-h-[28px]" title="New sale (F1)">
                            <i data-lucide="plus" class="h-3.5 w-3.5 text-white stroke-[2] shrink-0"></i>
                            <span class="hidden sm:inline">New Sale</span>
                        </button>
                        <button type="button" id="pos-sales-history-btn" class="inline-flex items-center justify-center gap-1 rounded-lg bg-slate-700 px-2 py-1 text-[10px] sm:text-xs font-semibold text-white hover:bg-slate-600 transition min-h-[28px]" title="Sales history (F11)">
                            <i data-lucide="clipboard-list" class="h-3.5 w-3.5 text-white stroke-[2] shrink-0"></i>
                            <span class="hidden sm:inline">Sales</span>
                        </button>
                        <button type="button" id="pos-x-reading-btn" class="inline-flex items-center justify-center gap-1 rounded-lg bg-slate-700 px-2 py-1 text-[10px] sm:text-xs font-semibold text-white hover:bg-slate-600 transition min-h-[28px]" title="X-Reading — mid-day snapshot (does not reset counters)">
                            <i data-lucide="file-bar-chart" class="h-3.5 w-3.5 text-white stroke-[2] shrink-0"></i>
                            <span class="hidden sm:inline">X-Reading</span>
                        </button>
                        <div class="relative shrink-0" id="pos-shortcuts-dropdown-wrap">
                            <button type="button" id="pos-shortcuts-trigger" class="inline-flex items-center justify-center gap-0.5 rounded-lg bg-slate-700 px-1.5 py-1 text-[10px] sm:text-xs font-medium text-white hover:bg-slate-600 transition" aria-haspopup="true" aria-expanded="false" title="Keyboard shortcuts">
                                <i data-lucide="keyboard" class="h-3.5 w-3.5 text-white stroke-[2] shrink-0"></i>
                                <span class="hidden sm:inline">Shortcuts</span>
                                <i data-lucide="chevron-down" class="h-2.5 w-2.5 text-slate-400 transition-transform pos-shortcuts-chevron shrink-0"></i>
                            </button>
                            <div id="pos-shortcuts-panel" class="hidden w-56 rounded-lg border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-xl py-1.5 fixed z-[99999]" role="menu" aria-label="Keyboard shortcuts" data-portal="1">
                                <div class="px-3 py-2 text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide border-b border-slate-100 dark:border-darkmode-600">Keyboard shortcuts</div>
                                <div class="py-1 text-xs text-slate-700 dark:text-slate-200">
                                    <div class="px-3 py-1.5 flex justify-between gap-4"><span>New sale</span><kbd class="font-mono text-slate-500 text-[11px]">F1</kbd></div>
                                    <div class="px-3 py-1.5 flex justify-between gap-4"><span>Scan</span><kbd class="font-mono text-slate-500 text-[11px]">F2</kbd></div>
                                    <div class="px-3 py-1.5 flex justify-between gap-4"><span>Search</span><kbd class="font-mono text-slate-500 text-[11px]">F3</kbd></div>
                                    <div class="px-3 py-1.5 flex justify-between gap-4"><span>Hold</span><kbd class="font-mono text-slate-500 text-[11px]">F4</kbd></div>
                                    <div class="px-3 py-1.5 flex justify-between gap-4"><span>Discount</span><kbd class="font-mono text-slate-500 text-[11px]">F5</kbd></div>
                                    <div class="px-3 py-1.5 flex justify-between gap-4"><span>SC/PWD</span><kbd class="font-mono text-slate-500 text-[11px]">F6</kbd></div>
                                    <div class="px-3 py-1.5 flex justify-between gap-4"><span>Clear</span><kbd class="font-mono text-slate-500 text-[11px]">F7</kbd></div>
                                    <div class="px-3 py-1.5 flex justify-between gap-4"><span>Complete sale</span><kbd class="font-mono text-slate-500 text-[11px]">F8</kbd></div>
                                    <div class="px-3 py-1.5 flex justify-between gap-4"><span>Re-print</span><kbd class="font-mono text-slate-500 text-[11px]">F9</kbd></div>
                                    <div class="px-3 py-1.5 flex justify-between gap-4"><span>Lock</span><kbd class="font-mono text-slate-500 text-[11px]">F10</kbd></div>
                                    <div class="px-3 py-1.5 flex justify-between gap-4"><span>Sales history</span><kbd class="font-mono text-slate-500 text-[11px]">F11</kbd></div>
                                    <div class="px-3 py-1.5 flex justify-between gap-4"><span>X-Reading</span><span class="text-slate-400 text-[11px]">Mid-day report</span></div>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="pos-lock-btn" class="inline-flex items-center justify-center gap-0.5 rounded-lg bg-slate-700 px-1.5 py-1 text-[10px] sm:text-xs font-medium text-white hover:bg-slate-600 transition" title="Lock POS (F10)">
                            <i data-lucide="lock" class="h-3.5 w-3.5 text-white stroke-[2] shrink-0"></i>
                            <span class="hidden sm:inline">Lock</span>
                        </button>
                        <button type="button" id="pos-logout-btn" class="inline-flex items-center justify-center gap-0.5 rounded-lg bg-slate-700 px-1.5 py-1 text-[10px] sm:text-xs font-medium text-white hover:bg-slate-600 hover:text-rose-200 transition" title="Logout">
                            <i data-lucide="log-out" class="h-3.5 w-3.5 text-white stroke-[2] shrink-0"></i>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>
    </div>

    {{-- Main POS layout: fills remaining height; capped at 768px on lg; single row so products + order fit without page scroll --}}
    <div class="mt-2 sm:mt-3 grid grid-cols-12 gap-2 sm:gap-3 max-h-[calc(100vh-11rem)] lg:max-h-none lg:flex-1 lg:min-h-0 lg:overflow-hidden lg:grid-rows-1 min-h-0">
        {{-- Product catalog --}}
        <div class="col-span-8 flex flex-col min-h-0 overflow-hidden">
            {{-- Search + filters --}}
            <div class="rounded-xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm px-2 py-2 sm:px-3 sm:py-2.5">
                <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-2 w-full">
                    <div class="relative text-slate-500 flex-1 min-w-0">
                        <input
                            type="text"
                            id="pos-search-input"
                            placeholder="Search product name, generic name, or barcode…"
                            class="pos-search-input w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-slate-50/50 dark:bg-darkmode-700/50 px-3 py-2 pr-9 text-xs sm:text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-primary/20 focus:border-primary dark:focus:border-primary transition @if(config('pos.touchscreen', false)) js-kioskboard-input @endif"
                            @if(config('pos.touchscreen', false)) inputmode="search" data-kioskboard-type="all" data-kioskboard-placement="bottom" @endif
                            autocomplete="off"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 pointer-events-none text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                    <button type="button" id="pos-scan-btn" class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-200 hover:border-primary hover:bg-primary/5 hover:text-primary transition whitespace-nowrap shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M7 8h10"/><path d="M7 12h6"/><path d="M17 12h.01"/><path d="M7 16h4"/></svg>
                        Scan
                    </button>
                </div>
                <div class="mt-2 flex flex-wrap items-center justify-between gap-1.5">
                    <div class="flex items-center gap-1.5 overflow-x-auto pb-0.5">
                        <button type="button" class="pos-category-chip inline-flex items-center h-7 sm:h-8 rounded-full border-[1.5px] border-primary bg-primary text-white font-semibold text-xs px-3 whitespace-nowrap transition-all duration-150 shadow-sm" data-category="">
                            All <span id="pos-tab-count-all" class="pos-tab-badge ml-1 rounded-full bg-white/25 px-1 py-0.5 text-[10px] font-medium">0</span>
                        </button>
                        <button type="button" class="pos-category-chip inline-flex items-center h-7 sm:h-8 rounded-full border-[1.5px] border-slate-200 dark:border-darkmode-500 bg-transparent text-slate-500 dark:text-slate-400 font-medium text-xs px-3 whitespace-nowrap transition-all duration-150 hover:border-primary hover:bg-primary/5 hover:text-primary dark:hover:bg-primary/10" data-category="rx" title="Prescription Items">
                            Rx <span id="pos-tab-count-rx" class="pos-tab-badge ml-1 rounded-full bg-slate-100 dark:bg-darkmode-600 text-slate-500 dark:text-slate-400 px-1 py-0.5 text-[10px] font-medium">0</span>
                        </button>
                        <button type="button" class="pos-category-chip inline-flex items-center h-7 sm:h-8 rounded-full border-[1.5px] border-slate-200 dark:border-darkmode-500 bg-transparent text-slate-500 dark:text-slate-400 font-medium text-xs px-3 whitespace-nowrap transition hover:border-primary hover:bg-primary/5 hover:text-primary dark:hover:bg-primary/10" data-category="otc">
                            OTC <span id="pos-tab-count-otc" class="pos-tab-badge ml-1 rounded-full bg-slate-100 dark:bg-darkmode-600 text-slate-500 dark:text-slate-400 px-1 py-0.5 text-[10px] font-medium">0</span>
                        </button>
                        <button type="button" class="pos-category-chip inline-flex items-center h-7 sm:h-8 rounded-full border-[1.5px] border-slate-200 dark:border-darkmode-500 bg-transparent text-slate-500 dark:text-slate-400 font-medium text-xs px-3 whitespace-nowrap transition-all duration-150 hover:border-primary hover:bg-primary/5 hover:text-primary dark:hover:bg-primary/10" data-category="supplies">
                            Supplies <span id="pos-tab-count-supplies" class="pos-tab-badge ml-1 rounded-full bg-slate-100 dark:bg-darkmode-600 text-slate-500 dark:text-slate-400 px-1 py-0.5 text-[10px] font-medium">0</span>
                        </button>
                        <button type="button" class="pos-category-chip inline-flex items-center h-7 sm:h-8 rounded-full border-[1.5px] border-slate-200 dark:border-darkmode-500 bg-transparent text-slate-500 dark:text-slate-400 font-medium text-xs px-3 whitespace-nowrap transition-all duration-150 hover:border-amber-500 hover:bg-amber-50 hover:text-amber-700 dark:hover:bg-amber-900/20" data-category="favorites" title="Frequent / top sold">
                            Frequent <span id="pos-tab-count-favorites" class="pos-tab-badge ml-1 rounded-full bg-slate-100 dark:bg-darkmode-600 text-slate-500 dark:text-slate-400 px-1 py-0.5 text-[10px] font-medium">0</span>
                        </button>
                    </div>
                    <div class="inline-flex items-center gap-0.5 rounded-full bg-slate-100 dark:bg-darkmode-700 px-1 py-0.5">
                        <span class="text-[10px] font-medium text-slate-500 dark:text-slate-400 px-1">Display</span>
                        <button type="button" id="pos-view-grid" class="pos-display-btn inline-flex h-6 w-6 sm:h-7 sm:w-7 items-center justify-center rounded-md bg-primary text-white shadow-sm" title="Grid view">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-3.5 sm:w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        </button>
                        <button type="button" id="pos-view-list" class="pos-display-btn inline-flex h-6 w-6 sm:h-7 sm:w-7 items-center justify-center rounded-md text-slate-400 hover:text-primary hover:bg-white dark:hover:bg-darkmode-600 transition-all duration-150" title="List view">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-3.5 sm:w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><circle cx="4" cy="6" r="1.5"/><circle cx="4" cy="12" r="1.5"/><circle cx="4" cy="18" r="1.5"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Product grid / list: scrollable to fit height --}}
            <div class="mt-2 flex-1 min-h-0 rounded-xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm flex flex-col overflow-hidden">
                <div id="pos-products-empty" class="flex-1 flex flex-col items-center justify-center text-center px-4 py-6 space-y-2">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 dark:bg-darkmode-700 text-slate-500 dark:text-slate-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="3"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-slate-700 dark:text-slate-200">No items loaded yet</p>
                        <p class="mt-0.5 text-[10px] text-slate-500 dark:text-slate-400">Use search, categories, or barcode scan.</p>
                    </div>
                </div>
                <div id="pos-products-grid" class="hidden flex-1 min-h-0 overflow-hidden flex flex-col">
                    <div class="flex-1 min-h-0 overflow-auto">
                        <div class="grid grid-cols-3 gap-2 sm:gap-3 p-2 sm:p-3 w-full">
                            {{-- Product cards will be rendered here by JavaScript --}}
                        </div>
                    </div>
                </div>
                <div id="pos-products-list" class="hidden flex-1 min-h-0 overflow-hidden flex flex-col">
                    <div id="pos-products-list-inner" class="w-full flex-1 min-h-0 overflow-y-auto overflow-x-hidden p-2 sm:p-3 space-y-2">
                        {{-- Product list rows will be rendered here by JavaScript --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Current order / payment (reference layout) --}}
        <div class="col-span-4 flex flex-col gap-2 min-h-0 min-w-0 overflow-auto sticky top-[5.5rem] self-start max-h-[calc(100vh-11rem)]">
            <div class="pos-order-panel flex flex-col rounded-xl border border-slate-200 bg-white shadow-md min-h-0 min-w-0 overflow-hidden">
                {{-- Header: Current Order + badge + Hold + Clear; then tags + OR --}}
                <div class="border-b border-slate-200 bg-white px-2 py-2 sm:px-3 sm:py-2.5">
                    <div class="flex items-center justify-between gap-2 flex-wrap min-w-0">
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 shrink-0">Current Order</h2>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <button type="button" id="pos-customer-quick-add-btn" class="inline-flex items-center gap-0.5 rounded-md bg-emerald-50 border border-emerald-200 px-2 py-1 text-[10px] font-medium text-emerald-700 hover:bg-emerald-100 transition-colors" title="Add customer / Senior or PWD ID for discount">Customer</button>
                            <span id="pos-order-item-badge" class="inline-flex items-center rounded-md bg-sky-100 text-sky-700 px-2 py-0.5 text-[10px] font-semibold hidden">0 items</span>
                            <button type="button" id="pos-hold-order-btn" class="inline-flex items-center gap-0.5 rounded-md bg-amber-50 border border-amber-200 px-2 py-1 text-[10px] font-medium text-amber-700 hover:bg-amber-100 transition-colors" title="Hold / Retrieve"><span id="pos-hold-btn-text">Hold</span></button>
                            <button type="button" id="pos-clear-order-btn" class="inline-flex items-center gap-0.5 rounded-md bg-rose-50 border border-rose-200 px-2 py-1 text-[10px] font-medium text-rose-600 hover:bg-rose-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                Clear
                            </button>
                        </div>
                    </div>
                    <div class="mt-1.5 flex items-center justify-between gap-2 flex-wrap">
                        <div class="flex items-center gap-1.5">
                            <span id="pos-order-type-label" class="inline-flex items-center rounded-md bg-slate-100 text-slate-600 px-2 py-0.5 text-[10px] font-medium">Walk-in</span>
                            <span id="pos-table-label" class="inline-flex items-center rounded-md bg-slate-100 text-slate-600 px-2 py-0.5 text-[10px] font-medium">Counter sale</span>
                        </div>
                        <span id="pos-or-badge" class="text-[10px] text-slate-500">OR: <span id="pos-or-placeholder" class="font-semibold text-slate-700">Pending</span></span>
                    </div>
                </div>
                {{-- Order items: constrained height so VAT + Total due + Payment stay visible --}}
                <div id="pos-order-items" class="h-[160px] min-h-[100px] max-h-[200px] overflow-x-auto overflow-y-auto mx-2 my-2 space-y-2 px-2 pr-3 shrink-0">
                    <div class="px-2 py-6 text-center text-slate-400 text-[10px]">
                        No items yet. Tap <span class="font-semibold text-sky-600">Add</span> on items to build order.
                    </div>
                </div>
                {{-- Summary: Subtotal, Discounts, VAT Details --}}
                <div class="border-t border-slate-200 px-2 py-2 sm:px-3 sm:py-2.5 space-y-1.5 min-w-0">
                    <div class="flex items-center justify-between gap-2 text-[11px] text-slate-700 min-w-0">
                        <span class="shrink-0">Subtotal</span>
                        <span id="pos-summary-subtotal" class="tabular-nums truncate text-right">₱0.00</span>
                    </div>
                    <div class="flex items-center justify-between gap-2 text-[11px] text-slate-700 flex-wrap min-w-0">
                        <span class="flex items-center gap-1.5 flex-wrap min-w-0">
                            <span class="shrink-0">Discounts</span>
                            <button type="button" id="pos-sc-pwd-btn" class="inline-flex items-center rounded-md bg-emerald-100 text-emerald-700 px-1.5 py-0.5 text-[10px] font-semibold hover:bg-emerald-200 transition-colors">SC/PWD 20%</button>
                            <div class="relative inline-block">
                                <button type="button" id="pos-discount-dropdown-btn" class="inline-flex items-center text-primary text-[10px] font-medium hover:underline">+ Add</button>
                                <div id="pos-discount-dropdown" class="hidden absolute left-0 top-full mt-1 z-20 min-w-[160px] rounded-lg border border-slate-200 bg-white shadow-lg py-1">
                                    <button type="button" class="pos-discount-option w-full text-left px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50" data-type="sc_pwd">SC/PWD (20%)</button>
                                    <button type="button" class="pos-discount-option w-full text-left px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50" data-type="employee">Employee discount</button>
                                    <button type="button" class="pos-discount-option w-full text-left px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50" data-type="promo">Promo discount</button>
                                    <button type="button" class="pos-discount-option w-full text-left px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50" data-type="manual">Manual discount</button>
                                </div>
                            </div>
                        </span>
                        <span id="pos-discount-summary" class="text-[11px] font-medium text-emerald-600 tabular-nums shrink-0">-₱0.00</span>
                    </div>
                    <div class="relative">
                        <button type="button" id="pos-vat-toggle" class="flex w-full items-center justify-between text-[11px] text-slate-700 hover:text-slate-900">
                            <span class="inline-flex items-center gap-1">
                                <svg id="pos-vat-toggle-icon" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-500 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.06l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                </svg>
                                <span>VAT Details</span>
                            </span>
                            <span class="text-[10px] text-slate-500">incl.</span>
                        </button>
                        {{-- VAT breakdown: floating container so Total due / Payment stay visible; always visible on print --}}
                        <div id="pos-vat-breakdown-wrap" class="hidden fixed z-[110] min-w-[200px] max-w-[280px] max-h-[240px] overflow-y-auto rounded-lg border border-slate-200 bg-white shadow-xl py-2 px-3 print:!static print:!block print:!max-h-none print:!shadow-none print:min-w-0 print:max-w-none" role="dialog" aria-label="VAT breakdown" style="top: 0; left: 0;">
                            <div id="pos-vat-breakdown" class="space-y-0.5 text-[11px]">
                                <div class="flex items-center justify-between text-xs text-slate-500">
                                    <span>Total sales (VAT inclusive)</span>
                                    <span id="pos-subtotal">₱0.00</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-500">
                                    <span>VAT Sales</span>
                                    <span id="pos-vatable-sales">₱0.00</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-500">
                                    <span>Non‑VAT Sales</span>
                                    <span id="pos-vat-exempt">₱0.00</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-500">
                                    <span>Zero-Rated Sales</span>
                                    <span id="pos-zero-rated">₱0.00</span>
                                </div>
                                <div class="flex items-center justify-between text-xs text-slate-500">
                                    <span>Total VAT (12%)</span>
                                    <span id="pos-vat-amount">₱0.00</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-500">
                                    <span>Total Discount</span>
                                    <span id="pos-total-discount">₱0.00</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-500">
                                    <span>VAT Exemption</span>
                                    <span id="pos-vat-exemption">₱0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="pos-applied-discounts" class="space-y-0.5 hidden"></div>
                    {{-- Total due: prominent block (min-w-0 so amount not clipped) --}}
                    <div class="mt-2 flex items-center justify-between gap-2 rounded-lg bg-slate-100 px-2 sm:px-3 py-2.5 min-w-0">
                        <span class="text-[10px] font-bold uppercase tracking-wide text-slate-600 shrink-0">Total due</span>
                        <span id="pos-total-due" class="text-lg sm:text-xl font-bold text-slate-900 tabular-nums truncate text-right min-w-0">₱0.00</span>
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

            {{-- Payment mode + Complete sale (compressed so order items get more height) --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm p-2 sm:p-2.5 space-y-2">
                <div>
                    <span class="block text-[9px] font-semibold text-slate-500 uppercase tracking-wide mb-1">Payment mode</span>
                    <div class="grid grid-cols-4 gap-1">
                        <button type="button" class="pos-tender-type-btn flex flex-col items-center justify-center gap-0.5 rounded-md border-2 border-slate-200 bg-white px-1 py-1.5 text-center transition-all duration-200 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:ring-offset-0 data-[selected=true]:border-sky-400 data-[selected=true]:bg-sky-50" data-type="cash">
                            <span class="flex items-center justify-center w-6 h-6 rounded-md bg-emerald-100 text-emerald-600 text-base leading-none" aria-hidden="true">💵</span>
                            <span class="text-[9px] font-semibold text-slate-700">Cash</span>
                        </button>
                        <button type="button" class="pos-tender-type-btn flex flex-col items-center justify-center gap-0.5 rounded-md border-2 border-slate-200 bg-white px-1 py-1.5 text-center transition-all duration-200 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:ring-offset-0 data-[selected=true]:border-sky-400 data-[selected=true]:bg-sky-50" data-type="card">
                            <span class="flex items-center justify-center w-6 h-6 rounded-md bg-sky-100 text-sky-600 text-base leading-none" aria-hidden="true">💳</span>
                            <span class="text-[9px] font-semibold text-slate-700">Card</span>
                        </button>
                        <button type="button" class="pos-tender-type-btn flex flex-col items-center justify-center gap-0.5 rounded-md border-2 border-slate-200 bg-white px-1 py-1.5 text-center transition-all duration-200 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:ring-offset-0 data-[selected=true]:border-sky-400 data-[selected=true]:bg-sky-50" data-type="ewallet" title="GCash, Maya, etc.">
                            <span class="flex items-center justify-center w-6 h-6 rounded-md bg-violet-100 text-violet-600 text-[10px] font-bold leading-none" aria-hidden="true">G/M</span>
                            <span class="text-[9px] font-semibold text-slate-700">GCash / Maya</span>
                        </button>
                        <button type="button" class="pos-tender-type-btn flex flex-col items-center justify-center gap-0.5 rounded-md border-2 border-slate-200 bg-white px-1 py-1.5 text-center transition-all duration-200 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:ring-offset-0 data-[selected=true]:border-sky-400 data-[selected=true]:bg-sky-50" data-type="split" title="Split payment across methods">
                            <span class="flex items-center justify-center w-6 h-6 rounded-md bg-amber-100 text-amber-600 text-base leading-none" aria-hidden="true">🔀</span>
                            <span class="text-[9px] font-semibold text-slate-700">Split</span>
                        </button>
                    </div>
                    <p id="pos-payment-split-hint" class="hidden mt-1 text-[10px] text-slate-500">Split amount per method at checkout.</p>
                </div>
                <div class="space-y-2 hidden">
                    <label for="pos-amount-received" class="text-xs font-medium text-slate-600">Amount received (₱)</label>
                    <input type="tel" inputmode="decimal" id="pos-amount-received" class="w-full rounded-xl border border-slate-200 bg-slate-50/70 px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition @if(config('pos.touchscreen', false)) js-kioskboard-input @endif" placeholder="Enter amount received" autocomplete="off" @if(config('pos.touchscreen', false)) data-kioskboard-type="numpad" data-kioskboard-placement="bottom" @endif>
                    <div class="flex items-center justify-between rounded-xl bg-slate-100 px-3 py-2.5">
                        <span class="text-sm font-medium text-slate-600">Change</span>
                        <span id="pos-change-amount" class="text-lg font-bold text-slate-800">₱0.00</span>
                    </div>
                </div>
                <button type="button" id="pos-complete-sale-btn" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-primary px-4 py-3 text-sm font-bold text-white shadow-md hover:bg-primary/90 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:shadow-md" title="Proceed to checkout">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    <span id="pos-complete-sale-label">Checkout</span>
                </button>
                <p class="text-[10px] text-slate-400">OR and BIR details generated after checkout.</p>
            </div>
        </div>
    </div>
    </div>{{-- /pos-main-content --}}
    </div>{{-- /max-w-[1024px] centered --}}

    {{-- Line void manager approval modal (custom, not Swal) --}}
    <div id="pos-void-modal" class="fixed inset-0 z-[100] hidden" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="pos-void-modal-title">
        <div id="pos-void-modal-backdrop" class="absolute inset-0 bg-slate-900/50 dark:bg-slate-900/70 backdrop-blur-sm transition-opacity"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="relative w-full max-w-md rounded-2xl border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-xl" role="document">
                <div class="p-6 sm:p-6">
                    <h2 id="pos-void-modal-title" class="text-lg font-semibold text-slate-800 dark:text-slate-100">Void line?</h2>
                    <p id="pos-void-modal-desc" class="mt-1 text-sm text-slate-600 dark:text-slate-300">
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
                                class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition @if(config('pos.touchscreen', false)) js-kioskboard-input @endif"
                                @if(config('pos.touchscreen', false)) data-kioskboard-type="all" data-kioskboard-placement="bottom" @endif
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
                            <input type="text" id="pos-sc-pwd-id" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition @if(config('pos.touchscreen', false)) js-kioskboard-input @endif" @if(config('pos.touchscreen', false)) data-kioskboard-type="all" data-kioskboard-placement="bottom" @endif placeholder="e.g. 1234-5678-9012" maxlength="50" autocomplete="off">
                            <p id="pos-sc-pwd-id-error" class="mt-1 text-xs text-rose-600 dark:text-rose-400 hidden">Please enter SC/PWD ID number.</p>
                        </div>
                        <div>
                            <label for="pos-sc-pwd-name" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1.5">Customer name <span class="text-slate-400 font-normal">(optional)</span></label>
                            <input type="text" id="pos-sc-pwd-name" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition @if(config('pos.touchscreen', false)) js-kioskboard-input @endif" @if(config('pos.touchscreen', false)) data-kioskboard-type="all" data-kioskboard-placement="bottom" @endif placeholder="Full name" maxlength="255" autocomplete="off">
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
        <div class="absolute inset-0 flex items-center justify-center p-4 overflow-y-auto">
            <div class="relative w-full max-w-md rounded-2xl border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-xl my-4 max-h-[calc(100vh-2rem)] flex flex-col overflow-hidden" role="document">
                <div class="flex-1 min-h-0 overflow-y-auto p-6 sm:p-6">
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
                                class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition @if(config('pos.touchscreen', false)) js-kioskboard-input @endif"
                                @if(config('pos.touchscreen', false)) data-kioskboard-type="numpad" data-kioskboard-placement="bottom" @endif
                                placeholder="Enter amount received"
                                autocomplete="off"
                            >
                            <p id="pos-payment-modal-error" class="mt-1 text-xs text-rose-600 dark:text-rose-400 hidden"></p>
                            {{-- Touch-friendly quick amount + keypad (hidden when POS touchscreen: KioskBoard is used instead) --}}
                            <div id="pos-payment-custom-keypad" class="mt-3 space-y-3 @if(config('pos.touchscreen', false)) hidden @endif">
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" class="pos-payment-quick-amount flex-1 min-w-[100px] rounded-lg border border-primary bg-primary/10 px-3 py-2.5 text-sm font-semibold text-primary hover:bg-primary/20 active:scale-95 transition touch-manipulation" data-action="exact">Exact amount</button>
                                    <button type="button" class="pos-payment-quick-amount rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-darkmode-600 active:scale-95 transition touch-manipulation" data-action="100">+₱100</button>
                                    <button type="button" class="pos-payment-quick-amount rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-darkmode-600 active:scale-95 transition touch-manipulation" data-action="500">+₱500</button>
                                    <button type="button" class="pos-payment-quick-amount rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-darkmode-600 active:scale-95 transition touch-manipulation" data-action="1000">+₱1000</button>
                                </div>
                                <div class="grid grid-cols-4 gap-1.5">
                                    <button type="button" class="pos-payment-numkey h-12 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-100 text-lg font-medium hover:bg-slate-100 dark:hover:bg-darkmode-600 active:scale-95 transition touch-manipulation select-none" data-key="7">7</button>
                                    <button type="button" class="pos-payment-numkey h-12 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-100 text-lg font-medium hover:bg-slate-100 dark:hover:bg-darkmode-600 active:scale-95 transition touch-manipulation select-none" data-key="8">8</button>
                                    <button type="button" class="pos-payment-numkey h-12 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-100 text-lg font-medium hover:bg-slate-100 dark:hover:bg-darkmode-600 active:scale-95 transition touch-manipulation select-none" data-key="9">9</button>
                                    <button type="button" class="pos-payment-numkey h-12 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-slate-100 dark:bg-darkmode-600 text-slate-600 dark:text-slate-300 text-sm font-medium hover:bg-slate-200 dark:hover:bg-darkmode-500 active:scale-95 transition touch-manipulation select-none" data-key="back">⌫</button>
                                    <button type="button" class="pos-payment-numkey h-12 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-100 text-lg font-medium hover:bg-slate-100 dark:hover:bg-darkmode-600 active:scale-95 transition touch-manipulation select-none" data-key="4">4</button>
                                    <button type="button" class="pos-payment-numkey h-12 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-100 text-lg font-medium hover:bg-slate-100 dark:hover:bg-darkmode-600 active:scale-95 transition touch-manipulation select-none" data-key="5">5</button>
                                    <button type="button" class="pos-payment-numkey h-12 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-100 text-lg font-medium hover:bg-slate-100 dark:hover:bg-darkmode-600 active:scale-95 transition touch-manipulation select-none" data-key="6">6</button>
                                    <button type="button" class="pos-payment-numkey h-12 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-slate-100 dark:bg-darkmode-600 text-slate-600 dark:text-slate-300 text-sm font-medium hover:bg-slate-200 dark:hover:bg-darkmode-500 active:scale-95 transition touch-manipulation select-none" data-key="clear">C</button>
                                    <button type="button" class="pos-payment-numkey h-12 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-100 text-lg font-medium hover:bg-slate-100 dark:hover:bg-darkmode-600 active:scale-95 transition touch-manipulation select-none" data-key="1">1</button>
                                    <button type="button" class="pos-payment-numkey h-12 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-100 text-lg font-medium hover:bg-slate-100 dark:hover:bg-darkmode-600 active:scale-95 transition touch-manipulation select-none" data-key="2">2</button>
                                    <button type="button" class="pos-payment-numkey h-12 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-100 text-lg font-medium hover:bg-slate-100 dark:hover:bg-darkmode-600 active:scale-95 transition touch-manipulation select-none" data-key="3">3</button>
                                    <button type="button" class="pos-payment-numkey h-12 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-100 text-lg font-medium hover:bg-slate-100 dark:hover:bg-darkmode-600 active:scale-95 transition touch-manipulation select-none" data-key=".">.</button>
                                    <button type="button" class="pos-payment-numkey h-12 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-100 text-lg font-medium hover:bg-slate-100 dark:hover:bg-darkmode-600 active:scale-95 transition touch-manipulation select-none col-span-2" data-key="0">0</button>
                                    <button type="button" class="pos-payment-numkey h-12 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-100 text-sm font-medium hover:bg-slate-100 dark:hover:bg-darkmode-600 active:scale-95 transition touch-manipulation select-none" data-key="00">00</button>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 dark:bg-darkmode-700 px-3 py-2.5">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Change</span>
                            <span id="pos-payment-modal-change" class="text-lg font-bold text-slate-800 dark:text-slate-100">₱0.00</span>
                        </div>
                        <div id="pos-payment-modal-card-ewallet-fields" class="hidden space-y-3 pt-2 border-t border-slate-200 dark:border-darkmode-600">
                            <div>
                                <label for="pos-payment-modal-reference" class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">Reference / Approval no.</label>
                                <input type="text" id="pos-payment-modal-reference" maxlength="100" autocomplete="off" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition @if(config('pos.touchscreen', false)) js-kioskboard-input @endif" @if(config('pos.touchscreen', false)) data-kioskboard-type="all" data-kioskboard-placement="bottom" @endif placeholder="e.g. 123456789">
                            </div>
                            <div>
                                <label for="pos-payment-modal-provider" class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">Provider</label>
                                <input type="text" id="pos-payment-modal-provider" maxlength="100" autocomplete="off" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition @if(config('pos.touchscreen', false)) js-kioskboard-input @endif" @if(config('pos.touchscreen', false)) data-kioskboard-type="all" data-kioskboard-placement="bottom" @endif placeholder="e.g. GCash, Maya, Visa, Mastercard">
                            </div>
                        </div>
                        <div class="space-y-3 pt-2 border-t border-slate-200 dark:border-darkmode-600">
                            <p class="text-[10px] font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Customer (optional)</p>
                            <div>
                                <label for="pos-payment-modal-customer-name" class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1">Name or ID</label>
                                <input type="text" id="pos-payment-modal-customer-name" maxlength="255" autocomplete="off" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition @if(config('pos.touchscreen', false)) js-kioskboard-input @endif" @if(config('pos.touchscreen', false)) data-kioskboard-type="all" data-kioskboard-placement="bottom" @endif placeholder="Customer name or ID">
                            </div>
                            <div>
                                <label for="pos-payment-modal-customer-address" class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1">Address</label>
                                <input type="text" id="pos-payment-modal-customer-address" maxlength="500" autocomplete="nope" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition @if(config('pos.touchscreen', false)) js-kioskboard-input @endif" @if(config('pos.touchscreen', false)) data-kioskboard-type="all" data-kioskboard-placement="bottom" @endif placeholder="Customer address">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0 flex flex-wrap items-center justify-end gap-2 px-6 py-4 border-t border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800">
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

    {{-- Split payment modal --}}
    <div id="pos-split-payment-modal" class="fixed inset-0 z-[100] hidden" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="pos-split-payment-modal-title">
        <div id="pos-split-payment-modal-backdrop" class="absolute inset-0 bg-slate-900/50 dark:bg-slate-900/70 backdrop-blur-sm transition-opacity"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4 overflow-y-auto">
            <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-xl my-4" role="document">
                <div class="p-6 sm:p-6">
                    <h2 id="pos-split-payment-modal-title" class="text-lg font-semibold text-slate-800 dark:text-slate-100">Split payment</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                        Total due: <span id="pos-split-payment-total" class="font-semibold">₱0.00</span>
                    </p>
                    <div class="mt-4 space-y-3">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="text-xs font-medium text-slate-600 dark:text-slate-300">Payment breakdown</span>
                            <div class="flex items-center gap-2">
                                <button type="button" id="pos-split-quick-50-50-cash-card" class="rounded border border-slate-200 dark:border-darkmode-500 px-2 py-1 text-[10px] font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-darkmode-600">50% Cash, 50% Card</button>
                                <button type="button" id="pos-split-quick-50-50-cash-ewallet" class="rounded border border-slate-200 dark:border-darkmode-500 px-2 py-1 text-[10px] font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-darkmode-600">50% Cash, 50% E-wallet</button>
                            </div>
                        </div>
                        <div id="pos-split-payment-rows" class="space-y-3">
                            <!-- Rows added by JS -->
                        </div>
                        <button type="button" id="pos-split-add-row" class="w-full rounded-lg border border-dashed border-slate-300 dark:border-darkmode-500 py-2 text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-darkmode-700 hover:border-primary/50 transition-colors">
                            + Add payment
                        </button>
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 dark:bg-darkmode-700 px-3 py-2.5 border border-slate-200 dark:border-darkmode-600">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Total entered</span>
                            <span id="pos-split-total-entered" class="text-lg font-bold text-slate-800 dark:text-slate-100">₱0.00</span>
                        </div>
                        <p id="pos-split-payment-error" class="text-xs text-rose-600 dark:text-rose-400 hidden"></p>
                        <div class="space-y-3 pt-2 border-t border-slate-200 dark:border-darkmode-600">
                            <p class="text-[10px] font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Customer (optional)</p>
                            <div>
                                <label for="pos-split-modal-customer-name" class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1">Name or ID</label>
                                <input type="text" id="pos-split-modal-customer-name" maxlength="255" autocomplete="off" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition @if(config('pos.touchscreen', false)) js-kioskboard-input @endif" placeholder="Customer name or ID" @if(config('pos.touchscreen', false)) data-kioskboard-type="all" data-kioskboard-placement="bottom" @endif>
                            </div>
                            <div>
                                <label for="pos-split-modal-customer-address" class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1">Address</label>
                                <input type="text" id="pos-split-modal-customer-address" maxlength="500" autocomplete="nope" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition @if(config('pos.touchscreen', false)) js-kioskboard-input @endif" placeholder="Customer address" @if(config('pos.touchscreen', false)) data-kioskboard-type="all" data-kioskboard-placement="bottom" @endif>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-wrap items-center justify-end gap-2">
                        <button type="button" id="pos-split-payment-cancel" class="rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors">
                            Cancel
                        </button>
                        <button type="button" id="pos-split-payment-apply" class="rounded-lg bg-primary hover:bg-primary/90 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors focus:ring-4 focus:ring-primary/30">
                            Apply split
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sales history modal (max-w-5xl, toolbar, filters, sortable table, summary, pagination, detail panel) --}}
    <div id="pos-sales-history-modal" class="fixed inset-0 z-[100] hidden" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="pos-sales-history-modal-title">
        <div id="pos-sales-history-modal-backdrop" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4 overflow-y-auto">
            <div class="relative w-full max-w-5xl rounded-2xl border border-slate-200 bg-white shadow-2xl my-4 flex flex-col max-h-[90vh]" role="document">
                <div class="p-4 sm:p-5 border-b border-slate-200 flex-shrink-0 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 id="pos-sales-history-modal-title" class="text-lg font-semibold text-slate-800">Sales history</h2>
                        <p class="mt-0.5 text-xs text-slate-500">Recent transactions. Click a row for details; Void or Reprint from actions.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <button type="button" id="pos-sales-history-export-btn" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                Export
                            </button>
                            <div id="pos-sales-history-export-dropdown" class="hidden absolute right-0 top-full mt-1 z-20 min-w-[140px] rounded-lg border border-slate-200 bg-white shadow-lg py-1">
                                <button type="button" class="pos-sales-export-option w-full text-left px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50" data-format="csv">Export as CSV</button>
                                <button type="button" class="pos-sales-export-option w-full text-left px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50" data-format="pdf">Export as PDF</button>
                            </div>
                        </div>
                        <button type="button" id="pos-sales-history-close" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">Close</button>
                    </div>
                </div>
                {{-- Toolbar: search, date range, status, clear --}}
                <div class="px-4 sm:px-5 py-3 border-b border-slate-100 flex-shrink-0 flex flex-wrap items-center gap-2 sm:gap-3">
                    <input type="text" id="pos-sales-history-search" placeholder="Search OR # or amount…" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition w-full sm:w-48 @if(config('pos.touchscreen', false)) js-kioskboard-input @endif" @if(config('pos.touchscreen', false)) data-kioskboard-type="all" data-kioskboard-placement="bottom" @endif>
                    <div class="flex items-center gap-1.5">
                        <label for="pos-sales-history-date-from" class="text-xs text-slate-500 whitespace-nowrap">From</label>
                        <input type="date" id="pos-sales-history-date-from" class="rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-xs text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition @if(config('pos.touchscreen', false)) js-kioskboard-input @endif" @if(config('pos.touchscreen', false)) data-kioskboard-type="all" data-kioskboard-placement="bottom" @endif>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <label for="pos-sales-history-date-to" class="text-xs text-slate-500 whitespace-nowrap">To</label>
                        <input type="date" id="pos-sales-history-date-to" class="rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-xs text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition @if(config('pos.touchscreen', false)) js-kioskboard-input @endif" @if(config('pos.touchscreen', false)) data-kioskboard-type="all" data-kioskboard-placement="bottom" @endif>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <label for="pos-sales-history-status" class="text-xs text-slate-500 whitespace-nowrap">Status</label>
                        <select id="pos-sales-history-status" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition min-w-[120px]" aria-label="Filter by status">
                            <option value="all">All status</option>
                            <option value="completed">Completed</option>
                            <option value="voided">Voided</option>
                            <option value="pending">Pending</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                    <button type="button" id="pos-sales-history-apply-filters" class="rounded-lg bg-primary px-3 py-2 text-xs font-medium text-white hover:bg-primary/90 transition-colors">Apply</button>
                    <button type="button" id="pos-sales-history-clear-filters" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Clear filters</button>
                </div>
                <div class="flex-1 min-h-0 flex overflow-hidden">
                    <div class="flex-1 min-w-0 flex flex-col overflow-hidden">
                        <div id="pos-sales-history-loading" class="hidden py-12 text-center text-sm text-slate-500">Loading…</div>
                        <div id="pos-sales-history-empty" class="hidden py-12 text-center">
                            <p class="text-sm text-slate-500">No transactions found. Try adjusting your filters.</p>
                        </div>
                        <div id="pos-sales-history-table-wrap" class="flex-1 overflow-auto px-4 sm:px-5 hidden">
                            <table class="w-full text-left text-xs sm:text-sm border-collapse" id="pos-sales-history-table">
                                <thead class="bg-slate-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="pos-sales-th-or px-3 py-2.5 font-semibold text-slate-700 bg-slate-50 sticky left-0 z-20 min-w-[100px] cursor-pointer hover:bg-slate-100 transition-colors" data-sort="or_number">OR # <span class="pos-sales-sort-icon inline-block ml-0.5 opacity-50">↕</span></th>
                                        <th class="pos-sales-th-date px-3 py-2.5 font-semibold text-slate-700 cursor-pointer hover:bg-slate-100 transition-colors" data-sort="created_at">Date / Time <span class="pos-sales-sort-icon inline-block ml-0.5 opacity-50">↕</span></th>
                                        <th class="pos-sales-th-total px-3 py-2.5 font-semibold text-slate-700 text-right cursor-pointer hover:bg-slate-100 transition-colors" data-sort="total">Total <span class="pos-sales-sort-icon inline-block ml-0.5 opacity-50">↕</span></th>
                                        <th class="px-3 py-2.5 font-semibold text-slate-700">Payment</th>
                                        <th class="px-3 py-2.5 font-semibold text-slate-700">Cashier</th>
                                        <th class="pos-sales-th-status px-3 py-2.5 font-semibold text-slate-700 cursor-pointer hover:bg-slate-100 transition-colors" data-sort="status">Status <span class="pos-sales-sort-icon inline-block ml-0.5 opacity-50">↕</span></th>
                                        <th class="px-3 py-2.5 font-semibold text-slate-700 text-right bg-slate-50 sticky right-0 z-20 min-w-[120px]">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="pos-sales-history-tbody" class="divide-y divide-slate-200 bg-white">
                                </tbody>
                            </table>
                        </div>
                        {{-- Summary footer --}}
                        <div id="pos-sales-history-summary" class="hidden flex-shrink-0 px-4 sm:px-5 py-2 border-t border-slate-200 bg-slate-50 text-xs text-slate-600 flex flex-wrap items-center gap-x-6 gap-y-1">
                            <span id="pos-sales-history-summary-count">0 transactions</span>
                            <span id="pos-sales-history-summary-total">Total sales: ₱0.00</span>
                            <span id="pos-sales-history-summary-voided">Voided: 0 (₱0.00)</span>
                        </div>
                        {{-- Pagination --}}
                        <div id="pos-sales-history-pagination" class="hidden flex-shrink-0 px-4 sm:px-5 py-3 border-t border-slate-200 flex flex-wrap items-center justify-between gap-2">
                            <p id="pos-sales-history-record-count" class="text-xs text-slate-500">Showing 0–0 of 0</p>
                            <div class="flex items-center gap-2">
                                <label for="pos-sales-history-per-page" class="text-xs text-slate-500">Per page</label>
                                <select id="pos-sales-history-per-page" class="rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 focus:ring-1 focus:ring-primary/20 outline-none transition">
                                    <option value="10">10</option>
                                    <option value="25" selected>25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <div class="flex items-center gap-0.5">
                                    <button type="button" id="pos-sales-history-prev" class="rounded border border-slate-200 bg-white px-2 py-1 text-xs font-medium text-slate-700 hover:bg-slate-50 disabled:opacity-50 disabled:pointer-events-none" disabled>Previous</button>
                                    <button type="button" id="pos-sales-history-next" class="rounded border border-slate-200 bg-white px-2 py-1 text-xs font-medium text-slate-700 hover:bg-slate-50 disabled:opacity-50 disabled:pointer-events-none" disabled>Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Transaction detail side panel: professional, modern, clean --}}
                    <div id="pos-sales-history-detail-panel" class="hidden w-0 sm:w-80 flex-shrink-0 border-l border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 overflow-y-auto">
                        <div class="sticky top-0 z-10 flex items-center justify-between gap-2 px-4 py-3 border-b border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800">
                            <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">Transaction details</h3>
                            <button type="button" id="pos-sales-history-detail-close" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-darkmode-600 dark:hover:text-slate-300 transition-colors touch-manipulation" aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div id="pos-sales-history-detail-content" class="p-4 space-y-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Void confirmation modal (reason required) --}}
    <div id="pos-sales-void-modal" class="fixed inset-0 z-[110] hidden" aria-hidden="true" role="dialog">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" id="pos-sales-void-modal-backdrop"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="relative w-full max-w-md rounded-xl border border-slate-200 bg-white shadow-xl p-5">
                <h3 class="text-lg font-semibold text-slate-800">Void transaction</h3>
                <p class="mt-1 text-sm text-slate-600">OR #<span id="pos-sales-void-or"></span> will be voided. This cannot be undone.</p>
                <div class="mt-4">
                    <label for="pos-sales-void-reason" class="block text-xs font-medium text-slate-600 mb-1">Reason for void <span class="text-rose-500">*</span></label>
                    <input type="text" id="pos-sales-void-reason" placeholder="e.g. Wrong entry, customer cancelled" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition @if(config('pos.touchscreen', false)) js-kioskboard-input @endif" @if(config('pos.touchscreen', false)) data-kioskboard-type="all" data-kioskboard-placement="bottom" @endif maxlength="255">
                    <p id="pos-sales-void-reason-error" class="mt-1 text-xs text-rose-600 hidden">Please enter a reason.</p>
                </div>
                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" id="pos-sales-void-cancel" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</button>
                    <button type="button" id="pos-sales-void-confirm" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">Confirm void</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Reprint preview modal (Official / Duplicate) --}}
    <div id="pos-sales-reprint-preview-modal" class="fixed inset-0 z-[110] hidden" aria-hidden="true" role="dialog">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" id="pos-sales-reprint-preview-backdrop"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="relative w-full max-w-md rounded-xl border border-slate-200 bg-white shadow-xl p-5">
                <h3 class="text-lg font-semibold text-slate-800">Reprint receipt</h3>
                <p class="mt-1 text-sm text-slate-600">Choose copy type and then print.</p>
                <div class="mt-4 space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="pos-reprint-copy-type" value="official" class="rounded-full border-slate-300 text-primary focus:ring-primary/20" checked>
                        <span class="text-sm text-slate-700">Official Receipt</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="pos-reprint-copy-type" value="duplicate" class="rounded-full border-slate-300 text-primary focus:ring-primary/20">
                        <span class="text-sm text-slate-700">Duplicate Copy</span>
                    </label>
                </div>
                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" id="pos-sales-reprint-preview-cancel" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</button>
                    <button type="button" id="pos-sales-reprint-preview-print" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary/90">Print</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Reprint: select transaction, show total due, then reprint --}}
    <div id="pos-reprint-modal" class="fixed inset-0 z-[100] hidden" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="pos-reprint-modal-title">
        <div id="pos-reprint-modal-backdrop" class="absolute inset-0 bg-slate-900/50 dark:bg-slate-900/70 backdrop-blur-sm"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="relative w-full max-w-md rounded-2xl border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-xl" role="document">
                <div class="p-6 sm:p-6">
                    <h2 id="pos-reprint-modal-title" class="text-lg font-semibold text-slate-800 dark:text-slate-100">Reprint receipt</h2>
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Select a transaction to view total due and reprint.</p>
                    <div class="mt-4 space-y-3">
                        <div>
                            <label for="pos-reprint-select" class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">Transaction (OR number)</label>
                            <select id="pos-reprint-select" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition">
                                <option value="">— Select transaction —</option>
                            </select>
                        </div>
                        <div id="pos-reprint-total-wrap" class="hidden rounded-xl bg-slate-50 dark:bg-darkmode-700 px-4 py-3 border border-slate-200 dark:border-darkmode-600">
                            <span class="text-xs font-medium text-slate-600 dark:text-slate-300">Total due</span>
                            <span id="pos-reprint-total" class="block text-lg font-bold text-slate-900 dark:text-slate-100 mt-0.5">₱0.00</span>
                        </div>
                        <p id="pos-reprint-loading" class="hidden text-xs text-slate-500">Loading transactions…</p>
                    </div>
                    <div class="mt-6 flex flex-wrap items-center justify-end gap-2">
                        <button type="button" id="pos-reprint-modal-cancel" class="rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors">Cancel</button>
                        <button type="button" id="pos-reprint-do-btn" class="rounded-lg bg-primary hover:bg-primary/90 px-4 py-2 text-sm font-semibold text-white transition-colors disabled:opacity-50 disabled:pointer-events-none" disabled>Reprint</button>
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
                                class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition @if(config('pos.touchscreen', false)) js-kioskboard-input @endif"
                                placeholder="e.g. RX-2024-001"
                                maxlength="100"
                                autocomplete="off"
                                @if(config('pos.touchscreen', false)) data-kioskboard-type="all" data-kioskboard-placement="bottom" @endif
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
                                class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition @if(config('pos.touchscreen', false)) js-kioskboard-input @endif"
                                placeholder="Doctor name"
                                maxlength="120"
                                autocomplete="off"
                                @if(config('pos.touchscreen', false)) data-kioskboard-type="all" data-kioskboard-placement="bottom" @endif
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

    {{-- X-Reading Step 1: Manager PIN only --}}
    <div id="pos-x-reading-pin-modal" class="fixed inset-0 z-[100] hidden" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="pos-x-reading-pin-modal-title">
        <div id="pos-x-reading-pin-modal-backdrop" class="absolute inset-0 bg-slate-900/50 dark:bg-slate-900/70 backdrop-blur-sm transition-opacity"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="relative w-full max-w-md rounded-2xl border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-xl" role="document">
                <div class="p-6 sm:p-6">
                    <h2 id="pos-x-reading-pin-modal-title" class="text-lg font-semibold text-slate-800 dark:text-slate-100">Generate X-Reading</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                        Enter manager PIN or password for this branch.
                    </p>
                    <div class="mt-4 space-y-2">
                        <div>
                            <label for="pos-x-reading-pin-input" class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">
                                Manager PIN or password
                            </label>
                            <input
                                type="password"
                                id="pos-x-reading-pin-input"
                                class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-3 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition @if(config('pos.touchscreen', false)) js-kioskboard-input @endif"
                                @if(config('pos.touchscreen', false)) data-kioskboard-type="all" data-kioskboard-placement="bottom" @endif
                                placeholder="••••••••"
                                autocomplete="off"
                                maxlength="255"
                            >
                            <p id="pos-x-reading-pin-error" class="mt-1 text-xs text-rose-600 dark:text-rose-400 hidden">
                                Enter manager PIN or password.
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-wrap items-center justify-end gap-2">
                        <button type="button" id="pos-x-reading-pin-modal-cancel" class="rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors">
                            Cancel
                        </button>
                        <button type="button" id="pos-x-reading-pin-modal-continue" class="rounded-lg bg-primary hover:bg-primary/90 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors focus:ring-4 focus:ring-primary/30">
                            Continue
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- X-Reading Step 2: Cash Count — Enter quantity per denomination --}}
    <div id="pos-x-reading-cash-modal" class="fixed inset-0 z-[101] hidden" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="pos-x-reading-cash-modal-title">
        <div id="pos-x-reading-cash-modal-backdrop" class="absolute inset-0 bg-slate-900/50 dark:bg-slate-900/70 backdrop-blur-sm transition-opacity"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4 overflow-y-auto">
            <div class="relative w-full max-w-md rounded-2xl border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-xl my-4" role="document">
                <div class="p-6 sm:p-6">
                    <h2 id="pos-x-reading-cash-modal-title" class="text-lg font-semibold text-slate-800 dark:text-slate-100">X-Reading — Cash Count</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                        Enter quantity per denomination from the cash drawer.
                    </p>
                    <div class="mt-4">
                        <p class="text-xs font-medium text-slate-600 dark:text-slate-300 mb-2">CASH COUNT — Enter quantity per denomination</p>
                        <div class="rounded-lg border border-slate-200 dark:border-darkmode-500 bg-slate-50/50 dark:bg-darkmode-700/30 p-3 space-y-1.5">
                            @php
                                $denoms = [
                                    '1000' => '1,000.00',
                                    '500' => '500.00',
                                    '200' => '200.00',
                                    '100' => '100.00',
                                    '50' => '50.00',
                                    '20' => '20.00',
                                    '10' => '10.00',
                                    '5' => '5.00',
                                    '1' => '1.00',
                                    '0.25' => '0.25',
                                    '0.10' => '0.10',
                                    '0.05' => '0.05',
                                    '0.01' => '0.01',
                                ];
                            @endphp
                            @foreach ($denoms as $key => $label)
                                <div class="flex items-center gap-2">
                                    <span class="w-16 text-right text-xs font-medium text-slate-600 dark:text-slate-400 tabular-nums">{{ $label }}</span>
                                    <input type="number" id="pos-x-reading-cash-{{ $key }}" data-denom="{{ $key }}" min="0" step="1" value="0"
                                        class="flex-1 rounded border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-2 py-1.5 text-sm text-slate-900 dark:text-slate-100 tabular-nums focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none"
                                        placeholder="0">
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Total: <span id="pos-x-reading-cash-total" class="font-semibold tabular-nums">0.00</span></p>
                        <p id="pos-x-reading-cash-error" class="mt-1.5 text-xs text-rose-600 dark:text-rose-400 hidden"></p>
                    </div>
                    <div class="mt-6 flex flex-wrap items-center justify-end gap-2">
                        <button type="button" id="pos-x-reading-cash-modal-back" class="rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors">
                            Back
                        </button>
                        <button type="button" id="pos-x-reading-cash-modal-generate" class="rounded-lg bg-primary hover:bg-primary/90 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors focus:ring-4 focus:ring-primary/30">
                            Generate
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- X-Reading modal: mid-day snapshot report --}}
    <div id="pos-x-reading-modal" class="fixed inset-0 z-[100] hidden" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="pos-x-reading-modal-title">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" id="pos-x-reading-modal-backdrop"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4 overflow-y-auto">
            <div class="relative w-full max-w-md rounded-2xl border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-xl" role="document">
                <div class="p-5 sm:p-6">
                    <div class="flex items-center justify-between gap-2">
                        <div>
                            <h2 id="pos-x-reading-modal-title" class="text-lg font-semibold text-slate-800 dark:text-slate-100">X-Reading Report</h2>
                            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Mid-day snapshot · Counters have NOT been reset</p>
                        </div>
                        <button type="button" id="pos-x-reading-modal-close" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 dark:hover:bg-darkmode-600 hover:text-slate-600 transition" aria-label="Close">&times;</button>
                    </div>
                    <div id="pos-x-reading-period" class="mt-3 text-sm text-slate-600 dark:text-slate-300"></div>
                    <div id="pos-x-reading-summary" class="mt-4 space-y-2 rounded-xl border border-slate-200 dark:border-darkmode-600 bg-slate-50/50 dark:bg-darkmode-700/30 p-4 text-sm">
                        <div class="flex justify-between"><span class="text-slate-600 dark:text-slate-400">Transactions</span><span id="pos-x-reading-trans" class="font-semibold tabular-nums">—</span></div>
                        <div class="flex justify-between"><span class="text-slate-600 dark:text-slate-400">Gross Sales</span><span id="pos-x-reading-gross" class="font-semibold tabular-nums">—</span></div>
                        <div class="flex justify-between"><span class="text-slate-600 dark:text-slate-400">Discounts</span><span id="pos-x-reading-disc" class="font-semibold tabular-nums">—</span></div>
                        <div class="flex justify-between border-t border-slate-200 dark:border-darkmode-600 pt-2"><span class="font-medium text-slate-800 dark:text-slate-200">Net Sales</span><span id="pos-x-reading-net" class="font-bold tabular-nums text-primary">—</span></div>
                    </div>
                    <div id="pos-x-reading-payments" class="mt-3 text-xs text-slate-500 dark:text-slate-400 space-y-0.5"></div>
                    <p class="mt-3 text-[11px] text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-900/20 rounded-lg px-3 py-2">Counters are NOT reset after X-Reading. This is not a Z-Report.</p>
                    <div class="mt-5 flex flex-wrap items-center justify-end gap-2">
                        <button type="button" id="pos-x-reading-modal-close-btn" class="rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors">Close</button>
                        <button type="button" id="pos-x-reading-print-btn" class="inline-flex items-center gap-2 rounded-lg bg-primary hover:bg-primary/90 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors focus:ring-4 focus:ring-primary/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h2"/><path d="M18 18h2a2 2 0 0 0 2-2v-5a2 2 0 0 0-2-2h-2"/><path d="M6 14h12v8H6z"/></svg>
                            Print Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Fixed bottom action bar: icon + label + shortcut [ Fn ], with animations (concept: top-border hover) --}}
    <nav id="pos-bottom-bar" class="fixed bottom-0 left-0 right-0 z-40 flex w-full items-center bg-primary px-2 py-2 shadow-[0_-2px_10px_rgba(0,0,0,0.1)] print:hidden transition-shadow duration-300" aria-label="POS actions">
        <div class="flex flex-1 items-center justify-around gap-0.5 min-w-0">
            <button type="button" id="pos-bottom-new-sale" class="pos-bottom-btn flex flex-col items-center justify-center gap-0 rounded-lg px-1.5 py-1 text-white border-t-2 border-transparent hover:bg-white/20 hover:border-t-white/90 transition-all duration-200 ease-out hover:scale-105 active:scale-95 origin-center min-w-0 flex-1 max-w-[72px] sm:max-w-none" title="New sale — start a fresh transaction [ F1 ]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span class="text-[9px] font-medium uppercase tracking-wide truncate w-full text-center leading-tight">New Sale</span>
                <span class="text-[8px] text-white/80 font-mono">[ F1 ]</span>
            </button>
            <button type="button" id="pos-bottom-scan" class="pos-bottom-btn flex flex-col items-center justify-center gap-0 rounded-lg px-1.5 py-1 text-white border-t-2 border-transparent hover:bg-white/20 hover:border-t-white/90 transition-all duration-200 ease-out hover:scale-105 active:scale-95 origin-center min-w-0 flex-1 max-w-[72px] sm:max-w-none" title="Scan — focus barcode scanner or open scanner [ F2 ]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M7 8h10"/><path d="M7 12h6"/><path d="M17 12h.01"/><path d="M7 16h4"/></svg>
                <span class="text-[9px] font-medium uppercase tracking-wide truncate w-full text-center leading-tight">Scan</span>
                <span class="text-[8px] text-white/80 font-mono">[ F2 ]</span>
            </button>
            <button type="button" id="pos-bottom-hold" class="pos-bottom-btn flex flex-col items-center justify-center gap-0 rounded-lg px-1.5 py-1 text-white border-t-2 border-transparent hover:bg-white/20 hover:border-t-white/90 transition-all duration-200 ease-out hover:scale-105 active:scale-95 origin-center min-w-0 flex-1 max-w-[72px] sm:max-w-none" title="Hold — save current order and retrieve later [ F4 ]. Disabled when cart is empty.">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4"/><path d="M12 18v4"/><path d="M4.93 4.93l2.83 2.83"/><path d="M16.24 16.24l2.83 2.83"/><path d="M2 12h4"/><path d="M18 12h4"/><path d="M4.93 19.07l2.83-2.83"/><path d="M16.24 7.76l2.83-2.83"/><circle cx="12" cy="12" r="3"/></svg>
                <span class="text-[9px] font-medium uppercase tracking-wide truncate w-full text-center leading-tight">Hold</span>
                <span class="text-[8px] text-white/80 font-mono">[ F4 ]</span>
            </button>
            <button type="button" id="pos-bottom-discount" class="pos-bottom-btn flex flex-col items-center justify-center gap-0 rounded-lg px-1.5 py-1 text-white border-t-2 border-transparent hover:bg-white/20 hover:border-t-white/90 transition-all duration-200 ease-out hover:scale-105 active:scale-95 origin-center min-w-0 flex-1 max-w-[72px] sm:max-w-none" title="Discount — apply SC/PWD, employee, promo, or manual discount [ F5 ]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                <span class="text-[9px] font-medium uppercase tracking-wide truncate w-full text-center leading-tight">Discount</span>
                <span class="text-[8px] text-white/80 font-mono">[ F5 ]</span>
            </button>
            <button type="button" id="pos-bottom-payment" class="pos-bottom-btn flex flex-col items-center justify-center gap-0 rounded-lg px-1.5 py-1 text-white border-t-2 border-transparent hover:bg-white/20 hover:border-t-white/90 transition-all duration-200 ease-out hover:scale-105 active:scale-95 origin-center min-w-0 flex-1 max-w-[72px] sm:max-w-none" title="Payment — complete sale with cash, card, GCash/Maya, or split [ F8 ]. Disabled when cart is empty.">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                <span class="text-[9px] font-medium uppercase tracking-wide truncate w-full text-center leading-tight">Payment</span>
                <span class="text-[8px] text-white/80 font-mono">[ F8 ]</span>
            </button>
            <button type="button" id="pos-bottom-reprint" class="pos-bottom-btn flex flex-col items-center justify-center gap-0 rounded-lg px-1.5 py-1 text-white border-t-2 border-transparent hover:bg-white/20 hover:border-t-white/90 transition-all duration-200 ease-out hover:scale-105 active:scale-95 origin-center min-w-0 flex-1 max-w-[72px] sm:max-w-none" title="Re-print — print receipt for a previous transaction by reference [ F9 ]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h2"/><path d="M18 18h2a2 2 0 0 0 2-2v-5a2 2 0 0 0-2-2h-2"/><path d="M6 14h12v8H6z"/></svg>
                <span class="text-[9px] font-medium uppercase tracking-wide truncate w-full text-center leading-tight">Re-print</span>
                <span class="text-[8px] text-white/80 font-mono">[ F9 ]</span>
            </button>
            <button type="button" id="pos-bottom-sales" class="pos-bottom-btn flex flex-col items-center justify-center gap-0 rounded-lg px-1.5 py-1 text-white border-t-2 border-transparent hover:bg-white/20 hover:border-t-white/90 transition-all duration-200 ease-out hover:scale-105 active:scale-95 origin-center min-w-0 flex-1 max-w-[72px] sm:max-w-none" title="Sales history — view today’s transactions [ F11 ]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>
                <span class="text-[9px] font-medium uppercase tracking-wide truncate w-full text-center leading-tight">Sales</span>
                <span class="text-[8px] text-white/80 font-mono">[ F11 ]</span>
            </button>
            <button type="button" id="pos-bottom-x-reading" class="pos-bottom-btn flex flex-col items-center justify-center gap-0 rounded-lg px-1.5 py-1 text-white border-t-2 border-transparent hover:bg-white/20 hover:border-t-white/90 transition-all duration-200 ease-out hover:scale-105 active:scale-95 origin-center min-w-0 flex-1 max-w-[72px] sm:max-w-none" title="X-Reading — mid-day snapshot, manager PIN + cash count">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h2"/><path d="M8 17h2"/><path d="M16 13h2"/><path d="M16 17h2"/></svg>
                <span class="text-[9px] font-medium uppercase tracking-wide truncate w-full text-center leading-tight">X-Read</span>
                <span class="text-[8px] text-white/80 font-mono">—</span>
            </button>
            <button type="button" id="pos-bottom-lock" class="pos-bottom-btn flex flex-col items-center justify-center gap-0 rounded-lg px-1.5 py-1 text-white border-t-2 border-transparent hover:bg-white/20 hover:border-t-white/90 transition-all duration-200 ease-out hover:scale-105 active:scale-95 origin-center min-w-0 flex-1 max-w-[72px] sm:max-w-none" title="Lock POS — re-enter PIN to continue [ F10 ]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <span class="text-[9px] font-medium uppercase tracking-wide truncate w-full text-center leading-tight">Lock</span>
                <span class="text-[8px] text-white/80 font-mono">[ F10 ]</span>
            </button>
            <button type="button" id="pos-bottom-logout" class="pos-bottom-btn flex flex-col items-center justify-center gap-0 rounded-lg px-1.5 py-1 text-white border-t-2 border-transparent hover:bg-rose-500/40 hover:border-t-rose-300 transition-all duration-200 ease-out hover:scale-105 active:scale-95 origin-center min-w-0 flex-1 max-w-[72px] sm:max-w-none border-l border-white/20 ml-0.5 pl-1.5" title="Logout — end session">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <span class="text-[9px] font-medium uppercase tracking-wide truncate w-full text-center leading-tight">Logout</span>
                <span class="text-[8px] text-white/80 font-mono">—</span>
            </button>
            <button type="button" id="pos-bottom-minimal-toggle" class="pos-bottom-btn flex flex-col items-center justify-center gap-0 rounded-lg px-1 py-1 text-white/80 border-l border-white/20 ml-0.5 hover:bg-white/15 hover:text-white transition-all max-w-[44px]" title="Hide toolbar — use F1–F11. Click tab at bottom to show again." aria-label="Minimal mode">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                <span class="text-[8px]">Hide</span>
            </button>
        </div>
    </nav>
    <button type="button" id="pos-bottom-show-bar" class="fixed left-1/2 z-50 flex items-center gap-1.5 -translate-x-1/2 py-2.5 px-4 rounded-t-xl bg-slate-800 text-white text-sm font-semibold shadow-lg hover:bg-slate-700 active:bg-slate-600 transition print:hidden border border-b-0 border-slate-600" style="bottom: 0; display: none;" title="Click to show toolbar">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 15l7-7 7 7"/></svg>
        <span>Show toolbar</span>
    </button>
    </div>
@endsection

@if(config('pos.touchscreen', false))
@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/furcan/KioskBoard@2.3.0/dist/kioskboard-aio-2.3.0.min.js"></script>
@endpush
@endif

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

    var POS_TOUCHSCREEN = {{ json_encode(config('pos.touchscreen', false)) }};

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
    var lastXReadingData = null;
    var birDisplay = { tin: '', ptu_number: '', footer_text: 'This document is not valid for claim of input tax.' };
    var STOCK_LOW = 10;
    var STOCK_CRITICAL = 2;
    var pendingVoidProductId = null;
    var PENDING_CLEAR_ORDER = 'CLEAR';
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
                cartItems = state.cartItems.map(function (item) {
                    return Object.assign({ notes: '' }, item);
                });
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
    function getCategoryBorderClass(cat) {
        if (!cat) return 'pos-cat-default';
        var n = (cat.name || '').toLowerCase();
        var t = (cat.type || '').toLowerCase();
        if (t === 'rx' || n.indexOf('rx') >= 0 || n.indexOf('prescription') >= 0) return 'pos-cat-rx';
        if (t === 'otc' || n.indexOf('otc') >= 0) return 'pos-cat-otc';
        if (n.indexOf('vitamin') >= 0) return 'pos-cat-vitamin';
        if (n.indexOf('supplies') >= 0 || n.indexOf('first aid') >= 0) return 'pos-cat-supplies';
        return 'pos-cat-default';
    }
    function getCategoryIconSvg(cat) {
        var n = (cat && cat.name || '').toLowerCase();
        var t = (cat && cat.type || '').toLowerCase();
        if (t === 'rx' || n.indexOf('rx') >= 0 || n.indexOf('prescription') >= 0) return '<svg class="h-4 w-4 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M4.93 4.93l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83M12 18v4"/><circle cx="12" cy="12" r="3"/></svg>';
        if (n.indexOf('vitamin') >= 0) return '<svg class="h-4 w-4 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v12M6 12h12"/></svg>';
        return '<svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M9 9h6v6H9z"/></svg>';
    }
    var productsPerPage = 9;
    var productsCurrentPage = 1;

    // Header: real-time date/time (e.g. Sat, Feb 28, 2026, 1:39 PM)
    var dateLabel = document.getElementById('pos-date-label');
    var dateTimeOptions = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' };
    function updatePosDateTime() {
        if (dateLabel) dateLabel.textContent = new Date().toLocaleString('en-PH', dateTimeOptions);
    }
    if (dateLabel) {
        updatePosDateTime();
        setInterval(updatePosDateTime, 1000);
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

            var companyLabel = document.getElementById('pos-company-label');
            if (companyLabel) {
                var companyName = company && company.name ? String(company.name).trim() : '';
                companyLabel.textContent = companyName ? ' · ' + companyName : '';
            }
            var branchLabel = document.getElementById('pos-branch-label');
            if (branchLabel) {
                var branchName = branch && branch.name ? branch.name : '';
                branchLabel.textContent = branchName || '—';
            }
        })
        .catch(function () {
            // Leave defaults if /auth/me fails
        });

    // Init floating menu and fixed bottom bar
    initFloatingMenu();
    initBottomBar();

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
                    var text = code;
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
                setNotReadyStatusChip('active');
                setConnectionStatus(true);
                initShiftTimer();
                setTimeout(function () { showPosMainContent(); }, 450);
            })
            .catch(function (err) {
                setConnectionStatus(false);
                var msg = err.response && err.response.data && err.response.data.message
                    ? err.response.data.message
                    : 'POS is not registered or this terminal is inactive. Contact the software provider to register this POS.';
                showPosNotReady(msg);
                setRetryButtonLoading(false);
                var completeBtn = document.getElementById('pos-complete-sale-btn');
                if (completeBtn) completeBtn.disabled = true;
            });
    }

    function setNotReadyStatusChip(state) {
        var chip = document.getElementById('pos-not-ready-status-chip');
        var label = document.getElementById('pos-not-ready-status-label');
        var dot = document.getElementById('pos-not-ready-status-dot');
        if (!chip || !label || !dot) return;
        chip.classList.remove('bg-red-50', 'dark:bg-red-900/20', 'text-red-700', 'dark:text-red-300', 'bg-slate-100', 'dark:bg-slate-700/50', 'text-slate-600', 'dark:text-slate-400', 'bg-emerald-50', 'dark:bg-emerald-900/20', 'text-emerald-700', 'dark:text-emerald-300');
        dot.classList.remove('pos-not-ready-status-dot', 'bg-red-500', 'bg-slate-500', 'bg-emerald-500');
        if (state === 'checking') {
            chip.classList.add('bg-slate-100', 'dark:bg-slate-700/50', 'text-slate-600', 'dark:text-slate-400');
            dot.classList.add('bg-slate-500');
            label.textContent = 'Checking…';
        } else if (state === 'active') {
            chip.classList.add('bg-emerald-50', 'dark:bg-emerald-900/20', 'text-emerald-700', 'dark:text-emerald-300');
            dot.classList.add('bg-emerald-500');
            label.textContent = 'Active';
        } else {
            chip.classList.add('bg-red-50', 'dark:bg-red-900/20', 'text-red-700', 'dark:text-red-300');
            dot.classList.add('pos-not-ready-status-dot', 'bg-red-500');
            label.textContent = 'Inactive';
        }
    }

    function setConnectionStatus(online) {
        var dot = document.getElementById('pos-connection-dot');
        if (!dot) return;
        dot.classList.remove('bg-emerald-400', 'bg-amber-400');
        dot.classList.remove('shadow-[0_0_6px_rgba(52,211,153,0.8)]', 'shadow-[0_0_6px_rgba(251,191,36,0.8)]');
        if (online) {
            dot.classList.add('bg-emerald-400', 'shadow-[0_0_6px_rgba(52,211,153,0.8)]');
            dot.setAttribute('title', 'Online');
        } else {
            dot.classList.add('bg-amber-400', 'shadow-[0_0_6px_rgba(251,191,36,0.8)]');
            dot.setAttribute('title', 'Local only');
        }
    }

    var shiftStartKey = 'pos_shift_start';
    function initShiftTimer() {
        if (!sessionStorage.getItem(shiftStartKey)) sessionStorage.setItem(shiftStartKey, String(Date.now()));
        function updateShiftTimer() {
            var el = document.getElementById('pos-shift-timer');
            if (!el) return;
            var start = parseInt(sessionStorage.getItem(shiftStartKey), 10);
            if (isNaN(start)) return;
            var mins = Math.floor((Date.now() - start) / 60000);
            var h = Math.floor(mins / 60);
            var m = mins % 60;
            el.textContent = h > 0 ? 'Shift: ' + h + 'h ' + m + 'm' : 'Shift: ' + m + 'm';
        }
        updateShiftTimer();
        setInterval(updateShiftTimer, 60000);
    }

    function setRetryButtonLoading(loading) {
        var btn = document.getElementById('pos-not-ready-retry-btn');
        var icon = document.getElementById('pos-not-ready-retry-icon');
        var spinner = document.getElementById('pos-not-ready-retry-spinner');
        var label = document.getElementById('pos-not-ready-retry-label');
        if (!btn) return;
        if (loading) {
            btn.disabled = true;
            if (label) label.textContent = 'Checking…';
            if (icon) icon.classList.add('hidden');
            if (spinner) spinner.classList.remove('hidden');
        } else {
            btn.disabled = false;
            if (label) label.textContent = 'Retry';
            if (icon) icon.classList.remove('hidden');
            if (spinner) spinner.classList.add('hidden');
        }
    }

    function showPosNotReady(message) {
        var container = document.getElementById('pos-not-ready-container');
        var mainContent = document.getElementById('pos-main-content');
        var bottomBar = document.getElementById('pos-bottom-bar');
        var line1 = document.getElementById('pos-not-ready-line1');
        var line2 = document.getElementById('pos-not-ready-line2');
        var lastChecked = document.getElementById('pos-not-ready-last-checked');
        var defaultMsg = 'POS is not registered or this terminal is inactive. Contact the software provider to register this POS.';
        var msg = message || defaultMsg;
        var firstPeriod = msg.indexOf('.');
        if (line1) line1.textContent = firstPeriod > 0 ? msg.substring(0, firstPeriod + 1).trim() : msg;
        if (line2) line2.textContent = firstPeriod > 0 ? msg.substring(firstPeriod + 1).trim() : 'Contact the software provider to register this POS.';
        setNotReadyStatusChip('inactive');
        if (lastChecked) lastChecked.textContent = 'just now';
        setRetryButtonLoading(false);
        if (container) container.classList.remove('hidden');
        if (mainContent) mainContent.classList.add('hidden');
        if (bottomBar) bottomBar.classList.add('hidden');
    }

    function showPosMainContent() {
        var container = document.getElementById('pos-not-ready-container');
        var mainContent = document.getElementById('pos-main-content');
        if (container) container.classList.add('hidden');
        if (mainContent) mainContent.classList.remove('hidden');
        var bottomBar = document.getElementById('pos-bottom-bar');
        if (bottomBar) bottomBar.classList.remove('hidden');
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
            if (currentCategory === 'favorites') return true;
            var catName = p.category && p.category.name ? p.category.name.toLowerCase() : '';
            if (currentCategory === 'rx') return /rx|prescription/.test(catName);
            if (currentCategory === 'otc') return /otc|over[- ]the[- ]counter/.test(catName);
            if (currentCategory === 'supplies') return /supply|supplies|disposables?/.test(catName);
            return true;
        });
        if (currentCategory === 'favorites') {
            filteredProducts = filteredProducts.slice(0, 9);
        }
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
        var favorites = Math.min(9, all);
        return { all: all, rx: rx, otc: otc, supplies: supplies, favorites: favorites };
    }

    function updateCategoryTabCounts() {
        var counts = countByCategory(allProducts);
        var elAll = document.getElementById('pos-tab-count-all');
        var elRx = document.getElementById('pos-tab-count-rx');
        var elOtc = document.getElementById('pos-tab-count-otc');
        var elSupplies = document.getElementById('pos-tab-count-supplies');
        var elFavorites = document.getElementById('pos-tab-count-favorites');
        if (elAll) elAll.textContent = counts.all;
        if (elRx) elRx.textContent = counts.rx;
        if (elOtc) elOtc.textContent = counts.otc;
        if (elSupplies) elSupplies.textContent = counts.supplies;
        if (elFavorites) elFavorites.textContent = counts.favorites;
    }

    function renderProducts() {
        var emptyEl = document.getElementById('pos-products-empty');
        var gridOuter = document.getElementById('pos-products-grid');
        var gridInner = gridOuter ? gridOuter.querySelector('.grid') : null;
        var listOuter = document.getElementById('pos-products-list');
        var listInner = listOuter ? listOuter.querySelector('#pos-products-list-inner') || listOuter.querySelector('.divide-y') : null;
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
                var catBorderClass = getCategoryBorderClass(cat);
                var catIcon = getCategoryIconSvg(cat);
                var stockBadge = getStockBadgeHtml(stock);
                var outOfStock = stock <= 0;
                var isRx = isProductRx(p);
                var earliestExpiry = getEarliestExpiry(p.batches);
                var expiryBadgeHtml = formatExpiryBadge(earliestExpiry);
                var card = document.createElement('button');
                card.type = 'button';
                card.className = 'pos-product-card ' + catBorderClass + ' group relative flex flex-col rounded-xl border-[1.5px] border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 text-left overflow-hidden shadow-sm transition-all duration-200 hover:border-primary/40 hover:shadow-md hover:-translate-y-0.5 active:translate-y-0' + (outOfStock ? ' opacity-60 cursor-not-allowed' : '');
                card.setAttribute('data-product-id', p.id);
                card.disabled = outOfStock;
                card.innerHTML =
                    '<span class="absolute top-1.5 right-1.5 w-7 h-7 flex items-center justify-center rounded-md bg-slate-100 dark:bg-darkmode-700 shrink-0" aria-hidden="true">' + catIcon + '</span>' +
                    (!outOfStock ? '<div class="pos-product-card-overlay absolute inset-0 z-10 flex items-center justify-center rounded-xl bg-slate-900/70 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none group-hover:pointer-events-auto" aria-hidden="true"><div class="flex gap-1.5 p-2 rounded-lg bg-white dark:bg-darkmode-800 shadow-lg" onclick="event.stopPropagation()">' +
                        [1,2,3,4,5].map(function (n) { return '<button type="button" class="pos-quick-add-qty h-8 w-8 rounded-md border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-200 text-xs font-bold hover:bg-sky-600 hover:text-white hover:border-sky-600 active:bg-sky-700 transition-colors" data-qty="' + n + '">' + n + '</button>'; }).join('') +
                    '</div></div>' : '') +
                    '<div class="flex-1 p-2 flex flex-col gap-0.5">' +
                        '<div class="flex items-center justify-between gap-1">' +
                            '<div class="flex items-center gap-1 min-w-0">' +
                                (isRx ? '<span class="inline-flex items-center rounded border border-rose-200 dark:border-rose-800 bg-rose-50 dark:bg-rose-900/30 px-1 py-0.5 text-[9px] font-bold text-rose-600 dark:text-rose-300 uppercase">Rx</span>' : '<span class="text-[10px] font-medium rounded-full px-1.5 py-0.5 inline-flex shrink-0 ' + catPillClass + '">' + (catName || '—') + '</span>') +
                            '</div>' +
                            (!outOfStock ? '<span class="pos-add-circle flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary text-white shadow-sm transition-all duration-200 group-hover:shadow-md group-hover:scale-110 hover:scale-110 hover:bg-primary/90 hover:shadow-md" aria-label="Add 1 to order"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></span>' : '') +
                        '</div>' +
                        '<div class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight line-clamp-2 min-h-[2rem]">' + name + '</div>' +
                        (generic ? '<div class="text-[10px] text-slate-600 dark:text-slate-400 truncate">' + generic + '</div>' : '') +
                        '<div class="mt-auto flex items-center justify-between flex-wrap gap-1 text-[10px]">' +
                            '<span class="flex items-center gap-0.5 min-w-0">' + stockBadge + (unit ? ' <span class="text-slate-400 dark:text-slate-500 truncate">' + unit + '</span>' : '') + (expiryBadgeHtml ? ' ' + expiryBadgeHtml : '') + '</span>' +
                            '<span class="text-sm font-bold text-primary shrink-0">' + price + '</span>' +
                        '</div>' +
                    '</div>';
                if (!outOfStock) {
                    card.addEventListener('click', function (e) {
                        var overlay = card.querySelector('.pos-product-card-overlay');
                        var qtyBtn = e.target.closest('.pos-quick-add-qty');
                        if (qtyBtn) {
                            var qty = parseInt(qtyBtn.getAttribute('data-qty'), 10) || 1;
                            addToCart(p, qty);
                            card.classList.add('pos-product-card-just-added', 'border-emerald-300', 'bg-emerald-50/50', 'dark:border-emerald-700', 'dark:bg-emerald-900/20');
                            setTimeout(function () { card.classList.remove('pos-product-card-just-added', 'border-emerald-300', 'bg-emerald-50/50', 'dark:border-emerald-700', 'dark:bg-emerald-900/20'); }, 400);
                            return;
                        }
                        if (overlay && overlay.contains(e.target)) return;
                        addToCart(p, 1);
                        card.classList.add('pos-product-card-just-added', 'border-emerald-300', 'bg-emerald-50/50', 'dark:border-emerald-700', 'dark:bg-emerald-900/20');
                        setTimeout(function () { card.classList.remove('pos-product-card-just-added', 'border-emerald-300', 'bg-emerald-50/50', 'dark:border-emerald-700', 'dark:bg-emerald-900/20'); }, 400);
                    });
                    card.querySelectorAll('.pos-quick-add-qty').forEach(function (btn) {
                        btn.addEventListener('click', function (e) {
                            e.preventDefault();
                            e.stopPropagation();
                            var qty = parseInt(this.getAttribute('data-qty'), 10) || 1;
                            addToCart(p, qty);
                            card.classList.add('pos-product-card-just-added', 'border-emerald-300', 'bg-emerald-50/50', 'dark:border-emerald-700', 'dark:bg-emerald-900/20');
                            setTimeout(function () { card.classList.remove('pos-product-card-just-added', 'border-emerald-300', 'bg-emerald-50/50', 'dark:border-emerald-700', 'dark:bg-emerald-900/20'); }, 400);
                        });
                    });
                }
                gridInner.appendChild(card);
            });
            var paginationWrap = gridOuter.querySelector('.pos-products-pagination');
            if (paginationWrap) paginationWrap.remove();
            if (totalFiltered > 0) {
                var pagEl = document.createElement('div');
                pagEl.className = 'pos-products-pagination flex flex-shrink-0 items-center justify-between gap-2 px-4 py-2 border-t border-slate-200 dark:border-darkmode-600 bg-slate-50/50 dark:bg-darkmode-800/50';
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
            var cat = null;
            var catName = '';
            var catPillClass = '';
            var isRx = false;
            pageProducts.forEach(function (p) {
                var stock = Array.isArray(p.batches)
                    ? p.batches.reduce(function (sum, b) { return sum + (parseFloat(b.quantity) || 0); }, 0)
                    : 0;
                var price = formatMoney(p.price || 0);
                var name = p.name || 'Product';
                var generic = p.generic_name || '';
                var unit = p.unit || '';
                cat = p.category;
                catName = cat && cat.name ? cat.name : '';
                catPillClass = getCategoryPillClass(cat);
                var stockBadge = getStockBadgeHtml(stock);
                var outOfStock = stock <= 0;
                isRx = isProductRx(p);
                var rxBadge = isRx ? '<span class="shrink-0 inline-flex items-center rounded border border-rose-200 dark:border-rose-800 bg-rose-50 dark:bg-rose-900/30 px-1 py-0.5 text-[9px] font-bold text-rose-600 dark:text-rose-300 uppercase">Rx</span>' : '';
                var categoryPill = !isRx ? '<span class="text-[10px] font-medium rounded-full px-1.5 py-0.5 inline-flex shrink-0 ' + catPillClass + '">' + (catName || '—') + '</span>' : '';
                var row = document.createElement('div');
                row.className = 'flex items-center justify-between gap-3 py-2.5 px-3 rounded-lg border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 hover:border-slate-300 dark:hover:border-darkmode-500 transition-colors';
                row.innerHTML =
                    '<div class="min-w-0 flex-1">' +
                        '<div class="flex items-center gap-1.5 flex-wrap">' + rxBadge + categoryPill + '<span class="text-xs font-semibold text-slate-800 dark:text-slate-100 truncate">' + escapeHtml(name) + '</span></div>' +
                        (generic ? '<div class="mt-0.5 text-[10px] text-slate-500 dark:text-slate-400 truncate">' + escapeHtml(generic) + '</div>' : '') +
                        '<div class="mt-0.5 text-[10px]">' + stockBadge + (unit ? ' · ' + unit : '') + '</div>' +
                    '</div>' +
                    '<div class="flex items-center gap-2 flex-shrink-0">' +
                        '<span class="text-sm font-bold text-primary tabular-nums">' + price + '</span>' +
                        '<button type="button" class="pos-add-btn inline-flex h-8 min-w-[2rem] items-center justify-center rounded-lg border-2 border-primary bg-primary px-3 py-1.5 text-xs font-semibold text-white hover:bg-primary/90 transition-colors' + (outOfStock ? ' opacity-50 cursor-not-allowed' : '') + '"' + (outOfStock ? ' disabled' : '') + '>Add</button>' +
                    '</div>';
                var btn = row.querySelector('.pos-add-btn');
                if (!outOfStock) {
                    btn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        addToCart(p);
                        row.classList.add('border-emerald-300', 'bg-emerald-50/50', 'dark:border-emerald-700', 'dark:bg-emerald-900/20');
                        setTimeout(function () { row.classList.remove('border-emerald-300', 'bg-emerald-50/50', 'dark:border-emerald-700', 'dark:bg-emerald-900/20'); }, 400);
                    });
                }
                listInner.appendChild(row);
            });
            var listPaginationWrap = listOuter ? listOuter.querySelector('.pos-products-pagination') : null;
            if (listPaginationWrap) listPaginationWrap.remove();
            if (totalFiltered > 0 && listOuter) {
                var listPagEl = document.createElement('div');
                listPagEl.className = 'pos-products-pagination flex items-center justify-between gap-2 px-4 py-2 border-t border-slate-200 dark:border-darkmode-600 bg-slate-50/50 dark:bg-darkmode-800/50 flex-shrink-0';
                var prevDisabled = productsCurrentPage <= 1 ? ' disabled' : '';
                var nextDisabled = productsCurrentPage >= totalPages ? ' disabled' : '';
                listPagEl.innerHTML = '<span class="text-xs text-slate-500 dark:text-slate-400">Showing ' + (pageStart + 1) + '–' + Math.min(pageStart + productsPerPage, totalFiltered) + ' of ' + totalFiltered + '</span>' +
                    '<div class="flex gap-1">' +
                    '<button type="button" class="pos-products-prev rounded-lg border border-slate-200 dark:border-darkmode-500 px-2.5 py-1 text-xs font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-darkmode-600 disabled:opacity-50"' + prevDisabled + '>Previous</button>' +
                    '<button type="button" class="pos-products-next rounded-lg border border-slate-200 dark:border-darkmode-500 px-2.5 py-1 text-xs font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-darkmode-600 disabled:opacity-50"' + nextDisabled + '>Next</button>' +
                    '</div>';
                listOuter.appendChild(listPagEl);
                listPagEl.querySelector('.pos-products-prev').addEventListener('click', function () { if (productsCurrentPage > 1) { productsCurrentPage--; renderProducts(); } });
                listPagEl.querySelector('.pos-products-next').addEventListener('click', function () { if (productsCurrentPage < totalPages) { productsCurrentPage++; renderProducts(); } });
            }
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

    function addToCart(product, quantity) {
        if (!product || !product.id) return;
        var qty = parseInt(quantity, 10);
        if (isNaN(qty) || qty < 1) qty = 1;
        var existing = findCartItem(product.id);
        if (existing) {
            existing.quantity += qty;
        } else {
            cartItems.push({
                product_id: product.id,
                name: product.name || 'Product',
                generic_name: product.generic_name || '',
                unit: product.unit || '',
                unit_price: parseFloat(product.price) || 0,
                quantity: qty,
                is_rx: isProductRx(product),
                notes: ''
            });
        }
        renderCart();
        saveCartToStorage();
    }

    function getEarliestExpiry(batches) {
        if (!Array.isArray(batches) || !batches.length) return null;
        var date = null;
        batches.forEach(function (b) {
            var d = b.expiry_date ? (b.expiry_date.split && b.expiry_date.split('T')[0]) || b.expiry_date : null;
            if (d && (!date || d < date)) date = d;
        });
        return date;
    }

    function formatExpiryBadge(expiryStr) {
        if (!expiryStr) return '';
        var d = new Date(expiryStr);
        if (isNaN(d.getTime())) return '';
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        var label = 'Exp: ' + months[d.getMonth()] + ' ' + d.getFullYear();
        var now = new Date();
        var daysLeft = Math.ceil((d - now) / (24 * 60 * 60 * 1000));
        var isSoon = daysLeft <= 30;
        return '<span class="pos-expiry-badge text-[9px] font-semibold ' + (isSoon ? 'text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/30' : 'text-slate-500 dark:text-slate-400') + '">' + label + '</span>';
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
            qty = 1;
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

    function setCartItemNotes(productId, notes) {
        for (var i = 0; i < cartItems.length; i++) {
            if (cartItems[i].product_id === productId) {
                cartItems[i].notes = notes || '';
                saveCartToStorage();
                return;
            }
        }
    }

    function clearCart() {
        cartItems = [];
        appliedDiscounts = [];
        serviceChargeAmount = 0;
        var paymentName = document.getElementById('pos-payment-modal-customer-name');
        var paymentAddr = document.getElementById('pos-payment-modal-customer-address');
        var splitName = document.getElementById('pos-split-modal-customer-name');
        var splitAddr = document.getElementById('pos-split-modal-customer-address');
        if (paymentName) paymentName.value = '';
        if (paymentAddr) paymentAddr.value = '';
        if (splitName) splitName.value = '';
        if (splitAddr) splitAddr.value = '';
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
            container.innerHTML = '<div class="flex flex-col items-center justify-center px-4 py-8 text-center"><span class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 dark:bg-darkmode-700 text-slate-400 mb-3" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg></span><p class="text-xs font-medium text-slate-600 dark:text-slate-400">Scan or search a product to begin</p><p class="mt-0.5 text-[10px] text-slate-500 dark:text-slate-500">Use the search bar, categories, or barcode scanner.</p></div>';
            if (badgeEl) { badgeEl.classList.add('hidden'); badgeEl.textContent = '0 items'; }
        } else {
            // Order line: top = title + description, bottom = qty + total + void (compressed)
            var qtyBtnCls = 'pos-cart-qty-btn inline-flex h-5 w-5 items-center justify-center rounded border border-slate-200 bg-white text-slate-600 text-[9px] font-bold transition-colors hover:border-primary hover:text-primary hover:bg-primary/5';
            var qtyInputCls = 'pos-cart-qty-input w-6 min-w-[1.5rem] text-center text-[10px] font-semibold text-slate-800 rounded border border-slate-200 bg-white py-0 px-0 focus:ring-1 focus:ring-primary/20 focus:border-primary md:[appearance:textfield] md:[&::-webkit-outer-spin-button]:appearance-none md:[&::-webkit-outer-spin-button]:m-0 md:[&::-webkit-inner-spin-button]:appearance-none md:[&::-webkit-inner-spin-button]:m-0';
            var html = '';
            cartItems.forEach(function (item) {
                var lineTotal = item.unit_price * item.quantity;
                var unitPriceStr = item.unit_price.toFixed ? item.unit_price.toFixed(2) : item.unit_price;
                var rxBadge = item.is_rx ? '<span class="shrink-0 inline-flex items-center rounded px-1 py-0.5 text-[9px] font-semibold uppercase tracking-wide bg-rose-50 text-rose-600 border border-rose-200">Rx</span>' : '';
                var descParts = [];
                if (item.generic_name) descParts.push(item.generic_name);
                descParts.push('₱' + unitPriceStr + '/unit');
                var description = descParts.join(' · ');
                var itemNotes = (item.notes != null && item.notes !== '') ? escapeHtml(item.notes) : '';
                html += [
                    '<article class="pos-order-item pos-order-item-animate flex flex-col gap-1.5 rounded-lg border border-slate-200 bg-slate-50/30 px-2 py-1.5 transition-colors hover:border-slate-300 hover:bg-slate-50/50 min-w-0">',
                    '<div class="min-w-0">',
                    '<div class="flex items-center gap-1.5 min-w-0">',
                    rxBadge,
                    '<h3 class="pos-order-item-name text-xs font-semibold text-slate-800 truncate leading-tight">', escapeHtml(item.name), (item.quantity > 1 ? ' <span class="text-slate-500 font-normal">' + item.quantity + '×</span>' : ''), '</h3>',
                    '</div>',
                    '<p class="pos-order-item-desc mt-0.5 text-[10px] text-slate-500 truncate leading-snug">', escapeHtml(description), '</p>',
                    '</div>',
                    '<div class="flex items-center justify-between gap-1.5 flex-wrap">',
                    '<div class="pos-qty-control inline-flex items-center gap-0 rounded border border-slate-200 bg-white px-0.5 py-0 shadow-sm">',
                    '<button type="button" class="', qtyBtnCls, '" data-id="', item.product_id, '" data-delta="-1" aria-label="Decrease">−</button>',
                    '<input type="number" class="', qtyInputCls, (POS_TOUCHSCREEN ? ' js-kioskboard-input' : ''), '" min="1" value="', item.quantity, '" data-id="', item.product_id, '" inputmode="numeric" autocomplete="off" aria-label="Quantity"', (POS_TOUCHSCREEN ? ' data-kioskboard-type="numpad" data-kioskboard-placement="bottom"' : ''), '>',
                    '<button type="button" class="', qtyBtnCls, '" data-id="', item.product_id, '" data-delta="1" aria-label="Increase">+</button>',
                    '</div>',
                    '<div class="flex items-center gap-1 shrink-0">',
                    '<span class="pos-order-item-total text-[11px] font-semibold text-slate-800 tabular-nums whitespace-nowrap">', formatMoney(lineTotal), '</span>',
                    '<button type="button" class="pos-cart-void-btn inline-flex h-5 w-5 shrink-0 items-center justify-center rounded border border-slate-200 bg-white text-slate-400 text-sm leading-none transition-colors hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600" data-id="', item.product_id, '" title="Remove line" aria-label="Remove line">×</button>',
                    '</div>',
                    '</div>',
                    '<input type="text" class="pos-cart-notes-input w-full rounded border border-slate-200 bg-white px-1 py-0.5 text-[9px] text-slate-700 placeholder:text-slate-400 focus:ring-1 focus:ring-primary/20 focus:border-primary min-h-0 leading-tight' + (POS_TOUCHSCREEN ? ' js-kioskboard-input' : '') + '" data-id="', item.product_id, '" placeholder="Note" value="', itemNotes, '" maxlength="120" aria-label="Line note"' + (POS_TOUCHSCREEN ? ' data-kioskboard-type="all" data-kioskboard-placement="bottom"' : '') + '>',
                    '</article>'
                ].join('');
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
            if (POS_TOUCHSCREEN && typeof KioskBoard !== 'undefined') {
                container.querySelectorAll('.pos-cart-qty-input.js-kioskboard-input').forEach(function (el) {
                    KioskBoard.run(el);
                });
                container.querySelectorAll('.pos-cart-notes-input.js-kioskboard-input').forEach(function (el) {
                    KioskBoard.run(el);
                });
            }
            container.querySelectorAll('.pos-cart-void-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var id = parseInt(this.getAttribute('data-id'), 10);
                    openVoidManagerModal(id);
                });
            });
            container.querySelectorAll('.pos-cart-notes-input').forEach(function (input) {
                input.addEventListener('change', function () {
                    setCartItemNotes(parseInt(this.getAttribute('data-id'), 10), this.value.trim());
                });
                input.addEventListener('blur', function () {
                    setCartItemNotes(parseInt(this.getAttribute('data-id'), 10), this.value.trim());
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

        var itemCountEl = document.getElementById('pos-item-count');
        if (itemCountEl) itemCountEl.textContent = itemsCount;
        var summarySubtotalEl = document.getElementById('pos-summary-subtotal');
        if (summarySubtotalEl) summarySubtotalEl.textContent = formatMoney(subtotal);
        document.getElementById('pos-subtotal').textContent = formatMoney(subtotal);
        var discountSummaryEl = document.getElementById('pos-discount-summary');
        if (discountSummaryEl) discountSummaryEl.textContent = totalDiscount > 0 ? '-' + formatMoney(totalDiscount) : '-₱0.00';
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
            void totalDueEl.offsetWidth;
            totalDueEl.classList.add('pos-total-amount-animate');
        }
        var headerTotalEl = document.getElementById('pos-header-total');
        if (headerTotalEl) {
            headerTotalEl.textContent = formatMoney(totalDue);
            headerTotalEl.classList.remove('pos-total-amount-animate');
            void headerTotalEl.offsetWidth;
            headerTotalEl.classList.add('pos-total-amount-animate');
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
        updateBottomBarContext();
    }

    function updateBottomBarContext() {
        var empty = !cartItems.length;
        var holdBtn = document.getElementById('pos-bottom-hold');
        var paymentBtn = document.getElementById('pos-bottom-payment');
        if (holdBtn) holdBtn.classList.toggle('pos-bottom-dim', empty);
        if (paymentBtn) paymentBtn.classList.toggle('pos-bottom-dim', empty);
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
        if (labelEl) labelEl.textContent = hasRx ? 'Checkout · Rx Required' : 'Checkout';
    }

    var paymentModalCallback = null;
    var paymentModalType = 'cash';
    var paymentModalTouchscreenPoll = null;
    var lastPaymentReference = '';
    var lastPaymentProvider = '';
    var currentSplitPayments = [];
    var splitPaymentModalCallback = null;
    var splitModalTouchscreenPoll = null;

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

    // Fixed bottom bar: wire each button to existing POS actions
    function initBottomBar() {
        var newSaleBtn = document.getElementById('pos-bottom-new-sale');
        var scanBtn = document.getElementById('pos-bottom-scan');
        var holdBtn = document.getElementById('pos-bottom-hold');
        var discountBtn = document.getElementById('pos-bottom-discount');
        var paymentBtn = document.getElementById('pos-bottom-payment');
        var reprintBtn = document.getElementById('pos-bottom-reprint');
        var lockBtn = document.getElementById('pos-bottom-lock');
        var logoutBtn = document.getElementById('pos-bottom-logout');

        if (newSaleBtn) newSaleBtn.addEventListener('click', function () { var b = document.getElementById('pos-new-sale-btn'); if (b) b.click(); });
        if (scanBtn) scanBtn.addEventListener('click', function () { var b = document.getElementById('pos-scan-btn'); if (b) b.click(); });
        if (holdBtn) holdBtn.addEventListener('click', function () { var b = document.getElementById('pos-hold-order-btn'); if (b) b.click(); });
        if (discountBtn) discountBtn.addEventListener('click', function () { var b = document.getElementById('pos-discount-dropdown-btn'); if (b) b.click(); });
        if (paymentBtn) paymentBtn.addEventListener('click', function () { var b = document.getElementById('pos-complete-sale-btn'); if (b) b.click(); });

        if (reprintBtn) reprintBtn.addEventListener('click', openReprintModal);

        var bottomSalesBtn = document.getElementById('pos-bottom-sales');
        if (bottomSalesBtn) bottomSalesBtn.addEventListener('click', openSalesHistoryModal);

        if (lockBtn) {
            lockBtn.addEventListener('click', function () {
                try { localStorage.setItem('pos_locked_at', String(Date.now())); } catch (e) {}
                window.location.href = '{{ route('dashboard.pos.lock') }}';
            });
        }
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function () {
                var headerLogout = document.getElementById('pos-logout-btn');
                if (headerLogout) headerLogout.click();
            });
        }

        var minimalToggle = document.getElementById('pos-bottom-minimal-toggle');
        var showBarBtn = document.getElementById('pos-bottom-show-bar');
        var bottomBar = document.getElementById('pos-bottom-bar');
        var POS_BOTTOM_MINIMAL_KEY = 'pos_bottom_minimal';

        function setBottomBarMinimal(minimal) {
            if (!bottomBar) return;
            if (minimal) {
                bottomBar.classList.add('pos-bottom-minimal');
                if (showBarBtn) showBarBtn.style.display = 'flex';
                try { localStorage.setItem(POS_BOTTOM_MINIMAL_KEY, '1'); } catch (e) {}
            } else {
                bottomBar.classList.remove('pos-bottom-minimal');
                if (showBarBtn) showBarBtn.style.display = 'none';
                try { localStorage.removeItem(POS_BOTTOM_MINIMAL_KEY); } catch (e) {}
            }
        }

        if (minimalToggle) minimalToggle.addEventListener('click', function () { setBottomBarMinimal(true); });
        if (showBarBtn) showBarBtn.addEventListener('click', function () { setBottomBarMinimal(false); });

        try {
            if (localStorage.getItem(POS_BOTTOM_MINIMAL_KEY) === '1') setBottomBarMinimal(true);
        } catch (e) {}
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
        amountInput.classList.remove('border-rose-500', 'dark:border-rose-400');

        var cardEwalletBlock = document.getElementById('pos-payment-modal-card-ewallet-fields');
        var refInput = document.getElementById('pos-payment-modal-reference');
        var provInput = document.getElementById('pos-payment-modal-provider');
        if (refInput) refInput.classList.remove('border-rose-500', 'dark:border-rose-400');
        if (provInput) provInput.classList.remove('border-rose-500', 'dark:border-rose-400');
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
        if (POS_TOUCHSCREEN) {
            setTimeout(function () {
                amountInput.focus();
                amountInput.select();
            }, 250);
            if (amountInput.classList.contains('js-kioskboard-input')) {
                if (paymentModalTouchscreenPoll) clearInterval(paymentModalTouchscreenPoll);
                paymentModalTouchscreenPoll = setInterval(updatePaymentModalChangeFromDom, 350);
            }
        }
    }

    function closePaymentModal(success) {
        if (paymentModalTouchscreenPoll) {
            clearInterval(paymentModalTouchscreenPoll);
            paymentModalTouchscreenPoll = null;
        }
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
        var refInput = document.getElementById('pos-payment-modal-reference');
        var provInput = document.getElementById('pos-payment-modal-provider');
        if (!amountInput || !hiddenAmountInput) {
            closePaymentModal(false);
            return;
        }
        var raw = amountInput.value || '';
        var cleaned = raw.replace(/[^\d.,-]/g, '');
        amountInput.value = cleaned;
        var val = cleaned.trim();
        var received = parseAmount(val);
        var isCardOrEwallet = paymentModalType === 'card' || paymentModalType === 'ewallet';

        function showPaymentError(msg, focusEl) {
            if (errorEl) {
                errorEl.textContent = msg;
                errorEl.classList.remove('hidden');
            }
            if (focusEl) focusEl.focus();
        }
        function clearFieldError(input) {
            if (input) input.classList.remove('border-rose-500', 'dark:border-rose-400');
        }
        function setFieldError(input) {
            if (input) input.classList.add('border-rose-500', 'dark:border-rose-400');
        }

        if (errorEl) errorEl.classList.add('hidden');
        if (refInput) clearFieldError(refInput);
        if (provInput) clearFieldError(provInput);
        if (amountInput) clearFieldError(amountInput);

        if (isNaN(received) || received < total) {
            setFieldError(amountInput);
            showPaymentError(
                val === '' ? 'Please enter amount received.' : 'Amount received is less than the total due.',
                amountInput
            );
            return;
        }
        if (isCardOrEwallet) {
            var refVal = refInput ? String(refInput.value || '').trim() : '';
            var provVal = provInput ? String(provInput.value || '').trim() : '';
            var refLabel = paymentModalType === 'card' ? 'Approval / reference no.' : 'Reference / approval no.';
            var provHint = paymentModalType === 'card' ? 'e.g. Visa, Mastercard' : 'e.g. GCash, Maya';
            if (!refVal) {
                setFieldError(refInput);
                showPaymentError('Please enter ' + refLabel.toLowerCase() + '.', refInput);
                return;
            }
            if (!provVal) {
                setFieldError(provInput);
                showPaymentError('Please enter provider (' + provHint + ').', provInput);
                return;
            }
        }
        if (errorEl) errorEl.classList.add('hidden');
        hiddenAmountInput.value = val;
        lastPaymentReference = (refInput && refInput.value) ? String(refInput.value).trim().slice(0, 100) : '';
        lastPaymentProvider = (provInput && provInput.value) ? String(provInput.value).trim().slice(0, 100) : '';
        updateChange();
        closePaymentModal(true);
    }

    var splitPaymentRowIndex = 0;
    function buildSplitPaymentRowHtml(idx) {
        var id = 'pos-split-row-' + idx;
        var refId = 'pos-split-ref-' + idx;
        var provId = 'pos-split-prov-' + idx;
        var kCls = POS_TOUCHSCREEN ? ' js-kioskboard-input' : '';
        var kDataNumpad = POS_TOUCHSCREEN ? ' data-kioskboard-type="numpad" data-kioskboard-placement="bottom"' : '';
        var kDataAll = POS_TOUCHSCREEN ? ' data-kioskboard-type="all" data-kioskboard-placement="bottom"' : '';
        return '<div class="pos-split-row rounded-xl border border-slate-200 dark:border-darkmode-600 bg-slate-50/50 dark:bg-darkmode-700/30 p-3 space-y-2" data-idx="' + idx + '">' +
            '<div class="flex items-center gap-2 flex-wrap">' +
                '<select class="pos-split-method rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-2 py-2 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 min-w-[100px]" data-idx="' + idx + '">' +
                    '<option value="cash">Cash</option><option value="card">Card</option><option value="ewallet">E-wallet</option>' +
                '</select>' +
                '<input type="tel" inputmode="decimal" class="pos-split-amount w-24 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-2 py-2 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400' + kCls + '" placeholder="0.00" data-idx="' + idx + '"' + kDataNumpad + '>' +
                '<button type="button" class="pos-split-remove rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 px-2 py-2 text-xs font-medium" data-idx="' + idx + '" title="Remove">Remove</button>' +
            '</div>' +
            '<div class="pos-split-ref-prov hidden gap-2 grid grid-cols-2">' +
                '<input type="text" id="' + refId + '" class="pos-split-ref rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-2 py-1.5 text-xs placeholder-slate-400' + kCls + '" placeholder="Ref no." maxlength="100" data-idx="' + idx + '"' + kDataAll + '>' +
                '<input type="text" id="' + provId + '" class="pos-split-prov rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-2 py-1.5 text-xs placeholder-slate-400' + kCls + '" placeholder="Provider" maxlength="100" data-idx="' + idx + '"' + kDataAll + '>' +
            '</div>' +
        '</div>';
    }
    function updateSplitTotalEntered() {
        var totalEl = document.getElementById('pos-split-total-entered');
        if (!totalEl) return;
        var sum = 0;
        document.querySelectorAll('.pos-split-amount').forEach(function (input) {
            var v = parseAmount((input.value || '').trim());
            if (!isNaN(v) && v > 0) sum += v;
        });
        totalEl.textContent = formatMoney(sum);
    }
    function toggleSplitRefProv(rowEl) {
        var methodSelect = rowEl.querySelector('.pos-split-method');
        var refProv = rowEl.querySelector('.pos-split-ref-prov');
        if (!refProv || !methodSelect) return;
        var method = (methodSelect.value || 'cash').toLowerCase();
        if (method === 'card' || method === 'ewallet') {
            refProv.classList.remove('hidden');
            refProv.classList.add('grid');
        } else {
            refProv.classList.add('hidden');
            refProv.classList.remove('grid');
        }
    }
    function openSplitPaymentModal(totalDue, onDone) {
        splitPaymentModalCallback = typeof onDone === 'function' ? onDone : null;
        var totalEl = document.getElementById('pos-split-payment-total');
        var rowsContainer = document.getElementById('pos-split-payment-rows');
        var errorEl = document.getElementById('pos-split-payment-error');
        if (!totalEl || !rowsContainer) {
            if (splitPaymentModalCallback) splitPaymentModalCallback(false);
            splitPaymentModalCallback = null;
            return;
        }
        totalEl.textContent = formatMoney(totalDue);
        if (errorEl) errorEl.classList.add('hidden');
        splitPaymentRowIndex = 0;
        rowsContainer.innerHTML = buildSplitPaymentRowHtml(splitPaymentRowIndex++) + buildSplitPaymentRowHtml(splitPaymentRowIndex++);
        rowsContainer.querySelectorAll('.pos-split-row').forEach(function (row) {
            var methodSelect = row.querySelector('.pos-split-method');
            var amountInput = row.querySelector('.pos-split-amount');
            if (methodSelect) {
                methodSelect.addEventListener('change', function () { toggleSplitRefProv(row); });
                toggleSplitRefProv(row);
            }
            if (amountInput) amountInput.addEventListener('input', updateSplitTotalEntered);
        });
        rowsContainer.querySelectorAll('.pos-split-remove').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var idx = this.getAttribute('data-idx');
                var row = this.closest('.pos-split-row');
                if (row && rowsContainer.querySelectorAll('.pos-split-row').length > 1) {
                    row.remove();
                    updateSplitTotalEntered();
                }
            });
        });
        updateSplitTotalEntered();
        document.getElementById('pos-split-payment-modal').classList.remove('hidden');
        if (POS_TOUCHSCREEN && typeof KioskBoard !== 'undefined') {
            rowsContainer.querySelectorAll('.pos-split-amount, .pos-split-ref, .pos-split-prov').forEach(function (el) {
                if (el.classList.contains('js-kioskboard-input')) KioskBoard.run(el);
            });
            if (splitModalTouchscreenPoll) clearInterval(splitModalTouchscreenPoll);
            splitModalTouchscreenPoll = setInterval(updateSplitTotalEntered, 350);
        }
        var firstAmount = rowsContainer.querySelector('.pos-split-amount');
        if (firstAmount) {
            firstAmount.focus();
            firstAmount.select();
            if (POS_TOUCHSCREEN) setTimeout(function () { firstAmount.focus(); firstAmount.select(); }, 250);
        }
    }
    function closeSplitPaymentModal(success) {
        if (splitModalTouchscreenPoll) {
            clearInterval(splitModalTouchscreenPoll);
            splitModalTouchscreenPoll = null;
        }
        document.getElementById('pos-split-payment-modal').classList.add('hidden');
        var cb = splitPaymentModalCallback;
        splitPaymentModalCallback = null;
        if (typeof cb === 'function') cb(success === true);
    }
    function applySplitPaymentFromModal() {
        var totalLabel = document.getElementById('pos-total-due');
        var totalDue = totalLabel ? parseAmount(totalLabel.textContent.replace(/[^\d.,-]/g, '')) : 0;
        var rows = document.querySelectorAll('.pos-split-row');
        var payments = [];
        var errorEl = document.getElementById('pos-split-payment-error');
        rows.forEach(function (row) {
            var methodSelect = row.querySelector('.pos-split-method');
            var amountInput = row.querySelector('.pos-split-amount');
            var refInput = row.querySelector('.pos-split-ref');
            var provInput = row.querySelector('.pos-split-prov');
            var method = (methodSelect && methodSelect.value) ? methodSelect.value : 'cash';
            var amt = amountInput ? parseAmount((amountInput.value || '').trim()) : 0;
            if (amt <= 0) return;
            var p = { payment_method: method, amount: amt, payment_reference: '', payment_provider: '' };
            if ((method === 'card' || method === 'ewallet') && refInput && refInput.value) p.payment_reference = String(refInput.value).trim().slice(0, 100);
            if ((method === 'card' || method === 'ewallet') && provInput && provInput.value) p.payment_provider = String(provInput.value).trim().slice(0, 100);
            payments.push(p);
        });
        var sum = payments.reduce(function (s, p) { return s + p.amount; }, 0);
        if (Math.abs(sum - totalDue) > 0.01) {
            if (errorEl) {
                errorEl.textContent = 'Total entered (' + formatMoney(sum) + ') must equal total due (' + formatMoney(totalDue) + ').';
                errorEl.classList.remove('hidden');
            }
            return;
        }
        if (payments.length === 0) {
            if (errorEl) {
                errorEl.textContent = 'Add at least one payment with an amount.';
                errorEl.classList.remove('hidden');
            }
            return;
        }
        if (errorEl) errorEl.classList.add('hidden');
        currentSplitPayments = payments;
        closeSplitPaymentModal(true);
    }

    var salesHistoryList = [];
    var salesHistoryMeta = null;
    var salesHistoryFilters = { search: '', date_from: '', date_to: '', status: 'all', sort: 'created_at', dir: 'desc', page: 1, per_page: 25 };

    function formatOrNumber(num) {
        if (num == null || num === '') return '';
        var s = String(num).replace(/\D/g, '');
        var n = parseInt(s, 10);
        if (isNaN(n)) return String(num);
        return String(n).padStart(10, '0');
    }
    function getSalesStatusBadgeClass(status) {
        var s = (status || 'completed').toLowerCase();
        if (s === 'voided') return 'rounded-full px-2 py-0.5 text-[10px] font-medium bg-rose-100 text-rose-700';
        if (s === 'pending') return 'rounded-full px-2 py-0.5 text-[10px] font-medium bg-amber-100 text-amber-700';
        if (s === 'refunded') return 'rounded-full px-2 py-0.5 text-[10px] font-medium bg-sky-100 text-sky-700';
        return 'rounded-full px-2 py-0.5 text-[10px] font-medium bg-emerald-100 text-emerald-700';
    }
    function getSalesStatusLabel(status) {
        var s = (status || 'completed').toLowerCase();
        return s.charAt(0).toUpperCase() + s.slice(1);
    }
    function getPaymentDisplay(method, provider) {
        var m = (method || '').toLowerCase();
        var p = (provider || '').trim();
        if (m === 'split') return { icon: '🔀', label: 'Split', cls: 'bg-slate-100 text-slate-700' };
        if (m === 'cash') return { icon: '💵', label: 'Cash', cls: 'bg-emerald-100 text-emerald-700' };
        if (m === 'card') return { icon: '💳', label: p || 'Card', cls: 'bg-sky-100 text-sky-700' };
        if (m === 'ewallet') return { icon: '📱', label: p || 'E-wallet', cls: 'bg-violet-100 text-violet-700' };
        if (m === 'other') return { icon: '💰', label: p || 'Other', cls: 'bg-slate-100 text-slate-600' };
        return { icon: '💰', label: p || method || '—', cls: 'bg-slate-100 text-slate-600' };
    }

    function buildSalesHistoryParams() {
        var p = { per_page: salesHistoryFilters.per_page, page: salesHistoryFilters.page, sort: salesHistoryFilters.sort, dir: salesHistoryFilters.dir };
        if (currentTerminalId) p.terminal_id = currentTerminalId;
        if (salesHistoryFilters.search) p.search = salesHistoryFilters.search;
        if (salesHistoryFilters.date_from) p.date_from = salesHistoryFilters.date_from;
        if (salesHistoryFilters.date_to) p.date_to = salesHistoryFilters.date_to;
        if (salesHistoryFilters.status && salesHistoryFilters.status !== 'all') p.status = salesHistoryFilters.status;
        return p;
    }

    function loadSalesHistory() {
        var loadingEl = document.getElementById('pos-sales-history-loading');
        var emptyEl = document.getElementById('pos-sales-history-empty');
        var tableWrap = document.getElementById('pos-sales-history-table-wrap');
        var tbody = document.getElementById('pos-sales-history-tbody');
        var summaryEl = document.getElementById('pos-sales-history-summary');
        var paginationEl = document.getElementById('pos-sales-history-pagination');
        if (loadingEl) loadingEl.classList.remove('hidden');
        if (emptyEl) emptyEl.classList.add('hidden');
        if (tableWrap) tableWrap.classList.add('hidden');
        if (tbody) tbody.innerHTML = '';
        if (summaryEl) summaryEl.classList.add('hidden');
        if (paginationEl) paginationEl.classList.add('hidden');
        var params = buildSalesHistoryParams();
        axios.get(apiBase + '/pos/transactions', { headers: headers.headers, params: params })
            .then(function (r) {
                var list = (r.data && r.data.data) ? r.data.data : [];
                salesHistoryList = list;
                salesHistoryMeta = (r.data && r.data.meta) ? r.data.meta : null;
                if (loadingEl) loadingEl.classList.add('hidden');
                if (list.length === 0) {
                    if (emptyEl) emptyEl.classList.remove('hidden');
                } else {
                    if (tableWrap) tableWrap.classList.remove('hidden');
                    renderSalesHistoryTable(list);
                    updateSalesHistorySummary(list);
                    updateSalesHistoryPagination(salesHistoryMeta);
                    updateSalesHistorySortIcons();
                    if (summaryEl) summaryEl.classList.remove('hidden');
                    if (paginationEl) paginationEl.classList.remove('hidden');
                }
            })
            .catch(function (err) {
                if (loadingEl) loadingEl.classList.add('hidden');
                if (emptyEl) { emptyEl.textContent = (err.response && err.response.data && err.response.data.message) || 'Failed to load transactions. Try adjusting your filters.'; emptyEl.classList.remove('hidden'); }
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Error', text: (err.response && err.response.data && err.response.data.message) || 'Failed to load sales history.' });
            });
    }

    function renderSalesHistoryTable(list) {
        var tbody = document.getElementById('pos-sales-history-tbody');
        if (!tbody) return;
        tbody.innerHTML = '';
        list.forEach(function (t) {
            var tr = document.createElement('tr');
            tr.className = 'pos-sales-row cursor-pointer hover:bg-slate-50 transition-colors';
            tr.setAttribute('data-id', t.id);
            tr.setAttribute('data-or', t.or_number || '');
            var created = t.created_at || '';
            if (created && created.indexOf('T') !== -1) {
                var d = new Date(created.replace('Z', ''));
                created = d.toLocaleString('en-PH', { dateStyle: 'short', timeStyle: 'short' });
            }
            var totalVal = parseFloat(t.total) || 0;
            var status = (t.status || 'completed').toLowerCase();
            var statusCls = getSalesStatusBadgeClass(status);
            var statusLabel = getSalesStatusLabel(status);
            var pay = getPaymentDisplay(t.payment_method, t.payment_provider);
            var orFormatted = formatOrNumber(t.or_number);
            var cashierName = t.cashier_name || t.cashier && (t.cashier.name || t.cashier.email) || '—';
            tr.innerHTML =
                '<td class="pos-sales-td-or px-3 py-2 font-mono text-xs font-semibold text-slate-800 bg-white sticky left-0 z-10">' + escapeHtml(orFormatted) + '</td>' +
                '<td class="px-3 py-2 text-slate-600">' + escapeHtml(created) + '</td>' +
                '<td class="px-3 py-2 text-right font-semibold text-slate-800">' + formatMoney(totalVal) + '</td>' +
                '<td class="px-3 py-2"><span class="inline-flex items-center gap-1 rounded-md px-1.5 py-0.5 text-[10px] font-medium ' + pay.cls + '"><span class="text-sm leading-none" aria-hidden="true">' + pay.icon + '</span>' + escapeHtml(pay.label) + '</span></td>' +
                '<td class="px-3 py-2 text-slate-600">' + escapeHtml(cashierName) + '</td>' +
                '<td class="px-3 py-2"><span class="inline-flex ' + statusCls + '">' + escapeHtml(statusLabel) + '</span></td>' +
                '<td class="px-3 py-2 text-right bg-white sticky right-0 z-10">' +
                    (status !== 'voided' ?
                        '<button type="button" class="pos-sales-void-btn mr-1 rounded border border-rose-200 px-2 py-1 text-[10px] font-medium text-rose-600 hover:bg-rose-50" data-id="' + t.id + '" data-or="' + escapeHtml(orFormatted) + '">Void</button>' +
                        '<button type="button" class="pos-sales-reprint-btn rounded border border-sky-200 px-2 py-1 text-[10px] font-medium text-sky-600 hover:bg-sky-50" data-id="' + t.id + '">Reprint</button>' :
                        '<button type="button" class="pos-sales-reprint-btn rounded border border-sky-200 px-2 py-1 text-[10px] font-medium text-sky-600 hover:bg-sky-50" data-id="' + t.id + '">Reprint</button>') +
                '</td>';
            tbody.appendChild(tr);
        });
        tbody.querySelectorAll('.pos-sales-row').forEach(function (row) {
            row.addEventListener('click', function (e) {
                if (e.target.closest('.pos-sales-void-btn') || e.target.closest('.pos-sales-reprint-btn')) return;
                var id = row.getAttribute('data-id');
                if (id) openSalesHistoryDetail(id);
            });
        });
        tbody.querySelectorAll('.pos-sales-void-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) { e.stopPropagation(); openSalesVoidModal(this.getAttribute('data-id'), this.getAttribute('data-or')); });
        });
        tbody.querySelectorAll('.pos-sales-reprint-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) { e.stopPropagation(); openSalesReprintPreviewModal(btn.getAttribute('data-id')); });
        });
    }

    function updateSalesHistorySummary(list) {
        var totalCount = list.length;
        var totalSales = 0;
        var voidedCount = 0;
        var voidedAmount = 0;
        list.forEach(function (t) {
            var amt = parseFloat(t.total) || 0;
            var s = (t.status || '').toLowerCase();
            if (s === 'voided') { voidedCount++; voidedAmount += amt; }
            else totalSales += amt;
        });
        var countEl = document.getElementById('pos-sales-history-summary-count');
        var totalEl = document.getElementById('pos-sales-history-summary-total');
        var voidedEl = document.getElementById('pos-sales-history-summary-voided');
        if (countEl) countEl.textContent = totalCount + ' transaction' + (totalCount !== 1 ? 's' : '');
        if (totalEl) totalEl.textContent = 'Total sales: ' + formatMoney(totalSales);
        if (voidedEl) voidedEl.textContent = 'Voided: ' + voidedCount + ' (' + formatMoney(voidedAmount) + ')';
    }

    function updateSalesHistoryPagination(meta) {
        if (!meta) return;
        var from = meta.from != null ? meta.from : 0;
        var to = meta.to != null ? meta.to : 0;
        var total = meta.total != null ? meta.total : 0;
        var current = meta.current_page != null ? meta.current_page : 1;
        var last = meta.last_page != null ? meta.last_page : 1;
        var recordCountEl = document.getElementById('pos-sales-history-record-count');
        var prevBtn = document.getElementById('pos-sales-history-prev');
        var nextBtn = document.getElementById('pos-sales-history-next');
        if (recordCountEl) recordCountEl.textContent = 'Showing ' + from + '–' + to + ' of ' + total;
        if (prevBtn) { prevBtn.disabled = current <= 1; }
        if (nextBtn) { nextBtn.disabled = current >= last; }
    }

    function updateSalesHistorySortIcons() {
        var sort = salesHistoryFilters.sort;
        var dir = salesHistoryFilters.dir;
        var arrow = dir === 'asc' ? ' ↑' : ' ↓';
        document.querySelectorAll('#pos-sales-history-table thead th[data-sort]').forEach(function (th) {
            var icon = th.querySelector('.pos-sales-sort-icon');
            if (icon) {
                icon.textContent = th.getAttribute('data-sort') === sort ? arrow : ' ↕';
            }
        });
    }

    function openSalesHistoryDetail(transactionId) {
        var panel = document.getElementById('pos-sales-history-detail-panel');
        var content = document.getElementById('pos-sales-history-detail-content');
        if (!panel || !content) return;
        content.innerHTML = '<div class="flex items-center justify-center py-8"><span class="text-sm text-slate-500 dark:text-slate-400">Loading…</span></div>';
        panel.classList.remove('hidden');
        if (panel.classList.contains('w-0')) { panel.classList.remove('w-0'); panel.classList.add('sm:w-80'); }
        axios.get(apiBase + '/pos/transactions/' + transactionId + '/receipt', { headers: headers.headers })
            .then(function (r) {
                var t = (r.data && r.data.data) ? r.data.data : null;
                if (!t) { content.innerHTML = '<p class="text-sm text-slate-500 dark:text-slate-400 py-4">Not found.</p>'; return; }
                var orNum = formatOrNumber(t.or_number);
                var created = t.created_at || '';
                if (created && created.indexOf('T') !== -1) created = new Date(created.replace('Z', '')).toLocaleString('en-PH', { dateStyle: 'short', timeStyle: 'short' });
                var cashierName = (t.cashier && (t.cashier.name || t.cashier.email)) || '—';
                var pay = getPaymentDisplay(t.payment_method, t.payment_provider);
                var html = '<div class="rounded-xl border border-slate-200 dark:border-darkmode-600 bg-slate-50/50 dark:bg-darkmode-700/30 px-4 py-3">' +
                    '<p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">OR Number</p>' +
                    '<p class="mt-0.5 text-base font-bold text-slate-800 dark:text-slate-100">' + escapeHtml(orNum) + '</p>' +
                    '</div>' +
                    '<dl class="space-y-2.5 text-sm">' +
                    '<div class="flex justify-between gap-2"><dt class="text-slate-500 dark:text-slate-400 shrink-0">Date</dt><dd class="text-slate-800 dark:text-slate-200 text-right">' + escapeHtml(created) + '</dd></div>' +
                    '<div class="flex justify-between gap-2"><dt class="text-slate-500 dark:text-slate-400 shrink-0">Cashier</dt><dd class="text-slate-800 dark:text-slate-200 text-right">' + escapeHtml(cashierName) + '</dd></div>' +
                    '<div class="flex justify-between gap-2"><dt class="text-slate-500 dark:text-slate-400 shrink-0">Payment</dt><dd class="text-slate-800 dark:text-slate-200 text-right inline-flex items-center gap-1"><span aria-hidden="true">' + pay.icon + '</span>' + escapeHtml(pay.label) + '</dd></div>' +
                    '</dl>' +
                    '<div class="border-t border-slate-200 dark:border-darkmode-600 pt-4">' +
                    '<p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 mb-3">Items</p>' +
                    '<ul class="space-y-2.5">';
                var items = t.items || [];
                items.forEach(function (item) {
                    var name = (item.product && item.product.name) || item.product_id || '—';
                    var qty = item.quantity || 0;
                    var price = parseFloat(item.unit_price) || 0;
                    var sub = (parseFloat(item.subtotal) || (qty * price)).toFixed(2);
                    html += '<li class="flex flex-col gap-0.5 py-2 border-b border-slate-100 dark:border-darkmode-600 last:border-0">' +
                        '<span class="text-sm font-medium text-slate-800 dark:text-slate-200">' + escapeHtml(name) + '</span>' +
                        '<span class="text-xs text-slate-500 dark:text-slate-400">' + qty + ' × ' + formatMoney(price) + ' = <span class="font-medium text-slate-700 dark:text-slate-300">' + formatMoney(parseFloat(sub)) + '</span></span>' +
                        '</li>';
                });
                var total = parseFloat(t.total) || 0;
                html += '</ul></div>' +
                    '<div class="rounded-xl bg-primary/10 dark:bg-primary/20 border border-primary/20 dark:border-primary/30 px-4 py-3 flex justify-between items-center">' +
                    '<span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Total</span>' +
                    '<span class="text-lg font-bold text-primary dark:text-primary">' + formatMoney(total) + '</span>' +
                    '</div>';
                content.innerHTML = html;
            })
            .catch(function () {
                content.innerHTML = '<p class="text-sm text-rose-600 dark:text-rose-400 py-4">Failed to load details.</p>';
            });
    }

    var salesVoidTransactionId = null;
    function openSalesVoidModal(transactionId, orNum) {
        salesVoidTransactionId = transactionId;
        document.getElementById('pos-sales-void-or').textContent = formatOrNumber(orNum) || orNum || transactionId;
        var reasonInput = document.getElementById('pos-sales-void-reason');
        if (reasonInput) reasonInput.value = '';
        document.getElementById('pos-sales-void-reason-error').classList.add('hidden');
        document.getElementById('pos-sales-void-modal').classList.remove('hidden');
        if (POS_TOUCHSCREEN && reasonInput) {
            setTimeout(function () { reasonInput.focus(); }, 250);
        }
    }
    function closeSalesVoidModal() {
        document.getElementById('pos-sales-void-modal').classList.add('hidden');
        salesVoidTransactionId = null;
    }
    function confirmSalesVoidModal() {
        var reason = (document.getElementById('pos-sales-void-reason').value || '').trim();
        var errorEl = document.getElementById('pos-sales-void-reason-error');
        if (!reason) {
            errorEl.classList.remove('hidden');
            errorEl.textContent = 'Please enter a reason.';
            return;
        }
        errorEl.classList.add('hidden');
        var id = salesVoidTransactionId;
        if (!id) { closeSalesVoidModal(); return; }
        axios.post(apiBase + '/pos/transactions/' + id + '/void', { reason: reason }, headers)
            .then(function () {
                closeSalesVoidModal();
                if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Transaction voided', timer: 2000, showConfirmButton: false, timerProgressBar: true });
                var row = document.querySelector('.pos-sales-row[data-id="' + id + '"]');
                if (row) {
                    var statusCell = row.querySelectorAll('td')[5];
                    if (statusCell) statusCell.innerHTML = '<span class="inline-flex ' + getSalesStatusBadgeClass('voided') + '">Voided</span>';
                    var actionsCell = row.querySelectorAll('td')[6];
                    if (actionsCell) actionsCell.innerHTML = '<button type="button" class="pos-sales-reprint-btn rounded border border-sky-200 px-2 py-1 text-[10px] font-medium text-sky-600 hover:bg-sky-50" data-id="' + id + '">Reprint</button>';
                    row.querySelectorAll('.pos-sales-reprint-btn').forEach(function (btn) {
                        btn.addEventListener('click', function (e) { e.stopPropagation(); openSalesReprintPreviewModal(btn.getAttribute('data-id')); });
                    });
                }
            })
            .catch(function (err) {
                var msg = (err.response && err.response.data && err.response.data.message) || 'Failed to void.';
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Void failed', text: msg });
            });
    }

    var salesReprintTransactionId = null;
    function openSalesReprintPreviewModal(transactionId) {
        salesReprintTransactionId = transactionId;
        var official = document.querySelector('input[name="pos-reprint-copy-type"][value="official"]');
        if (official) official.checked = true;
        document.getElementById('pos-sales-reprint-preview-modal').classList.remove('hidden');
    }
    function closeSalesReprintPreviewModal() {
        document.getElementById('pos-sales-reprint-preview-modal').classList.add('hidden');
        salesReprintTransactionId = null;
    }
    function doSalesReprintFromPreview() {
        var id = salesReprintTransactionId;
        var duplicate = document.querySelector('input[name="pos-reprint-copy-type"]:checked');
        var isDuplicate = duplicate && duplicate.value === 'duplicate';
        if (!id) { closeSalesReprintPreviewModal(); return; }
        axios.post(apiBase + '/receipts/reprint/' + id, {}, headers).catch(function () {});
        var url = dashboardBase + '/pos/receipt-print?transaction_id=' + encodeURIComponent(id) + (isDuplicate ? '&reprint=1&copy=duplicate' : '&reprint=1');
        window.open(url, 'pos_receipt_print', 'width=800,height=900,scrollbars=yes');
        closeSalesReprintPreviewModal();
        if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Reprint opened', timer: 1500, showConfirmButton: false, timerProgressBar: true });
    }

    function openSalesHistoryModal() {
        var modal = document.getElementById('pos-sales-history-modal');
        if (!modal) return;
        modal.classList.remove('hidden');
        salesHistoryFilters = { search: '', date_from: '', date_to: '', status: 'all', sort: 'created_at', dir: 'desc', page: 1, per_page: parseInt(document.getElementById('pos-sales-history-per-page').value, 10) || 25 };
        document.getElementById('pos-sales-history-search').value = '';
        document.getElementById('pos-sales-history-date-from').value = '';
        document.getElementById('pos-sales-history-date-to').value = '';
        document.getElementById('pos-sales-history-status').value = 'all';
        var detailPanel = document.getElementById('pos-sales-history-detail-panel');
        if (detailPanel) { detailPanel.classList.add('hidden'); detailPanel.classList.remove('sm:w-80'); detailPanel.classList.add('w-0'); }
        loadSalesHistory();
    }

    function openXReadingModal(xData) {
        lastXReadingData = xData || lastXReadingData;
        if (!lastXReadingData) return;
        var x = lastXReadingData;
        var periodFrom = x.period_from ? new Date(x.period_from.replace(' ', 'T')) : null;
        var periodTo = x.period_to ? new Date(x.period_to.replace(' ', 'T')) : null;
        var fromStr = periodFrom && !isNaN(periodFrom.getTime()) ? periodFrom.toLocaleTimeString('en-PH', { hour: '2-digit', minute: '2-digit' }) : '—';
        var toStr = periodTo && !isNaN(periodTo.getTime()) ? periodTo.toLocaleTimeString('en-PH', { hour: '2-digit', minute: '2-digit' }) : '—';
        var periodEl = document.getElementById('pos-x-reading-period');
        if (periodEl) periodEl.textContent = 'Period: ' + fromStr + ' — ' + toStr + (x.terminal ? ' · Terminal: ' + (x.terminal.code || x.terminal.name || '') : '');
        var transEl = document.getElementById('pos-x-reading-trans');
        if (transEl) transEl.textContent = (x.total_transactions || 0) + ' completed' + (x.void_transactions ? ' / ' + x.void_transactions + ' void' : '');
        var grossEl = document.getElementById('pos-x-reading-gross');
        if (grossEl) grossEl.textContent = formatMoney(x.gross_sales);
        var discEl = document.getElementById('pos-x-reading-disc');
        if (discEl) discEl.textContent = '- ' + formatMoney(x.total_discounts);
        var netEl = document.getElementById('pos-x-reading-net');
        if (netEl) netEl.textContent = formatMoney(x.net_sales);
        var payEl = document.getElementById('pos-x-reading-payments');
        if (payEl) {
            var parts = [];
            if (parseFloat(x.cash_total) > 0) parts.push('Cash ' + formatMoney(x.cash_total));
            if (parseFloat(x.card_total) > 0) parts.push('Card ' + formatMoney(x.card_total));
            if (parseFloat(x.ewallet_total) > 0) parts.push('E-Wallet ' + formatMoney(x.ewallet_total));
            if (parseFloat(x.split_total) > 0) parts.push('Split ' + formatMoney(x.split_total));
            payEl.innerHTML = parts.length ? 'Payments: ' + parts.join(' · ') : '';
        }
        var modal = document.getElementById('pos-x-reading-modal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeXReadingModal() {
        var modal = document.getElementById('pos-x-reading-modal');
        if (modal) modal.classList.add('hidden');
    }

    function doVoidTransaction(transactionId, reason) {
        axios.post(apiBase + '/pos/transactions/' + transactionId + '/void', { reason: reason || '' }, headers)
            .then(function () {
                if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Transaction voided', timer: 2000, showConfirmButton: false, timerProgressBar: true });
                loadSalesHistory();
            })
            .catch(function (err) {
                var msg = (err.response && err.response.data && err.response.data.message) || 'Failed to void.';
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Void failed', text: msg });
            });
    }

    function exportSalesHistoryCsv() {
        var list = salesHistoryList;
        if (!list.length) {
            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'info', title: 'No data', text: 'No transactions to export.' });
            return;
        }
        var headers = ['OR #', 'Date/Time', 'Total', 'Payment', 'Cashier', 'Status'];
        var rows = [headers.join(',')];
        list.forEach(function (t) {
            var created = t.created_at || '';
            if (created && created.indexOf('T') !== -1) created = new Date(created.replace('Z', '')).toLocaleString('en-PH', { dateStyle: 'short', timeStyle: 'short' });
            var pay = getPaymentDisplay(t.payment_method, t.payment_provider);
            var cashier = (t.cashier_name || (t.cashier && (t.cashier.name || t.cashier.email))) || '';
            var status = getSalesStatusLabel(t.status);
            var total = parseFloat(t.total) || 0;
            var orNum = formatOrNumber(t.or_number);
            rows.push([orNum, '"' + String(created).replace(/"/g, '""') + '"', total.toFixed(2), '"' + pay.label.replace(/"/g, '""') + '"', '"' + String(cashier).replace(/"/g, '""') + '"', '"' + status + '"'].join(','));
        });
        var csv = rows.join('\n');
        var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        var a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'sales-history-' + new Date().toISOString().slice(0, 10) + '.csv';
        a.click();
        URL.revokeObjectURL(a.href);
        if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'CSV exported', timer: 1500, showConfirmButton: false, timerProgressBar: true });
    }

    function exportSalesHistoryPdf() {
        var list = salesHistoryList;
        if (!list.length) {
            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'info', title: 'No data', text: 'No transactions to export.' });
            return;
        }
        var win = window.open('', '_blank', 'width=800,height=600');
        if (!win) {
            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'warning', title: 'Popup blocked', text: 'Allow popups to export PDF.' });
            return;
        }
        var rows = list.map(function (t) {
            var created = t.created_at || '';
            if (created && created.indexOf('T') !== -1) created = new Date(created.replace('Z', '')).toLocaleString('en-PH', { dateStyle: 'short', timeStyle: 'short' });
            var pay = getPaymentDisplay(t.payment_method, t.payment_provider);
            var cashier = (t.cashier_name || (t.cashier && (t.cashier.name || t.cashier.email))) || '';
            return '<tr><td class="col-or">' + escapeHtml(formatOrNumber(t.or_number)) + '</td><td class="col-date">' + escapeHtml(created) + '</td><td class="col-total text-right">' + formatMoney(parseFloat(t.total) || 0) + '</td><td class="col-payment">' + escapeHtml(pay.icon + ' ' + pay.label) + '</td><td class="col-cashier">' + escapeHtml(cashier) + '</td><td class="col-status">' + escapeHtml(getSalesStatusLabel(t.status)) + '</td></tr>';
        }).join('');
        var totalAmount = list.reduce(function (sum, t) {
            return sum + (parseFloat(t.total) || 0);
        }, 0);
        var voidedCount = list.filter(function (t) { return (t.status || '').toLowerCase() === 'voided'; }).length;
        var exportedAt = new Date().toLocaleString('en-PH', { dateStyle: 'long', timeStyle: 'short' });
        var summaryLine = list.length + ' transaction(s)  ·  Total: ' + formatMoney(totalAmount) + (voidedCount > 0 ? '  ·  Voided: ' + voidedCount : '');
        var printCss = [
            '@page { size: A4; margin: 18mm 15mm; }',
            'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 11px; line-height: 1.4; color: #1e293b; margin: 0; padding: 0; }',
            '.report { max-width: 100%; }',
            '.report-header { border-bottom: 2px solid #1e293b; padding-bottom: 12px; margin-bottom: 16px; }',
            '.report-title { font-size: 18px; font-weight: 700; letter-spacing: -0.02em; color: #0f172a; margin: 0 0 4px 0; }',
            '.report-meta { font-size: 10px; color: #64748b; margin: 0; }',
            '.report-table { width: 100%; border-collapse: collapse; font-size: 10px; margin-bottom: 16px; }',
            '.report-table thead { display: table-header-group; }',
            '.report-table th { text-align: left; font-weight: 600; color: #334155; background: #f1f5f9; padding: 8px 10px; border: 1px solid #e2e8f0; }',
            '.report-table th.col-total { text-align: right; }',
            '.report-table td { padding: 7px 10px; border: 1px solid #e2e8f0; vertical-align: middle; }',
            '.report-table tbody tr:nth-child(even) { background: #f8fafc; }',
            '.report-table .col-or { font-family: ui-monospace, monospace; font-weight: 600; }',
            '.report-table .col-total { text-align: right; font-weight: 600; }',
            '.report-summary { font-size: 10px; color: #64748b; padding: 10px 0; border-top: 1px solid #e2e8f0; }',
            '.report-footer { font-size: 9px; color: #94a3b8; margin-top: 20px; padding-top: 8px; border-top: 1px solid #e2e8f0; }',
            '@media print { body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } }'
        ].join('\n');
        var html = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Sales History Report</title><style>' + printCss + '</style></head><body><div class="report">' +
            '<header class="report-header"><h1 class="report-title">Sales History Report</h1><p class="report-meta">Exported ' + escapeHtml(exportedAt) + '</p></header>' +
            '<table class="report-table"><thead><tr><th class="col-or">OR #</th><th class="col-date">Date / Time</th><th class="col-total">Total</th><th class="col-payment">Payment</th><th class="col-cashier">Cashier</th><th class="col-status">Status</th></tr></thead><tbody>' + rows + '</tbody></table>' +
            '<p class="report-summary">' + escapeHtml(summaryLine) + '</p>' +
            '<footer class="report-footer">This report is generated from the POS. For official records, use the system audit trail.</footer></div></body></html>';
        win.document.write(html);
        win.document.close();
        win.focus();
        setTimeout(function () { win.print(); win.close(); }, 350);
    }
    function openReceiptPrint(transactionId, isReprint) {
        var url = dashboardBase + '/pos/receipt-print?transaction_id=' + encodeURIComponent(transactionId);
        if (isReprint) url += '&reprint=1';
        window.open(url, 'pos_receipt_print', 'width=800,height=900,scrollbars=yes');
    }

    var reprintTransactionsList = [];
    function openReprintModal() {
        var modal = document.getElementById('pos-reprint-modal');
        if (!modal) return;
        modal.classList.remove('hidden');
        var selectEl = document.getElementById('pos-reprint-select');
        var totalWrap = document.getElementById('pos-reprint-total-wrap');
        var totalEl = document.getElementById('pos-reprint-total');
        var doBtn = document.getElementById('pos-reprint-do-btn');
        var loadingEl = document.getElementById('pos-reprint-loading');
        if (selectEl) { selectEl.innerHTML = '<option value="">— Select transaction —</option>'; selectEl.value = ''; }
        if (totalWrap) totalWrap.classList.add('hidden');
        if (doBtn) doBtn.disabled = true;
        if (loadingEl) loadingEl.classList.remove('hidden');
        var params = { per_page: 50 };
        if (currentTerminalId) params.terminal_id = currentTerminalId;
        axios.get(apiBase + '/pos/transactions', { headers: headers.headers, params: params })
            .then(function (r) {
                var list = (r.data && r.data.data) ? r.data.data : [];
                reprintTransactionsList = list;
                if (loadingEl) loadingEl.classList.add('hidden');
                list.forEach(function (t) {
                    var opt = document.createElement('option');
                    opt.value = t.id;
                    var created = t.created_at || '';
                    if (created && created.indexOf('T') !== -1) {
                        var d = new Date(created.replace('Z', ''));
                        created = d.toLocaleString('en-PH', { dateStyle: 'short', timeStyle: 'short' });
                    }
                    var totalVal = parseFloat(t.total) || 0;
                    opt.textContent = (t.or_number || t.id) + ' — ' + formatMoney(totalVal) + ' — ' + created;
                    opt.setAttribute('data-total', totalVal);
                    selectEl.appendChild(opt);
                });
            })
            .catch(function () {
                if (loadingEl) loadingEl.classList.add('hidden');
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load transactions.' });
            });
    }
    function closeReprintModal() {
        var modal = document.getElementById('pos-reprint-modal');
        if (modal) modal.classList.add('hidden');
    }
    function onReprintSelectChange() {
        var selectEl = document.getElementById('pos-reprint-select');
        var totalWrap = document.getElementById('pos-reprint-total-wrap');
        var totalEl = document.getElementById('pos-reprint-total');
        var doBtn = document.getElementById('pos-reprint-do-btn');
        var id = selectEl && selectEl.value ? selectEl.value : '';
        var opt = selectEl && selectEl.selectedIndex >= 0 ? selectEl.options[selectEl.selectedIndex] : null;
        var total = opt && opt.getAttribute('data-total') !== null ? parseFloat(opt.getAttribute('data-total')) : 0;
        if (id && !isNaN(total)) {
            if (totalWrap) totalWrap.classList.remove('hidden');
            if (totalEl) totalEl.textContent = formatMoney(total);
            if (doBtn) doBtn.disabled = false;
        } else {
            if (totalWrap) totalWrap.classList.add('hidden');
            if (doBtn) doBtn.disabled = true;
        }
    }
    function doReprintFromModal() {
        var selectEl = document.getElementById('pos-reprint-select');
        var id = selectEl && selectEl.value ? selectEl.value : '';
        if (!id) return;
        openReceiptPrint(id, true);
        closeReprintModal();
        if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Reprint opened', timer: 1500, showConfirmButton: false, timerProgressBar: true });
    }
    function escapeHtml(s) {
        if (s == null) return '';
        var div = document.createElement('div');
        div.textContent = s;
        return div.innerHTML;
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
        if (POS_TOUCHSCREEN) setTimeout(function () { numInput.focus(); numInput.select(); }, 250);
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
            btn.setAttribute('data-selected', t === type ? 'true' : 'false');
        });
        var splitHint = document.getElementById('pos-payment-split-hint');
        if (splitHint) splitHint.classList.toggle('hidden', type !== 'split');
        updateCompleteSaleButtonState();
    }

    function toggleVatDetails() {
        var wrap = document.getElementById('pos-vat-breakdown-wrap');
        var btn = document.getElementById('pos-vat-toggle');
        var icon = document.getElementById('pos-vat-toggle-icon');
        if (!wrap) return;
        var willShow = wrap.classList.contains('hidden');
        if (willShow) {
            if (btn) {
                var rect = btn.getBoundingClientRect();
                wrap.style.top = (rect.bottom + 4) + 'px';
                wrap.style.left = rect.left + 'px';
                wrap.style.minWidth = Math.max(rect.width, 200) + 'px';
            }
            wrap.classList.remove('hidden');
        } else {
            wrap.classList.add('hidden');
        }
        if (icon) {
            if (willShow) {
                icon.classList.add('rotate-90');
            } else {
                icon.classList.remove('rotate-90');
            }
        }
    }

    function closeVatFloatingPanel() {
        var wrap = document.getElementById('pos-vat-breakdown-wrap');
        var icon = document.getElementById('pos-vat-toggle-icon');
        if (wrap && !wrap.classList.contains('hidden')) {
            wrap.classList.add('hidden');
            if (icon) icon.classList.remove('rotate-90');
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
                    if (POS_TOUCHSCREEN && typeof KioskBoard !== 'undefined') {
                        input.classList.add('js-kioskboard-input');
                        input.setAttribute('data-kioskboard-type', 'all');
                        input.setAttribute('data-kioskboard-placement', 'bottom');
                        KioskBoard.run(input);
                    }
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
        if (currentTenderType === 'split') {
            var totalLabel = document.getElementById('pos-total-due');
            var total = totalLabel ? parseAmount(totalLabel.textContent.replace(/[^\d.,-]/g, '')) : 0;
            openSplitPaymentModal(total, function (ok) {
                if (!ok) return;
                completeSale();
            });
            return;
        }
        openPaymentModal(currentTenderType || 'cash', function (ok) {
            if (!ok) return;
            completeSale();
        });
    }

    function doCompleteSale(prescriptionForRx) {
        var isSplit = currentSplitPayments && currentSplitPayments.length > 0;
        var paymentMethod = isSplit ? 'split' : (currentTenderType === 'ewallet' ? 'ewallet' : (currentTenderType === 'card' ? 'card' : 'cash'));
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
                    prescription_number: item.is_rx && rxInfo ? rxInfo.number : null,
                    notes: (item.notes && item.notes.trim()) ? item.notes.trim() : null
                };
            })
        };
        if (isSplit) {
            payload.payments = currentSplitPayments.map(function (p) {
                return {
                    payment_method: p.payment_method,
                    amount: p.amount,
                    payment_reference: p.payment_reference || null,
                    payment_provider: p.payment_provider || null
                };
            });
        } else if (paymentMethod === 'card' || paymentMethod === 'ewallet') {
            if (lastPaymentReference) payload.payment_reference = lastPaymentReference;
            if (lastPaymentProvider) payload.payment_provider = lastPaymentProvider;
        }
        var customerNameEl = isSplit
            ? document.getElementById('pos-split-modal-customer-name')
            : document.getElementById('pos-payment-modal-customer-name');
        var customerAddressEl = isSplit
            ? document.getElementById('pos-split-modal-customer-address')
            : document.getElementById('pos-payment-modal-customer-address');
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
                var headerOr = document.getElementById('pos-header-or');
                if (headerOr && orNumber) headerOr.textContent = orNumber;
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
                var totalAmount = parseFloat(d.total) || 0;
                var received, changeAmt;
                if (isSplit) {
                    currentSplitPayments = [];
                    received = totalAmount;
                    changeAmt = 0;
                } else {
                    var amountInput = document.getElementById('pos-amount-received');
                    received = amountInput ? parseAmount(amountInput.value) : 0;
                    changeAmt = Math.max(0, received - totalAmount);
                }
                var receiptPrintUrl = dashboardBase + '/pos/receipt-print';
                var qs = '?transaction_id=' + encodeURIComponent(d.id) + '&amount_received=' + encodeURIComponent(received) + '&change=' + encodeURIComponent(changeAmt);
                try { localStorage.setItem('pos_last_transaction_id', String(d.id)); } catch (e) {}
                window.open(receiptPrintUrl + qs, 'pos_receipt_print', 'width=800,height=900,scrollbars=yes');
                clearCart();
                var amountReceivedEl = document.getElementById('pos-amount-received');
                if (amountReceivedEl) amountReceivedEl.value = '';
                updateChange();
            })
            .catch(function (err) {
                var msg = err.response && err.response.data && err.response.data.message
                    ? err.response.data.message
                    : 'Checkout failed.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Error', text: msg });
                }
            })
            .finally(function () {
                if (btn) btn.disabled = false;
            });
    }

    var VOID_LINE_TITLE = 'Void line?';
    var VOID_LINE_DESC = 'Remove this item from the order. Enter manager PIN or password for this branch.';

    function openVoidManagerModal(productId) {
        pendingVoidProductId = productId;
        var titleEl = document.getElementById('pos-void-modal-title');
        var descEl = document.getElementById('pos-void-modal-desc');
        if (titleEl) titleEl.textContent = VOID_LINE_TITLE;
        if (descEl) descEl.textContent = VOID_LINE_DESC;
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
        if (input) {
            input.focus();
            if (POS_TOUCHSCREEN) setTimeout(function () { input.focus(); }, 250);
        }
    }

    function openClearOrderModal() {
        pendingVoidProductId = PENDING_CLEAR_ORDER;
        var titleEl = document.getElementById('pos-void-modal-title');
        var descEl = document.getElementById('pos-void-modal-desc');
        if (titleEl) titleEl.textContent = VOID_LINE_TITLE;
        if (descEl) descEl.textContent = VOID_LINE_DESC;
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
        if (input) {
            input.focus();
            if (POS_TOUCHSCREEN) setTimeout(function () { input.focus(); }, 250);
        }
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
        var isClearOrder = id === PENDING_CLEAR_ORDER;
        axios.post(apiBase + '/pos/verify-manager', { pin_or_password: pinOrPassword }, headers)
            .then(function () {
                if (isClearOrder) {
                    clearCart();
                    if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Order cleared', text: 'All items removed from the order.', timer: 1200, showConfirmButton: false, timerProgressBar: true });
                    pendingVoidProductId = null;
                    closeVoidManagerModal();
                    return;
                }
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
                if (POS_TOUCHSCREEN) setTimeout(function () { idInput.focus(); }, 250);
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
        var discountInputClass = 'swal2-input' + (POS_TOUCHSCREEN ? ' js-kioskboard-input' : '');
        var discountInputData = POS_TOUCHSCREEN ? ' data-kioskboard-type="numpad" data-kioskboard-placement="bottom"' : '';
        Swal.fire({
            title: typeLabel,
            html: '<input type="number" id="pos-manual-discount-amount" class="' + discountInputClass + '" placeholder="Amount (₱)" min="0" step="0.01" value="0"' + discountInputData + '>',
            showCancelButton: true,
            confirmButtonText: 'Apply',
            didOpen: function () {
                if (POS_TOUCHSCREEN && typeof KioskBoard !== 'undefined') {
                    var el = document.getElementById('pos-manual-discount-amount');
                    if (el && el.classList.contains('js-kioskboard-input')) KioskBoard.run(el);
                }
            },
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
        var posNotReadyRetry = document.getElementById('pos-not-ready-retry-btn');
        if (posNotReadyRetry) {
            posNotReadyRetry.addEventListener('click', function () {
                setRetryButtonLoading(true);
                setNotReadyStatusChip('checking');
                var lastChecked = document.getElementById('pos-not-ready-last-checked');
                if (lastChecked) lastChecked.textContent = 'just now';
                loadTerminalAndProducts();
            });
        }

        var newSaleBtn = document.getElementById('pos-new-sale-btn');
        if (newSaleBtn) {
            newSaleBtn.addEventListener('click', function () {
                clearCart();
                document.getElementById('pos-amount-received').value = '';
                updateChange();
                var orLabel = document.getElementById('pos-or-placeholder');
                var orBadge = document.getElementById('pos-or-badge');
                if (orLabel) orLabel.textContent = 'Pending';
                var headerOr = document.getElementById('pos-header-or');
                if (headerOr) headerOr.textContent = 'Pending';
                if (orBadge) orBadge.className = 'inline-flex items-center rounded-full bg-amber-100 dark:bg-amber-900/30 px-3 py-1 text-[11px] font-medium text-amber-800 dark:text-amber-200';
            });
        }

        document.addEventListener('click', function (e) {
            var btn = e.target && e.target.closest && e.target.closest('#pos-clear-order-btn');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();
            openClearOrderModal();
        });

        var holdBtn = document.getElementById('pos-hold-order-btn');
        if (holdBtn) holdBtn.addEventListener('click', holdOrder);

        var scPwdBtn = document.getElementById('pos-sc-pwd-btn');
        if (scPwdBtn) scPwdBtn.addEventListener('click', function () { document.getElementById('pos-discount-dropdown').classList.add('hidden'); openScPwdModal(); });
        var customerQuickAddBtn = document.getElementById('pos-customer-quick-add-btn');
        if (customerQuickAddBtn) customerQuickAddBtn.addEventListener('click', function () { openScPwdModal(); });

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
        var vatBreakdownWrap = document.getElementById('pos-vat-breakdown-wrap');
        if (vatToggleBtn) {
            vatToggleBtn.addEventListener('click', function (e) { e.stopPropagation(); toggleVatDetails(); });
        }
        document.addEventListener('click', function (e) {
            if (!vatBreakdownWrap || vatBreakdownWrap.classList.contains('hidden')) return;
            if (vatToggleBtn && vatToggleBtn.contains(e.target)) return;
            if (vatBreakdownWrap.contains(e.target)) return;
            closeVatFloatingPanel();
        });

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
        (function initPaymentModalKeypad() {
            var modal = document.getElementById('pos-payment-modal');
            var amountInput = document.getElementById('pos-payment-modal-amount');
            var totalEl = document.getElementById('pos-payment-modal-total');
            if (!modal || !amountInput || !totalEl) return;
            function getTotalDue() {
                return parseAmount(totalEl.textContent.replace(/[^\d.,-]/g, ''));
            }
            function setAmountAndUpdate(val) {
                var cleaned = String(val).replace(/[^\d.,-]/g, '');
                amountInput.value = cleaned;
                updatePaymentModalChangeFromDom();
            }
            modal.addEventListener('click', function (e) {
                var quick = e.target && e.target.closest && e.target.closest('.pos-payment-quick-amount');
                if (quick) {
                    e.preventDefault();
                    var action = quick.getAttribute('data-action');
                    var total = getTotalDue();
                    var current = parseAmount((amountInput.value || '').replace(/[^\d.,-]/g, ''));
                    if (isNaN(current)) current = 0;
                    if (action === 'exact') {
                        setAmountAndUpdate(total % 1 === 0 ? String(Math.round(total)) : total.toFixed(2));
                    } else if (action === '100' || action === '500' || action === '1000') {
                        var add = parseInt(action, 10);
                        setAmountAndUpdate((current + add) % 1 === 0 ? String(Math.round(current + add)) : (current + add).toFixed(2));
                    }
                    return;
                }
                var numkey = e.target && e.target.closest && e.target.closest('.pos-payment-numkey');
                if (numkey) {
                    e.preventDefault();
                    var key = numkey.getAttribute('data-key');
                    var cur = amountInput.value || '';
                    var cleaned = cur.replace(/[^\d.]/g, '');
                    if (key === 'back') {
                        setAmountAndUpdate(cleaned.slice(0, -1));
                    } else if (key === 'clear') {
                        setAmountAndUpdate('');
                    } else if (key === '.') {
                        if (cleaned.indexOf('.') === -1) setAmountAndUpdate(cleaned + '.');
                    } else if (key === '00') {
                        setAmountAndUpdate(cleaned + '00');
                    } else if (/^\d$/.test(key)) {
                        var parts = cleaned.split('.');
                        if (parts.length > 1 && parts[1].length >= 2) return;
                        setAmountAndUpdate(cleaned + key);
                    }
                }
            });
        })();
        if (paymentModal) {
            paymentModal.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    e.preventDefault();
                    closePaymentModal(false);
                }
            });
        }

        var splitModal = document.getElementById('pos-split-payment-modal');
        var splitModalBackdrop = document.getElementById('pos-split-payment-modal-backdrop');
        var splitModalCancel = document.getElementById('pos-split-payment-cancel');
        var splitModalApply = document.getElementById('pos-split-payment-apply');
        var splitAddRow = document.getElementById('pos-split-add-row');
        var splitQuick5050CashCard = document.getElementById('pos-split-quick-50-50-cash-card');
        var splitQuick5050CashEwallet = document.getElementById('pos-split-quick-50-50-cash-ewallet');
        if (splitModalBackdrop) splitModalBackdrop.addEventListener('click', function () { closeSplitPaymentModal(false); });
        if (splitModalCancel) splitModalCancel.addEventListener('click', function () { closeSplitPaymentModal(false); });
        if (splitModalApply) splitModalApply.addEventListener('click', applySplitPaymentFromModal);
        if (splitAddRow) {
            splitAddRow.addEventListener('click', function () {
                var rowsContainer = document.getElementById('pos-split-payment-rows');
                if (!rowsContainer) return;
                var idx = splitPaymentRowIndex++;
                var div = document.createElement('div');
                div.innerHTML = buildSplitPaymentRowHtml(idx).trim();
                var row = div.firstChild;
                rowsContainer.appendChild(row);
                var methodSelect = row.querySelector('.pos-split-method');
                var amountInput = row.querySelector('.pos-split-amount');
                if (methodSelect) { methodSelect.addEventListener('change', function () { toggleSplitRefProv(row); }); toggleSplitRefProv(row); }
                if (amountInput) amountInput.addEventListener('input', updateSplitTotalEntered);
                row.querySelector('.pos-split-remove').addEventListener('click', function () {
                    if (rowsContainer.querySelectorAll('.pos-split-row').length > 1) { row.remove(); updateSplitTotalEntered(); }
                });
                if (POS_TOUCHSCREEN && typeof KioskBoard !== 'undefined') {
                    row.querySelectorAll('.pos-split-amount, .pos-split-ref, .pos-split-prov').forEach(function (el) {
                        if (el.classList.contains('js-kioskboard-input')) KioskBoard.run(el);
                    });
                }
                updateSplitTotalEntered();
            });
        }
        if (splitQuick5050CashCard) {
            splitQuick5050CashCard.addEventListener('click', function () {
                var totalLabel = document.getElementById('pos-split-payment-total');
                var total = totalLabel ? parseAmount(totalLabel.textContent.replace(/[^\d.,-]/g, '')) : 0;
                var half = Math.round((total / 2) * 100) / 100;
                var rows = document.querySelectorAll('.pos-split-row');
                if (rows.length >= 2) {
                    var a0 = rows[0].querySelector('.pos-split-amount');
                    var m0 = rows[0].querySelector('.pos-split-method');
                    var a1 = rows[1].querySelector('.pos-split-amount');
                    var m1 = rows[1].querySelector('.pos-split-method');
                    if (m0) m0.value = 'cash'; if (a0) a0.value = half.toFixed(2);
                    if (m1) m1.value = 'card'; if (a1) a1.value = (total - half).toFixed(2);
                    rows.forEach(function (r) { toggleSplitRefProv(r); });
                }
                updateSplitTotalEntered();
            });
        }
        if (splitQuick5050CashEwallet) {
            splitQuick5050CashEwallet.addEventListener('click', function () {
                var totalLabel = document.getElementById('pos-split-payment-total');
                var total = totalLabel ? parseAmount(totalLabel.textContent.replace(/[^\d.,-]/g, '')) : 0;
                var half = Math.round((total / 2) * 100) / 100;
                var rows = document.querySelectorAll('.pos-split-row');
                if (rows.length >= 2) {
                    var a0 = rows[0].querySelector('.pos-split-amount');
                    var m0 = rows[0].querySelector('.pos-split-method');
                    var a1 = rows[1].querySelector('.pos-split-amount');
                    var m1 = rows[1].querySelector('.pos-split-method');
                    if (m0) m0.value = 'cash'; if (a0) a0.value = half.toFixed(2);
                    if (m1) m1.value = 'ewallet'; if (a1) a1.value = (total - half).toFixed(2);
                    rows.forEach(function (r) { toggleSplitRefProv(r); });
                }
                updateSplitTotalEntered();
            });
        }
        if (splitModal) {
            splitModal.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') { e.preventDefault(); closeSplitPaymentModal(false); }
            });
        }

        var salesHistoryBtn = document.getElementById('pos-sales-history-btn');
        if (salesHistoryBtn) salesHistoryBtn.addEventListener('click', openSalesHistoryModal);
        function openXReadingFromButton() {
            if (!currentTerminalId) {
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'warning', title: 'Terminal required', text: 'POS terminal must be ready before generating X-Reading.' });
                return;
            }
            openXReadingPinModal();
        }
        var xReadingBtn = document.getElementById('pos-x-reading-btn');
        if (xReadingBtn) xReadingBtn.addEventListener('click', openXReadingFromButton);
        var xReadingBottomBtn = document.getElementById('pos-bottom-x-reading');
        if (xReadingBottomBtn) xReadingBottomBtn.addEventListener('click', openXReadingFromButton);
        var xReadingCashDenoms = { '1000': 1000, '500': 500, '200': 200, '100': 100, '50': 50, '20': 20, '10': 10, '5': 5, '1': 1, '0.25': 0.25, '0.10': 0.10, '0.05': 0.05, '0.01': 0.01 };
        function updateXReadingCashTotal() {
            var total = 0;
            for (var key in xReadingCashDenoms) {
                var el = document.getElementById('pos-x-reading-cash-' + key);
                var n = el ? parseInt(el.value, 10) : 0;
                if (!isNaN(n) && n > 0) total += n * xReadingCashDenoms[key];
            }
            var totalEl = document.getElementById('pos-x-reading-cash-total');
            if (totalEl) totalEl.textContent = (Math.round(total * 100) / 100).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
        var pendingXReadingPin = null;
        function openXReadingPinModal() {
            pendingXReadingPin = null;
            var modal = document.getElementById('pos-x-reading-pin-modal');
            var input = document.getElementById('pos-x-reading-pin-input');
            var errorEl = document.getElementById('pos-x-reading-pin-error');
            if (modal) modal.classList.remove('hidden');
            if (input) { input.value = ''; input.classList.remove('border-rose-500'); }
            if (errorEl) errorEl.classList.add('hidden');
            setTimeout(function () { if (input) input.focus(); }, 100);
        }
        function closeXReadingPinModal() {
            var modal = document.getElementById('pos-x-reading-pin-modal');
            if (modal) modal.classList.add('hidden');
        }
        function openXReadingCashModal() {
            var modal = document.getElementById('pos-x-reading-cash-modal');
            var errorEl = document.getElementById('pos-x-reading-cash-error');
            if (modal) modal.classList.remove('hidden');
            if (errorEl) { errorEl.textContent = ''; errorEl.classList.add('hidden'); }
            for (var key in xReadingCashDenoms) {
                var el = document.getElementById('pos-x-reading-cash-' + key);
                if (el) el.value = '0';
            }
            updateXReadingCashTotal();
            setTimeout(function () {
                var first = document.getElementById('pos-x-reading-cash-1000');
                if (first) first.focus();
            }, 100);
        }
        function closeXReadingCashModal() {
            var modal = document.getElementById('pos-x-reading-cash-modal');
            if (modal) modal.classList.add('hidden');
        }
        function continueFromPinToCashModal() {
            var input = document.getElementById('pos-x-reading-pin-input');
            var errorEl = document.getElementById('pos-x-reading-pin-error');
            var continueBtn = document.getElementById('pos-x-reading-pin-modal-continue');
            var pinOrPassword = input && input.value ? input.value.trim() : '';
            if (!pinOrPassword) {
                if (errorEl) { errorEl.textContent = 'Enter manager PIN or password.'; errorEl.classList.remove('hidden'); }
                if (input) { input.classList.add('border-rose-500'); input.focus(); }
                return;
            }
            if (errorEl) errorEl.classList.add('hidden');
            if (input) input.classList.remove('border-rose-500');
            if (continueBtn) { continueBtn.disabled = true; continueBtn.textContent = 'Verifying…'; }
            axios.post(apiBase + '/pos/verify-manager', { pin_or_password: pinOrPassword }, headers)
                .then(function () {
                    pendingXReadingPin = pinOrPassword;
                    if (continueBtn) { continueBtn.disabled = false; continueBtn.textContent = 'Continue'; }
                    closeXReadingPinModal();
                    openXReadingCashModal();
                })
                .catch(function (err) {
                    if (continueBtn) { continueBtn.disabled = false; continueBtn.textContent = 'Continue'; }
                    var msg = (err.response && err.response.data && err.response.data.message) || 'Invalid manager PIN or password.';
                    var errors = err.response && err.response.data && err.response.data.errors;
                    if (errors && errors.pin_or_password && errors.pin_or_password[0]) {
                        msg = errors.pin_or_password[0];
                    }
                    if (errorEl) { errorEl.textContent = msg; errorEl.classList.remove('hidden'); }
                    if (input) { input.classList.add('border-rose-500'); input.focus(); }
                });
        }
        function generateFromCashModal() {
            if (!pendingXReadingPin) {
                closeXReadingCashModal();
                openXReadingPinModal();
                return;
            }
            var errorEl = document.getElementById('pos-x-reading-cash-error');
            if (errorEl) { errorEl.classList.add('hidden'); }
            var cashCount = {};
            for (var key in xReadingCashDenoms) {
                var el = document.getElementById('pos-x-reading-cash-' + key);
                var n = el ? parseInt(el.value, 10) : 0;
                cashCount[key] = isNaN(n) || n < 0 ? 0 : n;
            }
            if (typeof Swal !== 'undefined') Swal.fire({ title: 'Generating…', allowOutsideClick: false, didOpen: function () { Swal.showLoading(); } });
            axios.post(apiBase + '/pos/x-reading/generate', { terminal_id: currentTerminalId, pin_or_password: pendingXReadingPin, cash_count: cashCount }, headers)
                .then(function (r) {
                    var data = r.data && r.data.data ? r.data.data : null;
                    if (data) { lastXReadingData = data; openXReadingModal(data); }
                    pendingXReadingPin = null;
                    closeXReadingCashModal();
                    if (typeof Swal !== 'undefined') Swal.close();
                    if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'X-Reading generated', timer: 1500, showConfirmButton: false, timerProgressBar: true });
                })
                .catch(function (err) {
                    if (typeof Swal !== 'undefined') Swal.close();
                    var msg = (err.response && err.response.data && err.response.data.message) || 'Failed to generate X-Reading.';
                    var errors = err.response && err.response.data && err.response.data.errors;
                    if (errors && errors.pin_or_password && errors.pin_or_password[0]) {
                        if (errorEl) { errorEl.textContent = errors.pin_or_password[0]; errorEl.classList.remove('hidden'); }
                    } else {
                        if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'X-Reading failed', text: msg });
                    }
                });
        }
        var xReadingPinModalBackdrop = document.getElementById('pos-x-reading-pin-modal-backdrop');
        var xReadingPinModalCancel = document.getElementById('pos-x-reading-pin-modal-cancel');
        var xReadingPinModalContinue = document.getElementById('pos-x-reading-pin-modal-continue');
        var xReadingPinInput = document.getElementById('pos-x-reading-pin-input');
        if (xReadingPinModalBackdrop) xReadingPinModalBackdrop.addEventListener('click', closeXReadingPinModal);
        if (xReadingPinModalCancel) xReadingPinModalCancel.addEventListener('click', closeXReadingPinModal);
        if (xReadingPinModalContinue) xReadingPinModalContinue.addEventListener('click', continueFromPinToCashModal);
        if (xReadingPinInput) xReadingPinInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); continueFromPinToCashModal(); }
        });
        var xReadingCashModalBackdrop = document.getElementById('pos-x-reading-cash-modal-backdrop');
        var xReadingCashModalBack = document.getElementById('pos-x-reading-cash-modal-back');
        var xReadingCashModalGenerate = document.getElementById('pos-x-reading-cash-modal-generate');
        if (xReadingCashModalBackdrop) xReadingCashModalBackdrop.addEventListener('click', function () { closeXReadingCashModal(); openXReadingPinModal(); });
        if (xReadingCashModalBack) xReadingCashModalBack.addEventListener('click', function () { closeXReadingCashModal(); openXReadingPinModal(); });
        if (xReadingCashModalGenerate) xReadingCashModalGenerate.addEventListener('click', generateFromCashModal);
        for (var d in xReadingCashDenoms) {
            var cashInput = document.getElementById('pos-x-reading-cash-' + d);
            if (cashInput) cashInput.addEventListener('input', updateXReadingCashTotal);
        }
        var salesHistoryClose = document.getElementById('pos-sales-history-close');
        var salesHistoryBackdrop = document.getElementById('pos-sales-history-modal-backdrop');
        if (salesHistoryClose) salesHistoryClose.addEventListener('click', function () { document.getElementById('pos-sales-history-modal').classList.add('hidden'); });
        if (salesHistoryBackdrop) salesHistoryBackdrop.addEventListener('click', function () { document.getElementById('pos-sales-history-modal').classList.add('hidden'); });
        var xReadingModal = document.getElementById('pos-x-reading-modal');
        var xReadingBackdrop = document.getElementById('pos-x-reading-modal-backdrop');
        var xReadingClose = document.getElementById('pos-x-reading-modal-close');
        var xReadingCloseBtn = document.getElementById('pos-x-reading-modal-close-btn');
        if (xReadingBackdrop) xReadingBackdrop.addEventListener('click', closeXReadingModal);
        if (xReadingClose) xReadingClose.addEventListener('click', closeXReadingModal);
        if (xReadingCloseBtn) xReadingCloseBtn.addEventListener('click', closeXReadingModal);
        var xReadingPrintBtn = document.getElementById('pos-x-reading-print-btn');
        if (xReadingPrintBtn) xReadingPrintBtn.addEventListener('click', function () {
            if (!lastXReadingData || !lastXReadingData.id) return;
            var url = dashboardBase + '/pos/x-reading-print?x_reading_id=' + encodeURIComponent(lastXReadingData.id);
            window.open(url, 'pos_x_reading_print', 'width=420,height=700,scrollbars=yes');
            axios.patch(apiBase + '/pos/x-reading/' + lastXReadingData.id + '/printed', {}, headers).catch(function () {});
        });
        var applyFiltersBtn = document.getElementById('pos-sales-history-apply-filters');
        if (applyFiltersBtn) applyFiltersBtn.addEventListener('click', function () {
            salesHistoryFilters.search = (document.getElementById('pos-sales-history-search').value || '').trim();
            salesHistoryFilters.date_from = (document.getElementById('pos-sales-history-date-from').value || '').trim();
            salesHistoryFilters.date_to = (document.getElementById('pos-sales-history-date-to').value || '').trim();
            salesHistoryFilters.status = document.getElementById('pos-sales-history-status').value || 'all';
            salesHistoryFilters.page = 1;
            salesHistoryFilters.per_page = parseInt(document.getElementById('pos-sales-history-per-page').value, 10) || 25;
            loadSalesHistory();
        });
        var clearFiltersBtn = document.getElementById('pos-sales-history-clear-filters');
        if (clearFiltersBtn) clearFiltersBtn.addEventListener('click', function () {
            document.getElementById('pos-sales-history-search').value = '';
            document.getElementById('pos-sales-history-date-from').value = '';
            document.getElementById('pos-sales-history-date-to').value = '';
            document.getElementById('pos-sales-history-status').value = 'all';
            salesHistoryFilters.search = '';
            salesHistoryFilters.date_from = '';
            salesHistoryFilters.date_to = '';
            salesHistoryFilters.status = 'all';
            salesHistoryFilters.page = 1;
            loadSalesHistory();
        });
        var perPageSelect = document.getElementById('pos-sales-history-per-page');
        if (perPageSelect) perPageSelect.addEventListener('change', function () {
            salesHistoryFilters.per_page = parseInt(this.value, 10) || 25;
            salesHistoryFilters.page = 1;
            loadSalesHistory();
        });
        var prevBtn = document.getElementById('pos-sales-history-prev');
        var nextBtn = document.getElementById('pos-sales-history-next');
        if (prevBtn) prevBtn.addEventListener('click', function () { if (salesHistoryMeta && salesHistoryMeta.current_page > 1) { salesHistoryFilters.page = salesHistoryMeta.current_page - 1; loadSalesHistory(); } });
        if (nextBtn) nextBtn.addEventListener('click', function () { if (salesHistoryMeta && salesHistoryMeta.current_page < salesHistoryMeta.last_page) { salesHistoryFilters.page = salesHistoryMeta.current_page + 1; loadSalesHistory(); } });
        document.querySelectorAll('.pos-sales-th-or, .pos-sales-th-date, .pos-sales-th-total, .pos-sales-th-status').forEach(function (th) {
            if (!th.getAttribute('data-sort')) return;
            th.addEventListener('click', function () {
                var col = this.getAttribute('data-sort');
                if (salesHistoryFilters.sort === col) salesHistoryFilters.dir = salesHistoryFilters.dir === 'asc' ? 'desc' : 'asc';
                else { salesHistoryFilters.sort = col; salesHistoryFilters.dir = 'desc'; }
                loadSalesHistory();
            });
        });
        var exportBtn = document.getElementById('pos-sales-history-export-btn');
        var exportDropdown = document.getElementById('pos-sales-history-export-dropdown');
        if (exportBtn && exportDropdown) {
            exportBtn.addEventListener('click', function (e) { e.stopPropagation(); exportDropdown.classList.toggle('hidden'); });
            document.addEventListener('click', function () { exportDropdown.classList.add('hidden'); });
        }
        document.querySelectorAll('.pos-sales-export-option').forEach(function (opt) {
            opt.addEventListener('click', function (e) {
                e.stopPropagation();
                var format = this.getAttribute('data-format');
                if (format === 'csv') exportSalesHistoryCsv();
                if (format === 'pdf') exportSalesHistoryPdf();
                if (exportDropdown) exportDropdown.classList.add('hidden');
            });
        });
        var detailCloseBtn = document.getElementById('pos-sales-history-detail-close');
        if (detailCloseBtn) detailCloseBtn.addEventListener('click', function () {
            var panel = document.getElementById('pos-sales-history-detail-panel');
            if (panel) { panel.classList.add('hidden'); panel.classList.remove('sm:w-80'); panel.classList.add('w-0'); }
        });
        if (document.getElementById('pos-sales-void-modal-backdrop')) document.getElementById('pos-sales-void-modal-backdrop').addEventListener('click', closeSalesVoidModal);
        if (document.getElementById('pos-sales-void-cancel')) document.getElementById('pos-sales-void-cancel').addEventListener('click', closeSalesVoidModal);
        if (document.getElementById('pos-sales-void-confirm')) document.getElementById('pos-sales-void-confirm').addEventListener('click', confirmSalesVoidModal);
        if (document.getElementById('pos-sales-reprint-preview-backdrop')) document.getElementById('pos-sales-reprint-preview-backdrop').addEventListener('click', closeSalesReprintPreviewModal);
        if (document.getElementById('pos-sales-reprint-preview-cancel')) document.getElementById('pos-sales-reprint-preview-cancel').addEventListener('click', closeSalesReprintPreviewModal);
        if (document.getElementById('pos-sales-reprint-preview-print')) document.getElementById('pos-sales-reprint-preview-print').addEventListener('click', doSalesReprintFromPreview);

        var reprintModalCancel = document.getElementById('pos-reprint-modal-cancel');
        var reprintModalBackdrop = document.getElementById('pos-reprint-modal-backdrop');
        var reprintSelect = document.getElementById('pos-reprint-select');
        var reprintDoBtn = document.getElementById('pos-reprint-do-btn');
        if (reprintModalCancel) reprintModalCancel.addEventListener('click', closeReprintModal);
        if (reprintModalBackdrop) reprintModalBackdrop.addEventListener('click', closeReprintModal);
        if (reprintSelect) reprintSelect.addEventListener('change', onReprintSelectChange);
        if (reprintDoBtn) reprintDoBtn.addEventListener('click', doReprintFromModal);
        var reprintModalEl = document.getElementById('pos-reprint-modal');
        if (reprintModalEl) reprintModalEl.addEventListener('keydown', function (e) { if (e.key === 'Escape') { e.preventDefault(); closeReprintModal(); } });

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
            var lastSearchValue = (searchInput.value || '').trim();
            function scheduleApplyFilters() {
                lastSearchValue = (searchInput.value || '').trim();
                if (searchTimer) clearTimeout(searchTimer);
                searchTimer = setTimeout(applyFilters, 250);
            }
            searchInput.addEventListener('input', scheduleApplyFilters);
            searchInput.addEventListener('change', scheduleApplyFilters);
            searchInput.addEventListener('keyup', scheduleApplyFilters);
            // Virtual keyboards (e.g. KioskBoard) set value programmatically and often do not fire input/change
            if (searchInput.classList.contains('js-kioskboard-input')) {
                setInterval(function () {
                    var current = (searchInput.value || '').trim();
                    if (current !== lastSearchValue) {
                        lastSearchValue = current;
                        if (searchTimer) clearTimeout(searchTimer);
                        searchTimer = setTimeout(applyFilters, 250);
                    }
                }, 350);
            }
        }

        var scanBtn = document.getElementById('pos-scan-btn');
        if (scanBtn) {
            scanBtn.addEventListener('click', function () {
                triggerBarcodeScan();
            });
        }

        document.querySelectorAll('.pos-category-chip').forEach(function (chip) {
            chip.addEventListener('click', function () {
                var cat = this.getAttribute('data-category') || '';
                document.querySelectorAll('.pos-category-chip').forEach(function (c) {
                    c.classList.remove('border-primary', 'bg-primary', 'text-white', 'font-semibold', 'shadow-sm', 'border-sky-500', 'bg-sky-500', 'border-emerald-500', 'bg-emerald-500', 'border-slate-500', 'bg-slate-200', 'dark:bg-darkmode-600', 'text-slate-700', 'dark:text-slate-300', 'border-amber-500', 'bg-amber-500');
                    c.classList.add('border-slate-200', 'dark:border-darkmode-500', 'bg-transparent', 'text-slate-500', 'dark:text-slate-400', 'font-medium');
                    var b = c.querySelector('.pos-tab-badge');
                    if (b) { b.classList.remove('bg-white/25'); b.classList.add('bg-slate-100', 'dark:bg-darkmode-600', 'text-slate-500', 'dark:text-slate-400'); }
                });
                if (cat === '') {
                    this.classList.add('border-primary', 'bg-primary', 'text-white', 'font-semibold', 'shadow-sm');
                } else if (cat === 'rx') {
                    this.classList.add('border-sky-500', 'bg-sky-500', 'text-white', 'font-semibold', 'shadow-sm');
                } else if (cat === 'otc') {
                    this.classList.add('border-emerald-500', 'bg-emerald-500', 'text-white', 'font-semibold', 'shadow-sm');
                } else if (cat === 'supplies') {
                    this.classList.add('border-slate-500', 'bg-slate-200', 'dark:bg-darkmode-600', 'text-slate-700', 'dark:text-slate-300', 'font-semibold', 'shadow-sm');
                } else if (cat === 'favorites') {
                    this.classList.add('border-amber-500', 'bg-amber-500', 'text-white', 'font-semibold', 'shadow-sm');
                } else {
                    this.classList.add('border-primary', 'bg-primary', 'text-white', 'font-semibold', 'shadow-sm');
                }
                this.classList.remove('border-slate-200', 'dark:border-darkmode-500', 'bg-transparent', 'text-slate-500', 'dark:text-slate-400', 'font-medium');
                var badge = this.querySelector('.pos-tab-badge');
                if (badge) { badge.classList.add('bg-white/25'); badge.classList.remove('bg-slate-100', 'dark:bg-darkmode-600', 'text-slate-500', 'dark:text-slate-400'); }
                currentCategory = cat;
                applyFilters();
            });
        });

        var completeBtn = document.getElementById('pos-complete-sale-btn');
        if (completeBtn) {
            completeBtn.addEventListener('click', handleCompleteSaleClick);
        }

        var shortcutsTrigger = document.getElementById('pos-shortcuts-trigger');
        var shortcutsPanel = document.getElementById('pos-shortcuts-panel');
        var shortcutsWrap = document.getElementById('pos-shortcuts-dropdown-wrap');
        var shortcutsChevron = shortcutsWrap && shortcutsWrap.querySelector('.pos-shortcuts-chevron');
        function positionShortcutsPanel() {
            if (!shortcutsPanel || !shortcutsTrigger) return;
            var rect = shortcutsTrigger.getBoundingClientRect();
            var panelW = 224;
            var left = Math.min(rect.right - panelW, window.innerWidth - panelW - 8);
            if (left < 8) left = 8;
            shortcutsPanel.style.left = left + 'px';
            shortcutsPanel.style.top = (rect.bottom + 6) + 'px';
        }
        function closeShortcutsPanel() {
            if (!shortcutsPanel || !shortcutsWrap) return;
            shortcutsPanel.classList.add('hidden');
            if (shortcutsPanel.parentNode === document.body) {
                document.body.removeChild(shortcutsPanel);
                shortcutsWrap.appendChild(shortcutsPanel);
            }
            if (shortcutsTrigger) shortcutsTrigger.setAttribute('aria-expanded', 'false');
            if (shortcutsChevron) shortcutsChevron.classList.remove('rotate-180');
        }
        if (shortcutsTrigger && shortcutsPanel && shortcutsWrap) {
            shortcutsTrigger.addEventListener('click', function (e) {
                e.stopPropagation();
                var isOpen = !shortcutsPanel.classList.contains('hidden');
                if (isOpen) {
                    closeShortcutsPanel();
                } else {
                    if (shortcutsPanel.parentNode !== document.body) {
                        shortcutsWrap.removeChild(shortcutsPanel);
                        document.body.appendChild(shortcutsPanel);
                    }
                    positionShortcutsPanel();
                    shortcutsPanel.classList.remove('hidden');
                    shortcutsTrigger.setAttribute('aria-expanded', 'true');
                    if (shortcutsChevron) shortcutsChevron.classList.add('rotate-180');
                }
            });
            document.addEventListener('click', function () {
                if (!shortcutsPanel.classList.contains('hidden')) closeShortcutsPanel();
            });
            window.addEventListener('resize', function () {
                if (!shortcutsPanel.classList.contains('hidden')) positionShortcutsPanel();
            });
        }

        document.addEventListener('keydown', function (e) {
            var tag = document.activeElement && document.activeElement.tagName ? document.activeElement.tagName.toUpperCase() : '';
            var inInput = tag === 'INPUT' || tag === 'TEXTAREA' || (document.activeElement && document.activeElement.isContentEditable);
            if (inInput && e.key !== 'F3' && e.key !== 'F9' && e.key !== 'F10' && e.key !== 'F11') return;
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
            } else if (e.key === 'F9') {
                e.preventDefault();
                openReprintModal();
            } else if (e.key === 'F10') {
                e.preventDefault();
                var lockBtn = document.getElementById('pos-bottom-lock');
                if (lockBtn) lockBtn.click();
            } else if (e.key === 'F11') {
                e.preventDefault();
                openSalesHistoryModal();
            }
        });
    })();

    // KioskBoard must be initialized before any code that may call KioskBoard.run() (e.g. renderCart, split modal)
    if (POS_TOUCHSCREEN && typeof KioskBoard !== 'undefined') {
        var kioskTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        KioskBoard.init({
            keysJsonUrl: '{{ asset("js/kioskboard-keys-english.json") }}',
            language: 'en',
            theme: kioskTheme,
            allowRealKeyboard: false,
            allowMobileKeyboard: false,
            cssAnimations: true,
            cssAnimationsDuration: 280,
            cssAnimationsStyle: 'slide',
            keysAllowSpacebar: true,
            keysSpacebarText: 'Space',
            keysFontFamily: 'sans-serif',
            keysFontSize: '22px',
            keysFontWeight: 'normal',
            autoScroll: false,
            capsLockActive: false,
            keysEnterCanClose: true,
            keysEnterCallback: function () {
                var modalVisible = function (id) {
                    var m = document.getElementById(id);
                    return m && !m.classList.contains('hidden');
                };
                if (modalVisible('pos-void-modal')) {
                    var btn = document.getElementById('pos-void-modal-confirm');
                    if (btn) { btn.click(); return; }
                }
                if (modalVisible('pos-sc-pwd-modal')) {
                    var btn = document.getElementById('pos-sc-pwd-modal-apply');
                    if (btn) { btn.click(); return; }
                }
                if (modalVisible('pos-payment-modal')) {
                    var btn = document.getElementById('pos-payment-modal-apply');
                    if (btn) { btn.click(); return; }
                }
                if (modalVisible('pos-split-payment-modal')) {
                    var btn = document.getElementById('pos-split-payment-apply');
                    if (btn) { btn.click(); return; }
                }
                if (modalVisible('pos-sales-void-modal')) {
                    var btn = document.getElementById('pos-sales-void-confirm');
                    if (btn) { btn.click(); return; }
                }
                if (modalVisible('pos-rx-modal')) {
                    var btn = document.getElementById('pos-rx-modal-apply');
                    if (btn) { btn.click(); return; }
                }
                if (modalVisible('pos-sales-history-modal')) {
                    var btn = document.getElementById('pos-sales-history-apply-filters');
                    if (btn) { btn.click(); return; }
                }
                var swalPopup = document.querySelector('.swal2-popup');
                if (swalPopup && swalPopup.offsetParent !== null) {
                    var swalConfirm = swalPopup.querySelector('.swal2-confirm');
                    if (swalConfirm && !swalConfirm.disabled) { swalConfirm.click(); return; }
                }
                var active = document.activeElement;
                if (active && active.id === 'pos-search-input') {
                    if (typeof applyFilters === 'function') applyFilters();
                    return;
                }
                if (active && active.classList && active.classList.contains('pos-cart-qty-input')) {
                    active.blur();
                }
            }
        });
        KioskBoard.run('.js-kioskboard-input');
    }

    // Initial load: restore cart from localStorage, then load terminal and products
    loadCartFromStorage();
    loadTerminalAndProducts();
})();
</script>
@endpush

