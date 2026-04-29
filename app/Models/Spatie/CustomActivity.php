<?php

namespace App\Models\Spatie;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class CustomActivity extends SpatieActivity
{

    public function causer(): MorphTo
    {
        if (config('activitylog.causer_returns_soft_deleted_models')) {
            return parent::causer()->withTrashed();
        }

        return parent::causer();
    }
}
