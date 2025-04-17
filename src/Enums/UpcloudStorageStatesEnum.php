<?php

namespace IBroStudio\Upcloud\Enums;

use IBroStudio\Contracts\Enums\Hosting\StorageStatesEnum;

enum UpcloudStorageStatesEnum: string implements StorageStatesEnum
{
    case ONLINE = 'online'; // The storage resource is ready for use. The storage can be attached or detached.
    case MAINTENANCE = 'maintenance'; // Maintenance work is currently performed on the storage. The storage may have been newly created or it is being updated by some API operation.
    case CLONING = 'cloning'; // The storage resource is currently the clone source for another storage.
    case BACKUPING = 'backuping'; // The storage resource is currently being backed up to another storage.
    case SYNCING = 'syncing'; // The storage resource is currently synchronizing its data.
    case ERROR = 'error'; // The storage has encountered an error and is currently inaccessible.
}
