<?php

namespace IBroStudio\Upcloud\Models;

use IBroStudio\Contracts\Models\Hosting\DatacenterZone;
use IBroStudio\Upcloud\Actions\DatacenterZones\FetchDatacenterZones;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Sushi\Sushi;

class UpcloudDatacenterZone extends Model implements DatacenterZone
{
    use Sushi;

    protected $keyType = 'string';

    protected $schema = [
        'public' => 'boolean',
    ];

    public function getRows()
    {
        return Cache::remember('datacenter_zones', now()->addMonth(), function () {
            return FetchDatacenterZones::run()->toArray();
        });
    }

    #[Scope]
    protected function public(Builder $query): void
    {
        $query->where('public', true);
    }

    #[Scope]
    protected function default(Builder $query): void
    {
        $query->where('id', config('upcloud-sdk.default.datacenter'));
    }
}
