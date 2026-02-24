@extends('super-admin.layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

@section('content')
    {{-- Header: welcome + role --}}
    <div class="intro-y mt-6 sm:mt-8">
        <div class="flex flex-col gap-1 sm:gap-2">
            <h1 class="text-xl sm:text-2xl font-semibold text-slate-800 dark:text-slate-100 tracking-tight" id="dashboard-welcome">Dashboard</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400" id="dashboard-subtitle">Overview of your key metrics today.</p>
        </div>
    </div>

    {{-- KPI cards --}}
    <div class="intro-y mt-6 grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm p-5 sm:p-6 transition hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl sm:text-3xl font-semibold text-slate-800 dark:text-slate-100" id="sales-today">—</div>
                    <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">Sales today</div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm p-5 sm:p-6 transition hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M12 8v8"/><path d="M8 12h8"/></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl sm:text-3xl font-semibold text-slate-800 dark:text-slate-100" id="transaction-count">—</div>
                    <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">Transactions today</div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm p-5 sm:p-6 transition hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-500/10 text-orange-600 dark:text-orange-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl sm:text-3xl font-semibold text-slate-800 dark:text-slate-100" id="low-stock-count">—</div>
                    <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">Low stock alerts</div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm p-5 sm:p-6 transition hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl sm:text-3xl font-semibold text-slate-800 dark:text-slate-100" id="expiring-count">—</div>
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
                <div class="flex h-10 items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-800 dark:text-slate-100 sm:text-lg">Branch overview</h2>
                    <a href="{{ route('dashboard.branches') }}" class="text-sm font-medium text-primary hover:underline">View all</a>
                </div>
                <div class="mt-4 rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm overflow-hidden">
                    <div id="dashboard-branch-list" class="divide-y divide-slate-100 dark:divide-darkmode-600">
                        <div class="px-5 py-8 text-center text-slate-500 dark:text-slate-400 text-sm">Loading…</div>
                    </div>
                </div>
            </div>

            {{-- Official Store (map) --}}
            <div class="mt-6 sm:mt-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-base font-semibold text-slate-800 dark:text-slate-100 sm:text-lg">Official Store</h2>
                    <div class="relative w-full sm:w-56">
                        <span class="pointer-events-none absolute inset-y-0 left-0 z-10 flex items-center pl-3 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </span>
                        <input type="text" id="dashboard-store-filter-city" placeholder="Filter by city" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 py-2.5 pl-10 pr-4 text-sm text-slate-700 dark:text-slate-300 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition dark:placeholder-slate-500">
                    </div>
                </div>
                <div class="mt-4 rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm p-5">
                    <p class="text-sm text-slate-600 dark:text-slate-400" id="dashboard-official-store-desc">
                        <span id="dashboard-store-count">—</span> Official stores, click the marker to see location details.
                    </p>
                    <div id="dashboard-official-store-map" class="mt-5 h-[310px] w-full rounded-lg overflow-hidden bg-slate-200 dark:bg-darkmode-600 z-0" data-lat="14.5995" data-long="120.9842"></div>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-500">
                        Leaflet | Map data © OpenStreetMap contributors, Tiles © Thunderforest
                    </p>
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
            {{-- Quick actions --}}
            <div class="rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm p-5">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-300">Quick actions</h2>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('dashboard.transactions') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary/10 text-primary"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M12 8v8"/><path d="M8 12h8"/></svg></span>
                        Transactions
                    </a>
                    <a href="{{ route('dashboard.inventory') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></span>
                        Inventory
                    </a>
                    <a href="{{ route('dashboard.reports.z-reading') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></span>
                        Z-Reading
                    </a>
                    <a href="{{ route('dashboard.reports.sales') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-sky-500/10 text-sky-600 dark:text-sky-400"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></span>
                        Sales report
                    </a>
                </div>
            </div>

            {{-- Role info --}}
            <div class="mt-4 rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-slate-50/50 dark:bg-darkmode-700/50 p-5">
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-slate-500">Logged in as</p>
                <p class="mt-1 text-sm font-medium text-slate-700 dark:text-slate-300" id="dashboard-role-label">—</p>
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

    var roleLabels = { super_admin: 'Super Admin', admin: 'Admin', manager: 'Manager', pharmacist: 'Pharmacist', cashier: 'Cashier' };

    // Summary
    axios.get(apiBase + '/dashboard/summary', headers)
        .then(function(r) {
            var d = r.data && r.data.data ? r.data.data : r.data;
            if (!d) return;
            document.getElementById('sales-today').textContent = fmtMoney(d.sales_today);
            document.getElementById('transaction-count').textContent = d.transaction_count != null ? d.transaction_count : '—';
            document.getElementById('low-stock-count').textContent = d.low_stock_count != null ? d.low_stock_count : '—';
            document.getElementById('expiring-count').textContent = d.expiring_soon_count != null ? d.expiring_soon_count : '—';
            var role = (d.role || '').replace(/_/g, ' ');
            role = role ? role.charAt(0).toUpperCase() + role.slice(1) : (roleLabels[d.role] || '—');
            document.getElementById('dashboard-role-label').textContent = role;
        })
        .catch(function() {
            document.getElementById('sales-today').textContent = '—';
            document.getElementById('transaction-count').textContent = '—';
            document.getElementById('low-stock-count').textContent = '—';
            document.getElementById('expiring-count').textContent = '—';
        });

    // Branch overview (super_admin / admin)
    axios.get(apiBase + '/dashboard/branch-overview', headers)
        .then(function(r) {
            var list = (r.data && r.data.data) ? r.data.data : (Array.isArray(r.data) ? r.data : []);
            var el = document.getElementById('dashboard-branch-list');
            var section = document.getElementById('dashboard-branch-section');
            if (!list || list.length === 0) {
                section.classList.add('hidden');
                return;
            }
            section.classList.remove('hidden');
            var html = '';
            list.slice(0, 8).forEach(function(b) {
                var name = escapeHtml((b.company && b.company.name) ? b.company.name + ' — ' + (b.name || '') : (b.name || 'Branch'));
                var sales = fmtMoney(b.sales_today);
                var count = b.transaction_count_today != null ? b.transaction_count_today : 0;
                html += '<a href="' + dashboardBase + '/branches?branch=' + (b.id || '') + '" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-5 py-4 hover:bg-slate-50 dark:hover:bg-darkmode-700/50 transition">';
                html += '<div class="min-w-0"><div class="font-medium text-slate-800 dark:text-slate-200 truncate">' + name + '</div><div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">' + count + ' transaction(s) today</div></div>';
                html += '<div class="flex-shrink-0 text-sm font-semibold text-primary">' + sales + '</div>';
                html += '</a>';
            });
            el.innerHTML = html;
        })
        .catch(function() {
            document.getElementById('dashboard-branch-section').classList.add('hidden');
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
            if (!list || list.length === 0) {
                el.innerHTML = '<div class="px-5 py-8 text-center text-slate-500 dark:text-slate-400 text-sm">No low stock alerts.</div>';
                return;
            }
            var html = '';
            list.slice(0, 5).forEach(function(p) {
                var name = escapeHtml(p.name || p.product_name || 'Product');
                var stock = p.batches_sum_quantity != null ? p.batches_sum_quantity : (p.stock ?? '—');
                var reorder = p.reorder_level != null ? p.reorder_level : '—';
                html += '<div class="flex items-center justify-between gap-3 px-5 py-3">';
                html += '<div class="min-w-0 flex-1"><div class="font-medium text-slate-800 dark:text-slate-200 text-sm truncate">' + name + '</div><div class="text-xs text-slate-500 dark:text-slate-400">Reorder at ' + reorder + '</div></div>';
                html += '<span class="flex-shrink-0 rounded-full bg-amber-100 dark:bg-amber-900/30 px-2.5 py-0.5 text-xs font-medium text-amber-800 dark:text-amber-300">' + stock + ' left</span>';
                html += '</div>';
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
                el.innerHTML = '<div class="px-5 py-8 text-center text-slate-500 dark:text-slate-400 text-sm">No items expiring soon.</div>';
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
