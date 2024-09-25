<?php

namespace Modules\Teams\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        "name"
    ];

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class)
            ->using(TeamRole::class);
    }

    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, "owner_id");
    }

    public function isOwner(User $user): bool {
        return $this->owner()->is($user);
    }
}
