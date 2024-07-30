<?php

namespace Modules\Cloud\Models;

use App\Models\Traits\HasUuidV7;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Modules\Cloud\Enums\TaskStatus;
use Modules\Cloud\Observers\TaskObserver;

#[ObservedBy(TaskObserver::class)]
class Task extends Model
{
    use HasUuidV7;

    protected $fillable = [
        "project_id",
        "server_id",
        "status",
        "name",
        "type",
        "meta",
        "started_at",
        "stopped_at",
    ];

    protected $casts = [
        "status" => TaskStatus::class,
        "meta" => "json",
    ];

    protected $appends = [
        "logFile",
    ];

    public function getLogFileAttribute(): ?string {
        return Storage::disk("ssh-logs")->get($this->getKey());
    }

    public function project(): BelongsTo {
        return $this->belongsTo(Project::class);
    }

    public function server(): BelongsTo {
        return $this->belongsTo(Server::class);
    }
}
