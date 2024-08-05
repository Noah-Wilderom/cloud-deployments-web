<?php

namespace App\SSH;

use App\Events\SSHLogStreamBase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;
use Spatie\Ssh\Ssh;
use Symfony\Component\Console\Command\Command as CommandAlias;

class Connection {
    private string $host;
    private int $port;
    private string $username;
    private string $privateKeyPath;
    private int $timeout;
    private ?SSHLogStreamBase $event;
    private string $logFilePath;

    public function __construct(string $host, int $port, string $username, string $privateKeyPath, int $timeout = 10, ?SSHLogStreamBase $event = null, string $logFileId = null) {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->privateKeyPath = $privateKeyPath;
        $this->timeout = $timeout;
        $this->event = $event;
        $this->logFilePath = $logFileId ?? uuidV7();
    }

    public function ping(): bool {
        // Load the private key
        $privateKeyContents = Storage::disk("keypairs")->get($this->privateKeyPath);
        $privateKeyContents = Crypt::decryptString($privateKeyContents);
        $privateKey = PublicKeyLoader::loadPrivateKey($privateKeyContents);
        // Connect to the server
        $ssh = new SSH2($this->host, $this->port, timeout: $this->timeout);
        $login = $ssh->login($this->username, $privateKey);
        $ssh->disconnect();
        return $login;
    }

    public function runCommand(string $command): bool {
        try {
            // Load the private key
            $privateKeyContents = Storage::disk("keypairs")->get($this->privateKeyPath);
            $privateKeyContents = Crypt::decryptString($privateKeyContents);
            $privateKey = PublicKeyLoader::loadPrivateKey($privateKeyContents);

            $ssh = new SSH2($this->host, $this->port, timeout: $this->timeout);

            if (!$ssh->login($this->username, $privateKey)) {
                throw new \Exception('SSH login failed');
            }

            // Log command execution attempt
            $start = now();

            // Run the command
            $output = $ssh->exec($command, function(string $log) {
                $log = trim($log). PHP_EOL;
                if (str_replace(PHP_EOL, "", $log) !== "") {
                    Storage::disk("ssh-logs")->append($this->logFilePath, $log);
                    if (!is_null($this->event)) {
                        $event = clone $this->event;
                        $event->logLine = $log;
                        Log::info(sprintf("Dispatching (%d) bytes", strlen($log)));
                        Event::dispatch($event);
                    }
                }
            });

            // Log command execution result
            Log::info('Command executed via SSH.', ['output' => $output, "ssh_status" => $ssh->getExitStatus(), "time" => sprintf("%.2f Seconds", $start->diffInMilliseconds(now()) / 1000)]);

            $ssh->disconnect();
            return $ssh->getExitStatus() == CommandAlias::SUCCESS;
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Error in runCommand.', ['exception' => $e->getMessage()]);
            // Re-throw the exception for further handling
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function executeShellScript(string $filepath): bool {
        $script = Storage::disk("scripts")->get($filepath);
        if (is_null($script)) {
            throw new \Exception(sprintf("Script not found at: %s", Storage::disk("scripts")->path($filepath)));
        }

        return $this->runCommand(sprintf("echo '%s' | sh", $script));
    }
}
