<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('initiatives', 'approval_description')) {
            Schema::table('initiatives', function (Blueprint $table) {
                $table->text('approval_description')->nullable();
                $table->string('approval_file')->nullable();
                $table->string('approval_status')->nullable(); // 'proposed', 'approved', 'rejected'
            });
        }

        // Register spatie permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Delete old accept-approve permission if present
        Permission::where('name', 'shelf-initiative: accept-approve')->delete();

        $proposePermission = Permission::firstOrCreate(['name' => 'shelf-initiative: approval-request', 'guard_name' => 'web']);
        $acceptPermission = Permission::firstOrCreate(['name' => 'shelf-initiative: approve', 'guard_name' => 'web']);

        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo([$proposePermission, $acceptPermission]);
        }

        $planningDirectorate = Role::where('name', 'Planning Directorate')->first();
        if ($planningDirectorate) {
            $planningDirectorate->givePermissionTo($proposePermission);
            $planningDirectorate->revokePermissionTo($acceptPermission);
        }

        $higherOfficials = Role::where('name', 'Higher level officials')->first();
        if ($higherOfficials) {
            $higherOfficials->givePermissionTo($acceptPermission);
            $higherOfficials->revokePermissionTo($proposePermission);
        }
    }

    public function down(): void
    {
        Schema::table('initiatives', function (Blueprint $table) {
            $table->dropColumn(['approval_description', 'approval_file', 'approval_status']);
        });

        Permission::whereIn('name', ['shelf-initiative: approval-request', 'shelf-initiative: approve'])->delete();
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
