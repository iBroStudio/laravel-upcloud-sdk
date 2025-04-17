<?php

namespace IBroStudio\Upcloud\Data;

use IBroStudio\Contracts\Data\Hosting\ServerPlanData;
use IBroStudio\Contracts\Enums\Hosting\ServerPlanCategoriesEnum;
use IBroStudio\DataRepository\Enums\ByteUnitEnum;
use IBroStudio\DataRepository\Transformers\ValueObjectTransformer;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\GigaByteUnit;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\MegaByteUnit;
use IBroStudio\Upcloud\Enums\UpcloudServerPlanCategoriesEnum;
use IBroStudio\Upcloud\SDK\UpcloudResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Saloon\Http\Response;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Computed;

class UpcloudServerPlanData extends Data implements ServerPlanData
{
    #[Computed]
    public ServerPlanCategoriesEnum $category;

    public function __construct(
        public string $name,
        public int $core_number,
        #[WithTransformer(ValueObjectTransformer::class)]
        public MegaByteUnit|int $memory_amount,
        public ?string $storage_tier,
        #[WithTransformer(ValueObjectTransformer::class)]
        public GigaByteUnit|int $storage_size,
        #[WithTransformer(ValueObjectTransformer::class)]
        public GigaByteUnit|int $public_traffic_out,
    ) {
        $this
            ->_category()
            ->_memory()
            ->_storage()
            ->_traffic();
    }

    public static function fromUpcloud(UpcloudResponse $response): self
    {
        return new self(...$response->json()['plans']);
    }

    public static function collectFromUpcloud(UpcloudResponse|Response $response): Collection
    {
        return collect(
            Arr::map($response->json()['plans']['plan'], function (array $data) {
                return new self(...$data);
            })
        );
    }

    private function _category(): self
    {
        $guessCategory = Str::of($this->name)
            ->before('-')
            ->lower()
            ->value();

        $this->category = UpcloudServerPlanCategoriesEnum::tryFrom($guessCategory)
            ?? UpcloudServerPlanCategoriesEnum::STANDARD;

        return $this;
    }

    private function _memory(): self
    {
        if (is_int($this->memory_amount)) {
            $this->memory_amount = MegaByteUnit::from($this->memory_amount);
        }

        return $this;
    }

    private function _storage(): self
    {
        if (is_int($this->storage_size)) {
            $this->storage_size = GigaByteUnit::from($this->storage_size);
        }

        return $this;
    }

    private function _traffic(): self
    {
        if (is_int($this->public_traffic_out)) {
            $this->public_traffic_out = GigaByteUnit::from($this->public_traffic_out);
        }

        return $this;
    }
}
