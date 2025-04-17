<?php

namespace IBroStudio\Upcloud\Enums;

use IBroStudio\Contracts\Enums\Hosting\BootOrderValuesEnum;

enum UpcloudBootOrderValuesEnum: string implements BootOrderValuesEnum
{
    case DISK = 'disk';
    case CDROM = 'cdrom';
    case NETWORK = 'network';
}
