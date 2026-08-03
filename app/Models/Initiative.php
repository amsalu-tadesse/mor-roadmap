<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Initiative extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedBy;

    // Archival type constants
    const ARCHIVAL_NOT_ARCHIVED = 0;
    const ARCHIVAL_COMPLETED    = 1;
    const ARCHIVAL_PENDING      = 2;

    protected $fillable = [
        'name',
        'objective_id',
        'theme_id',
        'implementation_status_id',
        'note',
        'approval_description',
        'approval_remarks',
        'approval_file',
        'approval_original_file_name',
        'approval_status',
        'archival_type',
        'created_by',
        'updated_by',
    ];


    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function objective()
    {
        return $this->belongsTo(Objective::class);
    }

    public function directorates()
    {
        return $this->belongsToMany(Directorate::class, 'directorate_initiative');
    }

    public function implementationStatus()
    {
        return $this->belongsTo(ImplementationStatus::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function approvalHistories()
    {
        return $this->hasMany(InitiativeApprovalHistory::class)->orderBy('id', 'asc');
    }
}
