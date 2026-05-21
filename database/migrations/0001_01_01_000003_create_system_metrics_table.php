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
        Schema::create('system_metrics', function (Blueprint $table) {
            $table->id();
            $table->float('cpu_load')->nullable();
            $table->float('memory_used')->nullable();
            $table->float('memory_total')->nullable();
            $table->float('disk_used')->nullable();
            $table->float('disk_total')->nullable();
            $table->string('processor_name')->nullable();
            $table->integer('processor_cores')->nullable();
            $table->float('processor_frequency')->nullable();
            $table->dateTime('recorded_at')->nullable();
            $table->timestamps();
            $table->index('recorded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_metrics');
    }
};
