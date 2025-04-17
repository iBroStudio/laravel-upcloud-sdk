<?php

namespace IBroStudio\Upcloud\SDK\Resources;

use IBroStudio\Contracts\Data\Hosting\NewServerData;
use IBroStudio\Contracts\Data\Hosting\ServerData;
use IBroStudio\Contracts\SDK\Hosting\Resources\ServerResource;
use IBroStudio\Upcloud\SDK\Requests\Servers\CreateServerRequest;
use IBroStudio\Upcloud\SDK\Requests\Servers\GetServer;
use IBroStudio\Upcloud\SDK\Requests\Servers\GetServers;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Saloon\Http\BaseResource;

class UpcloudServers extends BaseResource implements ServerResource
{
    /**
     * @return Collection<int, ServerData>|LazyCollection<int, ServerData>
     */
    public function all(): Collection|LazyCollection
    {
        return $this->connector->send(
            new GetServers
        )->dto();
    }

    public function get(int|string $id): ServerData
    {
        return $this->connector->send(
            new GetServer($id)
        )->dtoOrFail();
    }

    public function create(NewServerData $serverData): ServerData
    {
        return $this->connector->send(
            new CreateServerRequest($serverData)
        )->dtoOrFail();
    }

    public function plans()
    {
        return new UpcloudServerPlans($this->connector);
    }

    public function templates()
    {
        return new UpcloudServerTemplates($this->connector);
    }
}
