<?php

namespace IBroStudio\Upcloud\Models;

use IBroStudio\Contracts\Models\Hosting\ServerPlan;
use IBroStudio\DataRepository\EloquentCasts\ValueObjectCast;
use IBroStudio\DataRepository\Enums\Timezones;
use IBroStudio\DataRepository\ValueObjects\Boolean;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\ByteUnit;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\GigaByteUnit;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\MegaByteUnit;
use IBroStudio\DataRepository\ValueObjects\Uuid;
use IBroStudio\Upcloud\Actions\Servers\Plans\FetchServerPlansAction;
use IBroStudio\Upcloud\Enums\UpcloudServerPlanCategoriesEnum;
use IBroStudio\Upcloud\Enums\UpcloudServerStatesEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Sushi\Sushi;

class UpcloudServerPlan extends Model implements ServerPlan
{
    use Sushi;

    protected $keyType = 'string';

    protected $schema = [
    ];

    public function getRows()
    {
        return Cache::remember('server_plans', now()->addMonth(), function () {
            return FetchServerPlansAction::run()->toArray();
        });
    }

    protected function casts(): array
    {
        return [
            'category' => UpcloudServerPlanCategoriesEnum::class,
            'memory_amount' => ValueObjectCast::class.':'.ByteUnit::class,
            'storage_size' => ValueObjectCast::class.':'.ByteUnit::class,
            'public_traffic_out' => ValueObjectCast::class.':'.ByteUnit::class,
        ];
    }
}
