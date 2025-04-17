<?php

namespace IBroStudio\Upcloud\Jobs;

use IBroStudio\Upcloud\Contracts\UpcloudServer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WatchServerCreationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public UpcloudServer $server,
    ) {}

    public function handle(): void {}
}
