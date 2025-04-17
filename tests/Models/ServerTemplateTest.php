<?php

use IBroStudio\DataRepository\ValueObjects\Boolean;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\ByteUnit;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\GigaByteUnit;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\MegaByteUnit;
use IBroStudio\DataRepository\ValueObjects\Uuid;
use IBroStudio\Upcloud\Enums\UpcloudServerPlanCategoriesEnum;
use IBroStudio\Upcloud\Enums\UpcloudStorageAccessTypesEnum;
use IBroStudio\Upcloud\Enums\UpcloudStorageStatesEnum;
use IBroStudio\Upcloud\Enums\UpcloudStorageTypesEnum;
use IBroStudio\Upcloud\Models\UpcloudDatacenterZone;
use IBroStudio\Upcloud\Models\UpcloudServerPlan;
use IBroStudio\Upcloud\Models\UpcloudServerTemplate;
use IBroStudio\Upcloud\SDK\Requests\Servers\Templates\GetServerTemplates;
use Illuminate\Database\Eloquent\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;

beforeEach(function () {
    Saloon::fake([
        GetServerTemplates::class => MockResponse::fixture('server_templates'),
    ]);
});

it('can fetch Server Plans', function () {
    $templates = UpcloudServerTemplate::all();

    expect($templates)->toBeInstanceOf(Collection::class)
        ->and($templates->first())->toBeInstanceOf(UpcloudServerTemplate::class);
});

it('can cast properties', function () {
    $template = UpcloudServerTemplate::first();

    expect(
        $template->uuid
    )->toBeInstanceOf(Uuid::class)
        ->and(
            $template->type
        )->toBeInstanceOf(UpcloudStorageTypesEnum::class)
        ->and(
            $template->size
        )->toBeInstanceOf(GigaByteUnit::class)
        ->and(
            $template->encrypted
        )->toBeInstanceOf(Boolean::class)
        ->and(
            $template->access
        )->toBeInstanceOf(UpcloudStorageAccessTypesEnum::class)
        ->and(
            $template->state
        )->toBeInstanceOf(UpcloudStorageStatesEnum::class);
});

it('can fetch default server template', function () {
    $zone = UpcloudServerTemplate::default()->first();

    expect($zone)->toBeInstanceOf(UpcloudServerTemplate::class)
        ->and($zone->uuid->value)->toBe(config('upcloud-sdk.default.server_template_uuid'));
});
