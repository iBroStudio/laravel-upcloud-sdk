<?php

namespace IBroStudio\Upcloud\Concerns;

use IBroStudio\DataRepository\ValueObjects\Uuid;
use Spatie\LaravelData\Optional;

trait DataHasUuid
{
    public Uuid|string|Optional $uuid;

    private function _uuid(): self
    {
        if (is_string($this->uuid)) {
            $this->uuid = Uuid::from($this->uuid);
        }

        return $this;
    }
}
