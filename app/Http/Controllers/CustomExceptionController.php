<?php

namespace App\Http\Controllers;

use App\DataTables\CustomExceptionDataTable;
use App\DataTables\CustomExceptionsDataTable;
use App\Models\CustomException;
use App\Http\Requests\StoreCustomExceptionRequest;
use App\Http\Requests\UpdateCustomExceptionRequest;
use App\Traits\ModelAuthorizable;
use Illuminate\Support\Facades\DB;

class CustomExceptionController extends Controller
{
    use ModelAuthorizable;

    /**
     * Display a listing of the resource.
     */
    public function index(CustomExceptionsDataTable $dataTable)
    {
        return $dataTable->render('admin.customExceptions.index');
    }



    /**
     * Display the specified resource.
     */
    public function show(CustomException $customException)
    {
        if (request()->ajax()) {
            $response = array();
            if (!empty($customException)) {
                $response['customException_id'] = $customException->id;
                $response['description'] = $customException->description;
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
    public function update(UpdateCustomExceptionRequest $request, CustomException $customException)
    {
        $customException->status = 1;
        $customException->save();
        return response()->json(array("success" => true), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomException $customException)
    {
        if (!$customException->exists()) {
            return redirect()->route('admin.customExceptions.index')->with('error', 'Unautorized!');
        }
        $customException->delete();
        return response()->json(array("success" => true), 200);
    }

    public function deleteAllData()
    {
         CustomException::truncate();
        return redirect()->back()->with('success', 'All data has been deleted.');
    }
}
