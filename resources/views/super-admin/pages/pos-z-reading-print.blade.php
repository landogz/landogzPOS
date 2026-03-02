<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Z-Report (End of Day) - Print</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Courier New', Courier, monospace; font-size: 12px; line-height: 1.35; color: #000; background: #fff; margin: 0; padding: 12px 16px; }
        @media print { body { padding: 0; } .no-print { display: none !important; } }
        .zreport { width: 100%; max-width: 280px; margin: 0 auto; text-align: left; }
        .zreport .header-center { text-align: center; }
        .zreport-sep { border: none; border-top: 1px dashed #000; margin: 6px 0; }
        .zreport-section { margin-top: 8px; padding-top: 6px; }
        .zreport-row { display: flex; justify-content: space-between; margin: 2px 0; gap: 8px; }
        .zreport-row .left { flex-shrink: 0; }
        .zreport-row .right { text-align: right; flex-shrink: 0; }
        .zreport-row.cash-count-row { display: flex; justify-content: space-between; align-items: baseline; white-space: nowrap; gap: 6px; }
        .zreport-row.cash-count-row .cc-left { width: 10ch; min-width: 10ch; text-align: left; }
        .zreport-row.cash-count-row .cc-mid { width: 8ch; min-width: 8ch; text-align: left; flex-shrink: 0; }
        .zreport-row.cash-count-row .cc-right { width: 10ch; min-width: 10ch; text-align: right; }
        .font-bold { font-weight: bold; }
        .zreport-logo { max-width: 120px; max-height: 56px; object-fit: contain; display: block; margin: 0 auto 8px; }
        .loading { padding: 24px; text-align: center; color: #666; }
        .error { padding: 24px; text-align: center; color: #c00; }
    </style>
</head>
<body>
    <div id="zreport-container">
        <div id="zreport-loading" class="loading">Loading Z-Report…</div>
        <div id="zreport-error" class="error no-print" style="display: none;"></div>
        <div id="zreport-body" class="zreport" style="display: none;"></div>
    </div>
    <script>
    (function () {
        var apiBase = {!! json_encode($apiBase ?? url('/api/v1')) !!};
        var params = new URLSearchParams(window.location.search);
        var zReadingId = params.get('z_reading_id') || params.get('id');

        var token = null;
        try { token = localStorage.getItem('super_admin_token'); } catch (e) {}

        var loadingEl = document.getElementById('zreport-loading');
        var errorEl = document.getElementById('zreport-error');
        var bodyEl = document.getElementById('zreport-body');

        function showError(msg) {
            loadingEl.style.display = 'none';
            bodyEl.style.display = 'none';
            errorEl.textContent = msg;
            errorEl.style.display = 'block';
        }

        function formatMoney(n) {
            return (typeof n === 'number' ? n.toFixed(2) : parseFloat(n || 0).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        function escapeHtml(s) {
            if (s == null) return '';
            var div = document.createElement('div');
            div.textContent = s;
            return div.innerHTML;
        }

        function buildZReportHtml(z) {
            var branch = z.branch || {};
            var terminal = z.terminal || {};
            function formatReportDate(val) {
                if (!val) return '—';
                var s = String(val).split('T')[0];
                if (!s) return '—';
                var p = s.split('-');
                if (p.length >= 3) return parseInt(p[1], 10) + '/' + parseInt(p[2], 10) + '/' + p[0];
                return s;
            }
            var reportDateStr = formatReportDate(z.reporting_date) || (z.period_from ? String(z.period_from).split(' ')[0] : '—');
            var generatedAt = z.created_at ? new Date(z.created_at.replace(' ', 'T')) : new Date();
            var genStr = !isNaN(generatedAt.getTime()) ? generatedAt.toLocaleString('en-PH', { dateStyle: 'short', timeStyle: 'medium' }) : '';

            var totalAmount = (parseFloat(z.cash_total) || 0) + (parseFloat(z.card_total) || 0) + (parseFloat(z.ewallet_total) || 0) + (parseFloat(z.hmo_total) || 0) + (parseFloat(z.split_total) || 0);
            var totalReturns = parseFloat(z.total_returns) || 0;
            var totalSales = totalAmount - totalReturns;
            var vatRemoved = parseFloat(z.vat_amount) || 0;
            var totalDiscounts = parseFloat(z.total_discounts) || 0;
            var grossSales = totalSales + vatRemoved + totalDiscounts;
            var changeFund = parseFloat(z.change_fund) || 0;
            var pullOuts = parseFloat(z.pull_outs) || 0;
            var totalInDrawer = totalSales + changeFund - totalReturns - pullOuts;
            var amountSubmitted = z.amount_submitted != null && z.amount_submitted !== '' ? parseFloat(z.amount_submitted) : null;
            if (amountSubmitted == null && z.cash_count && typeof z.cash_count === 'object') {
                var denomValues = { '1000': 1000, '500': 500, '200': 200, '100': 100, '50': 50, '20': 20, '10': 10, '5': 5, '1': 1, '0.25': 0.25, '0.10': 0.10, '0.05': 0.05, '0.01': 0.01 };
                var cashTotal = 0;
                for (var k in denomValues) {
                    var qty = parseInt(z.cash_count[k], 10) || 0;
                    cashTotal += qty * denomValues[k];
                }
                amountSubmitted = Math.round(cashTotal * 100) / 100;
            }
            var amountOver = z.amount_over != null && z.amount_over !== '' ? parseFloat(z.amount_over) : (amountSubmitted != null ? Math.round((amountSubmitted - totalInDrawer) * 100) / 100 : null);
            var storeManager = (z.store_manager_name || '—').toUpperCase();

            var html = '';
            html += '<div class="header-center">';
            var company = branch.company || {};
            var logoUrl = company.logo_url || (company.logo ? (window.location.origin + '/storage/' + company.logo) : null);
            if (logoUrl) html += '<img src="' + escapeHtml(logoUrl) + '" alt="" class="zreport-logo">';
            html += '<div class="font-bold" style="margin-bottom: 2px;">' + escapeHtml(branch.name || '') + '</div>';
            if (branch.address) html += '<div style="margin: 2px 0; font-size: 11px;">' + escapeHtml(branch.address) + '</div>';
            if (branch.tin) html += '<div style="margin: 2px 0;">TIN ' + escapeHtml(branch.tin) + ' VAT</div>';
            if (terminal.min) html += '<div style="margin: 2px 0;">MIN ' + escapeHtml(terminal.min) + '</div>';
            if (terminal.sn) html += '<div style="margin: 2px 0;">SN ' + escapeHtml(terminal.sn) + '</div>';
            html += '<div class="zreport-sep"></div>';
            html += '<div class="font-bold">TERMINAL READING</div>';
            html += '<div>Reporting Date: ' + escapeHtml(reportDateStr) + '</div>';
            html += '<div>POS Terminal No. ' + escapeHtml(terminal.code || terminal.name || z.terminal_id || '—') + '</div>';
            html += '<div class="zreport-sep"></div></div>';

            html += '<div class="zreport-section"><div class="font-bold">TENDER RECONCILIATION</div>';
            html += '<div class="zreport-row"><span class="left">Cash</span><span class="right">' + formatMoney(z.cash_total) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Credit Card</span><span class="right">' + formatMoney(z.card_total) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">E-Wallet</span><span class="right">' + formatMoney(z.ewallet_total) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">HMO</span><span class="right">' + formatMoney(z.hmo_total) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Split</span><span class="right">' + formatMoney(z.split_total) + '</span></div>';
            html += '<div class="zreport-sep"></div>';
            html += '<div class="zreport-row font-bold"><span class="left">TOTAL AMOUNT</span><span class="right">' + formatMoney(totalAmount) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Less Refunds</span><span class="right">' + formatMoney(totalReturns) + '</span></div>';
            html += '</div><div class="zreport-sep"></div>';

            html += '<div class="zreport-section"><div class="font-bold">SALES BREAKDOWN</div>';
            html += '<div class="zreport-row font-bold"><span class="left">TOTAL SALES</span><span class="right">' + formatMoney(totalSales) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Add: VAT Removed</span><span class="right">' + formatMoney(vatRemoved) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Add: Discounts</span><span class="right">' + formatMoney(totalDiscounts) + '</span></div>';
            html += '<div class="zreport-sep"></div>';
            html += '<div class="zreport-row font-bold"><span class="left">GROSS SALES</span><span class="right">' + formatMoney(grossSales) + '</span></div>';
            html += '</div><div class="zreport-sep"></div>';

            html += '<div class="zreport-section"><div class="font-bold">VAT DECLARATION</div>';
            html += '<div class="zreport-row"><span class="left">VATable Sales</span><span class="right">' + formatMoney(z.vatable_sales) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">VAT Amount</span><span class="right">' + formatMoney(z.vat_amount) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">VAT Exempt Sales</span><span class="right">' + formatMoney(z.vat_exempt) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Zero Rated Sales</span><span class="right">' + formatMoney(z.zero_rated) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">CURRENT SALES</span><span class="right">' + formatMoney(totalSales) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Less: Refunds</span><span class="right">' + formatMoney(totalReturns) + '</span></div>';
            html += '<div class="zreport-row font-bold"><span class="left">TOTAL SALES</span><span class="right">' + formatMoney(totalSales) + '</span></div>';
            html += '</div><div class="zreport-sep"></div>';

            html += '<div class="zreport-section"><div class="font-bold">CASHIER ACCOUNTABILITY</div>';
            html += '<div class="zreport-row"><span class="left">Current Sales</span><span class="right">' + formatMoney(totalSales) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Add: Change Fund</span><span class="right">' + formatMoney(changeFund) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Less: Refunds</span><span class="right">' + formatMoney(totalReturns) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Less: Pull-Outs</span><span class="right">' + formatMoney(pullOuts) + '</span></div>';
            html += '<div class="zreport-row font-bold"><span class="left">Total In Drawer</span><span class="right">' + formatMoney(totalInDrawer) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Amount Submitted</span><span class="right">' + (amountSubmitted != null ? formatMoney(amountSubmitted) : '__________') + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Amount Over</span><span class="right">' + (amountOver != null ? formatMoney(amountOver) : '__________') + '</span></div>';
            html += '</div><div class="zreport-sep"></div>';

            html += '<div class="zreport-section"><div class="font-bold">CASHIER AUDIT</div>';
            html += '<div class="zreport-row"><span class="left">Items Sold</span><span class="right">' + (z.items_sold || 0) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Transactions</span><span class="right">' + (z.total_transactions || 0) + '  ' + formatMoney(totalSales) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Void Trans</span><span class="right">' + (z.void_transactions || 0) + '  ' + formatMoney(z.void_trans_amount || 0) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">SC VAT</span><span class="right">' + formatMoney(z.sc_vat || 0) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">SC Discounts</span><span class="right">' + formatMoney(z.sc_discount || 0) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">PWD VAT</span><span class="right">' + formatMoney(z.pwd_vat || 0) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">PWD Discounts</span><span class="right">' + formatMoney(z.pwd_discount || 0) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Zero Rated</span><span class="right">' + formatMoney(z.zero_rated || 0) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Net Sales</span><span class="right">' + formatMoney(z.net_sales) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Sales Refunds</span><span class="right">' + formatMoney(totalReturns) + '</span></div>';
            html += '</div><div class="zreport-sep"></div>';

            html += '<div class="zreport-section"><div class="font-bold">COUNTERS</div>';
            html += '<div class="zreport-row"><span class="left">Old Accumulated Sales</span><span class="right">' + formatMoney(z.old_accumulated_sales || 0) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">New Accumulated Sales</span><span class="right">' + formatMoney(z.new_accumulated_sales || 0) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Void Sales</span><span class="right">' + formatMoney(z.void_trans_amount || 0) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Sales Refunds</span><span class="right">' + formatMoney(totalReturns) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Transactions</span><span class="right">' + (z.total_transactions || 0) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">First Official Receipt</span><span class="right">' + escapeHtml((z.or_series_start || '0000000000').toString().padStart(10, '0')) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Last Official Receipt</span><span class="right">' + escapeHtml((z.or_series_end || '0000000000').toString().padStart(10, '0')) + '</span></div>';
            html += '<div class="zreport-row"><span class="left">Z-Counter</span><span class="right">' + (z.z_counter || 0) + '</span></div>';
            html += '</div><div class="zreport-sep"></div>';

            if (z.cash_count && Object.keys(z.cash_count).length) {
                html += '<div class="zreport-section"><div class="font-bold">CASH COUNT</div>';
                var denomKeys = ['1000', '500', '200', '100', '50', '20', '10', '5', '1', '0.25', '0.10', '0.05', '0.01'];
                var denomLabels = ['1,000.00', '500.00', '200.00', '100.00', '50.00', '20.00', '10.00', '5.00', '1.00', '0.25', '0.10', '0.05', '0.01'];
                var denomValues = [1000, 500, 200, 100, 50, 20, 10, 5, 1, 0.25, 0.10, 0.05, 0.01];
                var cashCountTotal = 0;
                for (var i = 0; i < denomKeys.length; i++) {
                    var count = parseInt(z.cash_count[denomKeys[i]], 10) || 0;
                    var subtotal = count * denomValues[i];
                    cashCountTotal += subtotal;
                    var countStr = count > 0 ? String(count) : '__________';
                    var subtotalStr = count > 0 ? formatMoney(subtotal) : '__________';
                    html += '<div class="zreport-row cash-count-row"><span class="cc-left">' + countStr + '</span><span class="cc-mid">' + denomLabels[i] + '</span><span class="cc-right">' + subtotalStr + '</span></div>';
                }
                html += '<div class="zreport-row font-bold"><span class="left">Total:</span><span class="right">' + (cashCountTotal > 0 ? formatMoney(cashCountTotal) : '__________') + '</span></div>';
                html += '</div><div class="zreport-sep"></div>';
            }

            html += '<div class="header-center">';
            html += '<div class="zreport-sep"></div>';
            html += '<div style="margin: 4px 0;">Store Manager:</div>';
            html += '<div class="font-bold" style="margin: 2px 0;">' + escapeHtml(storeManager) + '</div>';
            html += '<div class="zreport-sep"></div>';
            html += '<div style="margin-top: 6px; font-size: 11px;">' + escapeHtml(genStr) + '</div>';
            html += '</div>';

            return html;
        }

        if (!zReadingId) {
            showError('Missing Z-Reading ID.');
            return;
        }
        if (!token) {
            showError('Not logged in. Open this page from the POS after generating a Z-Reading.');
            return;
        }

        fetch(apiBase + '/pos/z-reading/' + zReadingId, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + token,
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(function (res) {
            if (!res.ok) throw new Error('Could not load Z-Reading.');
            return res.json();
        }).then(function (data) {
            var z = (data && data.data) ? data.data : data;
            if (!z) throw new Error('Invalid Z-Reading data.');
            loadingEl.style.display = 'none';
            bodyEl.innerHTML = buildZReportHtml(z);
            bodyEl.style.display = 'block';
            setTimeout(function () { window.print(); }, 300);
        }).catch(function (err) {
            showError(err.message || 'Failed to load Z-Reading.');
        });
    })();
    </script>
</body>
</html>
