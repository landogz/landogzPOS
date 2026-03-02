<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('day_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('terminal_id')->constrained()->cascadeOnDelete();
            $table->date('session_date');
            $table->foreignId('opened_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('opening_cash', 14, 2)->default(0);
            $table->string('status', 20)->default('open'); // open, closed
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->string('or_series_start', 20)->nullable();
            $table->string('or_series_end', 20)->nullable();
            $table->unsignedBigInteger('z_reading_id')->nullable();
            $table->timestamps();

            $table->foreign('z_reading_id')->references('id')->on('z_readings')->nullOnDelete();
            $table->unique(['branch_id', 'terminal_id', 'session_date']);
            $table->index(['branch_id', 'terminal_id', 'session_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('day_sessions');
    }
};
