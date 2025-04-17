<?php

namespace IBroStudio\Upcloud\Enums;

use IBroStudio\Contracts\Enums\Hosting\RemoteAccessEnabledEnum;

enum UpcloudRemoteAccessEnabledEnum: string implements RemoteAccessEnabledEnum
{
    case YES = 'yes';
    case NO = 'no';
}
