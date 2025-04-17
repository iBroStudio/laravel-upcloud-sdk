<?php

namespace IBroStudio\Upcloud\SDK\Requests\Servers\Plans;

use IBroStudio\Upcloud\Data\UpcloudServerPlanData;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetServerPlans extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/plan';
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        return UpcloudServerPlanData::collectFromUpcloud($response);
    }
}
