<?php

namespace App\Http\Controllers;

use App\DataTables\ImplementationInitiativesDataTable;
use App\Http\Requests\StoreImplementationInitiativeRequest;
use App\Http\Requests\UpdateImplementationInitiativeRequest;
use App\Models\Directorate;
use App\Models\ImplementationStatus;
use App\Models\Initiative;
use App\Models\InitiativeStatus;
use App\Models\Objective;
use App\Models\Partner;
use App\Models\RequestStatus;
use App\Models\SupportRequest;

class ImplementationInitiativeController extends Controller
{
    public function index(ImplementationInitiativesDataTable $dataTable)
    {
        $objectives = Objective::all();
        $directorates = Directorate::all();
        $implementationStatuses = ImplementationStatus::all();
        return $dataTable->render('admin.implementation-initiatives.index', compact('objectives', 'directorates', 'implementationStatuses'));
    }

    public function create()
    {
        $objectives = Objective::all();
        $directorates = Directorate::all();
        $implementationStatuses = ImplementationStatus::all();
        $partners = Partner::all();
        $initiativeStatuses = InitiativeStatus::all();
        return view('admin.implementation-initiatives.new', compact('objectives', 'directorates', 'implementationStatuses', 'partners', 'initiativeStatuses'));
    }

    public function store(StoreImplementationInitiativeRequest $request)
    {
        Initiative::create($request->validated());
        return redirect()->route('admin.implementation-initiatives.index')->with('success_create', 'Implementation Initiative created successfully!');
    }

    public function show(Initiative $implementationInitiative)
    {
        if (request()->ajax()) {
            $implementationInitiative->load(['partner', 'initiativeStatus']);
            $creator = \App\Models\User::find($implementationInitiative->created_by);
            $getCreatedBy = $creator ? ($creator->first_name . ' ' . $creator->middle_name . ' ' . $creator->last_name) : 'Unknown';

            return response()->json([
                'success' => 1,
                'initiative' => $implementationInitiative,
                'partnerName' => $implementationInitiative->partner->name ?? 'N/A',
                'initiativeStatusName' => $implementationInitiative->initiativeStatus->name ?? 'N/A',
                'getCreatedBy' => $getCreatedBy,
                'created_at' => $implementationInitiative->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        return view('admin.implementation-initiatives.show', compact('implementationInitiative'));
    }

    public function edit(Initiative $implementationInitiative)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => 1,
                'initiative' => $implementationInitiative,
                'start_date' => $implementationInitiative->start_date ? $implementationInitiative->start_date->format('Y-m-d') : '',
                'end_date' => $implementationInitiative->end_date ? $implementationInitiative->end_date->format('Y-m-d') : '',
            ]);
        }
        return redirect()->route('admin.implementation-initiatives.index');
    }

    public function update(UpdateImplementationInitiativeRequest $request, Initiative $implementationInitiative)
    {
        $implementationInitiative->update($request->validated());
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.implementation-initiatives.index')->with('success_update', 'Implementation Details updated successfully!');
    }

    public function destroy(Initiative $implementationInitiative)
    {
        // Typically we might not allow deleting from the implementation stage,
        // but if required, it will just delete the initiative.
        $implementationInitiative->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.implementation-initiatives.index')->with('success_delete', 'Initiative deleted successfully!');
    }
}
