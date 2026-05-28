<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\DataTables\InitiativeActivitiesDataTable;
use App\DataTables\ShelfInitiativesDataTable;
use App\Http\Requests\StoreShelfInitiativeRequest;
use App\Models\Directorate;
use App\Models\ImplementationStatus;
use App\Models\Initiative;
use App\Models\ActivityStatus;
use App\Models\Objective;
use App\Models\Partner;
use App\Models\Activity;
use App\Models\Theme;
use Illuminate\Support\Arr;

class ShelfInitiativeController extends Controller
{
    public function index(ShelfInitiativesDataTable $dataTable)
    {
        $objectives = Objective::all();
        $themes = Theme::all();
        $directorates = Directorate::all();
        $implementationStatuses = ImplementationStatus::all();
        $partners = Partner::all();
        $priorities = Activity::PRIORITIES;
        $initiatives = Initiative::whereHas('implementationStatus', function ($q) {
            $q->where('id', Constants::IMPLEMENTATION_STATUS_SHELFING);
        })->get();
        $activityStatuses = ActivityStatus::all();

        $initiativeActivitiesEditTable = app(InitiativeActivitiesDataTable::class)
            ->setTableId('initiative-activities-edit-table')
            ->setShowActions(true);

        $initiativeActivitiesShowTable = app(InitiativeActivitiesDataTable::class)
            ->setTableId('initiative-activities-show-table')
            ->setShowActions(false);

        return $dataTable->render('admin.shelf-initiatives.index', compact(
            'objectives', 'themes', 'directorates', 'implementationStatuses',
            'partners', 'priorities', 'initiatives', 'activityStatuses',
            'initiativeActivitiesEditTable', 'initiativeActivitiesShowTable'
        ));
    }

    public function create()
    {
        $themes = Theme::all();
        $objectives = Objective::all();
        $directorates = Directorate::all();
        $implementationStatuses = ImplementationStatus::all();
        $partners = Partner::all();
        $activityStatuses = ActivityStatus::all();
        return view('admin.shelf-initiatives.new', compact('themes', 'objectives', 'directorates', 'implementationStatuses', 'partners', 'activityStatuses'));
    }

    public function store(StoreShelfInitiativeRequest $request)
    {
        $data = $request->validated();
        if (empty($data['implementation_status_id'])) {
            $data['implementation_status_id'] = Constants::IMPLEMENTATION_STATUS_SHELFING;
        }
        $initiative = Initiative::create(Arr::except($data, ['directorates']));
        $initiative->directorates()->sync($data['directorates']);
        return redirect()->route('admin.shelf-initiatives.index')->with('success_create', 'Shelf Initiative created successfully!');
    }

    public function show(Initiative $shelfInitiative)
    {
        if (request()->ajax()) {
            $shelfInitiative->load(['objective', 'directorates', 'implementationStatus', 'theme']);
            $creator = \App\Models\User::find($shelfInitiative->created_by);
            $getCreatedBy = $creator ? ($creator->first_name . ' ' . $creator->middle_name . ' ' . $creator->last_name) : 'Unknown';
        // dd([
        //     'initiative_id' => $shelfInitiative->id,
        //     'objective_id' => $shelfInitiative->objective_id,
        //     'objective' => $shelfInitiative->objective,
        //     'theme' => $shelfInitiative->objective->theme ?? null,
        //     'theme_name' => $shelfInitiative->objective->theme->name ?? 'N/A',
        // ]);
            return response()->json([
                'success' => 1,
                'initiative' => $shelfInitiative,
                'objectiveName' => $shelfInitiative->objective->name ?? 'N/A',
                'themeName' => $shelfInitiative->objective->theme->name ?? 'N/A',
                'directorateName' => $shelfInitiative->directorates->pluck('name')->join(', ') ?: 'N/A',
                'implementationStatusName' => $shelfInitiative->implementationStatus->name ?? 'N/A',
                'getCreatedBy' => $getCreatedBy,
                'created_at' => $shelfInitiative->created_at ? $shelfInitiative->created_at->format('Y-m-d H:i:s') : null,
            ]);
        }
        return view('admin.shelf-initiatives.show', compact('shelfInitiative'));
    }

    public function edit(Initiative $shelfInitiative)
    {
        if (request()->ajax()) {
            $shelfInitiative->load(['objective', 'directorates']);
            return response()->json([
                'success' => 1,
                'initiative' => $shelfInitiative,
                'directorates' => $shelfInitiative->directorates->pluck('id')->toArray(),
            ]);
        }
        $objectives = Objective::all();
        $directorates = Directorate::all();
        $implementationStatuses = ImplementationStatus::all();
        return view('admin.shelf-initiatives.edit', compact('shelfInitiative', 'objectives', 'directorates', 'implementationStatuses'));
    }

    public function update(\App\Http\Requests\UpdateDraftInitiativeRequest $request, Initiative $shelfInitiative)
    {
        $data = $request->validated();
        $shelfInitiative->update(Arr::except($data, ['directorates']));
        $shelfInitiative->directorates()->sync($data['directorates']);
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.shelf-initiatives.index')->with('success_update', 'Shelf Initiative updated successfully!');
    }
}
