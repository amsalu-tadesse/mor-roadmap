<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Zone extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, CreatedUpdatedBy;
    protected $fillable = [
        'name',
        'region_id',
        'created_by',
        'updated_by',
    ];
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
    public function zone_habitat(){
        return $this->hasMany(Habitat::class,"zone_id");
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'region_id','created_by','created_at']);
        // Chain fluent methods for configuration options
    }
}
