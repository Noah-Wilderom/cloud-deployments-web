<?php

namespace Modules\Services\Models;

use App\Models\Traits\HasUuidV7;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Subscription extends Model
{
    use HasUuidV7;
    protected $fillable = [
        "user_id",
        "customer_id",
        "service_plan_id",
        "service_type",
        "service_id",
        "name",
        "price",
        "canceled_at",
    ];

    protected $casts = [
        "price" => "integer",
        "canceled_at" => "datetime",
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class);
    }

    public function servicePlan(): BelongsTo {
        return $this->belongsTo(ServicePlan::class, "service_plan_id");
    }

    public function service(): MorphTo {
        return $this->morphTo("service");
    }
}

