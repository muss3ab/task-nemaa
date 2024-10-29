<?php

namespace App\Services\DataProviders;

class DataProviderFactory
{
    private array $providers = [];

    public function __construct()
    {
        $this->registerProvider(new DataProviderX());
        $this->registerProvider(new DataProviderY());
    }

    public function registerProvider(DataProviderInterface $provider): void
    {
        $this->providers[$provider->getName()] = $provider;
    }

    public function getProvider(string $name): DataProviderInterface
    {
        if (!isset($this->providers[$name])) {
            throw new \InvalidArgumentException("Provider {$name} not found");
        }
        
        return $this->providers[$name];
    }

    public function getAllProviders(): array
    {
        return $this->providers;
    }
}
