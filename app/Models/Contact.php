<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Contact extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, CreatedUpdatedBy;
    protected $table= 'contacts';
    protected $with = ['messages'];
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'replied_message',
        'updated_by',
    ];

    public function messages()
    {
        return $this->hasMany(ContactMessage::class, 'contact_id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'email',
                'subject',
                'message',
                'updated_by',
            ]);
        // Chain fluent methods for configuration options
    }


}
