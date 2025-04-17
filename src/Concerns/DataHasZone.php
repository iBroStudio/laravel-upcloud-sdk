<?php

namespace IBroStudio\Upcloud\Concerns;

use IBroStudio\Contracts\Models\Hosting\DatacenterZone;
use IBroStudio\Upcloud\Models\UpcloudDatacenterZone;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Optional;

trait DataHasZone
{
    public DatacenterZone|string|Optional $zone;

    private function _zone(): self
    {
        if (is_string($this->zone)) {
            $this->zone = UpcloudDatacenterZone::whereId($this->zone)->first();
        }

        return $this;
    }
}
