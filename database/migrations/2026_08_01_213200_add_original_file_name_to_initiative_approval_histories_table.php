<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('initiative_approval_histories', function (Blueprint $table) {
            $table->string('original_file_name')->nullable()->after('file');
        });
    }

    public function down(): void
    {
        Schema::table('initiative_approval_histories', function (Blueprint $table) {
            $table->dropColumn('original_file_name');
        });
    }
};
