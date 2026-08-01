<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InitiativeApprovalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'initiative_id',
        'user_id',
        'cycle_number',
        'action',
        'description',
        'file',
        'original_file_name',
        'remarks',
    ];

    public function initiative()
    {
        return $this->belongsTo(Initiative::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
