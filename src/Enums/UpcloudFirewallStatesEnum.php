<?php

namespace IBroStudio\Upcloud\Enums;

use IBroStudio\Contracts\Enums\Hosting\FirewallStatesEnum;

enum UpcloudFirewallStatesEnum: string implements FirewallStatesEnum
{
    case ON = 'on';
    case OFF = 'off';
}
