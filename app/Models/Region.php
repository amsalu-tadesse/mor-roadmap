<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Region extends Model
{
    use HasFactory, SoftDeletes, LogsActivity,CreatedUpdatedBy;
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'color',
        'ordering',
        'created_by',
        'updated_by',
    ];

    public function zones()
    {
        return $this->hasMany(Zone::class,"region_id");
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'ordering','created_by','created_at']);
        // Chain fluent methods for configuration options
    }
}
