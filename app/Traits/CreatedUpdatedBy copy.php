<?php

namespace App\Traits;

use App\Http\Controllers\Utility;
use App\Models\Setting;
use Exception;
use Spatie\Activitylog\Models\Activity;

trait CreatedUpdatedByCopy
{
    public static function bootCreatedUpdatedBy()
    {
        //    dd(90);
        // updating created_by and updated_by when model is created
        static::creating(function ($model) {
            if (!$model->isDirty('created_by')) {
                $model->created_by = auth()?->user()?->id;
                $lastActivities = Activity::all()->last();

                //send message in telegram.
                $enable = Setting::where('code', 'allow_telegram_message')->value('value1');
                if ($enable == 1) {
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
                $lastActivities = Activity::all()->last();
            }

            //   dd($lastActivities->changes);
        });

        // updating updated_by when model is updated
        static::updating(function ($model) {
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = auth()?->user()?->id;
            }
            //TODO check what is important to log.
            $lastActivities = Activity::all()->last();
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
             "Region", "Kpi", "WorkingGroup" => "*** New ".$modelName. " has been created ***\n Name: ".$model->name,
            "User" => "*** New User has been registered *** \n Name: " . $model->first_name . " " . $model->middle_name . " " . $model->last_name . " \n Email: " . $model->email,
            "Issue" => "*** New Issue has been created *** \n Title: " . $model->title,
            "Email" => $model->subject,
            "Zone" =>  "*** New ".$modelName. " has been created ***\n Name: ".$model->name." \n Region: ".$model?->region?->name,
            default => 'Unknown',
        };
        return $message;
    }
}
