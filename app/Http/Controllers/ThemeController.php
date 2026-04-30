<?php

namespace App\Http\Controllers;

use App\DataTables\ThemesDataTable;
use App\Http\Requests\StoreThemeRequest;
use App\Http\Requests\UpdateThemeRequest;
use App\Models\Theme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ThemesDataTable $dataTable)
    {
        return $dataTable->render('admin.themes.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.themes.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreThemeRequest $request)
    {
        Theme::create($request->validated());
        return redirect()->route('admin.themes.index')->with('success_create', 'Theme created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Theme $theme)
    {
        if (request()->ajax()) {
            $creator = \App\Models\User::find($theme->created_by);
            $getCreatedBy = $creator ? ($creator->first_name . ' ' . $creator->middle_name . ' ' . $creator->last_name) : 'Unknown';

            return response()->json([
                'success' => 1,
                'theme' => $theme,
                'getCreatedBy' => $getCreatedBy,
                'created_at' => $theme->created_at->format('Y-m-d H:i:s')
            ]);
        }
        return view('admin.themes.show', compact('theme'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Theme $theme)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => 1,
                'theme' => $theme
            ]);
        }
        return view('admin.themes.edit', compact('theme'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateThemeRequest $request, Theme $theme)
    {
        $theme->update($request->validated());
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.themes.index')->with('success_update', 'Theme updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Theme $theme)
    {
        $theme->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.themes.index')->with('success_delete', 'Theme deleted successfully!');
    }
}
