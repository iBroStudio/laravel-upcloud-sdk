<?php

namespace IBroStudio\Upcloud\SDK\Requests\Servers\Templates;

use IBroStudio\Upcloud\Data\UpcloudStorageData;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetServerTemplates extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/storage/template';
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        return UpcloudStorageData::collectFromUpcloud($response);
    }
}
