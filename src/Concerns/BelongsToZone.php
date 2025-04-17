<?php

namespace IBroStudio\Upcloud\Concerns;

use IBroStudio\Upcloud\Models\UpcloudDatacenterZone;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToZone
{
    public function zone(): BelongsTo
    {
        return $this->belongsTo(UpcloudDatacenterZone::class);
    }
}
