<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Directorate;
use App\Models\Partner;

class Activity extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedBy;

    protected $table = 'activities';

    protected $fillable = [
        'initiative_id',
        'partner_id',
        'activities',
        'request_status_id',
        'priority',
        'start_date',
        'end_date',
        'budget',
        'expenditure',
        'completion',
        'activity_status_id',
        'request_type',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    const PRIORITIES = [
        'L' => 'Low',
        'M' => 'Medium',
        'H' => 'High',
    ];

    public function activityStatus()
    {
        return $this->belongsTo(ActivityStatus::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function requestStatus()
    {
        return $this->belongsTo(RequestStatus::class);
    }

    public function initiative()
    {
        return $this->belongsTo(Initiative::class);
    }

    public function interestedPartners()
    {
        return $this->belongsToMany(Partner::class, 'activity_interested_partner');
    }

    public function directorates()
    {
        return $this->belongsToMany(Directorate::class, 'activity_directorate');
    }
}
