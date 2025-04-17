<?php

namespace IBroStudio\Upcloud\Database\Factories;

use IBroStudio\Upcloud\Database\Factories\Concerns\BelongsToServer;
use IBroStudio\Upcloud\Enums\UpcloudStorageStatesEnum;
use IBroStudio\Upcloud\Enums\UpcloudStorageTiersEnum;
use IBroStudio\Upcloud\Models\Storage;
use IBroStudio\Upcloud\Models\UpcloudDatacenterZone;
use Illuminate\Database\Eloquent\Factories\Factory;

class StorageFactory extends Factory
{
    use BelongsToServer;

    protected $model = Storage::class;

    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'title' => $this->faker->word(),
            'size' => $this->faker->randomNumber(),
            'tier' => $this->faker->randomElement(UpcloudStorageTiersEnum::cases()),
            'zone_id' => $this->faker->randomElement(UpcloudDatacenterZone::public()->pluck('id')->toArray()),
            'state' => $this->faker->randomElement(UpcloudStorageStatesEnum::cases()),
        ];
    }
}
