<?php

namespace Modules\Cloud\Enums;

enum ServerStatus: string {
    case Created = "created";
    case Dispatched = "dispatched";
    case Provisioning = "provisioning";
    case Failed = "failed";

    case Online = "online";
    case Offline = "offline";
}
