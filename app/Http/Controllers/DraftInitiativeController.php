<?php

namespace App\Http\Controllers;

use App\DataTables\DraftInitiativesDataTable;
use App\Http\Requests\StoreDraftInitiativeRequest;
use App\Http\Requests\UpdateDraftInitiativeRequest;
use App\Models\Directorate;
use App\Models\ImplementationStatus;
use App\Models\Initiative;
use App\Models\Objective;
use App\Models\Theme;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class DraftInitiativeController extends Controller
{
    public function index(DraftInitiativesDataTable $dataTable)
    {
        $objectives = Objective::all();
        $themes = Theme::all();
        $directorates = Directorate::all();
        $implementationStatuses = ImplementationStatus::all();
        return $dataTable->render('admin.draft-initiatives.index', compact('objectives', 'themes', 'directorates', 'implementationStatuses'));
    }

    public function create()
    {
        $objectives = Objective::all();
        $themes = Theme::all();
        $directorates = Directorate::all();
        $implementationStatuses = ImplementationStatus::all();
        return view('admin.draft-initiatives.new', compact('objectives', 'themes', 'directorates', 'implementationStatuses'));
    }

    public function store(StoreDraftInitiativeRequest $request)
    {
        $data = $request->validated();

        // Default to "Drafting stage" if no implementation status is selected
        if (empty($data['implementation_status_id'])) {
            $draftingStatus = ImplementationStatus::where('name', 'Drafting stage')->first();
            if ($draftingStatus) {
                $data['implementation_status_id'] = $draftingStatus->id;
            }
        }

        $initiative = Initiative::create(Arr::except($data, ['directorates']));
        $initiative->directorates()->sync($data['directorates']);
        return redirect()->route('admin.draft-initiatives.index')->with('success_create', 'Draft Initiative created successfully!');
    }

    public function show(Initiative $draftInitiative)
    {
        if (request()->ajax()) {
            $draftInitiative->load(['objective', 'directorates', 'implementationStatus', 'theme']);
            $creator = \App\Models\User::find($draftInitiative->created_by);
            $getCreatedBy = $creator ? ($creator->first_name . ' ' . $creator->middle_name . ' ' . $creator->last_name) : 'Unknown';

            return response()->json([
                'success' => 1,
                'initiative' => $draftInitiative,
                'objectiveName' => $draftInitiative->objective->name ?? 'N/A',
                'themeName' => $draftInitiative->theme->name ?? 'N/A',
                'directorateName' => $draftInitiative->directorates->pluck('name')->join(', ') ?: 'N/A',
                'implementationStatusName' => $draftInitiative->implementationStatus->name ?? 'N/A',
                'getCreatedBy' => $getCreatedBy,
                'created_at' => $draftInitiative->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        return view('admin.draft-initiatives.show', compact('draftInitiative'));
    }

    public function edit(Initiative $draftInitiative)
    {
        if (request()->ajax()) {
            $draftInitiative->load('directorates');
            return response()->json([
                'success' => 1,
                'initiative' => $draftInitiative,
                'directorates' => $draftInitiative->directorates->pluck('id')->toArray(),
            ]);
        }
        $objectives = Objective::all();
        $themes = Theme::all();
        $directorates = Directorate::all();
        $implementationStatuses = ImplementationStatus::all();

        return view('admin.draft-initiatives.edit', compact('draftInitiative', 'objectives', 'themes', 'directorates', 'implementationStatuses'));
    }

    public function update(UpdateDraftInitiativeRequest $request, Initiative $draftInitiative)
    {
        $data = $request->validated();
        $draftInitiative->update(Arr::except($data, ['directorates']));
        $draftInitiative->directorates()->sync($data['directorates']);
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.draft-initiatives.index')->with('success_update', 'Draft Initiative updated successfully!');
    }

    public function destroy(Initiative $draftInitiative)
    {
        $draftInitiative->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.draft-initiatives.index')->with('success_delete', 'Draft Initiative deleted successfully!');
    }

    public function search(Request $request)
    {
        $name = $request->input('name');
        $themeId = $request->input('theme_id');
        $directorateIds = $request->input('directorates');

        if (empty($name)) {
            return response()->json([]);
        }

        if (is_string($directorateIds)) {
            $directorateIds = explode(',', $directorateIds);
        }
        $directorateIds = array_filter((array) $directorateIds);

        $query = Initiative::with(['implementationStatus', 'objective']);

        $query->where('name', 'like', '%' . $name . '%');

        if (!empty($themeId)) {
            $query->where('theme_id', $themeId);
        }

        if (!empty($directorateIds)) {
            $query->whereHas('directorates', function ($q) use ($directorateIds) {
                $q->whereIn('directorates.id', $directorateIds);
            });
        }

        $initiatives = $query->get();

        return response()->json($initiatives);
    }
}
