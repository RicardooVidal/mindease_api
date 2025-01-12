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
        Schema::table('wait_list', function (Blueprint $table) {
           $table->dropForeign('wait_list_patient_id_foreign');
           $table->dropIfExists();
        });
    }
};
