<?php

namespace IBroStudio\Upcloud\Commands;

use Illuminate\Console\Command;

class UpcloudCommand extends Command
{
    public $signature = 'laravel-upcloud-sdk';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
