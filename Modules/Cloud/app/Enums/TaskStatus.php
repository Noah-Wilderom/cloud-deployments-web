<?php

namespace Modules\Cloud\Enums;

enum TaskStatus: string {

    case Created = "created";
    case Running = "running";
    case Failed = "failed";
    case Success = "success";
}
