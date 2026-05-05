<?php

namespace App\Http\Controllers;

use App\DataTables\DraftInitiativesDataTable;
use App\Http\Requests\StoreDraftInitiativeRequest;
use App\Http\Requests\UpdateDraftInitiativeRequest;
use App\Models\Directorate;
use App\Models\ImplementationStatus;
use App\Models\Initiative;
use App\Models\Objective;

class DraftInitiativeController extends Controller
{
    public function index(DraftInitiativesDataTable $dataTable)
    {
        $objectives = Objective::all();
        $directorates = Directorate::all();
        $implementationStatuses = ImplementationStatus::all();
        return $dataTable->render('admin.draft-initiatives.index', compact('objectives', 'directorates', 'implementationStatuses'));
    }

    public function create()
    {
        $objectives = Objective::all();
        $directorates = Directorate::all();
        $implementationStatuses = ImplementationStatus::all();
        return view('admin.draft-initiatives.new', compact('objectives', 'directorates', 'implementationStatuses'));
    }

    public function store(StoreDraftInitiativeRequest $request)
    {
        Initiative::create($request->validated());
        return redirect()->route('admin.draft-initiatives.index')->with('success_create', 'Draft Initiative created successfully!');
    }

    public function show(Initiative $draftInitiative)
    {
        if (request()->ajax()) {
            $draftInitiative->load(['objective', 'directorate', 'implementationStatus']);
            $creator = \App\Models\User::find($draftInitiative->created_by);
            $getCreatedBy = $creator ? ($creator->first_name . ' ' . $creator->middle_name . ' ' . $creator->last_name) : 'Unknown';

            return response()->json([
                'success' => 1,
                'initiative' => $draftInitiative,
                'objectiveName' => $draftInitiative->objective->name ?? 'N/A',
                'directorateName' => $draftInitiative->directorate->name ?? 'N/A',
                'implementationStatusName' => $draftInitiative->implementationStatus->name ?? 'N/A',
                'getCreatedBy' => $getCreatedBy,
                'created_at' => $draftInitiative->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        return view('admin.draft-initiatives.show', compact('draftInitiative'));
    }

    public function edit(Initiative $draftInitiative)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => 1,
                'initiative' => $draftInitiative,
            ]);
        }
        $objectives = Objective::all();
        $directorates = Directorate::all();
        $implementationStatuses = ImplementationStatus::all();
        return view('admin.draft-initiatives.edit', compact('draftInitiative', 'objectives', 'directorates', 'implementationStatuses'));
    }

    public function update(UpdateDraftInitiativeRequest $request, Initiative $draftInitiative)
    {
        $draftInitiative->update($request->validated());
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.draft-initiatives.index')->with('success_update', 'Draft Initiative updated successfully!');
    }

    public function destroy(Initiative $draftInitiative)
    {
        $draftInitiative->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.draft-initiatives.index')->with('success_delete', 'Draft Initiative deleted successfully!');
    }
}
