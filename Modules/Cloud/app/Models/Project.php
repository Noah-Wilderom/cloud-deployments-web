<?php

namespace Modules\Cloud\Models;

use App\Models\Traits\HasUuidV7;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Cloud\Data\GitRepositoryData;
use Modules\Cloud\Enums\ProjectTemplate;
use Modules\Services\Models\Customer;
use Modules\Services\Models\Domain;
use Modules\Services\Models\ServicePlan;

class Project extends Model
{
    use HasUuidV7;
    protected $fillable = [
        "user_id",
        "customer_id",
        "domain_id",
        "server_id",
        "initialized",
        "sub_domain",
        "template",
        "name",
        "ssh_user",
        "ssh_credentials_path",
        "host_ssh_credentials_path",
        "git_repository",
    ];

    protected $casts = [
        "initialized" => "boolean",
        "template" => ProjectTemplate::class,
        "git_repository" => GitRepositoryData::class,
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class);
    }

    public function server(): BelongsTo {
        return $this->belongsTo(Server::class);
    }

    public function domain(): BelongsTo {
        return $this->belongsTo(Domain::class);
    }
}
