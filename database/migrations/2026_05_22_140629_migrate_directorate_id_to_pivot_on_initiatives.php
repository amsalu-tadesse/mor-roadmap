<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing directorate_id values into the pivot table
        DB::table('initiatives')
            ->whereNotNull('directorate_id')
            ->get()
            ->each(function ($initiative) {
                DB::table('directorate_initiative')->insert([
                    'initiative_id' => $initiative->id,
                    'directorate_id' => $initiative->directorate_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

        // Drop the old column
        Schema::table('initiatives', function (Blueprint $table) {
            $table->dropForeign(['directorate_id']);
            $table->dropColumn('directorate_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('initiatives', function (Blueprint $table) {
            $table->foreignId('directorate_id')->nullable()->constrained('directorates')->onDelete('set null');
        });
    }
};
