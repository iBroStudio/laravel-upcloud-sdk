<?php

namespace IBroStudio\Upcloud\Actions\Servers\Templates;

use IBroStudio\Contracts\SDK\Hosting\HostingProviderSDK;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Lorisleiva\Actions\Concerns\AsAction;

class FetchServerTemplatesAction
{
    use AsAction;

    public function __construct(private HostingProviderSDK $provider) {}

    public function handle(): Collection|LazyCollection
    {
        return $this->provider->servers()->templates()->all();
    }
}
