<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\DataTables\PermissionsDataTable;
use App\DataTables\RolesDataTable;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $roles = Role::all();
        // // dd();
        // $permissions = Permission::query();

        // if (request()->ajax()) {
        //     $index_column = 0;

        //     $dataTable = DataTables::eloquent($permissions)
        //         ->addColumn('no', function () use (&$index_column) {
        //             return ++$index_column;
        //         });

        //     $row_columns = [];
        //     foreach ($roles as $role) {
        //         $row_columns[] = $role->name;

        //         $dataTable->addColumn($role->name, function ($row) use($role) {
        //             if($role->hasPermissionTo($row)){

        //                 return '<span class="badge bg-success text-light" role="button"
        //                 onclick="update_role_permission(this,'.$role->id.','. $row->id.')">Yes</span>';
        //             }
        //             return '<span class="badge bg-danger text-light" role="button"
        //                 onclick="update_role_permission(this,'.$role->id.','. $row->id.')">No</span>';
        //         });
        //     }
        //     return $dataTable->rawColumns($row_columns)
        //      ->make(true);
        // }
        // $permissions = $permissions->get();

        // return view('admin.roles.index', compact('roles', 'permissions'));
        // return $dataTable->render('admin.roles.index');
        $roles = Role::latest()->get();
        $permissions = Permission::all();
        $pageNumbers = Constants::PAGE_NUMBER();

        // dd($pageNumbers);

        return view('admin.roles.index', compact('roles', 'permissions','pageNumbers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(StoreRoleRequest $request)
    {
        $roles = Role::create($request->validated());

        return view('admin.roles.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        if (request()->ajax()) {
            $validator = Validator::make(request()->all(), [
                'name' => ['required', 'string', Rule::unique('roles', 'name')],
            ]);

            if ($validator->fails()) {
                //pass validator errors as errors object for ajax response
                // dd($validator->validated());
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $role_validated_form = $validator->validated();

            $role = Role::create($role_validated_form);
            $role->code = request()->input('name');
            $role->save();

            return response()->json(array("success" => true, "role" => $role), 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Role $role)
    {
        if (request()->ajax()) {
            $validated_data = request()->validate([
                'id' => ['required', Rule::exists('roles', 'id')],
                'name' => ['required', 'string', Rule::unique('roles', 'name')->ignore($role)]
            ]);

            $role->update(
                [
                    'name' => $validated_data['name'],
                    'code' => $validated_data['name'],
            ]);

            return response()->json(array("success" => true, "role" => $role), 200);
        }
        // $role->update($request->validated());

        return view('admin.roles.edit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if (request()->ajax()) {
            $removed_role = $role;

            // dd($removed_role);

            if ($role->hasAnyPermission()) {
                $role->revokePermissionTo($role->permissions());
            }
            $role->delete();

            return response()->json(array("success" => true, "removed_role" => $removed_role), 200);
        }
        return view('admin.roles.edit');
    }
}
