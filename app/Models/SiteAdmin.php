<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SiteAdmin extends Model
{
    use HasFactory, SoftDeletes, LogsActivity,CreatedUpdatedBy;
    protected $fillable = [
        'name',
        'aboutus',
        'location',
        'address',
        'email',
        'telephone',
        'facebook',
        'twitter',
        'youtube',
        'intro_video',
        'linkedin',
        'updated_by',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'aboutus',
                'location',
                'address',
                'email',
                'telephone',
                'facebook',
                'twitter',
                'youtube',
                'linkedin',
                'updated_by',
                'created_at'
            ]);
        // Chain fluent methods for configuration options
    }
}
