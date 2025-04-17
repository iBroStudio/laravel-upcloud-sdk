<?php

namespace IBroStudio\Upcloud\SDK;

use IBroStudio\Contracts\SDK\Hosting\HostingProviderSDK;
use IBroStudio\Contracts\SDK\Hosting\Resources\ServerResource;
use IBroStudio\Contracts\SDK\Hosting\Resources\ZoneResource;
use IBroStudio\Upcloud\SDK\Resources\UpcloudServers;
use IBroStudio\Upcloud\SDK\Resources\UpcloudServerTemplates;
use IBroStudio\Upcloud\SDK\Resources\UpcloudZones;
use Saloon\Http\Auth\BasicAuthenticator;
use Saloon\Http\Connector;

class UpcloudSDK extends Connector implements HostingProviderSDK
{
    protected ?string $response = UpcloudResponse::class;

    public function __construct(
        public readonly string $username,
        public readonly string $password
    ) {}

    public function resolveBaseUrl(): string
    {
        return 'https://api.upcloud.com/1.3';
    }

    protected function defaultAuth(): BasicAuthenticator
    {
        return new BasicAuthenticator($this->username, $this->password);
    }

    public function servers(): ServerResource
    {
        return new UpcloudServers($this);
    }

    public function zones(): ZoneResource
    {
        return new UpcloudZones($this);
    }
}
