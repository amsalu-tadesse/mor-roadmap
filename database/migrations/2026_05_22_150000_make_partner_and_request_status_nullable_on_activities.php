<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign('support_requests_partner_id_foreign');
            $table->dropForeign('support_requests_request_status_id_foreign');
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->unsignedBigInteger('partner_id')->nullable()->change();
            $table->unsignedBigInteger('request_status_id')->nullable()->change();
        });

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
            $table->unsignedBigInteger('partner_id')->nullable(false)->change();
            $table->unsignedBigInteger('request_status_id')->nullable(false)->change();
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->foreign('partner_id')->references('id')->on('partners');
            $table->foreign('request_status_id')->references('id')->on('request_statuses');
        });
    }
};
