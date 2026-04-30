<?php

namespace App\Http\Controllers;

use App\DataTables\InitiativeStatusesDataTable;
use App\Http\Requests\StoreInitiativeStatusRequest;
use App\Http\Requests\UpdateInitiativeStatusRequest;
use App\Models\InitiativeStatus;
use Illuminate\Http\Request;

class InitiativeStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(InitiativeStatusesDataTable $dataTable)
    {
        return $dataTable->render('admin.initiative-statuses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.initiative-statuses.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInitiativeStatusRequest $request)
    {
        InitiativeStatus::create($request->validated());
        return redirect()->route('admin.initiative-statuses.index')->with('success_create', 'Initiative Status created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(InitiativeStatus $initiativeStatus)
    {
        if (request()->ajax()) {
            $creator = \App\Models\User::find($initiativeStatus->created_by);
            $getCreatedBy = $creator ? ($creator->first_name . ' ' . $creator->middle_name . ' ' . $creator->last_name) : 'Unknown';

            return response()->json([
                'success' => 1,
                'initiativeStatus' => $initiativeStatus,
                'getCreatedBy' => $getCreatedBy,
                'created_at' => $initiativeStatus->created_at->format('Y-m-d H:i:s')
            ]);
        }
        return view('admin.initiative-statuses.show', compact('initiativeStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InitiativeStatus $initiativeStatus)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => 1,
                'initiativeStatus' => $initiativeStatus
            ]);
        }
        return view('admin.initiative-statuses.edit', compact('initiativeStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInitiativeStatusRequest $request, InitiativeStatus $initiativeStatus)
    {
        $initiativeStatus->update($request->validated());
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.initiative-statuses.index')->with('success_update', 'Initiative Status updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InitiativeStatus $initiativeStatus)
    {
        $initiativeStatus->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.initiative-statuses.index')->with('success_delete', 'Initiative Status deleted successfully!');
    }
}
