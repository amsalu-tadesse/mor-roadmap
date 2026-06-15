<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Directorate;
use App\Models\Partner;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('admin.index');
    }

    public function visualize1()
    {
        // Fetch directorates sorted by most initiatives down to least
        $directorates = Directorate::withCount('initiatives')
            ->orderBy('initiatives_count', 'asc') // 'asc' positions the highest bars at the top of an ECharts h-bar
            ->get();

        // Isolate labels and count values into separate primitive arrays
        $labels = $directorates->pluck('name')->toArray();
        $counts = $directorates->pluck('initiatives_count')->toArray();


        // Fetch partners with count and arrange them so highest count bars show on top of ECharts h-bar
        $partners = Partner::withCount('activities')
            ->orderBy('activities_count', 'asc')
            ->get();

        // Separate records into labels string array and values integer array
        $partnerLabels = $partners->pluck('name')->toArray();
        $activityCounts = $partners->pluck('activities_count')->toArray();


        // Fetch partners with count, sorting highest to lowest
        $partners = Partner::withCount('activities')
            ->has('activities')
            ->orderBy('activities_count', 'desc')
            ->get();

        $partnerLabels = $partners->pluck('name')->toArray();
        $activityCounts = $partners->pluck('activities_count')->toArray();



        // Fetch statuses with counts, filtering out statuses with 0 activities
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

        return view('admin.visualize.index', compact('labels', 'counts', 'partnerLabels', 'activityCounts', 'pieData', 'themeLabels', 'initiativeCounts'));

    }


}
