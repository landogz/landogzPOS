@php
    $base = asset('midone-html.vercel.app');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="POS system for food and retail in the Philippines. Multi-branch, BIR-ready receipts, real-time inventory. Secure, affordable. Request a quote or get a demo — Landogz POS.">
    <title>POS System for Food &amp; Retail Philippines | Multi-Branch, BIR-Ready | Landogz POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#1e3a8a',
                        'primary-dark': '#1e40af',
                        accent: '#2563eb',
                        success: '#16a34a',
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
        /* FAQ accordion */
        .landing-faq-accordion .landing-faq-body { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
        .landing-faq-accordion.open .landing-faq-body { max-height: 500px; }
        .landing-faq-accordion .landing-faq-chevron { transition: transform 0.3s ease; }
        .landing-faq-accordion.open .landing-faq-chevron { transform: rotate(180deg); }
        .landing-faq-accordion .landing-faq-plus { transition: opacity 0.2s ease; }
        .landing-faq-accordion.open .landing-faq-plus { opacity: 0; pointer-events: none; }
        .landing-faq-accordion .landing-faq-minus { opacity: 0; transition: opacity 0.2s ease; }
        .landing-faq-accordion.open .landing-faq-minus { opacity: 1; }
        .landing-section-blue { background-color: #eef2ff; }
        .dark .landing-section-blue { background-color: rgba(30, 58, 138, 0.15); }
    </style>
</head>
<body class="antialiased bg-white text-slate-800 dark:bg-darkmode-900 dark:text-slate-200">
    @include('partials.landing-header')

    <!-- Hero -->
    <section class="relative overflow-hidden min-h-[520px] sm:min-h-[560px] lg:min-h-[640px]" style="background-image: url('{{ asset('images/banner01.webp') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        {{-- Layer 1: left-to-right — nearly solid on text side, still dark on image side --}}
        <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/78 to-black/55 dark:from-black/95 dark:via-black/88 dark:to-black/70" aria-hidden="true"></div>
        {{-- Layer 2: bottom vignette — ensures stats row is always readable --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent" aria-hidden="true"></div>
        <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 lg:py-28 flex flex-col lg:flex-row items-center justify-center gap-10 lg:gap-16 min-h-[520px] sm:min-h-[560px] lg:min-h-[640px]">
            <div class="landing-reveal is-visible relative z-10 flex-1 text-center lg:text-left max-w-2xl">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight">
                    POS for Food &amp; Retail — Secure, Affordable, BIR-Ready
                </h1>
                <p class="mt-6 text-xl text-white/85 leading-relaxed">
                    All-in-one: sales, inventory, and multi-branch. Real-time data, no lock-in, API-ready.
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center lg:justify-start gap-6 text-base text-white/75 font-medium">
                    <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-primary"></span> Multi-branch</span>
                    <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-primary"></span> BIR-ready receipts</span>
                    <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-primary"></span> API for web &amp; mobile</span>
                </div>
                <div class="mt-10 flex flex-wrap items-center justify-center lg:justify-start gap-4">
                    <a href="{{ route('dashboard.login') }}" class="inline-flex items-center justify-center rounded-xl bg-primary px-12 py-7 text-xl font-bold text-white shadow-2xl shadow-primary/40 hover:opacity-95 active:scale-[0.98] transition-all">Get a Demo</a>
                    <a href="{{ url('/') }}#why" class="inline-flex items-center justify-center rounded-xl border-2 border-white/50 px-12 py-7 text-xl font-bold text-white hover:bg-white/10 active:scale-[0.98] transition-all">Why Landogz</a>
                </div>
                <div class="mt-12 pt-8 border-t border-white/20 flex flex-wrap items-center justify-center lg:justify-start gap-10 text-center">
                    <div>
                        <span class="block text-5xl sm:text-6xl font-black text-white leading-none pb-1 border-b-4 border-primary">500+</span>
                        <span class="mt-2 block text-sm font-bold text-white/65 uppercase tracking-widest">Businesses</span>
                    </div>
                    <div>
                        <span class="block text-5xl sm:text-6xl font-black text-white leading-none pb-1 border-b-4 border-primary">BIR</span>
                        <span class="mt-2 block text-sm font-bold text-white/65 uppercase tracking-widest">Accredited</span>
                    </div>
                    <div>
                        <span class="block text-5xl sm:text-6xl font-black text-white leading-none pb-1 border-b-4 border-primary">24/7</span>
                        <span class="mt-2 block text-sm font-bold text-white/65 uppercase tracking-widest">Support</span>
                    </div>
                </div>
            </div>
            <div class="landing-reveal is-visible landing-reveal-delay-1 relative z-10 flex-1 w-full max-w-xl lg:max-w-[30rem] flex justify-center lg:justify-end">
                <div class="relative w-full">
                    <div class="relative aspect-[4/3] rounded-2xl border-4 border-white/20 bg-white dark:bg-darkmode-800 shadow-2xl overflow-hidden" style="box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.6);">
                        <img src="{{ asset('images/POS dashboard preview.webp') }}" alt="POS Dashboard" class="absolute inset-0 w-full h-full object-cover object-top">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Landogz -->
    <section id="why" class="py-20 lg:py-28 landing-section-blue dark:bg-darkmode-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#1A1A2E] dark:text-white">Why Landogz POS</h2>
                <p class="mt-4 text-lg text-slate-600 dark:text-slate-400">One system that grows with you—no lock-in, no hidden fees.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
                <div class="landing-reveal landing-reveal-delay-1 bg-white dark:bg-darkmode-900 rounded-2xl p-6 sm:p-8 border border-slate-200 dark:border-darkmode-700 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary text-white shadow-lg">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-slate-900 dark:text-white">Flexible &amp; scalable</h3>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Start with one branch and add more as you grow. Multi-branch support keeps inventory, sales, and reports in sync across locations. No long-term lock-in—your data stays yours, and you can scale up or adjust without being tied to a single vendor.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-2 bg-white dark:bg-darkmode-900 rounded-2xl p-6 sm:p-8 border border-slate-200 dark:border-darkmode-700 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-accent text-white shadow-lg">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-slate-900 dark:text-white">Real-time data</h3>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Track sales, costs, and product mix in a few clicks. Dashboards and reports update as transactions happen—see today’s totals, low-stock alerts, and branch performance without waiting for overnight batches. Make decisions using current numbers, not yesterday’s.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-3 bg-white dark:bg-darkmode-900 rounded-2xl p-6 sm:p-8 border border-slate-200 dark:border-darkmode-700 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-success text-white shadow-lg">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-slate-900 dark:text-white">Reliable &amp; simple</h3>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Clear workflows, fewer errors, and stable daily use. From checkout and receipts to inventory and BIR-ready reporting, the system is built for fast adoption and consistent operation. Your staff can focus on serving customers instead of fighting the till.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features: Everything you need (alternating layout) -->
    <section id="features" class="py-20 lg:py-28 bg-white dark:bg-darkmode-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#1A1A2E] dark:text-white">Everything you need to run your business</h2>
                <p class="mt-4 text-lg text-slate-600 dark:text-slate-400">Sales, inventory, branches, and reports in one POS platform.</p>
            </div>
            <!-- Row 1: image left, content right -->
            <div class="landing-reveal landing-reveal-delay-1 grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center max-w-6xl mx-auto mb-20">
                <div class="order-2 lg:order-1 rounded-2xl overflow-hidden border-2 border-primary/20 aspect-video shadow-lg bg-slate-100 dark:bg-darkmode-800">
                    <img src="{{ asset('images/POS dashboard preview.webp') }}" alt="POS dashboard – sales and checkout" class="w-full h-full object-cover object-top">
                </div>
                <div class="order-1 lg:order-2">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary text-white shadow-lg">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="mt-5 text-xl font-bold text-[#1A1A2E] dark:text-white">Sales &amp; checkout</h3>
                    <p class="mt-3 text-slate-600 dark:text-slate-400">Fast transactions, multiple payment methods, and optional discount. Each sale links to cashier and terminal for full traceability.</p>
                    <a href="{{ route('dashboard.login') }}" class="mt-4 inline-flex items-center text-sm font-semibold text-primary hover:underline">Learn more →</a>
                </div>
            </div>
            <!-- Row 2: content left, image right -->
            <div class="landing-reveal landing-reveal-delay-2 grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center max-w-6xl mx-auto mb-20">
                <div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-accent text-white shadow-lg">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8 4-8-4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <h3 class="mt-5 text-xl font-bold text-[#1A1A2E] dark:text-white">Inventory &amp; multi-branch</h3>
                    <p class="mt-3 text-slate-600 dark:text-slate-400">Products with barcodes, batch and expiry tracking. Manage multiple locations from one system with role-based access.</p>
                    <a href="{{ route('dashboard.login') }}" class="mt-4 inline-flex items-center text-sm font-semibold text-primary hover:underline">Learn more →</a>
                </div>
                <div class="rounded-2xl overflow-hidden border-2 border-primary/20 aspect-video shadow-lg bg-slate-100 dark:bg-darkmode-800">
                    <img src="{{ asset('images/Inventory & branches preview.webp') }}" alt="Inventory and multi-branch management" class="w-full h-full object-cover object-top">
                </div>
            </div>
            <!-- Row 3: image left, content right -->
            <div class="landing-reveal landing-reveal-delay-3 grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center max-w-6xl mx-auto">
                <div class="order-2 lg:order-1 rounded-2xl overflow-hidden border-2 border-primary/20 aspect-video shadow-lg bg-slate-100 dark:bg-darkmode-800">
                    <img src="{{ asset('images/Reports & receipts preview.webp') }}" alt="Reports, receipts and API" class="w-full h-full object-cover object-top">
                </div>
                <div class="order-1 lg:order-2">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-success text-white shadow-lg">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="mt-5 text-xl font-bold text-[#1A1A2E] dark:text-white">Reports, receipts &amp; API</h3>
                    <p class="mt-3 text-slate-600 dark:text-slate-400">Real-time dashboards, BIR-ready official receipts, and REST API for custom apps and integrations.</p>
                    <a href="{{ route('dashboard.login') }}" class="mt-4 inline-flex items-center text-sm font-semibold text-primary hover:underline">Learn more →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section id="how-it-works" class="py-20 lg:py-28 landing-section-blue dark:bg-darkmode-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#1A1A2E] dark:text-white">How it works</h2>
                <p class="mt-4 text-lg text-slate-600 dark:text-slate-400">From setup to daily sales in four steps.</p>
            </div>
            <div class="relative max-w-5xl mx-auto">
                <div class="hidden lg:block absolute top-8 left-0 right-0 h-0.5 bg-primary/30" style="left: 12.5%; right: 12.5%;"></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-6">
                    <div class="landing-reveal landing-reveal-delay-1 text-center relative">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-primary text-white font-bold text-2xl shadow-lg">1</div>
                        <h3 class="mt-5 font-bold text-[#1A1A2E] dark:text-white">Set up branches</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Add locations and assign users. Each branch has its own products and stock.</p>
                    </div>
                    <div class="landing-reveal landing-reveal-delay-2 text-center relative">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-primary text-white font-bold text-2xl shadow-lg">2</div>
                        <h3 class="mt-5 font-bold text-[#1A1A2E] dark:text-white">Add products</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Create products, set prices and reorder levels, manage batches and expiry.</p>
                    </div>
                    <div class="landing-reveal landing-reveal-delay-3 text-center relative">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-primary text-white font-bold text-2xl shadow-lg">3</div>
                        <h3 class="mt-5 font-bold text-[#1A1A2E] dark:text-white">Run sales</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Cashiers ring up sales. Stock updates automatically; receipts are BIR-ready.</p>
                    </div>
                    <div class="landing-reveal landing-reveal-delay-4 text-center relative">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-primary text-white font-bold text-2xl shadow-lg">4</div>
                        <h3 class="mt-5 font-bold text-[#1A1A2E] dark:text-white">View &amp; grow</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Dashboards, low-stock alerts, branch overview. Use the API to connect more tools.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Solutions: by industry -->
    <section id="solutions" class="py-20 lg:py-28 bg-white dark:bg-darkmode-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#1A1A2E] dark:text-white">POS solutions by industry</h2>
                <p class="mt-4 text-lg text-slate-600 dark:text-slate-400">Built for food, retail, and services.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
                <a href="{{ route('dashboard.login') }}" class="landing-reveal landing-reveal-delay-1 group flex items-start gap-6 p-8 rounded-2xl border-2 border-slate-200 dark:border-darkmode-700 border-t-4 border-t-amber-500 hover:border-amber-500 hover:shadow-xl hover:bg-amber-50/50 dark:hover:bg-amber-500/10 transition-all duration-300 bg-white dark:bg-darkmode-800">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-amber-500/20 text-amber-600 dark:text-amber-400 group-hover:bg-amber-500 group-hover:text-white transition-colors shadow-md">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div><h3 class="text-xl font-extrabold text-[#1A1A2E] dark:text-white">Food &amp; café</h3><p class="mt-2 text-slate-600 dark:text-slate-400">Cafés, restaurants, food kiosks. Orders, discounts, BIR receipts.</p><span class="mt-4 inline-block text-base font-semibold text-primary">Learn more →</span></div>
                </a>
                <a href="{{ route('dashboard.login') }}" class="landing-reveal landing-reveal-delay-2 group flex items-start gap-6 p-8 rounded-2xl border-2 border-slate-200 dark:border-darkmode-700 border-t-4 border-t-primary hover:border-primary hover:shadow-xl hover:bg-primary/5 dark:hover:bg-primary/10 transition-all duration-300 bg-white dark:bg-darkmode-800">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-primary/20 text-primary group-hover:bg-primary group-hover:text-white transition-colors shadow-md">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <div><h3 class="text-xl font-extrabold text-[#1A1A2E] dark:text-white">Retail &amp; store</h3><p class="mt-2 text-slate-600 dark:text-slate-400">Boutiques, convenience, supermarket. Barcodes, inventory, multi-branch.</p><span class="mt-4 inline-block text-base font-semibold text-primary">Learn more →</span></div>
                </a>
                <a href="{{ route('dashboard.login') }}" class="landing-reveal landing-reveal-delay-3 group flex items-start gap-6 p-8 rounded-2xl border-2 border-slate-200 dark:border-darkmode-700 border-t-4 border-t-success hover:border-primary hover:shadow-xl hover:bg-primary/5 dark:hover:bg-primary/10 transition-all duration-300 bg-white dark:bg-darkmode-800">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-success/20 text-success group-hover:bg-success group-hover:text-white transition-colors shadow-md">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <div><h3 class="text-xl font-extrabold text-[#1A1A2E] dark:text-white">Pharmacy</h3><p class="mt-2 text-slate-600 dark:text-slate-400">Batch &amp; expiry tracking, low-stock alerts, compliant receipts.</p><span class="mt-4 inline-block text-base font-semibold text-primary">Learn more →</span></div>
                </a>
                <a href="{{ route('dashboard.login') }}" class="landing-reveal landing-reveal-delay-4 group flex items-start gap-6 p-8 rounded-2xl border-2 border-slate-200 dark:border-darkmode-700 border-t-4 border-t-primary hover:border-primary hover:shadow-xl hover:bg-primary/5 dark:hover:bg-primary/10 transition-all duration-300 bg-white dark:bg-darkmode-800">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-primary/20 text-primary group-hover:bg-primary group-hover:text-white transition-colors shadow-md">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <div><h3 class="text-xl font-extrabold text-[#1A1A2E] dark:text-white">Salon &amp; spa</h3><p class="mt-2 text-slate-600 dark:text-slate-400">Appointments, services, retail. One POS for your wellness business.</p><span class="mt-4 inline-block text-base font-semibold text-primary">Learn more →</span></div>
                </a>
                <a href="{{ route('dashboard.login') }}" class="landing-reveal landing-reveal-delay-5 group flex items-start gap-6 p-8 rounded-2xl border-2 border-slate-200 dark:border-darkmode-700 border-t-4 border-t-accent hover:border-primary hover:shadow-xl hover:bg-primary/5 dark:hover:bg-primary/10 transition-all duration-300 bg-white dark:bg-darkmode-800">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-accent/20 text-accent group-hover:bg-accent group-hover:text-white transition-colors shadow-md">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <div><h3 class="text-xl font-extrabold text-[#1A1A2E] dark:text-white">Clinic</h3><p class="mt-2 text-slate-600 dark:text-slate-400">Health care, medical. Patient billing, inventory, BIR-ready records.</p><span class="mt-4 inline-block text-base font-semibold text-primary">Learn more →</span></div>
                </a>
                <a href="{{ route('dashboard.login') }}" class="landing-reveal landing-reveal-delay-6 group flex items-start gap-6 p-8 rounded-2xl border-2 border-slate-200 dark:border-darkmode-700 border-t-4 border-t-success hover:border-primary hover:shadow-xl hover:bg-primary/5 dark:hover:bg-primary/10 transition-all duration-300 bg-white dark:bg-darkmode-800">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-success/20 text-success group-hover:bg-success group-hover:text-white transition-colors shadow-md">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div><h3 class="text-xl font-extrabold text-[#1A1A2E] dark:text-white">Hardware &amp; bakery</h3><p class="mt-2 text-slate-600 dark:text-slate-400">SKUs, stock, multi-branch. Perfect for hardware stores and bakeries.</p><span class="mt-4 inline-block text-base font-semibold text-primary">Learn more →</span></div>
                </a>
            </div>
        </div>
    </section>

    <!-- POS Comparison -->
    <section id="comparison" class="py-20 lg:py-28 landing-section-blue dark:bg-darkmode-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#1A1A2E] dark:text-white">What you get with Landogz POS</h2>
                <p class="mt-4 text-lg text-slate-600 dark:text-slate-400">One system for sales, stock, branches, and compliance.</p>
            </div>
            <div class="landing-reveal landing-reveal-delay-1 max-w-4xl mx-auto rounded-2xl border-2 border-primary/20 bg-white dark:bg-darkmode-900 overflow-hidden shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-base">
                        <thead>
                            <tr class="border-b-2 border-slate-200 dark:border-darkmode-700">
                                <th class="text-left py-6 px-8 font-semibold text-slate-700 dark:text-slate-300">Feature</th>
                                <th class="text-left py-6 px-8 font-bold text-white bg-primary rounded-tr-lg">
                                    <span class="inline-block bg-white/20 text-xs font-semibold px-2 py-0.5 rounded mb-1">Most Popular</span><br>Landogz POS
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-600 dark:text-slate-400">
                            <tr class="border-b border-slate-100 dark:border-darkmode-700"><td class="py-5 px-8">Multi-branch management</td><td class="py-5 px-8 text-success font-semibold">✓ Included</td></tr>
                            <tr class="border-b border-slate-100 dark:border-darkmode-700"><td class="py-5 px-8">Real-time sales &amp; inventory</td><td class="py-5 px-8 text-success font-semibold">✓ Included</td></tr>
                            <tr class="border-b border-slate-100 dark:border-darkmode-700"><td class="py-5 px-8">Product batches &amp; expiry tracking</td><td class="py-5 px-8 text-success font-semibold">✓ Included</td></tr>
                            <tr class="border-b border-slate-100 dark:border-darkmode-700"><td class="py-5 px-8">Low-stock &amp; expiring-soon alerts</td><td class="py-5 px-8 text-success font-semibold">✓ Included</td></tr>
                            <tr class="border-b border-slate-100 dark:border-darkmode-700"><td class="py-5 px-8">BIR-ready official receipts</td><td class="py-5 px-8 text-success font-semibold">✓ Included</td></tr>
                            <tr class="border-b border-slate-100 dark:border-darkmode-700"><td class="py-5 px-8">Role-based access</td><td class="py-5 px-8 text-success font-semibold">✓ Included</td></tr>
                            <tr class="border-b border-slate-100 dark:border-darkmode-700"><td class="py-5 px-8">Dashboard &amp; branch overview</td><td class="py-5 px-8 text-success font-semibold">✓ Included</td></tr>
                            <tr class="border-b border-slate-100 dark:border-darkmode-700"><td class="py-5 px-8">REST API for web &amp; mobile</td><td class="py-5 px-8 text-success font-semibold">✓ Included</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-slate-200 dark:border-darkmode-700 bg-slate-50/50 dark:bg-darkmode-800">
                    <a href="{{ route('dashboard.login') }}" class="inline-flex items-center text-base font-bold text-primary hover:underline">Get a demo →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Who We Serve -->
    <section id="who-we-serve" class="py-20 lg:py-28 landing-section-blue dark:bg-darkmode-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#1A1A2E] dark:text-white">Who we serve</h2>
                <p class="mt-4 text-lg text-slate-600 dark:text-slate-400">Retail and food businesses of every size, from single store to multi-branch.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div class="landing-reveal landing-reveal-delay-1 rounded-2xl border-2 border-slate-200 dark:border-darkmode-700 p-10 shadow-lg hover:border-primary hover:shadow-xl transition-all duration-300 flex items-start gap-6 bg-white dark:bg-darkmode-800">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-primary text-white shadow-lg">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <div><h3 class="font-bold text-lg text-[#1A1A2E] dark:text-white">Retail &amp; boutiques</h3><p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Products, categories, stock. Fast checkout and daily reports.</p></div>
                </div>
                <div class="landing-reveal landing-reveal-delay-2 rounded-2xl border-2 border-slate-200 dark:border-darkmode-700 p-10 shadow-lg hover:border-amber-500 hover:shadow-xl transition-all duration-300 flex items-start gap-6 bg-white dark:bg-darkmode-800">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-lg">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div><h3 class="font-bold text-lg text-[#1A1A2E] dark:text-white">Restaurants &amp; cafés</h3><p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Orders, payments, receipts. Track sales by cashier and terminal.</p></div>
                </div>
                <div class="landing-reveal landing-reveal-delay-3 rounded-2xl border-2 border-slate-200 dark:border-darkmode-700 p-10 shadow-lg hover:border-success hover:shadow-xl transition-all duration-300 flex items-start gap-6 bg-white dark:bg-darkmode-800">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-success text-white shadow-lg">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <div><h3 class="font-bold text-lg text-[#1A1A2E] dark:text-white">Pharmacies</h3><p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Batch and expiry tracking, low-stock alerts, compliant receipts.</p></div>
                </div>
                <div class="landing-reveal landing-reveal-delay-4 rounded-2xl border-2 border-slate-200 dark:border-darkmode-700 p-10 shadow-lg hover:border-primary hover:shadow-xl transition-all duration-300 flex items-start gap-6 bg-white dark:bg-darkmode-800">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-primary text-white shadow-lg">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div><h3 class="font-bold text-lg text-[#1A1A2E] dark:text-white">Convenience stores</h3><p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Quick sales, multiple payment methods, inventory per branch.</p></div>
                </div>
                <div class="landing-reveal landing-reveal-delay-5 rounded-2xl border-2 border-slate-200 dark:border-darkmode-700 p-10 shadow-lg hover:border-amber-500 hover:shadow-xl transition-all duration-300 flex items-start gap-6 bg-white dark:bg-darkmode-800">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-lg">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div><h3 class="font-bold text-lg text-[#1A1A2E] dark:text-white">Supermarkets</h3><p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Many products, barcodes, central or per-branch reporting.</p></div>
                </div>
                <div class="landing-reveal landing-reveal-delay-6 rounded-2xl border-2 border-slate-200 dark:border-darkmode-700 p-10 shadow-lg hover:border-success hover:shadow-xl transition-all duration-300 flex items-start gap-6 bg-white dark:bg-darkmode-800">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-success text-white shadow-lg">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div><h3 class="font-bold text-lg text-[#1A1A2E] dark:text-white">Multi-branch chains</h3><p class="mt-2 text-sm text-slate-600 dark:text-slate-400">One platform for all locations. Branch and head-office dashboards.</p></div>
                </div>
            </div>
        </div>
    </section>

    <!-- What's included / Support -->
    <section id="support" class="py-20 lg:py-28 bg-white dark:bg-darkmode-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#1A1A2E] dark:text-white">What&rsquo;s included</h2>
                <p class="mt-4 text-lg text-slate-600 dark:text-slate-400">From setup to daily use: what you get with Landogz POS.</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 max-w-6xl mx-auto">
                <div class="landing-reveal landing-reveal-delay-1 rounded-2xl bg-white dark:bg-darkmode-900 border-2 border-slate-200 dark:border-darkmode-700 border-t-4 border-t-primary p-10 shadow-xl hover:shadow-2xl hover:border-primary/40 transition-all duration-300">
                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-primary text-white shadow-lg">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="mt-8 text-2xl font-extrabold text-[#1A1A2E] dark:text-white">Multi-branch dashboard</h3>
                    <p class="mt-4 text-lg text-slate-600 dark:text-slate-400 leading-relaxed">Central view of sales and activity. Per-branch stock and reports for managers.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-2 rounded-2xl bg-white dark:bg-darkmode-900 border-2 border-slate-200 dark:border-darkmode-700 border-t-4 border-t-accent p-10 shadow-xl hover:shadow-2xl hover:border-accent/50 transition-all duration-300">
                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-accent text-white shadow-lg">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="mt-8 text-2xl font-extrabold text-[#1A1A2E] dark:text-white">Real-time reports</h3>
                    <p class="mt-4 text-lg text-slate-600 dark:text-slate-400 leading-relaxed">Sales today, transaction counts, low-stock and expiring-soon lists. No waiting for end-of-day.</p>
                </div>
                <div class="landing-reveal landing-reveal-delay-3 rounded-2xl bg-white dark:bg-darkmode-900 border-2 border-slate-200 dark:border-darkmode-700 border-t-4 border-t-success p-10 shadow-xl hover:shadow-2xl hover:border-success/50 transition-all duration-300">
                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-success text-white shadow-lg">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="mt-8 text-2xl font-extrabold text-[#1A1A2E] dark:text-white">API &amp; integrations</h3>
                    <p class="mt-4 text-lg text-slate-600 dark:text-slate-400 leading-relaxed">REST API with token auth. Build custom apps, mobile clients, or connect to your existing tools.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ (accordion) -->
    <section id="faq" class="py-20 lg:py-28 landing-section-blue dark:bg-darkmode-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#1A1A2E] dark:text-white">Frequently asked questions</h2>
                <p class="mt-4 text-lg text-slate-600 dark:text-slate-400">Quick answers about Landogz POS.</p>
            </div>
            <div class="max-w-2xl mx-auto space-y-3">
                <p class="landing-reveal landing-reveal-delay-1 text-sm font-bold uppercase tracking-wider text-primary mt-8 mb-2">Features</p>
                <div class="landing-reveal landing-reveal-delay-1 landing-faq-accordion open rounded-xl border-2 border-primary/20 dark:border-darkmode-700 overflow-hidden bg-white dark:bg-darkmode-800 shadow-md">
                    <button type="button" class="landing-faq-trigger w-full text-left flex items-center justify-between gap-4 py-5 px-6 font-extrabold text-lg text-[#1A1A2E] dark:text-white bg-slate-50 dark:bg-darkmode-800 hover:bg-slate-100 dark:hover:bg-darkmode-700 transition-colors" aria-expanded="true">
                        <span>What is Landogz POS?</span>
                        <span class="relative flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/20 text-primary font-bold text-lg">
                            <span class="landing-faq-plus">+</span>
                            <span class="landing-faq-minus absolute inset-0 flex items-center justify-center text-xl font-bold leading-none">−</span>
                        </span>
                    </button>
                    <div class="landing-faq-body">
                        <div class="py-5 px-6 text-base text-slate-600 dark:text-slate-400 leading-relaxed border-t border-slate-200 dark:border-darkmode-700">Landogz POS is an all-in-one point of sale system for food and retail. It handles sales, inventory (including batches and expiry), multiple branches, BIR-ready receipts, and real-time dashboards. An API lets you connect your own apps or mobile clients.</div>
                    </div>
                </div>
                <div class="landing-reveal landing-reveal-delay-2 landing-faq-accordion rounded-xl border-2 border-primary/20 dark:border-darkmode-700 overflow-hidden bg-white dark:bg-darkmode-800 shadow-md">
                    <button type="button" class="landing-faq-trigger w-full text-left flex items-center justify-between gap-4 py-5 px-6 font-extrabold text-lg text-[#1A1A2E] dark:text-white bg-slate-50 dark:bg-darkmode-800 hover:bg-slate-100 dark:hover:bg-darkmode-700 transition-colors" aria-expanded="false">
                        <span>Can I use it for multiple branches?</span>
                        <span class="relative flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/20 text-primary font-bold text-lg">
                            <span class="landing-faq-plus">+</span>
                            <span class="landing-faq-minus absolute inset-0 flex items-center justify-center text-xl font-bold leading-none">−</span>
                        </span>
                    </button>
                    <div class="landing-faq-body">
                        <div class="py-5 px-6 text-base text-slate-600 dark:text-slate-400 leading-relaxed border-t border-slate-200 dark:border-darkmode-700">Yes. You set up each branch, assign users (managers, cashiers), and manage products and stock per location. Super admins see a branch overview; managers and cashiers see only their branch.</div>
                    </div>
                </div>
                <p class="landing-reveal landing-reveal-delay-2 text-sm font-bold uppercase tracking-wider text-primary mt-8 mb-2">BIR &amp; Setup</p>
                <div class="landing-reveal landing-reveal-delay-3 landing-faq-accordion rounded-xl border-2 border-primary/20 dark:border-darkmode-700 overflow-hidden bg-white dark:bg-darkmode-800 shadow-md">
                    <button type="button" class="landing-faq-trigger w-full text-left flex items-center justify-between gap-4 py-5 px-6 font-extrabold text-lg text-[#1A1A2E] dark:text-white bg-slate-50 dark:bg-darkmode-800 hover:bg-slate-100 dark:hover:bg-darkmode-700 transition-colors" aria-expanded="false">
                        <span>Are receipts BIR-compliant?</span>
                        <span class="relative flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/20 text-primary font-bold text-lg">
                            <span class="landing-faq-plus">+</span>
                            <span class="landing-faq-minus absolute inset-0 flex items-center justify-center text-xl font-bold leading-none">−</span>
                        </span>
                    </button>
                    <div class="landing-faq-body">
                        <div class="py-5 px-6 text-base text-slate-600 dark:text-slate-400 leading-relaxed border-t border-slate-200 dark:border-darkmode-700">The system supports BIR-style official receipts with OR numbers, TIN, and accreditation data. You configure these per branch so your receipts are audit-ready.</div>
                    </div>
                </div>
                <p class="landing-reveal landing-reveal-delay-3 text-sm font-bold uppercase tracking-wider text-primary mt-8 mb-2">API &amp; Integrations</p>
                <div class="landing-reveal landing-reveal-delay-4 landing-faq-accordion rounded-xl border-2 border-primary/20 dark:border-darkmode-700 overflow-hidden bg-white dark:bg-darkmode-800 shadow-md">
                    <button type="button" class="landing-faq-trigger w-full text-left flex items-center justify-between gap-4 py-5 px-6 font-extrabold text-lg text-[#1A1A2E] dark:text-white bg-slate-50 dark:bg-darkmode-800 hover:bg-slate-100 dark:hover:bg-darkmode-700 transition-colors" aria-expanded="false">
                        <span>Is there an API?</span>
                        <span class="relative flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/20 text-primary font-bold text-lg">
                            <span class="landing-faq-plus">+</span>
                            <span class="landing-faq-minus absolute inset-0 flex items-center justify-center text-xl font-bold leading-none">−</span>
                        </span>
                    </button>
                    <div class="landing-faq-body">
                        <div class="py-5 px-6 text-base text-slate-600 dark:text-slate-400 leading-relaxed border-t border-slate-200 dark:border-darkmode-700">Yes. A REST API with token authentication is available for products, transactions, dashboard data, and more. Use it for custom reporting, mobile apps, or integrations with your existing systems.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog -->
    <section id="blog" class="py-20 lg:py-28 bg-white dark:bg-darkmode-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="landing-reveal text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#1A1A2E] dark:text-white">Blog</h2>
                <p class="mt-4 text-lg text-slate-600 dark:text-slate-400">Updates and tips for your POS. Coming soon.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <article class="landing-reveal landing-reveal-delay-1 rounded-2xl border-2 border-slate-200 dark:border-darkmode-700 bg-white dark:bg-darkmode-900 overflow-hidden shadow-lg hover:shadow-xl hover:border-primary/30 transition-all duration-300 flex flex-col">
                    <div class="aspect-video bg-gradient-to-br from-primary/30 via-primary/15 to-primary/5 dark:from-primary/40 dark:via-primary/25 dark:to-primary/10 flex items-center justify-center">
                        <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-primary text-white shadow-lg"><svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg></div>
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="inline-block rounded-full bg-primary/15 px-3 py-1 text-xs font-bold text-primary uppercase tracking-wider">Tips</span>
                            <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Feb 20, 2025
                            </span>
                            <span class="flex items-center gap-1 text-xs text-slate-400 dark:text-slate-500">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                5 min read
                            </span>
                        </div>
                        <h3 class="text-xl font-extrabold text-[#1A1A2E] dark:text-white leading-snug">Getting started with Landogz POS</h3>
                        <p class="mt-3 text-sm text-slate-600 dark:text-slate-400 leading-relaxed flex-1">A step-by-step guide to setting up your branches, adding products, and running your first sale. Coming soon.</p>
                    </div>
                </article>
                <article class="landing-reveal landing-reveal-delay-2 rounded-2xl border-2 border-slate-200 dark:border-darkmode-700 bg-white dark:bg-darkmode-900 overflow-hidden shadow-lg hover:shadow-xl hover:border-amber-400/50 transition-all duration-300 flex flex-col">
                    <div class="aspect-video bg-gradient-to-br from-amber-400/25 via-amber-500/15 to-amber-600/10 dark:from-amber-500/30 dark:via-amber-500/20 dark:to-amber-600/10 flex items-center justify-center">
                        <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-lg"><svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="inline-block rounded-full bg-amber-500/15 px-3 py-1 text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider">BIR</span>
                            <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Mar 5, 2025
                            </span>
                            <span class="flex items-center gap-1 text-xs text-slate-400 dark:text-slate-500">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                4 min read
                            </span>
                        </div>
                        <h3 class="text-xl font-extrabold text-[#1A1A2E] dark:text-white leading-snug">BIR-ready receipts and compliance</h3>
                        <p class="mt-3 text-sm text-slate-600 dark:text-slate-400 leading-relaxed flex-1">How Landogz POS prints official BIR receipts, stores OR numbers, and keeps your business audit-ready. Coming soon.</p>
                    </div>
                </article>
                <article class="landing-reveal landing-reveal-delay-3 rounded-2xl border-2 border-slate-200 dark:border-darkmode-700 bg-white dark:bg-darkmode-900 overflow-hidden shadow-lg hover:shadow-xl hover:border-success/40 transition-all duration-300 flex flex-col">
                    <div class="aspect-video bg-gradient-to-br from-emerald-400/25 via-emerald-500/15 to-emerald-600/10 dark:from-emerald-500/30 dark:via-emerald-500/20 dark:to-emerald-600/10 flex items-center justify-center">
                        <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-success text-white shadow-lg"><svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="inline-block rounded-full bg-success/15 px-3 py-1 text-xs font-bold text-success uppercase tracking-wider">Features</span>
                            <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Mar 18, 2025
                            </span>
                            <span class="flex items-center gap-1 text-xs text-slate-400 dark:text-slate-500">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                6 min read
                            </span>
                        </div>
                        <h3 class="text-xl font-extrabold text-[#1A1A2E] dark:text-white leading-snug">Multi-branch best practices</h3>
                        <p class="mt-3 text-sm text-slate-600 dark:text-slate-400 leading-relaxed flex-1">Tips for managing multiple locations: stock transfers, per-branch reports, and keeping your team in sync. Coming soon.</p>
                    </div>
                </article>
            </div>
            <div class="landing-reveal landing-reveal-delay-1 max-w-xl mx-auto hidden">
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
                <a href="{{ route('quote.request') }}" class="inline-flex items-center justify-center rounded-md bg-white px-6 py-3 text-base font-semibold text-primary hover:bg-white/95">Request a Quote</a>
                <a href="{{ route('dashboard.login') }}" class="inline-flex items-center justify-center rounded-md border border-white/60 px-6 py-3 text-base font-medium text-white hover:bg-white/10">Get a Demo</a>
            </div>
        </div>
    </section>

    @include('partials.landing-footer')
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
        document.querySelectorAll('.landing-faq-trigger').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var accordion = this.closest('.landing-faq-accordion');
                var isOpen = accordion.classList.contains('open');
                document.querySelectorAll('.landing-faq-accordion').forEach(function(a) { a.classList.remove('open'); });
                if (!isOpen) accordion.classList.add('open');
                btn.setAttribute('aria-expanded', !isOpen);
            });
        });
    </script>
    @include('partials.tawk')
</body>
</html>
