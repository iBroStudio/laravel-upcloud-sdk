<?php

namespace IBroStudio\Upcloud\Enums;

use IBroStudio\Contracts\Enums\Hosting\StorageTiersEnum;

enum UpcloudStorageTiersEnum: string implements StorageTiersEnum
{
    case MAXIOPS = 'maxiops'; // High performance storage
    case STANDARD = 'standard'; // General purpose storage
    case ARCHIVE = 'hdd'; // High capacity storage
}
