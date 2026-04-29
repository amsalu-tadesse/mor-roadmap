<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;


class Role extends Model
{
    // use HasFactory, LogsActivity;
    // protected $table = 'kpis';

    // protected $fillable = ['name','description'];

    // public function Permissions()
    // {
    //     return $this->hasMany(Permission::class, 'role_has_permissions');
    // }

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly(['name', 'permissions'])
    //         ->useLogName('roles')
    //         ->logChangesOnUpdate();
    // }




}
