<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_registry', function (Blueprint $table) {
            $table->id();
            $table->string('terminal_name')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('type')->nullable(); // pos, inventory
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
            $table->index('mac_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_registry');
    }
};
