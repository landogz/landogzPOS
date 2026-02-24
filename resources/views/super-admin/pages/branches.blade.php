@extends('super-admin.layouts.app')

@section('title', 'Branches')
@section('breadcrumb', 'Branches')

@section('content')
    <div class="intro-y mt-6 sm:mt-10 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
        <h2 class="text-base font-semibold text-slate-800 dark:text-slate-100 sm:text-lg">Branches</h2>

        <div class="flex flex-wrap items-center gap-2 min-w-0">
            {{-- Grid / List toggle --}}
            <div class="flex rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 overflow-hidden flex-shrink-0">
                <button id="branches-view-grid-btn" title="Grid view" class="branches-view-toggle active px-3 py-2.5 sm:px-2.5 sm:py-2 text-primary bg-primary/10 transition-colors touch-manipulation min-h-[44px] sm:min-h-0" aria-pressed="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                </button>
                <button id="branches-view-list-btn" title="List view" class="branches-view-toggle px-3 py-2.5 sm:px-2.5 sm:py-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors touch-manipulation min-h-[44px] sm:min-h-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                </button>
            </div>

            {{-- Status filter --}}
            <div class="relative flex-shrink-0 min-h-[44px] sm:min-h-0 flex items-center">
                <select id="branches-status-filter" class="appearance-none rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 py-2.5 sm:py-2 pl-3 pr-8 text-sm text-slate-700 dark:text-slate-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition cursor-pointer min-h-[44px] sm:min-h-0">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400"><polyline points="6 9 12 15 18 9"/></svg>
            </div>

            {{-- Company filter --}}
            <div class="relative flex-1 min-w-0 sm:flex-initial sm:min-w-[180px]">
                <select id="branches-company-filter" class="appearance-none rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 py-2.5 sm:py-2 pl-3 pr-8 text-sm text-slate-700 dark:text-slate-300 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition cursor-pointer w-full min-w-0 sm:w-auto sm:min-w-[180px] min-h-[44px] sm:min-h-0">
                    <option value="">All companies</option>
                </select>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400"><polyline points="6 9 12 15 18 9"/></svg>
            </div>

            {{-- Search --}}
            <div class="relative text-slate-500 flex-1 min-w-0 sm:flex-initial sm:w-52">
                <input type="text" id="branches-search" placeholder="Search branches…" class="transition duration-200 ease-in-out text-sm border-slate-200 dark:border-transparent shadow-sm rounded-lg placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary dark:bg-darkmode-800 dark:placeholder:text-slate-500/80 box w-full min-w-0 sm:w-52 pr-9 py-2.5 sm:py-2 min-h-[44px] sm:min-h-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>

            <button type="button" id="branches-add-btn" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-indigo-600 border border-indigo-600 px-4 py-2.5 sm:py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 hover:border-indigo-700 focus:ring-4 focus:ring-indigo-500/20 transition-colors touch-manipulation min-h-[44px] w-full sm:w-auto flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Branch
            </button>
        </div>
    </div>

    <div class="intro-y mt-3">
        <span id="branches-summary-badge" class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 dark:bg-darkmode-600 px-3 py-1 text-xs font-medium text-slate-600 dark:text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span id="branches-summary-text">Loading…</span>
        </span>
    </div>

    <div id="branches-grid" class="intro-y mt-4 sm:mt-5 grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6"></div>

    <div id="branches-list" class="intro-y mt-4 sm:mt-5 hidden">
        <div id="branches-list-content"></div>
    </div>

    <div id="branches-empty" class="intro-y mt-6 hidden text-center py-16 text-slate-400 dark:text-slate-500">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3 opacity-40"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <p class="text-sm">No branches found.</p>
    </div>
@endsection

@push('modals')
    <x-modal id="branch-modal" title="Add Branch" title-id="branch-modal-title" size="xl">
        <form id="branch-form">
            <input type="hidden" id="branch-id" name="branch_id" value="">
            <div class="px-4 sm:px-6 md:px-8 py-4 sm:py-6 space-y-6">
                <div>
                    <label for="branch-company-id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Company <span class="text-red-500">*</span></label>
                    <select id="branch-company-id" name="company_id" required class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-4 py-2.5 text-slate-800 dark:text-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition cursor-pointer">
                        <option value="">Select company</option>
                    </select>
                </div>
                <div>
                    <label for="branch-name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Branch name <span class="text-red-500">*</span></label>
                    <input type="text" id="branch-name" name="name" required
                        class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-4 py-2.5 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition"
                        placeholder="e.g. Main Branch">
                </div>
                <div>
                    <label for="branch-address" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Address</label>
                    <textarea id="branch-address" name="address" rows="2"
                        class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-4 py-2.5 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition resize-none"
                        placeholder="Street, city, region"></textarea>
                </div>
                <div>
                    <label for="branch-tin" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">TIN</label>
                    <input type="text" id="branch-tin" name="tin"
                        class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-4 py-2.5 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition"
                        placeholder="Branch TIN">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="branch-bir-start" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">BIR series start</label>
                        <input type="text" id="branch-bir-start" name="bir_series_start"
                            class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-4 py-2.5 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition">
                    </div>
                    <div>
                        <label for="branch-bir-end" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">BIR series end</label>
                        <input type="text" id="branch-bir-end" name="bir_series_end"
                            class="w-full rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-4 py-2.5 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition">
                    </div>
                </div>
            </div>
            <div class="modal-footer flex flex-col-reverse sm:flex-row sm:justify-end gap-3 px-4 sm:px-6 md:px-8 py-4 border-t border-slate-200 dark:border-darkmode-600 bg-slate-50/30 dark:bg-darkmode-700/30">
                <button type="button" data-tw-dismiss="modal" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 sm:py-2.5 rounded-lg border border-slate-200 dark:border-darkmode-500 text-slate-700 dark:text-slate-300 bg-white dark:bg-darkmode-700 hover:bg-slate-50 dark:hover:bg-darkmode-600 font-medium text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-darkmode-800 touch-manipulation min-h-[44px] sm:min-h-0">Cancel</button>
                <button type="submit" id="branch-submit-btn" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 sm:py-2.5 rounded-lg bg-primary border border-primary text-white font-medium text-sm hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-darkmode-800 transition-colors touch-manipulation min-h-[44px] sm:min-h-0">Save</button>
            </div>
        </form>
    </x-modal>
@endpush

@push('scripts')
<script src="{{ $midoneBase ?? asset('midone-html.vercel.app') }}/dist/js/vendors/modal.js"></script>
<script>
(function () {
    var apiBase = '{{ url("/api/v1") }}';
    var dashboardBase = '{{ url("/dashboard") }}';
    function companySummaryUrl(companyId, branchId) { var u = dashboardBase + '/companies/' + companyId + '/summary'; if (branchId) u += '?branch_id=' + encodeURIComponent(branchId); return u; }

    // Company color system: distinct colors for cards, avatars, and accordion preview badges.
    // Assigned by company_id % length so new companies get a color automatically. Add hex values to extend.
    var COMPANY_BORDER_COLORS = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#0ea5e9', '#ec4899', '#14b8a6'];

    var grid = document.getElementById('branches-grid');
    var listEl = document.getElementById('branches-list');
    var listContent = document.getElementById('branches-list-content');
    var summaryText = document.getElementById('branches-summary-text');
    var empty = document.getElementById('branches-empty');
    var companyFilter = document.getElementById('branches-company-filter');
    var statusFilter = document.getElementById('branches-status-filter');
    var searchInput = document.getElementById('branches-search');
    var modal = document.getElementById('branch-modal');
    var form = document.getElementById('branch-form');
    var modalTitle = document.getElementById('branch-modal-title');
    var submitBtn = document.getElementById('branch-submit-btn');
    var gridBtn = document.getElementById('branches-view-grid-btn');
    var listBtn = document.getElementById('branches-view-list-btn');

    var companiesList = [];
    var currentBranchesList = [];
    var currentView = localStorage.getItem('branches_view') || 'grid';
    var currentUserRole = '';

    function getToken() { return localStorage.getItem('super_admin_token'); }
    function authHeaders() { return { headers: { Authorization: 'Bearer ' + (getToken() || ''), Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } }; }
    function escapeHtml(s) { if (!s) return ''; var d = document.createElement('div'); d.textContent = s; return d.innerHTML; }
    function getCompanyIdFromUrl() { var p = new URLSearchParams(window.location.search); var c = p.get('company'); return (c !== null && c !== undefined && c !== '') ? c : ''; }

    function formatMoney(v) {
        var n = parseFloat(v) || 0;
        if (n >= 1000000) return '\u20B1' + (n / 1000000).toFixed(1) + 'M';
        if (n >= 1000) return '\u20B1' + Math.round(n / 1000) + 'k';
        return '\u20B1' + Math.round(n).toLocaleString();
    }
    function statusBadge(isActive) {
        return isActive
            ? '<span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Active</span>'
            : '<span class="inline-flex items-center gap-1 rounded-full bg-slate-200 dark:bg-darkmode-500 px-2.5 py-0.5 text-xs font-semibold text-slate-500 dark:text-slate-400"><span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>Inactive</span>';
    }
    function branchBadge(idx) { return 'B' + (idx + 1); }
    /** Picks a consistent color per company (cards, B1/B2 badges, accordion header). companyId can be number or string; cycles through COMPANY_BORDER_COLORS. */
    function companyBorderColor(companyId) { return COMPANY_BORDER_COLORS[(companyId || 0) % COMPANY_BORDER_COLORS.length]; }

    function sortBranchesForDisplay(branches) {
        return branches.slice().sort(function (a, b) {
            var nameA = (a.name || '').toLowerCase();
            var nameB = (b.name || '').toLowerCase();
            var mainA = nameA.indexOf('main') >= 0 ? 0 : 1;
            var mainB = nameB.indexOf('main') >= 0 ? 0 : 1;
            if (mainA !== mainB) return mainA - mainB;
            return nameA.localeCompare(nameB);
        });
    }
    function groupBranchesByCompany(list) {
        var byId = {};
        list.forEach(function (b) {
            var cid = b.company_id || (b.company && b.company.id) || 0;
            if (!byId[cid]) byId[cid] = { company: b.company || {}, company_id: cid, company_name: (b.company && b.company.name) ? b.company.name : '—', branches: [] };
            byId[cid].branches.push(b);
        });
        var groups = Object.keys(byId).map(function (k) { return byId[k]; });
        groups.forEach(function (g) { g.branches = sortBranchesForDisplay(g.branches); });
        groups.sort(function (a, b) { return (a.company_name || '').localeCompare(b.company_name || ''); });
        return groups;
    }
    function dropdownMenuHtml(b) {
        var isActive = b.is_active !== false;
        var isAdmin = currentUserRole === 'admin';
        var html = '<div class="branch-dropdown-menu absolute right-0 top-full z-[9999] mt-1 hidden min-w-[11rem] max-w-[calc(100vw-2rem)] rounded-xl border border-slate-200 dark:border-darkmode-600 bg-white dark:bg-darkmode-600 p-1.5 shadow-xl">'
            + '<a href="javascript:;" class="branch-edit flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-darkmode-400 transition-colors cursor-pointer"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg>Edit</a>'
            + '<a href="javascript:;" class="branch-toggle-status flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-darkmode-400 transition-colors cursor-pointer ' + (isActive ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400') + '">' + (isActive ? '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="5" width="22" height="14" rx="7" ry="7"/><circle cx="16" cy="12" r="3"/></svg>Deactivate' : '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="5" width="22" height="14" rx="7" ry="7"/><circle cx="8" cy="12" r="3"/></svg>Activate') + '</a>';
        if (!isAdmin) {
            html += '<hr class="my-1 border-slate-200 dark:border-darkmode-500">'
                + '<a href="javascript:;" class="branch-delete flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors cursor-pointer"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>Delete</a>';
        }
        html += '</div>';
        return html;
    }

    function setView(v) {
        currentView = v;
        localStorage.setItem('branches_view', v);
        if (v === 'grid') {
            grid.classList.remove('hidden');
            listEl.classList.add('hidden');
            gridBtn.classList.add('active', 'text-primary', 'bg-primary/10'); gridBtn.classList.remove('text-slate-400');
            listBtn.classList.remove('active', 'text-primary', 'bg-primary/10'); listBtn.classList.add('text-slate-400');
        } else {
            grid.classList.add('hidden');
            listEl.classList.remove('hidden');
            listBtn.classList.add('active', 'text-primary', 'bg-primary/10'); listBtn.classList.remove('text-slate-400');
            gridBtn.classList.remove('active', 'text-primary', 'bg-primary/10'); gridBtn.classList.add('text-slate-400');
        }
        renderBranches(currentBranchesList);
    }
    setView(currentView);
    gridBtn.addEventListener('click', function () { setView('grid'); });
    listBtn.addEventListener('click', function () { setView('list'); });

    function runInitialLoad() {
        if (!getToken()) { loadCompanies(); return; }
        axios.get(apiBase + '/auth/me', authHeaders()).then(function (r) {
            var d = r.data && r.data.data ? r.data.data : r.data;
            currentUserRole = (d && d.user && d.user.role) ? d.user.role : '';
            if (currentUserRole === 'admin') {
                var addBtn = document.getElementById('branches-add-btn');
                if (addBtn) addBtn.style.display = 'none';
            }
            loadCompanies();
        }).catch(function () { loadCompanies(); });
    }

    function loadCompanies() {
        if (!getToken()) return;
        axios.get(apiBase + '/companies', authHeaders())
            .then(function (r) {
                var list = (r.data && r.data.data) ? r.data.data : [];
                if (!Array.isArray(list)) list = [];
                companiesList = list;
                var current = companyFilter.value;
                companyFilter.innerHTML = '<option value="">All companies</option>' + list.map(function (c) { return '<option value="' + c.id + '">' + escapeHtml(c.name || '') + '</option>'; }).join('');
                var urlCompany = getCompanyIdFromUrl();
                companyFilter.value = urlCompany || current || '';
                loadBranches();
            })
            .catch(function () { companyFilter.innerHTML = '<option value="">All companies</option>'; loadBranches(); });
    }

    function loadBranches() {
        if (!getToken()) { summaryText.textContent = 'Please log in.'; grid.innerHTML = ''; if (listContent) listContent.innerHTML = ''; empty.classList.remove('hidden'); return; }
        var companyId = companyFilter.value;
        var status = statusFilter.value;
        var url = apiBase + '/branches?status=' + encodeURIComponent(status === 'all' ? '' : status);
        if (companyId) url += '&company_id=' + encodeURIComponent(companyId);
        axios.get(url, authHeaders())
            .then(function (r) {
                var list = (r.data && r.data.data) ? r.data.data : [];
                if (!Array.isArray(list)) list = [];
                var search = searchInput.value.trim().toLowerCase();
                if (search) list = list.filter(function (b) {
                    var name = (b.name || '').toLowerCase();
                    var addr = (b.address || '').toLowerCase();
                    var companyName = (b.company && b.company.name ? b.company.name : '').toLowerCase();
                    return name.indexOf(search) !== -1 || addr.indexOf(search) !== -1 || companyName.indexOf(search) !== -1;
                });
                currentBranchesList = list;
                renderBranches(list);
                var n = list.length;
                var activeCount = list.filter(function (b) { return b.is_active !== false; }).length;
                summaryText.textContent = n + ' ' + (n === 1 ? 'branch' : 'branches') + (status === 'all' ? ' · ' + activeCount + ' active' : '');
                empty.classList.toggle('hidden', n > 0);
            })
            .catch(function (err) {
                summaryText.textContent = err.response && err.response.status === 403 ? 'Access denied.' : 'Failed to load.';
                currentBranchesList = [];
                grid.innerHTML = '';
                if (listContent) listContent.innerHTML = '';
                empty.classList.remove('hidden');
            });
    }

    function cardHtml(b, branchIdx, company, companyId, borderColor) {
        var isActive = b.is_active !== false;
        var terminalsCount = typeof b.terminals_count === 'number' ? b.terminals_count : 0;
        var totalSales = parseFloat(b.transactions_sum_total) || 0;
        var salesHtml = totalSales === 0
            ? '<span class="text-sm font-medium text-slate-400 dark:text-slate-500">No sales yet</span>'
            : '<span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">' + formatMoney(totalSales) + '</span>';
        return '<div class="branch-card box relative flex flex-col h-[320px] transition-all duration-200 hover:shadow-xl hover:-translate-y-1 rounded-xl overflow-hidden border-l-4 ' + (isActive ? '' : ' opacity-80') + '" style="border-left-color:' + borderColor + '">'
            + '<div class="flex flex-1 flex-col min-h-0 min-w-0">'
            + '<div class="flex items-start gap-3 p-4 sm:p-5 pb-3 sm:pb-4 flex-shrink-0">'
            +   '<div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl font-bold text-sm text-white" style="background:' + borderColor + '">' + branchBadge(branchIdx) + '</div>'
            +   '<div class="min-w-0 flex-1">'
            +     '<p class="font-semibold text-slate-800 dark:text-slate-100 truncate">' + escapeHtml(b.name || '') + '</p>'
            +     '<div class="mt-2">' + statusBadge(isActive) + '</div>'
            +   '</div>'
            +   '<div class="relative flex-shrink-0">'
            +     '<button type="button" class="branch-dropdown-btn flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-darkmode-400 hover:text-slate-600 transition-colors" aria-label="Actions">'
            +       '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>'
            +     '</button>' + dropdownMenuHtml(b)
            +   '</div>'
            + '</div>'
            + '<div class="card-body flex-1 min-h-0 min-w-0 flex flex-col px-4 sm:px-5 pb-4">'
            + '<div class="space-y-1.5 h-24 overflow-hidden flex-shrink-0">'
            +   (b.address ? '<div class="flex items-start gap-2 text-sm text-slate-500 dark:text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mt-0.5 flex-shrink-0"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg><span class="line-clamp-2">' + escapeHtml(b.address) + '</span></div>' : '')
            +   (b.tin ? '<div class="text-sm text-slate-500 dark:text-slate-400">TIN: ' + escapeHtml(b.tin) + '</div>' : '')
            +   ((b.bir_series_start || b.bir_series_end) ? '<div class="branch-bir-meta text-xs font-normal">BIR: ' + escapeHtml(b.bir_series_start || '—') + ' – ' + escapeHtml(b.bir_series_end || '—') + '</div>' : '')
            + '</div>'
            + '<div class="flex-1 min-h-0"></div>'
            + '</div>'
            + '</div>'
            + '<div class="flex-shrink-0 border-t border-slate-200/60 dark:border-darkmode-400">'
            + '<div class="mx-4 sm:mx-5 mt-3 sm:mt-4 flex flex-nowrap items-center gap-3 rounded-xl bg-slate-50 dark:bg-darkmode-700/40 px-3 sm:px-4 py-2.5 sm:py-3 min-h-[2.75rem]">'
            +   '<div class="flex flex-shrink-0 items-center gap-1.5 text-sm font-medium text-slate-600 dark:text-slate-400 whitespace-nowrap"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-500 dark:text-slate-400 flex-shrink-0"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg><span>' + terminalsCount + ' Terminal' + (terminalsCount !== 1 ? 's' : '') + '</span></div>'
            +   '<div class="h-4 w-px flex-shrink-0 bg-slate-200 dark:bg-darkmode-500"></div>'
            +   '<div class="flex min-w-0 items-center justify-end text-sm">' + salesHtml + '</div>'
            + '</div>'
            + '<div class="flex items-center gap-2 p-3 sm:p-4">'
            +   '<a href="' + escapeHtml(companySummaryUrl(companyId, b.id)) + '" class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 py-2.5 sm:py-1.5 px-3 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors touch-manipulation min-h-[44px] sm:min-h-0">View Summary</a>'
            + '</div>'
            + '</div></div>';
    }

    function renderBranches(list) {
        var isGrid = currentView === 'grid';
        if (isGrid) {
            grid.innerHTML = '';
            var groups = groupBranchesByCompany(list);
            groups.forEach(function (grp) {
                var companyId = grp.company_id;
                var companyName = escapeHtml(grp.company_name || '—');
                var branchCount = grp.branches.length;
                var activeCount = grp.branches.filter(function (b) { return b.is_active !== false; }).length;
                var statusLabel = activeCount === branchCount ? 'active' : activeCount + ' active';
                var sectionId = 'accordion-company-' + (companyId || 'none');
                var borderColorAcc = companyBorderColor(companyId);
                var previewBadges = grp.branches.slice(0, 8).map(function (b, i) { return '<span class="inline-flex h-6 w-6 items-center justify-center rounded-md text-[10px] font-bold text-white flex-shrink-0" style="background:' + borderColorAcc + '">' + branchBadge(i) + '</span>'; }).join('');
                if (grp.branches.length > 8) previewBadges += '<span class="text-xs font-medium text-slate-400 dark:text-slate-500">+' + (grp.branches.length - 8) + '</span>';
                var section = document.createElement('div');
                section.className = 'branches-accordion-section col-span-12 intro-y';
                section.innerHTML =
                    '<div class="rounded-xl border border-slate-200 dark:border-darkmode-500 bg-slate-50/50 dark:bg-darkmode-700/30 overflow-hidden border-l-4 border-l-indigo-500">'
                    + '<button type="button" class="branches-accordion-btn w-full flex items-center justify-between gap-3 px-4 sm:px-5 py-3 sm:py-3.5 text-left font-semibold text-slate-800 dark:text-slate-100 hover:bg-slate-100/80 dark:hover:bg-darkmode-600/50 transition-colors" aria-expanded="true" aria-controls="' + sectionId + '" data-target="' + sectionId + '">'
                    +   '<span class="flex items-center gap-2 min-w-0">'
                    +     '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="branches-accordion-chevron text-slate-500 dark:text-slate-400 transition-transform flex-shrink-0" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>'
                    +     '<span class="truncate">' + companyName + ' <span class="text-xs font-medium text-slate-500 dark:text-slate-400">' + branchCount + ' branch' + (branchCount !== 1 ? 'es' : '') + ' · ' + statusLabel + '</span></span>'
                    +   '</span>'
                    +   '<span class="flex items-center gap-1 flex-shrink-0">' + previewBadges + '</span>'
                    + '</button>'
                    + '<div id="' + sectionId + '" class="branches-accordion-content border-t border-slate-200 dark:border-darkmode-500 p-4 sm:p-5">'
                    +   '<div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">'
                    +   '</div></div></div>';
                var cardsWrap = section.querySelector('.grid');
                grp.branches.forEach(function (b, idx) {
                    var companyForCard = { name: grp.company_name || (grp.company && grp.company.name) || '—' };
                    var borderColor = companyBorderColor(companyId);
                    var card = document.createElement('div');
                    card.className = 'col-span-12 sm:col-span-6 lg:col-span-4';
                    card.innerHTML = cardHtml(b, idx, companyForCard, companyId, borderColor);
                    cardsWrap.appendChild(card);
                    bindCardEvents(card, b);
                });
                grid.appendChild(section);
                var btn = section.querySelector('.branches-accordion-btn');
                var content = section.querySelector('.branches-accordion-content');
                var chevron = section.querySelector('.branches-accordion-chevron');
                btn.addEventListener('click', function () {
                    var nowCollapsed = content.classList.toggle('hidden');
                    btn.setAttribute('aria-expanded', nowCollapsed ? 'false' : 'true');
                    if (chevron) chevron.style.transform = nowCollapsed ? 'rotate(-90deg)' : 'rotate(0)';
                });
            });
        } else {
            listContent.innerHTML = '';
            var listGroups = groupBranchesByCompany(list);
            var tableHeader = '<thead><tr class="border-b border-slate-200/60 dark:border-darkmode-400 bg-slate-50/60 dark:bg-darkmode-700/40">'
                + '<th class="py-3 pl-5 pr-3 text-left font-semibold text-slate-600 dark:text-slate-400 w-12">#</th>'
                + '<th class="py-3 px-3 text-left font-semibold text-slate-600 dark:text-slate-400">Branch</th>'
                + '<th class="py-3 px-3 text-left font-semibold text-slate-600 dark:text-slate-400 hidden sm:table-cell">Company</th>'
                + '<th class="py-3 px-3 text-center font-semibold text-slate-600 dark:text-slate-400">Terminals</th>'
                + '<th class="py-3 px-3 text-right font-semibold text-slate-600 dark:text-slate-400">Sales</th>'
                + '<th class="py-3 px-3 text-center font-semibold text-slate-600 dark:text-slate-400">Status</th>'
                + '<th class="py-3 pl-3 pr-5 text-right font-semibold text-slate-600 dark:text-slate-400">Actions</th></tr></thead>';
            listGroups.forEach(function (grp) {
                var companyId = grp.company_id;
                var companyName = escapeHtml(grp.company_name || '—');
                var branchCount = grp.branches.length;
                var activeCount = grp.branches.filter(function (b) { return b.is_active !== false; }).length;
                var statusLabel = activeCount === branchCount ? 'active' : activeCount + ' active';
                var sectionId = 'list-accordion-company-' + (companyId || 'none');
                var borderColorList = companyBorderColor(companyId);
                var listPreviewBadges = grp.branches.slice(0, 8).map(function (b, i) { return '<span class="inline-flex h-6 w-6 items-center justify-center rounded-md text-[10px] font-bold text-white flex-shrink-0" style="background:' + borderColorList + '">' + branchBadge(i) + '</span>'; }).join('');
                if (grp.branches.length > 8) listPreviewBadges += '<span class="text-xs font-medium text-slate-400 dark:text-slate-500">+' + (grp.branches.length - 8) + '</span>';
                var section = document.createElement('div');
                section.className = 'branches-list-accordion-section intro-y mb-4 last:mb-0';
                section.innerHTML =
                    '<div class="rounded-xl border border-slate-200 dark:border-darkmode-500 bg-slate-50/50 dark:bg-darkmode-700/30 overflow-hidden border-l-4 border-l-indigo-500">'
                    + '<button type="button" class="branches-list-accordion-btn w-full flex items-center justify-between gap-3 px-4 sm:px-5 py-3 sm:py-3.5 text-left font-semibold text-slate-800 dark:text-slate-100 hover:bg-slate-100/80 dark:hover:bg-darkmode-600/50 transition-colors" aria-expanded="true" aria-controls="' + sectionId + '" data-target="' + sectionId + '">'
                    +   '<span class="flex items-center gap-2 min-w-0">'
                    +     '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="branches-list-accordion-chevron text-slate-500 dark:text-slate-400 transition-transform flex-shrink-0"><polyline points="6 9 12 15 18 9"/></svg>'
                    +     '<span class="truncate">' + companyName + ' <span class="text-xs font-medium text-slate-500 dark:text-slate-400">' + branchCount + ' branch' + (branchCount !== 1 ? 'es' : '') + ' · ' + statusLabel + '</span></span>'
                    +   '</span>'
                    +   '<span class="flex items-center gap-1 flex-shrink-0">' + listPreviewBadges + '</span>'
                    + '</button>'
                    + '<div id="' + sectionId + '" class="branches-list-accordion-content border-t border-slate-200 dark:border-darkmode-500">'
                    +   '<div class="box overflow-x-auto overflow-y-hidden sm:overflow-visible rounded-t-none">'
                    +     '<div class="min-w-[640px] md:min-w-0">'
                    +       '<table class="w-full text-sm"><tbody></tbody></table>'
                    +     '</div></div></div></div>';
                var tbody = section.querySelector('table tbody');
                section.querySelector('table').insertAdjacentHTML('afterbegin', tableHeader);
                grp.branches.forEach(function (b, idx) {
                    var company = grp.company || {};
                    var isActive = b.is_active !== false;
                    var terminalsCount = typeof b.terminals_count === 'number' ? b.terminals_count : 0;
                    var totalSales = parseFloat(b.transactions_sum_total) || 0;
                    var borderColor = companyBorderColor(companyId);
                    var salesCell = totalSales === 0
                        ? '<span class="text-slate-400 dark:text-slate-500">No sales yet</span>'
                        : '<span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">' + formatMoney(totalSales) + '</span>';
                    var tr = document.createElement('tr');
                    tr.className = 'border-b border-slate-200/60 dark:border-darkmode-400 hover:bg-slate-50/60 dark:hover:bg-darkmode-700/40 transition-colors';
                    tr.innerHTML = '<td class="py-3 pl-5 pr-3"><span class="inline-flex h-8 w-8 items-center justify-center rounded-lg font-bold text-xs text-white" style="background:' + borderColor + '">' + branchBadge(idx) + '</span></td>'
                        + '<td class="py-3 px-3"><a href="' + escapeHtml(companySummaryUrl(companyId, b.id)) + '" class="font-medium text-slate-800 dark:text-slate-200 hover:text-primary">' + escapeHtml(b.name || '') + '</a>' + (b.address ? '<div class="text-xs text-slate-400 mt-0.5 truncate max-w-[200px]">' + escapeHtml(b.address) + '</div>' : '') + '</td>'
                        + '<td class="py-3 px-3 text-sm text-slate-500 dark:text-slate-400 hidden sm:table-cell">' + escapeHtml(grp.company_name || '—') + '</td>'
                        + '<td class="py-3 px-3 text-center text-sm font-medium text-slate-700 dark:text-slate-300">' + terminalsCount + '</td>'
                        + '<td class="py-3 px-3 text-right">' + salesCell + '</td>'
                        + '<td class="py-3 px-3 text-center">' + statusBadge(isActive) + '</td>'
                        + '<td class="py-3 pl-3 pr-4 sm:pr-5"><div class="flex flex-wrap items-center justify-end gap-1.5"><a href="' + escapeHtml(companySummaryUrl(companyId, b.id)) + '" class="rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-2.5 py-2 sm:py-1.5 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 transition-colors touch-manipulation min-h-[40px] sm:min-h-0 inline-flex items-center">View Summary</a><button type="button" class="branch-list-edit rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 px-2.5 py-2 sm:py-1.5 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 transition-colors touch-manipulation min-h-[40px] sm:min-h-0">Edit</button>' + (currentUserRole !== 'admin' ? '<button type="button" class="branch-list-delete rounded-lg border border-red-200 dark:border-red-800/50 bg-red-50 dark:bg-red-900/20 px-2.5 py-2 sm:py-1.5 text-xs font-medium text-red-600 dark:text-red-400 hover:bg-red-100 transition-colors touch-manipulation min-h-[40px] sm:min-h-0">Delete</button>' : '') + '</div></td>';
                    tbody.appendChild(tr);
                    tr.querySelector('.branch-list-edit').addEventListener('click', function () { openEdit(b); });
                    var listDeleteBtn = tr.querySelector('.branch-list-delete');
                    if (listDeleteBtn) listDeleteBtn.addEventListener('click', function () { confirmDelete(b); });
                });
                listContent.appendChild(section);
                var btn = section.querySelector('.branches-list-accordion-btn');
                var content = section.querySelector('.branches-list-accordion-content');
                var chevron = section.querySelector('.branches-list-accordion-chevron');
                btn.addEventListener('click', function () {
                    var nowCollapsed = content.classList.toggle('hidden');
                    btn.setAttribute('aria-expanded', nowCollapsed ? 'false' : 'true');
                    if (chevron) chevron.style.transform = nowCollapsed ? 'rotate(-90deg)' : 'rotate(0)';
                });
            });
        }
    }

    function bindCardEvents(card, b) {
        var btn = card.querySelector('.branch-dropdown-btn');
        var menu = card.querySelector('.branch-dropdown-menu');
        btn.addEventListener('click', function (e) { e.preventDefault(); e.stopPropagation(); document.querySelectorAll('.branch-dropdown-menu:not(.hidden)').forEach(function (m) { if (m !== menu) m.classList.add('hidden'); }); menu.classList.toggle('hidden'); });
        card.querySelector('.branch-edit').addEventListener('click', function (e) { e.preventDefault(); menu.classList.add('hidden'); openEdit(b); });
        card.querySelector('.branch-toggle-status').addEventListener('click', function (e) { e.preventDefault(); menu.classList.add('hidden'); toggleStatus(b); });
        var delBtn = card.querySelector('.branch-delete');
        if (delBtn) delBtn.addEventListener('click', function (e) { e.preventDefault(); menu.classList.add('hidden'); confirmDelete(b); });
    }
    document.addEventListener('click', function () { document.querySelectorAll('.branch-dropdown-menu').forEach(function (m) { m.classList.add('hidden'); }); });

    function openAdd() {
        document.getElementById('branch-id').value = '';
        var urlCompany = getCompanyIdFromUrl();
        document.getElementById('branch-name').value = '';
        document.getElementById('branch-address').value = '';
        document.getElementById('branch-tin').value = '';
        document.getElementById('branch-bir-start').value = '';
        document.getElementById('branch-bir-end').value = '';
        var companySelect = document.getElementById('branch-company-id');
        companySelect.innerHTML = '<option value="">Select company</option>' + companiesList.map(function (c) { return '<option value="' + c.id + '">' + escapeHtml(c.name || '') + '</option>'; }).join('');
        companySelect.disabled = false;
        if (urlCompany) companySelect.value = urlCompany;
        modalTitle.textContent = 'Add Branch';
        modal.classList.remove('hidden'); modal.classList.add('show'); modal.style.display = 'flex'; document.body.style.overflow = 'hidden';
    }

    function openEdit(b) {
        document.getElementById('branch-id').value = b.id;
        var companyIdVal = b.company_id || (b.company && b.company.id) || '';
        document.getElementById('branch-name').value = b.name || '';
        document.getElementById('branch-address').value = b.address || '';
        document.getElementById('branch-tin').value = b.tin || '';
        document.getElementById('branch-bir-start').value = b.bir_series_start || '';
        document.getElementById('branch-bir-end').value = b.bir_series_end || '';
        var companySelect = document.getElementById('branch-company-id');
        companySelect.innerHTML = '<option value="">Select company</option>' + companiesList.map(function (c) { return '<option value="' + c.id + '">' + escapeHtml(c.name || '') + '</option>'; }).join('');
        companySelect.value = companyIdVal;
        companySelect.disabled = true;
        modalTitle.textContent = 'Edit Branch';
        modal.classList.remove('hidden'); modal.classList.add('show'); modal.style.display = 'flex'; document.body.style.overflow = 'hidden';
    }

    function toggleStatus(b) {
        var label = b.is_active !== false ? 'Deactivate' : 'Activate';
        Swal.fire({ title: label + ' Branch?', text: label + ' "' + (b.name || '') + '"?', icon: 'question', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#6b7280', confirmButtonText: 'Yes' }).then(function (r) {
            if (!r.isConfirmed) return;
            axios.patch(apiBase + '/branches/' + b.id + '/toggle-status', {}, authHeaders())
                .then(function (res) { showToastNotification('success', 'Done', (res.data && res.data.data && !res.data.data.is_active) ? 'Branch deactivated.' : 'Branch activated.'); loadBranches(); })
                .catch(function (err) { showToastNotification('error', 'Error', (err.response && err.response.data && err.response.data.message) || 'Request failed.'); });
        });
    }

    function confirmDelete(b) {
        Swal.fire({ title: 'Delete Branch?', text: 'Delete "' + (b.name || '') + '"? This cannot be undone.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, delete' }).then(function (r) {
            if (!r.isConfirmed) return;
            axios.delete(apiBase + '/branches/' + b.id, authHeaders())
                .then(function () { showToastNotification('success', 'Deleted', 'Branch deleted.'); loadBranches(); })
                .catch(function (err) { showToastNotification('error', 'Error', (err.response && err.response.data && err.response.data.message) || 'Delete failed.'); });
        });
    }

    function closeModal() {
        modal.classList.add('hidden'); modal.classList.remove('show'); modal.style.display = 'none'; document.body.style.overflow = '';
    }
    modal.querySelectorAll('[data-tw-dismiss="modal"]').forEach(function (btn) { btn.addEventListener('click', closeModal); });
    if (window.pulseModal && modal) {
        var backdrop = modal.querySelector('.modal-backdrop');
        if (backdrop) backdrop.addEventListener('click', function () { window.pulseModal(modal); });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!getToken()) { Swal.fire({ icon: 'warning', title: 'Login required', text: 'Please log in.' }); return; }
        var branchId = document.getElementById('branch-id').value;
        var companyId = document.getElementById('branch-company-id').value;
        var name = document.getElementById('branch-name').value.trim();
        if (!companyId) { Swal.fire({ icon: 'warning', title: 'Required', text: 'Please select a company.' }); return; }
        if (!name) { Swal.fire({ icon: 'warning', title: 'Required', text: 'Branch name is required.' }); return; }
        var payload = { name: name, address: document.getElementById('branch-address').value.trim(), tin: document.getElementById('branch-tin').value.trim(), bir_series_start: document.getElementById('branch-bir-start').value.trim(), bir_series_end: document.getElementById('branch-bir-end').value.trim() };
        if (!branchId) payload.company_id = parseInt(companyId, 10);
        submitBtn.disabled = true;
        var promise = branchId ? axios.put(apiBase + '/branches/' + branchId, payload, authHeaders()) : axios.post(apiBase + '/branches', payload, authHeaders());
        promise.then(function () { showToastNotification('success', 'Saved', branchId ? 'Branch updated.' : 'Branch created.'); closeModal(); loadBranches(); })
            .catch(function (err) { var msg = (err.response && err.response.data && err.response.data.message) || 'Save failed.'; if (err.response && err.response.data && err.response.data.errors) { var first = Object.values(err.response.data.errors)[0]; if (Array.isArray(first)) msg = first[0]; } showToastNotification('error', 'Error', msg); })
            .finally(function () { submitBtn.disabled = false; });
    });

    document.getElementById('branches-add-btn').addEventListener('click', openAdd);
    companyFilter.addEventListener('change', loadBranches);
    statusFilter.addEventListener('change', loadBranches);
    searchInput.addEventListener('input', function () { if (searchInput._timer) clearTimeout(searchInput._timer); searchInput._timer = setTimeout(loadBranches, 300); });
    runInitialLoad();
})();
</script>
@endpush
