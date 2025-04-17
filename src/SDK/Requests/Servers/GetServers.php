<?php

namespace IBroStudio\Upcloud\SDK\Requests\Servers;

use IBroStudio\Upcloud\Data\UpcloudServerData;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetServers extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/server';
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        return UpcloudServerData::collectFromUpcloud($response);
    }
}
