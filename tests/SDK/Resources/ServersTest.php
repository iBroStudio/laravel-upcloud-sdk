<?php

use IBroStudio\Contracts\SDK\Hosting\HostingProviderSDK;
use IBroStudio\Upcloud\Data\UpcloudServerData;
use IBroStudio\Upcloud\SDK\Requests\DatacenterZones\GetDatacenterZones;
use IBroStudio\Upcloud\SDK\Requests\Servers\GetServer;
use IBroStudio\Upcloud\SDK\Requests\Servers\GetServers;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;

it('can retrieve a server', function () {
    $mockClient = Saloon::fake([
        GetServer::class => MockResponse::fixture('server'),
        GetDatacenterZones::class => MockResponse::fixture('zones'),
    ]);
    $provider = App::make(HostingProviderSDK::class);
    $provider->withMockClient($mockClient);
    $server = $provider->servers()->get('00492b81-6533-4208-9d1e-d511a2623f4d');
    dd($server);
    expect($server)->toBeInstanceOf(UpcloudServerData::class);
});

it('can retrieve all servers', function () {
    $mockClient = Saloon::fake([
        GetServers::class => MockResponse::fixture('servers'),
    ]);
    $provider = App::make(HostingProviderSDK::class);
    $provider->withMockClient($mockClient);
    $servers = $provider->servers()->all();

    expect($servers)->toBeInstanceOf(Collection::class)
        ->and($servers->first())->toBeInstanceOf(UpcloudServerData::class);
});
