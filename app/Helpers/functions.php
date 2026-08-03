<?php

// app/Helpers/functions.php

use App\Constants\Constants;
use Carbon\Carbon;

if (!function_exists('percentageCalculator')) {
    /**
     * Helper to format values consistently for charts
     */
    function percentageCalculator($start_date, $end_date, $completion)
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
        //$variance = $completion;
        if ($completion == 100) {
            $variance = 101; // special code
        }

        $colorRange = Constants::getStatusColor($variance);
        return $colorRange;

    }
}
