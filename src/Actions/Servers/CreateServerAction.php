<?php

namespace IBroStudio\Upcloud\Actions\Servers;

use IBroStudio\Contracts\SDK\Hosting\HostingProviderSDK;
use IBroStudio\Upcloud\Data\UpcloudCreateServerData;
use IBroStudio\Upcloud\Data\UpcloudStorageData;
use IBroStudio\Upcloud\Events\ServerCreatedEvent;
use IBroStudio\Upcloud\Models\UpcloudServer;
use IBroStudio\Upcloud\Models\UpcloudDatacenterZone;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateServerAction
{
    use AsAction;

    public function __construct(private HostingProviderSDK $provider) {}

    public function handle(UpcloudCreateServerData $createServerData)
    {
        $serverData = $this->provider->servers()->create($createServerData);

        $server = UpcloudServer::create([
            ...$serverData->toArray(),
            'plan_id' => $serverData->plan->id,
            'zone_id' => $serverData->zone->id,
        ]);

        ServerCreatedEvent::dispatch($server);

        return $server;
    }
}
