<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bir_settings', function (Blueprint $table) {
            $table->string('provider_name')->nullable()->after('branch_id');
            $table->string('provider_address')->nullable()->after('provider_name');
            $table->string('ptu_number')->nullable()->after('valid_until');
            $table->text('validity_statement')->nullable()->after('ptu_number');
        });
    }

    public function down(): void
    {
        Schema::table('bir_settings', function (Blueprint $table) {
            $table->dropColumn(['provider_name', 'provider_address', 'ptu_number', 'validity_statement']);
        });
    }
};
