<?php

namespace IBroStudio\Upcloud\Models;

use IBroStudio\Contracts\Models\Hosting\SshKey;
use IBroStudio\DataRepository\Concerns\HasDataRepository;
use IBroStudio\DataRepository\EloquentCasts\DataObjectCast;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property-read int $id
 *
 * @property string $name
 * @property-read \IBroStudio\DataRepository\ValueObjects\Authentication\SshKey $key
 * @property boolean $autodeploy
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Support\Carbon $deleted_at
 *
 * @method static \IBroStudio\Upcloud\Database\Factories\UpcloudSshKeyFactory<self> factory($count = null, $state = [])
 */
class UpcloudSshKey extends Model implements SshKey
{
    use HasDataRepository;
    use HasFactory;

    public $table = 'ssh_keys';

    protected $fillable = [
        'name',
        'key',
        'autodeploy',
    ];

    protected function casts(): array
    {
        return [
            'key' => DataObjectCast::class,
            'autodeploy' => 'boolean',
        ];
    }

    public function servers(): BelongsToMany
    {
        return $this->belongsToMany(
            UpcloudServer::class,
            'server_ssh_keys',
            'ssh_key_id',
            'server_id',
        );
    }

    #[Scope]
    protected function autodeployed(Builder $query): void
    {
        $query->where('autodeploy', true);
    }

    #[Scope]
    protected function forSshUser(Builder $query, string $sshUser): void
    {
        $query->whereHas('data_repository', function ($query) use ($sshUser) {
            $query->where('values->user', $sshUser);
        });
    }
}
