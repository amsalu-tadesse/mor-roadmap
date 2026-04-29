<?php

namespace App\Http\Controllers;

use App\DataTables\SettingsDataTable;
use App\Models\Setting;
use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Traits\ModelAuthorizable;

class SettingController extends Controller
{
    use ModelAuthorizable;

    /**
     * Display a listing of the resource.
     */
    public function index(SettingsDataTable $dataTable)
    {

        return $dataTable->render('admin.settings.index');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        if (request()->ajax()) {
            $response = array();
            if (!empty($setting)) {
                $response['setting_id'] = $setting->id;
                $response['code'] = $setting->code;
                $response['name'] = $setting->name;
                $response['value1'] = $setting->value1;
                $response['value2'] = $setting->value2;
                $response['type'] = $setting->type;
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
    public function update(UpdateSettingRequest $request, Setting $setting)
    {
        // dd(request()->input('checkboxvalue1'));
        // $setting->update($request->validated());
       if (request()->input('checkboxvalue1') == 'on'  && $setting->type == 0) {
            $setting->value1 = 1;
        } else if (request()->input('checkboxvalue1') == null && $setting->type == 0) {
            $setting->value1 = 0;
        }
        else
        {
            $setting->update($request->validated());
        }
        $setting->save();
        return response()->json(array("success" => true), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
