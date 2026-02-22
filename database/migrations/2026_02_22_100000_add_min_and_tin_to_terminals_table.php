<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('terminals', function (Blueprint $table) {
            $table->string('min', 50)->nullable()->after('name')->comment('Machine Identification Number (BIR)');
            $table->string('tin', 50)->nullable()->after('min')->comment('Tax Identification Number');
        });
    }

    public function down(): void
    {
        Schema::table('terminals', function (Blueprint $table) {
            $table->dropColumn(['min', 'tin']);
        });
    }
};
