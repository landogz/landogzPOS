<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_reference', 100)->nullable()->after('payment_method')->comment('Approval/reference number from card or e-wallet');
            $table->string('payment_provider', 100)->nullable()->after('payment_reference')->comment('e.g. GCash, Maya, Visa, Mastercard');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['payment_reference', 'payment_provider']);
        });
    }
};
