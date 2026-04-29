<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Draft;
use App\Models\Email;
use App\Constants\Constants;
use App\Http\Controllers\Controller;
use App\Services\EmailService;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Exception;

class Utility  extends Controller
{


    public function dataAcces($user, $issue)
    {
        // BMO can see his own data only.
        //Federal BMO can see his own issues only.
        //zonal BMO can see his own issues only.
        //Regional BMO can see his own and all issues under all zones in the region. but no action on zonals

        // CFC Secretariat can see a level wise data.
        //Federal ECSA can see all Federal issues.
        //zonal ECSA can see all zonal issues under the given region.
        //Regional ECSA can see all issues under his region + all issues under all zones in the region. but no action on zonals

        //Working Group can see all a level wise data
        //Federal Working Group can see all Federal issues.
        //zonal Working Group can see all zonal issues under the given region.
        //Regional Working Group can see all issues under his region + all issues under all zones in the region. but no action on zonals

        //MOTRI-MOI can see all a level wise data
        //Federal MOTRI-MOI can see all Federal issues.
        //zonal MOTRI-MOI can see all zonal issues under the given region.
        //Regional MOTRI-MOI can see all issues under his region + all issues under all zones in the region. but no action on zonals



    }
    public static function getRandomStringRandomInt($length = 8)
    {
        $stringSpace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pieces = [];
        $max = mb_strlen($stringSpace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $stringSpace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }


    public static function getLoggedInUserfromNonAuth()
    {
        $access_token = request()->header('Authorization');

        // break up the string to get just the token
        $auth_header = explode(' ', $access_token);

        $token = $auth_header[1];

        // break up the token into its three parts
        $token_parts = explode('.', $token);

        $token_header = $token_parts[0];

        $token = PersonalAccessToken::findToken($token_header);

        $user = $token?->tokenable;
        return $user;
    }


    public static function sendTelegramMsg($msg)
    {

        $msg = urlencode($msg);

        $notifyInTelegramBot = "https://api.telegram.org/bot6387930830:AAG5N9trCyjLrtVEWyyVnaJ1hp5uyMwG3nI/sendMessage?chat_id=-915723432&text= " . $msg;

        file_get_contents($notifyInTelegramBot);
    }

    public static function changeIdToName($attribute, $value)
    {

        $result = match ($attribute) {
            'region_id' => ['region', \App\Models\Region::where('id', $value)?->first()?->name],
            'zone_id' => ['region', \App\Models\Zone::where('id', $value)?->first()?->name],
            default => [$attribute, $value],
        };


        return $result;
    }
}
