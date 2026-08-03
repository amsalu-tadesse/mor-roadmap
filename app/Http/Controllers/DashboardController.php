<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\Models\Activity;
use App\Models\Directorate;
use App\Models\Partner;
use App\Models\Initiative;
use App\Models\SiteAdmin;
use Carbon\Carbon;

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

            $colorRange = $this->percentageCalculator($activity->start_date, $activity->end_date, $activity->completion);

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


        $orderedPercentageLabels[] = "completed";
        $index = array_search("completed", $percentageLabels);
        $orderedPercentageCount[] = $percentageCount[$index];
        $orderedColors[] = Constants::getStatusColor(101)[0];

        $orderedPercentageLabels[] = "above 10%";
        $index = array_search("above 10%", $percentageLabels);
        $orderedPercentageCount[] = $percentageCount[$index];
        $orderedColors[] = Constants::getStatusColor(10)[0];

        $orderedPercentageLabels[] = "within 0-10%";
        $index = array_search("within 0-10%", $percentageLabels);
        $orderedPercentageCount[] = $percentageCount[$index];
        $orderedColors[] = Constants::getStatusColor(0)[0];

        $orderedPercentageLabels[] = "within -5% to 0%";
        $index = array_search("within -5% to 0%", $percentageLabels);
        $orderedPercentageCount[] = $percentageCount[$index];
        $orderedColors[] = Constants::getStatusColor(-5)[0];

        $orderedPercentageLabels[] = "within -15% to -5%";
        $index = array_search("within -15% to -5%", $percentageLabels);
        $orderedPercentageCount[] = $percentageCount[$index];
        $orderedColors[] = Constants::getStatusColor(-15)[0];

        $orderedPercentageLabels[] = "within -30% to -15%";
        $index = array_search("within -30% to -15%", $percentageLabels);
        $orderedPercentageCount[] = $percentageCount[$index];
        $orderedColors[] = Constants::getStatusColor(-30)[0];

        $orderedPercentageLabels[] = "below -30%";
        $index = array_search("below -30%", $percentageLabels);
        $orderedPercentageCount[] = $percentageCount[$index];
        $orderedColors[] = Constants::getStatusColor(-100)[0];


        // dd($orderedPercentageCount, $orderedPercentageLabels);








        // dd($percentageCount, $percentageLabels, $sortedCounts, $sortedLabels);


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


        /*
         0 => 14
         1 => 1
         2 => 62
         3 => 14
         4 => 10
         5 => 1
         6 => 72
        ]
        array:7 [
         0 => "completed"
         1 => "above 10%"
         2 => "within 0-10%"
         3 => "within -5% to 0%"
         4 => "within -15% to -5%"
         5 => "within -30% to -15%"
         6 => "below -30%"
         */
        $partnersLength = count($partnerStackedLabels);
        $completionStatusLength = count($orderedPercentageLabels);
        // dd($partnersLength, $completionStatusLength);

        // Create 7 rows, where each row contains an array of 14 zeros
        $rawData = array_fill(0, $completionStatusLength, array_fill(0, $partnersLength, 0));

        // dd($rawData);

        foreach ($partners as $key => $partner) {
            $activities = $partner->activities;

            foreach ($activities as $activity) {

                $colorRange = $this->percentageCalculator($activity->start_date, $activity->end_date, $activity->completion);
                // Find the index of the color range in the orderedPercentageLabels array
                $statusIndex = array_search($colorRange[1], $orderedPercentageLabels);

                if ($statusIndex !== false) {
                    // Increment the count for this partner and status
                    $rawData[$statusIndex][$key]++;
                }
            }
        }



        $colorMap = [
    "below -30%"          => Constants::getStatusColor(-30)[0], // Red
    "within -30% to -15%" => Constants::getStatusColor(-30)[0],
    "within -15% to -5%"  => Constants::getStatusColor(-15)[0],
    "within -5% to 0%"  => Constants::getStatusColor(-5)[0],
    "within 0-10%"        => Constants::getStatusColor(0)[0],
    "above 10%"           => Constants::getStatusColor(10)[0],
    "completed"           => Constants::getStatusColor(101)[0], // Green
];

        // dd($orderedColors, $orderedPercentageLabels);

        $chartColors = [];
        foreach ($orderedPercentageLabels as $label) {
            $chartColors[] = $colorMap[$label] ?? "#6c757d"; // Fallback gray
        }



        // dd($chartColors, $orderedColors);

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


    public function percentageCalculator($start_date, $end_date, $completion)
    {

        $start = Carbon::parse($start_date);
        $end   = Carbon::parse($end_date);
        $today = Carbon::today();

        $totalDays = max($start->diffInDays($end), 1);

        // Clamp today to the project period
        if ($today->lt($start)) {
            $elapsedDays = 0;
        } elseif ($today->gt($end)) {
            $elapsedDays = $totalDays;
        } else {
            $elapsedDays = $start->diffInDays($today);
        }

        $timeProgress = ($elapsedDays / $totalDays) * 100;

        $variance = $completion - $timeProgress;
        if ($completion == 100) {
            $variance = 101; // special code
        }

        $colorRange = Constants::getStatusColor($variance);
        return $colorRange;


    }


}
