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
        Schema::create('network_traffic', function (Blueprint $table) {
            $table->id();
            $table->string('interface_name')->nullable();
            $table->float('download_speed')->nullable();
            $table->float('upload_speed')->nullable();
            $table->bigInteger('packets_sent')->nullable();
            $table->bigInteger('packets_received')->nullable();
            $table->bigInteger('bytes_sent')->nullable();
            $table->bigInteger('bytes_received')->nullable();
            $table->integer('active_connections')->nullable();
            $table->integer('established_connections')->nullable();
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
        Schema::dropIfExists('network_traffic');
    }
};
