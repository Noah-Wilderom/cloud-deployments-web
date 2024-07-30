<?php

namespace Modules\Services\Models;

use App\Models\Traits\HasUuidV7;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Cloud\Models\Project;
use Modules\Cloud\Models\Server;
use Modules\Services\Data\ServicePlanResourcesData;
use Modules\Services\Data\ServicePlanSettingsData;

class ServicePlan extends Model
{
    use HasUuidV7;

    protected $fillable = [
        "user_id",
        "service_type",
        "name",
        "base_price",
        "resources",
        "settings",
    ];

    protected $casts = [
        "base_price" => "integer",
        "resources" => ServicePlanResourcesData::class,
        "settings" => ServicePlanSettingsData::class,
    ];


    /**
     *
     * @return array [<class>]<string>
     */
    public static function getServiceTypes(): array {
        return [
            Domain::class => "Domain",
            Server::class => "Server",
            Project::class => "Project",
        ];
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
