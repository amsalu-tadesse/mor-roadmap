<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Initiative extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedBy;

    protected $fillable = [
        'name',
        'objective_id',
        'directorate_id',
        'implementation_status_id',
        'note',
        'start_date',
        'end_date',
        'budget',
        'expenditure',
        'partner_id',
        'completion',
        'initiative_status_id',
        'request',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function objective()
    {
        return $this->belongsTo(Objective::class);
    }

    public function directorate()
    {
        return $this->belongsTo(Directorate::class);
    }

    public function implementationStatus()
    {
        return $this->belongsTo(ImplementationStatus::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function initiativeStatus()
    {
        return $this->belongsTo(InitiativeStatus::class);
    }
}
