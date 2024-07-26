<?php

namespace Modules\Cloud\Models;

use App\Models\Traits\HasUuidV7;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Script extends Model
{
    use HasUuidV7;
    protected $fillable = [
        //
    ];

    protected $casts = [
        //
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }}
}
