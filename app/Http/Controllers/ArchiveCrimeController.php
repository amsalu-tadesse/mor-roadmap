<?php

namespace App\Http\Controllers;

use App\DataTables\ArchiveCrimeDataTable;
use Illuminate\Http\Request;
use App\DataTables\CrimeDataTable;
use App\Http\Requests\UpdateCrimeRequest;
use App\Models\CaseStatus;
use App\Models\Crime;
use App\Models\CrimeCategory;
use App\Models\CrimeType;
use App\Models\DetectionMethod;
use App\Models\Habitat;
use App\Models\Law;
use App\Models\SeizuringBody;
use App\Models\Suspect;
use App\Models\VerdictType;

class ArchiveCrimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ArchiveCrimeDataTable $dataTable)
    {
        $crime_categories = CrimeCategory::all();
        $crime_types = CrimeType::all();
        $habitats = Habitat::all();
        $laws = Law::all();
        $seizuring_bodies = SeizuringBody::all();
        $detection_methods = DetectionMethod::all();
        $case_statuses = CaseStatus::all();
        $verdict_types = VerdictType::all();
        $suspects = Suspect::all();

        return $dataTable->render('admin.crimes.archive-crimes', compact('crime_categories', 'crime_types', 'habitats', 'laws', 'seizuring_bodies', 'detection_methods', 'case_statuses', 'verdict_types', 'suspects'));
    }
    public function update($archive_crime)
    {
        $archive_crime = Crime::withTrashed()->findOrFail($archive_crime);
        if ($archive_crime) {
            switch (request()->input('action')) {
                case 'permanet_delete':
                    $archive_crime->forceDelete();
                    break;
                case 'restore':
                    $archive_crime->restore();
                    break;
            }
        }

        return response()->json(array("success" => true), 200);
    }

    // public function destroy($archive_crime)
    // {
    //     // dd(request()->input('action'));
    //     $archive_crime = Crime::withTrashed()->findOrFail($archive_crime);
    //     if ($archive_crime) {
    //         switch (request()->input('action')) {
    //             case 'permanet_delete':
    //                 $archive_crime->forceDelete();
    //                 break;
    //             case 'restore':
    //                 $archive_crime->restore();
    //                 break;
    //         }
    //     }
        
    //     return response()->json(array("success" => true), 200);
    // }
}
