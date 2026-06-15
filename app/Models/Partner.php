<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CreatedUpdatedBy;


    protected $fillable = [
        'name',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the activities mapped to this specific partner.
     */
    public function activities(): HasMany
    {
        // Points to your 'partner_id' column on the activities table
        return $this->hasMany(Activity::class, 'partner_id', 'id');
    }
}
