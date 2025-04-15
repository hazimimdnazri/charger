<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RoleAccess extends Model
{
    use HasUuids;

    protected $fillable = [
        'role_id',
        'isActive',
    ];
}
