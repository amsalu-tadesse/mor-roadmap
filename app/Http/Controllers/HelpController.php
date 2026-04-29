<?php

namespace App\Http\Controllers;

use App\DataTables\HelpsDataTable;
use App\Models\Help;
use App\Http\Requests\StoreHelpRequest;
use App\Http\Requests\UpdateHelpRequest;
use App\Models\User;
use App\Traits\ModelAuthorizable;

class HelpController extends Controller
{
    use ModelAuthorizable;

    /**
     * Display a listing of the resource.
     */
    public function index(HelpsDataTable $dataTable)
    {
        return $dataTable->render('admin.helps.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $help = Help::where('route', request()->input('route'))->first();

        return view(
            'admin.helps.new',
            [
                'help' => $help
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHelpRequest $request)
    {
        // dd($request->all());
        $help = Help::where('route', request()->input('route'))->first();
        if (!$help) {
            $help = Help::create($request->validated());
        } else {
            $help->update($request->validated());
        }

        $route = request()->input('route');
        if ($route) {
            return redirect()->route($route)->with('success_create', 'help added!');
        }
        return redirect()->route('admin.helps.index')->with('success_create', 'help added!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Help $help)
    {
        if (request()->ajax()) {
            $response = array();
            $creator = User::find($help->created_by);
            $fName = $creator?->first_name;
            $mName = $creator?->middle_name;
            $lName = $creator?->last_name;
            $getCreatedBy = $fName . ' ' . $mName . ' ' . $lName;
            $response['help'] = $help;
            $response['getCreatedBy'] = $getCreatedBy;
            $response['success'] = 1;

            return response()->json($response);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Help $help)
    {
        // dd($help);
        if (request()->ajax()) {
            $response = array();
            if (!empty($help)) {
                $response['help_id'] = $help->id;
                $response['title'] = $help->title;
                $response['body'] = $help->body;
                $response['url'] = $help->url;
                $response['route'] = $help->route;
                $response['active'] = $help->active;

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
    public function update(UpdateHelpRequest $request, Help $help)
    {
        // $active = $request->input('active') ? 1 : 0;
        $help->update($request->validated());
        // $help->active = $active;
        $help->save();

        return response()->json(array("success" => true), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Help $help)
    {
        if (!$help->exists()) {
            return redirect()->route('admin.helps.index')->with('error', 'Unautorized!');
        }
        $help->delete();
        return response()->json(array("success" => true), 200);
    }
}
