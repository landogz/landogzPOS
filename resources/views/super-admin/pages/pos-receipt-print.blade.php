<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Official Receipt - Print</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Courier New', Courier, monospace; font-size: 12px; line-height: 1.35; color: #000; background: #fff; margin: 0; padding: 12px 16px; }
        @media print { body { padding: 0; } .no-print { display: none !important; } }
        .receipt { width: 100%; max-width: 48ch; margin: 0 auto; text-align: left; }
        .receipt .header-center { text-align: center; }
        .receipt .body-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .mt-1 { margin-top: 4px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }
        .mb-1 { margin-bottom: 4px; }
        .receipt-logo { max-width: 200px; max-height: 80px; width: auto; height: auto; margin: 0 auto 6px; display: block; object-fit: contain; }
        .receipt-title { font-size: 14px; font-weight: bold; margin-bottom: 2px; }
        .receipt-header-line { margin: 2px 0; }
        .receipt-sep { border: none; border-top: 1px dashed #000; margin: 6px 0; }
        .receipt-section { margin-top: 8px; padding-top: 8px; }
        .receipt-section.dashed { border-top: 1px dashed #000; }
        table.receipt-items { width: 100%; border-collapse: collapse; margin-top: 4px; font-size: 11px; }
        table.receipt-items th { text-align: left; border-bottom: 1px dashed #000; padding: 3px 2px; }
        table.receipt-items td { padding: 2px 2px; border-bottom: 1px dotted #333; }
        table.receipt-items .col-qty { width: 4ch; text-align: right; }
        table.receipt-items .col-price { width: 10ch; text-align: right; }
        table.receipt-items .col-amount { width: 11ch; text-align: right; }
        .receipt-row { display: flex; justify-content: space-between; margin: 2px 0; gap: 8px; }
        .receipt-row .left { flex-shrink: 0; }
        .receipt-row .right { text-align: right; flex-shrink: 0; }
        .receipt-vat { margin-top: 8px; font-size: 11px; }
        .receipt-vat table { width: 100%; }
        .receipt-vat td { padding: 2px 0; }
        .receipt-vat .label { text-align: left; }
        .receipt-vat .value { text-align: right; }
        .receipt-messages { margin-top: 10px; font-size: 11px; text-align: center; }
        .receipt-trans-no { text-align: center; }
        .receipt-customer-blanks { margin-top: 10px; font-size: 11px; }
        .receipt-customer-blanks .row { display: flex; align-items: flex-end; gap: 8px; margin: 4px 0; min-height: 18px; }
        .receipt-customer-blanks .label { flex-shrink: 0; }
        .receipt-customer-blanks .line { flex: 1; border-bottom: 1px solid #000; min-height: 14px; }
        .receipt-bir-footer { margin-top: 14px; padding-top: 8px; border-top: 1px dashed #000; font-size: 10px; text-align: center; }
        .receipt-bir-footer .receipt-validity-statement { text-transform: uppercase; }
        .loading { padding: 24px; text-align: center; color: #666; }
        .error { padding: 24px; text-align: center; color: #c00; }
    </style>
</head>
<body>
    <div id="receipt-container">
        <div id="receipt-loading" class="loading">Loading receipt…</div>
        <div id="receipt-error" class="error no-print" style="display: none;"></div>
        <div id="receipt-body" class="receipt" style="display: none;"></div>
    </div>

    <script>
    (function () {
        var apiBase = {!! json_encode($apiBase) !!};
        var params = new URLSearchParams(window.location.search);
        var transactionId = params.get('transaction_id');
        var amountReceived = parseFloat(params.get('amount_received')) || null;
        var change = parseFloat(params.get('change')) || null;

        var token = null;
        try {
            token = localStorage.getItem('super_admin_token');
        } catch (e) {}

        var loadingEl = document.getElementById('receipt-loading');
        var errorEl = document.getElementById('receipt-error');
        var bodyEl = document.getElementById('receipt-body');

        function showError(msg) {
            loadingEl.style.display = 'none';
            bodyEl.style.display = 'none';
            errorEl.textContent = msg;
            errorEl.style.display = 'block';
        }

        function formatMoney(n) {
            return (typeof n === 'number' ? n.toFixed(2) : parseFloat(n || 0).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        function buildReceiptHtml(r, amountReceivedParam, changeParam) {
            var h = r.receipt_header || {};
            var total = parseFloat(r.total) || 0;
            var amountRecv = amountReceivedParam != null ? amountReceivedParam : total;
            var changeAmount = changeParam != null ? changeParam : (amountRecv - total);
            if (changeAmount < 0) changeAmount = 0;

            var paymentLabel = (r.payment_method || 'cash').toLowerCase();
            if (paymentLabel === 'cash') paymentLabel = 'CASH SALES';
            else if (paymentLabel === 'ewallet') paymentLabel = 'E-WALLET';
            else paymentLabel = 'CHARGE SALES';

            var customerType = 'REGULAR';
            var customerName = '';
            (r.discounts || []).forEach(function (d) {
                if (d.type === 'sc_pwd' || d.type === 'senior_citizen') {
                    customerType = 'SENIOR CITIZEN';
                    if (d.customer_name) customerName = d.customer_name;
                } else if (d.type === 'pwd') {
                    customerType = 'PWD';
                    if (d.customer_name) customerName = d.customer_name;
                }
            });

            var html = '';
            html += '<div class="header-center mt-1">';

            if (h.logo_url) {
                html += '<img src="' + escapeAttr(h.logo_url) + '" alt="Store logo" class="receipt-logo">';
            }
            html += '<div class="receipt-title uppercase font-bold">' + escapeHtml(h.company_name || '') + '</div>';
            var addr = (h.address || '').split(/[\r\n]+/);
            addr.forEach(function (line) {
                if (line.trim()) html += '<div class="receipt-header-line">' + escapeHtml(line.trim()) + '</div>';
            });
            html += '<div class="receipt-header-line">VAT Registered</div>';
            if (h.vat_registered_tin) html += '<div class="receipt-header-line">TIN: ' + escapeHtml(h.vat_registered_tin) + '</div>';
            if (h.min) html += '<div class="receipt-header-line">MIN: ' + escapeHtml(h.min) + '</div>';
            html += '<div class="receipt-header-line">SN: ' + escapeHtml(h.sn || h.terminal_code || '') + '</div>';
            html += '</div>';

            html += '<hr class="receipt-sep">';
            html += '<div class="receipt-section body-left">';
            html += '<div class="receipt-row"><span class="left font-bold">' + escapeHtml(paymentLabel) + '</span><span class="right">' + escapeHtml(customerType) + '</span></div>';
            html += '<div class="receipt-row"><span class="left">POS ' + escapeHtml(h.terminal_code || '') + '</span><span class="right">' + escapeHtml(customerName) + '</span></div>';
            html += '</div>';
            html += '<hr class="receipt-sep">';

            html += '<div class="receipt-section body-left">';
            html += '<table class="receipt-items"><thead><tr><th>Item</th><th class="col-qty">Qty</th><th class="col-price">Price</th><th class="col-amount">Amount</th></tr></thead><tbody>';
            (r.items || []).forEach(function (item) {
                var amt = formatMoney(item.subtotal);
                if (item.is_vat_exempt) amt += 'v';
                html += '<tr><td>' + escapeHtml(item.product_name || '') + '</td><td class="col-qty">' + (item.quantity || 0) + '</td><td class="col-price">' + formatMoney(item.unit_price) + '</td><td class="col-amount">' + amt + '</td></tr>';
            });
            html += '</tbody></table>';

            var vatExemptVal = parseFloat(r.vat_exempt) || 0;
            if (vatExemptVal > 0) html += '<div class="receipt-row mt-1"><span class="left">Less 12% VAT</span><span class="right">-' + formatMoney(vatExemptVal) + '</span></div>';
            (r.discounts || []).forEach(function (d) {
                var label = 'Less ';
                if (d.type === 'sc_pwd' || d.type === 'senior_citizen' || d.type === 'pwd') label += '20% SC Discount';
                else if (d.type === 'employee') label += 'Employee Discount';
                else if (d.type === 'promo') label += 'Promo Discount';
                else label += 'Discount';
                html += '<div class="receipt-row mt-1"><span class="left">' + label + '</span><span class="right">-' + formatMoney(d.amount) + '</span></div>';
            });

            var itemCount = (r.items || []).length;
            html += '<hr class="receipt-sep">';
            html += '<div class="receipt-row font-bold"><span class="left">TOTAL:</span><span class="right">' + itemCount + ' Item' + (itemCount !== 1 ? 's' : '') + ' ' + formatMoney(total) + '</span></div>';
            html += '<div class="receipt-row"><span class="left">Payment Received:</span><span class="right">' + formatMoney(amountRecv) + '</span></div>';
            html += '<div class="receipt-row font-bold"><span class="left">CHANGE:</span><span class="right">' + formatMoney(changeAmount) + '</span></div>';
            html += '</div>';

            var vatSales = parseFloat(r.vatable_sales) || 0;
            var vatAmt = parseFloat(r.vat_amount) || 0;
            var vatExempt = parseFloat(r.vat_exempt) || 0;
            var zeroRated = parseFloat(r.zero_rated_sales) || 0;
            var nonVat = vatExempt;
            var totalSales = vatSales + nonVat + zeroRated;
            var totalVat = vatAmt;
            var discountAmt = parseFloat(r.discount_amount) || 0;

            html += '<hr class="receipt-sep">';
            html += '<div class="receipt-section receipt-vat">';
            html += '<table><tr><td class="label">VAT Sales</td><td class="value">' + formatMoney(vatSales) + '</td></tr>';
            html += '<tr><td class="label">Non-VAT Sales</td><td class="value">' + formatMoney(nonVat) + '</td></tr>';
            html += '<tr><td class="label">Zero-Rated Sales</td><td class="value">' + formatMoney(zeroRated) + '</td></tr>';
            html += '<tr><td class="label">Total Sales</td><td class="value">' + formatMoney(totalSales) + '</td></tr>';
            html += '<tr><td class="label">Total VAT</td><td class="value">' + formatMoney(totalVat) + '</td></tr>';
            html += '<tr><td class="label">Total Amount</td><td class="value">' + formatMoney(total) + '</td></tr>';
            html += '<tr><td class="label">Total Discount</td><td class="value">' + formatMoney(discountAmt) + '</td></tr>';
            if (vatExempt > 0) html += '<tr><td class="label">VAT Exemption</td><td class="value">' + formatMoney(vatExempt) + '</td></tr>';
            html += '</table></div>';

            html += '<hr class="receipt-sep">';
            html += '<div class="receipt-section receipt-trans-no">';
            var issuedAt = r.issued_at || '';
            if (issuedAt && issuedAt.indexOf('-') !== -1) {
                var d = new Date(issuedAt.replace(' ', 'T'));
                if (!isNaN(d.getTime())) issuedAt = (d.getMonth() + 1) + '/' + d.getDate() + '/' + d.getFullYear() + ' ' + String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0') + ':' + String(d.getSeconds()).padStart(2, '0');
            }
            html += '<div>Trans No. ' + escapeHtml(String(r.or_number || '')) + ' ' + issuedAt + '</div>';
            html += '</div>';

            html += '<div class="receipt-messages header-center">';
            html += '<div>THIS IS YOUR OFFICIAL RECEIPT</div>';
            html += '<div class="mt-2">THANK YOU, PLEASE COME AGAIN!</div>';
            html += '</div>';

            html += '<div class="receipt-customer-blanks receipt-section">';
            html += '<div class="row"><span class="label">Customer:</span><span class="line"></span></div>';
            html += '<div class="row"><span class="label">Address:</span><span class="line"></span></div>';
            html += '<div class="row"><span class="label">TIN:</span><span class="line"></span></div>';
            html += '<div class="row"><span class="label">SC ID No:</span><span class="line"></span></div>';
            html += '<div class="row"><span class="label">Signature:</span><span class="line"></span></div>';
            html += '</div>';

            var defaultValidityLine1 = 'THIS RECEIPT SHALL BE VALID FOR FIVE (5)';
            var defaultValidityLine2 = 'YEARS FROM THE DATE OF THE PERMIT TO USE';
            var validityStatement = (r.validity_statement && r.validity_statement.trim()) ? r.validity_statement : (defaultValidityLine1 + ' ' + defaultValidityLine2);
            var useDefaultValidity = !(r.validity_statement && r.validity_statement.trim());
            function formatDateYmd(s) {
                if (!s || s.length < 10) return s;
                var parts = s.split('-');
                if (parts.length >= 3) return parts[1] + '/' + parts[2] + '/' + parts[0];
                return s;
            }
            html += '<div class="receipt-bir-footer">';
            html += '<div class="font-bold">POS System Provider:</div>';
            html += '<div>' + escapeHtml(r.pos_system_provider || '') + '</div>';
            html += '<div>' + escapeHtml(r.provider_address || '') + '</div>';
            html += '<div>TIN ' + escapeHtml(r.tin || '') + '</div>';
            html += '<div>BIR Accreditation #' + escapeHtml(r.bir_accreditation_number || '') + '</div>';
            if (r.validity && r.validity.length >= 2) html += '<div>Issued: ' + formatDateYmd(r.validity[0]) + ' - Until: ' + formatDateYmd(r.validity[1]) + '</div>';
            html += '<div>PTU No. ' + escapeHtml(r.ptu_number || '') + '</div>';
            if (useDefaultValidity) {
                html += '<div class="receipt-validity-statement">' + escapeHtml(defaultValidityLine1) + '</div>';
                html += '<div>' + escapeHtml(defaultValidityLine2) + '</div>';
            } else {
                var validityParts = validityStatement.split(/<br\s*\/?>/gi);
                validityParts.forEach(function (part) {
                    var trimmed = (part || '').trim();
                    if (trimmed) html += '<div class="receipt-validity-statement">' + escapeHtml(trimmed) + '</div>';
                });
            }
            html += '</div>';

            return html;
        }

        function escapeHtml(s) {
            if (s == null) return '';
            var div = document.createElement('div');
            div.textContent = s;
            return div.innerHTML;
        }
        function escapeAttr(s) {
            if (s == null) return '';
            return String(s)
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        if (!transactionId) {
            showError('Missing transaction ID.');
            return;
        }
        if (!token) {
            showError('Not logged in. Please open this page from the POS after completing a sale.');
            return;
        }

        fetch(apiBase + '/receipts/' + transactionId, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + token,
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(function (res) {
            if (!res.ok) throw new Error('Could not load receipt.');
            return res.json();
        }).then(function (data) {
            var receipt = (data && data.data) ? data.data : data;
            if (!receipt) throw new Error('Invalid receipt data.');
            loadingEl.style.display = 'none';
            bodyEl.innerHTML = buildReceiptHtml(receipt, amountReceived, change);
            bodyEl.style.display = 'block';
            window.onload = function () {
                window.print();
            };
            setTimeout(function () { window.print(); }, 300);
        }).catch(function (err) {
            showError(err.message || 'Failed to load receipt. Please try again from the POS.');
        });
    })();
    </script>
</body>
</html>
