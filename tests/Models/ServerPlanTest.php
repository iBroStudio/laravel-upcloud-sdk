<?php

use IBroStudio\DataRepository\ValueObjects\Units\Byte\ByteUnit;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\MegaByteUnit;
use IBroStudio\Upcloud\Enums\UpcloudServerPlanCategoriesEnum;
use IBroStudio\Upcloud\Models\UpcloudDatacenterZone;
use IBroStudio\Upcloud\Models\UpcloudServerPlan;
use Illuminate\Database\Eloquent\Collection;
use IBroStudio\Upcloud\SDK\Requests\DatacenterZones\GetDatacenterZones;
use IBroStudio\Upcloud\SDK\Requests\Servers\Plans\GetServerPlans;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;

beforeEach(function () {
    Saloon::fake([
        GetServerPlans::class => MockResponse::fixture('server_plans'),
    ]);
});

it('can fetch Server Plans', function () {
    $plans = UpcloudServerPlan::all();

    expect($plans)->toBeInstanceOf(Collection::class)
        ->and($plans->first())->toBeInstanceOf(UpcloudServerPlan::class);
});

it('can cast properties', function () {
    $plan = UpcloudServerPlan::first();

    expect(
        $plan->category
    )->toBeInstanceOf(UpcloudServerPlanCategoriesEnum::class)
        ->and(
            $plan->memory_amount
        )->toBeInstanceOf(ByteUnit::class)
        ->and(
            $plan->storage_size
        )->toBeInstanceOf(ByteUnit::class)
        ->and(
            $plan->public_traffic_out
        )->toBeInstanceOf(ByteUnit::class);
});
