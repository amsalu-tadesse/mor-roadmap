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
        $initiatives = Initiative::whereHas('implementationStatus', function ($q) {
            $q->whereIn('name', ['Implementation', 'Shelf']);
        })->get();

        return $dataTable->render('admin.support-requests.index', compact('partners', 'requestStatuses', 'priorities', 'initiatives'));
    }

    public function create()
    {
        $partners = Partner::all();
        $requestStatuses = RequestStatus::all();
        $priorities = SupportRequest::PRIORITIES;
        $initiatives = Initiative::whereHas('implementationStatus', function ($q) {
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

    public function show(SupportRequest $support_request)
    {
        if (request()->ajax()) {
            $support_request->load(['partner', 'requestStatus']);
            $creator = \App\Models\User::find($support_request->created_by);
            $getCreatedBy = $creator ? ($creator->first_name . ' ' . $creator->middle_name . ' ' . $creator->last_name) : 'Unknown';
            $priorityLabels = SupportRequest::PRIORITIES;

            return response()->json([
                'success' => 1,
                'supportRequest' => $support_request,
                'partnerName' => $support_request->partner->name ?? 'N/A',
                'requestStatusName' => $support_request->requestStatus->name ?? 'N/A',
                'priorityLabel' => $priorityLabels[$support_request->priority] ?? $support_request->priority,
                'getCreatedBy' => $getCreatedBy,
                'created_at' => $support_request->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        return view('admin.support-requests.show', compact('support_request'));
    }

    public function edit(SupportRequest $support_request)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => 1,
                'supportRequest' => $support_request,
            ]);
        }
        $partners = Partner::all();
        $requestStatuses = RequestStatus::all();
        $priorities = SupportRequest::PRIORITIES;
        $initiatives = Initiative::whereHas('implementationStatus', function ($q) {
            $q->whereIn('name', ['Implementation', 'Shelf']);
        })->get();
        return view('admin.support-requests.edit', compact('support_request', 'partners', 'requestStatuses', 'priorities', 'initiatives'));
    }

    public function update(UpdateSupportRequestRequest $request, SupportRequest $support_request)
    {
        $support_request->update($request->validated());
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.support-requests.index')->with('success_update', 'Support Request updated successfully!');
    }

    public function destroy(SupportRequest $support_request)
    {
        $support_request->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.support-requests.index')->with('success_delete', 'Support Request deleted successfully!');
    }
}
