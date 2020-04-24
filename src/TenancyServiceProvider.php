<?php

namespace Statch\Tenancy;

use Illuminate\Support\ServiceProvider;
use Statch\Tenancy\Contracts\Tenant as TenantContract;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;

class TenancyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/statch-tenancy.php', 'statch-tenancy');

        $manager = new TenantManager();

        $this->app->instance(TenantManager::class, $manager);
        $this->app->bind(TenantContract::class, config('statch-tenancy.model'));
    }

    /**
     * Perform post-registration booting of services.
     */
    public function boot(Filesystem $filesystem)
    {
         // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/statch-tenancy.php' => config_path('statch-tenancy.php'),
        ], 'config');



        $this->publishes([
            __DIR__.'/../database/migrations/create_tenants_table.php.stub' => $this->getMigrationFileName($filesystem),
        ], 'migrations');

    }

      /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param Filesystem $filesystem
     * @return string
     */
    protected function getMigrationFileName(Filesystem $filesystem): string
    {
        $timestamp = date('Y_m_d_His');
        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem) {
                return $filesystem->glob($path.'*_create_tenants_table.php');
            })->push($this->app->databasePath()."/migrations/{$timestamp}_create_tenants_table.php")
            ->first();
    }
}
