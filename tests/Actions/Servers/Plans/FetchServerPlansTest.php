<?php

use IBroStudio\Upcloud\Actions\Servers\Plans\FetchServerPlansAction;
use IBroStudio\Upcloud\Data\UpcloudServerPlanData;
use IBroStudio\Upcloud\SDK\Requests\Servers\Plans\GetServerPlans;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;

it('can fetch server plans', function () {
    Saloon::fake([
        GetServerPlans::class => MockResponse::fixture('server_plans'),
    ]);
    $plans = FetchServerPlansAction::run();

    expect($plans)->toBeInstanceOf(Collection::class)
        ->and($plans->first())->toBeInstanceOf(UpcloudServerPlanData::class);
});
