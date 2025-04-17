<?php

namespace IBroStudio\Upcloud\Data;

use IBroStudio\Contracts\Data\Hosting\IPAddressData;
use IBroStudio\Contracts\Models\Hosting\DatacenterZone;
use IBroStudio\DataRepository\ValueObjects\Boolean;
use IBroStudio\Upcloud\Concerns\DataHasZone;
use IBroStudio\Upcloud\Enums\IpAccessEnum;
use IBroStudio\Upcloud\Enums\IpFamilyEnum;
use IBroStudio\Upcloud\Enums\IpReleasePolicyEnum;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpcloudIpAddressData extends Data implements IPAddressData
{
    use DataHasZone;

    public function __construct(
        public IpAccessEnum|string $access,
        public string $address,
        public IpFamilyEnum|string $family,
        public Boolean|string|null $floating,
        public ?string $ptr_record,
        public ?string $mac,
        public IpReleasePolicyEnum|string|null $release_policy,
        public Boolean|string|null $is_delegated,
        public DatacenterZone|string|Optional $zone,
    ) {
        $this
            ->_access()
            ->_delegated()
            ->_family()
            ->_floating()
            ->_release()
            ->_zone();
    }

    private function _access(): self
    {
        if (is_string($this->access)) {
            $this->access = IpAccessEnum::from($this->access);
        }

        return $this;
    }

    private function _delegated(): self
    {
        if (is_string($this->is_delegated)) {
            $this->is_delegated = Boolean::from($this->is_delegated);
        }

        return $this;
    }

    private function _family(): self
    {
        if (is_string($this->family)) {
            $this->family = IpFamilyEnum::from($this->family);
        }

        return $this;
    }

    private function _floating(): self
    {
        if (is_string($this->floating)) {
            $this->floating = Boolean::from($this->floating);
        }

        return $this;
    }

    private function _release(): self
    {
        if (is_string($this->release_policy)) {
            $this->release_policy = IpReleasePolicyEnum::from($this->release_policy);
        }

        return $this;
    }
}
