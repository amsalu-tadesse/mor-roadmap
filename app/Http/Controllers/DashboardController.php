<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\Models\Activity;
use App\Models\Directorate;
use App\Models\Partner;
use App\Models\Initiative;
use App\Models\SiteAdmin;
use App\Services\StatusService;
use Carbon\Carbon;
use Google\Service\IDS\Status;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $directoratesCount = Directorate::count();
        $initiativesCount = Initiative::count();
        $partnersCount = Partner::count();

        $draftCount = Initiative::where('implementation_status_id', \App\Constants\Constants::IMPLEMENTATION_STATUS_DRAFTING)->count();
        $shelfingCount = Initiative::where('implementation_status_id', \App\Constants\Constants::IMPLEMENTATION_STATUS_SHELFING)->count();
        $implementationCount = Initiative::where('implementation_status_id', \App\Constants\Constants::IMPLEMENTATION_STATUS_IMPLEMENTATION)->count();

        $siteAdmin = SiteAdmin::first();

        return view('admin.index', compact(
            'directoratesCount',
            'initiativesCount',
            'partnersCount',
            'draftCount',
            'shelfingCount',
            'implementationCount',
            'siteAdmin'
        ));
    }

    public function analytics()
    {
        // Fetch directorates sorted by most initiatives down to least
        $directorates = Directorate::withCount('initiatives')
            ->orderBy('initiatives_count', 'asc') // 'asc' positions the highest bars at the top of an ECharts h-bar
            ->get();

        // Isolate labels and count values into separate primitive arrays
        $labels = $directorates->pluck('name')->toArray();
        $counts = $directorates->pluck('initiatives_count')->toArray();


        // Fetch partners with count and arrange them so highest count bars show on top of ECharts h-bar
        // $partners = Partner::withCount('activities')
        //     ->orderBy('activities_count', 'asc')
        //     ->get();

        // Separate records into labels string array and values integer array
        // $partnerLabels = $partners->pluck('name')->toArray();
        // $activityCounts = $partners->pluck('activities_count')->toArray();


        // Fetch partners with count, sorting highest to lowest
        $partners = Partner::withCount('activities')
            ->has('activities')
            ->orderBy('activities_count', 'desc')
            ->get();

        $partnerLabels = $partners->pluck('name')->toArray();
        $activityCounts = $partners->pluck('activities_count')->toArray();

        $percentageCount = [];
        $percentageLabels = [];

        $allActivities = Activity::all();

        foreach ($allActivities as $activity) {

            $colorRange = percentageCalculator($activity->start_date, $activity->end_date, $activity->completion);

            // $color = $colorRange[0];
            $percentageLabels[]  = $colorRange[1];
            $index = array_search($colorRange[1], $percentageLabels);
            if (isset($percentageCount[$index])) {
                $percentageCount[$index]++;
            } else {
                $percentageCount[$index] = 1;
            }
        }


        // After your loop ends
        $percentageLabels = array_unique($percentageLabels);

        // $percentageLabels = array_values($percentageLabels); // Reindex the array to ensure sequential keys
        // $percentageCount = array_values($percentageCount); // Reindex the array to ensure sequential


        $orderedPercentageLabels = [];
        $orderedPercentageCount = [];
        $orderedColors = [];

        $statusRanges = StatusService::getChartRanges();

        foreach ($statusRanges as $label => $value) {
            $orderedPercentageLabels[] = $label;
            $index = array_search($label, $percentageLabels);
            if ($index !== false) {
                $orderedPercentageCount[] = $percentageCount[$index];
            } else {
                //$orderedPercentageCount[] = 0; // If not found, set count to 0
            }
            $orderedColors[] = StatusService::getStatus($value)[0];

        }


        $statuses = \DB::table('activity_statuses')
            ->leftJoin('activities', 'activity_statuses.id', '=', 'activities.activity_status_id')
            ->select('activity_statuses.name', \DB::raw('COUNT(activities.id) as total_activities'))
            ->groupBy('activity_statuses.id', 'activity_statuses.name')
            ->get();

        // Map database results into the required ECharts key-value structure
        $pieData = $statuses->map(function ($status) {
            return [
                'value' => $status->total_activities,
                'name'  => $status->name,
            ];
        })->toArray();




        // Aggregate lookup counting initiatives per strategic theme
        $themeData = \DB::table('themes')
            ->leftJoin('initiatives', 'themes.id', '=', 'initiatives.theme_id')
            ->select('themes.name', \DB::raw('COUNT(initiatives.id) as total_initiatives'))
            ->groupBy('themes.id', 'themes.name')
            ->orderBy('total_initiatives', 'asc') // 'asc' positions the highest bars at the top of an ECharts h-bar
            ->get();

        // Isolate labels and count values into clean separate arrays
        $themeLabels = $themeData->pluck('name')->toArray();
        $initiativeCounts = $themeData->pluck('total_initiatives')->toArray();


        $partners = Partner::all();
        $partnerStackedLabels = $partners->pluck('name')->toArray();


        $partnersLength = count($partnerStackedLabels);
        $completionStatusLength = count($orderedPercentageLabels);
        // dd($partnersLength, $completionStatusLength);

        // Create 7 rows, where each row contains an array of 14 zeros
        $rawData = array_fill(0, $completionStatusLength, array_fill(0, $partnersLength, 0));

        // dd($rawData);

        foreach ($partners as $key => $partner) {
            $activities = $partner->activities;

            foreach ($activities as $activity) {

                $colorRange = percentageCalculator($activity->start_date, $activity->end_date, $activity->completion);
                // Find the index of the color range in the orderedPercentageLabels array
                $statusIndex = array_search($colorRange[1], $orderedPercentageLabels);

                if ($statusIndex !== false) {
                    // Increment the count for this partner and status
                    $rawData[$statusIndex][$key]++;
                }
            }
        }


        $colorMap = StatusService::getColorMap();

        $chartColors = [];
        foreach ($orderedPercentageLabels as $label) {
            $chartColors[] = $colorMap[$label] ?? "#6c757d"; // Fallback gray
        }



        // dd($orderedPercentageLabels, $partnerStackedLabels);

        /* $rawData = [
  [100, 302, 301, 334, 390, 330, 320,100, 302, 301, 334, 390, 330, 320 ], #completed
  [320, 132, 101, 134, 90, 230, 210,320, 132, 101, 134, 90, 230, 210 ], #"above 10%"
  [220, 182, 191, 234, 290, 330, 310,220, 182, 191, 234, 290, 330, 310 ],
  [150, 212, 201, 154, 190, 330, 410,150, 212, 201, 154, 190, 330, 410 ],
  [820, 832, 901, 934, 1290, 1330, 1320,820, 832, 901, 934, 1290, 1330, 1320 ],
  [820, 832, 901, 934, 1290, 1330, 1320,820, 832, 901, 934, 1290, 1330, 1320 ],
  [820, 832, 901, 934, 1290, 1330, 1320,820, 832, 901, 934, 1290, 1330, 1320 ]
];*/


        return view('admin.visualize.index', compact('labels', 'counts', 'partnerLabels', 'activityCounts', 'pieData', 'themeLabels', 'initiativeCounts', 'orderedPercentageLabels', 'orderedPercentageCount', 'orderedColors', 'partnerStackedLabels', 'rawData', 'chartColors'));

    }





}
