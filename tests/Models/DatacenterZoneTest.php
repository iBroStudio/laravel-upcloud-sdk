<?php

use IBroStudio\Upcloud\Models\UpcloudDatacenterZone;
use IBroStudio\Upcloud\SDK\Requests\DatacenterZones\GetDatacenterZones;
use Illuminate\Database\Eloquent\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;

beforeEach(function () {
    Saloon::fake([
        GetDatacenterZones::class => MockResponse::fixture('zones'),
    ]);
});

it('can fetch Zones', function () {
    $zones = UpcloudDatacenterZone::all();

    expect($zones)->toBeInstanceOf(Collection::class)
        ->and($zones->first())->toBeInstanceOf(UpcloudDatacenterZone::class);
});

it('can fetch public Zones', function () {
    $zones = UpcloudDatacenterZone::public()->get();

    expect($zones)->toBeInstanceOf(Collection::class)
        ->and($zones->first())->toBeInstanceOf(UpcloudDatacenterZone::class);
});


it('can fetch default zone', function () {
    $zone = UpcloudDatacenterZone::default()->first();

    expect($zone)->toBeInstanceOf(UpcloudDatacenterZone::class)
        ->and($zone->id)->toBe(config('upcloud-sdk.default.datacenter'));
});
