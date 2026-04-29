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
        Schema::create('species_seizured', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trafficing_crime')->constrained('trafficing_crimes');
            $table->foreignId('species')->constrained('species');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('species_seizured');
    }
};
