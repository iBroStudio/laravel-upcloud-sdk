<?php

use IBroStudio\Upcloud\Actions\Servers\Plans\FetchServerPlansAction;
use IBroStudio\Upcloud\Actions\Servers\Templates\FetchServerTemplatesAction;
use IBroStudio\Upcloud\Data\UpcloudServerPlanData;
use IBroStudio\Upcloud\Data\UpcloudStorageData;
use IBroStudio\Upcloud\Enums\UpcloudStorageTypesEnum;
use IBroStudio\Upcloud\SDK\Requests\Servers\Templates\GetServerTemplates;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;

it('can fetch server templates', function () {
    Saloon::fake([
        GetServerTemplates::class => MockResponse::fixture('server_templates'),
    ]);

    $templates = FetchServerTemplatesAction::run();

    expect($templates)->toBeInstanceOf(Collection::class)
        ->and($templates->first())->toBeInstanceOf(UpcloudStorageData::class)
        ->and($templates->first())->toHaveProperty('type', UpcloudStorageTypesEnum::TEMPLATE);
});
