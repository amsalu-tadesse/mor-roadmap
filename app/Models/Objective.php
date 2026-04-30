<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Objective extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedBy;

    protected $fillable = [
        'name',
        'theme_id',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the theme that owns the objective.
     */
    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }
}
