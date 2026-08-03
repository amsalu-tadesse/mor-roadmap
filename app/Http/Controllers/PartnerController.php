<?php

namespace App\Http\Controllers;

use App\DataTables\PartnersDataTable;
use App\Http\Requests\StorePartnerRequest;
use App\Http\Requests\UpdatePartnerRequest;
use App\Models\Partner;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\PartnerUpdateMail;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PartnersDataTable $dataTable)
    {
        return $dataTable->render('admin.partners.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.partners.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePartnerRequest $request)
    {
        Partner::create($request->validated());
        return redirect()->route('admin.partners.index')->with('success_create', 'Partner created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Partner $partner)
    {
        if (request()->ajax()) {
            $creator = \App\Models\User::find($partner->created_by);
            $getCreatedBy = $creator ? ($creator->first_name . ' ' . $creator->middle_name . ' ' . $creator->last_name) : 'Unknown';

            return response()->json([
                'success' => 1,
                'partner' => $partner,
                'getCreatedBy' => $getCreatedBy,
                'created_at' => $partner->created_at->format('Y-m-d H:i:s')
            ]);
        }
        return view('admin.partners.show', compact('partner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partner $partner)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => 1,
                'partner' => $partner
            ]);
        }
        return view('admin.partners.edit', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePartnerRequest $request, Partner $partner)
    {
        $partner->update($request->validated());
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.partners.index')->with('success_update', 'Partner updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partner $partner)
    {
        $partner->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.partners.index')->with('success_delete', 'Partner deleted successfully!');
    }

    public function sendUpdate(Partner $partner)
    {
        if (empty($partner->email)) {
            return response()->json([
                'success' => false,
                'message' => 'Partner ' . $partner->name . ' has no email defined. Please update it first.'
            ], 422);
        }

        $activities = $partner->activities()->with('initiative.objective.theme')->get();

        // Generate PDF
        $pdf = Pdf::loadView('admin.partners.pdf', compact('partner', 'activities'));
        $pdfData = $pdf->output();

        // Send Email
        Mail::to($partner->email)->send(new PartnerUpdateMail($partner, $pdfData));

        //     \Illuminate\Support\Facades\Mail::to($partner->email)
        // ->queue(new \App\Mail\PartnerUpdateMail($partner, $pdfData));


        // dispatch(new \App\Jobs\SendEmailJob([$partner->email], [], $pdfData));

        return response()->json([
            'success' => true,
            'message' => 'Updates successfully sent to ' . $partner->name . ' (' . $partner->email . ').'
        ]);
    }
}
