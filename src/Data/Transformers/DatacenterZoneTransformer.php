<?php

namespace IBroStudio\Upcloud\Data\Transformers;

use IBroStudio\Contracts\Models\Hosting\DatacenterZone;
use IBroStudio\DataRepository\ValueObjects\ValueObject;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class DatacenterZoneTransformer implements Transformer
{
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): mixed
    {
        /** @var DatacenterZone $value */
        return $value->id;
    }
}
