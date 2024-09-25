<?php

namespace Modules\Cloud\Tasks;

use App\Events\SSHLogStreamBase;
use Illuminate\Support\Facades\Log;
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

    protected TaskModel $task;
    protected ?SSHLogStreamBase $event;
    protected array $data;

    public function __construct(Server $server, SSHLogStreamBase $event = null, $data = []) {
        $this->server = $server;
        $this->event = $event;
        $this->data = $data;

        $this->task = TaskModel::create([
            "server_id" => $this->server->getKey(),
            "project_id" => $this->project?->getKey(),
            "status" => TaskStatus::Created,
            "name" => $this->name(),
            "type" => static::class,
            "meta" => $this->data,
        ]);
    }

    public abstract function command(): string;
    public abstract function name(): string;
    public function callback(bool $commandSuccess): void {
        //
    }

    public function asProjectUser(Project $project): self {
        $this->project = $project;
        $this->user = $this->project->ssh_user;
        return $this;
    }

    public function setProject(Project $project): self {
        $this->project = $project;
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
            $ok = $this->conn->runCommand($this->command());
            if ($ok)  {
                $this->task->update(["status" => TaskStatus::Success, "stopped_at" => now()]);
            } else {
                Log::info("Task failed", ["task" => $this->task->name, "server" => $this->server->id]);
                $this->task->update(["status" => TaskStatus::Failed, "stopped_at" => now()]);
            }

            $this->callback($ok);
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
