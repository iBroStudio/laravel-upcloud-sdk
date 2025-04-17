<?php

namespace IBroStudio\Upcloud\Events;

use IBroStudio\Upcloud\Models\UpcloudServer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServerCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public UpcloudServer $server,
    )
    {
    }
}
