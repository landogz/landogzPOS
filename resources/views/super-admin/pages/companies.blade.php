@extends('super-admin.layouts.app')

@section('title', 'Companies')
@section('breadcrumb', 'Companies')

@section('content')
    {{-- ── Page header ──────────────────────────────────────────────────── --}}
    <div class="intro-y mt-10 flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Companies</h2>

        <div class="flex items-center gap-2">
            {{-- Grid / List toggle --}}
            <div class="flex rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 overflow-hidden">
                <button id="view-grid-btn" title="Grid view"
                    class="view-toggle-btn active px-2.5 py-2 text-primary bg-primary/10 transition-colors"
                    aria-pressed="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                </button>
                <button id="view-list-btn" title="List view"
                    class="view-toggle-btn px-2.5 py-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                </button>
            </div>

            {{-- Status filter --}}
            <div class="relative">
                <select id="companies-status-filter"
                    class="appearance-none rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 py-2 pl-3 pr-8 text-sm text-slate-700 dark:text-slate-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition cursor-pointer">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400"><polyline points="6 9 12 15 18 9"/></svg>
            </div>

            {{-- Search --}}
            <div class="relative text-slate-500">
                <input type="text" id="companies-search" placeholder="Search…"
                    class="transition duration-200 ease-in-out text-sm border-slate-200 dark:border-transparent shadow-sm rounded-lg placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary dark:bg-darkmode-800 dark:placeholder:text-slate-500/80 box w-52 pr-9">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>

            {{-- Add button --}}
            <button type="button" id="companies-add-btn"
                class="inline-flex items-center gap-1.5 rounded-lg bg-primary border border-primary px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary/90 focus:ring-4 focus:ring-primary/20 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Company
            </button>
        </div>
    </div>

    {{-- Summary badge --}}
    <div class="intro-y mt-3">
        <span id="companies-summary-badge" class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 dark:bg-darkmode-600 px-3 py-1 text-xs font-medium text-slate-600 dark:text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span id="companies-summary-text">Loading…</span>
        </span>
    </div>

    {{-- Grid view --}}
    <div id="companies-grid" class="intro-y mt-5 grid grid-cols-12 gap-6">
        {{-- JS rendered --}}
    </div>

    {{-- List view (hidden by default) --}}
    <div id="companies-list" class="intro-y mt-5 hidden">
        <div class="box overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200/60 dark:border-darkmode-400 bg-slate-50/60 dark:bg-darkmode-700/40">
                        <th class="py-3 pl-5 pr-3 text-left font-semibold text-slate-600 dark:text-slate-400 w-10"></th>
                        <th class="py-3 px-3 text-left font-semibold text-slate-600 dark:text-slate-400">Company</th>
                        <th class="py-3 px-3 text-left font-semibold text-slate-600 dark:text-slate-400 hidden sm:table-cell">TIN</th>
                        <th class="py-3 px-3 text-left font-semibold text-slate-600 dark:text-slate-400 hidden md:table-cell">Contact</th>
                        <th class="py-3 px-3 text-center font-semibold text-slate-600 dark:text-slate-400">Branches</th>
                        <th class="py-3 px-3 text-center font-semibold text-slate-600 dark:text-slate-400">Status</th>
                        <th class="py-3 pl-3 pr-5 text-right font-semibold text-slate-600 dark:text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody id="companies-list-tbody">
                    {{-- JS rendered --}}
                </tbody>
            </table>
        </div>
    </div>

    <div id="companies-empty" class="intro-y mt-6 hidden text-center py-16 text-slate-400 dark:text-slate-500">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3 opacity-40"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <p class="text-sm">No companies found.</p>
    </div>
@endsection

@push('modals')
    <x-modal id="company-modal" title="Add Company" title-id="company-modal-title" size="xl">
        <form id="company-form" enctype="multipart/form-data">
            <input type="hidden" id="company-id" name="id" value="">
            <div class="px-6 sm:px-8 py-6 space-y-6">
                {{-- Logo upload --}}
                <div class="rounded-xl border border-slate-200 dark:border-darkmode-500 bg-slate-50/50 dark:bg-darkmode-700/30 p-5">
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-4">Company logo</p>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                        <div class="flex-shrink-0">
                            <div class="relative h-28 w-28 rounded-2xl overflow-hidden border-2 border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 shadow-sm ring-1 ring-slate-200/50 dark:ring-darkmode-600">
                                <img id="company-logo-preview" src="" alt="Logo preview" class="h-full w-full object-cover hidden">
                                <span class="company-logo-placeholder absolute inset-0 flex items-center justify-center text-slate-300 dark:text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-60"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                </span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <label for="company-logo" class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 shadow-sm transition-colors hover:bg-slate-50 dark:hover:bg-darkmode-600 focus-within:ring-2 focus-within:ring-primary focus-within:ring-offset-2 dark:focus-within:ring-offset-darkmode-800">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                                <span id="company-logo-label">Choose file</span>
                                <input type="file" id="company-logo" name="logo" accept="image/*" class="sr-only">
                            </label>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">PNG, JPG or GIF · Max 2MB</p>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="company-name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Company name <span class="text-red-500">*</span></label>
                    <input type="text" id="company-name" name="name" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-4 py-2.5 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition dark:focus:border-primary" placeholder="Enter company name" required>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="company-tin" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">TIN</label>
                        <input type="text" id="company-tin" name="tin" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-4 py-2.5 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition dark:focus:border-primary" placeholder="Tax Identification Number">
                    </div>
                    <div>
                        <label for="company-bir" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">BIR accreditation</label>
                        <input type="text" id="company-bir" name="bir_accreditation" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-4 py-2.5 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition dark:focus:border-primary" placeholder="BIR accreditation number">
                    </div>
                </div>
                <div>
                    <label for="company-address" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Address</label>
                    <textarea id="company-address" name="address" rows="2" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-4 py-2.5 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition dark:focus:border-primary resize-none" placeholder="Street, city, region"></textarea>
                </div>
                <div>
                    <label for="company-contact" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Contact</label>
                    <input type="text" id="company-contact" name="contact" class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-4 py-2.5 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition dark:focus:border-primary" placeholder="Phone or email">
                </div>
            </div>
            <div class="modal-footer flex flex-col-reverse sm:flex-row sm:justify-end gap-3 px-6 sm:px-8 py-5 border-t border-slate-200 dark:border-darkmode-600 bg-slate-50/30 dark:bg-darkmode-700/30">
                <button type="button" data-tw-dismiss="modal" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-lg border border-slate-200 dark:border-darkmode-500 text-slate-700 dark:text-slate-300 bg-white dark:bg-darkmode-700 hover:bg-slate-50 dark:hover:bg-darkmode-600 font-medium text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-darkmode-800">
                    Cancel
                </button>
                <button type="submit" id="company-submit-btn" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-primary border border-primary text-white font-medium text-sm hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-darkmode-800 transition-colors">
                    Save
                </button>
            </div>
        </form>
    </x-modal>
@endpush

@push('scripts')
<script src="{{ $midoneBase ?? asset('midone-html.vercel.app') }}/dist/js/vendors/modal.js"></script>
<script>
(function () {
    var apiBase      = '{{ url("/api/v1") }}';
    var noImageUrl   = '{{ asset("images/noimage.png") }}';
    var dashboardBase = '{{ url("/dashboard") }}';

    function summaryUrl(id)  { return dashboardBase + '/companies/' + id + '/summary'; }
    function branchesUrl(id) { return '{{ route("dashboard.branches") }}?company=' + id; }

    // ── Deterministic avatar colours (cycles by index) ──────────────────
    var AVATAR_COLOURS = [
        { bg: '#6366f1', text: '#fff' }, // indigo
        { bg: '#0ea5e9', text: '#fff' }, // sky
        { bg: '#10b981', text: '#fff' }, // emerald
        { bg: '#f59e0b', text: '#fff' }, // amber
        { bg: '#ef4444', text: '#fff' }, // red
        { bg: '#8b5cf6', text: '#fff' }, // violet
        { bg: '#ec4899', text: '#fff' }, // pink
        { bg: '#14b8a6', text: '#fff' }, // teal
    ];

    function getInitials(name) {
        if (!name) return '?';
        var words = name.trim().split(/\s+/);
        if (words.length === 1) return words[0].slice(0, 2).toUpperCase();
        return (words[0][0] + words[1][0]).toUpperCase();
    }

    function avatarHtml(company, index, size) {
        // size: 'md' (grid cards) | 'sm' (list rows)
        var w  = size === 'sm' ? 'h-9 w-9' : 'h-14 w-14';
        var fs = size === 'sm' ? 'text-sm'  : 'text-lg';
        var r  = size === 'sm' ? 'rounded-lg' : 'rounded-xl';
        if (company.logo_url) {
            return '<img src="' + escapeHtml(company.logo_url) + '" alt="Logo" '
                + 'class="' + w + ' ' + r + ' object-cover border-2 border-slate-200 dark:border-darkmode-400 flex-shrink-0 company-logo-img" '
                + 'data-fallback-name="' + escapeHtml(company.name || '') + '" '
                + 'data-index="' + index + '">';
        }
        var c = AVATAR_COLOURS[index % AVATAR_COLOURS.length];
        return '<div class="' + w + ' ' + r + ' flex-shrink-0 flex items-center justify-center font-bold ' + fs + '" '
            + 'style="background:' + c.bg + ';color:' + c.text + ';">'
            + escapeHtml(getInitials(company.name))
            + '</div>';
    }

    function statusBadge(isActive) {
        return isActive
            ? '<span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400">'
                + '<span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Active</span>'
            : '<span class="inline-flex items-center gap-1 rounded-full bg-slate-200 dark:bg-darkmode-500 px-2.5 py-0.5 text-xs font-semibold text-slate-500 dark:text-slate-400">'
                + '<span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>Inactive</span>';
    }

    function dropdownMenuHtml(c) {
        var isActive = c.is_active !== false;
        return '<div class="company-dropdown-menu absolute right-0 top-full z-[9999] mt-1 hidden min-w-[11rem] rounded-xl border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-600 p-1.5 shadow-xl">'
            + '<a href="javascript:;" class="company-edit flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-darkmode-400 transition-colors cursor-pointer">'
                + '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg>Edit'
            + '</a>'
            + '<a href="javascript:;" class="company-toggle-status flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-darkmode-400 transition-colors cursor-pointer '
                + (isActive ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400') + '">'
                + (isActive
                    ? '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="5" width="22" height="14" rx="7" ry="7"/><circle cx="16" cy="12" r="3"/></svg>Deactivate'
                    : '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="5" width="22" height="14" rx="7" ry="7"/><circle cx="8" cy="12" r="3"/></svg>Activate')
            + '</a>'
            + '<hr class="my-1 border-slate-200 dark:border-darkmode-500">'
            + '<a href="javascript:;" class="company-delete flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors cursor-pointer">'
                + '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>Delete'
            + '</a>'
            + '</div>';
    }

    // ── DOM refs ─────────────────────────────────────────────────────────
    var grid         = document.getElementById('companies-grid');
    var listEl       = document.getElementById('companies-list');
    var listTbody    = document.getElementById('companies-list-tbody');
    var summaryText  = document.getElementById('companies-summary-text');
    var empty        = document.getElementById('companies-empty');
    var searchInput  = document.getElementById('companies-search');
    var statusFilter = document.getElementById('companies-status-filter');
    var modal        = document.getElementById('company-modal');
    var form         = document.getElementById('company-form');
    var modalTitle   = document.getElementById('company-modal-title');
    var submitBtn    = document.getElementById('company-submit-btn');
    var gridBtn      = document.getElementById('view-grid-btn');
    var listBtn      = document.getElementById('view-list-btn');

    // ── View toggle ───────────────────────────────────────────────────────
    var currentView = localStorage.getItem('companies_view') || 'grid';

    function setView(v) {
        currentView = v;
        localStorage.setItem('companies_view', v);
        if (v === 'grid') {
            grid.classList.remove('hidden');
            listEl.classList.add('hidden');
            gridBtn.classList.add('active', 'text-primary', 'bg-primary/10');
            gridBtn.classList.remove('text-slate-400');
            listBtn.classList.remove('active', 'text-primary', 'bg-primary/10');
            listBtn.classList.add('text-slate-400');
        } else {
            grid.classList.add('hidden');
            listEl.classList.remove('hidden');
            listBtn.classList.add('active', 'text-primary', 'bg-primary/10');
            listBtn.classList.remove('text-slate-400');
            gridBtn.classList.remove('active', 'text-primary', 'bg-primary/10');
            gridBtn.classList.add('text-slate-400');
        }
    }
    setView(currentView);
    gridBtn.addEventListener('click', function () { setView('grid'); });
    listBtn.addEventListener('click', function () { setView('list'); });

    // ── Auth ──────────────────────────────────────────────────────────────
    function getToken() { return localStorage.getItem('super_admin_token'); }
    function authHeaders() {
        return { headers: { Authorization: 'Bearer ' + (getToken() || ''), Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } };
    }

    // ── Load companies ────────────────────────────────────────────────────
    function loadCompanies() {
        if (!getToken()) {
            summaryText.textContent = 'Please log in.';
            grid.innerHTML = '';
            listTbody.innerHTML = '';
            empty.classList.remove('hidden');
            return;
        }
        var search = searchInput.value.trim();
        var status = statusFilter.value;
        var url = apiBase + '/companies?status=' + encodeURIComponent(status === 'all' ? '' : status);
        if (search) url += '&search=' + encodeURIComponent(search);

        axios.get(url, authHeaders())
            .then(function (r) {
                var list = (r.data && r.data.data) ? r.data.data : [];
                if (!Array.isArray(list)) list = [];
                renderGrid(list);
                renderList(list);
                var total = list.length;
                var active = list.filter(function (c) { return c.is_active !== false; }).length;
                summaryText.textContent = total + ' ' + (total === 1 ? 'company' : 'companies')
                    + (status === 'all' ? ' · ' + active + ' active' : '');
                empty.classList.toggle('hidden', total > 0);
            })
            .catch(function (err) {
                summaryText.textContent = err.response && err.response.status === 403
                    ? 'Super Admin only.' : 'Failed to load.';
                grid.innerHTML = '';
                listTbody.innerHTML = '';
                empty.classList.remove('hidden');
            });
    }

    // ── Render: Grid ──────────────────────────────────────────────────────
    function renderGrid(list) {
        grid.innerHTML = '';
        list.forEach(function (c, idx) {
            var isActive     = c.is_active !== false;
            var branchCount  = typeof c.branches_count === 'number' ? c.branches_count : (c.branches_count || 0);

            var card = document.createElement('div');
            card.className = 'intro-y col-span-12 md:col-span-6 lg:col-span-4';
            card.innerHTML =
                '<div class="box relative flex flex-col h-full' + (isActive ? '' : ' opacity-80') + '">'

                // ── Card header ──
                + '<div class="flex items-start gap-3 p-5 pb-4">'
                +   avatarHtml(c, idx, 'md')
                +   '<div class="flex-1 min-w-0">'
                +     '<a href="' + escapeHtml(summaryUrl(c.id)) + '" class="block font-semibold text-slate-800 dark:text-slate-100 hover:text-primary truncate">' + escapeHtml(c.name || '') + '</a>'
                +     '<div class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">' + (c.tin ? 'TIN: ' + escapeHtml(c.tin) : 'No TIN on file') + '</div>'
                +     '<div class="mt-2">' + statusBadge(isActive) + '</div>'
                +   '</div>'
                // ── Dropdown ──
                +   '<div class="relative flex-shrink-0">'
                +     '<button type="button" class="company-dropdown-btn flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-darkmode-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" aria-label="Actions">'
                +       '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>'
                +     '</button>'
                +     dropdownMenuHtml(c)
                +   '</div>'
                + '</div>'

                // ── Address + Contact ──
                + '<div class="px-5 space-y-1.5 flex-1">'
                +   '<div class="flex items-start gap-2 text-sm text-slate-500 dark:text-slate-400">'
                +     '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 flex-shrink-0"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>'
                +     '<span class="line-clamp-2">' + (c.address ? escapeHtml(c.address) : '<span class="italic text-slate-400">No address</span>') + '</span>'
                +   '</div>'
                +   '<div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">'
                +     '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.62 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l.77-.77a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>'
                +     '<span>' + (c.contact ? escapeHtml(c.contact) : '<span class="italic text-slate-400">No contact</span>') + '</span>'
                +   '</div>'
                + '</div>'

                // ── Stats row ──
                + '<div class="mx-5 mt-4 flex items-center gap-4 rounded-xl bg-slate-50 dark:bg-darkmode-700/40 px-4 py-3">'
                +   '<div class="flex items-center gap-1.5 text-sm font-medium text-slate-600 dark:text-slate-400">'
                +     '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>'
                +     '<span>' + branchCount + ' ' + (branchCount === 1 ? 'Branch' : 'Branches') + '</span>'
                +   '</div>'
                + '</div>'

                // ── Footer actions ──
                + '<div class="flex items-center gap-2 border-t border-slate-200/60 dark:border-darkmode-400 p-4 mt-4">'
                +   '<a href="' + escapeHtml(summaryUrl(c.id)) + '" class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 py-1.5 px-3 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors">'
                +     '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>Summary'
                +   '</a>'
                +   '<a href="' + escapeHtml(branchesUrl(c.id)) + '" class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg bg-primary border border-primary py-1.5 px-3 text-sm font-medium text-white hover:bg-primary/90 transition-colors">'
                +     '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>Branches'
                +   '</a>'
                + '</div>'

                + '</div>'; // .box

            grid.appendChild(card);
            bindCardEvents(card, c);
        });

        // Broken-image → initials fallback
        grid.addEventListener('error', function (e) {
            if (e.target.classList && e.target.classList.contains('company-logo-img')) {
                var name  = e.target.dataset.fallbackName || '';
                var index = parseInt(e.target.dataset.index || '0', 10);
                var col   = AVATAR_COLOURS[index % AVATAR_COLOURS.length];
                var div   = document.createElement('div');
                div.className = 'h-14 w-14 rounded-xl flex-shrink-0 flex items-center justify-center font-bold text-lg';
                div.style.background = col.bg;
                div.style.color = col.text;
                div.textContent = getInitials(name);
                e.target.parentNode.replaceChild(div, e.target);
            }
        }, true);

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    // ── Render: List ──────────────────────────────────────────────────────
    function renderList(list) {
        listTbody.innerHTML = '';
        list.forEach(function (c, idx) {
            var isActive    = c.is_active !== false;
            var branchCount = typeof c.branches_count === 'number' ? c.branches_count : 0;
            var tr = document.createElement('tr');
            tr.className = 'border-b border-slate-200/60 dark:border-darkmode-400 hover:bg-slate-50/60 dark:hover:bg-darkmode-700/40 transition-colors';
            tr.innerHTML =
                '<td class="py-3 pl-5 pr-3">' + avatarHtml(c, idx, 'sm') + '</td>'
                + '<td class="py-3 px-3">'
                +   '<a href="' + escapeHtml(summaryUrl(c.id)) + '" class="font-medium text-slate-800 dark:text-slate-200 hover:text-primary">' + escapeHtml(c.name || '') + '</a>'
                +   (c.address ? '<div class="text-xs text-slate-400 mt-0.5 truncate max-w-[200px]">' + escapeHtml(c.address) + '</div>' : '')
                + '</td>'
                + '<td class="py-3 px-3 text-sm text-slate-500 dark:text-slate-400 hidden sm:table-cell">' + (c.tin ? escapeHtml(c.tin) : '—') + '</td>'
                + '<td class="py-3 px-3 text-sm text-slate-500 dark:text-slate-400 hidden md:table-cell">' + (c.contact ? escapeHtml(c.contact) : '—') + '</td>'
                + '<td class="py-3 px-3 text-center text-sm font-medium text-slate-700 dark:text-slate-300">' + branchCount + '</td>'
                + '<td class="py-3 px-3 text-center">' + statusBadge(isActive) + '</td>'
                + '<td class="py-3 pl-3 pr-5">'
                +   '<div class="flex items-center justify-end gap-1.5">'
                +     '<a href="' + escapeHtml(summaryUrl(c.id)) + '" class="rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-2.5 py-1.5 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors">Summary</a>'
                +     '<button type="button" class="company-list-edit rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-2.5 py-1.5 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors">Edit</button>'
                +     '<button type="button" class="company-list-delete rounded-lg border border-red-200 dark:border-red-800/50 bg-red-50 dark:bg-red-900/20 px-2.5 py-1.5 text-xs font-medium text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors">Delete</button>'
                +   '</div>'
                + '</td>';
            listTbody.appendChild(tr);

            tr.querySelector('.company-list-edit').addEventListener('click', function () { openEdit(c); });
            tr.querySelector('.company-list-delete').addEventListener('click', function () { confirmDelete(c); });
        });
    }

    // ── Bind dropdown events on a grid card ───────────────────────────────
    function bindCardEvents(card, c) {
        var btn  = card.querySelector('.company-dropdown-btn');
        var menu = card.querySelector('.company-dropdown-menu');
        btn.addEventListener('click', function (e) {
            e.preventDefault(); e.stopPropagation();
            var open = document.querySelector('.company-dropdown-menu:not(.hidden)');
            if (open && open !== menu) open.classList.add('hidden');
            menu.classList.toggle('hidden');
        });
        card.querySelector('.company-edit').addEventListener('click', function (e) { e.preventDefault(); menu.classList.add('hidden'); openEdit(c); });
        card.querySelector('.company-toggle-status').addEventListener('click', function (e) { e.preventDefault(); menu.classList.add('hidden'); toggleStatus(c); });
        card.querySelector('.company-delete').addEventListener('click', function (e) { e.preventDefault(); menu.classList.add('hidden'); confirmDelete(c); });
    }

    document.addEventListener('click', function () {
        document.querySelectorAll('.company-dropdown-menu').forEach(function (m) { m.classList.add('hidden'); });
    });

    // ── Helpers ───────────────────────────────────────────────────────────
    function escapeHtml(s) {
        if (!s) return '';
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    // ── Modal ─────────────────────────────────────────────────────────────
    var logoPreview     = document.getElementById('company-logo-preview');
    var logoPlaceholder = document.querySelector('.company-logo-placeholder');
    var logoInput       = document.getElementById('company-logo');
    var logoLabel       = document.getElementById('company-logo-label');

    function setLogoPreview(src) {
        logoPreview.src = src || noImageUrl;
        logoPreview.classList.remove('hidden');
        if (logoPlaceholder) logoPlaceholder.classList.add('hidden');
        logoPreview.onerror = function () { this.src = noImageUrl; };
    }

    function openAdd() {
        document.getElementById('company-id').value = '';
        document.getElementById('company-name').value = '';
        document.getElementById('company-tin').value = '';
        document.getElementById('company-bir').value = '';
        document.getElementById('company-address').value = '';
        document.getElementById('company-contact').value = '';
        if (logoInput) logoInput.value = '';
        if (logoLabel) logoLabel.textContent = 'Choose file';
        setLogoPreview(null);
        modalTitle.textContent = 'Add Company';
        modal.classList.remove('hidden'); modal.classList.add('show');
        modal.style.display = 'flex'; document.body.style.overflow = 'hidden';
    }

    function openEdit(c) {
        document.getElementById('company-id').value = c.id;
        document.getElementById('company-name').value = c.name || '';
        document.getElementById('company-tin').value = c.tin || '';
        document.getElementById('company-bir').value = c.bir_accreditation || '';
        document.getElementById('company-address').value = c.address || '';
        document.getElementById('company-contact').value = c.contact || '';
        if (logoInput) logoInput.value = '';
        if (logoLabel) logoLabel.textContent = 'Choose file';
        setLogoPreview(c.logo_url || null);
        modalTitle.textContent = 'Edit Company';
        modal.classList.remove('hidden'); modal.classList.add('show');
        modal.style.display = 'flex'; document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.add('hidden'); modal.classList.remove('show');
        modal.style.display = 'none'; document.body.style.overflow = '';
    }

    modal.querySelectorAll('[data-tw-dismiss="modal"]').forEach(function (btn) { btn.addEventListener('click', closeModal); });
    modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });

    if (logoInput) {
        logoInput.addEventListener('change', function () {
            var file = this.files && this.files[0];
            if (logoLabel) logoLabel.textContent = file ? (file.name.length > 24 ? file.name.slice(0, 21) + '…' : file.name) : 'Choose file';
            if (file && file.type.indexOf('image') === 0) {
                var reader = new FileReader();
                reader.onload = function () { setLogoPreview(reader.result); };
                reader.readAsDataURL(file);
            } else { setLogoPreview(null); }
        });
    }

    // ── Form submit ───────────────────────────────────────────────────────
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!getToken()) { Swal.fire({ icon: 'warning', title: 'Login required', text: 'Please log in.' }); return; }
        var id   = document.getElementById('company-id').value;
        var name = document.getElementById('company-name').value.trim();
        if (!name) { Swal.fire({ icon: 'warning', title: 'Required', text: 'Company name is required.' }); return; }
        var fd = new FormData();
        fd.append('name', name);
        fd.append('tin', document.getElementById('company-tin').value.trim());
        fd.append('bir_accreditation', document.getElementById('company-bir').value.trim());
        fd.append('address', document.getElementById('company-address').value.trim());
        fd.append('contact', document.getElementById('company-contact').value.trim());
        if (logoInput && logoInput.files && logoInput.files[0]) fd.append('logo', logoInput.files[0]);
        submitBtn.disabled = true;
        var p;
        if (id) { fd.append('_method', 'PUT'); p = axios.post(apiBase + '/companies/' + id, fd, authHeaders()); }
        else     { p = axios.post(apiBase + '/companies', fd, authHeaders()); }
        p.then(function () {
            showToastNotification('success', 'Saved', id ? 'Company updated.' : 'Company created.');
            closeModal(); loadCompanies();
        }).catch(function (err) {
            var msg = (err.response && err.response.data && err.response.data.message) || 'Save failed.';
            if (err.response && err.response.data && err.response.data.errors) {
                var first = Object.values(err.response.data.errors)[0];
                if (Array.isArray(first)) msg = first[0];
            }
            showToastNotification('error', 'Error', msg);
        }).finally(function () { submitBtn.disabled = false; });
    });

    // ── Toggle status ─────────────────────────────────────────────────────
    function toggleStatus(c) {
        var label = c.is_active !== false ? 'Deactivate' : 'Activate';
        Swal.fire({
            title: label + ' Company?',
            text: label + ' "' + (c.name || '') + '"?',
            icon: 'question', showCancelButton: true,
            confirmButtonColor: '#3085d6', cancelButtonColor: '#6b7280', confirmButtonText: 'Yes'
        }).then(function (r) {
            if (!r.isConfirmed) return;
            axios.patch(apiBase + '/companies/' + c.id + '/toggle-status', {}, authHeaders())
                .then(function (res) {
                    var msg = (res.data && res.data.data && !res.data.data.is_active) ? 'Company deactivated.' : 'Company activated.';
                    showToastNotification('success', 'Done', msg);
                    loadCompanies();
                })
                .catch(function (err) {
                    showToastNotification('error', 'Error', (err.response && err.response.data && err.response.data.message) || 'Request failed.');
                });
        });
    }

    // ── Delete ────────────────────────────────────────────────────────────
    function confirmDelete(c) {
        Swal.fire({
            title: 'Delete Company?',
            text: 'Delete "' + (c.name || '') + '"? This cannot be undone.',
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, delete'
        }).then(function (r) {
            if (!r.isConfirmed) return;
            axios.delete(apiBase + '/companies/' + c.id, authHeaders())
                .then(function () { showToastNotification('success', 'Deleted', 'Company deleted.'); loadCompanies(); })
                .catch(function (err) {
                    showToastNotification('error', 'Error', (err.response && err.response.data && err.response.data.message) || 'Delete failed.');
                });
        });
    }

    // ── Event bindings ────────────────────────────────────────────────────
    document.getElementById('companies-add-btn').addEventListener('click', openAdd);
    statusFilter.addEventListener('change', loadCompanies);
    searchInput.addEventListener('input', function () {
        if (searchInput._timer) clearTimeout(searchInput._timer);
        searchInput._timer = setTimeout(loadCompanies, 300);
    });

    // ── Init ──────────────────────────────────────────────────────────────
    loadCompanies();
})();
</script>
@endpush
