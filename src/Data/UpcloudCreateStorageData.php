<?php

namespace IBroStudio\Upcloud\Data;

use IBroStudio\Contracts\Data\Hosting\StorageData;
use IBroStudio\DataRepository\Transformers\ValueObjectTransformer;
use IBroStudio\DataRepository\ValueObjects\Boolean;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\GigaByteUnit;
use IBroStudio\DataRepository\ValueObjects\Uuid;
use IBroStudio\Upcloud\Enums\UpcloudStorageTiersEnum;
use IBroStudio\Upcloud\Enums\ZonesEnum;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

class UpcloudCreateStorageData extends Data implements StorageData
{
    public function __construct(
        public string                  $title,
        #[WithTransformer(ValueObjectTransformer::class)]
        public GigaByteUnit            $size,
        public UpcloudStorageTiersEnum $tier,
        public ZonesEnum               $zone,
        public Boolean                 $encrypted, // no
        public ?string                 $action, // clone
        public ?string                 $storage, // template uuid
    ) {}
}
