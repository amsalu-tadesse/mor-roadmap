<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // if (request()->ajax())
        // {
        //     $permissionList = Permission::latest();

        //     return DataTables::of($permissionList)
        //             ->addColumn('actions', function($row){

        //                     $btn = '<a id="edit" class="action_btn mr_10"> <i class="far fa-edit"></i></a>
        //                             <a id="delete" class="action_btn"><i class="fas fa-trash"></i></a>';
        //                     return $btn;
        //             })
        //             ->rawColumns(['actions'])
        //             ->make(true);
        // }
        // return view('admin.permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.permissions.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        if (request()->ajax()) {
            $validated = request()->validate([
                'role_id' => ['required', Rule::exists('roles', 'id')],
                'permission_id' => ['required', Rule::exists('permissions', 'id')]
            ]);

            $permission = Permission::findById($validated['permission_id']);
            $role = Role::findById($validated['role_id']);

            //action => 1 permission given action => 0 not given
            if ($role->hasPermissionTo($permission)) {
                $role->revokePermissionTo($permission);
                return response()->json(array("success" => true, "action" => 0), 200); 
            }

            $role->givePermissionTo($permission);

            return response()->json(array("success" => true, "action" => 1), 200);
        }

        // $permissions = Permission::create($request->validated());

        return view('admin.permissions.new');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission->update($request->validated());
        return view('admin.permissions.edit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return view('admin.users.edit');
    }
}
