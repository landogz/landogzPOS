<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class PharmacyProductsSeeder extends Seeder
{
    /**
     * Real pharmacy products (Philippines-common OTC/generics). category_key must match category name.
     */
    protected function productDefinitions(): array
    {
        return [
            ['name' => 'Paracetamol 500mg Tablet', 'generic_name' => 'Paracetamol', 'brand' => 'Biogesic', 'category_key' => 'OTC', 'unit' => 'box', 'price' => 45.00, 'cost' => 28.00, 'reorder' => 20],
            ['name' => 'Paracetamol 500mg Tablet', 'generic_name' => 'Paracetamol', 'brand' => 'Tylenol', 'category_key' => 'OTC', 'unit' => 'box', 'price' => 85.00, 'cost' => 55.00, 'reorder' => 15],
            ['name' => 'Ibuprofen 200mg Tablet', 'generic_name' => 'Ibuprofen', 'brand' => 'Medicol', 'category_key' => 'OTC', 'unit' => 'box', 'price' => 65.00, 'cost' => 40.00, 'reorder' => 20],
            ['name' => 'Ibuprofen 400mg Tablet', 'generic_name' => 'Ibuprofen', 'brand' => 'Advil', 'category_key' => 'OTC', 'unit' => 'box', 'price' => 120.00, 'cost' => 75.00, 'reorder' => 15],
            ['name' => 'Mefenamic Acid 500mg', 'generic_name' => 'Mefenamic Acid', 'brand' => 'Ponstan', 'category_key' => 'OTC', 'unit' => 'box', 'price' => 95.00, 'cost' => 58.00, 'reorder' => 15],
            ['name' => 'Neozep Forte Capsule', 'generic_name' => 'Phenylephrine + Paracetamol + Chlorphenamine', 'brand' => 'Neozep', 'category_key' => 'OTC', 'unit' => 'box', 'price' => 78.00, 'cost' => 48.00, 'reorder' => 20],
            ['name' => 'Decolgen Forte Tablet', 'generic_name' => 'Phenylpropanolamine + Paracetamol', 'brand' => 'Decolgen', 'category_key' => 'OTC', 'unit' => 'box', 'price' => 72.00, 'cost' => 44.00, 'reorder' => 20],
            ['name' => 'Solmux 250mg Capsule', 'generic_name' => 'Carbocisteine', 'brand' => 'Solmux', 'category_key' => 'OTC', 'unit' => 'bottle', 'price' => 135.00, 'cost' => 82.00, 'reorder' => 12],
            ['name' => 'Bioflu Tablet', 'generic_name' => 'Phenylephrine + Paracetamol', 'brand' => 'Bioflu', 'category_key' => 'OTC', 'unit' => 'box', 'price' => 88.00, 'cost' => 54.00, 'reorder' => 18],
            ['name' => 'Amoxicillin 500mg Capsule', 'generic_name' => 'Amoxicillin', 'brand' => 'Amoxil', 'category_key' => 'Rx', 'unit' => 'capsule', 'price' => 12.00, 'cost' => 6.50, 'reorder' => 100],
            ['name' => 'Losartan 50mg Tablet', 'generic_name' => 'Losartan Potassium', 'brand' => 'Cozaar', 'category_key' => 'Rx', 'unit' => 'tablet', 'price' => 18.00, 'cost' => 10.00, 'reorder' => 50],
            ['name' => 'Metformin 500mg Tablet', 'generic_name' => 'Metformin HCl', 'brand' => 'Glucophage', 'category_key' => 'Rx', 'unit' => 'tablet', 'price' => 8.50, 'cost' => 4.20, 'reorder' => 100],
            ['name' => 'Omeprazole 20mg Capsule', 'generic_name' => 'Omeprazole', 'brand' => 'Omepron', 'category_key' => 'OTC', 'unit' => 'capsule', 'price' => 15.00, 'cost' => 8.00, 'reorder' => 60],
            ['name' => 'Kremil-S Tablet', 'generic_name' => 'Aluminum + Magnesium + Simethicone', 'brand' => 'Kremil-S', 'category_key' => 'OTC', 'unit' => 'box', 'price' => 165.00, 'cost' => 98.00, 'reorder' => 12],
            ['name' => 'Gaviscon Double Action', 'generic_name' => 'Sodium Alginate + Potassium Bicarbonate', 'brand' => 'Gaviscon', 'category_key' => 'OTC', 'unit' => 'bottle', 'price' => 245.00, 'cost' => 150.00, 'reorder' => 8],
            ['name' => 'Enervon Multivitamin Tablet', 'generic_name' => 'Multivitamins + B-Complex', 'brand' => 'Enervon', 'category_key' => 'Vitamins', 'unit' => 'bottle', 'price' => 185.00, 'cost' => 112.00, 'reorder' => 15],
            ['name' => 'Berocca Effervescent', 'generic_name' => 'B-Complex + Vitamin C', 'brand' => 'Berocca', 'category_key' => 'Vitamins', 'unit' => 'tube', 'price' => 285.00, 'cost' => 170.00, 'reorder' => 10],
            ['name' => 'Centrum Silver 50+', 'generic_name' => 'Multivitamin + Minerals', 'brand' => 'Centrum', 'category_key' => 'Vitamins', 'unit' => 'bottle', 'price' => 495.00, 'cost' => 298.00, 'reorder' => 8],
            ['name' => 'Ceelin 100mg/5ml Syrup', 'generic_name' => 'Ascorbic Acid', 'brand' => 'Ceelin', 'category_key' => 'Vitamins', 'unit' => 'bottle', 'price' => 125.00, 'cost' => 75.00, 'reorder' => 12],
            ['name' => 'Fern-C 1000mg Tablet', 'generic_name' => 'Sodium Ascorbate', 'brand' => 'Fern-C', 'category_key' => 'Vitamins', 'unit' => 'bottle', 'price' => 195.00, 'cost' => 118.00, 'reorder' => 10],
            ['name' => 'Stresstabs Tablet', 'generic_name' => 'B-Complex + Vitamin C', 'brand' => 'Stresstabs', 'category_key' => 'Vitamins', 'unit' => 'bottle', 'price' => 165.00, 'cost' => 98.00, 'reorder' => 12],
            ['name' => 'Alcohol 70% 500ml', 'generic_name' => 'Ethyl Alcohol', 'brand' => 'Generic', 'category_key' => 'Personal Care', 'unit' => 'bottle', 'price' => 85.00, 'cost' => 48.00, 'reorder' => 24],
            ['name' => 'Betadine Solution 120ml', 'generic_name' => 'Povidone Iodine', 'brand' => 'Betadine', 'category_key' => 'First Aid', 'unit' => 'bottle', 'price' => 125.00, 'cost' => 72.00, 'reorder' => 15],
            ['name' => 'Hydrogen Peroxide 3% 100ml', 'generic_name' => 'Hydrogen Peroxide', 'brand' => 'Generic', 'category_key' => 'First Aid', 'unit' => 'bottle', 'price' => 55.00, 'cost' => 32.00, 'reorder' => 20],
            ['name' => 'Cotton 100g', 'generic_name' => 'Absorbent Cotton', 'brand' => 'Generic', 'category_key' => 'First Aid', 'unit' => 'pack', 'price' => 65.00, 'cost' => 38.00, 'reorder' => 20],
            ['name' => 'Gauze Bandage 2" x 5yd', 'generic_name' => 'Gauze', 'brand' => 'Generic', 'category_key' => 'First Aid', 'unit' => 'roll', 'price' => 45.00, 'cost' => 26.00, 'reorder' => 30],
            ['name' => 'Bandage Strips Assorted', 'generic_name' => 'Adhesive Bandage', 'brand' => 'Band-Aid', 'category_key' => 'First Aid', 'unit' => 'box', 'price' => 95.00, 'cost' => 55.00, 'reorder' => 15],
            ['name' => 'Digital Thermometer', 'generic_name' => 'Thermometer', 'brand' => 'Omron', 'category_key' => 'Personal Care', 'unit' => 'pc', 'price' => 185.00, 'cost' => 105.00, 'reorder' => 10],
            ['name' => 'Face Mask 50s', 'generic_name' => 'Surgical Mask', 'brand' => 'Generic', 'category_key' => 'Personal Care', 'unit' => 'box', 'price' => 125.00, 'cost' => 68.00, 'reorder' => 20],
        ];
    }

    /**
     * Get or create standard pharmacy categories for a company.
     */
    protected function getCategoriesForCompany(Company $company): array
    {
        $names = ['OTC', 'Rx', 'Vitamins', 'Personal Care', 'First Aid'];
        $map = [];
        foreach ($names as $name) {
            $cat = Category::firstOrCreate(
                ['company_id' => $company->id, 'name' => $name],
                ['type' => $name]
            );
            $map[$name] = $cat->id;
        }
        return $map;
    }

    public function run(): void
    {
        $branches = Branch::with('company')->get();
        if ($branches->isEmpty()) {
            $this->command->warn('No branches found. Run PharmaPOSDemoSeeder or create branches first.');
            return;
        }

        $definitions = $this->productDefinitions();

        foreach ($branches as $branch) {
            $company = $branch->company;
            if (! $company) {
                continue;
            }

            $categoryIds = $this->getCategoriesForCompany($company);
            $supplier = Supplier::firstOrCreate(
                [
                    'branch_id' => $branch->id,
                    'name' => 'Pharmacy Supplier - ' . $branch->name,
                ],
                [
                    'company_id' => $company->id,
                    'contact' => 'supplier@pharmacy.local',
                    'address' => $branch->address ?? 'Supplier Address',
                ]
            );

            $created = 0;
            foreach ($definitions as $idx => $def) {
                $categoryId = $categoryIds[$def['category_key']] ?? $categoryIds['OTC'];
                $barcode = '8' . str_pad((string) $branch->id, 2, '0', STR_PAD_LEFT) . str_pad((string) ($idx + 1), 6, '0', STR_PAD_LEFT);

                $product = Product::firstOrCreate(
                    [
                        'branch_id' => $branch->id,
                        'barcode' => $barcode,
                    ],
                    [
                        'name' => $def['name'],
                        'generic_name' => $def['generic_name'],
                        'brand' => $def['brand'],
                        'category_id' => $categoryId,
                        'unit' => $def['unit'],
                        'price' => $def['price'],
                        'cost' => $def['cost'],
                        'reorder_level' => $def['reorder'],
                        'is_active' => true,
                    ]
                );

                if ($product->wasRecentlyCreated) {
                    $created++;
                    ProductBatch::create([
                        'product_id' => $product->id,
                        'batch_number' => 'B' . $branch->id . '-' . ($idx + 1) . '-' . now()->format('Ymd'),
                        'expiry_date' => now()->addMonths(rand(12, 24)),
                        'quantity' => (string) rand(30, 100),
                        'cost_price' => $def['cost'],
                        'supplier_id' => $supplier->id,
                    ]);
                }
            }

            $this->command->info("Branch \"{$branch->name}\": {$created} new products added (" . count($definitions) . " total definitions).");
        }
    }
}
