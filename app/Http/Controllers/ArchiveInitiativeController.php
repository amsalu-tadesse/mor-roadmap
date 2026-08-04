<?php

namespace App\Http\Controllers;

use App\DataTables\CompletedInitiativesDataTable;
use App\DataTables\SuspendedInitiativesDataTable;
use App\DataTables\InitiativeActivitiesDataTable;
use App\Models\Activity;
use App\Models\ActivityStatus;
use App\Models\Directorate;
use App\Models\ImplementationStatus;
use App\Models\Initiative;
use App\Models\Objective;
use App\Models\Partner;
use App\Models\Theme;

class ArchiveInitiativeController extends Controller
{
    public function completed(CompletedInitiativesDataTable $dataTable)
    {
        $objectives = Objective::all();
        $themes = Theme::all();
        $directorates = Directorate::all();
        $implementationStatuses = ImplementationStatus::all();
        $partners = Partner::all();
        $priorities = Activity::PRIORITIES;
        $initiatives = Initiative::all();
        $activityStatuses = ActivityStatus::all();

        $initiativeActivitiesEditTable = app(InitiativeActivitiesDataTable::class)
            ->setTableId('initiative-activities-edit-table')
            ->setShowActions(true);

        $initiativeActivitiesShowTable = app(InitiativeActivitiesDataTable::class)
            ->setTableId('initiative-activities-show-table')
            ->setShowActions(false);

        return $dataTable->render('admin.archives.completed', compact(
            'objectives', 'themes', 'directorates', 'implementationStatuses',
            'partners', 'priorities', 'initiatives', 'activityStatuses',
            'initiativeActivitiesEditTable', 'initiativeActivitiesShowTable'
        ));
    }

    public function suspended(SuspendedInitiativesDataTable $dataTable)
    {
        $objectives = Objective::all();
        $themes = Theme::all();
        $directorates = Directorate::all();
        $implementationStatuses = ImplementationStatus::all();
        $partners = Partner::all();
        $priorities = Activity::PRIORITIES;
        $initiatives = Initiative::all();
        $activityStatuses = ActivityStatus::all();

        $initiativeActivitiesEditTable = app(InitiativeActivitiesDataTable::class)
            ->setTableId('initiative-activities-edit-table')
            ->setShowActions(true);

        $initiativeActivitiesShowTable = app(InitiativeActivitiesDataTable::class)
            ->setTableId('initiative-activities-show-table')
            ->setShowActions(false);

        return $dataTable->render('admin.archives.suspended', compact(
            'objectives', 'themes', 'directorates', 'implementationStatuses',
            'partners', 'priorities', 'initiatives', 'activityStatuses',
            'initiativeActivitiesEditTable', 'initiativeActivitiesShowTable'
        ));
    }
}
