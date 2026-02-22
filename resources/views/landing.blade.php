@php
    $base = asset('midone-html.vercel.app');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Landogz POS - Secure, affordable POS for food and retail. Multi-branch, inventory, real-time reports. Request a quote or get a demo.">
    <title>Secure &amp; Affordable POS for Food &amp; Retail | Landogz POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#1e3a8a',
                        'primary-dark': '#1e40af',
                        darkmode: {
                            600: '#334155',
                            700: '#1e293b',
                            800: '#0f172a',
                            900: '#020617',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="{{ $base }}/dist/css/app.css" onerror="this.remove()">
    <style>
        html { scroll-behavior: smooth; }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .landing-reveal { opacity: 1 !important; transform: none !important; }
        }
        .landing-nav-link { color: #475569; transition: color 0.2s ease; }
        .landing-nav-link:hover { color: #0f172a; }
        .dark .landing-nav-link { color: #94a3b8; }
        .dark .landing-nav-link:hover { color: #f1f5f9; }
        /* Scroll-triggered reveal */
        .landing-reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        .landing-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        .landing-reveal-delay-1 { transition-delay: 0.1s; }
        .landing-reveal-delay-2 { transition-delay: 0.2s; }
        .landing-reveal-delay-3 { transition-delay: 0.3s; }
        .landing-reveal-delay-4 { transition-delay: 0.4s; }
        .landing-reveal-delay-5 { transition-delay: 0.5s; }
        .landing-reveal-delay-6 { transition-delay: 0.6s; }
        /* Button/link transitions */
        a[href], button { transition: opacity 0.2s ease, background-color 0.2s ease, border-color 0.2s ease, transform 0.2s ease; }
        a[href]:active, button:active { transform: scale(0.98); }
        /* Mobile menu */
        .landing-nav-open { max-height: 90vh; opacity: 1; }
        .landing-nav-closed { max-height: 0; opacity: 0; overflow: hidden; }
        /* Desktop dropdowns */
        .landing-dropdown-panel { opacity: 0; visibility: hidden; transform: translateY(-4px); transition: opacity 0.2s ease, visibility 0.2s ease, transform 0.2s ease; }
        .landing-dropdown:hover .landing-dropdown-panel { opacity: 1; visibility: visible; transform: translateY(0); }
    </style>
</head>
<body class="antialiased bg-white text-slate-800 dark:bg-darkmode-900 dark:text-slate-200">
    <!-- Main header: one bar, short menu with dropdowns -->
    <header class="sticky top-0 z-50 border-b border-slate-200 dark:border-darkmode-700 bg-white/95 dark:bg-darkmode-900/95 backdrop-blur">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-14 sm:h-16 items-center justify-between gap-4">
                <a href="{{ url('/') }}" class="flex shrink-0 items-center gap-2">
                    <img class="h-8 w-8" src="{{ asset('images/logo.png') }}" alt="Landogz POS">
                    <span class="text-base sm:text-lg font-semibold text-slate-800 dark:text-slate-100">Landogz POS</span>
                </a>
                <!-- Desktop: short nav + dropdowns -->
                <nav class="hidden lg:flex items-center gap-0.5">
                    <a href="{{ url('/') }}#why" class="landing-nav-link px-3 py-2 text-sm font-medium rounded-md hover:bg-slate-100 dark:hover:bg-darkmode-700">Why Landogz</a>
                    <a href="{{ url('/') }}#features" class="landing-nav-link px-3 py-2 text-sm font-medium rounded-md hover:bg-slate-100 dark:hover:bg-darkmode-700">Features</a>
                    <a href="{{ url('/') }}#how-it-works" class="landing-nav-link px-3 py-2 text-sm font-medium rounded-md hover:bg-slate-100 dark:hover:bg-darkmode-700">How it works</a>
                    <div class="landing-dropdown relative">
                        <button type="button" class="landing-nav-link flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-md hover:bg-slate-100 dark:hover:bg-darkmode-700" aria-haspopup="true" aria-expanded="false">
                            Solutions
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="landing-dropdown-panel absolute left-0 top-full pt-1 min-w-[160px] rounded-lg border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-lg py-1 z-10">
                            <a href="{{ url('/') }}#solutions" class="landing-nav-link block px-4 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-darkmode-700">Landogz Food</a>
                            <a href="{{ url('/') }}#solutions" class="landing-nav-link block px-4 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-darkmode-700">Landogz Retail</a>
                        </div>
                    </div>
                    <a href="{{ url('/') }}#comparison" class="landing-nav-link px-3 py-2 text-sm font-medium rounded-md hover:bg-slate-100 dark:hover:bg-darkmode-700">Comparison</a>
                    <a href="{{ url('/') }}#who-we-serve" class="landing-nav-link px-3 py-2 text-sm font-medium rounded-md hover:bg-slate-100 dark:hover:bg-darkmode-700">Who We Serve</a>
                    <div class="landing-dropdown relative">
                        <button type="button" class="landing-nav-link flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-md hover:bg-slate-100 dark:hover:bg-darkmode-700" aria-haspopup="true" aria-expanded="false">
                            Company
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="landing-dropdown-panel absolute left-0 top-full pt-1 min-w-[160px] rounded-lg border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-lg py-1 z-10">
                            <a href="{{ url('/') }}#blog" class="landing-nav-link block px-4 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-darkmode-700">Blog</a>
                            <a href="{{ url('/') }}#about" class="landing-nav-link block px-4 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-darkmode-700">About Us</a>
                            <a href="{{ url('/') }}#contact" class="landing-nav-link block px-4 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-darkmode-700">Contact Us</a>
                        </div>
                    </div>
                    <span class="mx-1 w-px h-5 bg-slate-200 dark:bg-darkmode-600" aria-hidden="true"></span>
                    <a href="{{ route('dashboard.login') }}" class="landing-nav-link px-3 py-2 text-sm font-medium rounded-md hover:bg-slate-100 dark:hover:bg-darkmode-700">Request a Quote</a>
                    <a href="{{ route('dashboard.login') }}" class="ml-1 inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white hover:opacity-95">Get a Demo</a>
                </nav>
                <!-- Mobile: hamburger + CTA -->
                <div class="flex lg:hidden items-center gap-2">
                    <button type="button" id="landing-nav-toggle" class="landing-nav-link p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-darkmode-700" aria-label="Menu">
                        <svg id="landing-nav-icon-open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg id="landing-nav-icon-close" class="h-6 w-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <a href="{{ route('dashboard.login') }}" class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white">Get a Demo</a>
                </div>
            </div>
            <!-- Mobile menu panel -->
            <div id="landing-nav-menu" class="landing-nav-closed lg:hidden transition-all duration-300 ease-out">
                <div class="py-4 border-t border-slate-200 dark:border-darkmode-700">
                    <div class="flex flex-col gap-0.5">
                        <a href="{{ url('/') }}#why" class="landing-nav-link block px-4 py-3 text-sm font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-darkmode-700">Why Landogz</a>
                        <a href="{{ url('/') }}#features" class="landing-nav-link block px-4 py-3 text-sm font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-darkmode-700">Features</a>
                        <a href="{{ url('/') }}#how-it-works" class="landing-nav-link block px-4 py-3 text-sm font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-darkmode-700">How it works</a>
                        <a href="{{ url('/') }}#solutions" class="landing-nav-link block px-4 py-3 text-sm font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-darkmode-700">Landogz Food</a>
                        <a href="{{ url('/') }}#solutions" class="landing-nav-link block px-4 py-3 text-sm font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-darkmode-700">Landogz Retail</a>
                        <a href="{{ url('/') }}#comparison" class="landing-nav-link block px-4 py-3 text-sm font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-darkmode-700">POS Comparison</a>
                        <a href="{{ url('/') }}#who-we-serve" class="landing-nav-link block px-4 py-3 text-sm font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-darkmode-700">Who We Serve</a>
                        <a href="{{ url('/') }}#blog" class="landing-nav-link block px-4 py-3 text-sm font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-darkmode-700">Blog</a>
                        <a href="{{ url('/') }}#about" class="landing-nav-link block px-4 py-3 text-sm font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-darkmode-700">About Us</a>
                        <a href="{{ url('/') }}#contact" class="landing-nav-link block px-4 py-3 text-sm font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-darkmode-700">Contact Us</a>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-200 dark:border-darkmode-700 flex flex-col gap-2 px-4">
                        <a href="{{ route('dashboard.login') }}" class="landing-nav-link text-center py-3 rounded-lg border border-slate-300 dark:border-darkmode-600 text-sm font-medium">Request a Quote</a>
                        <a href="{{ route('dashboard.login') }}" class="text-center py-3 rounded-lg bg-primary text-white text-sm font-semibold">Get a Demo</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="relative bg-white dark:bg-darkmode-900">
        <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 bg-cover bg-center bg-no-repeat min-h-[420px] sm:min-h-[480px] lg:min-h-[520px] flex items-center" style="background-image: url('{{ asset('images/banner01.webp') }}');">
            <div class="absolute inset-0 bg-white/80 dark:bg-darkmode-900/80" aria-hidden="true"></div>
            <div class="landing-reveal is-visible max-w-3xl mx-auto text-center relative z-10">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-slate-900 dark:text-white tracking-tight">
                    Secure &amp; affordable POS for food and retail
                </h1>
                <p class="mt-6 text-lg text-slate-600 dark:text-slate-400 leading-relaxed">
                    All-in-one solution: sales, inventory, and multi-branch in one place. Real-time data, no lock-in, API-ready.
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-6 text-sm text-slate-500 dark:text-slate-400">
                    <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-primary"></span> Multi-branch</span>
                    <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-primary"></span> BIR-ready receipts</span>
                    <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-primary"></span> API for web &amp; mobile</span>
                </div>
                <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ route('dashboard.login') }}" class="inline-flex items-center justify-center rounded-md bg-primary px-6 py-3 text-base font-semibold text-white hover:opacity-95">Get a Demo</a>
                    <a href="{{ url('/') }}#why" class="inline-flex items-center justify-center rounded-md border border-slate-300 dark:border-darkmode-600 px-6 py-3 text-base font-medium landing-nav-link hover:bg-slate-50 dark:hover:bg-darkmode-800">Why Landogz</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Landogz -->
    <section id="why" class="py-20 lg:py-24 bg-slate-50 dark:bg-darkmode-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Why Landogz POS</h2>
                <p class="mt-3 text-slate-600 dark:text-slate-400">One system that grows with you.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
                <div class="landing-reveal landing-reveal-delay-1 bg-white dark:bg-darkmode-900 rounded-xl p-6 border border-slate-200 dark:border-darkmode-700 transition-shadow duration-300 hover:shadow-lg">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-slate-900 dark:text-white">Flexible &amp; scalable</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Start with one branch, scale to many. No lock-in.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-2 bg-white dark:bg-darkmode-900 rounded-xl p-6 border border-slate-200 dark:border-darkmode-700 transition-shadow duration-300 hover:shadow-lg">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-slate-900 dark:text-white">Real-time data</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Track sales, costs, and product mix in a few clicks.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-3 bg-white dark:bg-darkmode-900 rounded-xl p-6 border border-slate-200 dark:border-darkmode-700 transition-shadow duration-300 hover:shadow-lg">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-slate-900 dark:text-white">Reliable &amp; simple</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Clear workflows, fewer errors, stable daily use.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features: What the POS does -->
    <section id="features" class="py-20 lg:py-24 bg-white dark:bg-darkmode-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Everything you need to run your business</h2>
                <p class="mt-3 text-slate-600 dark:text-slate-400">Sales, inventory, branches, and reports in one POS platform.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="landing-reveal landing-reveal-delay-1 bg-slate-50 dark:bg-darkmode-800 rounded-xl p-6 border border-slate-200 dark:border-darkmode-700 transition-shadow duration-300 hover:shadow-md">
                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-slate-900 dark:text-white">Sales &amp; checkout</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Fast transactions, multiple payment methods (cash, card, other), and optional discount. Each sale links to a cashier and terminal for full traceability.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-2 bg-slate-50 dark:bg-darkmode-800 rounded-xl p-6 border border-slate-200 dark:border-darkmode-700 transition-shadow duration-300 hover:shadow-md">
                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8 4-8-4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-slate-900 dark:text-white">Inventory &amp; batches</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Products with barcodes, units, and reorder levels. Track stock by batch and expiry; get low-stock and expiring-soon alerts so you never run out or waste.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-3 bg-slate-50 dark:bg-darkmode-800 rounded-xl p-6 border border-slate-200 dark:border-darkmode-700 transition-shadow duration-300 hover:shadow-md">
                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-slate-900 dark:text-white">Multi-branch</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Manage multiple locations from one system. Per-branch products and stock, branch-level and head-office dashboards, and role-based access so each team sees what they need.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-4 bg-slate-50 dark:bg-darkmode-800 rounded-xl p-6 border border-slate-200 dark:border-darkmode-700 transition-shadow duration-300 hover:shadow-md">
                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-slate-900 dark:text-white">Reports &amp; dashboard</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Sales today, transaction counts, low-stock and expiring-soon summaries. Super admins get a branch-overview; managers and cashiers see their branch only.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-5 bg-slate-50 dark:bg-darkmode-800 rounded-xl p-6 border border-slate-200 dark:border-darkmode-700 transition-shadow duration-300 hover:shadow-md">
                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-slate-900 dark:text-white">Receipts &amp; BIR</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Official receipts with OR numbers, BIR-style data for compliance, and reprint logging. Configure TIN and accreditation per branch for audit-ready records.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-6 bg-slate-50 dark:bg-darkmode-800 rounded-xl p-6 border border-slate-200 dark:border-darkmode-700 transition-shadow duration-300 hover:shadow-md">
                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-slate-900 dark:text-white">API &amp; integrations</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">REST API with token auth for your own apps, mobile clients, or reporting tools. Same APIs power our web dashboard so you can build custom workflows and integrations.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section id="how-it-works" class="py-20 lg:py-24 bg-slate-50 dark:bg-darkmode-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">How it works</h2>
                <p class="mt-3 text-slate-600 dark:text-slate-400">From setup to daily sales in four steps.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 max-w-5xl mx-auto">
                <div class="landing-reveal landing-reveal-delay-1 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary text-white font-bold text-lg">1</div>
                    <h3 class="mt-4 font-semibold text-slate-900 dark:text-white">Set up branches</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Add your locations and assign users. Each branch has its own products and stock.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-2 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary text-white font-bold text-lg">2</div>
                    <h3 class="mt-4 font-semibold text-slate-900 dark:text-white">Add products</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Create products, set prices and reorder levels, and manage batches and expiry.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-3 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary text-white font-bold text-lg">3</div>
                    <h3 class="mt-4 font-semibold text-slate-900 dark:text-white">Run sales</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Cashiers use the POS to ring up sales. Stock updates automatically; receipts are ready for BIR.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-4 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary text-white font-bold text-lg">4</div>
                    <h3 class="mt-4 font-semibold text-slate-900 dark:text-white">View &amp; grow</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Check dashboards, low-stock alerts, and branch overview. Use the API to connect more tools.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Solutions: Food & Retail -->
    <section id="solutions" class="py-20 lg:py-24 bg-white dark:bg-darkmode-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">POS solutions by industry</h2>
                <p class="mt-3 text-slate-600 dark:text-slate-400">Built for food and retail with the features you need.</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <a href="{{ route('dashboard.login') }}" class="landing-reveal landing-reveal-delay-1 group flex flex-col sm:flex-row items-start sm:items-center gap-6 p-8 rounded-xl border border-slate-200 dark:border-darkmode-700 hover:border-primary/30 hover:bg-slate-50/50 dark:hover:bg-darkmode-800 transition-all duration-300">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:bg-primary/20">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Landogz for Food</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Ideal for cafés, restaurants, and food kiosks. Ring up orders, apply discounts, and print receipts. Track sales by cashier and terminal; manage products and batches with expiry for ingredients.</p>
                        <span class="mt-4 inline-flex items-center text-sm font-medium text-primary">Get a demo →</span>
                    </div>
                </a>
                <a href="{{ route('dashboard.login') }}" class="landing-reveal landing-reveal-delay-2 group flex flex-col sm:flex-row items-start sm:items-center gap-6 p-8 rounded-xl border border-slate-200 dark:border-darkmode-700 hover:border-primary/30 hover:bg-slate-50/50 dark:hover:bg-darkmode-800 transition-all duration-300">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:bg-primary/20">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Landogz for Retail</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Built for boutiques, pharmacies, convenience stores, and supermarkets. Products with barcodes, batch and expiry tracking, low-stock alerts, and multi-branch inventory. BIR-ready receipts and role-based access.</p>
                        <span class="mt-4 inline-flex items-center text-sm font-medium text-primary">Get a demo →</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- POS Comparison / What you get -->
    <section id="comparison" class="py-20 lg:py-24 bg-slate-50 dark:bg-darkmode-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">What you get with Landogz POS</h2>
                <p class="mt-3 text-slate-600 dark:text-slate-400">A clear comparison: one system for sales, stock, branches, and compliance.</p>
            </div>
            <div class="landing-reveal landing-reveal-delay-1 max-w-4xl mx-auto rounded-xl border border-slate-200 dark:border-darkmode-700 bg-white dark:bg-darkmode-900 overflow-hidden transition-shadow duration-300 hover:shadow-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-darkmode-700 bg-slate-50 dark:bg-darkmode-800">
                                <th class="text-left py-4 px-6 font-semibold text-slate-900 dark:text-white">Feature</th>
                                <th class="text-left py-4 px-6 font-semibold text-slate-900 dark:text-white">Landogz POS</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-600 dark:text-slate-400">
                            <tr class="border-b border-slate-100 dark:border-darkmode-700"><td class="py-3 px-6">Multi-branch management</td><td class="py-3 px-6 text-primary font-medium">✓ Included</td></tr>
                            <tr class="border-b border-slate-100 dark:border-darkmode-700"><td class="py-3 px-6">Real-time sales &amp; inventory</td><td class="py-3 px-6 text-primary font-medium">✓ Included</td></tr>
                            <tr class="border-b border-slate-100 dark:border-darkmode-700"><td class="py-3 px-6">Product batches &amp; expiry tracking</td><td class="py-3 px-6 text-primary font-medium">✓ Included</td></tr>
                            <tr class="border-b border-slate-100 dark:border-darkmode-700"><td class="py-3 px-6">Low-stock &amp; expiring-soon alerts</td><td class="py-3 px-6 text-primary font-medium">✓ Included</td></tr>
                            <tr class="border-b border-slate-100 dark:border-darkmode-700"><td class="py-3 px-6">BIR-ready official receipts</td><td class="py-3 px-6 text-primary font-medium">✓ Included</td></tr>
                            <tr class="border-b border-slate-100 dark:border-darkmode-700"><td class="py-3 px-6">Role-based access (Super Admin, Manager, Cashier)</td><td class="py-3 px-6 text-primary font-medium">✓ Included</td></tr>
                            <tr class="border-b border-slate-100 dark:border-darkmode-700"><td class="py-3 px-6">Dashboard &amp; branch overview</td><td class="py-3 px-6 text-primary font-medium">✓ Included</td></tr>
                            <tr class="border-b border-slate-100 dark:border-darkmode-700"><td class="py-3 px-6">REST API for web &amp; mobile</td><td class="py-3 px-6 text-primary font-medium">✓ Included</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-slate-200 dark:border-darkmode-700">
                    <a href="{{ route('dashboard.login') }}" class="inline-flex items-center text-sm font-semibold text-primary hover:underline">Get a demo →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Who We Serve -->
    <section id="who-we-serve" class="py-20 lg:py-24 bg-white dark:bg-darkmode-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Who we serve</h2>
                <p class="mt-3 text-slate-600 dark:text-slate-400">Retail and food businesses of every size, from single store to multi-branch.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
                <div class="landing-reveal landing-reveal-delay-1 rounded-xl border border-slate-200 dark:border-darkmode-700 p-5 hover:border-primary/20 transition-colors">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Retail &amp; boutiques</h3>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Products, categories, and stock. Fast checkout and daily sales reports.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-2 rounded-xl border border-slate-200 dark:border-darkmode-700 p-5 hover:border-primary/20 transition-colors">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Restaurants &amp; cafés</h3>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Orders, payments, and receipts. Track sales by cashier and terminal.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-3 rounded-xl border border-slate-200 dark:border-darkmode-700 p-5 hover:border-primary/20 transition-colors">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Pharmacies</h3>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Batch and expiry tracking, low-stock alerts, and compliant receipts.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-4 rounded-xl border border-slate-200 dark:border-darkmode-700 p-5 hover:border-primary/20 transition-colors">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Convenience stores</h3>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Quick sales, multiple payment methods, and inventory per branch.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-5 rounded-xl border border-slate-200 dark:border-darkmode-700 p-5 hover:border-primary/20 transition-colors">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Supermarkets</h3>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Many products, barcodes, and central or per-branch reporting.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-6 rounded-xl border border-slate-200 dark:border-darkmode-700 p-5 hover:border-primary/20 transition-colors">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Multi-branch chains</h3>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">One platform for all locations. Branch-level and head-office dashboards.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- What's included / Support -->
    <section id="support" class="py-20 lg:py-24 bg-slate-50 dark:bg-darkmode-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">What&rsquo;s included</h2>
                <p class="mt-3 text-slate-600 dark:text-slate-400">From setup to daily use: what you get with Landogz POS.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <div class="landing-reveal landing-reveal-delay-1 rounded-xl bg-white dark:bg-darkmode-900 border border-slate-200 dark:border-darkmode-700 p-6">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Multi-branch dashboard</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Central view of sales and activity. Per-branch stock and reports for managers.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-2 rounded-xl bg-white dark:bg-darkmode-900 border border-slate-200 dark:border-darkmode-700 p-6">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Real-time reports</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Sales today, transaction counts, low-stock and expiring-soon lists. No waiting for end-of-day.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-3 rounded-xl bg-white dark:bg-darkmode-900 border border-slate-200 dark:border-darkmode-700 p-6">
                    <h3 class="font-semibold text-slate-900 dark:text-white">API &amp; integrations</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">REST API with token auth. Build custom apps, mobile clients, or connect to your existing tools.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="py-20 lg:py-24 bg-white dark:bg-darkmode-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Frequently asked questions</h2>
                <p class="mt-3 text-slate-600 dark:text-slate-400">Quick answers about Landogz POS.</p>
            </div>
            <div class="max-w-2xl mx-auto space-y-6">
                <div class="landing-reveal landing-reveal-delay-1 rounded-xl border border-slate-200 dark:border-darkmode-700 p-5">
                    <h3 class="font-semibold text-slate-900 dark:text-white">What is Landogz POS?</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Landogz POS is an all-in-one point of sale system for food and retail. It handles sales, inventory (including batches and expiry), multiple branches, BIR-ready receipts, and real-time dashboards. An API lets you connect your own apps or mobile clients.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-2 rounded-xl border border-slate-200 dark:border-darkmode-700 p-5">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Can I use it for multiple branches?</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Yes. You set up each branch, assign users (managers, cashiers), and manage products and stock per location. Super admins see a branch overview; managers and cashiers see only their branch.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-3 rounded-xl border border-slate-200 dark:border-darkmode-700 p-5">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Are receipts BIR-compliant?</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">The system supports BIR-style official receipts with OR numbers, TIN, and accreditation data. You configure these per branch so your receipts are audit-ready.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-4 rounded-xl border border-slate-200 dark:border-darkmode-700 p-5">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Is there an API?</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Yes. A REST API with token authentication is available for products, transactions, dashboard data, and more. Use it for custom reporting, mobile apps, or integrations with your existing systems.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog placeholder -->
    <!-- Blog (static / coming soon) -->
    <section id="blog" class="py-16 lg:py-20 bg-slate-50 dark:bg-darkmode-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal text-center max-w-2xl mx-auto mb-10">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Blog</h2>
                <p class="mt-3 text-slate-600 dark:text-slate-400">Updates and tips for your POS. Coming soon.</p>
            </div>
            <div class="landing-reveal landing-reveal-delay-1 max-w-xl mx-auto">
                <div class="rounded-xl border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-900 p-8 sm:p-10 text-center">
                    <div class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                    <p class="mt-4 text-slate-600 dark:text-slate-400">We’re preparing articles and tips to help you get the most from your POS. Check back later.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us -->
    <section id="about" class="py-20 lg:py-24 bg-white dark:bg-darkmode-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal max-w-3xl mx-auto text-center">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">About Landogz POS</h2>
                <p class="mt-4 text-slate-600 dark:text-slate-400 leading-relaxed">Landogz POS is built for businesses that need a secure, affordable point of sale with multi-branch support, real-time data, and an API-ready platform. We focus on what matters: fast sales, accurate inventory, clear reports, and BIR-ready receipts—so you can run your stores and scale without lock-in. The same system works for retail and food, from single store to multi-branch chains.</p>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-20 lg:py-24 bg-primary dark:bg-darkmode-800">
        <div class="landing-reveal container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-white">Ready to get started?</h2>
            <p class="mt-3 text-white/90 max-w-md mx-auto">Request a quote or get a demo. We’ll help you find the right setup.</p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('dashboard.login') }}" class="inline-flex items-center justify-center rounded-md bg-white px-6 py-3 text-base font-semibold text-primary hover:bg-white/95">Request a Quote</a>
                <a href="{{ route('dashboard.login') }}" class="inline-flex items-center justify-center rounded-md border border-white/60 px-6 py-3 text-base font-medium text-white hover:bg-white/10">Get a Demo</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="border-t border-slate-200 dark:border-darkmode-700 bg-white dark:bg-darkmode-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-14">
            <div class="flex flex-col items-center text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2">
                    <img class="h-8 w-8" src="{{ asset('images/logo.png') }}" alt="Landogz POS">
                    <span class="text-lg font-semibold text-slate-900 dark:text-white">Landogz POS</span>
                </a>
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400 max-w-md">Secure, affordable POS for food and retail.</p>
                <nav class="mt-8 flex flex-wrap items-center justify-center gap-x-6 gap-y-1 text-sm">
                    <a href="{{ url('/') }}#why" class="text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary">Why Landogz</a>
                    <a href="{{ url('/') }}#features" class="text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary">Features</a>
                    <a href="{{ url('/') }}#how-it-works" class="text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary">How it works</a>
                    <a href="{{ url('/') }}#solutions" class="text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary">Solutions</a>
                    <a href="{{ url('/') }}#comparison" class="text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary">Comparison</a>
                    <a href="{{ url('/') }}#who-we-serve" class="text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary">Who We Serve</a>
                    <a href="{{ url('/') }}#blog" class="text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary">Blog</a>
                    <a href="{{ url('/') }}#about" class="text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary">About</a>
                    <a href="{{ url('/') }}#faq" class="text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary">FAQ</a>
                    <a href="{{ url('/') }}#contact" class="text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary">Contact</a>
                </nav>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ route('super-admin.login') }}" class="inline-flex items-center justify-center rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition-opacity">Get a Demo</a>
                    <a href="{{ route('super-admin.login') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 dark:border-darkmode-600 px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-800 transition-colors">Request a Quote</a>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-slate-200 dark:border-darkmode-700 text-center">
                <p class="text-sm text-slate-500 dark:text-slate-500">&copy; {{ date('Y') }} Landogz POS. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script>
        (function() {
            var toggle = document.getElementById('landing-nav-toggle');
            var menu = document.getElementById('landing-nav-menu');
            var iconOpen = document.getElementById('landing-nav-icon-open');
            var iconClose = document.getElementById('landing-nav-icon-close');
            if (toggle && menu && iconOpen && iconClose) {
                toggle.addEventListener('click', function() {
                    var isOpen = menu.classList.contains('landing-nav-open');
                    if (isOpen) {
                        menu.classList.remove('landing-nav-open');
                        menu.classList.add('landing-nav-closed');
                        iconOpen.classList.remove('hidden');
                        iconClose.classList.add('hidden');
                    } else {
                        menu.classList.remove('landing-nav-closed');
                        menu.classList.add('landing-nav-open');
                        iconOpen.classList.add('hidden');
                        iconClose.classList.remove('hidden');
                    }
                });
                menu.querySelectorAll('a').forEach(function(link) {
                    link.addEventListener('click', function() {
                        menu.classList.remove('landing-nav-open');
                        menu.classList.add('landing-nav-closed');
                        iconOpen.classList.remove('hidden');
                        iconClose.classList.add('hidden');
                    });
                });
            }
        })();
    </script>
    <script>
        (function() {
            var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (reducedMotion) {
                document.querySelectorAll('.landing-reveal').forEach(function(el) { el.classList.add('is-visible'); });
                return;
            }
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, { rootMargin: '0px 0px -40px 0px', threshold: 0.1 });
            document.querySelectorAll('.landing-reveal').forEach(function(el) {
                if (!el.classList.contains('is-visible')) observer.observe(el);
            });
        })();
    </script>
</body>
</html>
