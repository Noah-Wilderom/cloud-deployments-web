<?php

namespace Modules\Teams\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamUser extends Pivot
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        "role"
    ];
}
