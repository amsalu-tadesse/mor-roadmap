<?php

namespace App\Http\Controllers;

use App\DataTables\FAQsDataTabale;
use App\DataTables\FAQsDataTbale;
use App\Models\FAQ;
use App\Http\Requests\StoreFAQRequest;
use App\Http\Requests\UpdateFAQRequest;
use App\Models\User;
use App\Traits\ModelAuthorizable;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class FAQController extends Controller
{

    use ModelAuthorizable;

    /**
     * Display a listing of the resource.
     */
    public function index(FAQsDataTabale $dataTable)
    {

        return $dataTable->render('admin.faqs.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {


        return view('admin.faqs.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFAQRequest $request)
    {

        FAQ::create($request->validated());
        return redirect()->route('admin.faqs.index')->with('success_create', 'FAQ added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(FAQ $fAQ)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FAQ $faq)
    {
        if (request()->ajax()) {
            $response = array();
            if (!empty($faq)) {
                $response['faq_id'] = $faq->id;
                $response['question'] = $faq->question;
                $response['answer'] = $faq->answer;
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
    public function update(UpdateFAQRequest $request, FAQ $faq)
    {
        $faq->update($request->validated());
        $faq->save();

        return response()->json(array("success" => true), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FAQ $faq)
    {
        $this->authorize('delete', $faq);

        if (!$faq->exists()) {
            return redirect()->route('admin.faqs.index')->with('error', 'Unautorized!');
        }
        $faq->delete();
        return response()->json(array("success" => true), 200);
    }
}
