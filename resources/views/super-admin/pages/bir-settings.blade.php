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
                <div class="px-5 sm:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8">
                    {{-- Provider identity --}}
                    <section class="space-y-4">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 border-l-2 border-primary/60 pl-3">Provider identity</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-6">
                            <div class="lg:col-span-2">
                                <label for="bir-provider-name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">POS System Provider</label>
                                <input type="text" id="bir-provider-name" name="provider_name" placeholder="e.g. DMS VIRTUAL iSOLUTIONS"
                                    class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 min-h-[44px] sm:min-h-[42px] px-4">
                            </div>
                            <div class="lg:col-span-2">
                                <label for="bir-provider-address" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Provider Address</label>
                                <textarea id="bir-provider-address" name="provider_address" rows="2" placeholder="e.g. 191 Rizal Avenue, Puerto Princesa City"
                                    class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 resize-none min-h-[44px] sm:min-h-[42px] px-4 py-3"></textarea>
                            </div>
                        </div>
                    </section>

                    {{-- BIR & tax details --}}
                    <section class="space-y-4">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 border-l-2 border-primary/60 pl-3">BIR & tax details</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                            <div>
                                <label for="bir-tin" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">TIN</label>
                                <input type="text" id="bir-tin" name="tin" placeholder="000-000-000-000"
                                    class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 min-h-[44px] sm:min-h-[42px] px-4">
                            </div>
                            <div class="sm:col-span-2">
                                <label for="bir-accreditation-number" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">BIR Accreditation #</label>
                                <input type="text" id="bir-accreditation-number" name="accreditation_number" placeholder="e.g. #036-103286608-000508"
                                    class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 min-h-[44px] sm:min-h-[42px] px-4">
                            </div>
                            <div class="sm:col-span-2 lg:col-span-1">
                                <label for="bir-ptu-number" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">PTU No.</label>
                                <input type="text" id="bir-ptu-number" name="ptu_number" placeholder="0000-000-000000-000"
                                    class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 min-h-[44px] sm:min-h-[42px] px-4">
                            </div>
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
                    </section>

                    {{-- Validity statement --}}
                    <section class="space-y-4">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 border-l-2 border-primary/60 pl-3">Validity</h3>
                        <div>
                            <label for="bir-validity-statement" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Validity statement</label>
                            <textarea id="bir-validity-statement" name="validity_statement" rows="3" placeholder="e.g. THIS RECEIPT SHALL BE VALID FOR FIVE (5) YEARS FROM THE DATE OF THE PERMIT TO USE"
                                class="w-full rounded-xl border border-slate-200 dark:border-darkmode-500 bg-white dark:bg-darkmode-700 text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 resize-none min-h-[80px] px-4 py-3"></textarea>
                        </div>
                    </section>
                </div>

                <div class="px-5 sm:px-8 py-5 sm:py-6 border-t border-slate-100 dark:border-darkmode-600 bg-slate-50/30 dark:bg-darkmode-700/30 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button type="submit" id="bir-save-btn" class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary border border-primary px-6 py-3 text-sm font-medium text-white shadow-sm hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-darkmode-800 transition-colors touch-manipulation min-h-[44px] sm:min-h-[42px]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
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
            var d = (typeof value === 'string') ? value.split('T')[0] : (value.substring ? value.substring(0, 10) : '');
            el.value = d || '';
        } else {
            el.value = value;
        }
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

    loadProviderSettings();
})();
</script>
@endpush
