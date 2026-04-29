<?php

namespace App\Http\Controllers;

use App\DataTables\ZonesDataTable;
use App\Models\Zone;
use App\Http\Requests\StoreZoneRequest;
use App\Http\Requests\UpdateZoneRequest;
use App\Models\Region;
use App\Models\User;
use App\Traits\ModelAuthorizable;

class ZoneController extends Controller
{
    use ModelAuthorizable;
    /**
     * Display a listing of the resource.
     */
    public function index(ZonesDataTable $dataTable)
    {
        $regionLists = Region::all();
        return $dataTable->render('admin.zones.index', (['regionLists' => $regionLists]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $regionLists = Region::all();
        return view('admin.zones.new', ([
            'regionLists' => $regionLists,
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreZoneRequest $request)
    {
        $zone = Zone::create($request->validated());

        return redirect()->route('admin.zones.index')->with('success_create', ' Zone added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Zone $zone)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Zone $zone)
    {
      
        if (request()->ajax()) {
            $response = array();
            if (!empty($zone)) {
                $response['zone_id'] = $zone->id;
                $response['name'] = $zone->name;
                $response['region_id'] = $zone->region_id;
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
    public function update(UpdateZoneRequest $request, Zone $zone)
    {
        $zone->update($request->validated());
        return response()->json(array("success" => true), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Zone $zone)
    {
        if (!$zone->exists()) {
            return redirect()->route('admin.zones.index')->with('error', 'Unautorized!');
        }
        $zone->delete();
        return response()->json(array("success" => true), 200);
    }
}
