@extends('super-admin.layouts.app')

@section('title', 'BIR Settings')
@section('breadcrumb', 'BIR Settings')

@section('content')
    <div class="intro-y mt-6 sm:mt-8">
        <div class="flex flex-col gap-1 sm:gap-2">
            <h1 class="text-xl sm:text-2xl font-semibold text-slate-800 dark:text-slate-100 tracking-tight">BIR Settings</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 max-w-2xl">
                Configure your POS system provider details for official BIR receipts. These details appear on printed receipts (e.g. 40-column format).
            </p>
        </div>
    </div>

    <div class="intro-y mt-6 sm:mt-8">
        <div class="rounded-2xl border border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800 shadow-sm overflow-hidden">
            <div class="px-5 sm:px-8 py-5 sm:py-6 border-b border-slate-100 dark:border-darkmode-600 bg-slate-50/50 dark:bg-darkmode-700/50">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Provider settings</h2>
                <p class="mt-0.5 text-slate-600 dark:text-slate-300 text-sm">Company and accreditation information shown on receipts.</p>
            </div>

            <form id="bir-form" class="hidden">
                <input type="hidden" id="bir-branch-id" name="branch_id" value="">
                <div class="px-5 sm:px-8 py-6 sm:py-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 lg:gap-6">
                        {{-- Left column: Provider settings --}}
                        <div class="space-y-6">
                            <section class="bir-settings-section rounded-lg bg-slate-50/60 dark:bg-darkmode-700/30 px-4 sm:px-5 py-5 border border-slate-100 dark:border-darkmode-600">
                                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-l-2 border-primary/60 pl-3 mb-4">Provider identity</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label for="bir-provider-name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">POS System Provider</label>
                                        <input type="text" id="bir-provider-name" name="provider_name" placeholder="e.g. DMS VIRTUAL iSOLUTIONS"
                                            class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 min-h-[44px] sm:min-h-[42px] px-4">
                                    </div>
                                    <div>
                                        <label for="bir-provider-address" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Provider Address</label>
                                        <input type="text" id="bir-provider-address" name="provider_address" placeholder="e.g. 191 Rizal Avenue, Puerto Princesa City" data-gramm="false" data-gramm_editor="false" data-enable-grammarly="false"
                                            class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 min-h-[44px] sm:min-h-[42px] px-4">
                                    </div>
                                </div>
                            </section>
                        </div>

                        {{-- Right column: BIR & tax details --}}
                        <div class="space-y-6">
                            <section class="bir-settings-section rounded-lg bg-slate-50/60 dark:bg-darkmode-700/30 px-4 sm:px-5 py-5 border border-slate-100 dark:border-darkmode-600 border-t-0 lg:border-t lg:border-t-slate-100 dark:lg:border-t-darkmode-600">
                                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-l-2 border-primary/60 pl-3 mb-4">BIR & tax details</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label for="bir-tin" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">TIN</label>
                                        <input type="text" id="bir-tin" name="tin" placeholder="000-000-000-000"
                                            class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 min-h-[44px] sm:min-h-[42px] px-4">
                                    </div>
                                    <div>
                                        <label for="bir-accreditation-number" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">BIR Accreditation #</label>
                                        <input type="text" id="bir-accreditation-number" name="accreditation_number" placeholder="e.g. #036-103286608-000508"
                                            class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 min-h-[44px] sm:min-h-[42px] px-4">
                                    </div>
                                    <div>
                                        <label for="bir-ptu-number" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">PTU No.</label>
                                        <input type="text" id="bir-ptu-number" name="ptu_number" placeholder="e.g. 2024-01-12345678-00001"
                                            class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 min-h-[44px] sm:min-h-[42px] px-4">
                                        <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Format BIR expects: e.g. 2024-01-12345678-00001</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="bir-valid-from" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Issued</label>
                                            <input type="date" id="bir-valid-from" name="valid_from"
                                                class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/20 min-h-[44px] sm:min-h-[42px] px-4">
                                        </div>
                                        <div>
                                            <label for="bir-valid-until" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Until</label>
                                            <input type="date" id="bir-valid-until" name="valid_until"
                                                class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/20 min-h-[44px] sm:min-h-[42px] px-4">
                                        </div>
                                    </div>
                                    <div id="bir-ptu-expiry-warning" class="hidden rounded-lg px-4 py-3 flex items-start gap-3 border bir-ptu-expiry-banner">
                                        <svg id="bir-ptu-expiry-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0 mt-0.5 text-amber-600 dark:text-amber-400" aria-hidden="true"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                        <p id="bir-ptu-expiry-text" class="text-sm font-medium text-amber-800 dark:text-amber-200"></p>
                                    </div>
                                </div>
                            </section>

                            {{-- Validity statement (full width in right column) --}}
                            <section class="bir-settings-section rounded-lg bg-slate-50/60 dark:bg-darkmode-700/30 px-4 sm:px-5 py-5 border border-slate-100 dark:border-darkmode-600 border-t-0 lg:border-t lg:border-t-slate-100 dark:lg:border-t-darkmode-600">
                                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-l-2 border-primary/60 pl-3 mb-4">Validity</h3>
                                <div>
                                    <label for="bir-validity-statement" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Validity statement</label>
                                    <textarea id="bir-validity-statement" name="validity_statement" rows="3" placeholder="e.g. This receipt is valid for income tax purposes. Valid for five (5) years from the date of the Permit to Use." data-gramm="false" data-gramm_editor="false" data-enable-grammarly="false"
                                        class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 resize-none min-h-[80px] px-4 py-3"></textarea>
                                    <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">e.g. &quot;This receipt is valid for income tax purposes. Valid for five (5) years from the date of the Permit to Use.&quot;</p>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>

                <div class="px-5 sm:px-8 py-5 sm:py-6 border-t border-slate-200 dark:border-darkmode-600 bg-slate-50/50 dark:bg-darkmode-700/50 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button type="submit" id="bir-save-btn" class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary border border-primary px-6 py-3 text-sm font-medium text-white shadow-sm hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-darkmode-800 transition-colors touch-manipulation min-h-[44px] sm:min-h-[42px]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        Save provider settings
                    </button>
                </div>
            </form>

            <div id="bir-loading" class="hidden px-5 sm:px-8 py-16 sm:py-24 flex flex-col items-center justify-center gap-4">
                <svg class="animate-spin h-10 w-10 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm text-slate-500 dark:text-slate-400">Loading provider settings…</span>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    var apiBase = '{{ url("/api/v1") }}';
    function getToken() { return localStorage.getItem('super_admin_token'); }
    function authHeaders() { return { headers: { Authorization: 'Bearer ' + (getToken() || ''), Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } }; }

    var form = document.getElementById('bir-form');
    var branchIdInput = document.getElementById('bir-branch-id');
    var loadingEl = document.getElementById('bir-loading');
    var saveBtn = document.getElementById('bir-save-btn');

    var fieldIds = {
        provider_name: 'bir-provider-name',
        provider_address: 'bir-provider-address',
        tin: 'bir-tin',
        accreditation_number: 'bir-accreditation-number',
        valid_from: 'bir-valid-from',
        valid_until: 'bir-valid-until',
        ptu_number: 'bir-ptu-number',
        validity_statement: 'bir-validity-statement'
    };

    function setFieldValue(key, value) {
        var id = fieldIds[key];
        if (!id) return;
        var el = document.getElementById(id);
        if (!el) return;
        if (value === null || value === undefined) value = '';
        if (el.type === 'date' && value) {
            var str = typeof value === 'string' ? value : (value && value.substring ? value.substring(0, 10) : '');
            var d = str.indexOf('T') !== -1 ? str.split('T')[0] : str.substring(0, 10);
            el.value = (d && d.length >= 10) ? d.substring(0, 10) : '';
        } else {
            el.value = value;
        }
        if (key === 'valid_until') updatePtuExpiryWarning();
    }

    function updatePtuExpiryWarning() {
        var untilEl = document.getElementById('bir-valid-until');
        var box = document.getElementById('bir-ptu-expiry-warning');
        var textEl = document.getElementById('bir-ptu-expiry-text');
        if (!untilEl || !box || !textEl) return;
        var untilStr = (untilEl.value || '').trim();
        if (!untilStr) {
            box.classList.add('hidden');
            return;
        }
        var until = new Date(untilStr);
        var today = new Date();
        today.setHours(0, 0, 0, 0);
        until.setHours(0, 0, 0, 0);
        var diffMs = until - today;
        var diffDays = Math.ceil(diffMs / (1000 * 60 * 60 * 24));
        if (diffDays <= 0) {
            textEl.textContent = 'PTU has expired — renew with BIR.';
        } else if (diffDays <= 30) {
            textEl.textContent = 'PTU expires in ' + diffDays + ' days — renew with BIR.';
        } else {
            textEl.textContent = 'PTU expires in ' + diffDays + ' days — plan your BIR renewal.';
        }
        box.classList.remove('hidden');
    }

    function getFormPayload() {
        var branchId = branchIdInput ? branchIdInput.value : '';
        if (!branchId) {
            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'warning', title: 'Cannot save', text: 'No provider settings loaded. Ensure at least one branch exists.' });
            return null;
        }
        var payload = { branch_id: parseInt(branchId, 10) };
        Object.keys(fieldIds).forEach(function (key) {
            var el = document.getElementById(fieldIds[key]);
            payload[key] = el ? (el.value || null) : null;
        });
        return payload;
    }

    function loadProviderSettings() {
        loadingEl.classList.remove('hidden');
        form.classList.add('hidden');
        axios.get(apiBase + '/branches', authHeaders())
            .then(function (branchesRes) {
                var list = (branchesRes.data && branchesRes.data.data) ? branchesRes.data.data : (Array.isArray(branchesRes.data) ? branchesRes.data : []);
                var firstBranchId = (list && list.length) ? list[0].id : null;
                if (!firstBranchId) {
                    loadingEl.classList.add('hidden');
                    form.classList.remove('hidden');
                    if (branchIdInput) branchIdInput.value = '';
                    Object.keys(fieldIds).forEach(function (key) { setFieldValue(key, ''); });
                    return;
                }
                return axios.get(apiBase + '/bir/settings', { params: { branch_id: firstBranchId }, ...authHeaders() });
            })
            .then(function (r) {
                if (!r || !r.data) {
                    loadingEl.classList.add('hidden');
                    form.classList.remove('hidden');
                    return;
                }
                var data = (r.data && r.data.data) ? r.data.data : r.data;
                loadingEl.classList.add('hidden');
                form.classList.remove('hidden');
                if (data && data.branch_id) {
                    if (branchIdInput) branchIdInput.value = data.branch_id;
                    Object.keys(fieldIds).forEach(function (key) {
                        setFieldValue(key, data[key]);
                    });
                } else {
                    if (branchIdInput) branchIdInput.value = data.branch_id || '';
                    Object.keys(fieldIds).forEach(function (key) { setFieldValue(key, data[key] || ''); });
                }
                updatePtuExpiryWarning();
            })
            .catch(function (err) {
                loadingEl.classList.add('hidden');
                form.classList.remove('hidden');
                if (branchIdInput) branchIdInput.value = '';
                Object.keys(fieldIds).forEach(function (key) { setFieldValue(key, ''); });
                var msg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'Could not load provider settings.';
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Error', text: msg });
            });
    }

    function submitSave() {
        var payload = getFormPayload();
        if (!payload) return;
        saveBtn.disabled = true;
        axios.put(apiBase + '/bir/settings', payload, authHeaders())
            .then(function () {
                saveBtn.disabled = false;
                if (typeof showToastNotification === 'function') showToastNotification('success', 'Saved', 'Provider settings updated.');
            })
            .catch(function (err) {
                saveBtn.disabled = false;
                var msg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'Failed to save provider settings.';
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Error', text: msg });
            });
    }

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            submitSave();
        });
    }

    var validUntilEl = document.getElementById('bir-valid-until');
    if (validUntilEl) {
        validUntilEl.addEventListener('change', updatePtuExpiryWarning);
        validUntilEl.addEventListener('input', updatePtuExpiryWarning);
    }

    loadProviderSettings();
})();
</script>
@endpush
