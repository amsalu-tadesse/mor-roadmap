<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners');
            $table->text('activities');
            $table->foreignId('request_status_id')->constrained('request_statuses');
            $table->enum('priority', ['L', 'M', 'H'])->default('M');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_requests');
    }
};
