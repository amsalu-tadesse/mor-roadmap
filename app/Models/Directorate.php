<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Directorate extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedBy;

    protected $fillable = ['name', 'user_id', 'created_by', 'updated_by'];

    public function director()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
