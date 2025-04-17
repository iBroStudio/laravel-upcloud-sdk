<?php

namespace IBroStudio\Upcloud;

use IBroStudio\Contracts\SDK\Hosting\HostingProviderSDK;
use IBroStudio\Upcloud\SDK\UpcloudSDK;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class UpcloudServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-upcloud-sdk')
            ->hasConfigFile();
    }

    public function packageRegistered()
    {
        $this->app->singleton(
            abstract: HostingProviderSDK::class,
            concrete: fn () => new UpcloudSDK(
                username: config('upcloud-sdk.api_username'),
                password: config('upcloud-sdk.api_password'),
            )
        );
    }
}
