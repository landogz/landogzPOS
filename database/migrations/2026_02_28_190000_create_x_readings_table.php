<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('x_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cashier_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('terminal_id')->nullable()->constrained('terminals')->nullOnDelete();
            $table->string('shift', 20)->default('day');

            $table->string('or_series_start', 20)->nullable();
            $table->string('or_series_end', 20)->nullable();

            $table->unsignedInteger('total_transactions')->default(0);
            $table->unsignedInteger('void_transactions')->default(0);
            $table->unsignedInteger('returned_transactions')->default(0);

            $table->decimal('gross_sales', 12, 2)->default(0);
            $table->decimal('total_discounts', 12, 2)->default(0);
            $table->decimal('total_returns', 12, 2)->default(0);
            $table->decimal('net_sales', 12, 2)->default(0);

            $table->decimal('vatable_sales', 12, 2)->default(0);
            $table->decimal('vat_amount', 12, 2)->default(0);
            $table->decimal('vat_exempt', 12, 2)->default(0);
            $table->decimal('zero_rated', 12, 2)->default(0);

            $table->decimal('sc_discount', 12, 2)->default(0);
            $table->decimal('pwd_discount', 12, 2)->default(0);
            $table->decimal('promo_discount', 12, 2)->default(0);

            $table->decimal('cash_total', 12, 2)->default(0);
            $table->decimal('card_total', 12, 2)->default(0);
            $table->decimal('ewallet_total', 12, 2)->default(0);
            $table->decimal('hmo_total', 12, 2)->default(0);
            $table->decimal('split_total', 12, 2)->default(0);

            $table->dateTime('period_from')->nullable();
            $table->dateTime('period_to')->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->timestamps();

            $table->index(['branch_id', 'terminal_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('x_readings');
    }
};
