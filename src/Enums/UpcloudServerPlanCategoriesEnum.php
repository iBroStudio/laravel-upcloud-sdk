<?php

namespace IBroStudio\Upcloud\Enums;

use IBroStudio\Contracts\Enums\Hosting\ServerPlanCategoriesEnum;

enum UpcloudServerPlanCategoriesEnum: string implements ServerPlanCategoriesEnum
{
    case STANDARD = 'standard';
    case CLOUDNATIVE = 'cloudnative';
    case DEV = 'dev';
    case HIMEM = 'himem';
    case HICPU = 'hicpu';

    public function label(): string
    {
        return match ($this) {
            self::STANDARD => 'Standard',
            self::CLOUDNATIVE => 'Cloud Native',
            self::DEV => 'Developer',
            self::HIMEM => 'High Memory',
            self::HICPU => 'High CPU',
            default => throw new \Exception('Unknown enum value requested for the label'),
        };
    }
}
