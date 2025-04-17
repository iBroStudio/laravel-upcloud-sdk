<?php

namespace IBroStudio\Upcloud\SDK\Requests\Servers;

use IBroStudio\Upcloud\Data\UpcloudServerData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetServer extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected readonly string $uuid) {}

    public function resolveEndpoint(): string
    {
        return "/server/{$this->uuid}";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return UpcloudServerData::from($response);
    }
}
