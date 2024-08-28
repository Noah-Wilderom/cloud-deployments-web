<?php

namespace Modules\Cloud\Enums;

enum DeploymentStatus: string {

    case Pending = "pending";
    case Provisioning = "provisioning";
    case Failed = "failed";
    case Success = "success";
}
