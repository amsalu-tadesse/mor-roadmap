<?php

namespace App\Services;

use App\Models\ColorCode;

class StatusService
{
    public static function getRanges()
    {

        return ColorCode::orderBy('min', 'desc')
           ->get()
           ->map(function ($range) {
               return [
                   'min' => $range->min,
                   'max' => $range->max,
                   'label' => $range->label,
                   'color' => $range->color,
               ];
           })
           ->toArray();


        // Later replace this with a DB query.
        /*return [
            [
                'min' => 101,
                'max' => 101,
                'label' => 'completed',
                'color' => '#194da8',
            ],
            [
                'min' => 0,
                'max' => 10,
                'label' => 'within 0-10%',
                'color' => '#0b8c38',
            ],
            [
                'min' => -10,
                'max' => -1,
                'label' => 'within -10% to 0%',
                'color' => '#FACC15',
            ],
            [
                'min' => -30,
                'max' => -11,
                'label' => 'within -30% to -10%',
                'color' => '#F97316',
            ],
            [
                'min' => PHP_INT_MIN,
                'max' => -31,
                'label' => 'below -30%',
                'color' => '#FF0000',
            ],
        ];*/
    }

    public static function getStatus($difference)
    {
        foreach (self::getRanges() as $range) {
            if ($difference >= $range['min'] && $difference <= $range['max']) {
                return [$range['color'], $range['label']];
            }
        }

        return ['#000000', 'Unknown'];
    }

    public static function getChartRanges()
    {
        return collect(self::getRanges())
            ->mapWithKeys(fn ($r) => [$r['label'] => $r['min']])
            ->toArray();
    }


    public static function getColorMap()
    {
        return collect(self::getRanges())
            ->mapWithKeys(function ($range) {
                return [
                    $range['label'] => $range['color']
                ];
            })
            ->toArray();
    }


}
