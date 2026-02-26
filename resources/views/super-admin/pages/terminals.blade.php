@extends('super-admin.layouts.app')

@section('title', 'Terminals')
@section('breadcrumb', 'Terminals')

@section('content')
    <div class="intro-y mt-6 sm:mt-10 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
        <div>
            <h2 class="text-base font-semibold text-slate-800 dark:text-slate-100 sm:text-lg">Terminals</h2>
            <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400 max-w-2xl">Generate or revoke API keys so each POS device can identify as a terminal. Add the key to the device .env as <code class="rounded bg-slate-200 dark:bg-darkmode-500 px-1.5 py-0.5 text-xs">TERMINAL_API_KEY=...</code></p>
        </div>
        <div class="flex flex-wrap items-center gap-2 min-w-0">
            <div class="relative text-slate-500 flex-1 min-w-0 sm:flex-initial sm:w-52">
                <input type="text" id="terminals-search" placeholder="Search terminal or branch…" class="transition duration-200 ease-in-out text-sm border-slate-200 dark:border-transparent shadow-sm rounded-lg placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary dark:bg-darkmode-800 dark:placeholder:text-slate-500/80 box w-full min-w-0 sm:w-52 pr-9 py-2.5 sm:py-2 min-h-[44px] sm:min-h-0" aria-label="Search terminals">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4 pointer-events-none text-slate-400"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>
            <button type="button" id="terminal-add-btn" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-indigo-600 border border-indigo-600 px-4 py-2.5 sm:py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 hover:border-indigo-700 focus:ring-4 focus:ring-indigo-500/20 transition-colors touch-manipulation min-h-[44px] w-full sm:w-auto flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add terminal
            </button>
        </div>
    </div>

    <div class="intro-y mt-3">
        <span id="terminals-summary-badge" class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 dark:bg-darkmode-600 px-3 py-1 text-xs font-medium text-slate-600 dark:text-slate-400" aria-live="polite" role="status">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            <span id="terminals-summary-text">Loading…</span>
        </span>
    </div>

    <div class="intro-y mt-4 sm:mt-5 space-y-3">
        <div id="terminals-accordion-wrapper" class="min-w-0"></div>
    </div>

    <div id="terminals-empty" class="intro-y mt-6 hidden text-center py-16 text-slate-400 dark:text-slate-500">
        <p class="text-sm">No terminals found.</p>
    </div>
    <div id="terminals-error" class="intro-y mt-6 hidden rounded-lg border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 p-4 text-red-700 dark:text-red-300">
        <p id="terminals-error-text"></p>
    </div>
@endsection

@push('modals')
    {{-- Create terminal modal (same style as Add Company) --}}
    <x-modal id="terminal-create-modal" title="Add terminal" title-id="terminal-create-title" size="lg">
        <form id="terminal-create-form" class="flex flex-col min-h-0">
            <div class="px-5 sm:px-6 md:px-8 py-5 sm:py-6 overflow-y-auto overscroll-contain min-h-0 flex-1 bg-slate-50/50 dark:bg-darkmode-800/50">
                <div class="card-margin rounded-2xl bg-white dark:bg-darkmode-800 shadow-sm ring-1 ring-slate-200/80 dark:ring-darkmode-600/80 p-5 sm:p-6 space-y-5">
                    <h3 class="text-[11px] font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500 pb-3 border-b border-slate-100 dark:border-darkmode-600">Terminal details</h3>
                    <div>
                        <label for="create-branch-id">Branch <span class="text-red-500">*</span></label>
                        <div class="mt-2">
                            <select id="create-branch-id" required data-placeholder="Select branch" class="tom-select w-full">
                                <option value="">Select branch</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="create-code" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Code <span class="text-red-500">*</span></label>
                        <input type="text" id="create-code" required maxlength="50" placeholder="e.g. T1" class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-slate-50/50 dark:bg-darkmode-700/50 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-white dark:focus:bg-darkmode-700 outline-none transition" />
                    </div>
                    <div>
                        <label for="create-name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Name</label>
                        <input type="text" id="create-name" maxlength="255" placeholder="Optional display name" class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-slate-50/50 dark:bg-darkmode-700/50 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-white dark:focus:bg-darkmode-700 outline-none transition" />
                    </div>
                    <div>
                        <label for="create-min" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">MIN</label>
                        <input type="text" id="create-min" maxlength="50" placeholder="Machine Identification Number (BIR)" class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-slate-50/50 dark:bg-darkmode-700/50 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-white dark:focus:bg-darkmode-700 outline-none transition" />
                    </div>
                    <div>
                        <label for="create-tin" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">TIN</label>
                        <input type="text" id="create-tin" maxlength="50" placeholder="Tax Identification Number" class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-slate-50/50 dark:bg-darkmode-700/50 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-white dark:focus:bg-darkmode-700 outline-none transition" />
                    </div>
                    <div class="flex items-center justify-between gap-4 rounded-xl bg-slate-50/80 dark:bg-darkmode-700/50 ring-1 ring-slate-200/60 dark:ring-darkmode-600 px-5 py-4">
                        <div>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Active</p>
                            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Terminal can be used for POS sessions</p>
                        </div>
                        <input type="checkbox" id="create-is-active" checked class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" />
                    </div>
                </div>
            </div>
            <div class="modal-footer flex-shrink-0 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 px-6 sm:px-8 py-5 border-t border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800">
                <button type="button" data-tw-dismiss="modal" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-slate-200 dark:border-darkmode-500 text-slate-700 dark:text-slate-300 bg-white dark:bg-darkmode-700 hover:bg-slate-50 dark:hover:bg-darkmode-600 font-medium text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300 dark:focus:ring-darkmode-500 focus:ring-offset-2">
                    Cancel
                </button>
                <button type="button" id="terminal-create-submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-primary text-white font-semibold text-sm shadow-sm hover:bg-primary/90 hover:shadow focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-darkmode-800 transition-all min-w-[8.5rem]">
                    Create
                </button>
            </div>
        </form>
    </x-modal>

    {{-- Edit terminal modal --}}
    <x-modal id="terminal-edit-modal" title="Edit terminal" title-id="terminal-edit-title" size="lg">
        <form id="terminal-edit-form" class="flex flex-col min-h-0">
            <div class="px-5 sm:px-6 md:px-8 py-5 sm:py-6 overflow-y-auto overscroll-contain min-h-0 flex-1 bg-slate-50/50 dark:bg-darkmode-800/50">
                <div class="card-margin rounded-2xl bg-white dark:bg-darkmode-800 shadow-sm ring-1 ring-slate-200/80 dark:ring-darkmode-600/80 p-5 sm:p-6 space-y-5">
                    <h3 class="text-[11px] font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500 pb-3 border-b border-slate-100 dark:border-darkmode-600">Terminal details</h3>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Branch</label>
                        <p id="edit-branch-name" class="text-sm text-slate-600 dark:text-slate-400"></p>
                    </div>
                    <div>
                        <label for="edit-code" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Code <span class="text-red-500">*</span></label>
                        <input type="text" id="edit-code" required maxlength="50" class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-slate-50/50 dark:bg-darkmode-700/50 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-white dark:focus:bg-darkmode-700 outline-none transition" />
                    </div>
                    <div>
                        <label for="edit-name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Name</label>
                        <input type="text" id="edit-name" maxlength="255" class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-slate-50/50 dark:bg-darkmode-700/50 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-white dark:focus:bg-darkmode-700 outline-none transition" />
                    </div>
                    <div>
                        <label for="edit-min" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">MIN</label>
                        <input type="text" id="edit-min" maxlength="50" class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-slate-50/50 dark:bg-darkmode-700/50 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-white dark:focus:bg-darkmode-700 outline-none transition" />
                    </div>
                    <div>
                        <label for="edit-tin" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">TIN</label>
                        <input type="text" id="edit-tin" maxlength="50" class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-slate-50/50 dark:bg-darkmode-700/50 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-white dark:focus:bg-darkmode-700 outline-none transition" />
                    </div>
                    <div class="flex items-center justify-between gap-4 rounded-xl bg-slate-50/80 dark:bg-darkmode-700/50 ring-1 ring-slate-200/60 dark:ring-darkmode-600 px-5 py-4">
                        <div>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Active</p>
                            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Terminal can be used for POS sessions</p>
                        </div>
                        <input type="checkbox" id="edit-is-active" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" />
                    </div>
                </div>
            </div>
            <div class="modal-footer flex-shrink-0 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 px-6 sm:px-8 py-5 border-t border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800">
                <button type="button" data-tw-dismiss="modal" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-slate-200 dark:border-darkmode-500 text-slate-700 dark:text-slate-300 bg-white dark:bg-darkmode-700 hover:bg-slate-50 dark:hover:bg-darkmode-600 font-medium text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300 dark:focus:ring-darkmode-500 focus:ring-offset-2">
                    Cancel
                </button>
                <button type="button" id="terminal-edit-submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-primary text-white font-semibold text-sm shadow-sm hover:bg-primary/90 hover:shadow focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-darkmode-800 transition-all min-w-[8.5rem]">
                    Save
                </button>
            </div>
        </form>
    </x-modal>
@endpush

@push('scripts')
<script>
(function () {
    var apiBase = '{{ url("/api/v1") }}';
    var token = localStorage.getItem('super_admin_token');
    if (!token) {
        window.location.href = '{{ route("dashboard.login") }}';
        return;
    }
    var authHeaders = { headers: { Authorization: 'Bearer ' + token, Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } };

    var currentUserRole = 'super_admin';

    var wrapper = document.getElementById('terminals-accordion-wrapper');
    var summaryText = document.getElementById('terminals-summary-text');
    var emptyEl = document.getElementById('terminals-empty');
    var errorEl = document.getElementById('terminals-error');
    var errorText = document.getElementById('terminals-error-text');
    var terminalsList = [];

    // Company color system (same as branches): distinct colors per company for accordion badges and branch header.
    var COMPANY_BORDER_COLORS = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#0ea5e9', '#ec4899', '#14b8a6'];
    function companyBorderColor(companyId) { return COMPANY_BORDER_COLORS[(companyId || 0) % COMPANY_BORDER_COLORS.length]; }

    function escapeHtml(s) {
        if (!s) return '';
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    function formatDate(iso) {
        if (!iso) return '—';
        try {
            var d = new Date(iso);
            return isNaN(d.getTime()) ? '—' : d.toLocaleString(undefined, { dateStyle: 'short', timeStyle: 'short' });
        } catch (e) { return '—'; }
    }

    function groupByBranch(list) {
        var groups = {};
        (list || []).forEach(function (t) {
            var key = (t.branch_id || '') + '|' + (t.branch_name || 'Unnamed branch');
            if (!groups[key]) groups[key] = { branch_name: t.branch_name || 'Unnamed branch', terminals: [] };
            groups[key].terminals.push(t);
        });
        return Object.keys(groups).sort().map(function (k) { return groups[k]; });
    }

    function groupByCompany(list) {
        var groups = {};
        (list || []).forEach(function (t) {
            var cid = t.company_id != null ? String(t.company_id) : '';
            var cname = t.company_name || 'Unnamed company';
            var key = cid + '|' + cname;
            if (!groups[key]) groups[key] = { company_id: cid, company_name: cname, terminals: [] };
            groups[key].terminals.push(t);
        });
        return Object.keys(groups).sort(function (a, b) {
            var an = (groups[a].company_name || '').toLowerCase();
            var bn = (groups[b].company_name || '').toLowerCase();
            return an.localeCompare(bn);
        }).map(function (k) { return groups[k]; });
    }

    var theadHtml = '<thead><tr class="border-b border-slate-200/60 dark:border-darkmode-400 bg-slate-50/60 dark:bg-darkmode-700/40">'
        + '<th class="py-2.5 pl-5 pr-3 text-left font-semibold text-slate-600 dark:text-slate-400">Terminal</th>'
        + '<th class="py-2.5 px-3 text-center font-semibold text-slate-600 dark:text-slate-400">Status</th>'
        + '<th class="py-2.5 px-3 text-center font-semibold text-slate-600 dark:text-slate-400">Registered</th>'
        + '<th class="terminals-last-used-col py-2.5 px-3 text-left font-semibold text-slate-600 dark:text-slate-400">Last used</th>'
        + '<th class="py-2.5 pl-3 pr-5 w-20 text-right font-semibold text-slate-600 dark:text-slate-400">Actions</th></tr></thead>';

    function buildTerminalRowsHtml(terminals, badges, companyId) {
        var html = '';
        var borderColor = companyBorderColor(companyId);
        var grps = groupByBranch(terminals);
        grps.forEach(function (grp) {
            html += '<tr class="bg-slate-200/90 dark:bg-darkmode-600"><td colspan="5" class="terminals-branch-header py-2 pl-4 pr-5 text-[0.8rem] font-bold uppercase tracking-wide text-slate-700 dark:text-slate-200 border-l-[4px]" style="border-left-color:' + borderColor + '">' + escapeHtml(grp.branch_name) + '</td></tr>';
            grp.terminals.forEach(function (t) {
                var registered = t.is_registered === true;
                var registeredBadge = registered ? '<span class="' + badges.active + '">Yes</span>' : '<span class="' + badges.attention + '" title="Generate a key to register this terminal.">No</span>';
                var statusBadge = t.is_active !== false ? '<span class="' + badges.active + '">Active</span>' : '<span class="' + badges.inactive + '">Inactive</span>';
                var terminalLabel = (t.name || t.code || 'Terminal #' + t.id) + (t.code ? ' <span class="text-slate-500 dark:text-slate-400 font-normal">(' + escapeHtml(t.code) + ')</span>' : '');
                var isAdminOnly = currentUserRole === 'admin';
                var revokeDisabled = registered ? '' : ' disabled';
                var revokeClass = 'terminal-revoke-key block w-full text-left px-3 py-2 text-xs font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors touch-manipulation border-0 bg-transparent cursor-pointer' + (registered ? '' : ' opacity-50 cursor-not-allowed');
                var lastUsedText = t.api_key_last_used_at ? formatDate(t.api_key_last_used_at) : '—';
                var rowData = ' data-terminal-id="' + t.id + '" data-branch-id="' + t.branch_id + '" data-branch-name="' + escapeHtml(t.branch_name || '') + '" data-code="' + escapeHtml(t.code || '') + '" data-name="' + escapeHtml(t.name || '') + '" data-min="' + escapeHtml(t.min || '') + '" data-tin="' + escapeHtml(t.tin || '') + '" data-is-active="' + (t.is_active !== false ? '1' : '0') + '"';
                var actionsMenuHtml = '<div class="terminal-actions-menu hidden min-w-[160px] rounded-lg border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 shadow-xl py-1 z-[100]">'
                    + '<button type="button" class="terminal-edit block w-full text-left px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-darkmode-600 rounded transition-colors touch-manipulation border-0 bg-transparent cursor-pointer" data-branch-id="' + t.branch_id + '" data-terminal-id="' + t.id + '">Edit</button>';
                if (!isAdminOnly) {
                    actionsMenuHtml += '<button type="button" class="terminal-generate-key block w-full text-left px-3 py-2 text-xs font-medium text-primary hover:bg-primary/10 dark:hover:bg-primary/20 rounded transition-colors touch-manipulation border-0 bg-transparent cursor-pointer" data-branch-id="' + t.branch_id + '" data-terminal-id="' + t.id + '">Generate key</button>'
                        + '<button type="button" class="' + revokeClass + '" data-branch-id="' + t.branch_id + '" data-terminal-id="' + t.id + '"' + revokeDisabled + '>Revoke key</button>'
                        + '<button type="button" class="terminal-delete block w-full text-left px-3 py-2 text-xs font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors touch-manipulation border-0 bg-transparent cursor-pointer" data-branch-id="' + t.branch_id + '" data-terminal-id="' + t.id + '">Delete</button>';
                }
                actionsMenuHtml += '</div>';
                html += '<tr class="hover:bg-slate-100 dark:hover:bg-darkmode-600/70 transition-colors"' + rowData + '>'
                    + '<td class="py-2.5 pl-5 pr-3 font-medium text-slate-800 dark:text-slate-100">' + terminalLabel + '</td>'
                    + '<td class="py-2.5 px-3 text-center">' + statusBadge + '</td>'
                    + '<td class="py-2.5 px-3 text-center">' + registeredBadge + '</td>'
                    + '<td class="py-2.5 px-3 terminals-last-used-col terminals-last-used-meta">' + lastUsedText + '</td>'
                    + '<td class="py-2.5 pl-3 pr-5 text-right">'
                    + '<div class="relative inline-block text-right">'
                    + '<button type="button" class="terminal-actions-btn rounded-lg border border-slate-200 dark:border-darkmode-500 p-3 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors touch-manipulation min-h-[44px] min-w-[44px]" data-branch-id="' + t.branch_id + '" data-terminal-id="' + t.id + '" data-registered="' + (registered ? '1' : '0') + '" aria-haspopup="true" aria-expanded="false" aria-label="Actions">'
                    + '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1.5"/><circle cx="6" cy="12" r="1.5"/><circle cx="18" cy="12" r="1.5"/></svg>'
                    + '</button>'
                    + actionsMenuHtml
                    + '</div></td></tr>';
            });
        });
        return html;
    }

    var activePortalMenu = null;

    function closePortalMenu() {
        if (activePortalMenu && activePortalMenu.parentNode) {
            activePortalMenu.parentNode.removeChild(activePortalMenu);
            activePortalMenu = null;
        }
        document.removeEventListener('click', closePortalMenuOnDocClick);
        document.removeEventListener('scroll', closePortalMenuOnScroll, true);
    }

    function closePortalMenuOnDocClick(e) {
        if (activePortalMenu && e.target && !activePortalMenu.contains(e.target)) closePortalMenu();
    }

    function closePortalMenuOnScroll() { closePortalMenu(); }

    function openTerminalMenuPortal(menu, x, y, alignRight) {
        closePortalMenu();
        var clone = menu.cloneNode(true);
        clone.classList.remove('hidden');
        clone.style.position = 'fixed';
        clone.style.left = '-9999px';
        clone.style.top = '0';
        document.body.appendChild(clone);
        var pad = 8;
        var menuWidth = clone.offsetWidth;
        var menuHeight = clone.offsetHeight;
        var left = alignRight ? (x - menuWidth) : x;
        var top = y;
        if (left + menuWidth > window.innerWidth - pad) left = window.innerWidth - menuWidth - pad;
        if (left < pad) left = pad;
        if (top + menuHeight > window.innerHeight - pad) top = window.innerHeight - menuHeight - pad;
        if (top < pad) top = pad;
        clone.style.left = left + 'px';
        clone.style.top = top + 'px';
        activePortalMenu = clone;

        clone.addEventListener('click', function (e) {
            e.stopPropagation();
            var target = e.target.closest('button');
            if (!target) return;
            var branchId = target.getAttribute('data-branch-id');
            var terminalId = target.getAttribute('data-terminal-id');
            if (target.classList.contains('terminal-edit')) {
                var row = document.querySelector('tr[data-terminal-id="' + terminalId + '"][data-branch-id="' + branchId + '"]');
                if (row) openEditModal({
                    branch_id: row.getAttribute('data-branch-id'),
                    branch_name: row.getAttribute('data-branch-name') || '',
                    terminal_id: row.getAttribute('data-terminal-id'),
                    code: row.getAttribute('data-code') || '',
                    name: row.getAttribute('data-name') || '',
                    min: row.getAttribute('data-min') || '',
                    tin: row.getAttribute('data-tin') || '',
                    is_active: row.getAttribute('data-is-active') === '1'
                });
            } else if (target.classList.contains('terminal-generate-key')) generateKey(branchId, terminalId);
            else if (target.classList.contains('terminal-revoke-key') && !target.disabled) revokeKey(branchId, terminalId);
            else if (target.classList.contains('terminal-delete')) deleteTerminal(branchId, terminalId);
            else return;
            closePortalMenu();
        });

        setTimeout(function () { document.addEventListener('click', closePortalMenuOnDocClick); }, 0);
        document.addEventListener('scroll', closePortalMenuOnScroll, true);
    }

    function bindTerminalRowEvents(tbodyEl, accordionWrapper) {
        tbodyEl.querySelectorAll('.terminal-actions-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var menu = btn.nextElementSibling;
                if (!menu || !menu.classList.contains('terminal-actions-menu')) return;
                var rect = btn.getBoundingClientRect();
                openTerminalMenuPortal(menu, rect.right, rect.bottom + 4, true);
            });
        });
        tbodyEl.querySelectorAll('tr[data-terminal-id]').forEach(function (row) {
            row.addEventListener('contextmenu', function (e) {
                e.preventDefault();
                var btn = row.querySelector('.terminal-actions-btn');
                var menu = btn && btn.nextElementSibling;
                if (!menu || !menu.classList.contains('terminal-actions-menu')) return;
                openTerminalMenuPortal(menu, e.clientX, e.clientY, false);
            });
        });
        tbodyEl.querySelectorAll('.terminal-generate-key').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                btn.closest('.terminal-actions-menu').classList.add('hidden');
                generateKey(btn.getAttribute('data-branch-id'), btn.getAttribute('data-terminal-id'));
            });
        });
        tbodyEl.querySelectorAll('.terminal-revoke-key').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                if (btn.disabled) return;
                btn.closest('.terminal-actions-menu').classList.add('hidden');
                revokeKey(btn.getAttribute('data-branch-id'), btn.getAttribute('data-terminal-id'));
            });
        });
        tbodyEl.querySelectorAll('.terminal-edit').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var menu = btn.closest('.terminal-actions-menu');
                if (menu) menu.classList.add('hidden');
                var row = btn.closest('tr');
                if (!row) return;
                openEditModal({
                    branch_id: row.getAttribute('data-branch-id'),
                    branch_name: row.getAttribute('data-branch-name') || '',
                    terminal_id: row.getAttribute('data-terminal-id'),
                    code: row.getAttribute('data-code') || '',
                    name: row.getAttribute('data-name') || '',
                    min: row.getAttribute('data-min') || '',
                    tin: row.getAttribute('data-tin') || '',
                    is_active: row.getAttribute('data-is-active') === '1'
                });
            });
        });
        tbodyEl.querySelectorAll('.terminal-delete').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                btn.closest('.terminal-actions-menu').classList.add('hidden');
                deleteTerminal(btn.getAttribute('data-branch-id'), btn.getAttribute('data-terminal-id'));
            });
        });
    }

    function renderRows(list) {
        var searchVal = (document.getElementById('terminals-search') && document.getElementById('terminals-search').value) ? document.getElementById('terminals-search').value.trim().toLowerCase() : '';
        var filtered = (list || []).filter(function (t) {
            if (!searchVal) return true;
            var name = (t.name || '').toLowerCase();
            var code = (t.code || '').toLowerCase();
            var branch = (t.branch_name || '').toLowerCase();
            return name.indexOf(searchVal) !== -1 || code.indexOf(searchVal) !== -1 || branch.indexOf(searchVal) !== -1;
        });

        if (!list || list.length === 0) {
            wrapper.innerHTML = '<div class="min-w-[520px] md:min-w-0"><table class="w-full text-sm">' + theadHtml + '<tbody class="divide-y divide-slate-200 dark:divide-darkmode-500"><tr><td colspan="5" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">No terminals found.</td></tr></tbody></table></div>';
            if (emptyEl) emptyEl.classList.remove('hidden');
            return;
        }
        if (emptyEl) emptyEl.classList.add('hidden');

        if (filtered.length === 0) {
            wrapper.innerHTML = '<div class="min-w-[520px] md:min-w-0"><table class="w-full text-sm">' + theadHtml + '<tbody class="divide-y divide-slate-200 dark:divide-darkmode-500"><tr><td colspan="5" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">No terminals match your search.</td></tr></tbody></table></div>';
            return;
        }

        var hasAnyLastUsed = filtered.some(function (t) { return !!t.api_key_last_used_at; });
        var badges = {
            active: 'inline-flex items-center gap-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400',
            attention: 'inline-flex items-center rounded-full bg-amber-100 dark:bg-amber-900/30 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:text-amber-400',
            inactive: 'inline-flex items-center rounded-full bg-slate-200 dark:bg-darkmode-500 px-2.5 py-0.5 text-xs font-semibold text-slate-500 dark:text-slate-400'
        };
        var companies = groupByCompany(filtered);
        wrapper.innerHTML = '';

        companies.forEach(function (company) {
            var companyId = company.company_id;
            var branchCount = groupByBranch(company.terminals).length;
            var terminalCount = company.terminals.length;
            var sectionId = 'terminals-company-' + (companyId || 'unknown').replace(/\s/g, '_');
            var contentId = sectionId + '-content';
            var btnId = sectionId + '-btn';
            var chevronId = sectionId + '-chevron';
            var tableClass = 'w-full text-sm' + (!hasAnyLastUsed ? ' hide-last-used' : '');
            var tbodyHtml = buildTerminalRowsHtml(company.terminals, badges, companyId);
            var summaryText = '(' + terminalCount + ' terminal' + (terminalCount !== 1 ? 's' : '') + ' · ' + branchCount + ' branch' + (branchCount !== 1 ? 'es' : '') + ')';
            var sectionHtml = '<div class="terminals-accordion-section rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm overflow-hidden">'
                + '<button type="button" id="' + btnId + '" class="terminals-accordion-btn w-full flex items-center gap-3 px-4 sm:px-5 py-4 sm:py-5 text-left font-semibold text-slate-800 dark:text-slate-100 hover:bg-slate-50/80 dark:hover:bg-darkmode-700/50 transition-colors touch-manipulation" aria-expanded="true" aria-controls="' + contentId + '" data-section="' + sectionId + '">'
                + '<svg id="' + chevronId + '" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="terminals-accordion-chevron text-slate-400 dark:text-slate-500 transition-transform flex-shrink-0" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>'
                + '<span class="flex-1 min-w-0"><span class="text-slate-800 dark:text-slate-100">' + escapeHtml(company.company_name) + '</span> <span class="text-sm font-normal text-slate-500 dark:text-slate-400">' + summaryText + '</span></span>'
                + '</button>'
                + '<div id="' + contentId + '" class="terminals-accordion-content border-t border-slate-200/80 dark:border-darkmode-600 bg-slate-50/30 dark:bg-darkmode-700/30" role="region">'
                + '<div class="overflow-x-auto"><div class="min-w-[520px] md:min-w-0"><table class="' + tableClass + '">' + theadHtml + '<tbody class="divide-y divide-slate-200 dark:divide-darkmode-500">' + tbodyHtml + '</tbody></table></div></div>'
                + '</div></div>';
            wrapper.insertAdjacentHTML('beforeend', sectionHtml);

            var contentDiv = document.getElementById(contentId);
            var tbodyEl = contentDiv && contentDiv.querySelector('tbody');
            if (tbodyEl) bindTerminalRowEvents(tbodyEl, wrapper);

            var btn = document.getElementById(btnId);
            var chevron = document.getElementById(chevronId);
            if (chevron) chevron.style.transform = 'rotate(90deg)';
            if (btn && contentDiv) {
                btn.addEventListener('click', function () {
                    var hidden = contentDiv.classList.toggle('hidden');
                    btn.setAttribute('aria-expanded', !hidden);
                    if (chevron) chevron.style.transform = hidden ? '' : 'rotate(90deg)';
                });
            }
        });
    }

    function generateKey(branchId, terminalId) {
        if (typeof Swal === 'undefined') {
            if (confirm('Generate a new API key for this terminal? The current key (if any) will stop working. Copy the new key when shown.')) {
                doGenerateKey(branchId, terminalId);
            }
            return;
        }
        Swal.fire({
            title: 'Generate terminal key?',
            text: 'The current key (if any) will stop working. You will see the new key once — add it to your POS .env as TERMINAL_API_KEY=...',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Generate',
            cancelButtonText: 'Cancel'
        }).then(function (result) {
            if (result.isConfirmed) doGenerateKey(branchId, terminalId);
        });
    }

    function doGenerateKey(branchId, terminalId) {
        axios.post(apiBase + '/branches/' + branchId + '/terminals/' + terminalId + '/generate-key', {}, authHeaders)
            .then(function (r) {
                var data = r.data && r.data.data ? r.data.data : {};
                var key = data.key || '';
                var envLine = data.env_line || ('TERMINAL_API_KEY=' + key);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Key generated',
                        html: '<p class="text-left text-sm text-slate-600 dark:text-slate-400 mb-3">Copy this key now. It will not be shown again.</p>'
                            + '<pre class="text-left p-3 rounded-lg bg-slate-100 dark:bg-darkmode-700 text-xs break-all select-all">' + escapeHtml(envLine) + '</pre>'
                            + '<p class="text-left text-xs text-slate-500 mt-2">Add the line above to your POS device .env file.</p>',
                        icon: 'success',
                        width: 520,
                        didOpen: function () {
                            var pre = Swal.getHtmlContainer().querySelector('pre');
                            if (pre && key) pre.addEventListener('click', function () { navigator.clipboard && navigator.clipboard.writeText(envLine); });
                        }
                    }).then(function () {
                        if (typeof showToastNotification === 'function') showToastNotification('success', 'Done', 'Key generated. Add it to your POS .env.');
                        loadTerminals();
                    });
                } else {
                    alert('Key: ' + key + '\n\nAdd to .env: ' + envLine);
                    loadTerminals();
                }
            })
            .catch(function (err) {
                var msg = (err.response && err.response.data && err.response.data.message) || 'Failed to generate key.';
                if (typeof showToastNotification === 'function') showToastNotification('error', 'Error', msg);
                else if (typeof Swal !== 'undefined') Swal.fire({ title: 'Error', text: msg, icon: 'error' });
                else alert(msg);
            });
    }

    function revokeKey(branchId, terminalId) {
        if (typeof Swal === 'undefined') {
            if (confirm('Revoke this terminal\'s API key? The POS will no longer be able to identify as this terminal.')) {
                doRevokeKey(branchId, terminalId);
            }
            return;
        }
        Swal.fire({
            title: 'Revoke terminal key?',
            text: 'The key will stop working. You will need to generate a new key for this terminal to use it again.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Revoke',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#ef4444'
        }).then(function (result) {
            if (result.isConfirmed) doRevokeKey(branchId, terminalId);
        });
    }

    function doRevokeKey(branchId, terminalId) {
        axios.post(apiBase + '/branches/' + branchId + '/terminals/' + terminalId + '/revoke-key', {}, authHeaders)
            .then(function () {
                if (typeof showToastNotification === 'function') showToastNotification('success', 'Done', 'Key revoked.');
                loadTerminals();
            })
            .catch(function (err) {
                var msg = (err.response && err.response.data && err.response.data.message) || 'Failed to revoke key.';
                if (typeof showToastNotification === 'function') showToastNotification('error', 'Error', msg);
                else if (typeof Swal !== 'undefined') Swal.fire({ title: 'Error', text: msg, icon: 'error' });
                else alert(msg);
            });
    }

    var branchesList = [];
    function loadBranches() {
        return axios.get(apiBase + '/branches', authHeaders).then(function (r) {
            branchesList = (r.data && r.data.data) ? r.data.data : [];
            return branchesList;
        });
    }

    var createModal = document.getElementById('terminal-create-modal');
    var editModal = document.getElementById('terminal-edit-modal');
    var editState = { branch_id: null, terminal_id: null };
    var createBranchTomSelect = null;

    function openCreateModal() {
        loadBranches().then(function () {
            var sel = document.getElementById('create-branch-id');
            if (createBranchTomSelect) {
                createBranchTomSelect.destroy();
                createBranchTomSelect = null;
            }
            sel.innerHTML = '<option value="">Select branch</option>';
            branchesList.forEach(function (b) {
                var opt = document.createElement('option');
                opt.value = b.id;
                opt.textContent = (b.company && b.company.name ? b.company.name + ' — ' : '') + (b.name || 'Branch #' + b.id);
                sel.appendChild(opt);
            });
            document.getElementById('create-code').value = '';
            document.getElementById('create-name').value = '';
            document.getElementById('create-min').value = '';
            document.getElementById('create-tin').value = '';
            document.getElementById('create-is-active').checked = true;
            if (typeof TomSelect !== 'undefined') {
                createBranchTomSelect = new TomSelect(sel, { plugins: { dropdown_input: {} }, placeholder: 'Select branch' });
            }
            if (createModal) { createModal.classList.remove('hidden'); createModal.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
        }).catch(function (err) {
            var msg = (err.response && err.response.data && err.response.data.message) || 'Failed to load branches.';
            if (typeof Swal !== 'undefined') Swal.fire({ title: 'Error', text: msg, icon: 'error' });
        });
    }

    function closeCreateModal() {
        if (createBranchTomSelect) { createBranchTomSelect.destroy(); createBranchTomSelect = null; }
        if (createModal) { createModal.classList.add('hidden'); createModal.style.display = 'none'; document.body.style.overflow = ''; }
    }
    function closeEditModal() { if (editModal) { editModal.classList.add('hidden'); editModal.style.display = 'none'; document.body.style.overflow = ''; } }

    function openEditModal(data) {
        editState = { branch_id: data.branch_id, terminal_id: data.terminal_id };
        document.getElementById('edit-branch-name').textContent = data.branch_name || '—';
        document.getElementById('edit-code').value = data.code || '';
        document.getElementById('edit-name').value = data.name || '';
        document.getElementById('edit-min').value = data.min || '';
        document.getElementById('edit-tin').value = data.tin || '';
        document.getElementById('edit-is-active').checked = !!data.is_active;
        if (editModal) { editModal.classList.remove('hidden'); editModal.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
    }

    function submitCreate() {
        var branchId = document.getElementById('create-branch-id').value;
        var code = document.getElementById('create-code').value.trim();
        if (!branchId || !code) {
            if (typeof Swal !== 'undefined') Swal.fire({ title: 'Required', text: 'Please select a branch and enter a code.', icon: 'warning' });
            return;
        }
        var payload = {
            code: code,
            name: document.getElementById('create-name').value.trim() || null,
            min: document.getElementById('create-min').value.trim() || null,
            tin: document.getElementById('create-tin').value.trim() || null,
            is_active: document.getElementById('create-is-active').checked
        };
        axios.post(apiBase + '/branches/' + branchId + '/terminals', payload, authHeaders)
            .then(function () {
                if (typeof showToastNotification === 'function') showToastNotification('success', 'Saved', 'Terminal created.');
                closeCreateModal();
                loadTerminals();
            })
            .catch(function (err) {
                var msg = (err.response && err.response.data && err.response.data.message) || 'Failed to create terminal.';
                var errs = err.response && err.response.data && err.response.data.errors;
                if (errs) msg = Object.keys(errs).map(function (k) { return k + ': ' + (errs[k][0] || ''); }).join('\n');
                if (typeof showToastNotification === 'function') showToastNotification('error', 'Error', msg);
                else if (typeof Swal !== 'undefined') Swal.fire({ title: 'Error', text: msg, icon: 'error' });
                else alert(msg);
            });
    }

    function submitEdit() {
        var branchId = editState.branch_id;
        var terminalId = editState.terminal_id;
        var code = document.getElementById('edit-code').value.trim();
        if (!branchId || !terminalId || !code) {
            if (typeof Swal !== 'undefined') Swal.fire({ title: 'Required', text: 'Code is required.', icon: 'warning' });
            return;
        }
        var payload = {
            code: code,
            name: document.getElementById('edit-name').value.trim() || null,
            min: document.getElementById('edit-min').value.trim() || null,
            tin: document.getElementById('edit-tin').value.trim() || null,
            is_active: document.getElementById('edit-is-active').checked
        };
        axios.put(apiBase + '/branches/' + branchId + '/terminals/' + terminalId, payload, authHeaders)
            .then(function () {
                if (typeof showToastNotification === 'function') showToastNotification('success', 'Saved', 'Terminal updated.');
                closeEditModal();
                loadTerminals();
            })
            .catch(function (err) {
                var msg = (err.response && err.response.data && err.response.data.message) || 'Failed to update terminal.';
                var errs = err.response && err.response.data && err.response.data.errors;
                if (errs) msg = Object.keys(errs).map(function (k) { return k + ': ' + (errs[k][0] || ''); }).join('\n');
                if (typeof showToastNotification === 'function') showToastNotification('error', 'Error', msg);
                else if (typeof Swal !== 'undefined') Swal.fire({ title: 'Error', text: msg, icon: 'error' });
                else alert(msg);
            });
    }

    function deleteTerminal(branchId, terminalId) {
        if (typeof Swal === 'undefined') {
            if (confirm('Delete this terminal? This cannot be undone.')) doDeleteTerminal(branchId, terminalId);
            return;
        }
        Swal.fire({
            title: 'Delete terminal?',
            text: 'This cannot be undone. The terminal and its key (if any) will be removed.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#ef4444'
        }).then(function (result) {
            if (result.isConfirmed) doDeleteTerminal(branchId, terminalId);
        });
    }

    function doDeleteTerminal(branchId, terminalId) {
        axios.delete(apiBase + '/branches/' + branchId + '/terminals/' + terminalId, authHeaders)
            .then(function () {
                if (typeof showToastNotification === 'function') showToastNotification('success', 'Deleted', 'Terminal deleted.');
                loadTerminals();
            })
            .catch(function (err) {
                var msg = (err.response && err.response.data && err.response.data.message) || 'Failed to delete terminal.';
                if (typeof showToastNotification === 'function') showToastNotification('error', 'Error', msg);
                else if (typeof Swal !== 'undefined') Swal.fire({ title: 'Error', text: msg, icon: 'error' });
                else alert(msg);
            });
    }

    function loadTerminals() {
        errorEl.classList.add('hidden');
        axios.get(apiBase + '/terminals', authHeaders)
            .then(function (r) {
                var list = (r.data && r.data.data) ? r.data.data : [];
                if (!Array.isArray(list)) list = [];
                terminalsList = list;
                summaryText.textContent = '\u{1F5A5} ' + list.length + ' terminal' + (list.length !== 1 ? 's' : '');
                renderRows(list);
            })
            .catch(function (err) {
                var msg = (err.response && err.response.data && err.response.data.message) || 'Failed to load terminals.';
                if (err.response && err.response.status === 403) msg = 'Only Super Admin can view terminals.';
                errorText.textContent = msg;
                errorEl.classList.remove('hidden');
                tbody.innerHTML = '';
                summaryText.textContent = 'Error';
            });
    }

    document.addEventListener('click', function () {
        closePortalMenu();
        var menus = document.querySelectorAll('.terminal-actions-menu');
        menus.forEach(function (m) { m.classList.add('hidden'); });
    });

    axios.get(apiBase + '/auth/me', authHeaders).then(function (r) {
        var d = r.data && r.data.data ? r.data.data : r.data;
        currentUserRole = (d && d.user && d.user.role) ? d.user.role : 'super_admin';
        loadTerminals();
        var addBtn = document.getElementById('terminal-add-btn');
        if (addBtn && currentUserRole === 'admin') addBtn.style.display = 'none';
    }).catch(function () {
        currentUserRole = 'super_admin';
        loadTerminals();
    });

    var searchEl = document.getElementById('terminals-search');
    if (searchEl) searchEl.addEventListener('input', function () { renderRows(terminalsList); });

    var addBtn = document.getElementById('terminal-add-btn');
    if (addBtn) addBtn.addEventListener('click', openCreateModal);
    if (createModal) {
        createModal.querySelectorAll('[data-tw-dismiss="modal"]').forEach(function (btn) { btn.addEventListener('click', closeCreateModal); });
        if (window.pulseModal) {
            var createBackdrop = createModal.querySelector('.modal-backdrop');
            if (createBackdrop) {
                createBackdrop.addEventListener('click', function () {
                    window.pulseModal(createModal);
                    if (createBranchTomSelect && typeof createBranchTomSelect.close === 'function') createBranchTomSelect.close();
                });
            }
        }
    }
    var createSubmit = document.getElementById('terminal-create-submit');
    if (createSubmit) createSubmit.addEventListener('click', submitCreate);

    if (editModal) {
        editModal.querySelectorAll('[data-tw-dismiss="modal"]').forEach(function (btn) { btn.addEventListener('click', closeEditModal); });
        if (window.pulseModal) {
            var editBackdrop = editModal.querySelector('.modal-backdrop');
            if (editBackdrop) editBackdrop.addEventListener('click', function () { window.pulseModal(editModal); });
        }
    }
    var editSubmit = document.getElementById('terminal-edit-submit');
    if (editSubmit) editSubmit.addEventListener('click', submitEdit);
})();
</script>
@endpush
