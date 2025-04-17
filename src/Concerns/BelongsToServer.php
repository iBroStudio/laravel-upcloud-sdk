<?php

namespace IBroStudio\Upcloud\Concerns;

use IBroStudio\Upcloud\Models\UpcloudServer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToServer
{
    public function server(): BelongsTo
    {
        return $this->belongsTo(UpcloudServer::class);
    }
}
