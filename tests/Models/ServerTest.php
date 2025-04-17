<?php

use IBroStudio\DataRepository\Enums\Timezones;
use IBroStudio\DataRepository\ValueObjects\Boolean;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\GigaByteUnit;
use IBroStudio\DataRepository\ValueObjects\Uuid;
use IBroStudio\Upcloud\Enums\UpcloudServerStatesEnum;
use IBroStudio\Upcloud\Models\IpAddress;
use IBroStudio\Upcloud\Models\UpcloudServer;
use IBroStudio\Upcloud\Models\Storage;
use IBroStudio\Upcloud\Models\UpcloudDatacenterZone;
use IBroStudio\Upcloud\Models\UpcloudSshKey;
use IBroStudio\Upcloud\States\Server\ServerState;
use Illuminate\Database\Eloquent\Collection;
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

it('can create a Server model', function () {
    assertModelExists(
        UpcloudServer::factory()->create()
    );
});

it('can cast properties', function () {
    $server = UpcloudServer::factory()->create();

    expect(
        $server->uuid
    )->toBeInstanceOf(Uuid::class)
        ->and(
            $server->memory_amount
        )->toBeInstanceOf(GigaByteUnit::class)
        ->and(
            $server->server_group
        )->toBeInstanceOf(Uuid::class)
        ->and(
            $server->timezone
        )->toBeInstanceOf(Timezones::class)
        ->and(
            $server->firewall
        )->toBeInstanceOf(Boolean::class)
        ->and(
            $server->state
        )->toBeInstanceOf(UpcloudServerStatesEnum::class);
});

it('can have IP addresses', function () {
    $server = UpcloudServer::factory()
        ->has(IpAddress::factory()->count(2))
        ->create();

    expect($server->ipAddresses)->toBeInstanceOf(Collection::class)
        ->and($server->ipAddresses->first())->toBeInstanceOf(IpAddress::class);
});

it('can have storages', function () {
    $server = UpcloudServer::factory()
        ->has(Storage::factory()->count(2))
        ->create();

    expect($server->storages)->toBeInstanceOf(Collection::class)
        ->and($server->storages->first())->toBeInstanceOf(Storage::class);
});

it('can belong to a zone', function () {
    expect(
        UpcloudServer::factory()->create()->zone
    )->toBeInstanceOf(UpcloudDatacenterZone::class);
});

it('can be associated with ssh keys', function () {
    $server = UpcloudServer::factory()
        ->has(UpcloudSshKey::factory(), 'ssh_keys')
        ->create();

    expect(
        $server->ssh_keys
    )->toBeInstanceOf(Collection::class)
        ->and(
            $server->ssh_keys->first()
        )->toBeInstanceOf(UpcloudSshKey::class);
});
