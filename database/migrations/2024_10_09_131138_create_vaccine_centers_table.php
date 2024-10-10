<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("vaccine_centers", function (Blueprint $table) {
            $table->id();
            $table->string("name", 128);
            $table->unsignedSmallInteger("daily_limit");
            $table->date("available_day");
            $table->unsignedSmallInteger("available_day_booked")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("vaccine_centers");
    }
};
