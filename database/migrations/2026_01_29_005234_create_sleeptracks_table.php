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
        Schema::create('sleeptracks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\SleepReport::class);
            $table->string('input_type');
            $table->datetime('input_time');
            $table->string('location');
            $table->double('long')->nullable();
            $table->double('lat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sleeptracks');
    }
};
