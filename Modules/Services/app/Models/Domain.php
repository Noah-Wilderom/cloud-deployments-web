<?php

namespace Modules\Services\Models;

use App\Models\Traits\HasUuidV7;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Domain extends Model
{
    use HasUuidV7;

    protected $fillable = [
        "customer_id",
        "name",
        "verified",
    ];

    protected $casts = [
        //
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class);
    }
}
