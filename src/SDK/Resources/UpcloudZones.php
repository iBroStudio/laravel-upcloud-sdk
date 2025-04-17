<?php

namespace IBroStudio\Upcloud\SDK\Resources;

use IBroStudio\Contracts\Data\Hosting\ZoneData;
use IBroStudio\Contracts\SDK\Hosting\Resources\ZoneResource;
use IBroStudio\Upcloud\SDK\Requests\DatacenterZones\GetDatacenterZones;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Saloon\Http\BaseResource;

class UpcloudZones extends BaseResource implements ZoneResource
{
    /**
     * @return Collection<int, ZoneData>|LazyCollection<int, ZoneData>
     */
    public function all(): Collection|LazyCollection
    {
        return $this->connector->send(
            new GetDatacenterZones
        )->dto();
    }
}
