<?php

namespace Modules\Cloud\Tasks;

use App\Events\SSHLogStreamBase;
use Illuminate\Support\Str;
use Modules\Cloud\Enums\TaskStatus;
use Modules\Cloud\Events\SSH\ServerInitializingLogs;
use Modules\Cloud\Models\Project;
use Modules\Cloud\Models\Server;
use Modules\Cloud\Models\Task as TaskModel;
use App\SSH;

abstract class Task {
    protected int $timeout = 10;
    private string $user;
    private SSH\Connection $conn;
    protected Server $server;
    protected ?Project $project = null;

    protected array $meta = [];
    protected TaskModel $task;
    protected ?SSHLogStreamBase $event;

    public function __construct(Server $server, SSHLogStreamBase $event = null) {
        $this->server = $server;
        $this->event = $event;

        $this->task = TaskModel::create([
            "server_id" => $this->server->getKey(),
            "project_id" => $this->project?->getKey(),
            "status" => TaskStatus::Created,
            "name" => $this->name(),
            "type" => self::class,
            "meta" => $this->meta,
        ]);
    }

    public abstract function command(): string;
    public abstract function name(): string;

    public function asProjectUser(Project $project): self {
        $this->project = $project;
        $this->user = $this->project->name;
        return $this;
    }

    public function asUser(): self {
        $this->user = "cloud-deployments";
        return $this;
    }

    public function asRoot(): self {
        $this->user = "root";
        return $this;
    }

    private function prepareSshConnection(): bool {
        if(! is_null($this->event)) {
            $this->event->task = $this->task;
        }
        $this->conn = new SSH\Connection(
            host: $this->server->host["ipv4"],
            port: 22,
            username: $this->user,
            privateKeyPath: sprintf("%s/id_rsa", $this->server->ssh_credentials_path),
            timeout: $this->timeout,
            event: $this->event,
            logFileId: $this->task->getKey(),
        );
        return $this->conn->ping();
    }

    public function handle(): void {
        try {
            if(! $this->prepareSshConnection()) {
                throw new \Exception("Cannot prepare SHH Connection");
            }

            $this->task->update(["status" => TaskStatus::Running, "started_at" => now()]);
            $output = $this->conn->runCommand($this->command());
            if ((bool) $output)  {
                $this->task->update(["status" => TaskStatus::Success, "stopped_at" => now()]);
            }
        } catch(\Exception $e) {
            $this->task->update(["status" => TaskStatus::Failed]);
            throw $e;
        }
    }

    protected function replaceVariables(string $contents, array $variables): string {
        foreach($variables as $key => $value) {
            $contents = Str::replace(sprintf("{{%s}}", Str::upper($key)), $value, $contents);
        }

        return $contents;
    }
}
