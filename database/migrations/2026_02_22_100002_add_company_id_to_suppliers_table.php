<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        // Backfill company_id from branch->company_id
        $suppliers = DB::table('suppliers')->get();
        foreach ($suppliers as $supplier) {
            $companyId = DB::table('branches')->where('id', $supplier->branch_id)->value('company_id');
            if ($companyId) {
                DB::table('suppliers')->where('id', $supplier->id)->update(['company_id' => $companyId]);
            }
        }

        }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });
    }
};
