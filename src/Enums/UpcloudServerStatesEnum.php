<?php

namespace IBroStudio\Upcloud\Enums;

use IBroStudio\Contracts\Enums\Hosting\ServerStatesEnum;

enum UpcloudServerStatesEnum: string implements ServerStatesEnum
{
    case STARTED = 'started'; // The server is running.
    case STOPPED = 'stopped'; // The server is stopped.
    case MAINTENANCE = 'maintenance'; // The server is in maintenance mode.
    case ERROR = 'error'; // The server has encountered an error. This means the server is inaccessible due to an error in the cloud service.
}
