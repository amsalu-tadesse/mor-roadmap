<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Setting extends Model
{
    use HasFactory, SoftDeletes, LogsActivity,CreatedUpdatedBy;
    protected $fillable = [
        'code',
        'name',
        'value1',
        'value2',
        'created_by',
        'updated_by',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['code','name', 'value1','value2','created_by','created_at']);
        // Chain fluent methods for configuration options
    }
}
