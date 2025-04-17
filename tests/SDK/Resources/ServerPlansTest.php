<?php

use IBroStudio\Contracts\SDK\Hosting\HostingProviderSDK;
use IBroStudio\Upcloud\Data\UpcloudServerData;
use IBroStudio\Upcloud\Enums\UpcloudServerPlanCategoriesEnum;
use IBroStudio\Upcloud\SDK\Requests\Servers\Plans\GetServerPlans;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;

it('can retrieve all server plans', function () {
    $mockClient = Saloon::fake([
        GetServerPlans::class => MockResponse::fixture('server_plans'),
    ]);
    $provider = App::make(HostingProviderSDK::class);
    $provider->withMockClient($mockClient);
    $plans = $provider->servers()->plans()->all();
    dd($plans);
    expect($servers)->toBeInstanceOf(Collection::class)
        ->and($servers->first())->toBeInstanceOf(UpcloudServerData::class);
});

/*
 * CLOUDNATIVE-1xCPU-4GB
 * DEV-1xCPU-2GB
 * 1xCPU-1GB
 * HIMEM-2xCPU-16GB
 */
