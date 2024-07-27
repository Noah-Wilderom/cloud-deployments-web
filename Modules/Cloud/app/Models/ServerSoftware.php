<?php

namespace Modules\Cloud\Models;

use App\Models\Traits\HasUuidV7;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Cloud\Enums\Software;

class ServerSoftware extends Model
{
    use HasUuidV7;

    protected $fillable = [
        "task_id",
        "software",
        "active_version",
    ];

    protected $casts = [
        "software" => Software::class,
    ];

    public function server(): BelongsTo {
        return $this->belongsTo(Server::class);
    }

    public function task(): BelongsTo {
        return $this->belongsTo(Task::class);
    }

    public static function isSoftwareInstalled(Server $server, Software $software): bool {
        return self::query()
            ->where("server_id", $server->getKey())
            ->where("software", $software)
            ->exists();
    }
}
