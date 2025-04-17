<?php

namespace IBroStudio\Upcloud\Data;

use IBroStudio\Contracts\Data\Hosting\StorageData;
use IBroStudio\Contracts\Enums\Hosting\StorageAccessTypesEnum;
use IBroStudio\Contracts\Enums\Hosting\StorageStatesEnum;
use IBroStudio\Contracts\Enums\Hosting\StorageTiersEnum;
use IBroStudio\Contracts\Enums\Hosting\StorageTypeEnum;
use IBroStudio\Contracts\Models\Hosting\DatacenterZone;
use IBroStudio\DataRepository\Transformers\ValueObjectTransformer;
use IBroStudio\DataRepository\ValueObjects\Boolean;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\GigaByteUnit;
use IBroStudio\DataRepository\ValueObjects\Uuid;
use IBroStudio\Upcloud\Concerns\DataHasUuid;
use IBroStudio\Upcloud\Concerns\DataHasZone;
use IBroStudio\Upcloud\Data\Transformers\DatacenterZoneTransformer;
use IBroStudio\Upcloud\Data\Transformers\UpcloudBooleanTransformer;
use IBroStudio\Upcloud\Enums\UpcloudStorageAccessTypesEnum;
use IBroStudio\Upcloud\Enums\UpcloudStorageStatesEnum;
use IBroStudio\Upcloud\Enums\UpcloudStorageTiersEnum;
use IBroStudio\Upcloud\Enums\UpcloudStorageTypesEnum;
use IBroStudio\Upcloud\Models\UpcloudServerTemplate;
use IBroStudio\Upcloud\SDK\UpcloudResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Saloon\Http\Response;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpcloudStorageData extends Data implements StorageData
{
    use DataHasUuid;
    use DataHasZone;

    private static array $optionnals = [
        'tier', 'zone', 'state', 'template_type', 'backup_rule', 'backups', 'labels'
    ];

    public function __construct(
        #[MapInputName('storage')]
        #[WithTransformer(ValueObjectTransformer::class)]
        public Uuid|string|Optional              $uuid,
        public StorageTypeEnum|string            $type,
        #[MapInputName('storage_size')]
        #[WithTransformer(ValueObjectTransformer::class)]
        public GigaByteUnit|int                  $size,
        #[MapInputName('storage_tier')]
        public StorageTiersEnum|string|Optional  $tier,
        #[MapInputName('storage_title')]
        public string                            $title,
        #[MapInputName('storage_encrypted')]
        #[WithTransformer(UpcloudBooleanTransformer::class)]
        public Boolean|string                    $encrypted,
        #[WithCast(EnumCast::class)]
        #[WithTransformer(DatacenterZoneTransformer::class)]
        public DatacenterZone|string|Optional    $zone,
        public StorageAccessTypesEnum|string|null     $access,
        public StorageStatesEnum|string|Optional $state,
        public string|Optional                   $template_type,
        public array|Optional                    $backup_rule,
        public array|Optional                    $backups,
        public array|Optional                    $labels,
        public float|Optional                    $license,
    )
    {
        $this
            ->_access()
            ->_encrypted()
            ->_size()
            ->_state()
            ->_tier()
            ->_type()
            ->_uuid()
            ->_zone();
    }

    public static function fromServerTemplateModel(
        UpcloudServerTemplate $template,
        ?StorageTiersEnum $tier = null,
        ?GigaByteUnit $size = null): self
    {
        return new self(
            uuid: $template->uuid,
            type: $template->type,
            size: $size ?? $template->size,
            tier: $tier ?? Optional::create(),
            title: $template->title,
            encrypted: $template->encrypted,
            zone: Optional::create(),
            access: $template->access,
            state: $template->state,
            template_type: $template->template_type,
            backup_rule: Optional::create(),
            backups: Optional::create(),
            labels: $template->labels,
            license: $template->license,
        );
    }

    public static function fromUpcloud(UpcloudResponse $response): self
    {
        return new self(...$response->json()['storage']);
    }

    public static function collectFromUpcloud(UpcloudResponse|Response $response): Collection
    {
        return collect(
            Arr::map($response->json()['storages']['storage'], function (array $data) {
                $data = static::addOptionnals($data);
                return new self(...$data);
            })
        );
    }

    public function withTemplate(UpcloudServerTemplate $template): self
    {
        return self::from([
            ...$this->toArray(),
            "uuid" => $template->uuid,
            "type" => $template->type,
            "zone" => Optional::create(),
            "access" => $template->access,
            "state" => $template->state,
            "template_type" => $template->template_type,
            "backup_rule" => Optional::create(),
            "backups" => Optional::create(),
            "labels" => $template->labels,
            "license" => $template->license,
        ])
            ->except('access', 'state', 'license', 'template_type', 'uuid', 'type')
            ->additional([
                'action' => 'clone',
                'storage' => $template->uuid->value
            ]);
    }

    public static function create(
        string $title,
        StorageTiersEnum $tier,
        GigaByteUnit $size,
        Boolean $encrypted,
        DatacenterZone $zone,
        StorageAccessTypesEnum $access,
        StorageTypeEnum $type = UpcloudStorageTypesEnum::DISK): self
    {

        return new self(
            uuid: Optional::create(),
            type: $type,
            size: $size,
            tier: $tier,
            title: $title,
            encrypted: $encrypted,
            zone: $zone,
            access: $access,
            state: Optional::create(),
            template_type: Optional::create(),
            backup_rule: Optional::create(),
            backups: Optional::create(),
            labels: Optional::create(),
            license: Optional::create(),
        )->except('access', 'state', 'license', 'template_type', 'uuid', 'type', 'labels', 'zone')
            ->additional([
                'action' => 'create',
            ]);
    }

    protected static function addOptionnals(array $data): array
    {
        $optionnals = collect(static::$optionnals)
            ->filter(function (string $value) use ($data) {
                return !Arr::has($data, $value);
            })
            ->mapWithKeys(function (string $value) {

                return [$value => Optional::create()];

            });

        return $data + $optionnals->toArray();
    }

    private function _access(): self
    {
        if (is_string($this->access)) {
            $this->access = UpcloudStorageAccessTypesEnum::from($this->access);
        }

        return $this;
    }

    private function _encrypted(): self
    {
        if (is_string($this->encrypted)) {
            $this->encrypted = Boolean::from($this->encrypted);
        }

        return $this;
    }

    private function _size(): self
    {
        if (is_int($this->size)) {
            $this->size = GigaByteUnit::from($this->size);
        }

        return $this;
    }

    private function _state(): self
    {
        if (is_string($this->state)) {
            $this->state = UpcloudStorageStatesEnum::from($this->state);
        }

        return $this;
    }

    private function _tier(): self
    {
        if (is_string($this->tier)) {
            $this->tier = UpcloudStorageTiersEnum::from($this->tier);
        }

        return $this;
    }

    private function _type(): self
    {
        if (is_string($this->type)) {
            $this->type = UpcloudStorageTypesEnum::from($this->type);
        }

        return $this;
    }
}
