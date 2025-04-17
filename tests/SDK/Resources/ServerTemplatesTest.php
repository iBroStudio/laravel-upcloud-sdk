<?php

use IBroStudio\Contracts\SDK\Hosting\HostingProviderSDK;
use IBroStudio\Upcloud\Data\UpcloudDatacenterZoneData;
use IBroStudio\Upcloud\Data\UpcloudStorageData;
use IBroStudio\Upcloud\Enums\UpcloudStorageTypesEnum;
use IBroStudio\Upcloud\SDK\Requests\Servers\Templates\GetServerTemplates;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;

it('can fetch server templates', function () {
    $mockClient = Saloon::fake([
        GetServerTemplates::class => MockResponse::fixture('server_templates'),
    ]);
    $provider = App::make(HostingProviderSDK::class);
    $provider->withMockClient($mockClient);
    $templates = $provider->servers()->templates()->all();

    expect($templates)->toBeInstanceOf(Collection::class)
        ->and($templates->first())->toBeInstanceOf(UpcloudStorageData::class)
        ->and($templates->first())->toHaveProperty('type', UpcloudStorageTypesEnum::TEMPLATE);
});
