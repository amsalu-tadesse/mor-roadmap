<?php

namespace App\Http\Controllers;

use App\DataTables\ActivitiesDataTable;
use App\DataTables\InitiativeActivitiesDataTable;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use App\Models\ActivityStatus;
use App\Models\Initiative;
use App\Models\Partner;
use App\Models\RequestStatus;
use Illuminate\Support\Arr;

class ActivityController extends Controller
{
    public function initiativeDataTable(InitiativeActivitiesDataTable $dataTable)
    {
        return $dataTable->ajax();
    }

    public function index(ActivitiesDataTable $dataTable)
    {
        $partners = Partner::all();
        $requestStatuses = RequestStatus::all();
        $priorities = Activity::PRIORITIES;
        $initiatives = Initiative::whereHas('implementationStatus', function ($q) {
            $q->whereIn('name', ['Implementation', 'Shelf']);
        })->get();
        $activityStatuses = ActivityStatus::all();
        $directorates = \App\Models\Directorate::all();

        return $dataTable->render('admin.activities.index', compact('partners', 'requestStatuses', 'priorities', 'initiatives', 'activityStatuses', 'directorates'));
    }

    public function create()
    {
        $partners = Partner::all();
        $requestStatuses = RequestStatus::all();
        $priorities = Activity::PRIORITIES;
        $initiatives = Initiative::whereHas('implementationStatus', function ($q) {
            $q->whereIn('name', ['Implementation', 'Shelf']);
        })->get();
        $activityStatuses = ActivityStatus::all();
        return view('admin.activities.new', compact('partners', 'requestStatuses', 'priorities', 'initiatives', 'activityStatuses'));
    }

    public function store(StoreActivityRequest $request)
    {
        $data = $request->validated();
        $activity = Activity::create(Arr::except($data, ['interested_partners', 'directorates']));
        if (isset($data['interested_partners'])) {
            $activity->interestedPartners()->sync($data['interested_partners']);
        }
        if (isset($data['directorates'])) {
            $activity->directorates()->sync($data['directorates']);
        }
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.activities.index')->with('success_create', 'Activity created successfully!');
    }

    public function show(Activity $activity)
    {
        if (request()->ajax()) {
            $activity->load(['partner', 'requestStatus', 'activityStatus', 'interestedPartners', 'directorates']);
            $creator = \App\Models\User::find($activity->created_by);
            $getCreatedBy = $creator ? ($creator->first_name . ' ' . $creator->middle_name . ' ' . $creator->last_name) : 'Unknown';
            $priorityLabels = Activity::PRIORITIES;

            return response()->json([
                'success' => 1,
                'activity' => $activity,
                'partnerName' => $activity->partner->name ?? 'N/A',
                'requestStatusName' => $activity->requestStatus->name ?? 'N/A',
                'priorityLabel' => $priorityLabels[$activity->priority] ?? $activity->priority,
                'getCreatedBy' => $getCreatedBy,
                'created_at' => $activity->created_at->format('Y-m-d H:i:s'),
                'activityStatusName' => $activity->activityStatus->name ?? 'N/A',
                'interestedPartners' => $activity->interestedPartners,
                'directorates' => $activity->directorates,
                'start_date' => $activity->start_date ? \Carbon\Carbon::parse($activity->start_date)->format('Y-m-d') : null,
                'end_date' => $activity->end_date ? \Carbon\Carbon::parse($activity->end_date)->format('Y-m-d') : null,
            ]);
        }
        return view('admin.activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        if (request()->ajax()) {
            $activity->load(['interestedPartners', 'directorates']);
            return response()->json([
                'success' => 1,
                'activity' => $activity,
                'interested_partners' => $activity->interestedPartners->pluck('id')->toArray(),
                'directorates' => $activity->directorates->pluck('id')->toArray(),
            ]);
        }
        $partners = Partner::all();
        $requestStatuses = RequestStatus::all();
        $priorities = Activity::PRIORITIES;
        $initiatives = Initiative::whereHas('implementationStatus', function ($q) {
            $q->whereIn('name', ['Implementation', 'Shelf']);
        })->get();
        $activityStatuses = ActivityStatus::all();
        return view('admin.activities.edit', compact('activity', 'partners', 'requestStatuses', 'priorities', 'initiatives', 'activityStatuses'));
    }

    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        $data = $request->validated();
        $activity->update(Arr::except($data, ['interested_partners', 'directorates']));
        
        if (isset($data['interested_partners'])) {
            $activity->interestedPartners()->sync($data['interested_partners']);
        } else {
            $activity->interestedPartners()->detach();
        }

        if (isset($data['directorates'])) {
            $activity->directorates()->sync($data['directorates']);
        } else {
            $activity->directorates()->detach();
        }

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.activities.index')->with('success_update', 'Activity updated successfully!');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.activities.index')->with('success_delete', 'Activity deleted successfully!');
    }
}
