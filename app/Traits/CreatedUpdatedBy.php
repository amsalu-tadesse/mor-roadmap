<?php

namespace App\Traits;

use App\Http\Controllers\Utility;
use App\Models\Setting;
use App\Models\Spatie\CustomActivity;
use Exception;

trait CreatedUpdatedBy
{
    public static function bootCreatedUpdatedBy()
    {
        //    dd(90);
        // updating created_by and updated_by when model is created
        static::creating(function ($model) {
            if (!$model->isDirty('created_by')) {
                $model->created_by = auth()?->user()?->id;
                $lastActivities = CustomActivity::all()->last();

                //send message in telegram.
                $enabled = Setting::where('code', 'allow_telegram_message')->value('value1');

                if ($enabled == 1) {
                    try {
                        $message = self::getMessage($model);

                        if($message != 'Unknown')
                        {
                            // Utility::sendTelegramMsg($message);
                            dispatch(new \App\Jobs\SendTelegram($message));
                        }

                    } catch (Exception $ex) {
                        //continue.
                    }
                }

            }



            if (!$model->isDirty('updated_by')) {
                $model->updated_by = auth()?->user()?->id;
                $lastActivities = CustomActivity::all()->last();
            }

            //   dd($lastActivities->changes);
        });

        // updating updated_by when model is updated
        static::updating(function ($model) {
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = auth()?->user()?->id;
            }
            //TODO check what is important to log.
            $lastActivities = CustomActivity::all()->last();
        });

        //when soft deleting the parent, soft delete the children as well.
        static::deleting(function ($model) {
            $model?->child?->each->delete();
        });

        //when restoring the parent, restore the children as well.
        static::restoring(function ($model) {
            $model?->child?->each->restore();
        });
    }



    public static function getMessage($model)
    {
        $modelName = class_basename($model);

        $message = match ($modelName) {
            "Organization", "OrganizationType", "OrganizationLevel", "Region", "Kpi", "WorkingGroup" => "*** New ".$modelName. " has been created ***\n Name: ".$model->name,
            "User" => "*** New User has been registered *** \n Name: " . $model->first_name . " " . $model->middle_name . " " . $model->last_name . " \n Email: " . $model->email.
            "\n Organization: " . $model?->organization?->name,
            "Issue" => "*** New Issue has been created *** \n Title: " . $model->title,
            "Email" => $model->subject,
            "City" =>  "*** New ".$modelName. " has been created ***\n Name: ".$model->name." \n Region: ".$model?->region?->name,
            default => 'Unknown',
        };
        return $message;
    }
}
