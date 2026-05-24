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
        'theme_id',
        'implementation_status_id',
        'note',
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
}
