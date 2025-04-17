<?php

namespace IBroStudio\Upcloud\Database\Factories;

use IBroStudio\DataRepository\Enums\Timezones;
use IBroStudio\Upcloud\Enums\UpcloudServerStatesEnum;
use IBroStudio\Upcloud\Models\UpcloudServer;
use IBroStudio\Upcloud\Models\UpcloudDatacenterZone;
use IBroStudio\Upcloud\Models\UpcloudServerPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

class UpcloudServerFactory extends Factory
{
    protected $model = UpcloudServer::class;

    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'title' => $this->faker->word(),
            'hostname' => $this->faker->word(),
            'host' => $this->faker->randomNumber(),
            'core_number' => $this->faker->randomNumber(),
            'memory_amount' => $this->faker->randomNumber(),
            'plan_id' => $this->faker->randomElement(UpcloudServerPlan::pluck('id')->toArray()),
            'server_group' => $this->faker->uuid(),
            'zone_id' => $this->faker->randomElement(UpcloudDatacenterZone::public()->pluck('id')->toArray()),
            'timezone' => $this->faker->randomElement(Timezones::cases()),
            'firewall' => $this->faker->randomElement(['on', 'off']),
            'state' => $this->faker->randomElement(UpcloudServerStatesEnum::cases()),
        ];
    }
}
