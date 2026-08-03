<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('initiatives', function (Blueprint $table) {
            // 0 = not archived (normal), 1 = archive completed, 2 = archive pending
            $table->tinyInteger('archival_type')->default(0)->after('approval_status');
        });
    }

    public function down(): void
    {
        Schema::table('initiatives', function (Blueprint $table) {
            $table->dropColumn('archival_type');
        });
    }
};
