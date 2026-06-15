<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Directorate extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CreatedUpdatedBy;

    protected $fillable = ['name', 'user_id', 'created_by', 'updated_by'];

    public function director()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
    * The initiatives that belong to the directorate.
    */
    public function initiatives(): BelongsToMany
    {
        return $this->belongsToMany(
            Initiative::class,
            'directorate_initiative', // Your pivot table name
            'directorate_id',         // Foreign key on pivot table referencing directorates
            'initiative_id'           // Foreign key on pivot table referencing initiatives
        );
    }
}
