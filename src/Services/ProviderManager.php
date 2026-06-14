<?php

namespace SanRatul\ShopAssist\Services;

use Closure;
use http\Exception\InvalidArgumentException;
use Illuminate\Contracts\Container\Container;
use SanRatul\ShopAssist\Contracts\AIProvider;

class ProviderManager
{
    /**
     * The application container instance.
     */
    protected Container $app;

    /**
     * The resolved driver instances.
     *
     * @var array<string, AIProvider>
     */
    protected array $drivers = [];

    /**
     * The custom driver creators.
     *
     * @var array<string, Closure>
     */
    protected array $customCreators = [];

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function driver(?string $name = null): AiProvider
    {
        $name ??= $this->getDefaultDriver();

        return $this->drivers[$name] ??= $this->resolve($name);
    }

    public function extend(string $name, Closure $resolver): static
    {
        $this->customCreators[$name] = $resolver;

        return $this;
    }

    public function getDefaultDriver(): string
    {
        return config('shopassist.default_provider');
    }

    public function forgetDrivers(): static
    {
        $this->drivers = [];

        return $this;
    }

    public function resolve(string $name): AIProvider
    {
        if (! empty($this->customCreators[$name])) {
            return ($this->customCreators[$name])($this->app);
        }

        $method = 'create' . ucfirst($name) . 'Driver';

        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        throw new InvalidArgumentException(
            "Driver [{$name}] is not supported."
        );
    }

}