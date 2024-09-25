<?php

namespace Modules\Services\Models;

use App\Models\Traits\HasUuidV7;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Teams\Models\Team;

class Subscription extends Model
{
    use HasUuidV7;
    protected $fillable = [
        "team_id",
        "creator_id",
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

    public function team(): BelongsTo {
        return $this->belongsTo(Team::class);
    }

    public function creator(): BelongsTo {
        return $this->belongsTo(User::class, "creator_id");
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

    public function scopeForAuthUser(Builder $query): Builder {
        $teamIds = auth()->user()->allTeams()->pluck("id");
        return $query->whereIn("team_id", $teamIds);
    }
}

