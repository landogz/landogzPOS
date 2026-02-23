<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('terminals', function (Blueprint $table) {
            if (! Schema::hasColumn('terminals', 'api_key_prefix')) {
                $table->string('api_key_prefix', 12)->nullable()->after('is_active');
            }
            if (! Schema::hasColumn('terminals', 'api_key_hash')) {
                $table->string('api_key_hash')->nullable()->after('api_key_prefix');
            }
            if (! Schema::hasColumn('terminals', 'api_key_last_used_at')) {
                $table->timestamp('api_key_last_used_at')->nullable()->after('api_key_hash');
            }
        });
    }

    public function down(): void
    {
        Schema::table('terminals', function (Blueprint $table) {
            $table->dropColumn(['api_key_prefix', 'api_key_hash', 'api_key_last_used_at']);
        });
    }
};
