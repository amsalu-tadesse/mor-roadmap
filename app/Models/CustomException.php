<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomException extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedBy;
    protected $fillable = ['title','description', 'code', 'status'];
}
