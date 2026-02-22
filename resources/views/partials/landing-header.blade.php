{{-- Generic landing header: full nav (default) or minimal (logo + back). Use $minimal = true for simple pages. --}}
@php
    $minimal = $minimal ?? false;
@endphp
<header class="sticky top-0 z-50 border-b border-slate-200 dark:border-darkmode-700 bg-white/95 dark:bg-darkmode-900/95 backdrop-blur">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-14 sm:h-16 items-center justify-between gap-4">
            <a href="{{ url('/') }}" class="flex shrink-0 items-center gap-2">
                <img class="h-8 w-8" src="{{ asset('images/logo.png') }}" alt="Landogz POS">
                <span class="text-base sm:text-lg font-semibold text-slate-800 dark:text-slate-100">Landogz POS</span>
            </a>
            @if ($minimal)
                <a href="{{ url('/') }}" class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary">← Back to home</a>
            @else
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
                    <a href="{{ route('quote.request') }}" class="landing-nav-link px-3 py-2 text-sm font-medium rounded-md hover:bg-slate-100 dark:hover:bg-darkmode-700">Request a Quote</a>
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
            @endif
        </div>
        @if (!$minimal)
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
                    <a href="{{ route('quote.request') }}" class="landing-nav-link text-center py-3 rounded-lg border border-slate-300 dark:border-darkmode-600 text-sm font-medium">Request a Quote</a>
                    <a href="{{ route('dashboard.login') }}" class="text-center py-3 rounded-lg bg-primary text-white text-sm font-semibold">Get a Demo</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</header>
@if (!$minimal)
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
@endif
