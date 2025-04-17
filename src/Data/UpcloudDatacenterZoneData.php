<?php

namespace IBroStudio\Upcloud\Data;

use IBroStudio\Contracts\Data\Hosting\DatacenterZoneData;
use IBroStudio\DataRepository\Transformers\ValueObjectTransformer;
use IBroStudio\DataRepository\ValueObjects\Boolean;
use IBroStudio\Upcloud\SDK\UpcloudResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Saloon\Http\Response;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

class UpcloudDatacenterZoneData extends Data implements DatacenterZoneData
{
    public function __construct(
        public string $id,
        public string $description,
        #[WithTransformer(ValueObjectTransformer::class)]
        public Boolean|string $public,
    ) {
        $this
            ->_public();
    }

    public static function collectFromUpcloud(UpcloudResponse|Response $response): Collection
    {
        return collect(
            Arr::map($response->json()['zones']['zone'], function (array $data) {
                return new self(...$data);
            })
        );
    }

    private function _public(): self
    {
        if (is_string($this->public)) {
            $this->public = Boolean::from($this->public);
        }

        return $this;
    }
}
