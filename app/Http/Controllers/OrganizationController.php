<?php
namespace App\Http\Controllers;
use App\DataTables\OrganizationDataTable;
use App\Models\Organization;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\User;
use App\Traits\ModelAuthorizable;
use Illuminate\Support\Facades\DB;


class OrganizationController extends Controller
{
    use ModelAuthorizable;
    /**
     * Display a listing of the resource.
     */
    public function index(OrganizationDataTable $dataTable)
    {
            return $dataTable->render('admin.organizations.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    { return view('admin.organizations.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrganizationRequest $request)
    {

        $organazation = Organization::create($request->validated());

        return redirect()->route('admin.organizations.index')->with('success_create', ' organazation added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        if (request()->ajax()) {
            $response = array();
            $response['success'] = 1;
            $response['organazation'] = $organization;
            return response()->json($response);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization)
    {
        if (request()->ajax()) {
            $response = array();
            $response['success'] = 1;
            $response['organazation'] = $organization;
            return response()->json($response);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrganizationRequest $request, Organization $organization)
    {

        $organization->update($request->validated());

        return response()->json(array('success' => true), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        if (!$organization->exists()) {
            return redirect()->route('admin.organizations.index')->with('error', 'Unautorized!');
        }
        $organization->delete();
        return response()->json(array('success' => true), 200);
    }
}

