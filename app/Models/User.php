<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\CreatedUpdatedBy;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;




class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, CreatedUpdatedBy,SoftDeletes,LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'mobile',
        'password',
        'password_changed',
        'status',
        'is_superadmin',
        'organization_id',
        'twofa_code',
        'created_by',
        'updated_by',
    ];
     protected $with = ['roles'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        //'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static $logAttributes = ['*'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([
            'first_name',
            'middle_name',
            'last_name',
            'email',
            'mobile',
            'is_superadmin',
            'status',
            'created_by',
            'updated_by',
            'roles',
            'organization_id',
            'permissions',
        ])
        ->useLogName('users');
    // ->dontLogIfAttributesChangedOnly(['password']);
    // ->logOnlyDirty();
        // Chain fluent methods for configuration options
    }

    public function organization(){
        return $this->belongsTo(Organization::class, 'organization_id');
    }

}
