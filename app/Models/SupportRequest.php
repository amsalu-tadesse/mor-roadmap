<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportRequest extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedBy;

    protected $fillable = [
        'initiative_id',
        'partner_id',
        'activities',
        'request_status_id',
        'priority',
        'created_by',
        'updated_by',
    ];

    const PRIORITIES = [
        'L' => 'Low',
        'M' => 'Medium',
        'H' => 'High',
    ];

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
}
