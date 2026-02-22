<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pos_sessions', function (Blueprint $table) {
            $table->dropColumn('terminal_id');
        });
        Schema::table('pos_sessions', function (Blueprint $table) {
            $table->foreignId('terminal_id')->nullable()->after('cashier_id')->constrained('terminals')->nullOnDelete();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('terminal_id')->nullable()->after('cashier_id')->constrained('terminals')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pos_sessions', function (Blueprint $table) {
            $table->dropForeign(['terminal_id']);
        });
        Schema::table('pos_sessions', function (Blueprint $table) {
            $table->string('terminal_id', 50)->nullable()->after('cashier_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['terminal_id']);
        });
    }
};
