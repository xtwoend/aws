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
        Schema::create('aws_loggers', function (Blueprint $table) {
            $table->id();
            $table->datetime('terminal_time');
            $table->string('device_id');
            $table->string('device_location')->nullable();
            $table->decimal('wind_speed', 8, 2)->default(0);
            $table->integer('wind_direction')->default(0);
            $table->decimal('temperature', 8, 2)->default(0);
            $table->decimal('humidity', 8, 2)->default(0);
            $table->decimal('pressure', 8, 2)->default(0);
            $table->decimal('par_sensor', 8, 2)->default(0);
            $table->decimal('rainfall', 8, 2)->default(0);
            $table->decimal('solar_radiation', 8, 2)->default(0);
            $table->decimal('lat', 9, 6)->default(0);
            $table->decimal('lng', 9, 6)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aws_loggers');
    }
};
