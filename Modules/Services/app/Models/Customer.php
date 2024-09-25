<?php

namespace Modules\Services\Models;

use App\Models\Traits\HasUuidV7;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Teams\Models\Team;

class Customer extends Model
{
    use HasUuidV7;
    protected $fillable = [
        "team_id",
        "creator_id",
        "company_name",
        "name",
        "email",
    ];

    protected $casts = [
        //
    ];

    public function team(): BelongsTo {
        return $this->belongsTo(Team::class);
    }

    public function creator(): BelongsTo {
        return $this->belongsTo(User::class, "creator_id");
    }

    public function scopeForAuthUser(Builder $query): Builder {
        $teamIds = auth()->user()->allTeams()->pluck("id");
        return $query->whereIn("team_id", $teamIds);
    }
}
