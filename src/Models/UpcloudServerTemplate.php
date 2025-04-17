<?php

namespace IBroStudio\Upcloud\Models;

use IBroStudio\Contracts\Models\Hosting\ServerTemplate;
use IBroStudio\DataRepository\EloquentCasts\ValueObjectCast;
use IBroStudio\DataRepository\Enums\Timezones;
use IBroStudio\DataRepository\ValueObjects\Boolean;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\ByteUnit;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\GigaByteUnit;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\MegaByteUnit;
use IBroStudio\DataRepository\ValueObjects\Uuid;
use IBroStudio\Upcloud\Actions\Servers\Plans\FetchServerPlansAction;
use IBroStudio\Upcloud\Actions\Servers\Templates\FetchServerTemplatesAction;
use IBroStudio\Upcloud\Enums\UpcloudStorageAccessTypesEnum;
use IBroStudio\Upcloud\Enums\UpcloudStorageStatesEnum;
use IBroStudio\Upcloud\Enums\UpcloudStorageTiersEnum;
use IBroStudio\Upcloud\Enums\UpcloudServerPlanCategoriesEnum;
use IBroStudio\Upcloud\Enums\UpcloudServerStatesEnum;
use IBroStudio\Upcloud\Enums\UpcloudStorageTypesEnum;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Sushi\Sushi;

class UpcloudServerTemplate extends Model implements ServerTemplate
{
    use Sushi;

    protected $keyType = 'string';

    public function getRows()
    {
        return Cache::remember('server_templates', now()->addMonth(), function () {
            $templates = FetchServerTemplatesAction::run()->toArray();

            return Arr::map($templates, function (array $template) {
                $template['labels'] = json_encode($template['labels']);
                return $template;
            });
        });
    }

    protected function casts(): array
    {
        return [
            'uuid' => ValueObjectCast::class.':'.Uuid::class,
            'type' => UpcloudStorageTypesEnum::class,
            'size' => ValueObjectCast::class.':'.GigaByteUnit::class,
            'encrypted' => ValueObjectCast::class.':'.Boolean::class,
            'access' => UpcloudStorageAccessTypesEnum::class,
            'state' => UpcloudStorageStatesEnum::class,
            'labels' => 'array',
        ];
    }

    #[Scope]
    protected function default(Builder $query): void
    {
        $query->where('uuid', config('upcloud-sdk.default.server_template_uuid'));
    }
}
