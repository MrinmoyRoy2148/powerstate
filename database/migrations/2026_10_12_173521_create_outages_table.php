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
        Schema::create('outages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained()->restrictOnDelete();
            $table->foreignId('schedule_id')->nullable()->constrained('load_shedding_schedules')->nullOnDelete();
            $table->foreignId('reason_id')->nullable()->constrained('outage_reasons')->nullOnDelete();
            $table->string('title', 180);
            $table->string('outage_type', 20);
            $table->text('description')->nullable();
            $table->dateTime('started_at')->index();
            $table->dateTime('expected_restoration_at')->nullable();
            $table->dateTime('restored_at')->nullable();
            $table->string('status', 20)->default('active')->index();
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outages');
    }
};
