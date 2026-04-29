<?php

namespace App\Http\Controllers;

use App\DataTables\RegionsDataTable;
use App\Models\Region;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use App\Models\User;
use App\Traits\ModelAuthorizable;

class RegionController extends Controller
{
    use ModelAuthorizable;
    /**
     * Display a listing of the resource.
     */
    public function index(RegionsDataTable $dataTable)
    {
        return $dataTable->render('admin.regions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.regions.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegionRequest $request)
    {
        $region = Region::create($request->validated());
        if (request()->input('is_cityadministration') == 'on') {
            $region->is_cityadministration = 1;
            $region->save();
        } else if (request()->input('is_cityadministration') == null) {
            $region->is_cityadministration = 0;
            $region->save();
        }

        return redirect()->route('admin.regions.index')->with('success_create', 'Regions added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region)
    {
        if (request()->ajax()) {
            $response = array();
            if (!empty($region)) {
                $response['region_id'] = $region->id;
                $response['name'] = $region->name;
                $response['latitude']=$region->latitude;
                $response['longitude']=$region->longitude;
                $response['ordering'] = $region->ordering;
                $response['color'] = $region->color;
                $response['is_cityadministration'] = $region->is_cityadministration;
                $response['success'] = 1;
            } else {
                $response['success'] = 0;
            }

            return response()->json($response);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionRequest $request, Region $region)
    {

        $region->update($request->validated());
        if (request()->input('is_cityadministration') == 'on') {
            $region->is_cityadministration = 1;
            $region->save();
        } else if (request()->input('is_cityadministration') == null) {
            $region->is_cityadministration = 0;
            $region->save();
        }

        return response()->json(array("success" => true), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region)
    {
        if (!$region->exists()) {
            return redirect()->route('admin.regions.index')->with('error', 'Unautorized!');
        }
        $region->delete();
        return response()->json(array("success" => true), 200);
    }
}
