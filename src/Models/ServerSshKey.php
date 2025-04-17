<?php

namespace IBroStudio\Upcloud\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ServerSshKey extends Pivot
{
    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'server_id',
        'ssh_key_id',
    ];
}
