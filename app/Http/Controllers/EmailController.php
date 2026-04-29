<?php

namespace App\Http\Controllers;

use App\DataTables\EmailDataTable;
use App\Models\Email;
use App\Http\Requests\StoreEmailRequest;
use App\Http\Requests\UpdateEmailRequest;
use App\Traits\ModelAuthorizable;

class EmailController extends Controller
{
    use ModelAuthorizable;

    /**
     * Display a listing of the resource.
     */
    public function index(EmailDataTable $dataTable)
    {
        return $dataTable->render('admin.emails.index');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.emails.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmailRequest $request)
    {
        $email = Email::create($request->validated());
        return redirect()->route('admin.emails.index')->with('success_create', 'Email added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Email $email)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Email $email)
    {
        if (request()->ajax())
        {
            $response = array();
            if (!empty($email)) {
                $response['email_id'] = $email->id;
                $response['subject'] = $email->subject;
                $response['body'] = $email->body;
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
    public function update(UpdateEmailRequest $request, Email $email)
    {
        $email->update($request->validated());
        
        if ($request->input('enable') === 'true') {
            $email->status = 1;
        } elseif ($request->input('enable') === 'false') {
            $email->status = 0;
        }
        $email->save();
        
        
        return response()->json(array("success" => true), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Email $email)
    {
        if (!$email->exists()) {
            return redirect()->route('admin.emails.index')->with('error', 'Unautorized!');
        }
        $email->delete();
        return response()->json(array("success" => true), 200);
    }
}
