<?php

namespace App\SSH;

use App\Events\SSHLogStreamBase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;

class Connection {
    private string $host;
    private int $port;
    private string $username;
    private string $privateKeyPath;
    private ?SSHLogStreamBase $event;
    private string $logFilePath;

    public function __construct(string $host, int $port, string $username, string $privateKeyPath, ?SSHLogStreamBase $event = null, string $logFileId = null) {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->privateKeyPath = $privateKeyPath;
        $this->event = $event;
        $this->logFilePath = Storage::disk("ssh-logs")->path($logFileId ?? uuidV7());
    }

    public function ping(): bool {
        // Load the private key
        $privateKeyContents = Storage::disk("keypairs")->get($this->privateKeyPath);
        $privateKeyContents = Crypt::decryptString($privateKeyContents);
        $privateKey = PublicKeyLoader::loadPrivateKey($privateKeyContents);
        // Connect to the server
        $ssh = new SSH2($this->host, $this->port);
        $login = $ssh->login($this->username, $privateKey);
        $ssh->disconnect();
        return $login;
    }

    public function runCommand(string $command): bool {
        // Load the private key
        $privateKeyContents = Storage::disk("keypairs")->get($this->privateKeyPath);
        $privateKeyContents = Crypt::decryptString($privateKeyContents);
        $privateKey = PublicKeyLoader::loadPrivateKey($privateKeyContents);

        // Connect to the server
        $ssh = new SSH2($this->host, $this->port);
        if (!$ssh->login($this->username, $privateKey)) {
            throw new \Exception('Login failed');
        }

        // Run the command
        $output = $ssh->exec($command, function(string $log) {
            Storage::disk("ssh-logs")->append($this->logFilePath, $log);
            if(is_null($this->event)) {
                $event = clone $this->event;
                $event->logLine = $log;
                Event::dispatch($event);
            }
        });

        $ssh->disconnect();

        return (string) $output;
    }
}
