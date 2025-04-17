<?php

use IBroStudio\Contracts\SDK\Hosting\HostingProviderSDK;
use IBroStudio\Upcloud\Data\UpcloudDatacenterZoneData;
use IBroStudio\Upcloud\SDK\Requests\DatacenterZones\GetDatacenterZones;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;

it('can retrieve provider zones', function () {

    dd(

    );

    $mockClient = Saloon::fake([
        GetDatacenterZones::class => MockResponse::fixture('zones'),
    ]);
    $provider = App::make(HostingProviderSDK::class);
    $provider->withMockClient($mockClient);
    $zones = $provider->zones()->all();
    dd($zones);
    expect($zones)->toBeInstanceOf(Collection::class)
        ->and($zones->first())->toBeInstanceOf(UpcloudDatacenterZoneData::class);
});
