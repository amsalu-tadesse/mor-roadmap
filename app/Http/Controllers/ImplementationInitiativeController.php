<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\DataTables\ImplementationInitiativesDataTable;
use App\DataTables\InitiativeActivitiesDataTable;
use App\Http\Requests\StoreImplementationInitiativeRequest;
use App\Http\Requests\UpdateImplementationInitiativeRequest;
use App\Models\Activity;
use App\Models\ActivityStatus;
use App\Models\Directorate;
use App\Models\ImplementationStatus;
use App\Models\Initiative;
use App\Models\Objective;
use App\Models\Partner;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class ImplementationInitiativeController extends Controller
{
    public function index(ImplementationInitiativesDataTable $dataTable)
    {
        $objectives = Objective::all();
        $themes = Theme::all();
        $directorates = Directorate::all();
        $implementationStatuses = ImplementationStatus::all();
        $partners = Partner::all();
        $priorities = Activity::PRIORITIES;
        $initiatives = Initiative::whereHas('implementationStatus', function ($q) {
            $q->where('id', Constants::IMPLEMENTATION_STATUS_IMPLEMENTATION);
        })->get();
        $activityStatuses = ActivityStatus::all();

        $initiativeActivitiesEditTable = app(InitiativeActivitiesDataTable::class)
            ->setTableId('initiative-activities-edit-table')
            ->setShowActions(true);

        $initiativeActivitiesShowTable = app(InitiativeActivitiesDataTable::class)
            ->setTableId('initiative-activities-show-table')
            ->setShowActions(false);

        return $dataTable->render('admin.implementation-initiatives.index', compact(
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
        return view('admin.implementation-initiatives.new', compact('themes', 'objectives', 'directorates', 'implementationStatuses', 'partners', 'activityStatuses'));
    }

    public function store(StoreImplementationInitiativeRequest $request)
    {
        $data = $request->validated();
        if (empty($data['implementation_status_id'])) {
            $data['implementation_status_id'] = Constants::IMPLEMENTATION_STATUS_IMPLEMENTATION;
        }
        $initiative = Initiative::create(Arr::except($data, ['directorates']));
        $initiative->directorates()->sync($data['directorates']);
        return redirect()->route('admin.implementation-initiatives.index')->with('success_create', 'Implementation Initiative created successfully!');
    }

    public function show(Initiative $implementationInitiative)
    {
        if (request()->ajax()) {
            $implementationInitiative->load(['objective', 'directorates', 'theme']);
            $creator = User::find($implementationInitiative->created_by);
            $getCreatedBy = $creator ? ($creator->first_name . ' ' . $creator->middle_name . ' ' . $creator->last_name) : 'Unknown';

            $histories = $implementationInitiative->approvalHistories()->with('user')->get()->map(function ($h) {
                return [
                    'id' => $h->id,
                    'cycle_number' => $h->cycle_number,
                    'action' => $h->action,
                    'description' => $h->description,
                    'file_url' => $h->file ? Storage::url($h->file) : null,
                    'file_name' => $h->original_file_name ?: ($h->file ? basename($h->file) : null),
                    'remarks' => $h->remarks,
                    'user_name' => $h->user ? trim(($h->user->first_name ?? '') . ' ' . ($h->user->last_name ?? '')) : 'User',
                    'created_at' => $h->created_at ? $h->created_at->format('Y-m-d') : null,
                ];
            });

            $latestHistoryWithFile = $implementationInitiative->approvalHistories()->whereNotNull('file')->orderBy('id', 'desc')->first();
            $approvalFileName = $implementationInitiative->approval_original_file_name
                ?: ($latestHistoryWithFile ? $latestHistoryWithFile->original_file_name : null)
                ?: ($implementationInitiative->approval_file ? basename($implementationInitiative->approval_file) : null);

            return response()->json([
                'success' => 1,
                'initiative' => $implementationInitiative,
                'objectiveName' => $implementationInitiative->objective->name ?? 'N/A',
                'themeName' => $implementationInitiative->theme->name ?? 'N/A',
                'directorateName' => $implementationInitiative->directorates->pluck('name')->join(', ') ?: 'N/A',
                'getCreatedBy' => $getCreatedBy,
                'created_at' => $implementationInitiative->created_at->format('Y-m-d H:i:s'),
                'approval_file_url' => $implementationInitiative->approval_file ? Storage::url($implementationInitiative->approval_file) : null,
                'approval_file_name' => $approvalFileName,
                'histories' => $histories,
            ]);
        }
        return view('admin.implementation-initiatives.show', compact('implementationInitiative'));
    }

    public function edit(Initiative $implementationInitiative)
    {
        if (request()->ajax()) {
            $implementationInitiative->load(['objective', 'directorates']);
            return response()->json([
                'success' => 1,
                'initiative' => $implementationInitiative,
                'directorates' => $implementationInitiative->directorates->pluck('id')->toArray(),
            ]);
        }
        return redirect()->route('admin.implementation-initiatives.index');
    }

    public function update(UpdateImplementationInitiativeRequest $request, Initiative $implementationInitiative)
    {
        $data = $request->validated();
        $implementationInitiative->update(Arr::except($data, ['directorates']));
        $implementationInitiative->directorates()->sync($data['directorates']);
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
