<?php

namespace App\Http\Controllers;

use App\DataTables\SiteAdminsDataTable;
use App\Models\SiteAdmin;
use App\Http\Requests\StoreSiteAdminRequest;
use App\Http\Requests\UpdateSiteAdminRequest;
use App\Traits\ModelAuthorizable;

class SiteAdminController extends Controller
{
    use ModelAuthorizable;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siteAdmin = SiteAdmin::first();
        return view('admin.siteAdmins.index', ['siteAdmin' => $siteAdmin]);    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.species.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSiteAdminRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SiteAdmin $siteAdmin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $siteAdmin = SiteAdmin::first();
        return view('admin.siteAdmins.index', ['siteAdmin' => $siteAdmin]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSiteAdminRequest $request)
    {
        $siteAdmin = SiteAdmin::first();
        $siteAdmin->update($request->validated());
        $siteAdmin->save();
        

        return redirect('/admin/site-admin')->with('success', 'Address Updated successfully');

        // return redirect()->route('siteAdmins.index');
        // return view('admin.siteAdmins.index', ['siteAdmin' => $siteAdmin]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SiteAdmin $siteAdmin)
    {
       //
    }
}
