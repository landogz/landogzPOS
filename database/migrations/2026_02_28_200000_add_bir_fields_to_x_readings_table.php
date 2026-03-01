<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('x_readings', function (Blueprint $table) {
            $table->string('sales_batch_no', 20)->nullable()->after('or_series_end');
            $table->decimal('void_trans_amount', 12, 2)->default(0)->after('void_transactions');
            $table->unsignedInteger('items_sold')->default(0)->after('returned_transactions');
            $table->decimal('sc_vat', 12, 2)->default(0)->after('sc_discount');
            $table->decimal('pwd_vat', 12, 2)->default(0)->after('pwd_discount');
            $table->decimal('price_quotes', 12, 2)->default(0)->after('net_sales');
            $table->decimal('change_fund', 12, 2)->default(0)->after('split_total');
            $table->decimal('pull_outs', 12, 2)->default(0)->after('change_fund');
            $table->decimal('amount_submitted', 12, 2)->nullable()->after('pull_outs');
            $table->decimal('amount_over', 12, 2)->nullable()->after('amount_submitted');
            $table->string('administrator_name', 120)->nullable()->after('printed_at');
        });
    }

    public function down(): void
    {
        Schema::table('x_readings', function (Blueprint $table) {
            $table->dropColumn([
                'sales_batch_no', 'items_sold', 'void_trans_amount',
                'sc_vat', 'pwd_vat', 'price_quotes',
                'change_fund', 'pull_outs', 'amount_submitted', 'amount_over',
                'administrator_name',
            ]);
        });
    }
};
