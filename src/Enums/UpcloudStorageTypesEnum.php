<?php

namespace IBroStudio\Upcloud\Enums;

use IBroStudio\Contracts\Enums\Hosting\StorageTypeEnum;

enum UpcloudStorageTypesEnum: string implements StorageTypeEnum
{
    case DISK = 'disk';
    case CDROM = 'cdrom';
    case TEMPLATE = 'template';
    case BACKUP = 'backup';
}
