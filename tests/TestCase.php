<?php

namespace IBroStudio\Upcloud\Tests;

use Bakame\Laravel\Pdp;
use IBroStudio\DataRepository\Commands\DataRepositoryInstallCommand;
use IBroStudio\DataRepository\DataRepositoryServiceProvider;
use IBroStudio\Upcloud\UpcloudServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as Orchestra;
use Saloon\Laravel\SaloonServiceProvider;
use Spatie\LaravelData\LaravelDataServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'IBroStudio\\Upcloud\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        Artisan::call(DataRepositoryInstallCommand::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            UpcloudServiceProvider::class,
            LaravelDataServiceProvider::class,
            SaloonServiceProvider::class,
            Pdp\ServiceProvider::class,
            DataRepositoryServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        foreach (File::allFiles(__DIR__.'/../database/migrations') as $migration) {
            (include $migration->getRealPath())->up();
        }
    }
}
