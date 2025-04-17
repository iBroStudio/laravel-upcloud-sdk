<?php

namespace IBroStudio\Upcloud\Data;

use IBroStudio\Contracts\Data\Hosting\SshKeyValueData;
use IBroStudio\Contracts\Enums\Hosting\StorageTiersEnum;
use IBroStudio\Contracts\Models\Hosting\DatacenterZone;
use IBroStudio\Contracts\Models\Hosting\SshKey;
use IBroStudio\DataRepository\ValueObjects\Boolean;
use IBroStudio\DataRepository\ValueObjects\EncryptableText;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\GigaByteUnit;
use IBroStudio\Upcloud\Concerns\DataHasZone;
use IBroStudio\Upcloud\Enums\IpAccessEnum;
use IBroStudio\Upcloud\Enums\IpFamilyEnum;
use IBroStudio\Upcloud\Enums\IpReleasePolicyEnum;
use IBroStudio\Upcloud\Models\UpcloudServerTemplate;
use IBroStudio\Upcloud\Models\UpcloudSshKey;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpcloudSshUserData extends Data
{
    public function __construct(
        public string $username,
        public array $ssh_keys,
    ) {}

    public static function forSshUser(string $username): self
    {
        $sshKeys = UpcloudSshKey::forSshUser('upclouduser')->get();

        $keys = $sshKeys->map(function (UpcloudSshKey $sshKey) {
            return $sshKey->key->public->decrypt();
        });

        return new self(
            username: $username,
            ssh_keys: ['ssh_key' => $keys->toArray()],
        );
    }
}
