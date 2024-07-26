<?php

namespace App\Models;

use App\Enums\IntegrationProvider;
use App\Models\Traits\HasUuidV7;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserIntegration extends Model
{
    use HasFactory, HasUuidV7;

    protected $fillable = [
        "user_id",
        "token",
        "provider",
        "data",
    ];

    protected $casts = [
        "provider" => IntegrationProvider::class,
        "data" => "encrypted:array",
        "token" => "encrypted",
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
