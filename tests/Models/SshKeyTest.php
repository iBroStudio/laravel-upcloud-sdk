<?php

use IBroStudio\DataRepository\Enums\Timezones;
use IBroStudio\DataRepository\ValueObjects\Authentication\SshKey;
use IBroStudio\DataRepository\ValueObjects\Boolean;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\GigaByteUnit;
use IBroStudio\DataRepository\ValueObjects\Uuid;
use IBroStudio\Upcloud\Enums\UpcloudServerStatesEnum;
use IBroStudio\Upcloud\Models\IpAddress;
use IBroStudio\Upcloud\Models\UpcloudServer;
use IBroStudio\Upcloud\Models\Storage;
use IBroStudio\Upcloud\Models\UpcloudDatacenterZone;
use IBroStudio\Upcloud\Models\UpcloudSshKey;
use Illuminate\Database\Eloquent\Collection;
use IBroStudio\Upcloud\SDK\Requests\DatacenterZones\GetDatacenterZones;
use IBroStudio\Upcloud\SDK\Requests\Servers\Plans\GetServerPlans;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;

use function Pest\Laravel\assertModelExists;

it('can create a SshKey model', function () {
    assertModelExists(UpcloudSshKey::factory()->create());
});

it('can cast properties', function () {
    expect(
        UpcloudSshKey::factory()->create()->key
    )->toBeInstanceOf(SshKey::class);
});

it('can be associated to a server', function () {
    Saloon::fake([
        GetDatacenterZones::class => MockResponse::fixture('zones'),
        GetServerPlans::class => MockResponse::fixture('server_plans'),
    ]);

    $ssh = UpcloudSshKey::factory()
        ->has(UpcloudServer::factory(), 'servers')
        ->create();

    expect(
        $ssh->servers
    )->toBeInstanceOf(Collection::class)
        ->and(
            $ssh->servers->first()
        )->toBeInstanceOf(UpcloudServer::class);
});

it('can scope SshKey to autodeployed keys', function () {
    UpcloudSshKey::factory()->count(2)->create(['autodeploy' => true]);
    UpcloudSshKey::factory()->create(['autodeploy' => false]);

    expect(UpcloudSshKey::count())->toBe(3);

    $ssh = UpcloudSshKey::autodeployed()->get();

    expect($ssh)->toBeInstanceOf(Collection::class)
        ->and($ssh->count())->toBe(2)
        ->and($ssh->first())->toBeInstanceOf(UpcloudSshKey::class)
        ->and($ssh->first()->autodeploy)->toBeTrue();
});

it('can scope SshKey to a specified ssh user', function () {
    UpcloudSshKey::factory()->create();
    UpcloudSshKey::factory()->withSshUser('upclouduser')->create();

    $ssh = UpcloudSshKey::forSshUser('upclouduser')->get();

    expect($ssh)->toBeInstanceOf(Collection::class)
        ->and($ssh->count())->toBe(1)
        ->and($ssh->first()->key->user)->toBe('upclouduser');
});
