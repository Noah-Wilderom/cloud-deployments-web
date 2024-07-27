<?php

namespace Modules\Cloud\Tasks;

abstract class RestartService extends Task {
    protected int $timeout = 30;
    protected string $service;

    public function command(): string {
        return sprintf("systemctl restart %s", $this->service);
    }

    public function name(): string {
        return sprintf("Reload %s", $this->service);
    }
}
