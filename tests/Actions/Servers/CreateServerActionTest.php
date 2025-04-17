<?php

use IBroStudio\Contracts\Enums\Hosting\StorageAccessTypesEnum;
use IBroStudio\Contracts\Enums\Hosting\StorageTiersEnum;
use IBroStudio\Contracts\Models\Hosting\DatacenterZone;
use IBroStudio\DataRepository\Enums\ByteUnitEnum;
use IBroStudio\DataRepository\ValueObjects\Boolean;
use IBroStudio\DataRepository\ValueObjects\Domain;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\ByteUnit;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\GigaByteUnit;
use IBroStudio\Upcloud\Actions\Servers\CreateServerAction;
use IBroStudio\Upcloud\Data\UpcloudCreateServerData;
use IBroStudio\Upcloud\Data\UpcloudSshUserData;
use IBroStudio\Upcloud\Data\UpcloudStorageData;
use IBroStudio\Upcloud\Enums\IpAccessEnum;
use IBroStudio\Upcloud\Enums\IpFamilyEnum;
use IBroStudio\Upcloud\Enums\UpcloudServerPlanCategoriesEnum;
use IBroStudio\Upcloud\Enums\UpcloudStorageAccessTypesEnum;
use IBroStudio\Upcloud\Enums\UpcloudStorageTiersEnum;
use IBroStudio\Upcloud\Events\ServerCreatedEvent;
use IBroStudio\Upcloud\Models\UpcloudDatacenterZone;
use IBroStudio\Upcloud\Models\UpcloudServerPlan;
use IBroStudio\Upcloud\Models\UpcloudServerTemplate;
use IBroStudio\Upcloud\Models\UpcloudSshKey;
use IBroStudio\Upcloud\SDK\Requests\Servers\CreateServerRequest;
use IBroStudio\Upcloud\SDK\Requests\Servers\Templates\GetServerTemplates;
use IBroStudio\Upcloud\States\Server\Maintenance;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use IBroStudio\Upcloud\SDK\Requests\DatacenterZones\GetDatacenterZones;
use IBroStudio\Upcloud\SDK\Requests\Servers\Plans\GetServerPlans;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;

use function Pest\Laravel\assertModelExists;

//*
beforeEach(function () {
    Saloon::fake([
        GetDatacenterZones::class => MockResponse::fixture('zones'),
        GetServerPlans::class => MockResponse::fixture('server_plans'),
        GetServerTemplates::class => MockResponse::fixture('server_templates'),
        CreateServerRequest::class => MockResponse::fixture('create_server'),
    ]);
});
//*/

it('can create a Server', function () {
    $reference = time();
    $datacenter_zone = UpcloudDatacenterZone::default()->first();
    UpcloudSshKey::factory()->withSshUser('upclouduser')->create();
    Event::fake();

    $server = CreateServerAction::run(
        UpcloudCreateServerData::from([
            'title' => 'Test Server '.$reference,
            'zone' => $datacenter_zone,
            'hostname' => Domain::from('test'.$reference.'.ibro.studio'),
            'plan' => UpcloudServerPlan::where('category', UpcloudServerPlanCategoriesEnum::CLOUDNATIVE)
                ->first(),
            'storage_devices' => [
                'storage_device' => UpcloudStorageData::collect([
                    UpcloudStorageData::create(
                        title: 'disk-os',
                        tier: UpcloudStorageTiersEnum::MAXIOPS,
                        size: GigaByteUnit::from(10),
                        encrypted: Boolean::from(false),
                        zone: $datacenter_zone,
                        access: UpcloudStorageAccessTypesEnum::PUBLIC,
                    )->withTemplate(UpcloudServerTemplate::default()->first())
                ], Collection::class)
            ],
            'networking' => [
              'interfaces' => [
                  'interface' => [
                      [
                          'ip_addresses' => [
                              'ip_address' => [
                                  ['family' => IpFamilyEnum::IPV4->value]
                              ]
                          ],
                          'type' => IpAccessEnum::PUBLIC->value
                      ],
                      [
                          'ip_addresses' => [
                              'ip_address' => [
                                  ['family' => IpFamilyEnum::IPV6->value]
                              ]
                          ],
                          'type' => IpAccessEnum::PUBLIC->value
                      ],
                      [
                          'ip_addresses' => [
                              'ip_address' => [
                                  ['family' => IpFamilyEnum::IPV4->value]
                              ]
                          ],
                          'type' => IpAccessEnum::UTILITY->value
                      ]
                  ]
              ]
            ],
            'login_user' => UpcloudSshUserData::forSshUser('upclouduser'),
            'metadata' => Boolean::from(true)
        ])
    );

    assertModelExists($server);

    Event::assertDispatched(ServerCreatedEvent::class);
});
