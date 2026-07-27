<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->string('email')->nullable()->after('name');
        });

        // Register Spatie permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $sendUpdatePermission = Permission::firstOrCreate(['name' => 'partner: send-update', 'guard_name' => 'web']);

        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($sendUpdatePermission);
        }

        $planningDirectorate = Role::where('name', 'Planning Directorate')->first();
        if ($planningDirectorate) {
            $planningDirectorate->givePermissionTo($sendUpdatePermission);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn('email');
        });

        Permission::where('name', 'partner: send-update')->delete();
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
