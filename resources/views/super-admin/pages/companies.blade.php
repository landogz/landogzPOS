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

    <!-- BEGIN: Add/Edit Company Modal -->
    <div id="company-modal" class="modal overflow-y-auto" tabindex="-1" aria-hidden="true" data-tw-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto" id="company-modal-title">Add Company</h2>
                    <button type="button" data-tw-dismiss="modal" class="rounded-md border border-slate-200 p-2 hover:bg-slate-100 dark:border-darkmode-400 dark:hover:bg-darkmode-600">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>
                <form id="company-form">
                    <input type="hidden" id="company-id" name="id" value="">
                    <div class="modal-body grid grid-cols-12 gap-4">
                        <div class="col-span-12">
                            <label for="company-name" class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" id="company-name" name="name" class="form-control w-full" placeholder="Company name" required>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label for="company-tin" class="form-label">TIN</label>
                            <input type="text" id="company-tin" name="tin" class="form-control w-full" placeholder="Tax Identification Number">
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label for="company-bir" class="form-label">BIR Accreditation</label>
                            <input type="text" id="company-bir" name="bir_accreditation" class="form-control w-full" placeholder="BIR accreditation">
                        </div>
                        <div class="col-span-12">
                            <label for="company-address" class="form-label">Address</label>
                            <textarea id="company-address" name="address" class="form-control w-full" rows="2" placeholder="Address"></textarea>
                        </div>
                        <div class="col-span-12">
                            <label for="company-contact" class="form-label">Contact</label>
                            <input type="text" id="company-contact" name="contact" class="form-control w-full" placeholder="Phone / email">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                        <button type="submit" id="company-submit-btn" class="btn btn-primary w-20">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END: Modal -->
@endsection

@push('scripts')
<script src="{{ $midoneBase ?? asset('midone-html.vercel.app') }}/dist/js/vendors/modal.js"></script>
<script>
(function() {
    var apiBase = '{{ url("/api/v1") }}';
    var token = localStorage.getItem('super_admin_token');
    if (!token) return;

    var grid = document.getElementById('companies-grid');
    var summary = document.getElementById('companies-summary');
    var empty = document.getElementById('companies-empty');
    var searchInput = document.getElementById('companies-search');
    var modal = document.getElementById('company-modal');
    var form = document.getElementById('company-form');
    var modalTitle = document.getElementById('company-modal-title');
    var submitBtn = document.getElementById('company-submit-btn');

    function authHeaders() {
        return { headers: { Authorization: 'Bearer ' + token, Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } };
    }

    function loadCompanies(search) {
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
            card.innerHTML =
                '<div class="box">' +
                '  <div class="flex items-start px-5 pt-5">' +
                '    <div class="flex w-full flex-col items-center lg:flex-row">' +
                '      <div class="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10 text-primary">' +
                '        <i data-lucide="building" class="stroke-1.5 h-8 w-8"></i>' +
                '      </div>' +
                '      <div class="mt-3 text-center lg:ml-4 lg:mt-0 lg:text-left">' +
                '        <a href="#" class="font-medium company-name">' + escapeHtml(c.name || '') + '</a>' +
                '        <div class="mt-0.5 text-xs text-slate-500 company-tin">' + (c.tin ? 'TIN: ' + escapeHtml(c.tin) : '—') + '</div>' +
                '      </div>' +
                '    </div>' +
                '    <div class="dropdown absolute right-0 top-0 mr-5 mt-3">' +
                '      <button type="button" data-tw-toggle="dropdown" class="cursor-pointer block h-5 w-5">' +
                '        <i data-lucide="more-horizontal" class="stroke-1.5 w-5 h-5 text-slate-500"></i>' +
                '      </button>' +
                '      <div class="dropdown-menu absolute z-[9999] hidden">' +
                '        <div class="dropdown-content rounded-md border-transparent bg-white p-2 shadow-lg dark:bg-darkmode-600 w-40">' +
                '          <a class="company-edit cursor-pointer flex items-center p-2 rounded-md hover:bg-slate-200/60 dark:hover:bg-darkmode-400" data-id="' + c.id + '">' +
                '            <i data-lucide="edit" class="stroke-1.5 mr-2 h-4 w-4"></i> Edit' +
                '          </a>' +
                '          <a class="company-delete cursor-pointer flex items-center p-2 rounded-md hover:bg-slate-200/60 dark:hover:bg-darkmode-400 text-danger" data-id="' + c.id + '" data-name="' + escapeHtml(c.name || '') + '">' +
                '            <i data-lucide="trash" class="stroke-1.5 mr-2 h-4 w-4"></i> Delete' +
                '          </a>' +
                '        </div>' +
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
                '  <div class="border-t border-slate-200/60 p-5 text-center dark:border-darkmode-400 lg:text-right">' +
                '    <a href="{{ route("dashboard.branches") }}?company=' + c.id + '" class="transition duration-200 border inline-flex items-center justify-center rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary bg-primary border-primary text-white py-1 px-2 text-sm">Branches</a>' +
                '  </div>' +
                '</div>';
            grid.appendChild(card);

            card.querySelector('.company-edit').addEventListener('click', function() { openEdit(c); });
            card.querySelector('.company-delete').addEventListener('click', function() { confirmDelete(c); });
        });
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function escapeHtml(s) {
        if (!s) return '';
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    function openAdd() {
        document.getElementById('company-id').value = '';
        document.getElementById('company-name').value = '';
        document.getElementById('company-tin').value = '';
        document.getElementById('company-bir').value = '';
        document.getElementById('company-address').value = '';
        document.getElementById('company-contact').value = '';
        modalTitle.textContent = 'Add Company';
        modal.classList.add('show');
        modal.style.display = 'block';
    }

    function openEdit(c) {
        document.getElementById('company-id').value = c.id;
        document.getElementById('company-name').value = c.name || '';
        document.getElementById('company-tin').value = c.tin || '';
        document.getElementById('company-bir').value = c.bir_accreditation || '';
        document.getElementById('company-address').value = c.address || '';
        document.getElementById('company-contact').value = c.contact || '';
        modalTitle.textContent = 'Edit Company';
        modal.classList.add('show');
        modal.style.display = 'block';
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
                        Swal.fire({ icon: 'success', title: 'Deleted', text: 'Company deleted.' });
                        loadCompanies(searchInput.value.trim());
                    })
                    .catch(function(err) {
                        Swal.fire({ icon: 'error', title: 'Error', text: (err.response && err.response.data && err.response.data.message) || 'Delete failed.' });
                    });
            }
        });
    }

    document.getElementById('companies-add-btn').addEventListener('click', openAdd);

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var id = document.getElementById('company-id').value;
        var payload = {
            name: document.getElementById('company-name').value.trim(),
            tin: document.getElementById('company-tin').value.trim() || null,
            bir_accreditation: document.getElementById('company-bir').value.trim() || null,
            address: document.getElementById('company-address').value.trim() || null,
            contact: document.getElementById('company-contact').value.trim() || null
        };
        if (!payload.name) {
            Swal.fire({ icon: 'warning', title: 'Required', text: 'Company name is required.' });
            return;
        }
        submitBtn.disabled = true;
        var promise = id
            ? axios.put(apiBase + '/companies/' + id, payload, authHeaders())
            : axios.post(apiBase + '/companies', payload, authHeaders());
        promise
            .then(function() {
                Swal.fire({ icon: 'success', title: 'Saved', text: id ? 'Company updated.' : 'Company created.' });
                modal.classList.remove('show');
                modal.style.display = 'none';
                loadCompanies(searchInput.value.trim());
            })
            .catch(function(err) {
                var msg = (err.response && err.response.data && err.response.data.message) || 'Save failed.';
                if (err.response && err.response.data && err.response.data.errors) {
                    var first = Object.values(err.response.data.errors)[0];
                    if (Array.isArray(first)) msg = first[0];
                }
                Swal.fire({ icon: 'error', title: 'Error', text: msg });
            })
            .finally(function() { submitBtn.disabled = false; });
    });

    searchInput.addEventListener('input', function() {
        var q = this.value.trim();
        if (searchInput._timer) clearTimeout(searchInput._timer);
        searchInput._timer = setTimeout(function() { loadCompanies(q); }, 300);
    });

    function closeModal() {
        modal.classList.remove('show');
        modal.style.display = 'none';
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
