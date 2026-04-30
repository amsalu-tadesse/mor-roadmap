<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDirectorateRequest;
use App\Http\Requests\UpdateDirectorateRequest;
use App\Models\Directorate;

use App\DataTables\DirectoratesDataTable;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DirectorateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DirectoratesDataTable $dataTable)
    {
        $users = User::select('id', DB::raw("CONCAT(first_name, ' ', last_name) as name"))->get();
        return $dataTable->render('admin.directorates.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::select('id', DB::raw("CONCAT(first_name, ' ', last_name) as name"))->get();
        return view('admin.directorates.new', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDirectorateRequest $request)
    {
        Directorate::create($request->validated());
        return redirect()->route('admin.directorates.index')->with('success_create', 'Directorate created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Directorate $directorate)
    {
        if (request()->ajax()) {
            $creator = \App\Models\User::find($directorate->created_by);
            $getCreatedBy = $creator ? ($creator->first_name . ' ' . $creator->middle_name . ' ' . $creator->last_name) : 'Unknown';

            return response()->json([
                'success' => 1,
                'directorate' => $directorate,
                'director' => $directorate->director,
                'getCreatedBy' => $getCreatedBy,
                'created_at' => $directorate->created_at->format('Y-m-d H:i:s')
            ]);
        }
        return view('admin.directorates.show', compact('directorate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Directorate $directorate)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => 1,
                'directorate' => $directorate
            ]);
        }
        $users = User::select('id', DB::raw("CONCAT(first_name, ' ', last_name) as name"))->get();
        return view('admin.directorates.edit', compact('directorate', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDirectorateRequest $request, Directorate $directorate)
    {
        $directorate->update($request->validated());
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.directorates.index')->with('success_update', 'Directorate updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Directorate $directorate)
    {
        $directorate->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.directorates.index')->with('success_delete', 'Directorate deleted successfully!');
    }
}
