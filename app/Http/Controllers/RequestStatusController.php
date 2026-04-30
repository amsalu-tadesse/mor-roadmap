<?php

namespace App\Http\Controllers;

use App\DataTables\RequestStatusesDataTable;
use App\Http\Requests\StoreRequestStatusRequest;
use App\Http\Requests\UpdateRequestStatusRequest;
use App\Models\RequestStatus;

class RequestStatusController extends Controller
{
    public function index(RequestStatusesDataTable $dataTable)
    {
        return $dataTable->render('admin.request-statuses.index');
    }

    public function create()
    {
        return view('admin.request-statuses.new');
    }

    public function store(StoreRequestStatusRequest $request)
    {
        RequestStatus::create($request->validated());
        return redirect()->route('admin.request-statuses.index')->with('success_create', 'Request Status created successfully!');
    }

    public function show(RequestStatus $requestStatus)
    {
        if (request()->ajax()) {
            $creator = \App\Models\User::find($requestStatus->created_by);
            $getCreatedBy = $creator ? ($creator->first_name . ' ' . $creator->middle_name . ' ' . $creator->last_name) : 'Unknown';
            return response()->json([
                'success' => 1,
                'requestStatus' => $requestStatus,
                'getCreatedBy' => $getCreatedBy,
                'created_at' => $requestStatus->created_at->format('Y-m-d H:i:s')
            ]);
        }
        return view('admin.request-statuses.show', compact('requestStatus'));
    }

    public function edit(RequestStatus $requestStatus)
    {
        if (request()->ajax()) {
            return response()->json(['success' => 1, 'requestStatus' => $requestStatus]);
        }
        return view('admin.request-statuses.edit', compact('requestStatus'));
    }

    public function update(UpdateRequestStatusRequest $request, RequestStatus $requestStatus)
    {
        $requestStatus->update($request->validated());
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.request-statuses.index')->with('success_update', 'Request Status updated successfully!');
    }

    public function destroy(RequestStatus $requestStatus)
    {
        $requestStatus->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.request-statuses.index')->with('success_delete', 'Request Status deleted successfully!');
    }
}
