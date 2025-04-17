<?php

namespace IBroStudio\Upcloud\Enums;

use IBroStudio\Contracts\Enums\Hosting\StorageAccessTypesEnum;

enum UpcloudStorageAccessTypesEnum: string implements StorageAccessTypesEnum
{
    case PUBLIC = 'public';
    case PRIVATE = 'private';
}
