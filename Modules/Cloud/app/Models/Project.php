<?php

namespace Modules\Cloud\Models;

use App\Events\SSHLogStreamBase;
use App\Models\Traits\HasPublicId;
use App\Models\Traits\HasUuidV7;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Cloud\Data\GitRepositoryData;
use Modules\Cloud\Data\ProjectSettings;
use Modules\Cloud\Enums\ProjectEnvironment;
use Modules\Cloud\Enums\ProjectTemplate;
use Modules\Services\Models\Customer;
use Modules\Services\Models\Domain;
use Modules\Services\Models\ServicePlan;
use Valorin\Random\Random;

class Project extends Model
{
    use HasUuidV7, HasPublicId;
    protected $fillable = [
        "public_id",
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
        "environments",
        "settings",
    ];

    protected $casts = [
        "initialized" => "boolean",
        "template" => ProjectTemplate::class,
        "git_repository" => GitRepositoryData::class,
        "environments" => AsEnumCollection::class . ":" . ProjectEnvironment::class,
        "settings" => ProjectSettings::class,
    ];

    protected string $publicIdPrefix = "project_";

    public function getRouteKeyName()
    {
        return "public_id";
    }

    public static function generateProjectName(string $name): string {
        $words = explode(" ", $name);
        if (count($words) <= 1) {
            $words = explode("-", $name);
        }

        $maxChars = 8;
        $maxSingleWordChar = 4;
        $projectName = "";

        foreach ($words as $word) {
            if ($maxChars < 1) {
                break;
            }

            $projectName .= str($word)->limit(
                limit: $maxChars - $maxSingleWordChar < 1
                    ? $maxChars
                    : $maxSingleWordChar,
                end: ""
            );
            $maxChars -= $maxSingleWordChar;
        }

        return str(sprintf("%s_%s", $projectName, Random::string(
            length: 6,
            lower: true,
            upper: false,
            numbers: true,
            symbols: false,
            requireAll: false,
        )))->slug(separator: "_");
    }

    public function getFullDomain(): string {
        return $this->sub_domain === ""
            ? $this->domain->name
            : sprintf("%s.%s", $this->sub_domain, $this->domain->name);
    }

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

    public function runTask(string $task, SSHLogStreamBase $event = null, $data = []): \Modules\Cloud\Tasks\Task {
        $this->loadMissing(["server"]);
        $obj = new $task($this->server, $event, $data);
        if ($obj instanceof \Modules\Cloud\Tasks\Task) {
            return $obj->setProject($this);
        }

        throw new \Exception(sprintf("[%s] is not a task", $task));
    }
}
