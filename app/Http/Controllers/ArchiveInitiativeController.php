<?php

namespace App\Http\Controllers;

use App\DataTables\CompletedInitiativesDataTable;
use App\DataTables\SuspendedInitiativesDataTable;
use App\DataTables\InitiativeActivitiesDataTable;
use App\Models\Directorate;
use App\Models\Objective;
use App\Models\Theme;

class ArchiveInitiativeController extends Controller
{
    public function completed(CompletedInitiativesDataTable $dataTable)
    {
        $objectives = Objective::all();
        $themes = Theme::all();
        $directorates = Directorate::all();

        $initiativeActivitiesShowTable = app(InitiativeActivitiesDataTable::class)
            ->setTableId('initiative-activities-show-table')
            ->setShowActions(false);

        return $dataTable->render('admin.archives.completed', compact(
            'objectives', 'themes', 'directorates', 'initiativeActivitiesShowTable'
        ));
    }

    public function suspended(SuspendedInitiativesDataTable $dataTable)
    {
        $objectives = Objective::all();
        $themes = Theme::all();
        $directorates = Directorate::all();

        $initiativeActivitiesShowTable = app(InitiativeActivitiesDataTable::class)
            ->setTableId('initiative-activities-show-table')
            ->setShowActions(false);

        return $dataTable->render('admin.archives.suspended', compact(
            'objectives', 'themes', 'directorates', 'initiativeActivitiesShowTable'
        ));
    }
}
