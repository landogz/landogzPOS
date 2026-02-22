@extends('super-admin.layouts.app')

@section('title', 'Companies')
@section('breadcrumb', 'Companies')

@section('content')
    <h2 class="intro-y mt-10 text-lg font-medium">Companies</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            <button type="button" id="companies-add-btn" class="transition duration-200 border inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 bg-primary border-primary text-white dark:border-primary mr-2 shadow-md">
                Add New Company
            </button>
            <div class="mx-auto hidden text-slate-500 md:block" id="companies-summary">Loading...</div>
            <div class="mt-3 w-full sm:ml-auto sm:mt-0 sm:w-auto md:ml-0">
                <div class="relative w-56 text-slate-500">
                    <input type="text" id="companies-search" placeholder="Search..." class="transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary dark:bg-darkmode-800 dark:border-transparent dark:placeholder:text-slate-500/80 box w-56 pr-10">
                    <i data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                </div>
            </div>
        </div>
        <div id="companies-grid" class="intro-y col-span-12 grid grid-cols-12 gap-6">
            <!-- Company cards rendered by JS -->
        </div>
        <div id="companies-empty" class="intro-y col-span-12 text-center py-12 text-slate-500 hidden">
            No companies found. Click "Add New Company" to create one.
        </div>
    </div>
@endsection

@push('modals')
    <x-modal id="company-modal" title="Add Company" title-id="company-modal-title" size="xl">
        <form id="company-form" enctype="multipart/form-data">
            <input type="hidden" id="company-id" name="id" value="">
            <div class="px-6 sm:px-8 py-6 space-y-6">
                {{-- Company logo: modern card-style upload --}}
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
(function() {
    var apiBase = '{{ url("/api/v1") }}';
    var noImageUrl = '{{ asset("images/noimage.png") }}';
    window._companyNoImageUrl = noImageUrl;
    var dashboardBase = '{{ url("/dashboard") }}';
    function summaryUrl(companyId) { return dashboardBase + '/companies/' + companyId + '/summary'; }

    var grid = document.getElementById('companies-grid');
    var summary = document.getElementById('companies-summary');
    var empty = document.getElementById('companies-empty');
    var searchInput = document.getElementById('companies-search');
    var modal = document.getElementById('company-modal');
    var form = document.getElementById('company-form');
    var modalTitle = document.getElementById('company-modal-title');
    var submitBtn = document.getElementById('company-submit-btn');

    function getToken() {
        return localStorage.getItem('super_admin_token');
    }

    function authHeaders() {
        var token = getToken();
        return { headers: { Authorization: token ? 'Bearer ' + token : '', Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } };
    }

    function loadCompanies(search) {
        if (!getToken()) {
            summary.textContent = 'Please log in to manage companies.';
            grid.innerHTML = '';
            empty.classList.remove('hidden');
            return;
        }
        var url = apiBase + '/companies';
        if (search) url += '?search=' + encodeURIComponent(search);
        axios.get(url, authHeaders())
            .then(function(r) {
                var list = (r.data && r.data.data) ? r.data.data : [];
                if (!Array.isArray(list)) list = [];
                renderCompanies(list);
                summary.textContent = 'Showing ' + list.length + ' companies';
                empty.classList.toggle('hidden', list.length > 0);
            })
            .catch(function(err) {
                if (err.response && err.response.status === 403) {
                    summary.textContent = 'Only Super Admin can manage companies.';
                } else {
                    summary.textContent = 'Failed to load companies.';
                }
                grid.innerHTML = '';
                empty.classList.remove('hidden');
            });
    }

    function renderCompanies(list) {
        grid.innerHTML = '';
        list.forEach(function(c) {
            var card = document.createElement('div');
            card.className = 'intro-y col-span-12 md:col-span-6 lg:col-span-4';
            var logoUrl = c.logo_url || noImageUrl;
            var logoHtml = '<img src="' + escapeHtml(logoUrl) + '" alt="Logo" class="h-full w-full object-cover company-logo-img">';
            var logoWrapClass = 'flex h-16 w-16 flex-shrink-0 rounded-full overflow-hidden border-2 border-slate-200 dark:border-darkmode-400';
            var isActive = c.is_active !== false;
            var toggleLabel = isActive ? 'Disable' : 'Enable';
            var toggleIcon = isActive ? 'toggle-left' : 'toggle-right';
            card.innerHTML =
                '<div class="box relative' + (isActive ? '' : ' opacity-75') + '">' +
                '  <div class="flex items-start px-5 pt-5">' +
                '    <div class="flex w-full flex-col items-center lg:flex-row">' +
                '      <div class="' + logoWrapClass + '">' + logoHtml + '</div>' +
                '      <div class="mt-3 text-center lg:ml-4 lg:mt-0 lg:text-left">' +
                '        <a href="' + escapeHtml(summaryUrl(c.id)) + '" class="font-medium company-name text-primary hover:underline">' + escapeHtml(c.name || '') + '</a>' +
                '        <div class="mt-0.5 text-xs text-slate-500 company-tin">' + (c.tin ? 'TIN: ' + escapeHtml(c.tin) : '—') + '</div>' +
                (isActive ? '' : '        <span class="mt-1 inline-block rounded px-2 py-0.5 text-xs font-medium bg-slate-200 text-slate-600 dark:bg-darkmode-400 dark:text-slate-300">Disabled</span>') +
                '      </div>' +
                '    </div>' +
                '    <div class="company-dropdown absolute right-0 top-0 mr-5 mt-3">' +
                '      <button type="button" class="company-dropdown-btn cursor-pointer block h-8 w-8 rounded-md hover:bg-slate-100 dark:hover:bg-darkmode-400 flex items-center justify-center text-slate-500" aria-expanded="false" aria-label="Actions">' +
                '        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>' +
                '      </button>' +
                '      <div class="company-dropdown-menu absolute right-0 top-full z-[9999] mt-1 hidden min-w-[10rem] rounded-md border border-slate-200 bg-white p-2 shadow-lg dark:border-darkmode-600 dark:bg-darkmode-600">' +
                '        <a href="javascript:;" class="company-edit cursor-pointer flex items-center p-2 rounded-md hover:bg-slate-200/60 dark:hover:bg-darkmode-400 transition duration-300 ease-in-out">' +
                '          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4 flex-shrink-0"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg> Edit' +
                '        </a>' +
                '        <a href="javascript:;" class="company-toggle-status cursor-pointer flex items-center p-2 rounded-md hover:bg-slate-200/60 dark:hover:bg-darkmode-400 transition duration-300 ease-in-out">' +
                '          <span class="mr-2 flex h-4 w-4 flex-shrink-0 items-center justify-center">' + (isActive ? '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="5" width="22" height="14" rx="7" ry="7"/><circle cx="16" cy="12" r="3"/></svg>' : '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="5" width="22" height="14" rx="7" ry="7"/><circle cx="8" cy="12" r="3"/></svg>') + '</span> ' + toggleLabel +
                '        </a>' +
                '        <a href="javascript:;" class="company-delete cursor-pointer flex items-center p-2 rounded-md hover:bg-slate-200/60 dark:hover:bg-darkmode-400 text-danger transition duration-300 ease-in-out">' +
                '          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4 flex-shrink-0"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg> Delete' +
                '        </a>' +
                '      </div>' +
                '    </div>' +
                '  </div>' +
                '  <div class="p-5 text-center lg:text-left">' +
                '    <div class="company-address text-slate-600 dark:text-slate-400 text-sm">' + (c.address ? escapeHtml(c.address) : '—') + '</div>' +
                '    <div class="mt-3 flex items-center justify-center text-slate-500 lg:justify-start">' +
                '      <i data-lucide="phone" class="stroke-1.5 mr-2 h-3 w-3"></i>' +
                '      <span class="company-contact">' + (c.contact ? escapeHtml(c.contact) : '—') + '</span>' +
                '    </div>' +
                '  </div>' +
                '  <div class="border-t border-slate-200/60 p-5 flex flex-wrap items-center justify-center gap-2 dark:border-darkmode-400 lg:justify-end">' +
                '    <a href="' + escapeHtml(summaryUrl(c.id)) + '" class="transition duration-200 border inline-flex items-center justify-center rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary border-slate-200 dark:border-darkmode-500 text-slate-700 dark:text-slate-300 bg-white dark:bg-darkmode-700 hover:bg-slate-50 dark:hover:bg-darkmode-600 py-1.5 px-3 text-sm">Summary</a>' +
                '    <a href="{{ route("dashboard.branches") }}?company=' + c.id + '" class="transition duration-200 border inline-flex items-center justify-center rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary bg-primary border-primary text-white py-1.5 px-3 text-sm">Branches</a>' +
                '  </div>' +
                '</div>';
            grid.appendChild(card);

            var dropdownBtn = card.querySelector('.company-dropdown-btn');
            var dropdownMenu = card.querySelector('.company-dropdown-menu');
            dropdownBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var open = document.querySelector('.company-dropdown-menu:not(.hidden)');
                if (open && open !== dropdownMenu) open.classList.add('hidden');
                dropdownMenu.classList.toggle('hidden');
            });
            card.querySelector('.company-edit').addEventListener('click', function(e) { e.preventDefault(); dropdownMenu.classList.add('hidden'); openEdit(c); });
            card.querySelector('.company-toggle-status').addEventListener('click', function(e) { e.preventDefault(); dropdownMenu.classList.add('hidden'); toggleStatus(c); });
            card.querySelector('.company-delete').addEventListener('click', function(e) { e.preventDefault(); dropdownMenu.classList.add('hidden'); confirmDelete(c); });
        });
        if (!grid._dropdownCloseBound) {
            grid._dropdownCloseBound = true;
            document.addEventListener('click', function() {
                document.querySelectorAll('.company-dropdown-menu').forEach(function(m) { m.classList.add('hidden'); });
            });
        }
        grid.addEventListener('error', function(e) {
            if (e.target.classList && e.target.classList.contains('company-logo-img') && window._companyNoImageUrl) {
                e.target.src = window._companyNoImageUrl;
            }
        }, true);
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function escapeHtml(s) {
        if (!s) return '';
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    var logoPreview = document.getElementById('company-logo-preview');
    var logoPlaceholder = document.querySelector('.company-logo-placeholder');
    var logoInput = document.getElementById('company-logo');

    function setLogoPreview(src) {
        var url = src || noImageUrl;
        logoPreview.src = url;
        logoPreview.classList.remove('hidden');
        if (logoPlaceholder) logoPlaceholder.classList.add('hidden');
        logoPreview.onerror = function() { this.src = noImageUrl; };
    }

    function openAdd() {
        document.getElementById('company-id').value = '';
        document.getElementById('company-name').value = '';
        document.getElementById('company-tin').value = '';
        document.getElementById('company-bir').value = '';
        document.getElementById('company-address').value = '';
        document.getElementById('company-contact').value = '';
        if (logoInput) { logoInput.value = ''; }
        if (logoLabel) logoLabel.textContent = 'Choose file';
        setLogoPreview(null);
        modalTitle.textContent = 'Add Company';
        modal.classList.remove('hidden');
        modal.classList.add('show');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function openEdit(c) {
        document.getElementById('company-id').value = c.id;
        document.getElementById('company-name').value = c.name || '';
        document.getElementById('company-tin').value = c.tin || '';
        document.getElementById('company-bir').value = c.bir_accreditation || '';
        document.getElementById('company-address').value = c.address || '';
        document.getElementById('company-contact').value = c.contact || '';
        if (logoInput) { logoInput.value = ''; }
        if (logoLabel) logoLabel.textContent = 'Choose file';
        setLogoPreview(c.logo_url || null);
        modalTitle.textContent = 'Edit Company';
        modal.classList.remove('hidden');
        modal.classList.add('show');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function toggleStatus(c) {
        Swal.fire({
            title: (c.is_active !== false ? 'Disable' : 'Enable') + ' Company?',
            text: (c.is_active !== false ? 'Disable' : 'Enable') + ' "' + (c.name || '') + '"?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes'
        }).then(function(result) {
            if (result.isConfirmed) {
                axios.patch(apiBase + '/companies/' + c.id + '/toggle-status', {}, authHeaders())
                    .then(function(r) {
                        var msg = (r.data && r.data.data && !r.data.data.is_active) ? 'Company disabled.' : 'Company enabled.';
                        showToastNotification('success', 'Done', msg);
                        loadCompanies(searchInput.value.trim());
                    })
                    .catch(function(err) {
                        showToastNotification('error', 'Error', (err.response && err.response.data && err.response.data.message) || 'Request failed.');
                    });
            }
        });
    }

    function confirmDelete(c) {
        Swal.fire({
            title: 'Delete Company?',
            text: 'Delete "' + (c.name || '') + '"? This cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete'
        }).then(function(result) {
            if (result.isConfirmed) {
                axios.delete(apiBase + '/companies/' + c.id, authHeaders())
                    .then(function() {
                        showToastNotification('success', 'Deleted', 'Company deleted.');
                        loadCompanies(searchInput.value.trim());
                    })
                    .catch(function(err) {
                        showToastNotification('error', 'Error', (err.response && err.response.data && err.response.data.message) || 'Delete failed.');
                    });
            }
        });
    }

    document.getElementById('companies-add-btn').addEventListener('click', openAdd);

    var logoLabel = document.getElementById('company-logo-label');
    if (logoInput) {
        logoInput.addEventListener('change', function() {
            var file = this.files && this.files[0];
            if (logoLabel) logoLabel.textContent = file ? (file.name.length > 24 ? file.name.slice(0, 21) + '…' : file.name) : 'Choose file';
            if (file && file.type.indexOf('image') === 0) {
                var reader = new FileReader();
                reader.onload = function() { setLogoPreview(reader.result); };
                reader.readAsDataURL(file);
            } else {
                setLogoPreview(null);
            }
        });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!getToken()) {
            Swal.fire({ icon: 'warning', title: 'Login required', text: 'Please log in to save a company.' });
            return;
        }
        var id = document.getElementById('company-id').value;
        var name = document.getElementById('company-name').value.trim();
        if (!name) {
            Swal.fire({ icon: 'warning', title: 'Required', text: 'Company name is required.' });
            return;
        }
        var formData = new FormData();
        formData.append('name', name);
        formData.append('tin', document.getElementById('company-tin').value.trim());
        formData.append('bir_accreditation', document.getElementById('company-bir').value.trim());
        formData.append('address', document.getElementById('company-address').value.trim());
        formData.append('contact', document.getElementById('company-contact').value.trim());
        if (logoInput && logoInput.files && logoInput.files[0]) {
            formData.append('logo', logoInput.files[0]);
        }
        submitBtn.disabled = true;
        var promise;
        if (id) {
            formData.append('_method', 'PUT');
            promise = axios.post(apiBase + '/companies/' + id, formData, authHeaders());
        } else {
            promise = axios.post(apiBase + '/companies', formData, authHeaders());
        }
        promise
            .then(function() {
                showToastNotification('success', 'Saved', id ? 'Company updated.' : 'Company created.');
                closeModal();
                loadCompanies(searchInput.value.trim());
            })
            .catch(function(err) {
                var msg = (err.response && err.response.data && err.response.data.message) || 'Save failed.';
                if (err.response && err.response.data && err.response.data.errors) {
                    var first = Object.values(err.response.data.errors)[0];
                    if (Array.isArray(first)) msg = first[0];
                }
                showToastNotification('error', 'Error', msg);
            })
            .finally(function() { submitBtn.disabled = false; });
    });

    searchInput.addEventListener('input', function() {
        var q = this.value.trim();
        if (searchInput._timer) clearTimeout(searchInput._timer);
        searchInput._timer = setTimeout(function() { loadCompanies(q); }, 300);
    });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('show');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
    modal.querySelectorAll('[data-tw-dismiss="modal"]').forEach(function(btn) {
        btn.addEventListener('click', closeModal);
    });
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });

    loadCompanies();
})();
</script>
@endpush
