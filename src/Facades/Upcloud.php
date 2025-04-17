<?php

namespace IBroStudio\Upcloud\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \IBroStudio\Upcloud\Upcloud
 */
class Upcloud extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \IBroStudio\Upcloud\Upcloud::class;
    }
}
