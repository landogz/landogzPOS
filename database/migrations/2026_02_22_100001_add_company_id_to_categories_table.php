<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        // Assign existing categories to first company if any exists
        if (Schema::hasTable('companies') && DB::table('companies')->exists()) {
            $firstCompanyId = DB::table('companies')->min('id');
            if ($firstCompanyId) {
                DB::table('categories')->whereNull('company_id')->update(['company_id' => $firstCompanyId]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });
    }
};
