@php
    $base = asset('midone-html.vercel.app');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Request a quote for Landogz POS - Secure, affordable point of sale for food and retail.">
    <title>Request a Quote | Landogz POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#1e3a8a',
                        'primary-dark': '#1e40af',
                        darkmode: { 600: '#334155', 700: '#1e293b', 800: '#0f172a', 900: '#020617' }
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="{{ $base }}/dist/css/app.css" onerror="this.remove()">
    <style>
        html { scroll-behavior: smooth; }
        .landing-nav-link { color: #475569; transition: color 0.2s ease; }
        .landing-nav-link:hover { color: #0f172a; }
        .dark .landing-nav-link { color: #94a3b8; }
        .dark .landing-nav-link:hover { color: #f1f5f9; }
        .landing-nav-open { max-height: 90vh; opacity: 1; }
        .landing-nav-closed { max-height: 0; opacity: 0; overflow: hidden; }
        .landing-dropdown-panel { opacity: 0; visibility: hidden; transform: translateY(-4px); transition: opacity 0.2s ease, visibility 0.2s ease, transform 0.2s ease; }
        .landing-dropdown:hover .landing-dropdown-panel { opacity: 1; visibility: visible; transform: translateY(0); }
        /* Slightly more vibrant blue gradient for page */
        .quote-page-bg { background: linear-gradient(180deg, #a5b4fc 0%, #c7d2fe 18%, #e0e7ff 45%, #eef2ff 75%, #f1f5f9 100%); }
        .dark .quote-page-bg { background: linear-gradient(180deg, rgba(30, 58, 138, 0.4) 0%, rgba(30, 58, 138, 0.25) 30%, rgba(15, 23, 42, 0.6) 60%, #0f172a 100%); }
    </style>
</head>
<body class="antialiased bg-slate-50 dark:bg-darkmode-900 text-slate-800 dark:text-slate-200">
    @include('partials.landing-header')

    {{-- Subtle blue gradient top section matching landing hero feel --}}
    <main class="quote-page-bg min-h-[80vh]">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                {{-- Left: Benefits panel — subtle blue/navy background to stand out from form --}}
                <aside class="lg:col-span-4 order-2 lg:order-1">
                    <div class="bg-gradient-to-br from-indigo-50 to-blue-100 border border-indigo-200 rounded-xl p-6 sm:p-8 shadow-sm sticky top-24 dark:from-indigo-950/40 dark:to-blue-950/30 dark:border-indigo-800/50">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Why choose Landogz POS?</h2>
                        <ul class="mt-4 space-y-3 text-slate-800 dark:text-slate-200 text-base font-medium">
                            <li class="flex items-start gap-3">
                                <span class="flex-shrink-0 text-emerald-600 dark:text-emerald-400 text-lg font-extrabold leading-none" aria-hidden="true">✓</span>
                                <span>BIR Accredited</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="flex-shrink-0 text-emerald-600 dark:text-emerald-400 text-lg font-extrabold leading-none" aria-hidden="true">✓</span>
                                <span>Free setup &amp; onboarding</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="flex-shrink-0 text-emerald-600 dark:text-emerald-400 text-lg font-extrabold leading-none" aria-hidden="true">✓</span>
                                <span>24/7 Support</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="flex-shrink-0 text-emerald-600 dark:text-emerald-400 text-lg font-extrabold leading-none" aria-hidden="true">✓</span>
                                <span>No lock-in contracts</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="flex-shrink-0 text-emerald-600 dark:text-emerald-400 text-lg font-extrabold leading-none" aria-hidden="true">✓</span>
                                <span>Works on any device</span>
                            </li>
                        </ul>
                        <div class="mt-6 pt-6 border-t border-slate-200 dark:border-darkmode-600 space-y-2 text-sm text-slate-600 dark:text-slate-400">
                            <p class="flex items-center gap-2">
                                <span aria-hidden="true">📞</span>
                                <a href="tel:+639387077940" class="hover:text-primary dark:hover:text-primary">+63 938 707 7940</a>
                            </p>
                            <p class="flex items-center gap-2">
                                <span aria-hidden="true">📧</span>
                                <a href="mailto:info@landogzwebsolutions.com" class="hover:text-primary dark:hover:text-primary break-all">info@landogzwebsolutions.com</a>
                            </p>
                            <p class="flex items-start gap-2">
                                <span aria-hidden="true">📍</span>
                                <span>Botolan, Zambales Philippines</span>
                            </p>
                        </div>
                        <p class="mt-4 text-xs font-medium text-primary dark:text-primary/90">Response within 24 hours</p>
                        <div class="mt-4 pt-4 border-t border-slate-200 dark:border-darkmode-600">
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2">Reach us on:</p>
                            <div class="flex flex-wrap gap-2">
                                <a href="https://m.me/landogz" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-lg bg-[#0084FF] px-3 py-2 text-xs font-medium text-white hover:opacity-90">Messenger</a>
                                <a href="viber://contact?number=639387077940" class="inline-flex items-center gap-1.5 rounded-lg bg-[#7360f2] px-3 py-2 text-xs font-medium text-white hover:opacity-90">Viber</a>
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- Right: Form (or success message) --}}
                <div class="lg:col-span-8 order-1 lg:order-2">
                    <div id="quote-form-wrapper">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="text-primary dark:text-primary/90 font-semibold text-sm uppercase tracking-wide">Get your free quote in 2 minutes</p>
                            <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary dark:bg-primary/20 dark:text-primary/90" aria-hidden="true">~2 min</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white mt-1">Get a Quote Today</h1>
                        <p class="mt-2 text-slate-600 dark:text-slate-400">Tell us about your business and we’ll get back to you with a tailored quote.</p>

                        <form id="quote-form" class="mt-8 space-y-6 bg-white dark:bg-darkmode-800 rounded-xl border border-slate-200 dark:border-darkmode-700 p-6 sm:p-8 shadow-sm">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="fullname" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Full Name <span class="text-red-500">*</span></label>
                                    <input type="text" id="fullname" name="fullname" required
                                        class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-darkmode-600 bg-white dark:bg-darkmode-900 px-4 py-2.5 text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                        placeholder="Full Name">
                                </div>
                                <div>
                                    <label for="company" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Company Name</label>
                                    <input type="text" id="company" name="company"
                                        class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-darkmode-600 bg-white dark:bg-darkmode-900 px-4 py-2.5 text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                        placeholder="Company Name">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Phone Number <span class="text-red-500">*</span></label>
                                    <input type="tel" id="phone" name="phone" required
                                        class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-darkmode-600 bg-white dark:bg-darkmode-900 px-4 py-2.5 text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                        placeholder="Phone Number">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email <span class="text-red-500">*</span></label>
                                    <input type="email" id="email" name="email" required
                                        class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-darkmode-600 bg-white dark:bg-darkmode-900 px-4 py-2.5 text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                        placeholder="Email">
                                </div>
                            </div>
                            <div>
                                <label for="preferred_contact" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Preferred contact method</label>
                                <select id="preferred_contact" name="preferred_contact"
                                    class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-darkmode-600 bg-white dark:bg-darkmode-900 px-4 py-2.5 text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                                    <option value="">Select how you’d like to be reached</option>
                                    <option value="Phone">Phone</option>
                                    <option value="Email">Email</option>
                                    <option value="Messenger">Messenger</option>
                                    <option value="Viber">Viber</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="business_type" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Type of Business</label>
                                    <select id="business_type" name="business_type"
                                        class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-darkmode-600 bg-white dark:bg-darkmode-900 px-4 py-2.5 text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                                        <option value="">Type of Business</option>
                                        <optgroup label="Food &amp; Beverages">
                                            <option value="Cafe/Bakery">Cafe/Bakery</option>
                                            <option value="Casual Dining">Casual Dining</option>
                                            <option value="Fine Dining">Fine Dining</option>
                                            <option value="Bar/Lounge">Bar/Lounge</option>
                                            <option value="Food Truck">Food Truck</option>
                                            <option value="Food Kiosk">Food Kiosk</option>
                                            <option value="Fast Food">Fast Food</option>
                                        </optgroup>
                                        <optgroup label="Retail">
                                            <option value="Grocery">Grocery</option>
                                            <option value="Pharmacy">Pharmacy</option>
                                            <option value="Supermarket">Supermarket</option>
                                            <option value="Clothing & Apparel">Clothing &amp; Apparel</option>
                                            <option value="Retail Store/Kiosk">Retail Store/Kiosk</option>
                                            <option value="Flower Shop">Flower Shop</option>
                                        </optgroup>
                                        <optgroup label="Services">
                                            <option value="Spa & Wellness Center">Spa &amp; Wellness Center</option>
                                            <option value="Auto Repair Shop">Auto Repair Shop</option>
                                            <option value="Laundry & Dry Cleaning">Laundry &amp; Dry Cleaning</option>
                                            <option value="Health Care/Medical Clinic">Health Care/Medical Clinic</option>
                                            <option value="Hair Salon">Hair Salon</option>
                                            <option value="Pet Grooming">Pet Grooming</option>
                                            <option value="Sports & Country Club">Sports &amp; Country Club</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div>
                                    <label for="branches" class="block text-sm font-medium text-slate-700 dark:text-slate-300">How many store/branches?</label>
                                    <select id="branches" name="branches"
                                        class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-darkmode-600 bg-white dark:bg-darkmode-900 px-4 py-2.5 text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                                        <option value="">How many store/branches?</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10 or more</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="region" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Location / Region</label>
                                    <select id="region" name="region"
                                        class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-darkmode-600 bg-white dark:bg-darkmode-900 px-4 py-2.5 text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                                        <option value="">Location/Region</option>
                                        <option value="NCR">NCR</option>
                                        <option value="CAR">CAR</option>
                                        <option value="Region I – Ilocos Region">Region I – Ilocos Region</option>
                                        <option value="Region II – Cagayan Valley">Region II – Cagayan Valley</option>
                                        <option value="Region III – Central Luzon">Region III – Central Luzon</option>
                                        <option value="Region IV‑A – CALABARZON">Region IV‑A – CALABARZON</option>
                                        <option value="MIMAROPA">MIMAROPA</option>
                                        <option value="Region V – Bicol Region">Region V – Bicol Region</option>
                                        <option value="Region VI – Western Visayas">Region VI – Western Visayas</option>
                                        <option value="Region VII – Central Visayas">Region VII – Central Visayas</option>
                                        <option value="Region VIII – Eastern Visayas">Region VIII – Eastern Visayas</option>
                                        <option value="Region IX – Zamboanga Peninsula">Region IX – Zamboanga Peninsula</option>
                                        <option value="Region X – Northern Mindanao">Region X – Northern Mindanao</option>
                                        <option value="Region XI – Davao Region">Region XI – Davao Region</option>
                                        <option value="Region XII – SOCCSKSARGEN">Region XII – SOCCSKSARGEN</option>
                                        <option value="Region XIII – Caraga">Region XIII – Caraga</option>
                                        <option value="BARMM">BARMM</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="city" class="block text-sm font-medium text-slate-700 dark:text-slate-300">City / Province</label>
                                    <input type="text" id="city" name="city"
                                        class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-darkmode-600 bg-white dark:bg-darkmode-900 px-4 py-2.5 text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                        placeholder="City/Province">
                                </div>
                            </div>
                            <div>
                                <p class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Do you have an existing POS system?</p>
                                <div class="flex gap-6">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="existing_pos" value="Yes" class="rounded-full border-slate-300 text-primary focus:ring-primary">
                                        <span class="ml-2 text-slate-700 dark:text-slate-300">Yes</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="existing_pos" value="No" class="rounded-full border-slate-300 text-primary focus:ring-primary">
                                        <span class="ml-2 text-slate-700 dark:text-slate-300">No</span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label for="requirement" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Brief detail of your requirement</label>
                                <textarea id="requirement" name="requirement" rows="4"
                                    class="mt-1 block w-full rounded-lg border border-slate-300 dark:border-darkmode-600 bg-white dark:bg-darkmode-900 px-4 py-2.5 text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                    placeholder="Tell us what you need..."></textarea>
                            </div>
                            <div class="pt-2">
                                <button type="submit" id="quote-submit" class="w-full inline-flex justify-center items-center rounded-lg bg-primary px-6 py-4 text-base font-semibold text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-50 min-h-[48px] sm:min-h-[52px]">
                                    <span class="submit-text">Get My Free Quote →</span>
                                    <span class="submit-loading hidden">Sending...</span>
                                </button>
                                <p class="mt-3 text-center text-xs text-slate-500 dark:text-slate-400">🔒 We never share your information.</p>
                            </div>
                        </form>
                    </div>

                    {{-- Success state (hidden by default) — "Thanks! We'll get back to you within 24 hours" with next steps --}}
                    <div id="quote-success" class="hidden bg-white dark:bg-darkmode-800 rounded-xl border border-slate-200 dark:border-darkmode-700 p-8 sm:p-10 shadow-sm text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 mb-6" aria-hidden="true">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Thanks!</h2>
                        <p class="mt-3 text-lg text-slate-600 dark:text-slate-400">We'll get back to you within 24 hours.</p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-500">Here’s what happens next:</p>
                        <ul class="mt-6 text-left max-w-md mx-auto space-y-2 text-sm text-slate-600 dark:text-slate-400">
                            <li class="flex items-start gap-2"><span class="text-primary dark:text-primary/90 flex-shrink-0">•</span> Check your email for a confirmation.</li>
                            <li class="flex items-start gap-2"><span class="text-primary dark:text-primary/90 flex-shrink-0">•</span> We may call or message you on your preferred contact method (Phone, Email, Messenger, or Viber).</li>
                            <li class="flex items-start gap-2"><span class="text-primary dark:text-primary/90 flex-shrink-0">•</span> Need help now? Call <a href="tel:+639387077940" class="font-medium text-primary dark:text-primary hover:underline">+63 938 707 7940</a>, email <a href="mailto:info@landogzwebsolutions.com" class="font-medium text-primary dark:text-primary hover:underline">info@landogzwebsolutions.com</a>, or message us on Messenger/Viber.</li>
                        </ul>
                        <a href="{{ url('/') }}" class="mt-8 inline-block w-full sm:w-auto rounded-lg bg-primary px-6 py-3.5 text-base font-semibold text-white hover:opacity-90 text-center">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('partials.landing-footer')

    <script>
        document.getElementById('quote-form').addEventListener('submit', function (e) {
            e.preventDefault();
            var form = this;
            var btn = document.getElementById('quote-submit');
            var submitText = btn.querySelector('.submit-text');
            var submitLoading = btn.querySelector('.submit-loading');
            var payload = {
                fullname: form.fullname.value.trim(),
                company: form.company.value.trim() || null,
                phone: form.phone.value.trim(),
                email: form.email.value.trim(),
                preferred_contact: form.preferred_contact && form.preferred_contact.value ? form.preferred_contact.value : null,
                business_type: form.business_type.value || null,
                branches: form.branches.value || null,
                region: form.region.value || null,
                city: form.city.value.trim() || null,
                existing_pos: form.existing_pos.value || null,
                requirement: form.requirement.value.trim() || null
            };
            btn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            axios.post('{{ url("/api/v1/quote-request") }}', payload, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            }).then(function (res) {
                if (res.data && res.data.status) {
                    document.getElementById('quote-form-wrapper').classList.add('hidden');
                    document.getElementById('quote-success').classList.remove('hidden');
                    document.getElementById('quote-success').scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    Swal.fire({ title: 'Error', text: (res.data && res.data.message) || 'Something went wrong.', icon: 'error', confirmButtonColor: '#1e3a8a' });
                }
            }).catch(function (err) {
                var msg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'Unable to send. Please try again.';
                Swal.fire({ title: 'Error', text: msg, icon: 'error', confirmButtonColor: '#1e3a8a' });
            }).finally(function () {
                btn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            });
        });
    </script>
    @include('partials.tawk')
</body>
</html>
