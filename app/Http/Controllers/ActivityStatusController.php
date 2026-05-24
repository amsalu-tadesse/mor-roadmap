<?php

namespace App\Http\Controllers;

use App\DataTables\ActivityStatusesDataTable;
use App\Http\Requests\StoreActivityStatusRequest;
use App\Http\Requests\UpdateActivityStatusRequest;
use App\Models\ActivityStatus;
use Illuminate\Http\Request;

class ActivityStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ActivityStatusesDataTable $dataTable)
    {
        return $dataTable->render('admin.activity-statuses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.activity-statuses.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityStatusRequest $request)
    {
        ActivityStatus::create($request->validated());
        return redirect()->route('admin.activity-statuses.index')->with('success_create', 'Activity Status created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ActivityStatus $activityStatus)
    {
        if (request()->ajax()) {
            $creator = \App\Models\User::find($activityStatus->created_by);
            $getCreatedBy = $creator ? ($creator->first_name . ' ' . $creator->middle_name . ' ' . $creator->last_name) : 'Unknown';

            return response()->json([
                'success' => 1,
                'activityStatus' => $activityStatus,
                'getCreatedBy' => $getCreatedBy,
                'created_at' => $activityStatus->created_at->format('Y-m-d H:i:s')
            ]);
        }
        return view('admin.activity-statuses.show', compact('activityStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActivityStatus $activityStatus)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => 1,
                'activityStatus' => $activityStatus
            ]);
        }
        return view('admin.activity-statuses.edit', compact('activityStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityStatusRequest $request, ActivityStatus $activityStatus)
    {
        $activityStatus->update($request->validated());
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.activity-statuses.index')->with('success_update', 'Activity Status updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityStatus $activityStatus)
    {
        $activityStatus->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.activity-statuses.index')->with('success_delete', 'Activity Status deleted successfully!');
    }
}
