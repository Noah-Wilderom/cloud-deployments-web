<?php

namespace App\Models;

use App\Models\Traits\HasUuidV7;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserKey extends Model
{
    use HasFactory, HasUuidV7;

    protected $fillable = [
        "user_id",
        "name",
        "path",
        "signature",
        "primary",
        "last_used_at",
    ];

    protected $casts = [
        "last_used_at" => "datetime",
        "path" => "encrypted",
        "primary" => "boolean",
    ];
}
