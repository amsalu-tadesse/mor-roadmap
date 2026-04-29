<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasswordResetToken extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedBy;

    protected $guarded = [];
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];
}
