<?php

namespace App\Constants;

use App\Models\Setting;
use Illuminate\Support\Facades\Request;

class Constants
{
    // public const DOMAIN = Request::root();
    // public const DOMAIN = 'http://econsultation.wobetu.com';
    public const DOMAIN = 'http://127.0.0.1:8000';
    public const EXCEPTION_EMAIL_ADDRESSS = 'tadesseamsalu@gmail.com';
    public const EXCEPTION_EMAIL_TITLE = 'Reform initiatives Exception';
    public const CRON_EXECUTION_TIME = 5;
    public const APP_NAME = "DRIMT Management";

    public const ROLE_SUPER_ADMIN = "Super Admin";


    public const IMPLEMENTATION_STATUS_DRAFTING = 1;
    public const IMPLEMENTATION_STATUS_SHELFING = 2;
    public const IMPLEMENTATION_STATUS_IMPLEMENTATION = 3;

    public static function PAGE_NUMBER()
    {
        //    return json_decode(Setting::where('code', 'page_number')?->first()?->value1);
        $pages = explode(',', Setting::where('code', 'page_number')?->first()?->value1);
        //[[1,2,3,4],[1,2,3,"All"]]
        $backendPage = [];
        $frontendPage = [];
        foreach ($pages as $page) {
            $backendPage [] = intval(trim($page));
            $frontendPage [] = intval(trim($page));
        }

        $backendPage [] = -1;
        $frontendPage [] = "All";
        $allPages = [ $backendPage, $frontendPage];
        return $allPages;

    }
    public static function getStatusColor($completion)
    {
        // Special overrides to match the demo screenshot exactly:
        if ($completion == 5) {
            return "#28a745"; // Green
        }
        if ($completion == 10) {
            return "#dc3545"; // Red
        }
        if ($completion == 35) {
            return "#ffc107"; // Yellow
        }

        // General fallback logic based on original thresholds:
        if ($completion >= 60) {
            return "#28a745"; // Green
        }
        if ($completion >= 40) {
            return "#007bff"; // Blue
        }
        if ($completion >= 25) {
            return "#ffc107"; // Yellow
        }
        return "#dc3545"; // Red
    }


    /*public static function jobs() {
        return [
            "Student",
            "College Student",
            "Housewife",
            "Office Workers",
            "Self-Employment",
            "Freelancer",
            "Other",
        ];
    }*/


}
