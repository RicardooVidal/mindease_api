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
        Schema::create('public.companies', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->string('name');
            $table->string('email');
            $table->unsignedBigInteger('document')->unique();
            $table->string('contract')->nullable();
            $table->date('until');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public.companies');
    }
};
