<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('initiative_id')->constrained('initiatives')->onDelete('cascade');
            $table->foreignId('partner_id')->constrained('partners');
            $table->text('activities');
            $table->foreignId('request_status_id')->constrained('request_statuses');
            $table->enum('priority', ['L', 'M', 'H'])->default('M');

            // Initiative lifecycle tracking fields (migrated from initiatives table)
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('budget')->nullable();
            $table->text('expenditure')->nullable();
            $table->double('completion', 8, 2)->nullable();
            $table->foreignId('activity_status_id')->nullable()->constrained('activity_statuses');
            $table->enum('request_type', ['New', 'Current'])->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
