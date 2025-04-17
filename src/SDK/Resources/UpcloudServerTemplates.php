<?php

namespace IBroStudio\Upcloud\SDK\Resources;

use IBroStudio\Contracts\Data\Hosting\StorageData;
use IBroStudio\Contracts\SDK\Hosting\Resources\ZoneResource;
use IBroStudio\Upcloud\SDK\Requests\Servers\Templates\GetServerTemplates;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Saloon\Http\BaseResource;

class UpcloudServerTemplates extends BaseResource implements ZoneResource
{
    /**
     * @return Collection<int, StorageData>|LazyCollection<int, StorageData>
     */
    public function all(): Collection|LazyCollection
    {
        return $this->connector->send(
            new GetServerTemplates
        )->dto();
    }
}
