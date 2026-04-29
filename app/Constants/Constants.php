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
    public const EXCEPTION_EMAIL_TITLE = 'UserMangment Exception';
    public const CRON_EXECUTION_TIME = 5;
    public const APP_NAME = "UserMangment";
    public const CRIME_CATEGORY_HABITAT = 1; //Habitat type id.
    
    public const CRIME_CATEGORY_FLORA = 2; //flora type id.

    public const CRIME_CATEGORY_WILDLIFE = 3; //wildlife type id.
    public const CRIME_CATEGORY_TRAFFICING = 4; //trafficing type id.


    public const ROLE_SUPER_ADMIN = "Super Admin";
    public const ROLE_SUPPERVISOR = "Suppervisor";
    public const ROLE_REGION_LEVEL_DATA_MANAGER = "Region Level Data Manager";
    public const ROLE_FEDERAL_LEVEL_DATA_MANAGER = "Federal Level Data Manager";

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
    }

    public static function jobsKr() {
        return [
            "학생",
            "대학생",
            "주부",
            "직장인",
            "자영업",
            "프리랜서",
            "기타",
        ];
    }*/
}
