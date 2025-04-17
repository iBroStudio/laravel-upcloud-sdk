<?php

namespace IBroStudio\Upcloud\SDK\Resources;

use IBroStudio\Contracts\Data\Hosting\ServerData;
use IBroStudio\Contracts\Data\Hosting\ServerPlanData;
use IBroStudio\Upcloud\SDK\Requests\Servers\Plans\GetServerPlans;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Saloon\Http\BaseResource;

class UpcloudServerPlans extends BaseResource
{
    /**
     * @return Collection<int, ServerPlanData>|LazyCollection<int, ServerData>
     */
    public function all(): Collection|LazyCollection
    {
        return $this->connector->send(
            new GetServerPlans
        )->dto();
    }
}
