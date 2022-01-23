<?php

namespace App\Containers\AppSection\Game\Providers;

use App\Ship\Parents\Providers\MainProvider;

/**
 * The Main Service Provider of this container, it will be automatically registered in the framework.
 */
class MainServiceProvider extends MainProvider
{
    /**
     * Container Service Providers.
     */
    public array $serviceProviders = [
    ];

    /**
     * Container Aliases
     */
    public array $aliases = [
    ];

    /**
     * Register anything in the container.
     */
    public function register(): void
    {
        parent::register();
    }
}
