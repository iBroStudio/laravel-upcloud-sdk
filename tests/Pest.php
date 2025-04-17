<?php

use IBroStudio\Upcloud\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Saloon\Config;

Config::preventStrayRequests();

pest()
    ->extends(
        TestCase::class,
        RefreshDatabase::class,
    )
    ->in(__DIR__);
