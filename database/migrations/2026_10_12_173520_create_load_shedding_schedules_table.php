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
        Schema::create('load_shedding_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained()->restrictOnDelete();
            $table->string('title', 180);
            $table->date('scheduled_date')->index();
            $table->time('start_time');
            $table->time('end_time');
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'scheduled_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('load_shedding_schedules');
    }
};
