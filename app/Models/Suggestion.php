<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Suggestion extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, CreatedUpdatedBy;

    protected $fillable = [
        'name',
        'email',
        'body',
        'subject',
        'phone',
        'created_by',
        'updated_by',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'email',
                'body',
                'subject',
                'phone',
                'created_by',
                'updated_by',
            ]);
    }

}
