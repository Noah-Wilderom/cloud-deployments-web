<?php

namespace Modules\Cloud\Models;

use App\Models\Traits\HasUuidV7;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Cloud\Data\ServerCloudProviderData;
use Modules\Cloud\Enums\ServerStatus;
use Modules\Cloud\Enums\ServerType;
use Modules\Cloud\Observers\ServerObserver;

#[ObservedBy(ServerObserver::class)]
class Server extends Model
{
    use HasUuidV7;

    protected $fillable = [
        "user_id",
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

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
