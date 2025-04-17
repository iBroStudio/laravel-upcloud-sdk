<?php

namespace IBroStudio\Upcloud\SDK\Requests\DatacenterZones;

use IBroStudio\Upcloud\Data\UpcloudDatacenterZoneData;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetDatacenterZones extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/zone';
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        return UpcloudDatacenterZoneData::collectFromUpcloud($response);
    }
}
