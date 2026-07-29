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
                'approval_file_url' => $shelfInitiative->approval_file ? \Illuminate\Support\Facades\Storage::url($shelfInitiative->approval_file) : null,
                'approval_file_name' => $shelfInitiative->approval_file ? basename($shelfInitiative->approval_file) : null,
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

    public function approve(Initiative $shelfInitiative)
    {
        $shelfInitiative->update([
            'implementation_status_id' => Constants::IMPLEMENTATION_STATUS_IMPLEMENTATION
        ]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.shelf-initiatives.index')->with('success_update', 'Initiative approved and moved to implementation stage successfully!');
    }

    public function showApproval(Initiative $shelfInitiative)
    {
        return response()->json([
            'success' => 1,
            'approval_description' => $shelfInitiative->approval_description,
            'approval_file_url' => $shelfInitiative->approval_file ? \Illuminate\Support\Facades\Storage::url($shelfInitiative->approval_file) : null,
            'approval_file_name' => $shelfInitiative->approval_file ? basename($shelfInitiative->approval_file) : null,
            'approval_status' => $shelfInitiative->approval_status,
        ]);
    }

    public function proposeApproval(\Illuminate\Http\Request $request, Initiative $shelfInitiative)
    {
        $request->validate([
            'approval_description' => 'nullable|string',
            'approval_file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg,zip|max:10240',
            'decision' => 'required|in:approve,reject',
        ]);

        $status = $request->input('decision') === 'approve' ? 'proposed' : 'rejected';

        $data = [
            'approval_description' => $request->input('approval_description'),
            'approval_status' => $status,
        ];

        if ($request->hasFile('approval_file')) {
            if ($shelfInitiative->approval_file) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($shelfInitiative->approval_file);
            }
            $path = $request->file('approval_file')->store('approval_files', 'public');
            $data['approval_file'] = $path;
        }

        $shelfInitiative->update($data);

        return response()->json([
            'success' => true,
            'message' => $status === 'proposed' ? 'Initiative approval proposed successfully.' : 'Initiative rejected.',
        ]);
    }

    public function acceptApproval(\Illuminate\Http\Request $request, Initiative $shelfInitiative)
    {
        $request->validate([
            'decision' => 'required|in:approve,reject',
            'approval_remarks' => 'required|string',
        ]);

        $remarks = $request->input('approval_remarks');

        if ($request->input('decision') === 'approve') {
            $shelfInitiative->update([
                'approval_status' => 'approved',
                'approval_remarks' => $remarks,
                'implementation_status_id' => Constants::IMPLEMENTATION_STATUS_IMPLEMENTATION,
            ]);
            $message = 'Initiative has been officially approved and moved to the implementation stage.';
        } else {
            $shelfInitiative->update([
                'approval_status' => 'rejected',
                'approval_remarks' => $remarks,
            ]);
            $message = 'Initiative approval request has been rejected.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }
}
