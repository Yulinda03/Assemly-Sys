<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scans', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->unique(); // Anti-duplicate global
            $table->string('component'); // result of auto-routing (wifi, ram, etc)
            $table->string('line'); // Line 1 - Line 15
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who scanned it
            $table->timestamps();

            // Index for faster queries on dashboards
            $table->index(['line', 'component']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scans');
    }
};
