<?php

namespace IBroStudio\Upcloud\Models;

use IBroStudio\DataRepository\EloquentCasts\ValueObjectCast;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\GigaByteUnit;
use IBroStudio\DataRepository\ValueObjects\Uuid;
use IBroStudio\Upcloud\Concerns\BelongsToServer;
use IBroStudio\Upcloud\Concerns\BelongsToZone;
use IBroStudio\Upcloud\Enums\UpcloudStorageStatesEnum;
use IBroStudio\Upcloud\Enums\UpcloudStorageTiersEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use BelongsToServer;
    use BelongsToZone;
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'title',
        'size',
        'tier',
        'zone_id',
        'state',
        'server_id',
    ];

    protected function casts(): array
    {
        return [
            'uuid' => ValueObjectCast::class.':'.Uuid::class,
            'size' => ValueObjectCast::class.':'.GigaByteUnit::class,
            'tier' => UpcloudStorageTiersEnum::class,
            'state' => UpcloudStorageStatesEnum::class,
        ];
    }
}
