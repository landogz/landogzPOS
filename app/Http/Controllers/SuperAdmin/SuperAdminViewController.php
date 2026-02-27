<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SuperAdminViewController extends Controller
{
    public function login(): View
    {
        return view('super-admin.login', [
            'step' => request('step', ''),
            'email' => request('email', ''),
        ]);
    }

    /**
     * Public page: Request a Quote form (sends email to configured address).
     */
    public function requestQuote(): View
    {
        return view('request-quote');
    }

    public function dashboard(): View
    {
        return view('super-admin.dashboard');
    }

    public function users(): View
    {
        return view('super-admin.pages.placeholder', ['title' => 'Users', 'breadcrumb' => 'Users', 'apiModule' => 'users']);
    }

    public function suppliers(): View
    {
        return view('super-admin.pages.placeholder', ['title' => 'Suppliers', 'breadcrumb' => 'Suppliers', 'apiModule' => 'suppliers']);
    }

    public function products(): View
    {
        return view('super-admin.pages.placeholder', ['title' => 'Products', 'breadcrumb' => 'Products', 'apiModule' => 'products']);
    }

    public function categories(): View
    {
        return view('super-admin.pages.placeholder', ['title' => 'Categories', 'breadcrumb' => 'Categories', 'apiModule' => 'categories']);
    }

    public function transactions(): View
    {
        return view('super-admin.pages.placeholder', ['title' => 'Transactions', 'breadcrumb' => 'Transactions', 'apiModule' => 'transactions']);
    }

    public function inventory(): View
    {
        return view('super-admin.pages.inventory');
    }

    public function pos(): View
    {
        return view('super-admin.pages.pos');
    }

    public function posLock(): View
    {
        return view('super-admin.pages.pos-lock');
    }

    /**
     * POS Official Receipt print page (BIR-compliant). Opens in new window; script fetches receipt via API and triggers print.
     */
    public function posReceiptPrint(): View
    {
        return view('super-admin.pages.pos-receipt-print', [
            'apiBase' => url('/api/v1'),
        ]);
    }

    public function branches(): View
    {
        return view('super-admin.pages.branches');
    }

    public function terminals(): View
    {
        return view('super-admin.pages.terminals');
    }

    public function birSettings(): View
    {
        return view('super-admin.pages.bir-settings');
    }

    public function receipts(): View
    {
        return view('super-admin.pages.placeholder', ['title' => 'Receipts', 'breadcrumb' => 'Receipts', 'apiModule' => 'receipts']);
    }

    public function chainDashboard(): View
    {
        return view('super-admin.pages.placeholder', ['title' => 'Chain Dashboard', 'breadcrumb' => 'Chain Dashboard', 'apiModule' => 'dashboard/chain']);
    }

    public function companies(): View
    {
        return view('super-admin.pages.companies');
    }

    public function companySummary(\App\Models\Company $company): View
    {
        return view('super-admin.pages.company-summary', ['company' => $company]);
    }

    public function reportPage(): View
    {
        $routeName = request()->route()->getName();
        $type = $routeName ? str_replace('dashboard.reports.', '', $routeName) : 'sales';
        $titles = [
            'z-reading' => 'Z-Reading Report (End of Day)',
            'x-reading' => 'X-Reading Report (Interim)',
            'sales' => 'Sales Report',
            'vat-relief' => 'VAT Relief / Relief Data',
            'alphalist-payees' => 'Monthly Alphalist of Payees (MAP)',
            'audit-trail' => 'Audit Trail / Transaction Log',
            'inventory' => 'Inventory Report',
            'profit-margin' => 'Profit Margin Report',
            'expiring-products' => 'Expiring Products',
            'top-selling' => 'Top Selling',
            'cashier-summary' => 'Cashier Summary',
            'vat-summary' => 'VAT Summary',
            'audit-log' => 'Audit Log',
            'consolidated' => 'Consolidated Report',
        ];
        $title = $titles[$type] ?? ucfirst(str_replace('-', ' ', $type));
        return view('super-admin.pages.placeholder', [
            'title' => $title,
            'breadcrumb' => $title,
            'apiModule' => 'reports/' . $type,
        ]);
    }
}
