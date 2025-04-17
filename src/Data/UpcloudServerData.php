<?php

namespace IBroStudio\Upcloud\Data;

use Carbon\Carbon;
use DateTime;
use IBroStudio\Contracts\Data\Hosting\ServerData;
use IBroStudio\Contracts\Enums\Hosting\BootOrderValuesEnum;
use IBroStudio\Contracts\Enums\Hosting\FirewallStatesEnum;
use IBroStudio\Contracts\Enums\Hosting\RemoteAccessEnabledEnum;
use IBroStudio\Contracts\Enums\Hosting\ServerStatesEnum;
use IBroStudio\Contracts\Models\Hosting\DatacenterZone;
use IBroStudio\Contracts\Models\Hosting\ServerPlan;
use IBroStudio\DataRepository\Enums\Timezones;
use IBroStudio\DataRepository\Transformers\ValueObjectTransformer;
use IBroStudio\DataRepository\ValueObjects\Boolean;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\GigaByteUnit;
use IBroStudio\DataRepository\ValueObjects\Uuid;
use IBroStudio\Upcloud\Concerns\DataHasUuid;
use IBroStudio\Upcloud\Concerns\DataHasZone;
use IBroStudio\Upcloud\Data\Transformers\GigaByteUnitTransformer;
use IBroStudio\Upcloud\Enums\UpcloudBootOrderValuesEnum;
use IBroStudio\Upcloud\Enums\UpcloudFirewallStatesEnum;
use IBroStudio\Upcloud\Enums\UpcloudRemoteAccessEnabledEnum;
use IBroStudio\Upcloud\Enums\UpcloudServerStatesEnum;
use IBroStudio\Upcloud\Models\UpcloudServerPlan;
use IBroStudio\Upcloud\SDK\UpcloudResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Saloon\Http\Response;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpcloudServerData extends Data implements ServerData
{
    use DataHasUuid;
    use DataHasZone;

    public function __construct(
        public int $core_number,
        public DateTime|int $created,
        public int $host,
        public string $hostname,
        public array $labels,
        public float $license,
        #[WithTransformer(ValueObjectTransformer::class)]
        public GigaByteUnit|int $memory_amount,
        public ServerPlan|string $plan,
        public int $plan_ipv4_bytes,
        public int $plan_ipv6_bytes,
        public ?string $server_group,
        public string $simple_backup,
        public ServerStatesEnum|string $state,
        public array $tags,
        public string $title,
        #[WithTransformer(ValueObjectTransformer::class)]
        public Uuid|string|Optional $uuid,
        public DatacenterZone|string|Optional $zone,
        public Boolean|string $metadata,
        public BootOrderValuesEnum|string|null $boot_order = null,
        public FirewallStatesEnum|string|null $firewall = null,
        /** @var Collection<int, UpcloudIpAddressData> */
        public Collection|array|null $ip_addresses = null,
        public ?array $networking = null,
        public ?string $nic_model = null,
        public RemoteAccessEnabledEnum|string|null $remote_access_enabled = null,
        public ?string $remote_access_password = null,
        public ?string $remote_access_type = null,
        /** @var Collection<int, UpcloudStorageData> */
        public Collection|array|null $storage_devices = null,
        #[WithTransformer(ValueObjectTransformer::class)]
        public Timezones|string|null $timezone = null,
        public ?string $video_model = null,
    ) {
        $this
            ->_boot_order()
            ->_created()
            ->_firewall()
            ->_ip_addresses()
            ->_memory()
            ->_plan()
            ->_remote_access()
            ->_storage()
            ->_state()
            ->_timezone()
            ->_uuid()
            ->_zone();
    }

    public static function fromUpcloud(UpcloudResponse $response): self
    {
        return new self(...Arr::except($response->json()['server'], ['progress']));

        return new self(...$response->json()['server']);
    }

    public static function collectFromUpcloud(UpcloudResponse|Response $response): Collection
    {
        return collect(
            Arr::map($response->json()['servers']['server'], function (array $data) {
                return new self(...$data);
            })
        );
    }

    private function _boot_order(): self
    {
        if (is_string($this->boot_order)) {
            $this->boot_order = UpcloudBootOrderValuesEnum::from($this->boot_order);
        }

        return $this;
    }

    private function _created(): self
    {
        if (is_int($this->created)) {
            $this->created = Carbon::createFromTimestamp($this->created);
        }

        return $this;
    }

    private function _firewall(): self
    {
        if (is_string($this->firewall)) {
            $this->firewall = UpcloudFirewallStatesEnum::from($this->firewall);
        }

        return $this;
    }

    private function _ip_addresses(): self
    {
        if (is_array($this->ip_addresses)) {
            $this->ip_addresses =
                UpcloudIpAddressData::collect(
                    collect($this->ip_addresses['ip_address'])
                        ->map(function (array $item) {
                            $item['zone'] = $this->zone;

                            return $item;
                        }),
                    Collection::class
                );
        }

        return $this;
    }

    private function _memory(): self
    {
        if (is_int($this->memory_amount)) {
            $this->memory_amount = GigaByteUnit::from($this->memory_amount);
        }

        return $this;
    }

    private function _plan(): self
    {
        if (is_string($this->plan)) {
            $this->plan = UpcloudServerPlan::where('name', $this->plan)->first();
        }

        return $this;
    }

    private function _remote_access(): self
    {
        if (is_string($this->remote_access_enabled)) {
            $this->remote_access_enabled = UpcloudRemoteAccessEnabledEnum::from($this->remote_access_enabled);
        }

        return $this;
    }

    private function _storage(): self
    {
        if (is_array($this->storage_devices)) {
            $this->storage_devices =
                UpcloudStorageData::collect(
                    collect($this->storage_devices['storage_device'])
                        ->map(function (array $item) {
                            $item['zone'] = $this->zone;
                            return $item;
                        }),
                    Collection::class
                );
        }

        return $this;
    }

    private function _state(): self
    {
        if (is_string($this->state)) {
            $this->state = UpcloudServerStatesEnum::from($this->state);
        }

        return $this;
    }

    private function _timezone(): self
    {
        if (is_string($this->timezone)) {
            $this->timezone = Timezones::from($this->timezone);
        }

        return $this;
    }
}
