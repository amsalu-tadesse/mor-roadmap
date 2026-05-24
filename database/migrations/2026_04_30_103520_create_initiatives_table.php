<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('initiatives', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->foreignId('objective_id')->constrained('objectives');
            $table->foreignId('directorate_id')->constrained('directorates');
            $table->foreignId('theme_id')->nullable()->constrained('themes');
            $table->foreignId('implementation_status_id')->nullable()->constrained('implementation_statuses');
            $table->text('note')->nullable();

            // Audit & Timestamps
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('initiatives');
    }
};
