<?php

namespace Modules\Teams\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TeamRole extends Model
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        "name",
        "permissions",
    ];

    protected $casts = [
        "permissions" => "array",
    ];
}
