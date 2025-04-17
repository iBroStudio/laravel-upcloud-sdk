<?php

namespace IBroStudio\Upcloud\Models;

use IBroStudio\DataRepository\EloquentCasts\ValueObjectCast;
use IBroStudio\DataRepository\ValueObjects\Boolean;
use IBroStudio\DataRepository\ValueObjects\Domain;
use IBroStudio\Upcloud\Concerns\BelongsToServer;
use IBroStudio\Upcloud\Concerns\BelongsToZone;
use IBroStudio\Upcloud\Enums\IpAccessEnum;
use IBroStudio\Upcloud\Enums\IpFamilyEnum;
use IBroStudio\Upcloud\Enums\IpReleasePolicyEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpAddress extends Model
{
    use BelongsToServer;
    use BelongsToZone;
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'address',
        'access',
        'family',
        'floating',
        'ptr_record',
        'mac',
        'release_policy',
        'is_delegated',
        'zone_id',
        'server_id',
    ];

    protected function casts(): array
    {
        return [
            'access' => IpAccessEnum::class,
            'family' => IpFamilyEnum::class,
            'floating' => ValueObjectCast::class.':'.Boolean::class,
            'ptr_record' => ValueObjectCast::class.':'.Domain::class,
            'release_policy' => IpReleasePolicyEnum::class,
            'is_delegated' => ValueObjectCast::class.':'.Boolean::class,
        ];
    }
}
