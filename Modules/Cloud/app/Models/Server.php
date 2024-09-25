<?php

namespace Modules\Cloud\Models;

use App\Events\SSHLogStreamBase;
use App\Models\Traits\HasUuidV7;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Cloud\Data\ServerCloudProviderData;
use Modules\Cloud\Enums\ServerStatus;
use Modules\Cloud\Enums\ServerType;
use Modules\Cloud\Enums\Software;
use Modules\Cloud\Observers\ServerObserver;
use Modules\Services\Models\ServicePlan;
use Modules\Teams\Models\Team;

#[ObservedBy(ServerObserver::class)]
class Server extends Model
{
    use HasUuidV7;

    protected $fillable = [
        "team_id",
        "creator_id",
        "customer_id",
        "type",
        "ssh_credentials_path",
        "status",
        "name",
        "cloud_provider",
        "host",
    ];

    protected $casts = [
        "type" => ServerType::class,
        "status" => ServerStatus::class,
        "cloud_provider" => ServerCloudProviderData::class . ":encrypt",
        "host" => "json",
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

    public function projects(): HasMany {
        return $this->hasMany(Project::class);
    }
    public function softwares(): HasMany {
        return $this->hasMany(ServerSoftware::class);
    }

    public function tasks(): HasMany {
        return $this->hasMany(Task::class);
    }

    public function hasSoftware(Software $software): bool {
        return ServerSoftware::isSoftwareInstalled($this, $software);
    }

    public function getInstalledSoftwareVersion(Software $software): ?string {
        return $this->softwares()
            ->where("software", $software)
            ->first("installed_version") ?? null;
    }

    public function runTask(string $task, SSHLogStreamBase $event = null, $data = []): \Modules\Cloud\Tasks\Task {
        $obj = new $task($this, $event, $data);
        if ($obj instanceof \Modules\Cloud\Tasks\Task) {
            return $obj;
        }

        throw new \Exception(sprintf("[%s] is not a task", $task));
    }
}
