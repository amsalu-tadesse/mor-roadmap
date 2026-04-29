<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
        'position',
        'education',
        'profession',
        'profile_image',
        'row_id',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
