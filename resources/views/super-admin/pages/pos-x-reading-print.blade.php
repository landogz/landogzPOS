<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>X-Report (Mid-Day) - Print</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Courier New', Courier, monospace; font-size: 12px; line-height: 1.35; color: #000; background: #fff; margin: 0; padding: 12px 16px; }
        @media print { body { padding: 0; } .no-print { display: none !important; } }
        .xreport { width: 100%; max-width: 280px; margin: 0 auto; text-align: left; }
        .xreport .header-center { text-align: center; }
        .xreport-sep { border: none; border-top: 1px dashed #000; margin: 6px 0; }
        .xreport-section { margin-top: 8px; padding-top: 6px; }
        .xreport-row { display: flex; justify-content: space-between; margin: 2px 0; gap: 8px; }
        .xreport-row .left { flex-shrink: 0; }
        .xreport-row .right { text-align: right; flex-shrink: 0; }
        .xreport-row.cash-count-row { display: flex; justify-content: space-between; align-items: baseline; white-space: nowrap; gap: 6px; }
        .xreport-row.cash-count-row .cc-left { width: 10ch; min-width: 10ch; text-align: left; }
        .xreport-row.cash-count-row .cc-mid { width: 8ch; min-width: 8ch; text-align: left; flex-shrink: 0; }
        .xreport-row.cash-count-row .cc-right { width: 10ch; min-width: 10ch; text-align: right; }
        .font-bold { font-weight: bold; }
        .xreport-logo { max-width: 120px; max-height: 56px; object-fit: contain; display: block; margin: 0 auto 8px; }
        .loading { padding: 24px; text-align: center; color: #666; }
        .error { padding: 24px; text-align: center; color: #c00; }
    </style>
</head>
<body>
    <div id="xreport-container">
        <div id="xreport-loading" class="loading">Loading X-Report…</div>
        <div id="xreport-error" class="error no-print" style="display: none;"></div>
        <div id="xreport-body" class="xreport" style="display: none;"></div>
    </div>
    <script>
    (function () {
        var apiBase = {!! json_encode($apiBase ?? url('/api/v1')) !!};
        var params = new URLSearchParams(window.location.search);
        var xReadingId = params.get('x_reading_id') || params.get('id');

        var token = null;
        try { token = localStorage.getItem('super_admin_token'); } catch (e) {}

        var loadingEl = document.getElementById('xreport-loading');
        var errorEl = document.getElementById('xreport-error');
        var bodyEl = document.getElementById('xreport-body');

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

        function buildXReportHtml(x) {
            var branch = x.branch || {};
            var cashier = x.cashier || {};
            var terminal = x.terminal || {};
            var periodFrom = x.period_from ? new Date(x.period_from.replace(' ', 'T')) : null;
            var periodTo = x.period_to ? new Date(x.period_to.replace(' ', 'T')) : null;
            var fromStr = periodFrom && !isNaN(periodFrom.getTime()) ? periodFrom.toLocaleTimeString('en-PH', { hour: '2-digit', minute: '2-digit' }) : '—';
            var toStr = periodTo && !isNaN(periodTo.getTime()) ? periodTo.toLocaleTimeString('en-PH', { hour: '2-digit', minute: '2-digit' }) : '—';
            var generatedAt = x.created_at ? new Date(x.created_at.replace(' ', 'T')) : new Date();
            var genStr = !isNaN(generatedAt.getTime()) ? generatedAt.toLocaleString('en-PH', { dateStyle: 'short', timeStyle: 'short' }) : '';
            var totalAmount = (parseFloat(x.cash_total) || 0) + (parseFloat(x.card_total) || 0) + (parseFloat(x.ewallet_total) || 0) + (parseFloat(x.hmo_total) || 0) + (parseFloat(x.split_total) || 0);
            var totalReturns = parseFloat(x.total_returns) || 0;
            var totalSales = totalAmount - totalReturns;
            var vatRemoved = parseFloat(x.vat_amount) || 0;
            var totalDiscounts = parseFloat(x.total_discounts) || 0;
            var grossSales = totalSales + vatRemoved + totalDiscounts;
            var changeFund = parseFloat(x.change_fund) || 0;
            var pullOuts = parseFloat(x.pull_outs) || 0;
            var totalInDrawer = totalSales + changeFund - totalReturns - pullOuts;
            var amountSubmitted = x.amount_submitted != null && x.amount_submitted !== '' ? parseFloat(x.amount_submitted) : null;
            if (amountSubmitted == null && x.cash_count && typeof x.cash_count === 'object') {
                var denomValuesX = { '1000': 1000, '500': 500, '200': 200, '100': 100, '50': 50, '20': 20, '10': 10, '5': 5, '1': 1, '0.25': 0.25, '0.10': 0.10, '0.05': 0.05, '0.01': 0.01 };
                var cashTotalX = 0;
                for (var k in denomValuesX) {
                    var qty = parseInt(x.cash_count[k], 10) || 0;
                    cashTotalX += qty * denomValuesX[k];
                }
                amountSubmitted = Math.round(cashTotalX * 100) / 100;
            }
            var amountOver = x.amount_over != null && x.amount_over !== '' ? parseFloat(x.amount_over) : (amountSubmitted != null ? Math.round((amountSubmitted - totalInDrawer) * 100) / 100 : null);
            var cashierName = (cashier.name || cashier.email || '—').toUpperCase();
            var adminName = (x.administrator_name || '—').toUpperCase();

            var html = '';
            html += '<div class="header-center">';
            var company = branch.company || {};
            var logoUrl = company.logo_url || (company.logo ? (window.location.origin + '/storage/' + company.logo) : null);
            if (logoUrl) html += '<img src="' + escapeHtml(logoUrl) + '" alt="" class="xreport-logo">';
            html += '<div class="font-bold" style="margin-bottom: 2px;">' + escapeHtml(branch.name || '') + '</div>';
            if (branch.address) html += '<div style="margin: 2px 0; font-size: 11px;">' + escapeHtml(branch.address) + '</div>';
            if (branch.tin) html += '<div style="margin: 2px 0;">TIN: ' + escapeHtml(branch.tin) + '</div>';
            html += '<div class="xreport-sep"></div>';
            html += '<div class="font-bold">BATCH SALES REPORT</div>';
            html += '<div class="xreport-sep"></div>';
            html += '<div>POS Terminal No. ' + escapeHtml(terminal.code || terminal.name || x.terminal_id || '—') + '</div>';
            html += '<div>Sales Batch No. : ' + escapeHtml(x.sales_batch_no || '—') + '</div>';
            html += '<div>Date: ' + genStr.split(',')[0] + '</div>';
            html += '<div>Time: ' + (genStr.indexOf(',') !== -1 ? genStr.split(',')[1].trim() : '') + '</div>';
            html += '<div>Cashier: ' + escapeHtml(cashierName) + '</div>';
            html += '<div class="xreport-sep"></div></div>';

            html += '<div class="xreport-section"><div class="font-bold">TENDER RECONCILIATION</div>';
            html += '<div class="xreport-row"><span class="left">Cash</span><span class="right">' + formatMoney(x.cash_total) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">Credit Card</span><span class="right">' + formatMoney(x.card_total) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">E-Wallet</span><span class="right">' + formatMoney(x.ewallet_total) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">HMO</span><span class="right">' + formatMoney(x.hmo_total) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">Split</span><span class="right">' + formatMoney(x.split_total) + '</span></div>';
            html += '<div class="xreport-row font-bold"><span class="left">TOTAL AMOUNT</span><span class="right">' + formatMoney(totalAmount) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">Less Refunds</span><span class="right">' + formatMoney(totalReturns) + '</span></div>';
            html += '<div class="xreport-row font-bold"><span class="left">TOTAL SALES</span><span class="right">' + formatMoney(totalSales) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">Add: VAT Removed</span><span class="right">' + formatMoney(vatRemoved) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">Add: Discounts</span><span class="right">' + formatMoney(totalDiscounts) + '</span></div>';
            html += '<div class="xreport-row font-bold"><span class="left">GROSS SALES</span><span class="right">' + formatMoney(grossSales) + '</span></div>';
            html += '</div><div class="xreport-sep"></div>';

            html += '<div class="xreport-section"><div class="font-bold">VAT DECLARATION</div>';
            html += '<div class="xreport-row"><span class="left">VATable Sales</span><span class="right">' + formatMoney(x.vatable_sales) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">VAT Amount</span><span class="right">' + formatMoney(x.vat_amount) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">VAT Exempt Sales</span><span class="right">' + formatMoney(x.vat_exempt) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">Zero Rated Sales</span><span class="right">' + formatMoney(x.zero_rated) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">CURRENT SALES</span><span class="right">' + formatMoney(totalSales) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">Less: Refunds</span><span class="right">' + formatMoney(totalReturns) + '</span></div>';
            html += '<div class="xreport-row font-bold"><span class="left">TOTAL SALES</span><span class="right">' + formatMoney(totalSales) + '</span></div>';
            html += '</div><div class="xreport-sep"></div>';

            html += '<div class="xreport-section"><div class="font-bold">CASHIER ACCOUNTABILITY</div>';
            html += '<div class="xreport-row"><span class="left">Current Sales</span><span class="right">' + formatMoney(totalSales) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">Add: Change Fund</span><span class="right">' + formatMoney(changeFund) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">Less: Refunds</span><span class="right">' + formatMoney(totalReturns) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">Less: Pull-Outs</span><span class="right">' + formatMoney(pullOuts) + '</span></div>';
            html += '<div class="xreport-row font-bold"><span class="left">Total In Drawer</span><span class="right">' + formatMoney(totalInDrawer) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">Amount Submitted</span><span class="right">' + (amountSubmitted != null ? formatMoney(amountSubmitted) : '__________') + '</span></div>';
            html += '<div class="xreport-row"><span class="left">Amount Over</span><span class="right">' + (amountOver != null ? formatMoney(amountOver) : '__________') + '</span></div>';
            html += '</div><div class="xreport-sep"></div>';

            html += '<div class="xreport-section"><div class="font-bold">CASHIER AUDIT</div>';
            html += '<div class="xreport-row"><span class="left">Items Sold</span><span class="right">' + (x.items_sold || 0) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">Transactions</span><span class="right">' + (x.total_transactions || 0) + '  ' + formatMoney(totalSales) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">Void Trans</span><span class="right">' + (x.void_transactions || 0) + '  ' + formatMoney(x.void_trans_amount || 0) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">SC VAT</span><span class="right">' + formatMoney(x.sc_vat || 0) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">SC Discounts</span><span class="right">' + formatMoney(x.sc_discount || 0) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">PWD VAT</span><span class="right">' + formatMoney(x.pwd_vat || 0) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">PWD Discounts</span><span class="right">' + formatMoney(x.pwd_discount || 0) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">Zero Rated</span><span class="right">' + formatMoney(x.zero_rated || 0) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">Net Sales</span><span class="right">' + formatMoney(x.net_sales) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">Sales Refunds</span><span class="right">' + formatMoney(totalReturns) + '</span></div>';
            html += '<div class="xreport-row"><span class="left">Price Quotes</span><span class="right">' + formatMoney(x.price_quotes || 0) + '</span></div>';
            html += '</div><div class="xreport-sep"></div>';

            html += '<div class="xreport-section"><div class="font-bold">CASH COUNT</div>';
            var denomKeys = ['1000', '500', '200', '100', '50', '20', '10', '5', '1', '0.25', '0.10', '0.05', '0.01'];
            var denomLabels = ['1,000.00', '500.00', '200.00', '100.00', '50.00', '20.00', '10.00', '5.00', '1.00', '0.25', '0.10', '0.05', '0.01'];
            var denomValues = [1000, 500, 200, 100, 50, 20, 10, 5, 1, 0.25, 0.10, 0.05, 0.01];
            var cashCount = x.cash_count || {};
            var cashCountTotal = 0;
            for (var i = 0; i < denomKeys.length; i++) {
                var count = parseInt(cashCount[denomKeys[i]], 10);
                if (isNaN(count)) count = 0;
                var subtotal = count * denomValues[i];
                cashCountTotal += subtotal;
                var countStr = count > 0 ? String(count) : '__________';
                var subtotalStr = count > 0 ? formatMoney(subtotal) : '__________';
                html += '<div class="xreport-row cash-count-row"><span class="cc-left">' + countStr + '</span><span class="cc-mid">' + denomLabels[i] + '</span><span class="cc-right">' + subtotalStr + '</span></div>';
            }
            html += '<div class="xreport-row font-bold"><span class="left">Total:</span><span class="right">' + (cashCountTotal > 0 ? formatMoney(cashCountTotal) : '__________') + '</span></div>';
            html += '</div><div class="xreport-sep"></div>';

            html += '<div class="header-center xreport-signatures">';
            html += '<div class="xreport-sep"></div>';
            html += '<div style="margin: 4px 0;">Cashier:</div>';
            html += '<div class="font-bold" style="margin: 2px 0;">' + escapeHtml(cashierName) + '</div>';
            html += '<div class="xreport-sep"></div>';
            html += '<div style="margin: 4px 0;">Administrator:</div>';
            html += '<div class="font-bold" style="margin: 2px 0;">' + escapeHtml(adminName) + '</div>';
            html += '<div class="xreport-sep"></div>';
            html += '<div style="margin-top: 6px; font-size: 11px;">' + escapeHtml(genStr) + '</div>';
            html += '<div style="margin-top: 6px; font-size: 10px;">** THIS IS NOT A Z-REPORT **</div>';
            html += '</div>';

            return html;
        }

        if (!xReadingId) {
            showError('Missing X-Reading ID.');
            return;
        }
        if (!token) {
            showError('Not logged in. Open this page from the POS after generating an X-Reading.');
            return;
        }

        fetch(apiBase + '/pos/x-reading/' + xReadingId, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + token,
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(function (res) {
            if (!res.ok) throw new Error('Could not load X-Reading.');
            return res.json();
        }).then(function (data) {
            var x = (data && data.data) ? data.data : data;
            if (!x) throw new Error('Invalid X-Reading data.');
            loadingEl.style.display = 'none';
            bodyEl.innerHTML = buildXReportHtml(x);
            bodyEl.style.display = 'block';
            setTimeout(function () { window.print(); }, 300);
        }).catch(function (err) {
            showError(err.message || 'Failed to load X-Reading.');
        });
    })();
    </script>
</body>
</html>
