@extends('super-admin.layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
    <div class="intro-y mt-8 flex h-10 items-center">
        <h2 class="mr-5 truncate text-lg font-medium">Dashboard Overview</h2>
    </div>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="relative zoom-in before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-[''] dark:before:bg-darkmode-700">
                <div class="box p-5">
                    <div class="flex">
                        <i data-lucide="shopping-cart" class="stroke-1.5 h-[28px] w-[28px] text-primary"></i>
                    </div>
                    <div class="mt-6 text-3xl font-medium leading-8" id="sales-today">—</div>
                    <div class="mt-1 text-base text-slate-500">Sales today</div>
                </div>
            </div>
        </div>
        <div class="intro-y col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="relative zoom-in before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-[''] dark:before:bg-darkmode-700">
                <div class="box p-5">
                    <div class="flex">
                        <i data-lucide="receipt" class="stroke-1.5 h-[28px] w-[28px] text-pending"></i>
                    </div>
                    <div class="mt-6 text-3xl font-medium leading-8" id="transaction-count">—</div>
                    <div class="mt-1 text-base text-slate-500">Transactions today</div>
                </div>
            </div>
        </div>
        <div class="intro-y col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="relative zoom-in before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-[''] dark:before:bg-darkmode-700">
                <div class="box p-5">
                    <div class="flex">
                        <i data-lucide="package" class="stroke-1.5 h-[28px] w-[28px] text-warning"></i>
                    </div>
                    <div class="mt-6 text-3xl font-medium leading-8" id="low-stock-count">—</div>
                    <div class="mt-1 text-base text-slate-500">Low stock alerts</div>
                </div>
            </div>
        </div>
        <div class="intro-y col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="relative zoom-in before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-[''] dark:before:bg-darkmode-700">
                <div class="box p-5">
                    <div class="flex">
                        <i data-lucide="alert-circle" class="stroke-1.5 h-[28px] w-[28px] text-danger"></i>
                    </div>
                    <div class="mt-6 text-3xl font-medium leading-8" id="expiring-count">—</div>
                    <div class="mt-1 text-base text-slate-500">Expiring soon</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function() {
    var token = localStorage.getItem('super_admin_token');
    if (!token) {
        window.location.href = '{{ route("dashboard.login") }}';
        return;
    }
    axios.get('{{ url("/api/v1/dashboard/summary") }}', { headers: { Authorization: 'Bearer ' + token, Accept: 'application/json' } })
        .then(function(r) {
            var d = r.data && r.data.data ? r.data.data : r.data;
            if (d) {
                document.getElementById('sales-today').textContent = typeof d.sales_today === 'number' ? d.sales_today.toFixed(2) : (d.sales_today || '—');
                document.getElementById('transaction-count').textContent = d.transaction_count ?? '—';
                document.getElementById('low-stock-count').textContent = d.low_stock_count ?? '—';
                document.getElementById('expiring-count').textContent = d.expiring_soon_count ?? '—';
            }
        })
        .catch(function() {
            document.getElementById('sales-today').textContent = '—';
            document.getElementById('transaction-count').textContent = '—';
            document.getElementById('low-stock-count').textContent = '—';
            document.getElementById('expiring-count').textContent = '—';
        });
})();
</script>
@endpush
