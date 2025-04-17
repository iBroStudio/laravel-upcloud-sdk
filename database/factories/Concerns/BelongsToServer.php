<?php

namespace IBroStudio\Upcloud\Database\Factories\Concerns;

use IBroStudio\Upcloud\Models\UpcloudServer;

trait BelongsToServer
{
    public function withServer(): static
    {
        return $this->state(fn (array $attributes) => [
            'server_id' => UpcloudServer::factory(),
        ]);
    }
}
