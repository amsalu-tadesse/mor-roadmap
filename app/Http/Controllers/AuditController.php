<?php

namespace App\Http\Controllers;

use App\Models\Spatie\CustomActivity;
use App\Models\User;
use Illuminate\Http\Request;

class AuditController extends Controller
{

    public function reset()
    {
        return redirect()->route('admin.audit.index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filter_actor = request()->input('actor');
        $filter_subject = request()->input('subject');
        $filter_action = request()->input('action');
        $filter_dateRange = request()->input('dateRange');
        $dateRangeArray = explode(' - ', $filter_dateRange);

        // dd( $dateRangeArray);

        $datas = [];
        $audits = [];
        $subjects = [];
        $actions = [];
        $actors = [];


        $allData = CustomActivity::all();

        $datas = CustomActivity::latest();



            if ($filter_actor) {
                $datas->where('causer_id', $filter_actor);
            }

            if ($filter_subject) {
                $datas->where('subject_type', 'App\Models\\' . $filter_subject);
            }
            if ($filter_action) {
                $datas->where('event',  $filter_action);
            }
            if ($filter_dateRange) {
                $startDate= $dateRangeArray[0];
                $endDate= $dateRangeArray[1];
                $datas->whereBetween('created_at', [$startDate, $endDate]);
            }

            $datas = $datas->get();



        foreach ($allData as $dat) {
            $clss_name = explode('\\', $dat->subject_type)[2];
            $subjects[$clss_name] = $clss_name;
            $actions[$dat->event] = $dat->event;
            if ($dat?->causer?->id) {
                $actors[$dat?->causer?->id] = $dat->causer;
            }
        }

        foreach ($datas as $activity0) {

            $subject = $activity0->subject;
            $property = $activity0->properties;

                $audits[] = array(
                    'subject_type' => class_basename($subject),
                    'subject_name' => $this->getSubjecName($property),
                    'actor_name' => $activity0?->causer?->first_name,
                    'activity' => $activity0->description,
                    'created_at' => $activity0->created_at,
                    'properties' => $activity0->properties,

                );
        }


// dd($audits);
        return view('admin.audit.audit', [
            'audits' => $audits,
            'actors' => $actors,
            'subjects' => $subjects,
            'actions' => $actions,
            'filter_actor' => $filter_actor,
            'filter_subject' => $filter_subject,
            'filter_action' => $filter_action,
            'filter_dateRange' => $filter_dateRange,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //destroy
    }

    public function getSubjecName($property)
    {
        //name
        if (isset($property['attributes']['name'])) {
            $message = $property['attributes']['name'];
        } elseif (isset($property['old']['name'])) {
            $message = $property['old']['name'];
        }

        // subject
        elseif (isset($property['attributes']['subject'])) {
            $message = $property['attributes']['subject'];
        } elseif (isset($property['old']['subject'])) {
            $message = $property['old']['subject'];
        } 
        // title
        elseif (isset($property['attributes']['title'])) {
            $message = $property['attributes']['title'];
        } elseif (isset($property['old']['title'])) {
            $message = $property['old']['title'];
        } 
        // label
        elseif (isset($property['attributes']['label'])) {
            $message = $property['attributes']['label'];
        } elseif (isset($property['old']['label'])) {
            $message = $property['old']['label'];
        } 
        // question
        elseif (isset($property['attributes']['question'])) {
            $message = $property['attributes']['question'];
        } elseif (isset($property['old']['question'])) {
            $message = $property['old']['question'];
        } 
        // ip_address
        elseif (isset($property['attributes']['ip_address'])) {
            $message = $property['attributes']['ip_address'];
        } elseif (isset($property['old']['ip_address'])) {
            $message = $property['old']['ip_address'];
        } 
        // file_number
        elseif (isset($property['attributes']['file_number'])) {
            $message = $property['attributes']['file_number'];
        } elseif (isset($property['old']['file_number'])) {
            $message = $property['old']['file_number'];
        } 
        // first_name
        elseif (isset($property['attributes']['first_name'])) {
            $message = $property['attributes']['first_name']. " " .$property['attributes']['middle_name'];
        } elseif (isset($property['old']['first_name'])) {
            $message = $property['old']['first_name']. " " .$property['attributes']['middle_name'];
        } 
        
        else {
            $message = 'Unknown';
        }
        
        return $message;
    }
}
