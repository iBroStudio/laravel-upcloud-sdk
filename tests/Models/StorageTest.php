<?php

use IBroStudio\DataRepository\ValueObjects\Units\Byte\GigaByteUnit;
use IBroStudio\DataRepository\ValueObjects\Uuid;
use IBroStudio\Upcloud\Enums\UpcloudStorageStatesEnum;
use IBroStudio\Upcloud\Enums\UpcloudStorageTiersEnum;
use IBroStudio\Upcloud\Models\UpcloudServer;
use IBroStudio\Upcloud\Models\Storage;
use IBroStudio\Upcloud\Models\UpcloudDatacenterZone;
use IBroStudio\Upcloud\SDK\Requests\DatacenterZones\GetDatacenterZones;
use IBroStudio\Upcloud\SDK\Requests\Servers\Plans\GetServerPlans;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;

use function Pest\Laravel\assertModelExists;

beforeEach(function () {
    Saloon::fake([
        GetDatacenterZones::class => MockResponse::fixture('zones'),
        GetServerPlans::class => MockResponse::fixture('server_plans'),
    ]);
});

it('can create a Storage model', function () {
    assertModelExists(
        Storage::factory()->create()
    );
});

it('can cast properties', function () {
    $storage = Storage::factory()->create();

    expect(
        $storage->uuid
    )->toBeInstanceOf(Uuid::class)
        ->and(
            $storage->size
        )->toBeInstanceOf(GigaByteUnit::class)
        ->and(
            $storage->tier
        )->toBeInstanceOf(UpcloudStorageTiersEnum::class)
        ->and(
            $storage->state
        )->toBeInstanceOf(UpcloudStorageStatesEnum::class);
});

it('can belong to a server', function () {
    expect(
        Storage::factory()->withServer()->create()->server
    )->toBeInstanceOf(UpcloudServer::class);
});

it('can belong to a zone', function () {
    expect(
        Storage::factory()->create()->zone
    )->toBeInstanceOf(UpcloudDatacenterZone::class);
});
