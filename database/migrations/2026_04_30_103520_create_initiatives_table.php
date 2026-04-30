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
            
            // Drafting Stage Fields (Required for creation)
            $table->string('name');
            $table->foreignId('objective_id')->constrained('objectives');
            $table->foreignId('directorate_id')->constrained('directorates');
            $table->foreignId('implementation_status_id')->nullable()->constrained('implementation_statuses');
            $table->text('note')->nullable();
            
            // Implementation Stage Fields (Nullable initially)
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('budget')->nullable();
            $table->text('expenditure')->nullable();
            $table->foreignId('partner_id')->nullable()->constrained('partners');
            $table->float('completion')->nullable();
            $table->foreignId('initiative_status_id')->nullable()->constrained('initiative_statuses');
            $table->enum('request', ['New', 'Current'])->nullable();

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
