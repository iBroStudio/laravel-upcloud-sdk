<?php

namespace IBroStudio\Upcloud\Models;

use IBroStudio\DataRepository\EloquentCasts\ValueObjectCast;
use IBroStudio\DataRepository\Enums\Timezones;
use IBroStudio\DataRepository\ValueObjects\Boolean;
use IBroStudio\DataRepository\ValueObjects\Units\Byte\GigaByteUnit;
use IBroStudio\DataRepository\ValueObjects\Uuid;
use IBroStudio\Upcloud\Concerns\BelongsToZone;
use IBroStudio\Upcloud\Enums\UpcloudServerStatesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UpcloudServer extends Model
{
    use BelongsToZone;
    use HasFactory;

    public $table = 'servers';

    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'title',
        'hostname',
        'host',
        'core_number',
        'memory_amount',
        'plan_id',
        'server_group',
        'zone_id',
        'timezone',
        'firewall',
        'state',
    ];

    protected function casts(): array
    {
        return [
            'uuid' => ValueObjectCast::class.':'.Uuid::class,
            'memory_amount' => ValueObjectCast::class.':'.GigaByteUnit::class,
            'server_group' => ValueObjectCast::class.':'.Uuid::class,
            'timezone' => Timezones::class,
            'firewall' => ValueObjectCast::class.':'.Boolean::class,
            'state' => UpcloudServerStatesEnum::class,
        ];
    }

    public function ipAddresses(): HasMany
    {
        return $this->hasMany(IpAddress::class, 'server_id');
    }

    public function storages(): HasMany
    {
        return $this->hasMany(Storage::class, 'server_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(UpcloudServerPlan::class, 'server_id');
    }

    public function ssh_keys(): BelongsToMany
    {
        return $this->belongsToMany(
            UpcloudSshKey::class,
            'server_ssh_keys',
            'server_id',
            'ssh_key_id'
        );
    }
}
