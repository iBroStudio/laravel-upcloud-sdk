<?php

namespace IBroStudio\Upcloud\Data;

use IBroStudio\Contracts\Data\Hosting\SshKeyValueData;
use IBroStudio\Contracts\Models\Hosting\DatacenterZone;
use IBroStudio\DataRepository\ValueObjects\Boolean;
use IBroStudio\DataRepository\ValueObjects\EncryptableText;
use IBroStudio\Upcloud\Concerns\DataHasZone;
use IBroStudio\Upcloud\Enums\IpAccessEnum;
use IBroStudio\Upcloud\Enums\IpFamilyEnum;
use IBroStudio\Upcloud\Enums\IpReleasePolicyEnum;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpcloudSshKeyValueData extends Data implements SshKeyValueData
{
    public function __construct(
        public string $user,
        public EncryptableText|string $data,
    ) {
        $this
            ->_encrypt();
    }

    private function _encrypt(): self
    {
        if (is_string($this->data)) {
            $this->data = EncryptableText::from($this->data);
        }

        return $this;
    }
}
