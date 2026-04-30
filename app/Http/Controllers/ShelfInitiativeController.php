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

class ShelfInitiativeController extends Controller
{
    public function index(ShelfInitiativesDataTable $dataTable)
    {
        return $dataTable->render('admin.shelf-initiatives.index');
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
            $shelfInitiative->load(['partner', 'initiativeStatus']);
            $creator = \App\Models\User::find($shelfInitiative->created_by);
            $getCreatedBy = $creator ? ($creator->first_name . ' ' . $creator->middle_name . ' ' . $creator->last_name) : 'Unknown';

            return response()->json([
                'success' => 1,
                'initiative' => $shelfInitiative,
                'partnerName' => $shelfInitiative->partner->name ?? 'N/A',
                'initiativeStatusName' => $shelfInitiative->initiativeStatus->name ?? 'N/A',
                'getCreatedBy' => $getCreatedBy,
                'created_at' => $shelfInitiative->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        return view('admin.shelf-initiatives.show', compact('shelfInitiative'));
    }
}
