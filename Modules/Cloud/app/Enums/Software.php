<?php

namespace Modules\Cloud\Enums;

use Modules\Cloud\Tasks\InstallCertbotTask;
use Modules\Cloud\Tasks\InstallComposerTask;
use Modules\Cloud\Tasks\InstallMySqlTask;
use Modules\Cloud\Tasks\InstallNginxTask;
use Modules\Cloud\Tasks\InstallPhpTask;
use Modules\Cloud\Tasks\InstallTaskPhp;

enum Software: string {
    case Caddy = 'caddy';
    case Nginx = 'nginx';
    case Composer = 'composer';
    case MySql = 'mysql';
    case Node = 'node';
    case Php = 'php';
    case Redis = 'redis';
    case Certbot = "certbot";

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
            self::Caddy => 'Caddy',
            self::Nginx => 'Nginx',
            self::Composer => 'Composer',
            self::MySql => 'MySql',
            self::Node => 'Node',
            self::Php => 'PHP',
            self::Redis => 'Redis',
            self::Certbot => "Certbot",
        };
    }

    /**
     * @param ServerType $serverType
     * @return Software[]
     */
    public static function defaultStack(ServerType $serverType): array {
        return match($serverType) {
            ServerType::Webserver => [
                self::Nginx,
                self::Composer,
                self::MySql,
//                self::Node,
                self::Php,
//                self::Redis,
                self::Certbot,
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
            default => null,
        };
    }
}
