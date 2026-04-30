<?php

namespace App\Http\Controllers;

use App\DataTables\ObjectivesDataTable;
use App\Http\Requests\StoreObjectiveRequest;
use App\Http\Requests\UpdateObjectiveRequest;
use App\Models\Objective;
use App\Models\Theme;
use Illuminate\Http\Request;

class ObjectiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ObjectivesDataTable $dataTable)
    {
        $themes = Theme::all();
        return $dataTable->render('admin.objectives.index', compact('themes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $themes = Theme::all();
        return view('admin.objectives.new', compact('themes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreObjectiveRequest $request)
    {
        Objective::create($request->validated());
        return redirect()->route('admin.objectives.index')->with('success_create', 'Objective created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Objective $objective)
    {
        if (request()->ajax()) {
            $creator = \App\Models\User::find($objective->created_by);
            $getCreatedBy = $creator ? ($creator->first_name . ' ' . $creator->middle_name . ' ' . $creator->last_name) : 'Unknown';
            $themeName = $objective->theme->name ?? 'N/A';

            return response()->json([
                'success' => 1,
                'objective' => $objective,
                'themeName' => $themeName,
                'getCreatedBy' => $getCreatedBy,
                'created_at' => $objective->created_at->format('Y-m-d H:i:s')
            ]);
        }
        return view('admin.objectives.show', compact('objective'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Objective $objective)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => 1,
                'objective' => $objective
            ]);
        }
        return view('admin.objectives.edit', compact('objective'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateObjectiveRequest $request, Objective $objective)
    {
        $objective->update($request->validated());
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.objectives.index')->with('success_update', 'Objective updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Objective $objective)
    {
        $objective->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.objectives.index')->with('success_delete', 'Objective deleted successfully!');
    }
}
