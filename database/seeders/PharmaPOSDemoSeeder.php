<?php

namespace Database\Seeders;

use App\Models\BirSetting;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Supplier;
use App\Models\Terminal;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PharmaPOSDemoSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::firstOrCreate(
            ['name' => 'Demo Pharmacy Chain'],
            [
                'tin' => '123-456-789-000',
                'bir_accreditation' => 'BIR-ACC-001',
                'address' => '123 Main St, Manila',
                'contact' => '+63 2 1234 5678',
            ]
        );

        $branchNames = ['Main Branch', 'Branch 2', 'Branch 3'];
        $branches = [];
        foreach ($branchNames as $i => $name) {
            $branches[] = Branch::firstOrCreate(
                ['company_id' => $company->id, 'name' => $name],
                [
                    'address' => ($i + 1) . ' Store St, Manila',
                    'tin' => '123-456-789-00' . ($i + 1),
                    'bir_series_start' => '000' . (($i * 1000) + 1),
                    'bir_series_end' => '000' . (($i + 1) * 1000),
                    'current_or_number' => 0,
                ]
            );
        }

        $roleNames = ['super_admin', 'admin', 'manager', 'inventory_manager', 'pharmacist', 'cashier'];
        foreach (['web', 'sanctum'] as $guard) {
            foreach ($roleNames as $name) {
                \Spatie\Permission\Models\Role::firstOrCreate(['name' => $name, 'guard_name' => $guard]);
            }
        }
        // Terminals: each branch has multiple POS terminals (e.g. Counter 1, Counter 2)
        if (Terminal::count() === 0) {
            foreach ($branches as $b => $branch) {
                foreach (['T1', 'T2', 'T3'] as $i => $code) {
                    Terminal::firstOrCreate(
                        ['branch_id' => $branch->id, 'code' => $code],
                        ['name' => 'Counter ' . ($i + 1), 'is_active' => true]
                    );
                }
            }
        }

        foreach ($roleNames as $i => $role) {
            $user = User::firstOrCreate(
                ['email' => $role . '@demo.pharmapos.test'],
                [
                    'branch_id' => $i === 0 ? null : $branches[min($i - 1, 2)]->id,
                    'name' => ucfirst(str_replace('_', ' ', $role)) . ' User',
                    'password' => Hash::make('password'),
                    'role' => $role,
                    'pin_hash' => $role === 'cashier' ? Hash::make('1234') : null,
                    'is_active' => true,
                ]
            );
            if (!$user->hasRole($role)) {
                $user->assignRole(\Spatie\Permission\Models\Role::where('name', $role)->where('guard_name', 'web')->first());
            }
        }

        $categories = [];
        foreach (['OTC', 'Rx', 'Vitamins', 'Personal Care', 'First Aid'] as $name) {
            $categories[] = Category::firstOrCreate(['name' => $name], ['type' => $name]);
        }

        if (Product::count() === 0) {
            foreach ($branches as $branch) {
                $sup = Supplier::firstOrCreate(
                    ['branch_id' => $branch->id, 'name' => 'Supplier ' . $branch->name],
                    ['contact' => 'sup@demo.test', 'address' => 'Address']
                );
                for ($i = 1; $i <= 17; $i++) {
                    $product = Product::create([
                    'branch_id' => $branch->id,
                    'barcode' => '890' . $branch->id . str_pad((string) $i, 8, '0', STR_PAD_LEFT),
                    'name' => $branch->name . ' Product ' . $i,
                    'generic_name' => 'Generic ' . $i,
                    'brand' => 'Brand ' . ($i % 5 + 1),
                    'category_id' => $categories[$i % count($categories)]->id,
                    'unit' => 'pc',
                    'price' => 10 + ($i * 2),
                    'cost' => 5 + $i,
                    'reorder_level' => 10,
                    'is_active' => true,
                ]);
                ProductBatch::create([
                    'product_id' => $product->id,
                    'batch_number' => 'B' . $branch->id . '-' . $i,
                    'expiry_date' => now()->addMonths(6 + ($i % 6)),
                    'quantity' => 50 + ($i * 2),
                    'cost_price' => 5 + $i,
                    'supplier_id' => $sup->id,
                ]);
            }
        }

        $cashier = User::where('role', 'cashier')->first();
        for ($t = 1; $t <= 100; $t++) {
            $branch = $branches[$t % 3];
            $branch->increment('current_or_number');
            $orNumber = (string) $branch->current_or_number;
            $transaction = Transaction::create([
                'branch_id' => $branch->id,
                'or_number' => $orNumber,
                'cashier_id' => $cashier->id,
                'total' => 0,
                'vat_amount' => 0,
                'discount_amount' => 0,
                'payment_method' => ['cash', 'card', 'gcash'][$t % 3],
                'status' => 'completed',
                'created_at' => now()->subDays(rand(0, 30))->setHour(rand(8, 20))->setMinute(rand(0, 59)),
            ]);
            $products = Product::where('branch_id', $branch->id)->limit(rand(1, 5))->get();
            $total = 0;
            foreach ($products as $p) {
                $qty = rand(1, 3);
                $price = (float) $p->price;
                $sub = $qty * $price;
                $total += $sub;
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $p->id,
                    'product_batch_id' => $p->batches()->first()?->id,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'subtotal' => $sub,
                ]);
            }
            $transaction->update(['total' => $total, 'vat_amount' => round($total / 1.12 * 0.12, 2)]);
            }
        }

        foreach ($branches as $branch) {
            BirSetting::firstOrCreate(
                ['branch_id' => $branch->id],
                [
                    'tin' => $branch->tin,
                    'accreditation_number' => 'ACC-' . $branch->id,
                    'series_start' => $branch->bir_series_start,
                    'series_end' => $branch->bir_series_end,
                    'valid_from' => now()->startOfYear(),
                    'valid_until' => now()->endOfYear(),
                    'footer_text' => 'This document is not valid for claim of input tax.',
                ]
            );
        }
    }
}
