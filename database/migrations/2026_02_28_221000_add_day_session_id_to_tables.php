<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('z_readings', function (Blueprint $table) {
            $table->unsignedBigInteger('day_session_id')->nullable()->after('terminal_id');
            $table->foreign('day_session_id')->references('id')->on('day_sessions')->nullOnDelete();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('day_session_id')->nullable()->after('terminal_id');
            $table->foreign('day_session_id')->references('id')->on('day_sessions')->nullOnDelete();
        });

        Schema::table('terminals', function (Blueprint $table) {
            $table->unsignedInteger('z_counter')->default(0)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('z_readings', function (Blueprint $table) {
            $table->dropForeign(['day_session_id']);
            $table->dropColumn('day_session_id');
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['day_session_id']);
            $table->dropColumn('day_session_id');
        });
        Schema::table('terminals', function (Blueprint $table) {
            $table->dropColumn('z_counter');
        });
    }
};
