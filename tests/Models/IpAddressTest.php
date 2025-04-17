<?php

use IBroStudio\DataRepository\ValueObjects\Boolean;
use IBroStudio\DataRepository\ValueObjects\Domain;
use IBroStudio\Upcloud\Enums\IpAccessEnum;
use IBroStudio\Upcloud\Enums\IpFamilyEnum;
use IBroStudio\Upcloud\Enums\IpReleasePolicyEnum;
use IBroStudio\Upcloud\Models\IpAddress;
use IBroStudio\Upcloud\Models\UpcloudServer;
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

it('can create an IpAddress model', function () {
    assertModelExists(
        IpAddress::factory()->create()
    );
});

it('can cast properties', function () {
    $ip_address = IpAddress::factory()->create();

    expect(
        $ip_address->access
    )->toBeInstanceOf(IpAccessEnum::class)
        ->and(
            $ip_address->family
        )->toBeInstanceOf(IpFamilyEnum::class)
        ->and(
            $ip_address->floating
        )->toBeInstanceOf(Boolean::class)
        ->and(
            $ip_address->ptr_record
        )->toBeInstanceOf(Domain::class)
        ->and(
            $ip_address->release_policy
        )->toBeInstanceOf(IpReleasePolicyEnum::class)
        ->and(
            $ip_address->is_delegated
        )->toBeInstanceOf(Boolean::class);
});

it('can belong to a server', function () {
    expect(
        IpAddress::factory()->withServer()->create()->server
    )->toBeInstanceOf(UpcloudServer::class);
});

it('can belong to a zone', function () {
    expect(
        IpAddress::factory()->create()->zone
    )->toBeInstanceOf(UpcloudDatacenterZone::class);
});
