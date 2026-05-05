<?php

namespace App\Http\Controllers;

use App\DataTables\SupportRequestsDataTable;
use App\Http\Requests\StoreSupportRequestRequest;
use App\Http\Requests\UpdateSupportRequestRequest;
use App\Models\Partner;
use App\Models\RequestStatus;
use App\Models\SupportRequest;
use App\Models\Initiative;

class SupportRequestController extends Controller
{
    public function index(SupportRequestsDataTable $dataTable)
    {
        $partners = Partner::all();
        $requestStatuses = RequestStatus::all();
        $priorities = SupportRequest::PRIORITIES;
        $initiatives = Initiative::whereHas('implementationStatus', function($q) {
            $q->whereIn('name', ['Implementation', 'Shelf']);
        })->get();

        return $dataTable->render('admin.support-requests.index', compact('partners', 'requestStatuses', 'priorities', 'initiatives'));
    }

    public function create()
    {
        $partners = Partner::all();
        $requestStatuses = RequestStatus::all();
        $priorities = SupportRequest::PRIORITIES;
        $initiatives = Initiative::whereHas('implementationStatus', function($q) {
            $q->whereIn('name', ['Implementation', 'Shelf']);
        })->get();
        return view('admin.support-requests.new', compact('partners', 'requestStatuses', 'priorities', 'initiatives'));
    }

    public function store(StoreSupportRequestRequest $request)
    {
        $supportRequest = SupportRequest::create($request->validated());
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.support-requests.index')->with('success_create', 'Support Request created successfully!');
    }

    public function show(SupportRequest $supportRequest)
    {
        if (request()->ajax()) {
            $supportRequest->load(['partner', 'requestStatus']);
            $creator = \App\Models\User::find($supportRequest->created_by);
            $getCreatedBy = $creator ? ($creator->first_name . ' ' . $creator->middle_name . ' ' . $creator->last_name) : 'Unknown';
            $priorityLabels = SupportRequest::PRIORITIES;

            return response()->json([
                'success' => 1,
                'supportRequest' => $supportRequest,
                'partnerName' => $supportRequest->partner->name ?? 'N/A',
                'requestStatusName' => $supportRequest->requestStatus->name ?? 'N/A',
                'priorityLabel' => $priorityLabels[$supportRequest->priority] ?? $supportRequest->priority,
                'getCreatedBy' => $getCreatedBy,
                'created_at' => $supportRequest->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        return view('admin.support-requests.show', compact('supportRequest'));
    }

    public function edit(SupportRequest $supportRequest)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => 1,
                'supportRequest' => $supportRequest,
            ]);
        }
        $partners = Partner::all();
        $requestStatuses = RequestStatus::all();
        $priorities = SupportRequest::PRIORITIES;
        $initiatives = Initiative::whereHas('implementationStatus', function($q) {
            $q->whereIn('name', ['Implementation', 'Shelf']);
        })->get();
        return view('admin.support-requests.edit', compact('supportRequest', 'partners', 'requestStatuses', 'priorities', 'initiatives'));
    }

    public function update(UpdateSupportRequestRequest $request, SupportRequest $supportRequest)
    {
        $supportRequest->update($request->validated());
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.support-requests.index')->with('success_update', 'Support Request updated successfully!');
    }

    public function destroy(SupportRequest $supportRequest)
    {
        $supportRequest->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.support-requests.index')->with('success_delete', 'Support Request deleted successfully!');
    }
}
