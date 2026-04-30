<?php

namespace App\Http\Controllers;

use App\DataTables\ImplementationStatusesDataTable;
use App\Http\Requests\StoreImplementationStatusRequest;
use App\Http\Requests\UpdateImplementationStatusRequest;
use App\Models\ImplementationStatus;
use Illuminate\Http\Request;

class ImplementationStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ImplementationStatusesDataTable $dataTable)
    {
        return $dataTable->render('admin.implementation-statuses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.implementation-statuses.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreImplementationStatusRequest $request)
    {
        ImplementationStatus::create($request->validated());
        return redirect()->route('admin.implementation-statuses.index')->with('success_create', 'Implementation Status created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ImplementationStatus $implementationStatus)
    {
        if (request()->ajax()) {
            $creator = \App\Models\User::find($implementationStatus->created_by);
            $getCreatedBy = $creator ? ($creator->first_name . ' ' . $creator->middle_name . ' ' . $creator->last_name) : 'Unknown';

            return response()->json([
                'success' => 1,
                'implementationStatus' => $implementationStatus,
                'getCreatedBy' => $getCreatedBy,
                'created_at' => $implementationStatus->created_at->format('Y-m-d H:i:s')
            ]);
        }
        return view('admin.implementation-statuses.show', compact('implementationStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ImplementationStatus $implementationStatus)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => 1,
                'implementationStatus' => $implementationStatus
            ]);
        }
        return view('admin.implementation-statuses.edit', compact('implementationStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateImplementationStatusRequest $request, ImplementationStatus $implementationStatus)
    {
        $implementationStatus->update($request->validated());
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.implementation-statuses.index')->with('success_update', 'Implementation Status updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ImplementationStatus $implementationStatus)
    {
        $implementationStatus->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.implementation-statuses.index')->with('success_delete', 'Implementation Status deleted successfully!');
    }
}
