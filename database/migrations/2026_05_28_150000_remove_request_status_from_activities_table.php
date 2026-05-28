<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['request_status_id']);
            $table->dropColumn('request_status_id');
        });

        Schema::dropIfExists('request_statuses');
    }

    public function down(): void
    {
        // No rollback is necessary as it is being removed permanently.
    }
};
