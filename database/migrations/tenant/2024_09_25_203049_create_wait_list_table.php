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
        Schema::create('wait_list', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patient_id');
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the table; no need to drop foreign constraints by name (unsupported by some drivers)
        Schema::dropIfExists('wait_list');
    }
};
