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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('consultation_id');
            $table->decimal('value')->nullable();
            $table->enum('status', ['pending', 'paid', 'not-paid'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('consultation_id')->references('id')->on('consultations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign('payments_consultation_id_foreign');
            $table->dropIfExists();
        });
    }
};
