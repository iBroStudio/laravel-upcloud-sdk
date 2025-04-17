<?php

namespace IBroStudio\Upcloud\Database\Factories;

use IBroStudio\Upcloud\Database\Factories\Concerns\BelongsToServer;
use IBroStudio\Upcloud\Enums\IpAccessEnum;
use IBroStudio\Upcloud\Enums\IpFamilyEnum;
use IBroStudio\Upcloud\Enums\IpReleasePolicyEnum;
use IBroStudio\Upcloud\Models\IpAddress;
use IBroStudio\Upcloud\Models\UpcloudDatacenterZone;
use Illuminate\Database\Eloquent\Factories\Factory;

class IpAddressFactory extends Factory
{
    use BelongsToServer;

    protected $model = IpAddress::class;

    public function definition(): array
    {
        return [
            'address' => $this->faker->address(),
            'access' => $this->faker->randomElement(IpAccessEnum::cases()),
            'family' => $this->faker->randomElement(IpFamilyEnum::cases()),
            'floating' => $this->faker->randomElement(['on', 'off']),
            'ptr_record' => $this->faker->domainName(),
            'mac' => $this->faker->macAddress(),
            'release_policy' => $this->faker->randomElement(IpReleasePolicyEnum::cases()),
            'is_delegated' => $this->faker->randomElement(['on', 'off']),
            'zone_id' => $this->faker->randomElement(UpcloudDatacenterZone::public()->pluck('id')->toArray()),
        ];
    }
}
