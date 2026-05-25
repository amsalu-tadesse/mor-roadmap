<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        // 1. Drop the correct existing foreign keys using column arrays
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
            $table->dropForeign(['request_status_id']);
        });

        // 2. Modify the columns to be nullable
        Schema::table('activities', function (Blueprint $table) {
            $table->unsignedBigInteger('partner_id')->nullable()->change();
            $table->unsignedBigInteger('request_status_id')->nullable()->change();
        });

        // 3. Re-apply foreign keys with the new nullOnDelete behavior
        Schema::table('activities', function (Blueprint $table) {
            $table->foreign('partner_id')->references('id')->on('partners')->nullOnDelete();
            $table->foreign('request_status_id')->references('id')->on('request_statuses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
            $table->dropForeign(['request_status_id']);
        });

        Schema::table('activities', function (Blueprint $table) {
            // Ensure your existing data doesn't contain NULL values before rolling down,
            // otherwise making it false will fail.
            $table->unsignedBigInteger('partner_id')->nullable(false)->change();
            $table->unsignedBigInteger('request_status_id')->nullable(false)->change();
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->foreign('partner_id')->references('id')->on('partners');
            $table->foreign('request_status_id')->references('id')->on('request_statuses');
        });
    }
};
