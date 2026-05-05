<?php

namespace App\Http\Controllers;

use App\DataTables\ShelfInitiativesDataTable;
use App\Http\Requests\StoreShelfInitiativeRequest;
use App\Models\Directorate;
use App\Models\ImplementationStatus;
use App\Models\Initiative;
use App\Models\InitiativeStatus;
use App\Models\Objective;
use App\Models\Partner;
use App\Models\RequestStatus;
use App\Models\SupportRequest;

class ShelfInitiativeController extends Controller
{
    public function index(ShelfInitiativesDataTable $dataTable)
    {
        $objectives = Objective::all();
        $directorates = Directorate::all();
        $implementationStatuses = ImplementationStatus::all();
        $partners = Partner::all();
        $requestStatuses = RequestStatus::all();
        $priorities = SupportRequest::PRIORITIES;
        return $dataTable->render('admin.shelf-initiatives.index', compact('objectives', 'directorates', 'implementationStatuses', 'partners', 'requestStatuses', 'priorities'));
    }

    public function create()
    {
        $objectives = Objective::all();
        $directorates = Directorate::all();
        $implementationStatuses = ImplementationStatus::all();
        $partners = Partner::all();
        $initiativeStatuses = InitiativeStatus::all();
        return view('admin.shelf-initiatives.new', compact('objectives', 'directorates', 'implementationStatuses', 'partners', 'initiativeStatuses'));
    }

    public function store(StoreShelfInitiativeRequest $request)
    {
        Initiative::create($request->validated());
        return redirect()->route('admin.shelf-initiatives.index')->with('success_create', 'Shelf Initiative created successfully!');
    }

    public function show(Initiative $shelfInitiative)
    {
        if (request()->ajax()) {
            $shelfInitiative->load(['partner', 'initiativeStatus', 'objective', 'directorate', 'implementationStatus']);
            $creator = \App\Models\User::find($shelfInitiative->created_by);
            $getCreatedBy = $creator ? ($creator->first_name . ' ' . $creator->middle_name . ' ' . $creator->last_name) : 'Unknown';

            return response()->json([
                'success' => 1,
                'initiative' => $shelfInitiative,
                'objectiveName' => $shelfInitiative->objective->name ?? 'N/A',
                'directorateName' => $shelfInitiative->directorate->name ?? 'N/A',
                'implementationStatusName' => $shelfInitiative->implementationStatus->name ?? 'N/A',
                'partnerName' => $shelfInitiative->partner->name ?? 'N/A',
                'initiativeStatusName' => $shelfInitiative->initiativeStatus->name ?? 'N/A',
                'getCreatedBy' => $getCreatedBy,
                'created_at' => $shelfInitiative->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        return view('admin.shelf-initiatives.show', compact('shelfInitiative'));
    }

    public function edit(Initiative $shelfInitiative)
    {
        if (request()->ajax()) {
            $shelfInitiative->load(['supportRequests.partner', 'supportRequests.requestStatus']);
            return response()->json([
                'success' => 1,
                'initiative' => $shelfInitiative,
                'supportRequests' => $shelfInitiative->supportRequests,
            ]);
        }
        $objectives = Objective::all();
        $directorates = Directorate::all();
        $implementationStatuses = ImplementationStatus::all();
        return view('admin.shelf-initiatives.edit', compact('shelfInitiative', 'objectives', 'directorates', 'implementationStatuses'));
    }

    public function update(\App\Http\Requests\UpdateDraftInitiativeRequest $request, Initiative $shelfInitiative)
    {
        $shelfInitiative->update($request->validated());
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.shelf-initiatives.index')->with('success_update', 'Shelf Initiative updated successfully!');
    }
}
