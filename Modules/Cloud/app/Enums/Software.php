<?php

namespace Modules\Cloud\Enums;

use Modules\Cloud\Tasks\InstallCertbotTask;
use Modules\Cloud\Tasks\InstallComposerTask;
use Modules\Cloud\Tasks\InstallDockerTask;
use Modules\Cloud\Tasks\InstallGoTask;
use Modules\Cloud\Tasks\InstallMySqlTask;
use Modules\Cloud\Tasks\InstallNginxTask;
use Modules\Cloud\Tasks\InstallPhpTask;

enum Software: string {
    case Caddy = 'caddy';
    case Nginx = 'nginx';
    case Composer = 'composer';
    case MySql = 'mysql';
    case Node = 'node';
    case Php = 'php';
    case Redis = 'redis';
    case Certbot = "certbot";
    case Go = "go";
    case Docker = "docker";

    /**
     * @return string[]|null
     */
    public function versions(): ?array {
        return match($this) {
            self::Node => ["18", "20", "22"],
            self::Php => ["8.1", "8.2", "8.3"],
            default => null,
        };
    }

    public function displayName(): string {
        return match($this) {
            self::Php => 'PHP',
            self::Caddy => 'Caddy',
            self::Nginx => 'Nginx',
            self::Node => 'Node',
            self::Redis => 'Redis',
            self::MySql => 'MySql',
            self::Certbot => "Certbot",
            self::Composer => 'Composer',
            self::Go => "Go",
            self::Docker => "Docker"
        };
    }

    /**
     * @param ServerType $serverType
     * @return Software[]
     */
    public static function defaultStack(ServerType $serverType): array {
        return match($serverType) {
            ServerType::Webserver => [
                self::Php,
                self::Nginx,
                self::MySql,
//                self::Node,
//                self::Redis,
                self::Certbot,
                self::Composer,
                self::Go,
                self::Docker,
            ],
        };
    }

    /**
     * @return string|null
     */
    public function getScriptPath(): ?string {
        return match($this) {
            self::Nginx => "server/softwares/install_nginx.sh",
            self::Composer => "server/softwares/install_composer.sh",
            self::MySql => "server/softwares/install_mysql.sh",
            self::Php => "server/softwares/install_php.sh",
            self::Certbot => "server/softwares/install_certbot.sh",
            self::Go => "server/softwares/install_go.sh",
            self::Docker => "server/softwares/install_docker.sh",
            default => null,
        };
    }

    public function getTask(): ?string {
        return match($this) {
            self::Nginx => InstallNginxTask::class,
            self::Composer => InstallComposerTask::class,
            self::MySql => InstallMySqlTask::class,
            self::Php => InstallPhpTask::class,
            self::Certbot => InstallCertbotTask::class,
            self::Go => InstallGoTask::class,
            self::Docker => InstallDockerTask::class,
            default => null,
        };
    }
}
