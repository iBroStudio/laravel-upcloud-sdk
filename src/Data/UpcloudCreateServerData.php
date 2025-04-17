<?php

namespace IBroStudio\Upcloud\Data;

use IBroStudio\Contracts\Data\Hosting\NewServerData;
use IBroStudio\Contracts\Models\Hosting\DatacenterZone;
use IBroStudio\Contracts\Models\Hosting\ServerPlan;
use IBroStudio\DataRepository\Transformers\ValueObjectTransformer;
use IBroStudio\DataRepository\ValueObjects\Boolean;
use IBroStudio\DataRepository\ValueObjects\Domain;
use IBroStudio\Upcloud\Data\Transformers\DatacenterZoneTransformer;
use IBroStudio\Upcloud\Data\Transformers\ServerPlanTransformer;
use IBroStudio\Upcloud\Data\Transformers\UpcloudBooleanTransformer;
use IBroStudio\Upcloud\Enums\ZonesEnum;
use IBroStudio\Upcloud\Models\UpcloudDatacenterZone;
use IBroStudio\Upcloud\Models\UpcloudServerPlan;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

class UpcloudCreateServerData extends Data implements NewServerData
{
    public function __construct(
        public string $title,
        #[WithTransformer(DatacenterZoneTransformer::class)]
        public DatacenterZone $zone,
        #[WithTransformer(ValueObjectTransformer::class)]
        public Domain $hostname,
        #[WithTransformer(ServerPlanTransformer::class)]
        public ServerPlan $plan,
        public array $storage_devices,
        public array $networking,
        public UpcloudSshUserData $login_user,
        #[WithTransformer(UpcloudBooleanTransformer::class)]
        public Boolean $metadata,
    ) {}
}
